@extends('layouts.app')

@section('title', 'MAP - Beranda')

@section('content')

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Selamat datang, {{ auth()->user()->user_name.'!' }}</h1>
    </div>

    <!-- Content Row -->
    <div class="row">

        @if (auth()->user()->role === 'ap')
        <div class="col-xl-6 col-md-12 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Pangkalan Saat Ini</div>
                                @if ($data->isEmpty())
                                    <div class="h4 mb-0 font-weight-bold text-gray-800">Belum terdaftar di pangkalan</div>
                                @else
                                    @foreach($data as $item)
                                        <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $item->pangkalan_name }}</div>
                                    @endforeach
                                @endif
                        </div>
                        <div>
                            <i class="fas fa-2x fa-shop"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-md-12 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Transaksi Pangkalan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">400 dari <span>400</span></div>
                            <div class="text-xs mb-0 font-weight-bold text-gray-500">Per hari ini</div>
                        </div>
                        <div>
                            <i class="fas fa-2x fa-rotate"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 mx-2" style="border: 3px dashed rgb(219, 219, 219); border-radius: 10px;">
            <div class="card-body">
                <div class="row justify-content-center text-center py-3">
                    <div class="col-6">
                        <i class="fas fa-robot fa-3x text-gray-400 mb-3"></i>
                        <h3 class="fw-bold text-gray-700 mb-1">Mulai input data otomatis</h3>
                        <div>
                            <form>

                            </form>
                            <button class="btn btn-primary shadow-sm mt-2" id="startBotBtn">Mulai Input Data</button>
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

        @endif

        @if (auth()->user()->role === 'ao' || auth()->user()->role === 'sa')

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Transaksi Pangkalan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">400.939</div>
                            <div class="text-xs mb-0 font-weight-bold text-gray-500">Per hari ini</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @endif

        @if (auth()->user()->role === 'sa')
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Pangkalan Aktif</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">18</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total User Aktif</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">18</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>

<div class="modal fade" id="startBotModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="exampleModalLabel">Sebelum dimulai...</h5>
                    <span class="mb-0">Silahkan masukkan PIN akun merchant dan pastikan email sudah sesuai dengan akun merchant untuk akses ke portal subsiditepatlpg.mypertamina.id</span>
                </div>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formBotAttr" class="row g-3">
                    <div class="col-12">
                        <label for="inputEmail4" class="form-label">Email</label>
                        <input type="text" class="form-control" id="pangkalan_email" name="pangkalan_email" placeholder="Ketik di sini" readonly>
                    </div>
                    <div class="col-12 mt-3">
                        <label for="inputEmail4" class="form-label">PIN</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="pangkalan_pin" name="pangkalan_pin" placeholder="Ketik di sini">
                            <button class="btn btn-outline-secondary toggle-password" type="button">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                        <div id="pangkalan_pin_error" class="text-danger text-sm d-none"></div>
                    </div>
                    <div class="col-12 border border-gray border-3 my-3"></div>
                    <div class="col-12">
                        <label for="inputEmail4" class="form-label">Masukkan file excel berisi NIK</label>
                        <input class="form-control" type="file" id="excel_file" name="excel_file">
                        <div id="excel_file_error" class="text-danger text-sm d-none"></div>
                    </div>
                    <div class="col-12 mt-3">
                        <label for="inputEmail4" class="form-label">Jumlah Input Transaksi</label>
                        <input type="text" class="form-control" id="input_transaction" name="input_transaction" placeholder="Ketik di sini">
                        <div id="input_transaction_error" class="text-danger text-sm d-none"></div>
                    </div>
                    <div class="col-12 mt-3">
                        <label for="inputEmail4" class="form-label">Pilih Tipe NIK</label>
                        <div class="d-flex">
                            <div class="form-check mr-4">
                                <input class="form-check-input" type="radio" name="nik_type" value="RT">
                                <label class="form-check-label">Rumah Tangga (RT)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="nik_type" value="UM">
                                <label class="form-check-label">Usaha Mikro (UM)</label>
                            </div>
                        </div>
                        <div id="nik_type_error" class="text-danger text-sm d-none"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <div>
                    <button class="btn btn-outline" type="button" data-dismiss="modal">Cancel</button>
                </div>
                <div>
                    <a class="btn btn-outline" id="startAutomationBtn">Mulai Eksekusi</a>
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
                <button class="btn btn-outline" type="button" data-dismiss="modal">Close</button>
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

        $(document).on("click", ".toggle-password", function() {
            let passwordField = $(this).siblings('input');
            let icon = $(this).find("i");

            if (passwordField.attr("type") === "password") {
                passwordField.attr("type", "text");
                icon.removeClass("fa-eye").addClass("fa-eye-slash");
            } else {
                passwordField.attr("type", "password");
                icon.removeClass("fa-eye-slash").addClass("fa-eye");
            }
        });

        $(document).on('click', '#startBotBtn', function(e){
            e.preventDefault();

            $.ajax({
                url: "{{ route('pangkalan.check') }}",
                type: "get",
                success: function(response){
                    if(response.pangkalan){
                        $('#startBotModal').modal('show');
                        $('#pangkalan_email').val(response.email);
                    } else {
                        Swal.fire("Mohon Maaf!", "Akun pangkalan Anda belum terdaftar di satu pangkalan. Silahkan hubungi Admin untuk bantuan.", "error");
                    }
                },
                error: function(xhr){
                    Swal.fire("Gagal!", "Terjadi kesalahan sistem", "error");
                }
            });


        });

        $('#startAutomationBtn').on('click', function(e){
            e.preventDefault();
            let nikType = $("input[name='nik_type']:checked").val();
            let formData = new FormData($('#formBotAttr')[0]);
            // let excelFile = $('#excel_file')[0].files[0];
            // formData.append("file", excelFile);

            //console.log([...formData.entries()]);

            if (!nikType) {
                Swal.fire("Error", "Pilih tipe NIK terlebih dahulu", "error");
                return;
            }

            $('.is-invalid').each(function() {
                $(this).removeClass('is-invalid');
                $('#' + this.id + '_error').addClass('d-none').text('');
            });

            $.ajax({
                url: "{{ route('automation.check') }}",
                type: "post",
                contentType: false,  // Harus false biar jQuery gak ubah data
                processData: false,  // Harus false biar FormData dikirim beneran
                data: formData,
                success: function(response){
                    Swal.fire({
                        html: `
                            <div class="spinner-container" style="margin-bottom: 10px;">
                                <div class="spinner-border text-primary" style="width: 4rem; height: 4rem;" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                            <span>Bot sedang dalam proses menginput data ke server. Mohon untuk tidak menganggu jalannya bot.</span>
                        `,
                        title: "Sedang Memproses",
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false
                    });

                    executeBot(formData);
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

                    }
                }
            });
        });

        function executeBot(formData){
            $.ajax({
                url: "{{ route('automation.run') }}",
                type: "post",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response){
                    Swal.fire("Berhasil", "Bot berhasil input data, silahkan cek ke website target", "success");
                    $("#startBotModal").modal('hide');
                    $('#formBotAttr')[0].reset();

                    /* $.ajax({
                        url: "{{ route('transaksi.add') }}",
                        type: "post",
                        data: {
                            transaction_total: $('#input_transaction').val(),
                            nik_type: nikType
                        },
                        success: function(response){
                            Swal.fire("Berhasil!", "Input transaksi ke website target selesai", "success");
                        },
                        error: function(xhr){
                            Swal.fire("Gagal!", "Input transaksi gagal", "error");
                        }
                    }); */
                },
                error: function(xhr){
                    Swal.fire("Gagal!", "Terjadi kesalahan saat menjalankan bot.", "error");
                }
            });
        }
    });
</script>

@endsection
