<div style="background-color:{{$color}}; position: relative">
    @if($columns[$column_name]['column_display_type']['value']=='text')
        @if($columns[$column_name]['column_editable']['value']==1)
            <input class="unit-save-js form-control" type="text" value="{{$cell['value']}}" name="{{$cell['key']}}">
        @else
            <span>{{$cell['value']}}</span>
        @endif
    @elseif($columns[$column_name]['column_display_type']['value']=='icon')

        @if($columns[$column_name]['column_editable']['value']==1)
            @if($cell['value']=='')
                <div class="fa-editable-js">
                    <a class="edit-icon-js" style="cursor:pointer;color:darkred" href="#"><i
                                class=" fa fa-question"></i></a>
                    <input class="unit-save-js" name="{{$cell['key']}}" value="{{$cell['value']}}" type="hidden">
                </div>
            @else
                <div class="fa-editable-js">
                    <a class="edit-icon-js" style="cursor:pointer;color:green" href="#"><i
                                class=" fa {{$cell['value']}}"></i></a>
                    <input class="unit-save-js" name="{{$cell['key']}}" value="{{$cell['value']}}" type="hidden">
                </div>
            @endif
        @else
            @if($cell['value']==''||$cell['value']==' ')
                <i style="color:grey;" class=" fa fa-question"></i>
            @else
                <i class="fa {{$cell['value']}}"></i>
            @endif
        @endif

    @elseif($columns[$column_name]['column_display_type']['value']=='image')

        <div style=" display: flex;justify-content: flex-start;">
            <img style="max-height:40px; max-width: 40px; float: left" src="{{$cell['value']}}">
            @if($columns[$column_name]['column_editable']['value']==1)
                <input class="unit-save-js form-control" type="text"
                       name="{{$cell['key']}}"
                       value="{{$cell['value']}}"
                       style="float: left;">
                <i class="fa fa-picture-o image-save-js" data-name="{{$cell['key']}}"></i>
            @endif
        </div>

    @elseif($columns[$column_name]['column_display_type']['value']=='checkbox')

        <input class="unit-save-js" type="checkbox" style="height:1vw;width:1vw" value="1" name="{{$cell['key']}}"
               @if($columns[$column_name]['column_editable']['value']!=1)
               disabled
               @endif
               @if($cell['value'] == 1)
               checked
               @endif
        >


    @elseif($columns[$column_name]['column_display_type']['value']=='textarea')
        <textarea class="unit-save-js" style="background: transparent; height:100%;max-height:100%;width: 100%; overflow: hidden" name="{{$cell['key']}}"
                  @if($columns[$column_name]['column_editable']['value']!=1)
                  disabled
                @endif
        >{{$cell['value']}}</textarea>
    @elseif($columns[$column_name]['column_display_type']['value']=='selectbox')

        <select class="unit-save-js form-control" name="{{$cell['key'] }}"
                @if($columns[$column_name]['column_editable']['value']!=1)
                disabled
                @endif
        >
            @if(isset($columns[$column_name]['column_display_type']['type_settings']['select_group_name']['value']))
                @foreach($selects[$columns[$column_name]['column_display_type']['type_settings']['select_group_name']['value']] as
                $select_item_name =>$select_item)
                    <option value="{{$select_item['value']}}"
                            @if($select_item['value']==$cell['value'])
                            selected
                            @endif
                    >{{$select_item['title']}}</option>
                @endforeach
            @endif
        </select>

    @elseif($columns[$column_name]['column_display_type']['value']=='selectbox_column')

        <select class="unit-save-js form-control" name="{{$cell['key'] }}"
                @if($columns[$column_name]['column_editable']['value']!=1)
                disabled
                @endif
        >
            @if(isset($columns[$column_name]['column_display_type']['type_settings']['items']))
                @foreach($columns[$column_name]['column_display_type']['type_settings']['items'] as
                $item_number =>$item)
                    <option value="{{$item[$columns[$column_name]['column_display_type']['type_settings']['stc_column_name']['value']]}}"
                            @if($item[$columns[$column_name]['column_display_type']['type_settings']['stc_column_name']['value']]==$cell['value'])
                            selected
                            @endif
                    >
                        @foreach(json_decode($columns[$column_name]['column_display_type']['type_settings']['stc_column_list']['value']) as
                        $stc_column_name_number=>$stc_column_name)
                          <span>{{$item[$stc_column_name]}} </span>
                        @endforeach

                    </option>
                @endforeach
            @endif
        </select>



    @elseif($columns[$column_name]['column_display_type']['value']=='date')

        @if($columns[$column_name]['column_editable']['value']==1)
            <input class="form-control" type="text"
                   id="{{'cell'.$cell_number}}"
                   value="{{$cell['value']}}"
                   name="{{$cell['key']}}"
            >
            <script>
                $(function () {
                    $("{{'#'.'cell'.$cell_number}}").datepicker({dateFormat: 'yy-mm-dd'});
                })
            </script>
        @else
            <span>
                   {{$cell['value']}}
            </span>
        @endif
    @else
    @endif
</div>
<style>

    .dataTable .form-control {
        height:100%;
        width:100%;
        background: transparent;
        color:black;
        overflow: hidden;
    }
    input:focus {
        border: none !important;
    }

    input:focus {
        border: 1px solid deepskyblue !important;
    }

    input:hover {
        border: 1px solid green !important;
    }

    input:active {
        border: 1px solid deepskyblue !important;
    }
</style>