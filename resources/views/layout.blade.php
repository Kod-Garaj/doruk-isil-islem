<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Doruk Otomasyon</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/img/favicon.png">
    <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <link href="assets/css/doruk.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="https://unpkg.com/vue-select@3/dist/vue-select.css">

    <style>
        table td.kisa-uzunluk {
            min-width: 100px;
        }

        table td.orta-uzunluk {
            min-width: 150px;
        }

        table td.uzun-uzunluk {
            min-width: 200px;
        }

        table td.en-uzun-uzunluk {
            min-width: 275px;
        }

        table td.align-left {
            text-align: left !important;
        }

        table td.align-right {
            text-align: right !important;
        }

        table td.align-center {
            text-align: center !important;
        }
        .kg-resim-sec {
            transition: filter .3s;
            width: 64px;
            height: 64px;
            border: 1px solid #666;
            border-radius: 4px;
        }

        .kg-resim-sec:hover {
            position: relative;
            filter: contrast(30%);
            cursor: pointer;
        }
    </style>
    @yield('style')
</head>

<body data-layout="detached" data-topbar="colored">
    <div id="preloader">
        <div id="status">
            <div class="spinner-chase">
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
            </div>
        </div>
    </div>

    <div id="app" class="container-fluid">
        <div id="layout-wrapper">
            <div class="vertical-menu">
                <div class="h-100">
                    <!--- Sidemenu close button -->
                    <div v-if="sidebarButonDurum" class="position-absolute top-0 end-0 m-2">
                        <button @click="sidebarAcKapat(false)" class="btn btn-outline-danger btn-sm">
                            <i class="mdi mdi-close-circle-outline"></i>
                        </button>
                    </div>
                    <div class="user-wid text-center py-4">
                        <div class="text-center">
                            <img src="img/doruk-logo.png" alt="">
                        </div>
                    </div>
                    <div id="sidebar-menu">
                        <ul class="metismenu list-unstyled" id="side-menu">
                            <li>
                                <a href="{{ route('home') }}" class="waves-effect">
                                    <i class="mdi mdi-home"></i> Anasayfa
                                </a>
                            </li>

                            @can("siparis_listeleme")
                                <li>
                                    <a href="{{ route('siparis-formu') }}" class=" waves-effect">
                                        <i class="mdi mdi-tag-plus-outline"></i> Sipari?? Formu
                                    </a>
                                </li>
                            @endcan

                            @can("isil_islem_formu_listeleme")
                                <li>
                                    <a href="{{ route("isil-islemler") }}" class=" waves-effect">
                                        <i class="mdi mdi-calendar-check"></i> Is??l ????lem Formlar??
                                    </a>
                                </li>
                            @endcan

                            @can("isil_islem_listeleme")
                                <li>
                                    <a href="{{ route("tum-islemler") }}" class=" waves-effect">
                                        <i class="mdi mdi-progress-wrench"></i> Is??l ????lemler
                                    </a>
                                </li>
                            @endcan

                            @can("rapor_listeleme")
                                <li>
                                    <a href="{{ route("raporlama") }}" class=" waves-effect">
                                        <i class="fas fa-chart-line"></i> Raporlama
                                    </a>
                                </li>
                            @endcan

                            <li>
                                <a href="{{ route("bildirimler") }}" class=" waves-effect">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>
                                            <i class="fas fa-bell"></i> Bildirimler
                                        </span>
                                        <span class="badge bg-danger badge-pill" v-if="miniBildirimlerObjesi.okunmamisBildirimSayisi > 0">@{{ miniBildirimlerObjesi.okunmamisBildirimSayisi }}</span>
                                    </div>
                                </a>
                            </li>

                            @can("yonetim_menusu")
                                <li>
                                    <a
                                        href="javascript:
                                            (document.getElementById('yonetim-ust-menu')).classList.toggle('mm-active');
                                            (document.getElementById('yonetim-alt-menu')).classList.toggle('mm-show');
                                        "
                                        class="has-arrow waves-effect"
                                        id="yonetim-ust-menu"
                                    >
                                        <i class="mdi mdi-account-multiple-outline"></i> Y??netim
                                    </a>
                                    <ul class="sub-menu mm-collapse" aria-expanded="true" id="yonetim-alt-menu">
                                        @can('kullanici_listeleme')
                                            <li class="waves-effect">
                                                <a href="{{ route('kullanicilar') }}">
                                                    <i class="fa fa-users"></i>
                                                    Kullan??c??lar
                                                </a>
                                            </li>
                                        @endcan
                                        @can('rol_listeleme')
                                            <li class="waves-effect">
                                                <a href="{{ route('roller') }}">
                                                    <i class="fa fa-user-tag"></i>
                                                    Roller
                                                </a>
                                            </li>
                                        @endcan
                                        @can('log_listeleme')
                                            <li class="waves-effect">
                                                <a href="{{ route('log-kayitlari') }}">
                                                    <i class="fa fa-clipboard-list"></i>
                                                    Log Kay??tlar??
                                                </a>
                                            </li>
                                        @endcan
                                        @can('firin_listeleme')
                                            <li class="waves-effect">
                                                <a href="{{ route('firinlar') }}">
                                                    <i class="fa fa-spinner"></i>
                                                    F??r??nlar
                                                </a>
                                            </li>
                                        @endcan
                                        @can('firma_listeleme')
                                            <li class="waves-effect">
                                                <a href="{{ route('firmalar') }}">
                                                    <i class="fas fa-globe"></i>
                                                    Firmalar
                                                </a>
                                            </li>
                                        @endcan
                                        {{-- <li><a href="#">Firmalar</a></li>
                                        <li><a href="#">????lem T??rleri</a></li>
                                        <li><a href="#">F??r??nlar</a></li> --}}
                                    </ul>
                                </li>
                            @endcan
                        </ul>
                    </div>
                </div>
            </div>

            <div class="main-content">
                <div class="page-content mb-5">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-flex align-items-center justify-content-between">
                                <!-- sidebar button -->
                                <div class="d-inline-flex align-items-center justify-content-between">
                                    <div v-if="sidebarButonDurum" class="me-2">
                                        <button @click="sidebarAcKapat(true)" class="btn btn-outline-light btn-sm">
                                            <i class="fas fa-bars"></i>
                                        </button>
                                    </div>
                                    <a href="{{ route('home') }}" class="waves-effect">
                                        <h4 class="page-title mb-0 px-1 font-size-18 text-nowrap d-none d-sm-block">ISIL ????LEM TAK??P OTOMASYONU</h4>
                                    </a>
                                </div>
                                <div>
                                    <div class="float-end">
                                        {{-- <div class="d-none d-lg-inline-block ms-1">
                                            <button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
                                                <i class="mdi mdi-fullscreen"></i>
                                            </button>
                                        </div> --}}
                                        <div class="dropdown d-inline-block">
                                            <button @click="miniBildirimleriGetir" type="button" class="btn header-item noti-icon waves-effect" id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="mdi mdi-bell-outline"></i>
                                                <span class="badge rounded-pill bg-danger" v-if="miniBildirimlerObjesi.okunmamisBildirimSayisi > 0">
                                                    @{{ miniBildirimlerObjesi.okunmamisBildirimSayisi }}
                                                </span>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-notifications-dropdown">
                                                <div class="p-3">
                                                    <div class="row align-items-center">
                                                        <div class="col">
                                                            <h6 class="m-0"> Bildirimler </h6>
                                                        </div>
                                                        <div class="col-auto">
                                                            <a href="{{ route('bildirimler') }}" class="small"> T??m??n?? g??r</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="m-0" />
                                                <div style="max-height: 230px;" class="overflow-auto">
                                                    <!-- y??kleniyor -->
                                                    <div class="col-12" v-if="miniBildirimlerObjesi.yukleniyor">
                                                        <div class="text-center py-4">
                                                            <div class="spinner-grow spinner-grow-sm text-primary" role="status">
                                                                <span class="sr-only">Y??kleniyor...</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <template v-else-if="miniBildirimlerObjesi.veriler && _.size(miniBildirimlerObjesi.veriler.data)">
                                                        <a
                                                            v-for="(bildirim, index) in miniBildirimlerObjesi.veriler.data"
                                                            href="#"
                                                            class="text-reset notification-item"
                                                            :key="index"
                                                        >
                                                            <div class="d-flex align-items-start" :style="{ backgroundColor: !bildirim.okundu ? '#54BAB933' : '' }">
                                                                <div class="flex-1">
                                                                    <h6 class="mt-0 mb-1">@{{ bildirim.baslik }}</h6>
                                                                    <div class="font-size-12 text-muted">
                                                                        <p class="mb-1" v-html="bildirim.icerik"></p>
                                                                        <p class="mb-0"><i class="mdi mdi-clock-outline"></i> @{{ m(bildirim.created_at).format("L LTS") }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </template>
                                                    <template v-else>
                                                        <div class="text-center py-4">
                                                            <small class="text-muted">Bildirim yok</small>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="dropdown d-inline-block">
                                            <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="ms-1">{{ Auth::user()->name }}</span>
                                                <i class="mdi mdi-chevron-down"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item d-block" href="#"><i
                                                    class="bx bx-wrench font-size-16 align-middle me-1"></i> Ayarlar</a>

                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-danger" href="{{ route('logout') }}"><i
                                                    class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i> ????k????</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row doruk-content mt-5 pt-3">
                        @yield('content')
                    </div>
                </div>
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-6">
                                &copy; @{{ new Date().getFullYear() }}
                            </div>
                            <div class="col-6">
                                <div class="text-end">
                                    KodGaraj
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </div>

    @yield("script")

    <!-- JAVASCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="assets/libs/jquery/jquery.min.js"></script>
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/metismenu/metisMenu.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>
    <script src="assets/libs/jquery-sparkline/jquery.sparkline.min.js"></script>
    <script src="assets/js/app.js"></script>

    <!-- axios cdn -->
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <!-- v-mask -->
    <script src="https://cdn.jsdelivr.net/npm/v-mask/dist/v-mask.min.js"></script>
    <!-- lodash -->
    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>
    <!-- momentjs -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.3/moment-with-locales.min.js"></script>
    <!-- sweetalert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- or point to a specific vue-select release -->
    <script src="https://unpkg.com/vue-select@3"></script>

    <script src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>

    <script>
        moment.locale("tr");

        Vue.use(VueMask.VueMaskPlugin);
        Vue.component('v-select', VueSelect.VueSelect);

        new Vue({
            mixins: [mixinApp],
            el: '#app',
            data: {
                yukleniyor: false,
                sidebarButonDurum: false,
                varsayilanResimYolu: "/no-image.jpg",
                miniBildirimlerObjesi: {
                    yukleniyor: false,
                    okunmamisBildirimSayisi: 0,
                    veriler: []
                },
            },
            computed: {
                m() {
                    return moment;
                }
            },
            mounted() {
                this.$nextTick(() => {
                    this.sidebarButonDurum = window.innerWidth < 992;
                });
                this.okunmamisBildirimSayisiGetir();
            },
            methods: {
                uyariAc(obje) {
                    if (obje.toast !== undefined) {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: obje.toast.position ? obje.toast.position : 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                        });

                        return Toast.fire({
                            icon: obje.toast.status ? 'success' : 'error',
                            title: obje.toast.message ? obje.toast.message : '????lem ba??ar??l??!'
                        });
                    }

                    Swal.fire({
                        title: obje.baslik,
                        text: obje.mesaj,
                        icon: obje.tur,
                        ...obje.ozellikler
                    });
                },
                yukleniyorDurum(durum) {
                    this.yukleniyor = durum;
                },
                sidebarAcKapat(durum = null) {
                    if (durum === null) {
                        document.body.classList.toggle('sidebar-enable');
                    } else {
                        if (durum) {
                            document.body.classList.add('sidebar-enable');
                        } else {
                            document.body.classList.remove('sidebar-enable');
                        }
                    }
                },
                resimOnizlemeAc(resimYolu) {
                    if (!resimYolu) {
                        resimYolu = this.varsayilanResimYolu;
                    }

                    Swal.fire({
                        imageUrl: resimYolu,
                        imageWidth: '100%',
                        imageHeight: '100%',
                        imageAlt: 'Resim',
                        animation: false,
                        showConfirmButton: true,
                        confirmButtonText: 'Kapat',
                    });
                },
                miniBildirimleriGetir() {
                    this.miniBildirimlerObjesi.yukleniyor = true;
                    axios.get("{{ route('bildirimleriGetir') }}", {
                        params: {
                            sayfalama: 6,
                        }
                    })
                    .then(response => {
                        this.miniBildirimlerObjesi.yukleniyor = false;
                        if (response.data.durum) {
                            this.miniBildirimlerObjesi = {
                                ...this.miniBildirimlerObjesi,
                                ...response.data.bildirimler
                            };

                            this.miniBildirimlerObjesi = _.cloneDeep(this.miniBildirimlerObjesi);
                        }
                    })
                    .catch(error => {
                        this.miniBildirimlerObjesi.yukleniyor = false;
                    });
                },
                okunmamisBildirimSayisiGetir() {
                    axios.get("{{ route('okunmamisBildirimSayisiGetir') }}")
                    .then(response => {
                        if (response.data.durum) {
                            this.miniBildirimlerObjesi.okunmamisBildirimSayisi = response.data.okunmamisBildirimSayisi;
                        }
                    })
                    .catch(error => {
                        this.miniBildirimlerObjesi.okunmamisBildirimSayisi = 0;
                    });
                },
            },
        });
    </script>

</body>

</html>