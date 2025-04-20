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
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $transactions }}</div>
                            <div class="text-xs mb-0 font-weight-bold text-gray-500">Per hari ini</div>
                        </div>
                        <div>
                            <i class="fas fa-2x fa-rotate"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{--  <div class="col-12 mx-2" style="border: 3px dashed rgb(219, 219, 219); border-radius: 10px;">
            <div class="card-body">
                <div class="row justify-content-center text-center py-3">
                    <div class="col-6">
                        <i class="fas fa-robot fa-3x text-gray-400 mb-3"></i>
                        <h3 class="fw-bold text-gray-700 mb-1">Mulai input data otomatis</h3>
                        <button class="btn btn-primary shadow-sm mt-2" id="startBotBtn">Mulai Input Data</button>
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
        </div>  --}}
        <div class="col-12 mx-2" style="border: 3px dashed rgb(219, 219, 219); border-radius: 10px;">
            <div class="card-body">
                <div class="row justify-content-center text-center py-3">
                    <div class="col-6">
                        <i class="fas fa-robot fa-3x text-secondary mb-3"></i>
                        <h3 class="fw-bold text-dark mb-1">Mulai input data otomatis</h3>
                        <button class="btn btn-primary shadow-sm mt-2" id="startBotBtn">Mulai Input Data</button>

                        <!-- Status Bot Online/Offline -->
                        <div class="text-center mt-3" id="status-indicator">
                            <div id="botStatusBox" class="d-inline-flex align-items-center px-3 py-2 rounded-pill shadow-sm" style="background-color: #e6ffed; transition: background-color 0.3s ease;">
                                <i class="fas fa-circle text-success mr-2" id="status-icon" style="font-size: 10px;"></i>
                                <span id="status-text" class="font-weight-bold text-success" style="font-size: 14px;">Bot Online</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="card-footer py-2">
                <div class="d-flex justify-content-center align-items-center">
                    <i class="fas fa-circle-question text-muted me-2"></i>
                    <a href="#" class="text-decoration-none text-muted" data-bs-toggle="modal"
                       data-bs-target="#howItModal">Bagaimana cara kerjanya?</a>
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $transactions }}</div>
                            <div class="text-xs mb-0 font-weight-bold text-gray-500">Per hari ini</div>
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
                                Total Pangkalan Terdaftar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pangkalan }}</div>
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
                                Total User Terdaftar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $user }}</div>
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
                        <label for="inputEmail4" class="form-label">PIN Merchant (6 Digit)</label>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function updateBotStatus() {
        fetch("{{ route('check.job.status') }}")
            .then(response => response.json())
            .then(data => {
                const botBox = document.getElementById('botStatusBox');
                const statusIcon = document.getElementById('status-icon');
                const statusText = document.getElementById('status-text');
                const startButton = document.getElementById('startBotBtn');

                if (data.status) {
                    // Bot Offline - >= 3 jobs
                    botBox.style.backgroundColor = "#ffe6e6";
                    statusIcon.classList.remove('text-success');
                    statusIcon.classList.add('text-danger');
                    statusText.classList.remove('text-success');
                    statusText.classList.add('text-danger');
                    statusText.textContent = "Bot Offline - Dalam Antrian";

                    // Disable tombol
                    startButton.setAttribute("disabled", true);
                    startButton.classList.add("btn-secondary");
                    startButton.classList.remove("btn-primary");

                } else {
                    // Bot Online - < 3 jobs
                    botBox.style.backgroundColor = "#e6ffed";
                    statusIcon.classList.remove('text-danger');
                    statusIcon.classList.add('text-success');
                    statusText.classList.remove('text-danger');
                    statusText.classList.add('text-success');
                    statusText.textContent = "Bot Online";

                    // Enable tombol
                    startButton.removeAttribute("disabled");
                    startButton.classList.add("btn-primary");
                    startButton.classList.remove("btn-secondary");
                }
            })
            .catch(error => {
                console.error("Gagal cek status bot:", error);
            });
    }

    setInterval(updateBotStatus, 5000);
    updateBotStatus();
</script>

<script>

    $(document).ready(function(){


        let countdownInterval = null;
        let progressChecker = null;
        let isPolling = false;

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            }
        });

        document.getElementById('pangkalan_pin').addEventListener('input', function (e) {
            this.value = this.value.replace(/\D/g, '').slice(0, 6);
        });

        document.getElementById('input_transaction').addEventListener('input', function (e) {
            this.value = this.value.replace(/\D/g, '').slice(0, 2);
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

            //$('#startBotModal').modal('show');

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

            if (!nikType) {
                Swal.fire("Error", "Pilih tipe NIK terlebih dahulu", "error");
                return;
            }

            $('.is-invalid').each(function() {
                $(this).removeClass('is-invalid');
                $('#' + this.id + '_error').addClass('d-none').text('');
            });

            // Hentikan interval sebelumnya jika ada
            if (countdownInterval) clearInterval(countdownInterval);
            if (progressChecker) clearInterval(progressChecker);

            $.ajax({
                url: "{{ route('automation.check') }}",
                type: "post",
                contentType: false,  // Harus false biar jQuery gak ubah data
                processData: false,  // Harus false biar FormData dikirim beneran
                data: formData,
                success: function(response){
                    let eta = response.eta;
                    let token = response.token;

                    Swal.fire({
                        html: `
                            <div class="d-flex justify-content-center" id="statusContainer" style="display: none;">
                                <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                            <div>Bot sedang dalam proses menginput data ke server. Mohon untuk TIDAK LOGIN ke akun merchant dan tidak menganggu jalannya bot.</div>
                            <p id="etaText" class="mt-3"></p>
                        `,
                        title: "Sedang Memproses",
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            updateEtaText(eta);
                            // clearInterval(countdownInterval);
                            // clearInterval(progressChecker);

                            countdownInterval = setInterval(() => {
                                eta--;
                                updateEtaText(eta);

                                if (eta <= 0) {
                                    clearInterval(countdownInterval);
                                    countdownInterval = null;
                                }
                            }, 1000);

                            progressChecker = setInterval(() => {
                                if (!isPolling) {
                                    isPolling = true;

                                    $.ajax({
                                        url: "{{ route('automation.getprogress') }}",
                                        type: "GET",
                                        data: { token },
                                        timeout: 3000, // timeout setelah 3 detik
                                        success: function(data) {
                                            console.log('Polling result:', data);

                                            if (data.done === true || data.done === 'true') {
                                                // Proses selesai, bersihkan semua interval
                                                if (countdownInterval) {
                                                    clearInterval(countdownInterval);
                                                    countdownInterval = null;
                                                }

                                                clearInterval(progressChecker);
                                                progressChecker = null;

                                                $('#etaText').text('✅ Proses selesai!');

                                                // // Opsional: kirim sinyal ke server untuk membersihkan token dari cache

                                            }

                                            isPolling = false;
                                        },
                                        error: function() {
                                            // Pastikan isPolling di-reset meskipun terjadi error
                                            isPolling = false;
                                        }
                                    });
                                }
                            }, 5000);
                        }
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

        function updateEtaText(eta) {
            document.getElementById('etaText').innerText = eta > 0 ? `Estimasi selesai dalam ${eta} detik...` : 'Menunggu konfirmasi...';
        }

        function executeBot(formData){
            $.ajax({
                url: "{{ route('automation.run') }}",
                type: "post",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response){
                    Swal.fire(
                        "Berhasil",
                        `Bot berhasil input data, silahkan cek ke website target. <br> Jumlah Request: ${response.input_trx}, Jumlah Transaksi Berhasil: ${response.jmlValidNik}`,
                        "success");
                    $("#startBotModal").modal('hide');
                    $('#formBotAttr')[0].reset();
                },
                error: function(xhr){
                    let errorMessage = "Terjadi kesalahan saat menjalankan bot.";

                    if (xhr.status === 422) {
                        Swal.fire("Error!", xhr.responseJSON.message, "error");
                    }

                    // Coba ambil pesan dari response backend
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    Swal.fire("Gagal!", errorMessage, "error");
                }
            });
        }

        // Tambahkan event listener untuk cleanup saat meninggalkan halaman
        $(window).on('beforeunload', function() {
            if (countdownInterval) clearInterval(countdownInterval);
            if (progressChecker) clearInterval(progressChecker);
        });

        // function startProcess() {

        //     const inputTrx = $('#input_transaction').val(); // contoh jumlah input

        //     fetch(`{{ route('automation.check') }}`, {
        //         method: 'POST',
        //         body: JSON.stringify({ inputTrx })
        //     })
        //     .then(res => res.json())
        //     .then(data => {
        //         let eta = data.eta;
        //         const token = data.token;

        //         document.getElementById('statusContainer').style.display = 'flex';
        //         updateEtaText(eta);

        //         const countdown = setInterval(() => {
        //             eta--;
        //             updateEtaText(eta);
        //         }, 1000);

        //         const statusChecker = setInterval(() => {
        //         fetch(`{{ route('automation.getprogress') }}?token=${token}`)
        //             .then(res => res.json())
        //             .then(data => {
        //             if (data.done) {
        //                 clearInterval(countdown);
        //                 clearInterval(statusChecker);
        //                 document.getElementById('etaText').innerText = 'Proses selesai';
        //             }
        //             });
        //         }, 1000);
        //     });
        // }

        // function getProgress(){
        //     fetch(`{{ route('automation.getprogress') }}`)
        //         .then(res => res.json())
        //         .then(data => {
        //         if (!data.done) {
        //             setTimeout(getProgress, 1000);
        //         } else {
        //             clearInterval(countdown);
        //             document.getElementById('etaText').innerText = 'Proses selesai!';
        //         }
        //     });
        // }
    });
</script>

@endsection
