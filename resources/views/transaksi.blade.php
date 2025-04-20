@extends('layouts.app')

@section('title', 'MAP - Riwayat Transaksi Pangkalan')

@section('content')

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Riwayat Transaksi Pangkalan</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Semua transaksi pangkalan ditampilkan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="transaction_table" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal Transaksi</th>
                            <th>NIK</th>
                            <th>Tipe NIK</th>
                            <th>Pangkalan</th>
                            <th>Admin Pangkalan</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- <div class="card shadow mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div>
                    <i class="fas fa-robot text-gray-500 mr-2"></i>
                    <span class="font-weight-bold text-primary">Bot Entri data</span>
                </div>
                <span>20 Maret 2025, 08:19 WIB</span>
            </div>
        </div>
        <div class="card-body d-flex justify-content-between">
            <div class="d-flex flex-column">
                <h4 class="font-weight-bold">Transaksi #000004</h4>
                <span>anin@gmail.com</span>
                <span>Pagklan Gas</span>
            </div>
            <div class="d-flex align-items-center">
                <span class="badge badge-success mr-3">Berhasil</span>
                <a class="btn btn-outline" id="detailBtn"><i class="fas fa-eye"></i></a>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Detail Input Transaksi</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="detail_transaksi_table" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Transaksi</th>
                                    <th>NIK</th>
                                    <th>Tipe NIK</th>
                                    <th>Pangkalan</th>
                                    <th>Admin Pangkalan</th>
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
    </div> -->

    <script>
        $(document).ready(function(){
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                }
            });

            let transactionTable = $('#transaction_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('transaksi.getall') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, serachable: false },
                    { 
                        data: 'transaction_date',
                        render: function(data){
                            return formatDate(data);
                        }
                    },
                    { data: 'nik' },
                    { data: 'nik_type' },
                    { 
                        data: 'pangkalan.pangkalan_name' 
                    },
                    { data: 'user.email' }
                ],
            });

            $(document).on('click', '#detailBtn', function(e){
                e.preventDefault();

                $('#detailModal').modal('show');
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
