@include('scholar::admin-constructor.layout.head_links')
@include('scholar::admin-constructor.layout.header_panel')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->


    <!-- Main content -->
    <section class="content no-padding" style="min-width: 250px;" id="page-content">

                    <?=$content?>


    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->


@include('scholar::admin-constructor.layout.footer')
<script src="/admin-constructor/js/constructor.js"></script>
<script src="/admin-constructor/js/crud.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="/admin-constructor/AdminLTE-2.3.7/bootstrap/js/bootstrap.min.js"></script>
<!-- DataTables -->
{{--<link rel="stylesheet" type="text/css" href="/admin-constructor/plugins/DataTables-1.10.13/media/css/jquery.dataTables.css">--}}
<link rel="stylesheet" type="text/css" href="/admin-constructor/plugins/DataTables-1.10.13/media/css/dataTables.bootstrap.css">
<script type="text/javascript" charset="utf8" src="/admin-constructor/plugins/DataTables-1.10.13/media/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8" src="/admin-constructor/plugins/DataTables-1.10.13/media/js/dataTables.bootstrap.js"></script>

<!-- SlimScroll -->
<script src="/admin-constructor/AdminLTE-2.3.7/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="/admin-constructor/AdminLTE-2.3.7/plugins/fastclick/fastclick.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/css/bootstrap-select.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/js/bootstrap-select.min.js"></script>
<!-- AdminLTE App -->
<script src="/admin-constructor/AdminLTE-2.3.7/dist/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="/admin-constructor/AdminLTE-2.3.7/dist/js/demo.js"></script>
<script src="/admin-constructor/AdminLTE-2.3.7/dist/js/demo.js"></script>
<!-- page script -->
<script>
    $(function () {
        $("#example1").DataTable();
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false
        });
    });
</script>
</body>
</html>
