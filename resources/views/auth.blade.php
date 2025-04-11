@include('layouts.header')

<body class="bg-light">
    <div class="container py-4">
        <div class="d-flex justify-content-center">
            <img src="{{ asset('img/subsidi-tepat.webp') }}" style="width: 90px; height: 90px; object-fit: cover;">
        </div>
        <div class="d-flex justify-content-center mt-4">
            <div class="col-xl-6 col-lg-6 col-md-9">
                <div class="card o-hidden border-0 shadow">
                    <div class="card-body p-0">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 fw-bold mb-4">Login</h1>
                            </div>
                            <form>
                                <div class="mb-3">
                                    <label for="formGroupExampleInput" class="form-label">Email</label>
                                    <input type="text" class="form-control" placeholder="Ketik di sini" name="email" id="email" value="{{ old('email') }}">
                                    <div id="email_error" class="text-danger"></div>
                                </div>
                                <div class="mb-4">
                                    <label for="formGroupExampleInput" class="form-label">Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Ketik di sini">
                                        <button class="btn btn-outline-secondary toggle-password" type="button">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </div>
                                    <div id="password_error" class="text-danger"></div>
                                </div>
                                <div>
                                    <button type="submit" id="loginBtn" class="btn btn-primary btn-user btn-block">
                                        <div class="d-flex justify-content-center align-items-center">
                                            <div class="spinner-border text-white spinner-border-sm mr-2 d-none" role="status" id="loadingSection">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                            <div>Login</div>
                                        </div>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

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
                icon.removeClass("fa-eye").addClass("fa-eye-slash"); // Ganti ikon
            } else {
                passwordField.attr("type", "password");
                icon.removeClass("fa-eye-slash").addClass("fa-eye"); // Balikin ikon
            }
        });

        $('#loginBtn').on('click', function(e){
            e.preventDefault();
            $('#loadingSection').removeClass('d-none');

            $('.is-invalid').each(function() {
                $(this).removeClass('is-invalid');
                $('#' + this.id + '_error').addClass('d-none').text('');
            });

            $.ajax({
                url: "{{ route('login') }}",
                type: "post",
                data: {
                    email: $('#email').val(),
                    password: $('#password').val()
                },
                success: function(response){
                    if (response.role === 'ap') {
                        window.location.href = "{{ route('ap.index') }}";
                    } else if (response.role === 'ao') {
                        window.location.href = "{{ route('ao.index') }}";
                    } else if (response.role === 'sa') {
                        window.location.href = "{{ route('sa.index') }}";
                    } else {
                        Swal.fire("Error", "Role tidak dikenali!", "error");
                    }
                },
                error: function(xhr){
                    $('#loadingSection').addClass('d-none');
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
   
                    } else if (xhr.status === 500){
                        Swal.fire("Error", "Gagal menyimpan data, kesalahan server", "error");
                    } else if (xhr.status === 404) {
                        Swal.fire("Login Gagal", "Username atau Password salah!", "error");
                    } else if (xhr.status === 419) {
                        Swal.fire("Login Gagal", "Gagal terhubung ke server!", "error");
                    }
                }
            });
        });

        document.getElementById('password').addEventListener('keypress', function(e) {
            if (e.key === ' ') {
                e.preventDefault(); // blokir spasi
            }
        });

        document.getElementById('email').addEventListener('keypress', function(e) {
            if (e.key === ' ') {
                e.preventDefault(); // blokir spasi
            }
        });
            
    });
</script>

</html>
