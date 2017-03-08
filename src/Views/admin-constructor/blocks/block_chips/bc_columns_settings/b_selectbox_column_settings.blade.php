<?
if (isset($columns[$column_name]['column_display_type']['type_settings'])) {
    $super_select_settings = $columns[$column_name]['column_display_type']['type_settings'];
}

$selected_table = '';

?>

<div class="col-xs-12 wrap-super-select-js">
    <div class="col-xs-2">
        <h4>Table Name:</h4>
    </div>

    <div class="col-xs-2">
        <select class=" form-control select2 select2-hidden-accessible super-select-table-js  " tabindex="-1"
                aria-hidden="true"
                name="{{$super_select_settings['stc_table_name']['key'] or
            'new[column_type]['.$columns[$column_name]['id']['value'].'][stc_table_name]'}}"
        >
            <option value="none">choose table</option>
            @foreach($tables_global_settings as $table_name =>$table_settings)

                <option value="{{$table_name}}"
                        @if(isset($super_select_settings['stc_table_name'])
                        &&$super_select_settings['stc_table_name']['value']==$table_name)

                        selected
                        @endif
                >
                    {{$table_name}}
                </option>

            @endforeach
        </select>
    </div>

    <div class="col-xs-2">
        <h4 class="pull-right">Column Name:</h4>
    </div>
    <div class="col-xs-2">
        <select class=" form-control select2 select2-hidden-accessible super-select-table-column-js"
                tabindex="-1"
                aria-hidden="true"
                name="{{$super_select_settings['stc_column_name']['key'] or
            'new[column_type]['.$columns[$column_name]['id']['value'].'][stc_column_name]'}}"
        >
            @if(isset($columns_global_settings)&&isset($super_select_settings['stc_table_name']['value']))
                @foreach($columns_global_settings[$tables_global_settings[$super_select_settings['stc_table_name']['value']]['id']] as $column_name_in =>$column)
                    <option
                            @if(isset ($super_select_settings['stc_column_name'])&&
                            $super_select_settings['stc_column_name']['value']==$column_name_in)
                            selected
                            @endif
                    >{{$column_name_in}}</option>
                @endforeach
            @endif
        </select>

    </div>
    <div class="col-xs-2">
        <h4 class="pull-right">Column List:</h4>
    </div>
    <div class="col-xs-2">
        <select class=" form-control selectpicker super-select-table-column-list-js"
                tabindex="-1"
                aria-hidden="true"
                multiple
                data-selected-text-format="count > 3"

                name="json_{{$super_select_settings['stc_column_list']['key'] or
            'new[column_type]['.$columns[$column_name]['id']['value'].'][stc_column_list]'}}[]"

        >
            @if(isset($columns_global_settings)&&isset($super_select_settings['stc_table_name']))
                @foreach($columns_global_settings[$tables_global_settings[$super_select_settings['stc_table_name']['value']]['id']] as $column_name_in_1 =>$column)
                    <option
                            @if(isset ($super_select_settings['stc_column_list'])&&is_array(json_decode($super_select_settings['stc_column_list']['value']))&&
                            in_array($column_name_in_1,json_decode($super_select_settings['stc_column_list']['value'])))
                            selected
                            @endif
                    >{{$column_name_in_1}}</option>
                @endforeach
            @endif
        </select>

    </div>

</div>
