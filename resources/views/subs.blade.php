@extends('layouts.app')

@section('title', 'MAP - Kelola Langganan')

@section('content')

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Kelola Langganan</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Semua data langganan admin pangkalan ditampilkan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Akun Pangkalan</th>
                            <th>Nama Pangkalan</th>
                            <th>Status Langganan</th>
                            <th>Riwayat Langganan</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="subsHistoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Riwayat Langganan</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5 id="emptyText" class="font-weight-bold text-center d-none">Belum ada riwayat langganan</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="subsHistoryTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Berakhir</th>
                                    <th>Didaftarkan Oleh</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-end">
                    <button class="btn btn-outline" type="button" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="subsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Perbarui Langganan Akun Pangkalan</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form>
                    <div class="row modal-body">
                        <div class="col-12">
                            <label class="form-label">Pilih Tanggal Berakhir Langganan*</label>
                            <input type="date" id="subs_end" name="subs_end" class="form-control">
                            <div id="subs_end_error" class="text-danger text-sm d-none"></div>
                            <input type="hidden" id="id_ap">
                        </div>
                        <div class="col-12 text-sm text-italic font-italic">*Tanggal awal langganan dimulai per hari ini</div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button class="btn btn-outline" type="button" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="subsRenewBtn">Perbarui Langganan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                }
            });

            let pangkalanAccTable = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('user.getusersubs') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'email', name: 'email' },
                    { 
                        data: 'pangkalan.pangkalan_name', 
                        name: 'pangkalan.pangkalan_name',
                        render: function(data, type, row){
                            if (data){
                                return data;
                            }
                            return 'Belum ada pangkalan';
                        }
                     },
                    { 
                        data: 'subscriptions',
                        searchable: false,
                        orderable: false,
                        render: function(data){
                            let today = new Date();
                            today.setHours(0,0,0,0);
                            
                            let status = "<span class='badge badge-warning'>Belum ada langganan</span>";

                            if (data && data.length > 0){
                                for (let i = 0; i < data.length; i++){
                                    let subsStart = new Date(data[i].subs_start);
                                    let subsEnd = new Date(data[i].subs_end);

                                    subsStart.setHours(0,0,0,0);
                                    subsEnd.setHours(0,0,0,0);
                                    
                                    if (today >= subsStart && today <= subsEnd){
                                        status = "<span class='badge badge-success'>Aktif</span>";
                                        break;
                                    }
                                }

                                if (status !== "<span class='badge badge-success'>Aktif</span>") {
                                    status = "<span class='badge badge-danger'>Tidak Aktif</span>";
                                }
                            } 

                            return status;
                        }
                    },
                    { data: 'subs_history', name: 'subs_history' },
                    { data: 'action', name: 'action' }
                ]
            });

            $(document).on('click', '#subsHistoryBtn', function() {
                let url = $(this).data('url');

                if ($.fn.DataTable.isDataTable('#subsHistoryTable')) {
                    $('#subsHistoryTable').DataTable().destroy();
                }
                
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response){
                        if(response.data.length === 0){
                            $('#subsHistoryTable').hide();
                            $('#emptyText').removeClass('d-none');
                        } else {
                            $('#emptyText').addClass('d-none');
                            $('#subsHistoryTable').show();
                            
                            $('#subsHistoryTable').DataTable({
                                processing: true,
                                serverSide: true,
                                ajax: {
                                    url: url,
                                    type: 'GET',
                                    error: function(xhr, error, thrown) {
                                        console.log(xhr.responseText);
                                    }
                                },
                                columns: [
                                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                                    { 
                                        data: 'subs_start', 
                                        name: 'subs_start',
                                        render: function(data) {
                                            return formatDate(data);
                                        }
                                    },
                                    { 
                                        data: 'subs_end', 
                                        name: 'subs_end',
                                        render: function(data) {
                                            return formatDate(data);
                                        }
                                    },
                                    { data: 'registered_by.user_name', name: 'user_name' },
                                    { data: 'action' }
                                ]
                            });
                        }
                    }
                });
            });

            $(document).on('click', '#deleteSubsDateBtn', function(e){
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
                            url: "{{ route('subs.delete') }}",
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

                                $('#subsHistoryTable').DataTable().ajax.reload(null, false);
                                pangkalanAccTable.ajax.reload(null, false);
                            },
                            error: function(xhr){
                                Swal.fire("Oops!", "Terjadi kesalahan, data gagal dihapus.", "error");
                            }
                        });
                    }
                });
            });

            $(document).on('click', '#subsBtn', function() {
                let id = $(this).data('id');
                $('#id_ap').val(id);

                $('.is-invalid').each(function() {
                    $(this).removeClass('is-invalid');
                    $('#' + this.id + '_error').addClass('d-none').text('');
                });
                
                $.ajax({
                    url: "{{ route('subs.check') }}",
                    type: "get",
                    data: {
                        id_ap: id
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.subs_active.length === 0){
                            $('#subsModal').modal('show');
                        } else {
                            Swal.fire("Ooops!", "Status berlangganan akun pangkalan masih aktif", "warning");
                        }
                    }, 
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });

            $('#subsRenewBtn').on('click', function(e) {
                e.preventDefault();

                let subsEnd = $('#subs_end').val();
                let idAp = $('#id_ap').val();
                
                $.ajax({
                    url: "{{ route('subs.renewal') }}",
                    type: "post",
                    data: {
                        subs_end: subsEnd,
                        subs_start: new Date().toISOString().split('T')[0],
                        id_ap: idAp
                    },
                    success: function(response){
                        $('#subsModal').modal('hide');
                        Swal.fire("Berhasil!", "Status berlangganan akun sudah aktif", "success");

                        $('#subs_end').val('');
                        pangkalanAccTable.ajax.reload(null, false);
                    },
                    error: function(xhr){
                        if (xhr.status === 400){
                            //console.log(xhr.responseJSON.message['subs_end']);
                            let errors = xhr.responseJSON.message;

                            $('.is-invalid').each(function() {
                                $(this).removeClass('is-invalid');
                                $('#' + this.id + '_error').addClass('d-none').text('');
                            });

                            $.each(errors, function(field, messages) {
                                $('#' + field).addClass('is-invalid'); 
                                $('#' + field + '_error').removeClass('d-none').text(messages[0]);         
                            });
   
                        }
                    }
                });
            });

        });
        
        function formatDate(data){
            if (data) {
                let date = new Date(data);
                return date.toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric'
                }).replace(/\//g, ' ');
            }
            return '-';
        }
    </script>
    @endsection
