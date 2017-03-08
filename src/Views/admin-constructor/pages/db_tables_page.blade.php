<!-- START ACCORDION & CAROUSEL-->
<div class="row">
    <div class="col-xs-12">
        <h2 class="page-header">DATABASE: Tables Settings</h2>
        <div class="box-group" id="accordion">
            @foreach($tables_settings as $table_name => $table)
                <div class="panel box box-grey">
                    <div class="box-header ">
                        <div class="table-settings-js column-settings-js no-margin">
                            <div class="row no-margin">
                            <div class=" col-xs-8 col-sm-5 col-md-3 ">
                                <h4 class="box-title">
                                    <a data-toggle="collapse" data-parent="#accordion"
                                       href="#collapse_{{$table['table_name']['value']}}">
                                        {{$table['table_name']['value']}}
                                    </a>
                                </h4>
                            </div>
                            <div class="hidden-xs col-sm-5 col-md-3 no-padding">
                                <div class="input-group">
                                            <span class="input-group-addon">
                                              <input class="unit-save-js" type="checkbox"
                                                     @if ($table['table_visible']['value'] == 1) {
                                                     checked
                                                     @endif
                                                     name="{{$table['table_visible']['key']}}">

                                            </span>
                                    <input class=" form-control unit-save-js" type="text"
                                           name="{{$table['table_title']['key']}}"
                                           placeholder="title for admin"
                                           value="{{$table['table_title']['value']}}"
                                    >

                                </div>
                            </div>
                            <div class="pull-right">
                                <button type="button" class="btn btn-flat bg-purple"><i
                                            class="fa fa-pencil fa-lg"></i></button>
                            </div>
                            </div>

                        </div>
                    </div>
                    <div id="collapse_{{$table['table_name']['value']}}" class="panel-collapse collapse">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="">
                                        <div class="box-body table-responsive no-padding">
                                            <table class="table table-hover table-striped table-settings-js">
                                                <tr>
                                                    <th class="col-xs-2 text-left column_1-js">db_name</th>
                                                    <th class="col-xs-2 text-center ">visible</th>
                                                    <th class="col-xs-2 text-center ">editable</th>
                                                    <th class="col-xs-2 text-center ">inRow_visible</th>
                                                    <th class="col-xs-2 text-center ">inRow_editable</th>
                                                    <th class="col-xs-2 text-center ">title</th>
                                                    <th class="col-xs-2 text-center ">display-type</th>
                                                </tr>
                                                @foreach($table['columns'] as $column_name => $column)
                                                    <tr class="column-settings-js">
                                                        <td class="text-left "
                                                            data-column_number="1">{{$column_name}}</td>
                                                        <td class="text-center " data-column_number="2">
                                                            <div class="form-group">
                                                                <input class="unit-save-js" value="1" type="checkbox"

                                                                       @if ($column['column_visible']['value'] == 1)
                                                                       checked
                                                                       @endif
                                                                       name="{{$column['column_visible']['key']}}">
                                                            </div>
                                                        </td>
                                                        <td class="text-center " >
                                                            <div class="form-group">
                                                                <input class="unit-save-js" value="1" type="checkbox"

                                                                       @if ($column['column_editable']['value'] == 1)
                                                                       checked
                                                                       @endif
                                                                       name="{{$column['column_editable']['key']}}">
                                                            </div>
                                                        </td>
                                                        <td class="text-center " data-column_number="2">
                                                            <div class="form-group">
                                                                <input class="unit-save-js" value="1" type="checkbox"

                                                                       @if ($column['column_inRow_visible']['value'] == 1)
                                                                       checked
                                                                       @endif
                                                                       name="{{$column['column_inRow_visible']['key']}}">
                                                            </div>
                                                        </td>
                                                        <td class="text-center " >
                                                            <div class="form-group">
                                                                <input class="unit-save-js" value="1" type="checkbox"

                                                                       @if ($column['column_inRow_editable']['value'] == 1)
                                                                       checked
                                                                       @endif
                                                                       name="{{$column['column_inRow_editable']['key']}}">
                                                            </div>
                                                        </td>

                                                        <td class="text-center " data-column_number="3">
                                                            <div class="input-group" style="display: inline-block">
                                                                <input class="form-control unit-save-js"
                                                                       type="text" placeholder="title for admin"
                                                                       value="{{$column['column_title']['value']}}"

                                                                       name="{{$column['column_title']['key']}}">
                                                            </div>
                                                        </td>
                                                        <td class="text-center " data-column_number="4">
                                                            <div class="input-group">

                                                                <select class=" form-control unit-save-js"
                                                                        name="{{$column['column_display_type']['key']}}">
                                                                    <?
                                                                    $options = array('text' => 'text', 'image' => 'image', 'article' => 'article', 'checkbox' => 'checkbox', 'icon' => 'icon', 'number' => 'number');
                                                                    foreach ($options as $number => $option) {
                                                                        if ($option == $column['column_display_type']['value']) {
                                                                            echo '<option value="' . $number . '" selected>' . $option;
                                                                        } else {
                                                                            echo '<option value="' . $number . '">' . $option;
                                                                        }
                                                                        echo '</option>';
                                                                    }
                                                                    ?>
                                                                </select>
                                                                <div class="input-group-btn">
                                                                    <button type="button"
                                                                            class="btn btn-flat btn-danger"><i
                                                                                class="fa fa-pencil "></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        </div>
                                        <!-- /.box-body -->
                                    </div>
                                    <!-- /.box -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>

    <!-- /.col -->
    <!-- /.col -->
</div>
<!-- /.row -->
<!-- END ACCORDION & CAROUSEL-->