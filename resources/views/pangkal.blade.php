@extends('layouts.app')

@section('title', 'MAP - Kelola Data Pangkalan')

@section('content')

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Kelola Data Pangkalan</h1>
        <button class="d-none d-sm-inline-block btn btn-primary shadow-sm" id="addBtn"><i class="fas fa-plus fa-sm mr-1"></i>Tambah data</button>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Semua data pangkalan ditampilkan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="pangkalanTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pangkalan</th>
                            <th>Alamat</th>
                            <th>Kuota Transaksi</th>
                            <th>Admin Pangkalan</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="pangkalanFormModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
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
                    <form class="row g-3 d-none" id="pangkalanForm">
                        <input type="hidden" id="id">
                        <div class="col-12">
                            <label for="inputEmail4" class="form-label">Nama Pangkalan</label>
                            <input type="text" class="form-control" id="pangkalan_name" name="pangkalan_name" placeholder="Ketik di sini">
                            <div id="pangkalan_name_error" class="text-danger text-sm d-none"></div>
                        </div>
                        <div class="col-12 mt-2">
                            <label for="inputEmail4" class="form-label">Alamat</label>
                            <input type="text" class="form-control" id="pangkalan_address" name="pangkalan_address" placeholder="Ketik di sini">
                            <div id="pangkalan_address_error" class="text-danger text-sm d-none"></div>
                        </div>
                        <div class="col-12 mt-2">
                            <label for="inputPassword4" class="form-label">Kuota Transaksi</label>
                            <input type="text" class="form-control" id="pangkalan_quota" name="pangkalan_quota" placeholder="Ketik di sini">
                            <div id="pangkalan_quota_error" class="text-danger text-sm d-none"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" id="submitBtn"></a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="adminModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ubah Admin Pangkalan</h5>
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
                    <form class="row g-3 d-none" id="adminForm">
                        <input type="hidden" id="id_pangkalan">
                        <div class="col-12">
                            <label class="form-label">Pilih Admin Pangkalan</label>
                            <select id="admin_pangkalan_option" name="admin_pangkalan_option" class="form-control"></select>
                            <div id="admin_pangkalan_option_error" class="text-danger text-sm d-none"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" id="assignAdminBtn">Ubah Admin</a>
                </div>
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

            let pangkalanTable = $('#pangkalanTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('pangkalan.getall') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'pangkalan_name' },
                    { data: 'pangkalan_address' },
                    { data: 'transaction_quota' },
                    { 
                        data: 'user.email',
                        render: function(data){
                            if (data){
                                return data;
                            }
                            return 'Belum memiliki admin';
                        }
                    },
                    { data: 'action' }
                ],
            });

            $(document).on('click', '#editBtn', function(e){
                e.preventDefault();
                submitBtnText = "Ubah data";
                let id = $(this).data("id");
                $("#id").val(id);

                $('#pangkalanFormModal').modal('show');
                $("#pangkalanFormModal .modal-title").text('Ubah data pangkalan');
                $('#submitBtn').text(submitBtnText);

                $('.is-invalid').each(function() {
                    $(this).removeClass('is-invalid');
                    $('#' + this.id + '_error').addClass('d-none').text('');
                });

                $('#loadingSection').removeClass('d-none');
                $('#pangkalanForm').addClass('d-none');

                $.ajax({
                    url: "{{ route('pangkalan.fetch') }}",
                    type: "get",
                    data: {
                        id: id
                    },
                    success: function(response){
                        $('#pangkalan_name').val(response.pangkalan_name);
                        $('#pangkalan_address').val(response.pangkalan_address);
                        $('#pangkalan_quota').val(response.transaction_quota);
                        $('#loadingSection').addClass('d-none');
                        $('#pangkalanForm').removeClass('d-none');
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
                            url: "{{ route('pangkalan.delete') }}",
                            type: "delete",
                            data: {
                                id: id
                            },
                            success: function(response){
                                Swal.fire("Berhasil!", "Data berhasil dihapus", "success");
                                pangkalanTable.ajax.reload(null, false);
                            },
                            error: function(xhr){
                                Swal.fire("Oops!", "Terjadi kesalahan, data gagal dihapus.", "error");
                            }
                        });
                    }
                });

            });

            $('#addBtn').on('click', function(e){
                e.preventDefault();
                submitBtnText = "Tambah data";

                $('#pangkalanFormModal').modal('show');
                $("#pangkalanFormModal .modal-title").text('Tambah data pangkalan');
                $("#pangkalanForm")[0].reset();
                $("#id").val("");
                $('#submitBtn').text(submitBtnText);

                $('#loadingSection').addClass('d-none');
                $('#pangkalanForm').removeClass('d-none');

                $('.is-invalid').each(function() {
                    $(this).removeClass('is-invalid');
                    $('#' + this.id + '_error').addClass('d-none').text('');
                });

            });

            $("#submitBtn").on('click', function(e){
                e.preventDefault();
                
                let id = $('#id').val();
                let url = id ? "{{ route('pangkalan.update') }}" : "{{ route('pangkalan.add') }}";
                let type = id ? "put" : "post";
                let data = {
                    pangkalan_name: $('#pangkalan_name').val(),
                    pangkalan_address: $('#pangkalan_address').val(),
                    pangkalan_quota: $('#pangkalan_quota').val()
                }

                if (id){
                    data.id = id;
                }

                $('#submitBtn').text('Loading...');

                $.ajax({
                    url: url,
                    type: type,
                    data: data,
                    success: function(response){
                        $("#pangkalanFormModal").modal("hide");
                        pangkalanTable.ajax.reload(null, false);
                        Swal.fire("Berhasil!", id ? "Data pangkalan berhasil diubah" : "Data pangkalan berhasil ditambahkan", "success");
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
   
                        } else if (xhr.status === 500){
                            Swal.fire("Error", "Gagal menyimpan data, kesalahan server", "error");
                        }
                    }
                });

            });

            $(document).on('click', '#adminBtn', function(e){
                let id = $(this).data('id');
                $('#id_pangkalan').val(id);

                $('#adminModal').modal('show');
                
                $('#adminModal #loadingSection').removeClass('d-none');
                $('#adminForm').addClass('d-none');

                $.ajax({
                    url: "{{ route('user.getadminpangkalan') }}",
                    type: "get",
                    success: function(response){
                        let select = $('#admin_pangkalan_option');
                        select.empty().append('<option value="null">-- Kosongkan Admin Pangkalan --</option>');

                        $.each(response, function(index, item){
                            select.append('<option value="'+ item.id +'">'+ item.email +'</option>')
                        })

                        $('#adminModal #loadingSection').addClass('d-none');
                        $('#adminForm').removeClass('d-none');
                    },
                    error: function(xhr){
                        if (xhr.status === 500){
                            Swal.fire("Error!", "Kesalahan pada sistem", "error");
                            //console.log(xhr.responseJSON.error);
                        } else {
                            Swal.fire("Error!", "Kesalahan server", "error");
                        }
                    }
                });
            });

            $('#assignAdminBtn').on('click', function(e){
                e.preventDefault();

                $.ajax({
                    url: "{{ route('pangkalan.assignadmin') }}",
                    type: "put",
                    data: {
                        user_id: $('#admin_pangkalan_option').val(),
                        id: $('#id_pangkalan').val()
                    },
                    success: function(response){
                        $("#adminModal").modal("hide");
                        pangkalanTable.ajax.reload(null, false);
                        Swal.fire('Berhasil!', 'Admin pangkalan berhasil diubah!', 'success');
                    },
                    error: function(xhr){
                        if (xhr.status === 500){
                            Swal.fire("Error!", "Kesalahan pada sistem", "error");
                            //console.log(xhr.responseJSON.error);
                        } else {
                            Swal.fire("Error!", "Kesalahan server", "error");
                        }
                    }
                });
            });
        });
    </script>
    @endsection
