@extends('admin.layouts.master')
@section('title', 'Edit Karyawan')

@section('content')
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Data Karyawan</h3>
                <p class="text-subtitle text-muted">Silakan isi data karyawan yang ingin diubah</p>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h5 class="alert-heading">Edit Error!</h5>
                    @foreach ($errors->all() as $error)
                        <li>
                            {{ $error }}</i>
                        </li>
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <form class="form" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data"
                method="POST">
                @method('PUT')
                @csrf
                <form action="form-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="name">Nama Karyawan</label>
                                <input type="text" class="form-control" id="name"
                                    placeholder="Masukkan Nama Karyawan" name="fullname" value="{{ $user->fullname }}"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" id="username" placeholder="Masukkan Username"
                                    name="username" value="{{ $user->username }}" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Nomor Telepon</label>
                                <input type="tel" class="form-control" id="phone"
                                    placeholder="Masukkan nomor telepon" name="phone" value="{{ $user->phone }}"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="role">Role</label>
                                <select class="form-control" id="role" name="role_id" required>
                                    <option value="" disabled selected>Pilih Role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}"
                                            {{ $user->role_id === $role->id ? 'selected' : '' }}>
                                            {{ $role->role_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" placeholder="Masukkan email"
                                    name="email" value="{{ $user->email }}" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" placeholder="Masukkan password"
                                    name="password">
                                <small><a href="#" class="toggle-password" data-target="password">Lihat
                                        Password</a></small>
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation">Konfirmasi Password</label>
                                <input type="password" class="form-control" id="password_confirmation"
                                    placeholder="Masukkan Konfirmasi password" name="password_confirmation">
                                <small><a href="#" class="toggle-password" data-target="password_confirmation">Lihat
                                        Password</a></small>
                            </div>

                            <div class="form-group d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary me-1 mb-1">Simpan</button>
                                {{-- <button type="submit" class="btn btn-light-secondary me-1 mb-1">Reset</button> --}}
                                <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                                <a href="{{ route('users.index') }}" type="submit"
                                    class="btn btn-light-secondary me-1 mb-1">Batal</a>
                            </div>

                        </div>
                </form>
            </form>
        </div>
    </div>
@endsection

<script>
    // document.querySelectorAll('.toggle-password').forEach(el => {
    //     el.addEventListener('click', function(e) {
    //         e.preventDefault();
    //         let input = document.getElementByID(this.dataset.target);
    //         let isHidden = input.type === 'password';
    //         input.type = isHidden ? 'text' : 'password';
    //         document.querySelectorAll(`a[data-target="${this.dataset.target}"]`).textContent =
    //             isHidden ?
    //             'Sembunyikan Password' : 'Lihat Password';
    //     });
    // });
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.toggle-password').forEach(function(toggle) {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                const target = this.getAttribute('data-target');
                const passwordField = document.getElementById(target);

                if (passwordField.type === 'password') {
                    passwordField.type = 'text';
                    this.textContent = 'Sembunyikan Password';
                } else {
                    passwordField.type = 'password';
                    this.textContent = 'Lihat Password';
                }
            });
        });
    });
</script>
