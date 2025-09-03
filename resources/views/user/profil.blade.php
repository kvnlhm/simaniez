@extends('master')
@section('judul', 'Profil')
@section('breadcrumb')
    <h1 class="page-heading d-flex title-custom fw-bolder fs-2hx flex-column justify-content-center my-0">
        Halaman Profil</h1>
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
        <li class="breadcrumb-item text-gray-700">Profil</li>
    </ul>
@endsection

@section('konten')
    <form id="kt_ecommerce_add_product_form" class="form d-flex flex-column flex-lg-row" method="POST"
        action="{{ url('user/profil/update') }}" enctype="multipart/form-data">
        @csrf
        <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
            <div class="card card-flush py-4">
                <div class="card-header">
                    <div class="card-title">
                        <h2>Foto Profil</h2>
                    </div>
                </div>
                <div class="card-body text-center pt-0">
                    <div class="image-input image-input-empty image-input-outline image-input-placeholder mb-3"
                        data-kt-image-input="true">
                        <div class="image-input-wrapper w-150px h-150px"
                            style="background-image: url({{ asset('public/storage/images/user/' . $user->foto) }})"></div>
                        <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                            data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Ganti Foto">
                            <i class="ki-duotone ki-pencil fs-7">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <input type="file" name="foto" accept=".png, .jpg, .jpeg" />
                            <input type="hidden" name="foto_lama" value="{{ $user->foto }}" />
                            <input type="hidden" name="avatar_remove" />
                        </label>
                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                            data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Batalkan Foto">
                            <i class="ki-duotone ki-cross fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                            data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Hapus Foto">
                            <i class="ki-duotone ki-cross fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                    </div>
                    <div class="text-muted fs-7">Atur foto profil. Hanya file gambar *.png, *.jpg dan *.jpeg yang dapat
                        diterima</div>
                </div>
            </div>
            @if (Auth::user()->id_priv == 1)
                <div class="card card-flush py-4">
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Hak Akses</h2>
                        </div>
                        <div class="card-toolbar">
                            <div class="rounded-circle w-15px h-15px" id="hak_akses"></div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <select class="form-select mb-2" name="id_priv" data-control="select2" data-hide-search="true"
                            data-placeholder="Pilih Hak Akses" id="pilih_hak_akses"
                            onchange="updateHakAksesIndicator(this.value)">
                            <option></option>
                            <option value="1" {{ $user->id_priv == 1 ? 'selected' : '' }}>Admin</option>
                            <option value="2" {{ $user->id_priv == 2 ? 'selected' : '' }}>Pengguna</option>
                        </select>
                    </div>
                </div>
            @endif
        </div>
        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
            <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-n2">
                <li class="nav-item">
                    <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#umum">Informasi
                        Umum</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#password">Password</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="umum" role="tab-panel">
                    <div class="d-flex flex-column gap-7 gap-lg-10">
                        <div class="card card-flush py-4">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Informasi Umum</h2>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="mb-10 fv-row">
                                    <label class="required form-label">Username</label>
                                    <input type="text" name="name" class="form-control mb-2" placeholder="Username"
                                        value="{{ $user->name }}" required />
                                    <div class="text-muted fs-7">Username wajib diisi dan disarankan agar unik.</div>
                                </div>
                                <div class="mb-10 fv-row">
                                    <label class="required form-label">Nama Lengkap</label>
                                    <input type="text" name="nama_lengkap" class="form-control mb-2"
                                        placeholder="Nama Lengkap" value="{{ $user->nama_lengkap }}" required />
                                    <div class="text-muted fs-7">Nama lengkap wajib diisi.</div>
                                </div>
                                <div class="mb-10 fv-row">
                                    <label class="required form-label">Email</label>
                                    <input type="email" name="email" class="form-control mb-2" placeholder="Email"
                                        value="{{ $user->email }}" required />
                                    <div class="text-muted fs-7">Email wajib diisi.</div>
                                </div>
                                <div class="mb-10 fv-row">
                                    <label class="required form-label">No. Telepon</label>
                                    <input type="number" name="no_telp" class="form-control mb-2"
                                        placeholder="No. Telepon" value="{{ $user->no_telp }}" required minlength="10"
                                        maxlength="13" min="0" />
                                    <div class="text-muted fs-7">No. telepon wajib diisi.</div>
                                </div>
                                <div>
                                    <label class="form-label">Alamat</label>
                                    <textarea name="alamat" class="form-control mb-2" placeholder="Alamat" required>{{ $user->alamat }}</textarea>
                                    <div class="text-muted fs-7">Alamat wajib diisi.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="password" role="tab-panel">
                    <div class="d-flex flex-column gap-7 gap-lg-10">
                        <div class="card card-flush py-4">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Password</h2>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="mb-10 fv-row">
                                    <label class="required form-label">Password</label>
                                    <input type="password" name="password" class="form-control mb-2"
                                        placeholder="•••••••••••" />
                                    <div class="text-muted fs-7">Password wajib diisi.</div>
                                </div>
                                <div class="mb-10 fv-row">
                                    <label class="required form-label">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" class="form-control mb-2"
                                        placeholder="•••••••••••" />
                                    <div class="text-muted fs-7">Masukkan kembali password untuk konfirmasi.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <a href="{{ url('dashboard') }}" class="btn btn-light me-5">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <span class="indicator-label">Simpan</span>
                    <span class="indicator-progress">Mohon menunggu...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
            </div>
        </div>
    </form>
@endsection

@section('css')
@endsection

@section('script')
    <script>
        function updateHakAksesIndicator(value) {
            var hakAksesIndicator = document.getElementById('hak_akses');
            if (value === '1') {
                hakAksesIndicator.className = 'rounded-circle bg-success w-15px h-15px';
            } else if (value === '2') {
                hakAksesIndicator.className = 'rounded-circle bg-danger w-15px h-15px';
            }
        }
        // Initialize the indicator based on the selected option
        document.addEventListener('DOMContentLoaded', function() {
            var selectedValue = document.getElementById('pilih_hak_akses').value;
            updateHakAksesIndicator(selectedValue);
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const password = document.querySelector('input[name="password"]');
                const confirmPassword = document.querySelector('input[name="password_confirmation"]');

                if (password.value !== confirmPassword.value) {
                    e.preventDefault(); // Prevent form submission
                    alert('Password dan konfirmasi password tidak cocok.');
                    confirmPassword.focus(); // Focus on the confirmPassword input
                }
            });
        });
    </script>

    @include('my_components.toastr')
    @include('my_components.datatables')
@endsection
