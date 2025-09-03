@extends('master')
@section('judul', 'Perhitungan FP-Growth')
@section('breadcrumb')
    <h1 class="page-heading d-flex title-custom fw-bolder fs-2hx flex-column justify-content-center my-0">
        Halaman Perhitungan FP-Growth</h1>
    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
        <li class="breadcrumb-item text-gray-700">
            <a href="{{ url('dashboard') }}" class="text-gray-700 text-hover-primary"><i class="ki-duotone ki-home"></i></a>
        </li>
        <li class="breadcrumb-item">
            <span class="">
                <i class="ki-duotone ki-right fs-6 text-gray-700"></i>
            </span>
        </li>
        <li class="breadcrumb-item text-gray-700">Perhitungan FP-Growth</li>
        <li class="breadcrumb-item">
            <span class="">
                <i class="ki-duotone ki-right fs-6 text-gray-700"></i>
            </span>
        </li>
        <li class="breadcrumb-item text-gray-700">Proses 1</li>
    </ul>
@endsection
@section('css')
    <style>
        .stepper.stepper-links .stepper-nav .stepper-item.current:after {
            background-color: var(--bs-warning);
        }
    </style>
@endsection

@section('konten')
    <div class="card">
        <div class="card-body">
            <div class="stepper stepper-links d-flex flex-column pt-15" id="kt_create_account_stepper">
                <div class="stepper-nav mb-5">
                    {{-- <div class="stepper-item completed" data-kt-stepper-element="nav">
                        <h3 class="stepper-title">Proses 1</h3>
                    </div> --}}
                    <div class="stepper-item current" data-kt-stepper-element="nav">
                        <h3 class="stepper-title text-warning">Proses 1</h3>
                    </div>
                    <div class="stepper-item pending" data-kt-stepper-element="nav">
                        <h3 class="stepper-title">Proses 2</h3>
                    </div>
                    <div class="stepper-item pending" data-kt-stepper-element="nav">
                        <h3 class="stepper-title">Proses 3</h3>
                    </div>
                    <div class="stepper-item pending" data-kt-stepper-element="nav">
                        <h3 class="stepper-title">Proses 4</h3>
                    </div>
                </div>
                <form class="mx-auto mw-600px w-100 pt-15 pb-10" id="kt_create_account_form"
                    action="{{ url('fpg/proses1') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="current" data-kt-stepper-element="content">
                        <div class="w-100">
                            <div class="pb-10 pb-lg-12">
                                <h2 class="fw-bold text-dark">Silahkan inputkan data dengan sesuai.</h2>
                                <div class="text-muted fw-semibold fs-6">Jika Anda memerlukan info lebih lanjut, silakan
                                    periksa
                                    <a href="{{ url('panduan') }}" class="link-warning fw-bold">Halaman Panduan</a>.
                                </div>
                            </div>
                            <div class="fv-row mb-10">
                                <label class="fs-6 fw-semibold form-label required">Tanggal Awal</label>
                                <input type="date" name="tanggal_awal"
                                    class="form-control form-control-lg form-control-solid"
                                    required />
                            </div>
                            <div class="fv-row mb-10">
                                <label class="fs-6 fw-semibold form-label required">Tanggal Akhir</label>
                                <input type="date" name="tanggal_akhir"
                                    class="form-control form-control-lg form-control-solid"
                                    required />
                            </div>
                            <div class="fv-row mb-10">
                                <label class="fs-6 fw-semibold form-label required">Minimum Support (%)</label>
                                <input type="number" name="minimal_support"
                                    class="form-control form-control-lg form-control-solid"
                                    placeholder="Masukkan minimal support" required />
                            </div>
                            <div class="fv-row mb-10">
                                <label class="fs-6 fw-semibold form-label required">Minimum Confidence (%)</label>
                                <input type="number" name="minimal_confidence"
                                    class="form-control form-control-lg form-control-solid"
                                    placeholder="Masukkan minimal confidence" required />
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-stack pt-15">
                        <div>
                            <button type="submit" class="btn btn-lg btn-warning me-3 d-inline-block">
                                <span class="indicator-label">Hitung
                                    <i class="ki-duotone ki-arrow-right fs-3 ms-2 me-0">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i></span>
                                <span class="indicator-progress">Mohon menunggu...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection