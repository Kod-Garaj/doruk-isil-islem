@extends('layout') 
@section('content')
<div class="row doruk-content">
    <h4 style="color:#999"><i class="fa fa-home"></i> ANASAYFA</h4>
    <div class="col-12 col-sm-4">
        <div
            class="card waves-effect"
            style="width: 100%;"
            @can("siparis_listeleme")
                @click="siparisSayfasiAc()"
            @endcan
        >
            <div class="card-body">
                <div class="d-flex align-items-start">
                    <div class="avatar-sm font-size-20 me-3">
                        <span class="avatar-title bg-soft-primary text-primary rounded">
                            <i class="mdi mdi-tag-plus-outline"></i>
                        </span>
                    </div>
                    <div class="flex-1">
                        <div class="font-size-16 mt-2">Siparişler</div>
                    </div>
                </div>
                <h4 class="mt-4">@{{ toplamKayitlar.siparisler }}</h4>
                {{-- <div class="row">
                    <div class="col-7">
                        <p class="mb-0"><span class="text-success me-2"> 0.28% <i
                                    class="mdi mdi-arrow-up"></i> </span></p>
                    </div>
                    <div class="col-5 align-self-center">
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 62%"
                                aria-valuenow="62" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-4">
        <div
            class="card waves-effect"
            style="width: 100%;"
            @can("kullanici_listeleme")
                @click="kullanicilarSayfasiAc()"
            @endcan
        >
            <div class="card-body">
                <div class="d-flex align-items-start">
                    <div class="avatar-sm font-size-20 me-3">
                        <span class="avatar-title bg-soft-primary text-primary rounded">
                            <i class="mdi mdi-account-multiple-outline"></i>
                        </span>
                    </div>
                    <div class="flex-1">
                        <div class="font-size-16 mt-2">Kullanıcılar</div>

                    </div>
                </div>
                <h4 class="mt-4">@{{ toplamKayitlar.kullanicilar }}</h4>
                {{-- <div class="row">
                    <div class="col-7">
                        <p class="mb-0"><span class="text-success me-2"> 0.16% <i
                                    class="mdi mdi-arrow-up"></i> </span></p>
                    </div>
                    <div class="col-5 align-self-center">
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 62%"
                                aria-valuenow="62" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-4">
        <div
            class="card waves-effect"
            style="width: 100%;"
            @can("isil_islem_formu_listeleme")
                @click="isilIslemSayfasiAc()"
            @endcan
        >
            <div class="card-body">
                <div class="d-flex align-items-start">
                    <div class="avatar-sm font-size-20 me-3">
                        <span class="avatar-title bg-soft-primary text-primary rounded">
                            <i class="mdi mdi-stove"></i>
                        </span>
                    </div>
                    <div class="flex-1">
                        <div class="font-size-16 mt-2">İşlemler</div>

                    </div>
                </div>
                <h4 class="mt-4">@{{ toplamKayitlar.islemler }}</h4>
                {{-- <div class="row">
                    <div class="col-7">
                        <p class="mb-0"><span class="text-success me-2"> 0.16% <i
                                    class="mdi mdi-arrow-up"></i> </span></p>
                    </div>
                    <div class="col-5 align-self-center">
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 62%"
                                aria-valuenow="62" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
    @can("isil_islem_listeleme")
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title mb-4">Isıl İşlemler</h4>
                        </div>
                        <div class="col-4 text-end ">
                            <button @click="isilIslemSayfasiAc()" class="btn btn-primary btn-sm">Tümünü Gör</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="tech-companies-1" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>İşlem ID</th>
                                    <th>Resim</th>
                                    <th>Malzeme</th>
                                    <th>İşlem</th>
                                    <th>Fırın/Şarj</th>
                                    <th class="text-center">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-if="yukleniyorObjesi.islemler">
                                    <tr>
                                        <td colspan="9" class="text-center">
                                            <div class="col-12 text-center">
                                                <div class="spinner-border text-primary" role="status">
                                                    <span class="sr-only">Yükleniyor...</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                                <template v-else-if="!_.size(islemler.data)">
                                    <tr>
                                        <td colspan="9" class="text-center">
                                            <div class="col-12 text-center">
                                                <h5>İşlem Bulunamadı</h5>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                                <template v-else>
                                    <template v-for="(islem, iIndex) in islemler.data">
                                        <tr
                                            @can("isil_islem_formu_listeleme")
                                                @click.stop="islemDetayiAc(islem)"
                                            @endcan
                                            style="cursor: pointer;"
                                            :style="{
                                                backgroundColor: islem.tekrarEdenId ? '#F8747450' : '#fff',
                                                border: islem.tekrarEdenId ? '1px solid #F87474' : '',
                                                borderRadius: islem.tekrarEdenId ? '4px' : '',
                                            }"
                                            :key="iIndex"
                                        >
                                            <td>
                                                <div class="row">
                                                    <div class="col-12 d-inline-flex">
                                                        <span># @{{ islem.id }}</span>
                                                        <div v-if="islem.tekrarEdilenId" class="ms-1">
                                                            <span class="badge rounded-pill bg-danger">Tekrar Edilen İşlem ID: @{{ islem.tekrarEdilenId }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <span class="badge badge-pill bg-primary">Sipariş No: @{{ islem.siparisNo }}</span>
                                                    </div>
                                                    <div class="col-12">
                                                        <span class="badge badge-pill" :class="`bg-${ islem.gecenSureRenk }`">Termin: @{{ islem.gecenSure }} Gün</span>
                                                    </div>
                                                    <div class="col-12">
                                                        <small class="text-muted">Firma: @{{ islem.firmaAdi }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <img
                                                    :src="islem.resimYolu ? islem.resimYolu : varsayilanResimYolu"
                                                    class="kg-resim-sec"
                                                    @click.stop="resimOnizlemeAc(islem.resimYolu)"
                                                />
                                            </td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-12">
                                                        @{{ islem.malzemeAdi }}
                                                    </div>
                                                    <div class="col-12">
                                                        <small class="text-muted">Adet: @{{ islem.adet }} adet</small>
                                                    </div>
                                                    <div class="col-12">
                                                        <small class="text-muted">Miktar: @{{ islem.miktar }} kg</small>
                                                    </div>
                                                    <div class="col-12">
                                                        <small class="text-muted">Dara: @{{ islem.dara }} kg</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <small class="text-muted">Türü: @{{ islem.islemTuruAdi ? islem.islemTuruAdi : "-" }}</small>
                                                    </div>
                                                    <div class="col-12">
                                                        <small class="text-muted">İ. Sertlik: @{{ islem.istenilenSertlik ? islem.istenilenSertlik : "-" }}</small>
                                                    </div>
                                                    <div class="col-12">
                                                        <small class="text-muted">Kalite: @{{ islem.kalite ? islem.kalite : "-" }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <span class="badge badge-pill" :class="`bg-${ islem.firinRenk }`">@{{ islem.firinAdi }}</span>
                                                    </div>
                                                    <div class="col-12">
                                                        <span class="badge badge-pill bg-secondary">@{{ islem.sarj }}. ŞARJ</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="uzun-uzunluk text-center align-center">
                                                <div class="btn-group row">
                                                    <div class="col-12">
                                                        <b :class="islem.islemDurumuRenk">
                                                            @{{ islem.islemDurumuAdi }}
                                                            <i
                                                                class="ml-2"
                                                                :class="islem.islemDurumuIkon"
                                                            ></i>
                                                        </b>
                                                    </div>
                                                    <hr class="m-2" />
                                                    <div class="col-12">
                                                        @can("isil_islem_duzenleme")
                                                            <button
                                                                class="btn btn-primary btn-sm"
                                                                @click.stop="islemBaslat(islem)"
                                                                v-if="islem.islemDurumuKodu === 'ISLEM_BEKLIYOR'"
                                                            >
                                                                <i class="mdi mdi-play"></i>
                                                            </button>
                                                            <button
                                                                v-else-if="islem.islemDurumuKodu === 'ISLEMDE'"
                                                                class="btn btn-success btn-sm"
                                                                @click.stop="islemTamamla(islem)"
                                                            >
                                                                <i class="mdi mdi-check"></i>
                                                            </button>
                                                        @endcan
                                                        <template v-if="islem.islemDurumuKodu === 'TAMAMLANDI'">
                                                            @can("isil_islem_duzenleme")
                                                                <button
                                                                    v-if="islem.bildirim !== 1"
                                                                    class="btn btn-info btn-sm"
                                                                    @click.stop="islemBildirimAt(islem)"
                                                                >
                                                                    <i class="mdi mdi-bell"></i>
                                                                </button>
                                                                <button
                                                                    class="btn btn-danger btn-sm"
                                                                    @click.stop="islemTamamlandiGeriAl(islem)"
                                                                >
                                                                    <i class="mdi mdi-close"></i>
                                                                </button>
                                                            @endcan
                                                            <div v-if="islem.tekrarEdenId" class="col-12">
                                                                <span class="badge rounded-pill bg-danger">Tekrar Eden İşlem ID: @{{ islem.tekrarEdenId }}</span>
                                                            </div>
                                                        </template>
                                                        @can("isil_islem_duzenleme")
                                                            <button
                                                                v-if="islem.islemDurumuKodu === 'ISLEMDE'"
                                                                class="btn btn-warning btn-sm"
                                                                @click.stop="islemTekrar(islem)"
                                                            >
                                                                <i class="mdi mdi-replay"></i>
                                                            </button>
                                                        @endcan
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <template v-if="_.size(islem.tekrarEdenIslemler)">
                                            <tr :key="'tekrarEdenler' + iIndex" style="background-color: #F8747450; border: 1px solid #F87474;">
                                                <td colspan="100%" class="p-0">
                                                    <div class="d-grid">
                                                        <button class="btn btn-sm btn-danger btn-block rounded-0 m-0 p-0" type="button" data-bs-toggle="collapse" :data-bs-target="'#collapseExample' + iIndex" aria-expanded="false" :aria-controls="'collapseExample' + iIndex">
                                                            Tekrar Eden İşlemler <i class="mdi mdi-chevron-down"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <template v-for="(tekrarEdenIslem, tiIndex) in islem.tekrarEdenIslemler">
                                                <tr
                                                    class="collapse"
                                                    :id="'collapseExample' + iIndex"
                                                    style="background-color: #F8747425; border-right: 1px solid #F87474; border-left: 1px solid #F87474;"
                                                    :style="tiIndex === (_.size(islem.tekrarEdenIslemler) - 1) ? 'border-bottom: 1px solid #F87474;' : ''"
                                                    :key="tiIndex + '_' + islem.id"
                                                >
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-12 d-inline-flex">
                                                                <span># @{{ tekrarEdenIslem.id }}</span>
                                                                <div v-if="tekrarEdenIslem.tekrarEdilenId" class="ms-1">
                                                                    <span class="badge rounded-pill bg-danger">Tekrar Edilen İşlem ID: @{{ tekrarEdenIslem.tekrarEdilenId }}</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <span class="badge badge-pill bg-primary">Sipariş No: @{{ tekrarEdenIslem.siparisNo }}</span>
                                                            </div>
                                                            <div class="col-12">
                                                                <span class="badge badge-pill" :class="`bg-${ tekrarEdenIslem.gecenSureRenk }`">Termin: @{{ tekrarEdenIslem.gecenSure }} Gün</span>
                                                            </div>
                                                            <div class="col-12">
                                                                <small class="text-muted">Firma: @{{ tekrarEdenIslem.firmaAdi }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <img
                                                            :src="tekrarEdenIslem.resimYolu ? tekrarEdenIslem.resimYolu : varsayilanResimYolu"
                                                            class="kg-resim-sec"
                                                            @click.stop="resimOnizlemeAc(tekrarEdenIslem.resimYolu)"
                                                        />
                                                    </td>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                @{{ tekrarEdenIslem.malzemeAdi }}
                                                            </div>
                                                            <div class="col-12">
                                                                <small class="text-muted">Adet: @{{ tekrarEdenIslem.adet }} adet</small>
                                                            </div>
                                                            <div class="col-12">
                                                                <small class="text-muted">Miktar: @{{ tekrarEdenIslem.miktar }} kg</small>
                                                            </div>
                                                            <div class="col-12">
                                                                <small class="text-muted">Dara: @{{ tekrarEdenIslem.dara }} kg</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <small class="text-muted">Türü: @{{ tekrarEdenIslem.islemTuruAdi ? tekrarEdenIslem.islemTuruAdi : "-" }}</small>
                                                            </div>
                                                            <div class="col-12">
                                                                <small class="text-muted">İ. Sertlik: @{{ tekrarEdenIslem.istenilenSertlik ? tekrarEdenIslem.istenilenSertlik : "-" }}</small>
                                                            </div>
                                                            <div class="col-12">
                                                                <small class="text-muted">Kalite: @{{ tekrarEdenIslem.kalite ? tekrarEdenIslem.kalite : "-" }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <span class="badge badge-pill" :class="`bg-${ tekrarEdenIslem.firinRenk }`">@{{ tekrarEdenIslem.firinAdi }}</span>
                                                            </div>
                                                            <div class="col-12">
                                                                <span class="badge badge-pill bg-secondary">@{{ tekrarEdenIslem.sarj }}. ŞARJ</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="uzun-uzunluk text-center align-center">
                                                        <div class="btn-group row">
                                                            <div class="col-12">
                                                                <b :class="tekrarEdenIslem.islemDurumuRenk">
                                                                    @{{ tekrarEdenIslem.islemDurumuAdi }}
                                                                    <i
                                                                        class="ml-2"
                                                                        :class="tekrarEdenIslem.islemDurumuIkon"
                                                                    ></i>
                                                                </b>
                                                            </div>
                                                            <hr class="m-2" />
                                                            <div class="col-12">
                                                                @can("isil_islem_duzenleme")
                                                                    <button
                                                                        class="btn btn-primary btn-sm"
                                                                        @click.stop="islemBaslat(islem)"
                                                                        v-if="islem.islemDurumuKodu === 'ISLEM_BEKLIYOR'"
                                                                    >
                                                                        <i class="mdi mdi-play"></i>
                                                                    </button>
                                                                    <button
                                                                        v-else-if="islem.islemDurumuKodu === 'ISLEMDE'"
                                                                        class="btn btn-success btn-sm"
                                                                        @click.stop="islemTamamla(islem)"
                                                                    >
                                                                        <i class="mdi mdi-check"></i>
                                                                    </button>
                                                                @endcan
                                                                <template v-if="islem.islemDurumuKodu === 'TAMAMLANDI'">
                                                                    @can("isil_islem_duzenleme")
                                                                        <button
                                                                            v-if="islem.bildirim !== 1"
                                                                            class="btn btn-info btn-sm"
                                                                            @click.stop="islemBildirimAt(islem)"
                                                                        >
                                                                            <i class="mdi mdi-bell"></i>
                                                                        </button>
                                                                        <button
                                                                            class="btn btn-danger btn-sm"
                                                                            @click.stop="islemTamamlandiGeriAl(islem)"
                                                                        >
                                                                            <i class="mdi mdi-close"></i>
                                                                        </button>
                                                                    @endcan
                                                                    <div v-if="islem.tekrarEdenId" class="col-12">
                                                                        <span class="badge rounded-pill bg-danger">Tekrar Eden İşlem ID: @{{ islem.tekrarEdenId }}</span>
                                                                    </div>
                                                                </template>
                                                                @can("isil_islem_duzenleme")
                                                                    <button
                                                                        v-if="islem.islemDurumuKodu === 'ISLEMDE'"
                                                                        class="btn btn-warning btn-sm"
                                                                        @click.stop="islemTekrar(islem)"
                                                                    >
                                                                        <i class="mdi mdi-replay"></i>
                                                                    </button>
                                                                @endcan
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr
                                                    v-if="tiIndex === (_.size(islem.tekrarEdenIslemler) - 1)"
                                                    class="collapse"
                                                    :id="'collapseExample' + iIndex"
                                                    :key="'d' + tiIndex + '_' + islem.id"
                                                >
                                                    <td colspan="100%">
                                                        <hr class="m-0 bg-danger" />
                                                    </td>
                                                </tr>
                                            </template>
                                        </template>
                                    </template>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row d-flex align-items-center justify-content-between">
                        <div class="col-auto"></div>
                        <div class="col">
                            <ul class="pagination pagination-rounded justify-content-center mb-0">
                                <li class="page-item">
                                    <button class="page-link" :disabled="!islemler.prev_page_url" @click="isilIslemleriGetir(islemler.prev_page_url)">Önceki</button>
                                </li>
                                <li
                                    v-for="sayfa in islemler.last_page"
                                    class="page-item"
                                    :class="[islemler.current_page === sayfa ? 'active' : '']"
                                >
                                    <button class="page-link" @click='isilIslemleriGetir("{{ route("islemler") }}?page=" + sayfa)'>@{{ sayfa }}</button>
                                </li>
                                <li class="page-item">
                                    <button class="page-link" :disabled="!islemler.next_page_url" @click="isilIslemleriGetir(islemler.next_page_url)">Sonraki</button>
                                </li>
                            </ul>
                        </div>
                        <div class="col-auto">
                            <small class="text-muted">Toplam Kayıt: @{{ islemler.total }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endcan
</div>
@endsection

@section('script')
    <script>
        let mixinApp = {
            data() {
                return {
                    islemler: {},
                    yukleniyorObjesi: {
                        islemler: false,
                    },
                    firinlar: @json($firinlar),
                    toplamKayitlar: @json($toplamKayitlar),
                };
            },
            mounted() {
                this.isilIslemleriGetir();
            },
            methods: {
                siparisSayfasiAc: function () {
                    window.location.href = "{{ route('siparis-formu') }}";
                },
                kullanicilarSayfasiAc: function () {
                    window.location.href = "{{ route('kullanicilar') }}";
                },
                isilIslemSayfasiAc: function () {
                    window.location.href = "{{ route('tum-islemler') }}";
                },
                isilIslemleriGetir(url = "{{ route('islemler') }}") {
                    this.yukleniyorObjesi.islemler = true;
                    axios.get(url)
                    .then(response => {
                        this.yukleniyorObjesi.islemler = false;
                        if (!response.data.durum) {
                            return this.uyariAc({
                                baslik: 'Hata',
                                mesaj: response.data.mesaj,
                                tur: "error"
                            });
                        }

                        this.islemler = response.data.islemler;
                    })
                    .catch(error => {
                        this.yukleniyorObjesi.islemler = false;
                        this.uyariAc({
                            baslik: 'Hata',
                            mesaj: error.response.data.mesaj + " - Hata Kodu: " + error.response.data.hataKodu,
                            tur: "error"
                        });
                        console.log(error);
                    });
                },
                islemBaslat(islem) {
                    this.yukleniyorObjesi.islemler = true;
                    axios.post("{{ route('islemDurumuDegistir') }}", {
                        islem: islem,
                        islemDurumuKodu: "ISLEMDE"
                    })
                    .then(response => {
                        this.yukleniyorObjesi.islemler = false;
                        if (!response.data.durum) {
                            return this.uyariAc({
                                baslik: 'Hata',
                                mesaj: response.data.mesaj,
                                tur: "error"
                            });
                        }

                        this.uyariAc({
                            baslik: 'Başarılı',
                            mesaj: response.data.mesaj,
                            tur: "success"
                        });

                        this.isilIslemleriGetir();
                    })
                    .catch(error => {
                        this.yukleniyorObjesi.islemler = false;
                        this.uyariAc({
                            baslik: 'Hata',
                            mesaj: error.response.data.mesaj + " - Hata Kodu: " + error.response.data.hataKodu,
                            tur: "error"
                        });
                        console.log(error);
                    });
                },
                islemTamamla(islem) {
                    this.yukleniyorObjesi.islemler = true;
                    axios.post("{{ route('islemDurumuDegistir') }}", {
                        islem: islem,
                        islemDurumuKodu: "TAMAMLANDI"
                    })
                    .then(response => {
                        this.yukleniyorObjesi.islemler = false;
                        if (!response.data.durum) {
                            return this.uyariAc({
                                baslik: 'Hata',
                                mesaj: response.data.mesaj,
                                tur: "error"
                            });
                        }

                        this.uyariAc({
                            baslik: 'Başarılı',
                            mesaj: response.data.mesaj,
                            tur: "success"
                        });

                        this.isilIslemleriGetir();
                    })
                    .catch(error => {
                        this.yukleniyorObjesi.islemler = false;
                        this.uyariAc({
                            baslik: 'Hata',
                            mesaj: error.response.data.mesaj + " - Hata Kodu: " + error.response.data.hataKodu,
                            tur: "error"
                        });
                        console.log(error);
                    });
                },
                islemTekrar(islem) {
                    const fonksiyon = (aciklama) => {
                        this.yukleniyorObjesi.islemler = true;
                        islem.aciklama = aciklama;
                        console.log("islem açıklaması", islem.aciklama);
                        axios.post("{{ route('islemTekrarEt') }}", {
                            islem: islem,
                        })
                        .then(response => {
                            this.yukleniyorObjesi.islemler = false;
                            if (!response.data.durum) {
                                return this.uyariAc({
                                    baslik: 'Hata',
                                    mesaj: response.data.mesaj,
                                    tur: "error"
                                });
                            }

                            this.uyariAc({
                                baslik: 'Başarılı',
                                mesaj: response.data.mesaj,
                                tur: "success"
                            });

                            this.isilIslemleriGetir();
                        })
                        .catch(error => {
                            this.yukleniyorObjesi.islemler = false;
                            this.uyariAc({
                                baslik: 'Hata',
                                mesaj: error.response.data.mesaj + " - Hata Kodu: " + error.response.data.hataKodu,
                                tur: "error"
                            });
                            console.log(error);
                        });
                    };

                    Swal.fire({
                        title: 'İşlem Tekrar Edilsin mi?',
                        text: "İşlem tekrardan başlatılacaktır. Lütfen işlemi tekrar etme sebebini giriniz.",
                        icon: 'warning',
                        input: 'textarea',
                        showCancelButton: true,
                        cancelButtonText: 'İptal',
                        confirmButtonText: 'Tekrar Et',
                        inputPlaceholder: 'Tekrar açıklaması...',
                        inputAttributes: {
                            'aria-label': 'Tekrar açıklaması'
                        },
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fonksiyon(result.value);
                        }
                    });
                },
                islemDetayiAc(islem) {
                    window.location.href = "{{ route('isil-islemler') }}?islemId=" + islem.id + "&formId=" + islem.formId;
                },
                islemTamamlandiGeriAl(islem) {
                    this.yukleniyorObjesi.islemler = true;
                    axios.post("{{ route('islemTamamlandiGeriAl') }}", {
                        islem: islem,
                    })
                    .then(response => {
                        this.yukleniyorObjesi.islemler = false;
                        if (!response.data.durum) {
                            return this.uyariAc({
                                baslik: 'Hata',
                                mesaj: response.data.mesaj,
                                tur: "error"
                            });
                        }

                        this.uyariAc({
                            baslik: 'Başarılı',
                            mesaj: response.data.mesaj,
                            tur: "success"
                        });

                        this.isilIslemleriGetir();
                    })
                    .catch(error => {
                        this.yukleniyorObjesi.islemler = false;
                        this.uyariAc({
                            baslik: 'Hata',
                            mesaj: error.response.data.mesaj + " - Hata Kodu: " + error.response.data.hataKodu,
                            tur: "error"
                        });
                        console.log(error);
                    });
                },
            }
        };
    </script>
@endsection