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
</head>

<body data-layout="detached" data-topbar="colored">
    <div id="app" class="container-fluid">
        <div id="layout-wrapper">
            <div class="vertical-menu">
                <div class="h-100">
                    <div class="user-wid text-center py-4">
                        <div class="text-center">
                            <img src="img/doruk-logo.png" alt="">
                        </div>
                    </div>
                    <div id="sidebar-menu">
                        <ul class="metismenu list-unstyled" id="side-menu">
                            <li>
                                <a href="{{ route('home') }}" class=" waves-effect">
                                    <i class="mdi mdi-home"></i> Anasayfa
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('siparis-formu') }}" class=" waves-effect">
                                    <i class="mdi mdi-tag-plus-outline"></i> Sipariş Formu
                                </a>
                            </li>
                            <li>
                                <a href="{{ route("isil-islem-formu") }}" class=" waves-effect">
                                    <i class="mdi mdi-stove"></i> Isıl İşlemler
                                </a>
                            </li>
                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="mdi mdi-account-multiple-outline"></i> Yönetim
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="#">Fırınlar</a></li>
                                    <li><a href="#">Sepetler</a></li>
                                    <li><a href="#">Firmalar</a></li>
                                    <li><a href="#">Kullanıcılar</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="main-content">
                <div class="page-content">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-flex align-items-center justify-content-between">
                                <h4 class="page-title mb-0 font-size-18">ISIL İŞLEM TAKİP OTOMASYONU</h4>
                                <div class="page-title-right">
                                    <div class="float-end">
                                        <div class="dropdown d-none d-lg-inline-block ms-1">
                                            <button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
                                            <i class="mdi mdi-fullscreen"></i>
                                        </button>
                                        </div>
                                        <div class="dropdown d-inline-block">
                                            <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="mdi mdi-bell-outline"></i>
                                            <span class="badge rounded-pill bg-danger">1</span>
                                        </button>
                                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-notifications-dropdown">
                                                <div class="p-3">
                                                    <div class="row align-items-center">
                                                        <div class="col">
                                                            <h6 class="m-0"> Bildirimler </h6>
                                                        </div>
                                                        <div class="col-auto">
                                                            <a href="#!" class="small"> Tümünü gör</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div data-simplebar style="max-height: 230px;">
                                                    <a href="" class="text-reset notification-item">
                                                        <div class="d-flex align-items-start">
                                                            <div class="avatar-xs me-3">
                                                                <span class="avatar-title bg-primary rounded-circle font-size-16">
                                                                <i class="bx bx-badge-check"></i>
                                                            </span>
                                                            </div>
                                                            <div class="flex-1">
                                                                <h6 class="mt-0 mb-1">Bildirim başlığı</h6>
                                                                <div class="font-size-12 text-muted">
                                                                    <p class="mb-1">Bildirim açıklaması</p>
                                                                    <p class="mb-0"><i class="mdi mdi-clock-outline"></i> 12.05.2022 15.18.55</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="dropdown d-inline-block">
                                            <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            
                                            <span class="d-none d-xl-inline-block ms-1">Admin</span>
                                            <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                                        </button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item d-block" href="#"><i
                                                    class="bx bx-wrench font-size-16 align-middle me-1"></i> Ayarlar</a>

                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-danger" href="{{ route('logout') }}"><i
                                                    class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i> Çıkış</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row doruk-content mt-5">
                        @yield('content')
                    </div>
                </div>
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                &copy; 2022
                            </div>
                            <div class="col-sm-6">
                                <div class="text-sm-end d-none d-sm-block">
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

    <script>
        new Vue({
            mixins: [mixinApp],
            el: '#app',
            data: {},
            methods: {},
        });
    </script>

</body>

</html>