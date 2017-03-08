@if(isset($page_configurations))
    <!-- START ACCORDION & CAROUSEL-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/3.5.0/lodash.min.js"></script>
    <script type="text/javascript" src="/admin-constructor/plugins/gridstack/gridstack.min.js"></script>
    <link rel="stylesheet" href="/admin-constructor/plugins/gridstack/gridstack.css"/>

    <div class="row" id="page-constructor-js" data-page_id="{{$page_configurations['id']['value']}}">
        <div class="col-xs-12">
            <div class="box-group" id="accordion">
                <div class="box box-warning">
                    <div class="box-header">
                        <div class="row text-center no-margin ">
                            <div class="col-xs-5 col-sm-3 col-md-2 text-left no-padding">
                                <h2 class="page-header no-margin">Edit
                                    Page: {{$page_configurations['title']['value']}}</h2>

                            </div>
                            <div class="col-xs-1 text-center no-padding">
                                <div class="checkbox">
                                    <label>
                                        <input class="unit-save-js" type="checkbox"
                                               name="{{$page_configurations['adm_visible']['key']}}"
                                               @if($page_configurations['adm_visible']['value']==1)
                                               checked
                                                @endif
                                        >
                                        <i class="fa fa-eye"></i>
                                    </label>
                                </div>
                            </div>
                            <div class="hidden-xs col-sm-4 col-md-2 no-padding">
                                <select class="form-control page_main_setting-js" id="current_page"
                                        name="page_current_page">
                                    @foreach ($admin_pages as $number_page => $page)
                                        <option value="{{$page->id}}"
                                                @if ($page->id == $page_configurations['id']['value'])
                                                selected
                                                @endif
                                        >
                                            {{$page->title}}
                                        </option>
                                    @endforeach
                                </select>

                            </div>
                            <div class="hidden-xs col-sm-2 col-md-1 no-padding">
                                <?$views = array('lg', 'md', 'sm', 'xs', 'xxs')?>
                                <select class="form-control page_main_setting-js" name="page-view" id="current_view">
                                    @foreach ($views as $view)
                                        <option value="{{$view}}"
                                                @if ($view == $current_view) {
                                                selected
                                                @endif
                                        >
                                            {{$view}}
                                        </option>
                                    @endforeach
                                </select>

                            </div>
                            <div class="hidden-xs hidden-sm col-md-1 no-padding">
                                @if($page_configurations['fa_fa_icon']['value']=='')
                                    <div class="fa-editable-js">
                                        <a class="edit-icon-js" style="cursor:pointer;color:darkred" href="#"><i
                                                    class=" fa fa-question"></i></a>
                                        <input class="unit-save-js" name="{{$page_configurations['fa_fa_icon']['key']}}"
                                               value="{{$page_configurations['fa_fa_icon']['value']}}" type="hidden">
                                    </div>
                                @else
                                    <div class="fa-editable-js">
                                        <a class="edit-icon-js" style="cursor:pointer;color:green" href="#"><i
                                                    class=" fa {{$page_configurations['fa_fa_icon']['value']}}"></i></a>
                                        <input class="unit-save-js" name="{{$page_configurations['fa_fa_icon']['key']}}"
                                               value="{{$page_configurations['fa_fa_icon']['value']}}" type="hidden">
                                    </div>
                                @endif
                            </div>
                            <div class="col-xs-3 col-sm-2 no-padding">
                                <select class="selectpicker change-rights-js"
                                        name="row[{{$page_configurations['id']['value']}}]" multiple
                                        data-selected-text-format="count > 3">

                                    @foreach($selects['rights'] as $right_name =>$right)
                                        <option value="{{$right['value']}}"
                                                @if(isset($page_configurations['rights'][$right['value']]['value'])&&$page_configurations['rights'][$right['value']]['value']==$right['value'])
                                                selected
                                                @endif
                                        >{{$right['name']}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="pull-right">
                                <a class="btn btn-circle btn-default show-tooltip add-block-js" name="page_id"
                                   data-value="{{$page_configurations['id']['value']}}"
                                   data-original-title="Add new record">
                                    <i class="fa fa-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- /.col -->
        <!-- /.col -->
    </div>
    <div id="gridstack_wrapper">
        @if(isset($page_configurations['views']))
            @include('scholar::admin-constructor.panels.p_blocks_gridstack')
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
            resizable: {handles: 'se'}
        };
        $('.grid-stack').gridstack(options);

        grid = $('.grid-stack').data('gridstack');

        $('.selectpicker').selectpicker({
            style: 'btn-default',
            size: 4
        });

        $('body').on('change', '.select-all-js', function () {
            var class_search = '.' + $(this).data('class');
            var checked = $(this).prop('checked');
            $(this).closest('.wrap-table-js').find(class_search).each(function () {
                if (checked == true) {
                    $(this).prop('checked', true);
                } else {
                    $(this).prop('checked', false);
                }
            });
        })

        $('body').on('click', '.add-table-join-js', function () {
            var data = {};
            data['count_join_tables'] = $('.join-tables-js').data('count_tables');
            $.ajax({
                type: "GET",
                url: '/admin/load_modal_chips/bc_join_table',
                data: data,
                success: function (msg) {
                    $('.join-tables-js').append(msg).removeClass('hidden', function () {
                        var count = $('.join-tables-js').data('count_tables') + 1;
                        $('.join-tables-js').data('count_tables', count);
                    });

                }
            })
            return false;
        })


        $('.add-block-js').on('click', function () {
            var value = $(this).data('value');
            var unit = {};
            unit['page_view'] = $('#current_view').val();
            unit[$(this).attr('name')] = value;
            $.ajax({
                type: "GET",
                url: '/admin/load_modal/m_wrap_block',
                data: unit,
                success: function (msg) {
                    $('.modal-js').html(msg);
                    $('#create_block').modal('show');
                }
            })
            return false;
        })


        $('.page_main_setting-js').on('change', function () {
            var page_id = $('#current_page').val();
            var page_view = $('#current_view').val();
            window.location = '/admin/page_constructor/' + page_id + '/' + page_view;
            return false;
        })

        $('body').on('change', '.select-block-type-js', function () {
            var block_type = $(this).val();
            $.ajax({
                type: "GET",
                url: '/admin/load_block/' + block_type,
                data: {},
                success: function (msg) {
                    $('.modal-block-settings').slideUp(200).html(msg).removeClass('hidden', function () {
                        $('.modal-block-settings').slideDown(200);
                    });
                }
            })
            return false;
        })

        $('body').on('change', '.select-table-js', function () {
            var table_name = $(this).val();
            var table_number = $(this).closest('.wrap-table-js').data('table_join_number');
            var columns_settings_wrap = $(this).closest('.wrap-table-js').find('.columns-settings-js');
            $.ajax({
                type: "GET",
                url: '/admin/load_block_chips/bc_columns_settings',
                data: {'table_name': table_name, 'table_number': table_number},
                success: function (msg) {
                    columns_settings_wrap.slideUp(200).html(msg).removeClass('hidden', function () {
                        columns_settings_wrap.slideDown(200);
                    });
                }
            })
            return false;
        })

        $('body').on('change', '.select-table-join-js', function () {
            var table_name = $(this).val();
            var table_number = $(this).closest('.wrap-table-js').data('table_join_number');
            var columns_settings_wrap = $(this).closest('.wrap-table-js').find('.columns-settings-js');
            $.ajax({
                type: "GET",
                url: '/admin/load_block_chips/bc_columns_join_settings',
                data: {'table_name': table_name, 'table_number': table_number},
                success: function (msg) {
                    columns_settings_wrap.slideUp(200).html(msg).removeClass('hidden', function () {
                        columns_settings_wrap.slideDown(200);
                    });
                }
            })
            return false;
        })

        $('body').on('click', '.block-settings-save-js', function () {
            var block_settings = $('.form-block-settings-js').serialize();
            var block_id = $('.form-block-settings-js').find('#block-id').val();
            var page_id = $('#page-constructor-js').data('page_id');
            var current_view = $('#current_view').val();
            $.ajax({
                type: "POST",
                url: '/admin/save_block_settings/' + page_id + '/' + current_view + '/' + block_id,
                data: block_settings,
                success: function (msg) {
                    var table_data = $('.block-wrap-js[data-block_id='+block_id+']').find('.table-settings-js').DataTable();
                    table_data.draw(true);

                }
            })
            return false;
        })

        $('body').on('click', '.edit-block-js', function () {
            var block_id = $(this).closest('.block-wrap-js').data('block_id');
            $.ajax({
                type: "GET",
                url: '/admin/edit_block_settings/' + block_id,
                success: function (msg) {
                    $('.modal-js').html(msg);
                    $('#create_block').modal('show');
                }
            })
            return false;
        })


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


        $('body').on('click', '.delete-block-js', function () {
            var block_id = $(this).closest('.block-wrap-js').data('block_id');
            var page_id = $('#current_page').val();
            var current_view = $('#current_view').val();
            window.location = '/admin/delete_block/' + page_id + '/' + current_view + '/' + block_id;
        })


        var saveBlocksPosition = function (items) {
            var blocks_data = {};
            blocks_data['blocks'] = JSON.stringify(items, ['x', 'y', 'height', 'width', 'id']);
            var page_id = $('#current_page').val();
            var page_view = $('#current_view').val();
            $.ajax({
                type: "GET",
                url: '/admin/save_blocks_position/' + page_id + '/' + page_view,
                data: blocks_data,
                success: function (msg) {
                }
            })
        };

        $('.grid-stack').on('change', function (event, items) {
            saveBlocksPosition(items);
        });
        $('body').on('change', '.super-select-table-js', function () {
            var table_selector = $(this);
            var column_selector = $(this).closest('.wrap-super-select-js').find('.super-select-table-column-js');
            var column_list_selector=$(this).closest('.wrap-super-select-js').find('.super-select-table-column-list-js').find('select');
            var data = {};
            data['main_join_table_name'] = table_selector.val();
            data['selected_column'] = table_selector.data('selected_column');
            $.ajax({
                type: "GET",
                url: '/admin/load_modal_chips/bc_table_column_join_main',
                data: data,
                success: function (items) {
                    column_selector.html(items);
                    column_list_selector.html(items);
                    column_list_selector.selectpicker('refresh');


                }
            })

            return false;
        })


    });
    function createParameter() {
        grid = $('.grid-stack').data('gridstack');

        var node = {};
        node.type = 'text';
        node.id = 0;
        node.title = 'title';
        node.x = 0;
        node.y = 100;
        node.width = 5;
        node.height = 2;

        grid.addWidget($('<div ><div style="padding-left:5px;" data-id="' + node.id + '" class="add-new-element grid-stack-item-content">' +
                '<span class="title-wrap">Block:</span><button class="btn btn-danger pull-right del_parameter" name=delete type=button>Delete</button>' +
                'Type: <select class="parameter" name=type type=text></select><div class="cont"></div></div></div>'),
            node.x, node.y, node.width, node.height);
        return false;
    }


</script>
