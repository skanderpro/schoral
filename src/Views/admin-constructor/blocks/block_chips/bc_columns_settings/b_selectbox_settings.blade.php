<div class="col-xs-4 text-center no-padding form-group">
    <span>Select Group Name:</span>
    <select
            @if(isset($columns[$column_name]['column_display_type']['type_settings']['select_group_name']))
            name="{{$columns[$column_name]['column_display_type']['type_settings']['select_group_name']['key']}}"
            @else
            name="{{'new[column_type]['.$columns[$column_name]['id']['value'].'][select_group_name]'}}}"
            @endif
    >
        @foreach($selects as $select_group_name =>$select_group)
            <option
                    @if(isset($columns[$column_name]['column_display_type']['type_settings']['select_group_name']['value'])&&
                    $columns[$column_name]['column_display_type']['type_settings']['select_group_name']['value']==$select_group_name)

                    selected
                    @else

                    @endif
                    value="{{$select_group_name}}">{{$select_group_name}}
            </option>
        @endforeach
    </select>
</div>