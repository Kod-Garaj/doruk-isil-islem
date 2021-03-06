<?php

namespace App\Http\Controllers;

use App\Models\Firmalar;
use App\Models\Islemler;
use App\Models\IslemTurleri;
use App\Models\Malzemeler;
use App\Models\SiparisDurumlari;
use App\Models\Siparisler;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SiparisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('siparis-formu');
    }

    public function siparisler(Request $request)
    {
        try
        {
            $filtrelemeler = json_decode($request->filtreleme ?? "[]", true);

            $sayfalamaSayisi = $request->sayfalamaSayisi ?? 10;
            $firmaTabloAdi = (new Firmalar())->getTable();
            $siparisDurumTabloAdi = (new SiparisDurumlari())->getTable();
            $siparisTabloAdi = (new Siparisler())->getTable();
            $islemTabloAdi = (new Islemler())->getTable();

            $siparisler = Siparisler::select(DB::raw("
                    $siparisTabloAdi.id as siparisId,
                    $siparisTabloAdi.ad as siparisAdi,
                    $siparisTabloAdi.irsaliyeNo,
                    $siparisTabloAdi.siparisNo,
                    $siparisTabloAdi.tarih,
                    $siparisTabloAdi.tutar,
                    $siparisTabloAdi.firmaId,
                    $siparisTabloAdi.durumId,
                    $siparisTabloAdi.terminSuresi,
                    $siparisTabloAdi.aciklama,
                    $siparisDurumTabloAdi.ad as siparisDurumAdi,
                    $firmaTabloAdi.firmaAdi,
                    $firmaTabloAdi.sorumluKisi,
                    COUNT(IF($islemTabloAdi.deleted_at IS NULL, $islemTabloAdi.id, NULL)) as islemSayisi
                "))
                ->join($firmaTabloAdi, $firmaTabloAdi . '.id', '=', $siparisTabloAdi . '.firmaId')
                ->join($siparisDurumTabloAdi, $siparisDurumTabloAdi . '.id', '=', $siparisTabloAdi . '.durumId')
                ->leftJoin($islemTabloAdi, $islemTabloAdi . '.siparisId', '=', $siparisTabloAdi . '.id')
                ->groupBy(
                    $siparisTabloAdi . '.id',
                    $siparisTabloAdi . '.ad',
                    $siparisTabloAdi . '.irsaliyeNo',
                    $siparisTabloAdi . '.siparisNo',
                    $siparisTabloAdi . '.tarih',
                    $siparisTabloAdi . '.tutar',
                    $siparisTabloAdi . '.firmaId',
                    $siparisTabloAdi . '.durumId',
                    $siparisTabloAdi . '.terminSuresi',
                    $siparisTabloAdi . '.aciklama',
                    $siparisDurumTabloAdi . '.ad',
                    $firmaTabloAdi . '.firmaAdi',
                    $firmaTabloAdi . '.sorumluKisi'
                )
                ->orderBy($siparisTabloAdi . '.created_at', 'desc');

            if (isset($filtrelemeler["termin"]) && $filtrelemeler["termin"] > 0)
            {
                $tarih = Carbon::now()->subDays($filtrelemeler["termin"])->format('Y-m-d');

                $siparisler = $siparisler->where("$siparisTabloAdi.tarih", "<=", $tarih);
            }

            if (isset($filtrelemeler["arama"]) && $filtrelemeler["arama"] != "")
            {
                // Sipari?? no, firma ad??, irsaliye no
                $siparisler = $siparisler->where(function ($query) use ($filtrelemeler, $siparisTabloAdi, $firmaTabloAdi) {
                    $query->where($siparisTabloAdi . '.siparisNo', 'like', '%' . $filtrelemeler["arama"] . '%')
                        ->orWhere($firmaTabloAdi . '.firmaAdi', 'like', '%' . $filtrelemeler["arama"] . '%')
                        ->orWhere($siparisTabloAdi . '.irsaliyeNo', 'like', '%' . $filtrelemeler["arama"] . '%');
                });
            }

            if (isset($filtrelemeler["firma"]) && $filtrelemeler["firma"] && count($filtrelemeler["firma"]) > 0)
            {
                $firmaIdleri = array_column($filtrelemeler["firma"], "id");

                $siparisler = $siparisler->whereIn("$firmaTabloAdi.id", $firmaIdleri);
            }

            if (isset($filtrelemeler["baslangicTarihi"]) && $filtrelemeler["baslangicTarihi"] != "")
            {
                $siparisler = $siparisler->where("$siparisTabloAdi.tarih", ">=", $filtrelemeler["baslangicTarihi"]);
            }

            if (isset($filtrelemeler["bitisTarihi"]) && $filtrelemeler["bitisTarihi"] != "")
            {
                $siparisler = $siparisler->where("$siparisTabloAdi.tarih", "<=", $filtrelemeler["bitisTarihi"]);
            }

            $siparisler = $siparisler->paginate($sayfalamaSayisi)->toArray();

            foreach ($siparisler["data"] as &$siparis)
            {
                $terminBilgileri = $this->terminHesapla($siparis["tarih"], $siparis["terminSuresi"]);

                $siparis["gecenSure"] = $terminBilgileri["gecenSure"];
                $siparis["gecenSureRenk"] = $terminBilgileri["gecenSureRenk"];
            }

            return response()->json([
                'durum' => true,
                'mesaj' => 'Sipari??ler ba??ar??yla getirildi.',
                'siparisler' => $siparisler
            ]);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'durum' => false,
                'mesaj' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Son sipari?? ve irsaliye numaras??n??n bir sonraki numaras??n?? d??nd??r??r.
     * ??rnek: DRK000001 -> DRK000002
     */
    public function numaralariGetir(Request $request)
    {
        try
        {
            $siparisNo = Siparisler::max('siparisNo');

            if(!$siparisNo)
            {
                $siparisNo = 'DRK0000001';
            }
            else
            {
                $siparisNo = substr($siparisNo, 3);
                $siparisNo = 'DRK' . sprintf('%07d', $siparisNo + 1);
            }

            $irsaliyeNo = Siparisler::max('irsaliyeNo');

            if(!$irsaliyeNo)
            {
                $irsaliyeNo = 'IR0000001';
            }
            else
            {
                $irsaliyeNo = substr($irsaliyeNo, 2);
                $irsaliyeNo = 'IR' . sprintf('%07d', $irsaliyeNo + 1);
            }

            return response()->json([
                'durum' => true,
                'mesaj' => 'Sipari?? ba??ar??yla getirildi.',
                'numaralar' => [
                    "siparisNo" => $siparisNo,
                    "irsaliyeNo" => $irsaliyeNo
                ]
            ]);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'durum' => false,
                'mesaj' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @global
     */
    public function siparisDurumlariGetir()
    {
        try
        {
            $siparisDurumlari = SiparisDurumlari::all();

            return response()->json([
                'durum' => true,
                'mesaj' => 'Sipari?? durumlar?? ba??ar??yla getirildi.',
                'siparisDurumlari' => $siparisDurumlari
            ]);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'durum' => false,
                'mesaj' => $e->getMessage()
            ], 500);
        }
    }

    public function siparisKaydet(Request $request)
    {
        DB::beginTransaction();

        try
        {
            $siparisBilgileri = $request->siparis;
            $userId = Auth::user()->id;
            // dd($siparisBilgileri);

            if (isset($siparisBilgileri['siparisId']))
            {
                $siparis = Siparisler::find($siparisBilgileri['siparisId']);

                if ($siparis->siparisNo != $siparisBilgileri['siparisNo'] && Siparisler::where('siparisNo', $siparisBilgileri['siparisNo'])->count() > 0)
                {
                    return response()->json([
                        'durum' => false,
                        'mesaj' => 'Bu sipari?? numaras?? zaten kullan??l??yor.',
                        "hataKodu" => "SK001"
                    ]);
                }
            }
            else
            {
                $siparis = new Siparisler();

                if (Siparisler::where('siparisNo', $siparisBilgileri['siparisNo'])->count() > 0)
                {
                    return response()->json([
                        'durum' => false,
                        'mesaj' => 'Bu sipari?? numaras?? zaten kullan??l??yor.',
                        "hataKodu" => "SK002"
                    ]);
                }
            }

            // dd($siparis);

            $siparis->firmaId = $siparisBilgileri['firma']["id"];
            $siparis->userId = $userId;
            $siparis->durumId = $siparisBilgileri['siparisDurumu']["id"];
            $siparis->ad = $siparisBilgileri['siparisAdi'];
            $siparis->siparisNo = $siparisBilgileri['siparisNo'];
            $siparis->irsaliyeNo = $siparisBilgileri['irsaliyeNo'];
            $siparis->aciklama = $siparisBilgileri['aciklama'] ?? null;
            $siparis->tarih = $siparisBilgileri['tarih'];
            $siparis->tutar = $siparisBilgileri['tutar'] ?? null;
            $siparis->terminSuresi = $siparisBilgileri['terminSuresi'] ?? 5;

            if (!$siparis->save())
            {
                DB::rollBack();

                return response()->json([
                    'durum' => false,
                    'mesaj' => 'Sipari?? kaydedilirken bir hata olu??tu.',
                    'hata' => $siparis->getErrors(),
                    "hataKodu" => "S001"
                ], 500);
            }

            foreach ($siparisBilgileri['islemler'] as $key => $islem)
            {
                if (isset($islem['id']))
                {
                    $islemModel = Islemler::find($islem['id']);
                }
                else
                {
                    $islemModel = new Islemler();
                }

                // dd($islemModel->siparisId, $siparisIslemleri, $siparis->id);

                $islemModel->siparisId = $siparis->id;
                $islemModel->malzemeId = $islem['malzeme']["id"] ?? null;
                $islemModel->islemTuruId = $islem['yapilacakIslem']["id"] ?? null;
                $islemModel->durumId = $islem['islemDurumu']["id"] ?? null;
                $islemModel->siraNo = $key + 1;
                $islemModel->adet = $islem['adet'];
                $islemModel->miktar = $islem['miktar'];
                $islemModel->dara = $islem['dara'];
                $islemModel->birimFiyat = $islem['birimFiyat'];
                $islemModel->kalite = $islem['kalite'];
                $islemModel->istenilenSertlik = $islem['istenilenSertlik'];
                $islemModel->json = $islem['json'] ?? null;

                if (isset($islem["yeniResimSecildi"], $islem["resim"]) && $islem["yeniResimSecildi"] && $islem["resim"])
                {
                    if (isset($islem["resimYolu"]) && $islem["resimYolu"])
                    {
                        $this->dosyaSil($islem["resimYolu"]);
                    }

                    $resimYolu = $this->base64ResimKaydet($islem["resim"], [
                        "dosyaAdi" => "$siparis->siparisNo-$islemModel->siraNo"
                    ]);

                    if (!$resimYolu)
                    {
                        DB::rollBack();

                        return response()->json([
                            'durum' => false,
                            'mesaj' => 'Resim kaydedilirken bir hata olu??tu.',
                            "hataKodu" => "S003"
                        ], 500);
                    }

                    $islemModel->resimYolu = $resimYolu;
                }

                if (!$islemModel->save())
                {
                    DB::rollBack();

                    return response()->json([
                        'durum' => false,
                        'mesaj' => '????lem kaydedilirken bir hata olu??tu.',
                        'hata' => $islemModel->getErrors(),
                        "hataKodu" => "S002"
                    ], 500);
                }
            }

            if (isset($siparisBilgileri['silinenIslemler']) && $siparisBilgileri['silinenIslemler'])
            {
                foreach ($siparisBilgileri['silinenIslemler'] as $islemId)
                {
                    $islemModel = Islemler::where("id", $islemId)->first();

                    // E??er resimYolu varsa sil
                    if ($islemModel->resimYolu)
                    {
                        $resimSilmeDurum = $this->dosyaSil($islemModel->resimYolu);

                        if (!$resimSilmeDurum)
                        {
                            DB::rollBack();

                            return response()->json([
                                'durum' => false,
                                'mesaj' => 'Resim silinirken bir hata olu??tu.',
                                "hataKodu" => "S004"
                            ], 500);
                        }
                    }

                    if (!$islemModel->delete())
                    {
                        DB::rollBack();

                        return response()->json([
                            'durum' => false,
                            'mesaj' => '????lem silinirken bir hata olu??tu.',
                            'hata' => $islemModel->getErrors(),
                            "hataKodu" => "S005"
                        ], 500);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'durum' => true,
                'mesaj' => 'Sipari?? ba??ar??yla kaydedildi.'
            ]);
        }
        catch(\Exception $e)
        {
            DB::rollBack();

            return response()->json([
                'durum' => false,
                'mesaj' => $e->getMessage(),
                'satir' => $e->getLine(),
            ], 500);
        }
    }

    public function siparisDetay(Request $request)
    {
        try
        {
            $siparisId = $request->siparisId;

            $malzemeTabloAdi = (new Malzemeler())->getTable();
            $islemTuruTabloAdi = (new IslemTurleri())->getTable();
            $islemTabloAdi = (new Islemler())->getTable();

            $islemler = Islemler::where('siparisId', $siparisId)->get();

            return response()->json([
                'durum' => true,
                'mesaj' => 'Sipari?? ba??ar??yla getirildi.',
                'veriler' => [
                    "islemler" => $islemler,
                ],
            ]);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'durum' => false,
                'mesaj' => $e->getMessage()
            ], 500);
        }
    }

    public function siparisSil(Request $request)
    {
        DB::beginTransaction();
        try
        {
            $siparisId = $request->siparisId;

            $islemler = Islemler::where('siparisId', $siparisId)->get();

            foreach ($islemler as $islem)
            {
                // E??er resimYolu varsa sil
                if ($islem->resimYolu)
                {
                    $resimSilmeDurum = $this->dosyaSil($islem->resimYolu);

                    if (!$resimSilmeDurum)
                    {
                        DB::rollBack();

                        return response()->json([
                            'durum' => false,
                            'mesaj' => 'Resim silinirken bir hata olu??tu.',
                            "hataKodu" => "S008"
                        ], 500);
                    }
                }

                if (!$islem->delete())
                {
                    DB::rollBack();

                    return response()->json([
                        'durum' => false,
                        'mesaj' => '????lem silinirken bir hata olu??tu.',
                        'hata' => $islem->getErrors(),
                        "hataKodu" => "S010"
                    ], 500);
                }
            }

            $siparis = Siparisler::find($siparisId);

            if (!$siparis)
            {
                DB::rollBack();

                return response()->json([
                    'durum' => false,
                    'mesaj' => 'Sipari?? bulunamad??.',
                    "hataKodu" => "S011"
                ], 404);
            }

            if (!$siparis->delete())
            {
                DB::rollBack();

                return response()->json([
                    'durum' => false,
                    'mesaj' => 'Sipari?? silinirken bir hata olu??tu.',
                    'hata' => $siparis->getErrors(),
                    "hataKodu" => "S012"
                ], 500);
            }

            DB::commit();

            return response()->json([
                'durum' => true,
                'mesaj' => 'Sipari?? ba??ar??yla silindi.'
            ]);
        }
        catch(\Exception $e)
        {
            DB::rollBack();

            return response()->json([
                'durum' => false,
                'mesaj' => $e->getMessage(),
                'satir' => $e->getLine(),
            ], 500);
        }
    }

    public function toplamSiparis()
    {
        try
        {
            $toplamSiparis = Siparisler::count();

            return response()->json([
                "durum" => true,
                "mesaj" => "Toplam sipari?? say??s?? bulundu.",
                "toplamSiparis" => $toplamSiparis,
            ], 200);
        }
        catch (\Exception $ex)
        {
            return response()->json([
                "durum" => false,
                "mesaj" => "Toplam sipari?? say??s?? bulunurken bir hata olu??tu.",
                "hata" => $ex->getMessage(),
                "satir" => $ex->getLine(),
            ], 500);
        }
    }
}
