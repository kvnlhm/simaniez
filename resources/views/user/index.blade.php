@extends('master')
@section('judul', 'Pengguna')
@section('breadcrumb')
    <h1 class="page-heading d-flex title-custom fw-bolder fs-2hx flex-column justify-content-center my-0">
        Halaman Pengguna</h1>
    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
        <li class="breadcrumb-item text-gray-700">
            <a href="{{ url('dashboard') }}" class="text-gray-700 text-hover-primary"><i class="ki-duotone ki-home"></i></a>
        </li>
        <li class="breadcrumb-item">
            <span class="">
                <i class="ki-duotone ki-right fs-6 text-gray-700"></i>
            </span>
        </li>
        <li class="breadcrumb-item text-gray-700">Menu lainnya</li>
        <li class="breadcrumb-item">
            <span class="">
                <i class="ki-duotone ki-right fs-6 text-gray-700"></i>
            </span>
        </li>
        <li class="breadcrumb-item text-gray-700">Pengguna</li>
    </ul>
@endsection

@section('konten')
    <div class="card mb-5 mb-xl-8">
        <div class="card-header border-0 pt-5">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bold fs-3 mb-1">Tabel</span>
                <span class="text-muted mt-1 fw-semibold fs-7">Daftar Pengguna</span>
            </h3>
            <div class="card-toolbar">
                <a href="#" data-bs-toggle="modal" data-bs-target="#modal_form_tambah"
                    class="btn btn-sm btn-light-warning">
                    <i class="ki-duotone ki-plus fs-2"></i>Tambah Pengguna</a>
            </div>
        </div>
        <div class="card-body py-3">
            <div class="table-responsive">
                <table id="kt_datatable_dom_positioning"
                    class="table table-row-bordered border rounded align-middle gs-0 gy-4">
                    <thead>
                        <tr class="fw-bold text-muted bg-light">
                            <th class="ps-4 min-w-40px rounded-start">No.</th>
                            <th class="min-w-100px">Nama Pengguna</th>
                            <th class="min-w-100px">Email</th>
                            <th class="min-w-100px">Nama Lengkap</th>
                            <th class="min-w-100px">Nomor Telepon</th>
                            <th class="min-w-100px">Alamat</th>
                            <th class="min-w-100px">Hak Akses</th>
                            <th class="min-w-100px">Foto</th>
                            <th class="min-w-100px text-center rounded-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                        @endphp
                        @foreach ($data as $dt)
                            <tr>
                                <td class="ps-4 text-dark fw-bold text-hover-primary mb-1 fs-6">
                                    {{ $i }}
                                </td>
                                <td class="text-dark fw-bold text-hover-primary mb-1 fs-6">
                                    {{ $dt->name }}
                                </td>
                                <td class="text-dark fw-bold text-hover-primary mb-1 fs-6">
                                    {{ $dt->email }}
                                </td>
                                <td class="text-dark fw-bold text-hover-primary mb-1 fs-6">
                                    {{ $dt->nama_lengkap }}
                                </td>
                                <td class="text-dark fw-bold text-hover-primary mb-1 fs-6">
                                    {{ $dt->no_telp }}
                                </td>
                                <td class="text-dark fw-bold text-hover-primary mb-1 fs-6">
                                    {{ $dt->alamat }}
                                </td>
                                <td class="text-dark fw-bold text-hover-primary mb-1 fs-6">
                                    {{ $dt->priv->nama }}
                                </td>
                                <td class="text-dark fw-bold text-hover-primary mb-1 fs-6">
                                    @if ($dt->foto != null)
                                        <img src="{{ asset('public/storage/images/user/' . $dt->foto) }}"alt=""
                                            width="100px">
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button
                                        onclick="edit({{ $dt->id }},{{ $dt->id_priv }},'{{ $dt->name }}','{{ $dt->email }}','{{ $dt->nama_lengkap }}','{{ $dt->no_telp }}','{{ $dt->alamat }}','{{ $dt->foto }}')"
                                        title="Edit Pengguna"
                                        class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                        <i class="ki-duotone ki-pencil fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </button>
                                    <button onclick="editpass({{ $dt->id }},'{{ $dt->name }}')"
                                        title="Edit Password Pengguna"
                                        class="btn btn-icon btn-bg-light btn-active-color-warning btn-sm me-1">
                                        <i class="ki-duotone ki-key fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </button>
                                    <button onclick="hapus({{ $dt->id }},'{{ $dt->name }}')"
                                        title="Hapus Pengguna"
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
                            @php
                                $i++;
                            @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
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
                    <form action="{{ url('user') }}" method="POST" class="form" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-13 text-center">
                            <h1 class="mb-3">Form Tambah Pengguna</h1>
                            <div class="text-muted fw-semibold fs-5">Silahkan isikan data dengan sesuai.</div>
                        </div>
                        <div class="row g-9 mb-8">
                            <div class="col-md-6 fv-row">
                                <label for="name" class="required fs-6 fw-semibold mb-2">Username</label>
                                <input type="text" id="name" name="name" class="form-control form-control-solid"
                                    placeholder="Nama Pengguna" required />
                            </div>
                            <div class="col-md-6 fv-row">
                                <label for="email" class="required fs-6 fw-semibold mb-2">Email</label>
                                <input type="email" id="email" name="email"
                                    class="form-control form-control-solid" placeholder="Email" required />
                            </div>
                        </div>
                        <div class="row g-9 mb-8">
                            <div class="col-md-6 fv-row">
                                <label for="nama_lengkap" class="required fs-6 fw-semibold mb-2">Nama Lengkap</label>
                                <input type="text" id="nama_lengkap" name="nama_lengkap"
                                    class="form-control form-control-solid" placeholder="Nama Lengkap" required />
                            </div>
                            <div class="col-md-6 fv-row">
                                <label for="no_telp" class="required fs-6 fw-semibold mb-2">Nomor Telepon</label>
                                <input type="number" id="no_telp" name="no_telp"
                                    class="form-control form-control-solid" placeholder="Nomor Telepon" minlength="10"
                                    maxlength="13" min="0" required />
                            </div>
                        </div>
                        <div class="d-flex flex-column mb-8 fv-row">
                            <label for="alamat" class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                <span class="required">Alamat</span>
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                </i>
                                </span>
                            </label>
                            <textarea class="form-control form-control-solid" name="alamat" id="alamat" cols="30" rows="5"></textarea>
                        </div>
                        <div class="row g-9 mb-8">
                            <div class="col-md-12 fv-row">
                                <label for="password" class="required fs-6 fw-semibold mb-2">Password</label>
                                <input type="password" id="password" name="password"
                                    class="form-control form-control-solid" placeholder="•••••••••••" required />
                            </div>
                        </div>
                        <div class="row g-9 mb-8">
                            <div class="col-md-6 fv-row" id="pilih_priv">
                                <label class="required fs-6 fw-semibold mb-2">Hak Akses</label>
                                <select id="id_priv" name="id_priv" data-control="select2"
                                    data-dropdown-parent="#pilih_priv" data-placeholder="Pilih Hak Akses"
                                    class="form-select form-select-solid" required>
                                    <option></option>
                                    @foreach ($priv as $p)
                                        <option value="{{ $p->id_priv }}">{{ $p->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 fv-row">
                                <label for="foto" class="required fs-6 fw-semibold mb-2">Foto</label>
                                <input type="file" id="foto" name="foto"
                                    class="form-control form-control-solid" placeholder="Foto" accept="image/*"
                                    required />
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
                    <form id="updateform" action="{{ url('user/update') }}" method="POST" class="form"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="id_user" name="id">
                        <input type="hidden" id="foto_lama" name="foto_lama">
                        <div class="mb-13 text-center">
                            <h1 class="mb-3">Form Update Pengguna</h1>
                            <div class="text-muted fw-semibold fs-5">Silahkan isikan data dengan sesuai.</div>
                        </div>
                        <div class="row g-9 mb-8">
                            <div class="col-md-6 fv-row">
                                <label for="name_ubah" class="required fs-6 fw-semibold mb-2">Username</label>
                                <input type="text" id="name_ubah" name="name"
                                    class="form-control form-control-solid" placeholder="Nama Pengguna" required />
                            </div>
                            <div class="col-md-6 fv-row">
                                <label for="email_ubah" class="required fs-6 fw-semibold mb-2">Email</label>
                                <input type="email" id="email_ubah" name="email"
                                    class="form-control form-control-solid" placeholder="Email" required />
                            </div>
                        </div>
                        <div class="row g-9 mb-8">
                            <div class="col-md-6 fv-row">
                                <label for="nama_lengkap_ubah" class="required fs-6 fw-semibold mb-2">Nama Lengkap</label>
                                <input type="text" id="nama_lengkap_ubah" name="nama_lengkap"
                                    class="form-control form-control-solid" placeholder="Nama Lengkap" required />
                            </div>
                            <div class="col-md-6 fv-row">
                                <label for="no_telp_ubah" class="required fs-6 fw-semibold mb-2">Nomor Telepon</label>
                                <input type="number" id="no_telp_ubah" name="no_telp"
                                    class="form-control form-control-solid" placeholder="Nomor Telepon" minlength="10"
                                    maxlength="13" min="0" required />
                            </div>
                        </div>
                        <div class="d-flex flex-column mb-8 fv-row">
                            <label for="alamat_ubah" class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                <span class="required">Alamat</span>
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                </i>
                                </span>
                            </label>
                            <textarea class="form-control form-control-solid" id="alamat_ubah" name="alamat" cols="30" rows="5"></textarea>
                        </div>
                        <div class="row g-9 mb-8">
                            <div class="col-md-6 fv-row" id="pilih_priv_ubah">
                                <label for="id_priv_ubah" class="required fs-6 fw-semibold mb-2">Hak Akses</label>
                                <select id="id_priv_ubah" name="id_priv" data-control="select2"
                                    data-dropdown-parent="#pilih_priv_ubah" data-placeholder="Pilih Hak Akses"
                                    class="form-select form-select-solid" required>
                                    <option></option>
                                    @foreach ($priv as $p)
                                        <option value="{{ $p->id_priv }}">{{ $p->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 fv-row">
                                <label for="foto_ubah" class="required fs-6 fw-semibold mb-2">Foto</label>
                                <input type="file" id="foto_ubah" name="foto"
                                    class="form-control form-control-solid" placeholder="Foto" accept="image/*"
                                    required />
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

    <div class="modal fade" id="modal_update_pass" tabindex="-1" aria-hidden="true">
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
                    <form action="{{ url('/user/updatepass') }}" method="POST" class="form"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="idPass" name="id">
                        <input type="hidden" id="namePass" name="name">
                        <div class="mb-13 text-center">
                            <h1 class="mb-3">Form Update Password Pengguna</h1>
                            <div class="text-muted fw-semibold fs-5">Silahkan isikan data dengan sesuai.</div>
                        </div>
                        <div class="d-flex flex-column mb-8 fv-row">
                            <label for="passwordUbah" class="required fs-6 fw-semibold mb-2">Password</label>
                            <input type="password" id="passwordUbah" name="password" value="{{ old('password') }}"
                                placeholder="•••••••••••" required
                                class="form-control form-control-solid
                        @error('password')
                            is-invalid
                        @enderror" />
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
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
                            <h1 class="mb-3">Hapus Pengguna</h1>
                            <div class="text-muted fw-semibold fs-5">
                                Apakah anda yakin untuk menghapus Pengguna <strong><span id="namadelete"></strong></span>?
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
@endsection

@section('css')
@endsection

@section('script')
    <script>
        function edit(id_user, id_priv, name, email, nama_lengkap, no_telp, alamat, foto) {
            $('#id_user').val(id_user);
            $('#id_priv_ubah').val(id_priv).change();
            $('#foto_lama').val(foto);
            $('#name_ubah').val(name);
            $('#email_ubah').val(email);
            $('#nama_lengkap_ubah').val(nama_lengkap);
            $('#no_telp_ubah').val(no_telp);
            $('#alamat_ubah').val(alamat);
            $('#modal_form_update').modal('show');
        }

        function hapus(id, name) {
            $('#form_hapus').attr('action', {!! json_encode(url('user/hapus/')) !!} + '/' + id);
            $('#namadelete').text(name);
            $('#modal_form_hapus').modal('show');
        }

        function editpass(id, name) {
            $('#idPass').val(id);
            $('#namePass').val(name);
            $('#modal_update_pass').modal('show');
        }
    </script>

    @include('my_components.toastr')
    @include('my_components.datatables')
@endsection
