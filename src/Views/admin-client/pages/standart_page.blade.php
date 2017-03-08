@if(isset($page_configurations))

    <!-- START ACCORDION & CAROUSEL-->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/3.5.0/lodash.min.js"></script>
    <script type="text/javascript" src="/admin-constructor/plugins/gridstack/gridstack.min.js"></script>
    <link rel="stylesheet" href="/admin-constructor/plugins/gridstack/gridstack.css"/>

    <div class="">
        <h2 class="page-header no-margin" style="padding: 10px">{{$page_configurations['title']['value'] or ''}}</h2>
    </div>

    <div id="gridstack_wrapper">
        @if(isset($page_configurations['views']))
            @include('scholar::admin-client.panels.p_blocks_gridstack')
        @endif
    </div>

@endif
<div class="modal-js">

</div>

<script type="text/javascript">
    $(function () {

        var options = {
            cellHeight: 25,
            verticalMargin: 5,
            horizontalMargin: 5,
            disableDrag: true,
            disableResize: true,
            resizable: {handles: 'se'}
        };
        $('.grid-stack').gridstack(options);
        grid = $('.grid-stack').data('gridstack');


        $('body').on('click', '.create-row-js', function () {
            var table_id = $(this).data('table_id');
            $.ajax({
                type: "GET",
                url: '/admin/load_modal_edit_row/' + table_id + '/' + 'new',
                success: function (msg) {
                    $('.modal-js').html(msg);
                    $('#edit_row').modal('show');
                }
            })
            return false;
        })

        $('body').on('click', '.edit-row-js', function () {

            var unit = $(this).attr('name');
            $.ajax({
                type: "GET",
                url: '/admin/load_modal_edit_row/edit/id',
                data: unit,
                success: function (msg) {
                    $('.modal-js').html(msg);
                    $('#edit_row').modal('show');
                }
            })
            return false;
        })


        $('body').on('click', '.edit-icon-js', function () {
            $('.add-current-icon-js').removeClass('add-current-icon-js');
            $(this).parent().addClass('add-current-icon-js');
            $.ajax({
                type: "GET",
                url: '/admin/load_modal/m_icons',
                success: function (msg) {
                    $('.modal-js').html(msg);
                    $('#edit_icon').modal('show');
                }
            })
            return false;
        })




    });


</script>
