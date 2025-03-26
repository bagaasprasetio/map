@include('layouts.header')

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        @include('layouts.sidebar')
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">
                @include('layouts.topbar')
                @yield('content')
            </div>
            <!-- End of Main Content -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

</body>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Logout</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Klik tombol "Logout" di bawah untuk logout...</div>
            <div class="modal-footer">
                <button class="btn btn-outline" type="button" data-dismiss="modal">Cancel</button>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <input type="submit" class="btn btn-primary" value="Logout">
                </form>
            </div>
        </div>
    </div>
</div>
<style>
    .btn-outline{
        border-color: #4e73df;
        color: #4e73df;
    }
    .btn-outline:hover{
        border-color: #4e73df;
        color: #fff;
        background-color: #4e73df;
    }

    .btn-outline-delete{
        border-color:#df4e4e;
        color: #df4e4e;
    }
    .btn-outline-delete:hover{
        border-color: #df4e4e;
        color: #fff;
        background-color: #df4e4e;
    }
</style>

@include('layouts.footer')
</html>