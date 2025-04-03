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
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>20 April 2025, 15:03</td>
                            <td>3271 0413 0765 6754</td>
                            <td>RT</td>
                            <td>Pangkalan Gas Haji Halimah</td>
                            <td>Hj. Halimas</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="transaksiModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Detail Admin Pangkalan</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Admin</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>System Architect (System Architect)</td>
                                    <td>
                                        <a class="btn btn-outline-delete" href="#" data-toggle="modal"
                                            data-target="#pangkalDeleteModal"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="#"><i class="fas fa-user-plus fa-sm mr-1"></i>Tambah admin</a>
                </div>
            </div>
        </div>
    </div>

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
                    { data: 'transaction_date' },
                    { data: 'nik' },
                    { data: 'nik_type' },
                    { data: 'user_id' },
                    { data: 'pangkalan_id' }
                ],
            })
        });
    </script>
    @endsection
