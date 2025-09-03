@extends('master')
@section('judul', 'Data')
@section('breadcrumb')
    <h1 class="page-heading d-flex title-custom fw-bolder fs-2hx flex-column justify-content-center my-0">
        Halaman Data</h1>
    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
        <li class="breadcrumb-item text-gray-700">
            <a href="{{ url('dashboard') }}" class="text-gray-700 text-hover-primary"><i class="ki-duotone ki-home"></i></a>
        </li>
        <li class="breadcrumb-item">
            <span class="">
                <i class="ki-duotone ki-right fs-6 text-gray-700"></i>
            </span>
        </li>
        <li class="breadcrumb-item text-gray-700">Data</li>
    </ul>
@endsection

@section('konten')
    @if (Auth::user()->id_priv == 1)
        <div class="card mb-5 mb-xl-8">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3 mb-1">Tabel</span>
                    <span class="text-muted mt-1 fw-semibold fs-7">Daftar Data</span>
                </h3>
                <div class="card-toolbar">
                    <!-- Tambahkan form pencarian di sini -->
                    <form action="{{ url('data') }}" method="GET" class="d-flex align-items-center">
                        <input type="text" name="search" class="form-control form-control-solid w-250px me-2" placeholder="Cari data..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-sm btn-primary me-2">Cari</button>
                        @if(request('search'))
                            <a href="{{ url('data') }}" class="btn btn-sm btn-secondary me-12">Reset</a>
                        @endif
                    </form>
                    <!-- Tombol-tombol lainnya -->
                    <a href="#" data-bs-toggle="modal" data-bs-target="#modal_form_tambah"
                        class="btn btn-sm btn-light-warning me-2">
                        <i class="ki-duotone ki-plus fs-2"></i>Tambah Data</a>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#modal_hapus_semua"
                        class="btn btn-sm btn-light-danger me-2">
                        <i class="ki-duotone ki-trash fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                            <span class="path5"></span>
                        </i>Hapus Semua Data</a>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#modal_upload_excel"
                        class="btn btn-sm btn-light-success">
                        <i class="ki-duotone ki-file-up fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>Upload File Excel</a>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="table-responsive">
                    <table id="kt_datatable_dom_positioning"
                        class="table table-row-bordered border rounded align-middle gs-0 gy-4">
                        <thead>
                            <tr class="fw-bold text-muted bg-light">
                                <th class="ps-4 min-w-40px rounded-start">No.</th>
                                <th class="min-w-100px">Hari</th>
                                <th class="min-w-100px">Tanggal</th>
                                <th class="min-w-100px">Waktu</th>
                                <th class="min-w-100px">Item</th>
                                <th class="min-w-100px text-center rounded-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $index => $dt)
                                <tr>
                                    <td class="ps-4 text-dark fw-bold text-hover-primary mb-1 fs-6">
                                        {{ $data->firstItem() + $index }}
                                    </td>
                                    <td class="text-dark fw-bold text-hover-primary mb-1 fs-6">
                                        {{ \Carbon\Carbon::parse($dt->tanggal)->locale('id')->isoFormat('dddd') }}
                                        {{-- {{ $dt->hari }} --}}
                                    </td>
                                    <td class="text-dark fw-bold text-hover-primary mb-1 fs-6">
                                        {{ \Carbon\Carbon::parse($dt->tanggal)->locale('id')->isoFormat('D MMMM YYYY') }}
                                        {{-- {{ $dt->tanggal }} --}}
                                    </td>
                                    <td class="text-dark fw-bold text-hover-primary mb-1 fs-6">
                                        {{ \Carbon\Carbon::createFromFormat('H:i:s', $dt->waktu)->format('H:i') }}
                                    </td>
                                    <td class="text-dark fw-bold text-hover-primary mb-1 fs-6">
                                        {{ $dt->item }}
                                    </td>
                                    <td class="text-center">
                                        <button
                                            onclick="edit({{ $dt->id_data }},'{{ $dt->item }}','{{ $dt->tanggal }}','{{ $dt->hari }}','{{ $dt->waktu }}')"
                                            title="Edit Data"
                                            class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                            <i class="ki-duotone ki-pencil fs-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </button>
                                        <button onclick="hapus({{ $dt->id_data }},'{{ $dt->item }}','{{ $dt->tanggal }}')"
                                            title="Hapus Data"
                                            class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm">
                                            <i class="ki-duotone ki-trash fs-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                                <span class="path4"></span>
                                                <span class="path5"></span>
                                            </i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Tambahkan pagination links -->
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div class="d-flex flex-wrap py-2 mr-3">
                        {{ $data->appends(['search' => request('search')])->links('vendor.pagination.custom') }}
                    </div>
                    <div class="d-flex align-items-center py-3">
                        <span class="text-muted">Menampilkan {{ $data->firstItem() }} - {{ $data->lastItem() }} dari {{ $data->total() }} data</span>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('modal')
    <div class="modal fade" id="modal_form_tambah" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content rounded">
                <div class="modal-header pb-0 border-0 justify-content-end">
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                </div>
                <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                    <form action="{{ url('data') }}" method="POST" class="form" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-13 text-center">
                            <h1 class="mb-3">Form Tambah Data</h1>
                            <div class="text-muted fw-semibold fs-5">Silahkan isikan data dengan sesuai.</div>
                        </div>
                        <div class="row g-9 mb-8">
                            <div class="col-md-12 fv-row">
                                <label for="tanggal" class="required fs-6 fw-semibold mb-2">Tanggal</label>
                                {{-- <input type="datetime-local" id="tanggal" name="tanggal" --}}
                                <input type="date" id="tanggal" name="tanggal"
                                    class="form-control form-control-solid" placeholder="Tanggal" required />
                            </div>
                        </div>
                        {{-- <div class="row g-9 mb-8">
                            <div class="col-md-12 fv-row">
                                <label for="hari" class="required fs-6 fw-semibold mb-2">Hari</label>
                                <input type="text" id="hari" name="hari"
                                    class="form-control form-control-solid" placeholder="Hari" required />
                            </div>
                        </div> --}}
                        <div class="row g-9 mb-8">
                            <div class="col-md-12 fv-row">
                                <label for="waktu" class="required fs-6 fw-semibold mb-2">Waktu</label>
                                <input type="time" id="waktu" name="waktu"
                                    class="form-control form-control-solid" placeholder="Waktu" required />
                            </div>
                        </div>
                        <div class="row g-9 mb-8">
                            <div class="col-md-12 fv-row">
                                <label for="item" class="required fs-6 fw-semibold mb-2">Item</label>
                                <input type="text" id="item" name="item"
                                    class="form-control form-control-solid" placeholder="Item" required />
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="reset" class="btn btn-light me-3">Reset</button>
                            <button type="submit" class="btn btn-primary">
                                <span class="indicator-label">Simpan</span>
                                <span class="indicator-progress">Mohon menunggu...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_form_update" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content rounded">
                <div class="modal-header pb-0 border-0 justify-content-end">
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                </div>
                <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                    <form id="updateform" method="POST" action="{{ url('data/update') }}" class="form"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="id_data" name="id_data">
                        <div class="mb-13 text-center">
                            <h1 class="mb-3">Form Update Data</h1>
                            <div class="text-muted fw-semibold fs-5">Silahkan isikan data dengan sesuai.</div>
                        </div>
                        <div class="row g-9 mb-8">
                            <div class="col-md-12 fv-row">
                                <label for="tanggal_ubah" class="required fs-6 fw-semibold mb-2">Tanggal</label>
                                {{-- <input type="datetime-local" id="tanggal_ubah" name="tanggal" --}}
                                <input type="date" id="tanggal_ubah" name="tanggal"
                                    class="form-control form-control-solid" placeholder="Tanggal" required />
                            </div>
                        </div>
                        {{-- <div class="row g-9 mb-8">
                            <div class="col-md-12 fv-row">
                                <label for="hari_ubah" class="required fs-6 fw-semibold mb-2">Hari</label>
                                <input type="text" id="hari_ubah" name="hari"
                                    class="form-control form-control-solid" placeholder="Hari" required />
                            </div>
                        </div> --}}
                        <div class="row g-9 mb-8">
                            <div class="col-md-12 fv-row">
                                <label for="waktu_ubah" class="required fs-6 fw-semibold mb-2">Waktu</label>
                                <input type="time" id="waktu_ubah" name="waktu"
                                    class="form-control form-control-solid" placeholder="Waktu" required />
                            </div>
                        </div>
                        <div class="row g-9 mb-8">
                            <div class="col-md-12 fv-row">
                                <label for="item_ubah" class="required fs-6 fw-semibold mb-2">Item</label>
                                <input type="text" id="item_ubah" name="item"
                                    class="form-control form-control-solid" placeholder="Item" required />
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="reset" class="btn btn-light me-3">Reset</button>
                            <button type="submit" class="btn btn-primary">
                                <span class="indicator-label">Simpan</span>
                                <span class="indicator-progress">Mohon menunggu...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_form_hapus" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content rounded">
                <div class="modal-header pb-0 border-0 justify-content-end">
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                </div>
                <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                    <form id="form_hapus" class="form" enctype="multipart/form-data">
                        <div class="mb-13 text-center">
                            <h1 class="mb-3">Hapus Data</h1>
                            <div class="text-muted fw-semibold fs-5">
                                Apakah anda yakin untuk menghapus Data <strong><span id="namadelete"></strong></span>?
                            </div>
                        </div>
                        <div class="text-center">
                            <div data-bs-dismiss="modal" class="btn btn-light me-3">Batal</div>
                            <button type="submit" class="btn btn-danger">
                                <span class="indicator-label">Ya, saya yakin</span>
                                <span class="indicator-progress">Mohon menunggu...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_hapus_semua" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content rounded">
                <div class="modal-header pb-0 border-0 justify-content-end">
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                </div>
                <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                    <form id="form_hapus_semua" action="{{ url('data/hapus-semua') }}" method="POST" class="form">
                        @csrf
                        @method('DELETE')
                        <div class="mb-13 text-center">
                            <h1 class="mb-3">Hapus Semua Data</h1>
                            <div class="text-muted fw-semibold fs-5">
                                Apakah anda yakin untuk menghapus semua data? Tindakan ini tidak dapat dibatalkan.
                            </div>
                        </div>
                        <div class="text-center">
                            <div data-bs-dismiss="modal" class="btn btn-light me-3">Batal</div>
                            <button type="submit" class="btn btn-danger">
                                <span class="indicator-label">Ya, hapus semua</span>
                                <span class="indicator-progress">Mohon menunggu...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_upload_excel" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content rounded">
                <div class="modal-header pb-0 border-0 justify-content-end">
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                </div>
                <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                    <form id="form_upload_excel" action="{{ url('data/upload-excel') }}" method="POST" class="form"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="mb-13 text-center">
                            <h1 class="mb-3">Upload File Excel</h1>
                            <div class="text-muted fw-semibold fs-5">Silahkan upload file Excel yang sudah diisi.</div>
                        </div>
                        <div class="row g-9 mb-8">
                            <div class="col-md-12 fv-row">
                                <label for="excel_file" class="required fs-6 fw-semibold mb-2">File Excel</label>
                                <input type="file" id="excel_file" name="excel_file" class="form-control form-control-solid" accept=".xlsx,.xls" required />
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="reset" class="btn btn-light me-3">Reset</button>
                            <button type="submit" class="btn btn-primary">
                                <span class="indicator-label">Upload</span>
                                <span class="indicator-progress">Mohon menunggu...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
<style>
    .pagination {
        display: flex;
        padding-left: 0;
        list-style: none;
        border-radius: 0.475rem;
    }
    .page-item:first-child .page-link {
        border-top-left-radius: 0.475rem;
        border-bottom-left-radius: 0.475rem;
    }
    .page-item:last-child .page-link {
        border-top-right-radius: 0.475rem;
        border-bottom-right-radius: 0.475rem;
    }
    .page-item.active .page-link {
        z-index: 3;
        color: #FFFFFF;
        background-color: #009EF7;
        border-color: #009EF7;
    }
    .page-item.disabled .page-link {
        color: #B5B5C3;
        pointer-events: none;
        background-color: transparent;
        border-color: #E4E6EF;
    }
    .page-link {
        position: relative;
        display: block;
        padding: 0.5rem 0.75rem;
        margin-left: -1px;
        line-height: 1.25;
        color: #7E8299;
        background-color: #ffffff;
        border: 1px solid #E4E6EF;
    }
    .page-link:hover {
        z-index: 2;
        color: #009EF7;
        text-decoration: none;
        background-color: #E4E6EF;
        border-color: #E4E6EF;
    }
    .page-link:focus {
        z-index: 3;
        outline: 0;
        box-shadow: none;
    }
</style>
@endsection

@section('script')
    <script>
        function edit(id_data, item, tanggal, hari, waktu) {
            $('#id_data').val(id_data);
            $('#item_ubah').val(item);
            $('#hari_ubah').val(hari);
            $('#waktu_ubah').val(waktu);
            $('#tanggal_ubah').val(tanggal).change();
            $('#modal_form_update').modal('show');
        }

        function hapus(id_data, item, tanggal) {
            $('#form_hapus').attr('action', {!! json_encode(url('data/hapus/')) !!} + '/' + id_data);
            $('#namadelete').text(item);
            $('#modal_form_hapus').modal('show');
        }

        function hapusSemuaData() {
            $('#modal_hapus_semua').modal('show');
        }
    </script>

    @include('my_components.toastr')
    {{-- @include('my_components.datatables') --}}
@endsection
