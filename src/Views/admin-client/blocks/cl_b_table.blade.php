@if(isset($block['tables']))
    <?
    $tables_settings = $block['tables'];

    $table_main_id = $block['table_main_id'];
    $table_main_settings = $block['tables'][$table_main_id];
    $table_main_content = $block['content'];
    $columns_all = $block['columns'];
    $i = 0;
    $b = 0;
    $column_sort_number = 0;
    $sort_direction = 'asc';
    $sort_table_id = $table_main_id;
    $sort_column_name = 'id';

    ?>

    <div class="box box-border-color-css_{{$block['id']['value']}} wrap-css_{{$block['id']['value']}}">


        <div class="box-header box-header-color-css_{{$block['id']['value']}} wrap-header">
            <div class="col-xs-12">
                <div class="row">
                    <div class="col-xs-12 no-margin pull-left"><h3>{{$block['block_title']['value']}}</h3></div>
                    @if(isset($view)&&$view=='client')
                        @include('scholar::admin-client.blocks.cl_b_filters')
                    @else
                        @include('scholar::admin-constructor.blocks.block_chips.b_filters')
                    @endif
                </div>
            </div>

        </div>


        <div class="box-body table-responsive wrap-body" style="background-color: #eee">
            @if(isset($table_main_content)&&is_array($table_main_content))
            <table class="table table-striped table-bordered table-settings-js display"

                   id="data-table-{{$table_main_settings['id']['value']}}">
                <?$i = 1;

                ?>

                    @foreach($table_main_content as $row_number =>$row)
                        @if($i==1)
                            <?$i++;
                            ?>
                            <thead>
                            <tr>
                                @foreach($row['row_data'] as $table_id => $table_columns)
                                    <?
                                    $table = $tables_settings[$table_id];
                                    $table_color = $table['table_color']['value'];
                                    switch ($table_color) {
                                        case 'default':
                                            $color = 'rgba(100,100,100,0.2)';
                                            break;
                                        case 'green':
                                            $color = 'rgba(0,200,0,0.2)';
                                            break;
                                        case 'red':
                                            $color = 'rgba(200,0,0,0.2)';
                                            break;
                                        case 'blue':
                                            $color = 'rgba(0,0,100,0.2)';
                                            break;
                                        case 'purple':
                                            $color = 'rgba(100,0,200,0.2)';
                                            break;
                                        case 'orange':
                                            $color = 'rgba(250,100,0,0.2)';
                                            break;
                                        default:
                                            $color = 'rgba(100,100,100,0.2)';
                                    }


                                    ?>

                                    @foreach($table_columns as $column_name => $cell)
                                        @if($columns_all[$table_id][$column_name]['column_visible']['value']==1)

                                            @if ($tables_settings[$table_id]['table_sort_column']['value'] == $tables_settings[$table_id]['table_name']['value'].'__'.$column_name&&
                                            ($tables_settings[$table_id]['table_main_sort_id']['value']==$table_id||
                                            $tables_settings[$table_id]['table_main_sort_id']['value']=='new'))

                                                <?$column_sort_number = $b;
                                                $sort_direction = ($tables_settings[$table_id]['table_column_sort_direction']['value'] == '0') ? "asc" : "desc";
                                                $sort_table_id = $table_id;
                                                $sort_column_name = $column_name;
                                                ?>

                                            @endif
                                            <th class="sort-column-js" data-table_id="{{$table_id}}"
                                                data-column_name="{{$column_name}}">
                                                <div style="background-color:{{$color}}; padding:0.5vw; padding-right:25px;">{{str_replace("_", " ", ucfirst($columns_all[$table_id][$column_name]['column_title']['value']))}} </div>
                                            </th>
                                            <?$b++;?>
                                        @endif
                                    @endforeach
                                    @if($tables_settings[$table_id]['table_row_editable']['value']==1||$tables_settings[$table_id]['table_row_delete']['value']==1)

                                        <th class="button-column-js" style="min-width: 80px;">
                                            <div style="background-color:{{$color}}; padding:0.5vw;"></div>
                                        </th>
                                        <?$b++;?>

                                    @endif
                                @endforeach

                            </tr>
                            </thead>
                            <tbody>
                            @endif
                            @endforeach
                            </tbody>

            </table>
        </div>
    </div>

    <?  $table_color = $table_main_settings['table_color']['value'];
    if ($table_color != '') {
        if ($table_color == 'red') {
            $table_color_css = 'rgba(255,84,131,0.8)';
            $table_header_color_css = '#999';
        } elseif ($table_color == 'green') {
            $table_color_css = 'darkslategrey';
            $table_header_color_css = '#999';
        } elseif ($table_color == 'blue') {
            $table_color_css = 'rgba(2,179,228,0.8)';
            $table_header_color_css = '#ddd';
        } else {
            $table_color_css = '#999';
            $table_header_color_css = '#ddd';
        }

    } else {
        $table_color_css = '#999';
        $table_header_color_css = '#ddd';
    }?>

    <style>
        td th {
            height: 40px;
            overflow: hidden;
        }

        th div {
            color: black !important;
        }

        @media screen and (min-width: 1301px) {
            ::after {
                bottom: 40% !important;
            }
        }

        @media screen and (min-width: 1000px) and (max-width: 1301px) {
            ::after {
                bottom: 26% !important;
            }
        }

        @media screen and (min-width: 800px) and (max-width: 1000px) {
            ::after {
                bottom: 20% !important;
            }
        }

        @media screen and (min-width: 600px ) and (max-width: 800px ) {
            ::after {

                bottom: 9% !important;
            }

            td div, th div {
                height: 4vw;
            }
        }

        td, th {

            padding: 0 !important;
        }

        td div, th div {
            overflow: hidden;
            color: grey;
            padding: 0;
            height: 40px;
            text-align: center;
            vertical-align: center;
        }

        .table-color-css_{{$block['id']['value']}}                                     {
            background: {{$table_color_css}};

        }

        .box-border-color-css_{{$block['id']['value']}}                                {
            border-top-color: {{$table_color_css}};
        }

        .box-header-color-css_{{$block['id']['value']}}                                {
            background: {{$table_header_color_css}};
        }

        .block-table-css {
            position: relative;
        }

        .block-table-css:hover .block-panel-edit-css {
            display: block !important;

        }
    </style>

    <script>
        $(document).ready(function () {
            var wrap_content_{{$block['id']['value']}}= $('.wrap-css_{{$block['id']['value']}}');
            var wrap_table_{{$block['id']['value']}}= wrap_content_{{$block['id']['value']}}.closest('.block-wrap-js');
            var sort_table_id = "{{$sort_table_id}}";
            var sort_column_name = "{{$sort_column_name}}";
            wrap_content_{{$block['id']['value']}}.on('resize', function () {
                alert('serg');
                var height = Math.ceil((wrap_content_{{$block['id']['value']}}.height() + 20) / 30);
                grid.update(wrap_table_{{$block['id']['value']}}, null, null, null, height);
            })


            $('.selectpicker').selectpicker({
                style: 'btn-default',
                size: 10
            });

            $('.sort-column-js').on('click', function () {
                sort_table_id = $(this).data('table_id');
                sort_column_name = $(this).data('column_name');
            })


            $("#data-table-{{$table_main_settings['id']['value']}}")
                .DataTable({

                    "order": [[{{$column_sort_number}}, "{{$sort_direction}}"]],

                    ajax: {
                        url: '/admin/data_table',
                        data: function (d) {
                            d.table_id = "{{$table_main_settings['id']['value']}}";
                            d.sort_table_id = sort_table_id;
                            d.sort_column_name = sort_column_name;
                        }
                    },
                    "pageLength": '{{$table_main_settings['table_count_records']['value']}}',
                    "processing": true,
                    "serverSide": true,
                    "initComplete": function (settings, json) {
                        var height = Math.ceil((wrap_content_{{$block['id']['value']}}.height() + 20) / 30);
                        grid.update(wrap_table_{{$block['id']['value']}}, null, null, null, height);
                    }
                });

        });

    </script>
    @else
        <div class="alert alert-info text-center">Table is empty</div>
    @endif
@endif
<style>

    .block-table-css {
        position: relative;
    }

    .block-table-css:hover .block-panel-edit-css {
        display: block !important;

    }
</style>