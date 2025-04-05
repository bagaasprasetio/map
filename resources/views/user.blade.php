@extends('layouts.app')

@section('title', 'MAP - Kelola Data User')

@section('content')

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Kelola Data User</h1>
        <div>
            <button class="d-sm-inline-block btn btn-primary shadow-sm" id="addBtn"><i class="fas fa-user-plus fa-sm mr-1"></i>Tambah data</button>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Semua data user ditampilkan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="userTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama User</th>
                            <th>Email</th>
                            <th>Role User</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="userFormModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-center">
                        <div class="spinner-border text-primary" role="status" id="loadingSection">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <form class="row g-3 d-none" id="userForm">
                        <input type="hidden" id="id">
                        <div class="col-12">
                            <label for="inputEmail4" class="form-label">Nama User</label>
                            <input type="text" class="form-control" id="user_name" name="user_name" placeholder="Ketik di sini">
                            <div id="user_name_error" class="text-danger text-sm d-none"></div>
                        </div>
                        <div class="col-12 mt-2">
                            <label for="inputEmail4" class="form-label">Email</label>
                            <input type="email" class="form-control" id="user_email" name="user_email" placeholder="Ketik di sini">
                            <div id="user_email_error" class="text-danger text-sm d-none"></div>
                        </div>
                        <div class="col-12 mt-2">
                            <label for="inputState" class="form-label">Role User</label>
                            <select id="user_role" class="form-control" name="user_role">
                                <option value="NaN" selected>Pilih di sini</option>
                                <option value="sa">Super Admin</option>
                                <option value="ao">Admin Operasional</option>
                                <option value="ap">Admin Pangkalan</option>
                            </select>
                            <div id="user_role_error" class="text-danger text-sm d-none"></div>
                        </div>
                        <div class="col-12 mt-2" id="user_password_section">
                            <label for="inputPassword4" class="form-label">Set Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="user_password" name="user_password" placeholder="Ketik di sini">
                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                            <div id="user_password_error" class="text-danger text-sm d-none"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline" type="button" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn"></a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="changePassModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ubah Password</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form>
                    <div class="row modal-body">
                        <div class="col-12">
                        <label for="inputPassword4" class="form-label">Masukkan Password Baru</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="user_change_password" name="user_change_password" placeholder="Ketik di sini">
                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                            <div id="user_change_password_error" class="text-danger text-sm d-none"></div>
                            <input type="hidden" id="id_change">
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button class="btn btn-outline" type="button" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="changePassSubmitBtn">Ubah Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){

            let submitBtnText = null;
            
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                }
            });

            let userTable = $('#userTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('user.getall') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, serachable: false },
                    { data: 'user_name' },
                    { data: 'email' },
                    { 
                        data: 'role',
                        render: function(data){
                            const roles = {
                                'sa': 'Super Admin',
                                'ap': 'Admin Pangkalan',
                                'ao': 'Admin Operasional'
                            };
                            return roles[data] || 'Entitas tidak dikenal';
                        }
                     },
                    { data: 'action' }
                ],
            });

            $(document).on("click", ".toggle-password", function() {
                let passwordField = $(this).siblings('input');
                let icon = $(this).find("i");

                if (passwordField.attr("type") === "password") {
                    passwordField.attr("type", "text");
                    icon.removeClass("fa-eye").addClass("fa-eye-slash"); // Ganti ikon
                } else {
                    passwordField.attr("type", "password");
                    icon.removeClass("fa-eye-slash").addClass("fa-eye"); // Balikin ikon
                }
            });

            $('#addBtn').on('click', function(e){
                e.preventDefault();
                submitBtnText = "Tambah data";

                $('#loadingSection').addClass('d-none');
                $('#userForm').removeClass('d-none');

                $("#userFormModal").modal("show");
                $("#userFormModal .modal-title").text('Tambah data user');
                $("#user_password_section").removeClass('d-none');
                $("#userForm")[0].reset();
                $("#submitBtn").text(submitBtnText);
                $("#id").val("");

                $('.is-invalid').each(function() {
                    $(this).removeClass('is-invalid');
                    $('#' + this.id + '_error').addClass('d-none').text('');
                });
            });

            $('#submitBtn').on('click', function(e){
                e.preventDefault();
                
                let id = $("#id").val();
                let url = id ? "{{ route('user.update') }}" : "{{ route('user.add') }}";
                let type = id ? "put" : "post";
                
                $("#submitBtn").text("Loading...");

                let data = {
                    user_name: $('#user_name').val(),
                    user_email: $('#user_email').val(),
                    user_role: $('#user_role').val()
                };

                if (!id){
                    data.user_password = $('#user_password').val();
                } else {
                    data.id = id;
                }

                $.ajax({
                    url: url,
                    type: type,
                    data: data,
                    success: function(response){
                        $('#userFormModal').modal('hide');
                        Swal.fire("Berhasil", id ? "Data berhasil diperbarui." : "Data berhasil ditambahkan.", "success");
                        userTable.ajax.reload(null, false);
                    },
                    error: function(xhr){
                        if (xhr.status === 400){
                            let errors = xhr.responseJSON.message;

                            $('.is-invalid').each(function() {
                                $(this).removeClass('is-invalid');
                                $('#' + this.id + '_error').addClass('d-none').text('');
                            });

                            $.each(errors, function(field, messages) {
                                $('#' + field).addClass('is-invalid'); 
                                $('#' + field + '_error').removeClass('d-none').text(messages[0]);     
                            });

                            $("#submitBtn").text(submitBtnText);
   
                        }
                    }
                });

            });

            $(document).on('click', '#deleteBtn', function(e){
                e.preventDefault();
                let id = $(this).data('id');

                Swal.fire({
                    title: "Konfirmasi",
                    text: "Apakah Anda yakin untuk menghapus data ini?",
                    icon: "warning",
                    cancelButtonColor: "#6c757d",
                    showCancelButton: true,
                    cancelButtonText: "Batal",
                    confirmButtonColor: "#d33",
                    confirmButtonText: "Hapus Data"
                }).then((result) => {
                    if (result.isConfirmed){
                        $.ajax({
                            url: "{{ route('user.delete') }}",
                            type: "delete",
                            data: {
                                id: id
                            },
                            success: function(response){
                                Swal.fire({
                                    title: "Berhasil",
                                    text: "Data berhasil dihapus",
                                    icon: "success"
                                });

                                userTable.ajax.reload(null, false);
                            },
                            error: function(xhr){
                                Swal.fire("Oops!", "Terjadi kesalahan, data gagal dihapus.", "error");
                            }
                        });
                    }
                });
            });

            $(document).on('click', '#editBtn', function(e){
                e.preventDefault();
                let id = $(this).data('id');
                submitBtnText = "Ubah data";
                
                $("#id").val(id);
                $("#userFormModal").modal("show");
                $("#userFormModal .modal-title").text('Edit data user');
                $("#user_password_section").addClass('d-none');
                $("#submitBtn").text(submitBtnText);

                $('#loadingSection').removeClass('d-none');
                $('#userForm').addClass('d-none');

                $('.is-invalid').each(function() {
                    $(this).removeClass('is-invalid');
                    $('#' + this.id + '_error').addClass('d-none').text('');
                });

                $.ajax({
                    url: "{{ route('user.fetch') }}",
                    type: "get",
                    data: {
                        id: id
                    },
                    success: function(response){
                        $('#loadingSection').addClass('d-none');
                        $('#userForm').removeClass('d-none');
                        $("#user_name").val(response.user_name);
                        $("#user_email").val(response.email);
                        $("#user_role").val(response.role);
                    },
                    error: function(xhr){
                        if (xhr.status === 404){
                            let errors = xhr.responseJSON.message;
                            Swal.fire("Oops!", "Terjadi kesalahan, data tidak ditemukan.", "error");
                            //console.log(errors);
                        }
                    }
                });
            });

            $(document).on('click', '#changePassBtn', function(e){
                e.preventDefault();
                let id = $(this).data('id');
                
                $("#id_change").val(id);
                $('#changePassModal').modal('show');
            });

            $(document).on('click', '#changePassSubmitBtn', function(e){
                e.preventDefault();
                let id = $("#id_change").val();

                $.ajax({
                    url: "{{ route('user.changepass') }}",
                    type: "put",
                    data: {
                        id: id,
                        user_change_password: $("#user_change_password").val()
                    },
                    success: function(response){
                        $('#changePassModal').modal('hide');
                        Swal.fire("Berhasil!", "Password berhasil diubah", "success");
                        $("#user_change_password").val('');

                        $('.is-invalid').each(function() {
                            $(this).removeClass('is-invalid');
                            $('#' + this.id + '_error').addClass('d-none').text('');
                        });
                    },
                    error: function(xhr){
                        if (xhr.status === 400){
                            let errors = xhr.responseJSON.message;

                            //console.log(errors);
                            
                            $('.is-invalid').each(function() {
                                $(this).removeClass('is-invalid');
                                $('#' + this.id + '_error').addClass('d-none').text('');
                            });

                            $.each(errors, function(field, messages) {
                                $('#' + field).addClass('is-invalid'); 
                                $('#' + field + '_error').removeClass('d-none').text(messages[0]);     
                            });
                        } else if (xhr.status === 500){
                            Swal.fire('Error!', 'Gagal terhubung ke server', 'error');
                            console.log(xhr.responseJSON);
                        }
                    }
                });
            });

        })
    </script>
    @endsection
