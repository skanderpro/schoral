<div class="no-margin filter-js" style="background-color: #fff">

    <?$k = 1;

    ?>
@if(!empty($table_main_content))

    @foreach($table_main_content as $row_number =>$row)
        @if($k==1)
            <?$k++;
            ?>

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
                @foreach($columns_all[$table_id] as $column_name => $cell)

                    @if($columns_all[$table_id][$column_name]['column_client_db_filter']['value']==1)
                        @if($columns_all[$table_id][$column_name]['column_filter_type']['value']=='select_all')
                            <div class="col-xs-4"
                                 style="padding:2px;background-color:{{$color}};border:solid 1px #eee">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <label style="font-size: 20px;">{{ucfirst($column_name).'_db:  '}}
                                        </label>
                                    </div>
                                    <div class="col-xs-6">

                                        @if(isset($columns_all[$table_id][$column_name]['column_filter_type']['column_filter_settings']))
                                            <select class="selectpicker client-js change-selectMultiple-js"
                                                    name="{{$columns_all[$table_id][$column_name]['column_filter_type']['column_filter_settings']['key']}}"
                                                    multiple
                                                    data-selected-text-format="count > 3">
                                                @if(isset($columns_all[$table_id][$column_name]['column_filter_type']['all_filter_items']))
                                                    @foreach($columns_all[$table_id][$column_name]['column_filter_type']['all_filter_items'] as $number_value =>$item_value)
                                                        <option value="{{$item_value}}"
                                                                @if( is_array($columns_all[$table_id][$column_name]['column_filter_type']['column_filter_settings']['value'])&&
                                                                in_array($item_value,$columns_all[$table_id][$column_name]['column_filter_type']['column_filter_settings']['value']))
                                                                selected
                                                                @endif
                                                        >{{$item_value}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        @elseif($columns_all[$table_id][$column_name]['column_filter_type']['value']=='range'||
                        $columns_all[$table_id][$column_name]['column_filter_type']['value']=='range_date')
                            <div class="col-xs-4"
                                 style="padding:2px;background-color:{{$color}};border:solid 1px #eee">
                                <div class="row ">
                                    <div class="col-xs-4"><label style="font-size: 20px;">{{ucfirst($column_name).'_db'}}
                                            :</label>
                                    </div>

                                @if(isset($columns_all[$table_id][$column_name]['column_filter_type']['column_filter_settings']))
                                    <div class="col-xs-8">
                                        <div class="col-xs-6">

                                            <input class="form-control client-js unit-save-js" placeholder="From"
                                                   id="datepickerDbFrom_{{$columns_all[$table_id][$column_name]['id']['value']}}"

                                                   name="{{$columns_all[$table_id][$column_name]['column_filter_type']['column_filter_settings']['key']}}"


                                                   type="text"

                                                   value="{{$columns_all[$table_id][$column_name]['column_filter_type']['column_filter_settings']['value']}}"
                                            >

                                        </div>
                                        <div class="col-xs-6">

                                            <input class="form-control unit-save-js" placeholder="To"
                                                   id="datepickerDbTo_{{$columns_all[$table_id][$column_name]['id']['value']}}"

                                                   name="{{$columns_all[$table_id][$column_name]['column_filter_type']['column_filter_settings_two']['key']}}"
                                                   type="text"
                                                   value="{{$columns_all[$table_id][$column_name]['column_filter_type']['column_filter_settings_two']['value']}}"

                                            >

                                        </div>
                                    </div>



                                    @if($columns_all[$table_id][$column_name]['column_filter_type']['value']=='range_date')
                                        <script>
                                            $(function () {
                                                $("#datepickerDbFrom_{{$columns_all[$table_id][$column_name]['id']['value']}}").datepicker({dateFormat: 'yy-mm-dd'});
                                                $("#datepickerDbTo_{{$columns_all[$table_id][$column_name]['id']['value']}}").datepicker({dateFormat: 'yy-mm-dd'});
                                            });
                                        </script>
                                    @endif
                                @endif
                                </div>
                            </div>
                        @endif
                        @endif

                        @if($columns_all[$table_id][$column_name]['column_client_session_filter']['value']==1)
                            @if($columns_all[$table_id][$column_name]['column_filter_type']['value']=='select_all')
                                <div class="col-xs-4"
                                     style="padding:2px;background-color:{{$color}};border:solid 1px #eee">
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <label style="font-size: 20px;">{{ucfirst($column_name).'_cl:  '}}
                                            </label>
                                        </div>
                                        <div class="col-xs-6">

                                            @if(isset($columns_all[$table_id][$column_name]['column_filter_type']['column_filter_settings']))
                                                <select class="selectpicker filter-client-session-js"
                                                        data-column_name="{{$column_name}}"
                                                        data-table_id="{{$table_id}}"
                                                        data-filter_column="{{'column_filter_settings'}}"
                                                        multiple
                                                        data-selected-text-format="count > 3">
                                                    @if(isset($columns_all[$table_id][$column_name]['column_filter_type']['all_filter_items']))
                                                        @foreach($columns_all[$table_id][$column_name]['column_filter_type']['all_filter_items'] as $number_value =>$item_value)
                                                            <option value="{{$item_value}}"
                                                                    @if( is_array($columns_all[$table_id][$column_name]['column_filter_type']['column_filter_settings']['value'])&&
                                                                    in_array($item_value,$columns_all[$table_id][$column_name]['column_filter_type']['column_filter_settings']['value']))
                                                                    selected
                                                                    @endif
                                                            >{{$item_value}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            @elseif($columns_all[$table_id][$column_name]['column_filter_type']['value']=='range'||
                            $columns_all[$table_id][$column_name]['column_filter_type']['value']=='range_date')
                                <div class="col-xs-4"
                                     style="padding:2px;background-color:{{$color}};border:solid 1px #eee">
                                    <div class="row ">
                                        <div class="col-xs-4">
                                            <label style="font-size: 20px;">{{ucfirst($column_name).'_cl'}}:</label>
                                        </div>

                                        @if(isset($columns_all[$table_id][$column_name]['column_filter_type']['column_filter_settings']))
                                            <div class="col-xs-8">
                                                <div class="col-xs-6">

                                                    <input class="form-control filter-client-session-js" placeholder="From"

                                                           id="datepickerClFrom_{{$columns_all[$table_id][$column_name]['id']['value']}}"
                                                           data-column_name="{{$column_name}}"
                                                           data-table_id="{{$table_id}}"
                                                           data-filter_column="{{'column_filter_settings'}}"
                                                           type="text"
                                                           value="{{$columns_all[$table_id][$column_name]['column_filter_type']['column_filter_settings']['value']}}"

                                                    >

                                                </div>
                                                <div class="col-xs-6">

                                                    <input class="form-control filter-client-session-js" placeholder="To"

                                                           id="datepickerClTo_{{$columns_all[$table_id][$column_name]['id']['value']}}"
                                                           data-column_name="{{$column_name}}"
                                                           data-table_id="{{$table_id}}"
                                                           data-filter_column="{{'column_filter_settings_two'}}"
                                                           type="text"
                                                           value="{{$columns_all[$table_id][$column_name]['column_filter_type']['column_filter_settings_two']['value']}}"
                                                    >

                                                </div>
                                            </div>



                                            @if($columns_all[$table_id][$column_name]['column_filter_type']['value']=='range_date')
                                                <script>
                                                    $(function () {
                                                        $("#datepickerClFrom_{{$columns_all[$table_id][$column_name]['id']['value']}}").datepicker({dateFormat: 'yy-mm-dd'});
                                                        $("#datepickerClTo_{{$columns_all[$table_id][$column_name]['id']['value']}}").datepicker({dateFormat: 'yy-mm-dd'});
                                                    });
                                                </script>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endif

                @endforeach

            @endforeach
        @endif
    @endforeach
@endif
</div>

