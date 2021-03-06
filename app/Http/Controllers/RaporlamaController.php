<?php

namespace App\Http\Controllers;

use App\Models\Firinlar;
use App\Models\Firmalar;
use App\Models\Islemler;
use App\Models\IslemTurleri;
use App\Models\Siparisler;
use Illuminate\Http\Request;

class RaporlamaController extends Controller
{
    public function index()
    {
        $sonSiparisYili = Siparisler::selectRaw("YEAR(tarih) as yil")->groupBy('yil')->orderBy('yil', 'desc')->first();
        $ilkSiparisYili = Siparisler::selectRaw("YEAR(tarih) as yil")->groupBy('yil')->orderBy('yil', 'asc')->first();

        return view('raporlama', [
            'ilkSiparisYili' => $ilkSiparisYili->yil,
            'sonSiparisYili' => $sonSiparisYili->yil,
        ]);
    }

    public function yillikCiroGetir()
    {
        try
        {
            $yillikCiro = Siparisler::selectRaw('YEAR(tarih) as yil, SUM(tutar) as ciro')
                ->groupBy('yil')
                ->get();

            // dd($yillikCiro->toArray());
            $yillar = $yillikCiro->pluck('yil')->toArray();
            $ciro = $yillikCiro->pluck('ciro')->toArray();

            return response()->json([
                "durum" => true,
                "mesaj" => "Yillik ciro getirildi",
                "yillikCiro" => [
                    "ciro" => $ciro,
                    "yillar" => $yillar,
                    "tumu" => $yillikCiro->toArray(),
                ],
            ]);
        }
        catch (\Exception $e)
        {
            return response()->json([
                "durum" => false,
                "mesaj" => "Yillik ciro getirilemedi",
                "hata" => $e->getMessage(),
                "satir" => $e->getLine(),
                "hataKodu" => "YC_CATCH",
            ]);
        }
    }

    public function aylikCiroGetir(Request $request)
    {
        try
        {
            $yil = $request->yil ?? date('Y');
            $aylikCiro = Siparisler::selectRaw('MONTH(tarih) as ay, SUM(tutar) as ciro')
                ->whereYear('tarih', $yil)
                ->groupBy('ay')
                ->get();

            // dd($aylikCiro->toArray());
            $aylar = $aylikCiro->pluck('ay')->toArray();
            $ciro = $aylikCiro->pluck('ciro')->toArray();

            $ayIsimleri = [
                1 => 'Oca',
                2 => '??ub',
                3 => 'Mar',
                4 => 'Nis',
                5 => 'May',
                6 => 'Haz',
                7 => 'Tem',
                8 => 'A??u',
                9 => 'Eyl',
                10 => 'Eki',
                11 => 'Kas',
                12 => 'Ara',
            ];

            foreach ($aylar as &$ay)
            {
                $ay = $ayIsimleri[$ay];
            }

            return response()->json([
                "durum" => true,
                "mesaj" => "Aylik ciro getirildi",
                "aylikCiro" => [
                    "ciro" => $ciro,
                    "aylar" => $aylar,
                    "tumu" => $aylikCiro->toArray(),
                ],
            ]);
        }
        catch (\Exception $e)
        {
            return response()->json([
                "durum" => false,
                "mesaj" => "Aylik ciro getirilemedi",
                "hata" => $e->getMessage(),
                "satir" => $e->getLine(),
                "hataKodu" => "AC_CATCH",
            ]);
        }
    }

    public function firinBazliTonaj(Request $request)
    {
        try
        {
            $baslangicTarihi = $request->baslangicTarihi;
            $bitisTarihi = $request->bitisTarihi;
            $orderTuru = $request->orderTuru ?? "tonaj";

            $firinTabloAdi = (new Firinlar())->getTable();
            $islemTabloAdi = (new Islemler())->getTable();

            $firinlar = Firinlar::selectRaw("
                $firinTabloAdi.id,
                $firinTabloAdi.ad,
                $firinTabloAdi.kod,
                $firinTabloAdi.json,
                SUM($islemTabloAdi.miktar - $islemTabloAdi.dara) as tonaj,
                SUM($islemTabloAdi.birimFiyat) as tutar
            ")
                ->join($islemTabloAdi, "$firinTabloAdi.id", '=', "$islemTabloAdi.firinId")
                ->groupBy(
                    "$firinTabloAdi.id",
                    "$firinTabloAdi.ad",
                    "$firinTabloAdi.kod",
                    "$firinTabloAdi.json",
                )
                ->orderBy($orderTuru, 'desc');

            if ($baslangicTarihi)
            {
                $firinlar = $firinlar->where("$islemTabloAdi.created_at", '>=', $baslangicTarihi);

                if ($bitisTarihi)
                {
                    $firinlar = $firinlar->where("$islemTabloAdi.created_at", '<=', $bitisTarihi);
                }
            }
            else if ($bitisTarihi)
            {
                $firinlar = $firinlar->where("$islemTabloAdi.created_at", '<=', $bitisTarihi);
            }

            $firinlar = $firinlar->get();

            foreach ($firinlar as &$firin)
            {
                $firin->json = json_decode($firin->json);
            }

            return response()->json([
                "durum" => true,
                "mesaj" => "Firin bazli tonaj getirildi",
                "firinlar" => $firinlar->toArray(),
            ]);
        }
        catch (\Exception $e)
        {
            return response()->json([
                "durum" => false,
                "mesaj" => "Firin bazli tonaj getirilemedi",
                "hata" => $e->getMessage(),
                "satir" => $e->getLine(),
                "hataKodu" => "FB_CATCH",
            ]);
        }
    }

    public function firmaBazliBilgileriGetir(Request $request)
    {
        try
        {
            $baslangicTarihi = $request->baslangicTarihi;
            $bitisTarihi = $request->bitisTarihi;
            $arama = $request->arama;
            $orderTuru = $request->orderTuru ?? "tonaj";

            $firmaTabloAdi = (new Firmalar())->getTable();
            $islemTabloAdi = (new Islemler())->getTable();
            $siparisTabloAdi = (new Siparisler())->getTable();

            $firmalar = Firmalar::selectRaw("
                $firmaTabloAdi.id,
                $firmaTabloAdi.firmaAdi,
                $firmaTabloAdi.sorumluKisi,
                SUM($islemTabloAdi.miktar - $islemTabloAdi.dara) as tonaj,
                SUM($islemTabloAdi.birimFiyat) as tutar
            ")
                ->join($siparisTabloAdi, "$firmaTabloAdi.id", '=', "$siparisTabloAdi.firmaId")
                ->join($islemTabloAdi, "$siparisTabloAdi.id", '=', "$islemTabloAdi.siparisId")
                ->groupBy(
                    "$firmaTabloAdi.id",
                    "$firmaTabloAdi.firmaAdi",
                    "$firmaTabloAdi.sorumluKisi",
                )
                ->orderBy($orderTuru, 'desc');

            if ($arama)
            {
                $firmalar = $firmalar->where("$firmaTabloAdi.firmaAdi", 'like', "%$arama%")
                    ->orWhere("$firmaTabloAdi.sorumluKisi", 'like', "%$arama%");
            }

            if ($baslangicTarihi)
            {
                $firmalar = $firmalar->where("$islemTabloAdi.created_at", '>=', $baslangicTarihi);

                if ($bitisTarihi)
                {
                    $firmalar = $firmalar->where("$islemTabloAdi.created_at", '<=', $bitisTarihi);
                }
            }
            else if ($bitisTarihi)
            {
                $firmalar = $firmalar->where("$islemTabloAdi.created_at", '<=', $bitisTarihi);
            }

            $firmalar = $firmalar->paginate(6);

            return response()->json([
                "durum" => true,
                "mesaj" => "Firma bazli bilgiler getirildi",
                "firmalar" => $firmalar->toArray(),
            ]);
        }
        catch (\Exception $e)
        {
            return response()->json([
                "durum" => false,
                "mesaj" => "Firma bazli bilgiler getirilemedi",
                "hata" => $e->getMessage(),
                "satir" => $e->getLine(),
                "hataKodu" => "FBB_CATCH",
            ]);
        }
    }

    public function firinBazliIslemTurleriGetir(Request $request)
    {
        try
        {
            $baslangicTarihi = $request->baslangicTarihi;
            $bitisTarihi = $request->bitisTarihi;
            $orderTuru = $request->orderTuru ?? "toplam";

            $islemTabloAdi = (new Islemler())->getTable();
            $firinTabloAdi = (new Firinlar())->getTable();
            $islemTurleriTabloAdi = (new IslemTurleri())->getTable();

            $islemTurleri = Firinlar::selectRaw("
                $firinTabloAdi.id,
                $firinTabloAdi.ad,
                $firinTabloAdi.kod,
                $firinTabloAdi.json,
                $islemTabloAdi.islemTuruId,
                $islemTurleriTabloAdi.ad as islemTuruAdi,
                COUNT($islemTurleriTabloAdi.id) as toplam,
                COUNT($islemTabloAdi.tekrarEdenId) as toplamTekrarEden
            ")
                ->join($islemTabloAdi, "$firinTabloAdi.id", '=', "$islemTabloAdi.firinId")
                ->join($islemTurleriTabloAdi, "$islemTabloAdi.islemTuruId", '=', "$islemTurleriTabloAdi.id")
                ->groupBy(
                    "$islemTabloAdi.islemTuruId",
                    "$islemTurleriTabloAdi.ad",
                    "$firinTabloAdi.id",
                    "$firinTabloAdi.ad",
                    "$firinTabloAdi.kod",
                    "$firinTabloAdi.json",
                )
                ->orderBy($orderTuru, 'desc');

            if ($baslangicTarihi)
            {
                $islemTurleri = $islemTurleri->where("$islemTabloAdi.created_at", '>=', $baslangicTarihi);

                if ($bitisTarihi)
                {
                    $islemTurleri = $islemTurleri->where("$islemTabloAdi.created_at", '<=', $bitisTarihi);
                }
            }
            else if ($bitisTarihi)
            {
                $islemTurleri = $islemTurleri->where("$islemTabloAdi.created_at", '<=', $bitisTarihi);
            }

            $islemTurleri = $islemTurleri->get()->toArray();

            $hazirlananVeriler = [
                "veriler" => [],
                "chartVerileri" => [],
            ];
            foreach ($islemTurleri as &$islemTur)
            {
                $firinId = $islemTur["id"];

                $islemTur["json"] = json_decode($islemTur["json"], true);
                $islemTur["tekrarEtmeyenSayisi"] = $islemTur["toplam"] - $islemTur["toplamTekrarEden"];

                if (!isset($hazirlananVeriler["veriler"][$firinId]))
                {
                    $hazirlananVeriler["veriler"][$firinId] = [
                        "id" => $firinId,
                        "ad" => $islemTur["ad"],
                        "kod" => $islemTur["kod"],
                        "json" => $islemTur["json"],
                        "islemTurleri" => [],
                    ];
                }

                unset($islemTur["id"], $islemTur["ad"], $islemTur["kod"], $islemTur["json"]);

                $hazirlananVeriler["veriler"][$firinId]["islemTurleri"][] = $islemTur;

                // Chart verileri haz??rlan??yor
                if (!isset($hazirlananVeriler["chartVerileri"][$firinId]))
                {
                    $hazirlananVeriler["chartVerileri"][$firinId] = [
                        "firinId" => $firinId,
                        "ad" => $hazirlananVeriler["veriler"][$firinId]["ad"],
                        "kod" => $hazirlananVeriler["veriler"][$firinId]["kod"],
                        "json" => $hazirlananVeriler["veriler"][$firinId]["json"],
                        "islemler" => [],
                        "tekrarEtmeyenSayisi" => [],
                        "tekrarEdenSayisi" => [],
                    ];
                }

                $key = array_search($islemTur["islemTuruAdi"], $hazirlananVeriler["chartVerileri"][$firinId]["islemler"]);

                if ($key === false)
                {
                    $hazirlananVeriler["chartVerileri"][$firinId]["islemler"][] = $islemTur["islemTuruAdi"];
                    $hazirlananVeriler["chartVerileri"][$firinId]["tekrarEtmeyenSayisi"][] = $islemTur["tekrarEtmeyenSayisi"];
                    $hazirlananVeriler["chartVerileri"][$firinId]["tekrarEdenSayisi"][] = $islemTur["toplamTekrarEden"];
                }
                else
                {
                    $hazirlananVeriler["chartVerileri"][$firinId]["tekrarEtmeyenSayisi"][$key] += $islemTur["tekrarEtmeyenSayisi"];
                    $hazirlananVeriler["chartVerileri"][$firinId]["tekrarEdenSayisi"][$key] += $islemTur["toplamTekrarEden"];
                }
            }

            $hazirlananVeriler["veriler"] = array_values($hazirlananVeriler["veriler"]);
            $hazirlananVeriler["chartVerileri"] = array_values($hazirlananVeriler["chartVerileri"]);

            return response()->json([
                "durum" => true,
                "mesaj" => "Firin bazli bilgiler getirildi",
                "islemTurleri" => $hazirlananVeriler,
            ]);
        }
        catch (\Exception $e)
        {
            return response()->json([
                "durum" => false,
                "mesaj" => "Firin bazli bilgiler getirilemedi",
                "hata" => $e->getMessage(),
                "satir" => $e->getLine(),
                "hataKodu" => "FBI_CATCH",
            ]);
        }
    }
}
