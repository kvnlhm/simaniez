@extends('master')
@section('judul', 'Beranda')
@section('breadcrumb')
    <h1 class="page-heading d-flex title-custom fw-bolder fs-2hx flex-column justify-content-center my-0">
        Halaman Beranda</h1>
    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
        <li class="breadcrumb-item text-gray-700">
            <a href="{{ url('dashboard') }}" class="text-gray-700 text-hover-primary"><i class="ki-duotone ki-home"></i></a>
        </li>
        <li class="breadcrumb-item">
            <span class="">
                <i class="ki-duotone ki-right fs-6 text-gray-700"></i>
            </span>
        </li>
        <li class="breadcrumb-item text-gray-700">Beranda</li>
    </ul>
@endsection

@section('konten')
    {{-- <h2 class="fs-2x fw-bold mb-10">Selamat Datang, {{ Auth::user()->name }}!</h2> --}}
    <h2 class="fs-2x fw-bold mb-10">FP-Growth</h2>
    <p class="text-gray-400 fs-4 fw-semibold">Algoritma FP-Growth merupakan perkembangan dari Algoritma Apriori. Algoritma FP-Growth adalah salah satu metode association rule mining. Algoritma FP-Growth menggunakan konsep pembangunan Tree dalam pencarian itemset (Bagus, 2018).</p>
@endsection

@section('css')
@endsection
@section('script')
    @include('my_components.toastr')
@endsection
