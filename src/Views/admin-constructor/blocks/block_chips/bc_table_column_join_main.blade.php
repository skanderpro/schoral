@if (isset($columns_join))
    @foreach($columns_join as $column_number => $column_name)
        <option value="{{$column_name}}"
                @if($selected_column==$column_name&&$selected_column!='none')
                selected
                @endif
        >{{$column_name}}
        </option>
    @endforeach
@endif