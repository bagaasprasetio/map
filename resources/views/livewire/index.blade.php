@extends('layouts.app')

@section('title', 'MAP - Beranda')

@section('content')

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Earnings (Monthly)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">$40,000</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Earnings (Annual)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">$215,000</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tasks
                            </div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">50%</div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: 50%"
                                            aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Requests Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Requests</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">18</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="row justify-content-center text-center py-3">
                <div class="col-6">
                    <i class="fas fa-robot fa-3x text-gray-400 mb-3"></i>
                    <h3 class="fw-bold text-gray-700 mb-1">Mulai input data otomatis</h3>
                    <p>Pilih file excel untuk melanjutkan</p>
                    <div>
                        <form>
                            <div class="mb-3">
                                <input class="form-control" type="file" id="formFile">
                            </div>
                        </form>
                        <a href="#" class="btn btn-primary shadow-sm mt-2" data-toggle="modal" data-target="#startBotModal">Mulai
                            Eksekusi</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer py-2">
            <div class="d-flex justify-content-center align-items-center">
                <i class="fas fa-circle-question fa-1x text-gray-500 mr-2"></i>
                <a href="#" class="link-underline-primary text-gray-500" data-toggle="modal"
                data-target="#howItModal">Bagaimana cara kerjanya?</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade  wire:ignore.self" id="startBotModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="exampleModalLabel">Sebelum dimulai...</h5>
                    <p class="mb-0">Silahkan masukkan email dan password akun merchant terlebih dahulu untuk akses ke portal subsiditepatlpg.mypertamina.id</p>
                </div>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="row g-3">
                    <div class="col-12">
                        <label for="inputEmail4" class="form-label">Email</label>
                        <input type="text" class="form-control" id="user-name" placeholder="Ketik di sini">
                    </div>
                    <div class="col-12 mt-2">
                        <label for="inputEmail4" class="form-label">Password</label>
                        <input type="password" class="form-control" id="user-email" name="user-email" placeholder="Ketik di sini">
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <div>
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                </div>
                <div>
                    <a class="btn btn-outline" href="#">Mulai Eksekusi</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="howItModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="exampleModalLabel">Informasi cara kerja bot</h5>
                </div>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Lorem ipsum</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection
