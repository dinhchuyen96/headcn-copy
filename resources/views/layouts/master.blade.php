<!DOCTYPE html>
<html lang="en">
<x-head />

<body class="fixed-navbar">
    <div class="page-wrapper">
        <!-- START HEADER-->
        <x-header />
        <!-- END HEADER-->
        <!-- START SIDEBAR-->
        <x-sidebar />
        <!-- END SIDEBAR-->
        <div class="content-wrapper">
            <!-- START PAGE CONTENT-->
            <div class="page-content fade-in-up">
                @yield('content')
            </div>
            <!-- END PAGE CONTENT-->
            <!-- START FOOTER -->
            <x-footer />
            <!-- END FOOTER -->
        </div>
    </div>
    <!-- BEGIN THEME CONFIG PANEL-->
    {{-- <x-theme/> --}}
    <!-- END THEME CONFIG PANEL-->
    <!-- BEGIN PAGA BACKDROPS-->
    <div class="sidenav-backdrop backdrop"></div>
    <!-- <div class="preloader-backdrop">
    <div class="page-preloader">Loading</div>
</div> -->
    <!-- END PAGA BACKDROPS-->
    <!-- CORE PLUGINS-->
    {{-- <x-toast/> --}}
    @include('layouts.partials._script')

    @yield('js')
    <!-- PAGE LEVEL SCRIPTS-->
    <script>
        $(document).on('keyup change', '.form-control', function(e) {
            $(this).siblings('.invalid-feedback').remove();
            $(this).removeClass('is-invalid');
            $(this).parents('.form-group').removeClass('has-error');
        });

        function showToast(heading, text, icon) {
            $.toast({
                heading: heading,
                text: text,
                position: 'bottom-right',
                icon: icon,
                hideAfter: 2000
            })
        }
    </script>

</body>

</html>
