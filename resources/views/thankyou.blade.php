<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Terima Kasih</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap CDN (opsional, jika belum include di project) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8fafc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .thank-you-card {
            max-width: 500px;
            padding: 40px;
            border-radius: 20px;
            background-color: #fff;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="thank-you-card">
        <h1 class="mb-3">🎉 Terima Kasih!</h1>
        <p class="lead">Data Anda telah berhasil diproses.</p>

        <a href="#" class="btn btn-primary mt-4">Kembali ke Halaman Utama</a>
    </div>

</body>
</html>
