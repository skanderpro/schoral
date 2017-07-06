<div class="col-xs-4 text-center no-padding form-group">
    <span>Image folder</span>
    <input
            @if(isset($columns[$column_name]['column_display_type']['type_settings']['image_folder']))
            name="{{$columns[$column_name]['column_display_type']['type_settings']['image_folder']['key']}}"
            value="{{$columns[$column_name]['column_display_type']['type_settings']['image_folder']['value']}}"
            @else
            name="{{'new[column_type]['.$columns[$column_name]['id']['value'].'][image_folder]'}}}"
            @endif
    />
</div>