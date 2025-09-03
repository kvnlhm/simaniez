<!DOCTYPE html>
<html lang="en">
<head>
    <base href="../../../" />
    <title>Daftar | SIMANIEZ</title>
    <meta charset="utf-8" />
    <meta name="description"
        content="SIMANIEZ" />
    <meta name="keywords"
        content="SIMANIEZ" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title"
        content="SIMANIEZ" />
    <meta property="og:url" content="https://sisenep.com/janiesi2020/register" />
    <meta property="og:site_name" content="Daftar | SIMANIEZ" />
    <link rel="canonical" href="https://sisenep.com/janiesi2020/register" />
    <link rel="shortcut icon" href="{{ asset('public/logo2.jpeg') }}" />
    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="{{ asset('public/assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->
    <script>
        // Frame-busting to prevent site from being loaded within a frame without permission (click-jacking) if (window.top != window.self) { window.top.location.replace(window.self.location.href); }
    </script>
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body" class="auth-bg">
    <!--begin::Theme mode setup on page load-->
    <script>
        var defaultThemeMode = "light";
        var themeMode;
        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if (localStorage.getItem("data-bs-theme") !== null) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }
            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }
            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }
    </script>
    <!--end::Theme mode setup on page load-->
    <!--begin::Main-->
    <!--begin::Root-->
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <!--begin::Page bg image-->
        <style>body { background-image: url('{{ asset('public/assets/media/auth/bg10.jpeg') }}'); } [data-bs-theme="dark"] body { background-image: url('{{ asset('assets/media/auth/bg10-dark.jpeg') }}'); }</style>
        <!--end::Page bg image-->
        <!--begin::Authentication - Sign-in -->
        <div class="d-flex flex-column flex-lg-row flex-column-fluid">
            <!--begin::Aside-->
            <div class="d-flex flex-lg-row-fluid">
                <!--begin::Content-->
                <div class="d-flex flex-column flex-center pb-0 pb-lg-10 p-10 w-100">
                    <!--begin::Image-->
                    <img class="theme-light-show mx-auto mw-100 w-150px w-lg-300px mb-10 mb-lg-20" src="{{ asset('public/logo.png') }}" alt="" />
                    <img class="theme-dark-show mx-auto mw-100 w-150px w-lg-300px mb-10 mb-lg-20" src="{{ asset('public/logo.png') }}" alt="" />
                    <!--end::Image-->
                    <!--begin::Title-->
                    <h1 class="text-gray-800 fs-2qx fw-bold text-center mb-7">SELAMAT DATANG</h1>
                    <!--end::Title-->
                    <!--begin::Text-->
                    <div class="text-gray-600 fs-base text-center fw-semibold">Sistem Informasi Implementasi Algoritma FP-Growth Toko Roti</div>
                    <!--end::Text-->
                </div>
                <!--end::Content-->
            </div>
            <!--begin::Aside-->
            <!--begin::Body-->
            <div class="d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-end p-12">
                <!--begin::Wrapper-->
                <div class="bg-body d-flex flex-column flex-center rounded-4 w-md-600px p-10">
                    <!--begin::Content-->
                    <div class="d-flex flex-center flex-column align-items-stretch h-lg-100 w-md-400px">
                        <!--begin::Wrapper-->
                        <div class="d-flex flex-center flex-column flex-column-fluid pb-15 pb-lg-20">
                            <!--begin::Form-->
                            <form class="form w-100" novalidate="novalidate" id="kt_sign_in_form"
                                action="{{ route('store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <!--begin::Heading-->
                                <div class="text-center mb-11">
                                    <!--begin::Title-->
                                    <h1 class="text-dark fw-bolder mb-3">Daftar</h1>
                                    <!--end::Title-->
                                    <!--begin::Subtitle-->
                                    <div class="text-gray-500 fw-semibold fs-6">Silahkan masukkan data dengan sesuai.</div>
                                    <!--end::Subtitle=-->
                                </div>
                                <!--begin::Heading-->
                                <!--begin::Input group=-->
                                <div class="fv-row mb-8">
                                    <!--begin::Email-->
                                    <input type="email" placeholder="Email" name="email" id="email"
                                        value="{{ old('email') }}" autocomplete="off"
                                        class="form-control bg-transparent @error('email') is-invalid @enderror" />
                                    <!--end::Email-->
                                    @if ($errors->has('email'))
                                        <div
                                            class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                            <div data-field="email" data-validator="regexp">{{ $errors->first('email') }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="fv-row mb-8">
                                    <input type="text" placeholder="Username" name="name" id="name"
                                        value="{{ old('name') }}" autocomplete="off"
                                        class="form-control bg-transparent @error('name') is-invalid @enderror" />
                                    @if ($errors->has('name'))
                                        <div
                                            class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                            <div data-field="name" data-validator="regexp">{{ $errors->first('name') }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="fv-row mb-8">
                                    <input type="text" placeholder="Nama Lengkap" name="nama_lengkap" id="nama_lengkap"
                                        value="{{ old('nama_lengkap') }}" autocomplete="off"
                                        class="form-control bg-transparent @error('nama_lengkap') is-invalid @enderror" />
                                    @if ($errors->has('nama_lengkap'))
                                        <div
                                            class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                            <div data-field="nama_lengkap" data-validator="regexp">
                                                {{ $errors->first('nama_lengkap') }}</div>
                                        </div>
                                    @endif
                                </div>
                                <div class="fv-row mb-8">
                                    <input type="number" placeholder="No. Telepon" name="no_telp" id="no_telp"
                                        value="{{ old('no_telp') }}" autocomplete="off"
                                        class="form-control bg-transparent @error('no_telp') is-invalid @enderror"
                                        minlength="10" maxlength="13" min="0" />
                                    @if ($errors->has('no_telp'))
                                        <div
                                            class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                            <div data-field="no_telp" data-validator="regexp">
                                                {{ $errors->first('no_telp') }}</div>
                                        </div>
                                    @endif
                                </div>
                                <div class="fv-row mb-8">
                                    <input type="text" placeholder="Alamat" name="alamat" id="alamat"
                                        value="{{ old('alamat') }}" autocomplete="off"
                                        class="form-control bg-transparent @error('alamat') is-invalid @enderror" />
                                    @if ($errors->has('alamat'))
                                        <div
                                            class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                            <div data-field="alamat" data-validator="regexp">
                                                {{ $errors->first('alamat') }}</div>
                                        </div>
                                    @endif
                                </div>
                                <div class="fv-row mb-8">
                                    <label class="form-control bg-transparent @error('foto') is-invalid @enderror">
                                        <input type="file" id="foto" name="foto" style="display: none;" accept="image/*"
                                        onchange="document.getElementById('fileName').innerHTML = this.files[0].name"
                                        required />
                                        <i class="ki-duotone ki-picture fs-2 position-absolute">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                            <span class="path5"></span>
                                            <span class="path6"></span>
                                        </i>
                                        <span id="fileName" class="ps-8">Pilih Foto</span>
                                    </label>
                                    @if ($errors->has('foto'))
                                        <div
                                            class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                            <div data-field="foto" data-validator="regexp">{{ $errors->first('foto') }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="fv-row mb-8">
                                    <input type="password" placeholder="Password" name="password" id="password"
                                        value="{{ old('password') }}" autocomplete="off"
                                        class="form-control bg-transparent @error('password') is-invalid @enderror" />
                                    @if ($errors->has('password'))
                                        <div
                                            class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                            <div data-field="password" data-validator="regexp">
                                                {{ $errors->first('password') }}</div>
                                        </div>
                                    @endif
                                </div>
                                <div class="fv-row mb-8">
                                    <!--begin::Repeat Password-->
                                    <input placeholder="Konfirmasi Password" name="password_confirmation"
                                        id="password_confirmation" value="{{ old('password_confirmation') }}" type="password"
                                        autocomplete="off" class="form-control bg-transparent" />
                                    <!--end::Repeat Password-->
                                </div>
                                <!--end::Input group=-->
                                <!--begin::Submit button-->
                                <div class="d-grid mb-10">
                                    <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
                                        <!--begin::Indicator label-->
                                        <span class="indicator-label">Daftar</span>
                                        <!--end::Indicator label-->
                                        <!--begin::Indicator progress-->
                                        <span class="indicator-progress">Mohon menunggu...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                        <!--end::Indicator progress-->
                                    </button>
                                </div>
                                <!--end::Submit button-->
                                <!--begin::Sign in-->
                                <div class="text-gray-500 text-center fw-semibold fs-6">Sudah mempunyai akun?
                                    <a href="{{ url('login') }}" class="link-primary">Login</a>
                                </div>
                                <!--end::Sign in-->
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Wrapper-->
                        <!--begin::Footer-->
                        {{-- <div class="d-flex flex-stack">
                            <!--begin::Languages-->
                            <div class="me-10">
                                <!--begin::Toggle-->
                                <button class="btn btn-flex btn-link btn-color-gray-700 btn-active-color-primary rotate fs-base" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 0px">
                                    <img data-kt-element="current-lang-flag" class="w-20px h-20px rounded me-3" src="{{ asset('public/assets/media/flags/united-states.svg') }}" alt="" />
                                    <span data-kt-element="current-lang-name" class="me-1">English</span>
                                    <i class="ki-duotone ki-down fs-5 text-muted rotate-180 m-0"></i>
                                </button>
                                <!--end::Toggle-->
                                <!--begin::Menu-->
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-4 fs-7" data-kt-menu="true" id="kt_auth_lang_menu">
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link d-flex px-5" data-kt-lang="English">
                                            <span class="symbol symbol-20px me-4">
                                                <img data-kt-element="lang-flag" class="rounded-1" src="{{ asset('public/assets/media/flags/united-states.svg') }}" alt="" />
                                            </span>
                                            <span data-kt-element="lang-name">English</span>
                                        </a>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link d-flex px-5" data-kt-lang="Spanish">
                                            <span class="symbol symbol-20px me-4">
                                                <img data-kt-element="lang-flag" class="rounded-1" src="{{ asset('public/assets/media/flags/spain.svg') }}" alt="" />
                                            </span>
                                            <span data-kt-element="lang-name">Spanish</span>
                                        </a>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link d-flex px-5" data-kt-lang="German">
                                            <span class="symbol symbol-20px me-4">
                                                <img data-kt-element="lang-flag" class="rounded-1" src="{{ asset('public/assets/media/flags/germany.svg') }}" alt="" />
                                            </span>
                                            <span data-kt-element="lang-name">German</span>
                                        </a>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link d-flex px-5" data-kt-lang="Japanese">
                                            <span class="symbol symbol-20px me-4">
                                                <img data-kt-element="lang-flag" class="rounded-1" src="{{ asset('public/assets/media/flags/japan.svg') }}" alt="" />
                                            </span>
                                            <span data-kt-element="lang-name">Japanese</span>
                                        </a>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link d-flex px-5" data-kt-lang="French">
                                            <span class="symbol symbol-20px me-4">
                                                <img data-kt-element="lang-flag" class="rounded-1" src="{{ asset('public/assets/media/flags/france.svg') }}" alt="" />
                                            </span>
                                            <span data-kt-element="lang-name">French</span>
                                        </a>
                                    </div>
                                    <!--end::Menu item-->
                                </div>
                                <!--end::Menu-->
                            </div>
                            <!--end::Languages-->
                            <!--begin::Links-->
                            <div class="d-flex fw-semibold text-primary fs-base gap-5">
                                <a href="../../demo26/dist/pages/team.html" target="_blank">Terms</a>
                                <a href="../../demo26/dist/pages/pricing/column.html" target="_blank">Plans</a>
                                <a href="../../demo26/dist/pages/contact.html" target="_blank">Contact Us</a>
                            </div>
                            <!--end::Links-->
                        </div> --}}
                        <!--end::Footer-->
                    </div>
                    <!--end::Content-->
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Body-->
        </div>
        <!--end::Authentication - Sign-in-->
    </div>
    <!--end::Root-->
    <!--end::Main-->
    <!--begin::Javascript-->
    <script>
        var hostUrl = "assets/";
    </script>
    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    <script src="{{ asset('public/assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('public/assets/js/scripts.bundle.js') }}"></script>
    <!--end::Global Javascript Bundle-->
    <!--begin::Custom Javascript(used for this page only)-->
    {{-- <script src="{{ asset('public/assets/js/custom/authentication/sign-in/general.js') }}"></script> --}}
    <!--end::Custom Javascript-->
    <!--end::Javascript-->
    <script>
        $(document).ready(function() {
            $('#password, #confirm-password').on('keyup', function() {
                if ($('#password').val() === $('#confirm-password').val()) {
                    $('#message').html('Matching').css('color', 'green');
                } else {
                    $('#message').html('Not Matching').css('color', 'red');
                }
            });
        });
    </script>
    @include('my_components.toastr')
</body>
<!--end::Body-->

</html>
