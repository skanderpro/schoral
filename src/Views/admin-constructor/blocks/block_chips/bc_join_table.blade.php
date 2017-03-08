<hr>

@if(!isset($table))

    <input type="hidden" name="new[table][{{$table_number}}][table_purpose]" value="join">
@endif
<div class="wrap-table-js" data-table_join_number={{$table_number}}>
    <div class="row">

        <div class="col-xs-1">
            <h4>j-Table:</h4>
        </div>

        <div class="col-xs-3">
            <select class=" form-control select2 select2-hidden-accessible select-table-join-js " tabindex="-1"
                    aria-hidden="true"
                    name="{{$table['table_name']['key'] or 'new[table]['.$table_number.'][table_name]'}}"
                    @if(isset($table['table_name'])) disabled @endif>
                <option value="none">choose table</option>
                @foreach($tables_global_settings as $table_name =>$table_settings)
                    @if($table_settings['table_visible']==1)
                        <option value="{{$table_name}}"
                                @if(isset($table)&&$table['table_name']['value']==$table_name)
                                selected
                                @endif
                        >
                            {{$table_name}}
                        </option>
                    @endif
                @endforeach
            </select>
        </div>

        <div class="col-xs-2 ">
            <select class=" form-control select2 select2-hidden-accessible" tabindex="-1"
                    aria-hidden="true"
                    name="{{$table['table_color']['key'] or 'new[table]['.$table_number.'][table_color]'}}">
                @foreach($selects['table_color'] as $color_name =>$color)
                    <option value="{{$color['value']}}"
                            @if(isset($table)&&$table['table_color']['value']==$color_name )
                            selected
                            @endif
                    >{{$color['title']}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-xs-1 no-padding">

            <a class="btn unit-delete-js" name="{{$table_key or 'new'}}" style="color:red"><i
                        class="fa fa-trash "></i>
            </a>

            <a class="btn edit-row-js" name="{{123}}" style="color:green"><i
                        class="fa fa-pencil "></i>
            </a>

        </div>
    </div>
    <div class="row">
        <div class="col-xs-2" style="padding-right:0;">
            <div class="checkbox">
                <label>
                    <input type="checkbox"
                           name="{{$table['table_row_add']['key'] or 'new[table]['.$table_number.'][table_row_add]'}}"
                           value="1"
                           @if(isset($table)&&$table['table_row_add']['value']==1)
                           checked
                            @endif
                    >
                    row-add
                </label>
            </div>
        </div>
        <div class="col-xs-2 no-padding">
            <div class="checkbox">
                <label>
                    <input type="checkbox"
                           name="{{$table['table_row_delete']['key'] or 'new[table]['.$table_number.'][table_row_delete]'}}"
                           value="1"
                           @if(isset($table)&&$table['table_row_delete']['value']==1)
                           checked
                            @endif
                    >
                    row-delete
                </label>
            </div>
        </div>
        <div class="col-xs-2 no-padding">
            <div class="checkbox">
                <label>
                    <input type="checkbox"
                           name="{{$table['table_row_editable']['key'] or 'new[table]['.$table_number.'][table_row_editable]'}}"
                           value="1"
                           @if(isset($table)&&$table['table_row_editable']['value']==1)
                           checked
                            @endif
                    >
                    row-editable
                </label>
            </div>
        </div>
        <div class="col-xs-2">
            <label>
                <input type="radio"
                       name="{{$table['table_main_sort_id']['key'] or 'new[table]['.$table_number.'][table_main_sort_id]'}}"
                       id="table_main_sort_id" value="{{$table['id']['value'] or 'new'}}"
                       @if(isset($table)&&$table['table_main_sort_id']['value']==$table['id']['value'])
                       checked
                        @endif
                >
                Main sort table
            </label>
        </div>
    </div>

    <div class="columns-settings-js">
        @if(isset($table['columns'])||isset($table_new))

            @include('scholar::admin-constructor.blocks.block_chips.bc_columns_settings')
        @endif
    </div>
</div>




























