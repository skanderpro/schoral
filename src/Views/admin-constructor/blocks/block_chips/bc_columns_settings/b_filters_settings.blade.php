
@if($columns_all[$table_id][$column_name]['column_filter_type']['value']=='select_all')
    <div class="col-xs-4  no-padding">
        <label>Filter:

            @if(isset($columns_all[$table_id][$column_name]['column_filter_type']['column_filter_settings']))

                <select class="selectpicker change-selectMultiple-js"
                        name="{{$columns_all[$table_id][$column_name]['column_filter_type']['column_filter_settings']['key']}}"
                        multiple
                        data-selected-text-format="count > 3">
                    @if(isset($columns_all[$table_id][$column_name]['column_filter_type']['all_filter_items']))
                        @foreach($columns_all[$table_id][$column_name]['column_filter_type']['all_filter_items'] as $number_value =>$item_value)
                            <option value="{{$item_value}}"
                                    @if(is_array($columns_all[$table_id][$column_name]['column_filter_type']['column_filter_settings']['value'])&&
                                    in_array($item_value,$columns_all[$table_id][$column_name]['column_filter_type']['column_filter_settings']['value']))
                                    selected
                                    @endif
                            >{{$item_value}}</option>
                        @endforeach
                    @endif
                </select>
            @endif
        </label>
    </div>
@elseif($columns_all[$table_id][$column_name]['column_filter_type']['value']=='range'||
$columns_all[$table_id][$column_name]['column_filter_type']['value']=='range_date')
    @if(isset($columns_all[$table_id][$column_name]['column_filter_type']['column_filter_settings']))
        <div class="col-xs-3">

            <input class="form-control" placeholder="From" id="BlockdatepickerFrom_{{$columns_all[$table_id][$column_name]['id']['value']}}"

                   @if($new==false)
                   name="{{$columns_all[$table_id][$column_name]['column_filter_type']['column_filter_settings']['key']}}"
                   @else
                   name="new[columns][{{$table_number}}][{{$column_name}}][column_title]"
                   @endif
                   type="text"

                   value="{{$columns_all[$table_id][$column_name]['column_filter_type']['column_filter_settings']['value']}}"
            >
            </label>
        </div>
        <div class="col-xs-3 form-group">

            <input class="form-control" placeholder="To" id="BlockdatepickerTo_{{$columns_all[$table_id][$column_name]['id']['value']}}"
                   @if($new==false)
                   name="{{$columns_all[$table_id][$column_name]['column_filter_type']['column_filter_settings_two']['key']}}"
                   @else
                   name="new[columns][{{$table_number}}][{{$column_name}}][column_title]"
                   @endif
                   type="text"
                   value="{{$columns_all[$table_id][$column_name]['column_filter_type']['column_filter_settings_two']['value']}}"
            >

        </div>
        @if($columns_all[$table_id][$column_name]['column_filter_type']['value']=='range_date')
            <script>

                $(function () {

                    $("#BlockdatepickerFrom_{{$columns_all[$table_id][$column_name]['id']['value']}}").datepicker({ dateFormat: 'yy-mm-dd' });
                    $("#BlockdatepickerTo_{{$columns_all[$table_id][$column_name]['id']['value']}}").datepicker({ dateFormat: 'yy-mm-dd' });

                });

            </script>
        @endif
    @endif
@endif
