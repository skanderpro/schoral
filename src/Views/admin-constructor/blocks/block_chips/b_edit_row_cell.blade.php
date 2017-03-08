<? /*print_r($columns);
exit();*/ ?>
@if($column['column_inRow_visible']['value']==1)
    <div class="edit-row-css form-group">
        <label class="col-sm-3 control-label">
            {{$column['column_title']['value']}}:
        </label>
        <div class=" col-sm-9">
            @if($column['column_display_type']['value']=='text')


                @if($column['column_inRow_editable']['value']==1)
                    <input class="form-control" type="text"

                           @if(isset($row_content))
                           name="{{$row_content[$column_name]['key']}}"
                           value="{{$row_content[$column_name]['value']}}"
                           @else
                           name="{{'new[row][row_data]['.$column['column_name']['value'].']'}}"
                            @endif

                    >
                @else
                    <input class="form-control" disabled type="text"
                           @if(isset($row_content))
                           value="{{$row_content[$column_name]['value']}}"
                            @endif
                    >
                @endif


            @elseif($column['column_display_type']['value']=='icon')


                @if($column['column_editable']['value']==1)

                    @if($row_content[$column_name]['value']=='')
                        <a class="edit-icon-js" style="cursor:pointer;color:darkred" href="#"><i
                                    class=" fa fa-question"></i></a>
                        <input class="" name="{{$row_content[$column_name]['key']}}"
                               value="{{$row_content[$column_name]['value']}}" type="hidden">
                    @else
                        <a class="edit-icon-js" style="cursor:pointer;color:green" href="#"><i
                                    class=" fa {{$row_content[$column_name]['value']}}"></i></a>
                        <input class="" name="{{$row_content[$column_name]['key']}}"
                               value="{{$row_content[$column_name]['value']}}" type="hidden">
                    @endif

                @else

                    @if($row_content[$column_name]['value']==''||$row_content[$column_name]['value']==' ')
                        <i style="color:grey;" class=" fa fa-question"></i>
                    @else
                        <i class="fa {{$row_content[$column_name]['value']}}"></i>
                    @endif

                @endif

            @elseif($column['column_display_type']['value']=='image')


                @if(isset($row_content))
                    <img style="max-height:2vw" src="{{$row_content[$column_name]['value']}}">
                @endif

            @elseif($column['column_display_type']['value']=='checkbox')


                <input class="" type="checkbox" style="height:1vw;width:1vw" value="1"
                       @if(isset($row_content))
                       name="{{$row_content[$column_name]['key']}}"
                       @else
                       name="{{'new[row][row_data]['.$column['column_name']['value'].']'}}"
                       @endif
                       @if($column['column_inRow_editable']['value']!=1)
                       disabled
                       @endif
                       @if(isset($row_content)&&$row_content[$column_name]['value']==1)
                       checked
                        @endif
                >

            @elseif($column['column_display_type']['value']=='textarea')

                <textarea class="" style="max-height:5vw;max-width:10vw; overflow: hidden"
                          @if(isset($row_content)))
                          name="{{$row_content[$column_name]['key']}}"
                          @else
                          name="{{'new[row][row_data]['.$column['column_name']['value'].']'}}"
                          @endif

                          @if($column['column_inRow_editable']['value']!=1)
                          disabled
                        @endif
                >
            @if(isset($row_content))
                        {{$row_content[$column_name]['value']}}
                    @endif

    </textarea>

            @elseif($columns[$column_name]['column_display_type']['value']=='selectbox')
                <select class=" form-control" style="min-width:150px;"
                        @if(isset($row_content))
                        name="{{$row_content[$column_name]['key']}}"
                        @else
                        name="{{'new[row][row_data]['.$column['column_name']['value'].']'}}"
                        @endif

                        @if($columns[$column_name]['column_inRow_editable']['value']!=1)
                        disabled
                        @endif


                >
                    @if(isset($columns[$column_name]['column_display_type']['type_settings']['select_group_name']['value']))
                        @foreach($selects[$columns[$column_name]['column_display_type']['type_settings']['select_group_name']['value']] as
                        $select_item_name =>$select_item)
                            <option value="{{$select_item['value']}}"
                                    @if($select_item['value']==$row_content[$column_name]['value'])
                                    selected
                                    @endif
                            >{{$select_item['title']}}</option>
                        @endforeach
                    @endif
                </select>
            @elseif($columns[$column_name]['column_display_type']['value']=='selectbox_column')

                <select class="unit-save-js form-control" name="{{$row_content[$column_name]['key'] or
                'new[row][row_data]['.$column['column_name']['value'].']'}}"
                        style="min-width:150px;"
                        @if($columns[$column_name]['column_inRow_editable']['value']!=1)
                        disabled
                        @endif
                >
                    @if(isset($columns[$column_name]['column_display_type']['type_settings']['items']))
                        @foreach($columns[$column_name]['column_display_type']['type_settings']['items'] as
                        $item_number =>$item)
                            <option value="{{$item[$columns[$column_name]['column_display_type']['type_settings']['stc_column_name']['value']]}}"
                                    @if($item[$columns[$column_name]['column_display_type']['type_settings']['stc_column_name']['value']]==$row_content[$column_name]['value'])
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


                @if($columns[$column_name]['column_inRow_editable']['value']==1)
                    <input class="form-control " type="text" id="{{$table_settings['id']['value'].'_'.$column_name}}"
                           @if(isset($row_content))
                           name="{{$row_content[$column_name]['key']}}"
                           value="{{$row_content[$column_name]['value']}}"
                           @else
                           name="{{'new[row][row_data]['.$column['column_name']['value'].']'}}"
                            @endif
                    >
                    <script>
                        $(function () {
                            $("{{'#'.$table_settings['id']['value'].'_'.$column_name}}").datepicker({dateFormat: 'yy-mm-dd'});
                        })
                    </script>
                @else
                    <input class="form-control" disabled type="text"
                           @if(isset($row_content))
                           value="{{$row_content[$column_name]['value']}}"
                            @endif
                    >
                @endif


            @endif
        </div>
    </div>
@endif

