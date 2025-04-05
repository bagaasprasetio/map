@section('title', 'MAP - Langganan Sudah Berakhir')

@include('layouts.header')

<!-- Begin Page Content -->
<div class="container vh-100 d-flex flex-column justify-content-center align-items-center">
    <i class="fas fa-triangle-exclamation fa-5x text-warning mb-3"></i>
    <h5>Mohon Maaf, Anda belum membayar biaya langganan</h5>
    <p class="mb-3 text-center">Silahkan hubungi admin kami untuk melakukan pembayaran biaya langganan agar Anda bisa terus menggunakan layanan kami.</p>
    <div class="d-flex">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <input type="submit" class="btn btn-outline mr-2" value="Logout">
        </form>
        <a class="btn btn-primary" href="https://wa.link/ds0u6i" target="_blank">Hubungi Admin</a>
    </div>
</div>


