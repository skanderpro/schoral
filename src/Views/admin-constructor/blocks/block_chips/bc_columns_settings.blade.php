<?
if(isset($table['columns']) || isset($table_new)){

if (isset($table['columns'])) {
    $columns = $table['columns'];
    $table_name = $table['table_name']['value'];
} else if (isset($table_new)) {
    $columns_new = $table_new['columns'];
    $table_name = $table_new['table_name']['value'];

}
if (isset($columns)) {
    $new = false;
} else {
    $new = true;
    $columns = $columns_new;
}
?>
@if($table_number!=0)
    <div class="row">
        <div class="col-xs-2">
            <h4 class="pull-right">Column Join:</h4>
        </div>
        <div class="col-xs-2">
            <select class=" form-control select2 select2-hidden-accessible  " tabindex="-1"
                    aria-hidden="true"
                    name="{{$table['table_column_join']['key'] or 'new[table]['.$table_number.'][table_column_join]'}}">


                @foreach($columns as $column_name => $column)

                    <option value="{{$column_name}}"
                            @if($new==false&& $table['table_column_join']['value']==$column_name)
                            selected
                            @endif
                    >{{$column_name}}
                    </option>
                @endforeach
            </select>

        </div>
        <div class="col-xs-8 wrap-super-select-js">
            <div class="col-xs-3">
                <h4>main join table:</h4>
            </div>

            <div class="col-xs-3">
                <select class=" form-control select2 select2-hidden-accessible super-select-table-js " tabindex="-1"
                        aria-hidden="true"
                        name="{{$table_settings['table_join_main']['key'] or 'new[table]['.$table_number.'][table_join_main]'}}"
                >
                    <option value="none">choose table</option>
                    @foreach($tables_global_settings as $table_name =>$table_settings)
                        @if($table_settings['table_visible']==1)
                            <option value="{{$table_name}}"
                                    @if(isset($table)&&$table['table_join_main']['value']==$table_name)
                                    selected
                                    @endif
                            >
                                {{$table_name}}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="col-xs-3">
                <h4 class="pull-right">Column Join Main:</h4>
            </div>
            <div class="col-xs-3">
                <select class=" form-control select2 select2-hidden-accessible super-select-table-column-js"
                        tabindex="-1"
                        aria-hidden="true"
                        name="{{$table['table_column_join_main']['key'] or 'new[table]['.$table_number.'][table_column_join_main]'}}"

                        data-selected_column="{{$table['table_column_join_main']['value'] or 'none'}}"

                >

                    @if(isset ($table['table_join_main_columns']))
                        @foreach($table['table_join_main_columns'] as $column_number => $column_name)

                            <option value="{{$column_name}}"
                                    @if($new==false&&$table['table_column_join_main']['value']==$column_name)
                                    selected
                                    @endif
                            >
                                {{$column_name}}
                            </option>
                        @endforeach
                    @endif
                </select>

            </div>
        </div>

    </div>
@endif
<div class="row">
    <div class="col-xs-2">
        <h4 class="pull-right">Sort Column:</h4>
    </div>
    <div class="col-xs-2">
        <select class="form-control select2 select2-hidden-accessible" tabindex="-1"
                aria-hidden="true"
                name="{{$table['table_sort_column']['key'] or 'new[table]['.$table_number.'][table_sort_column]'}}">


            @foreach($columns as $column_name => $column)
                @if($column['column_visible']['value']==1)
                    <option value="{{$table_name.'__'.$column_name}}"
                            @if($new==false && $table['table_sort_column']['value']==$table_name.'__'.$column_name)
                            selected
                            @endif
                    >
                        {{$table_name.': '.$column_name}}
                    </option>
                @endif
            @endforeach
        </select>

    </div>
    <div class="col-xs-2">
        <div class="col-xs-6">
            <label>
                <input type="radio"
                       name="{{$table['table_column_sort_direction']['key'] or 'new[table]['.$table_number.'][table_column_sort_direction]'}}"
                       id="optionsRadios1" value="0"
                       @if($new==false&&$table['table_column_sort_direction']['value']==0)
                       checked
                        @endif
                >
                ASC
            </label>
        </div>
        <div class="col-xs-6">
            <label>
                <input type="radio"
                       name="{{$table['table_column_sort_direction']['key'] or 'new[table]['.$table_number.'][table_column_sort_direction]'}}"
                       id="optionsRadios2" value="1"
                       @if($new==false&&$table['table_column_sort_direction']['value']==1)
                       checked
                        @endif
                >
                DESC
            </label>
        </div>
    </div>

</div>

<div class="box box-grey table-responsive no-padding bc-columns-settings" id="accordion">

    <div class="box-header">
        <div class="row text-center no-margin ">
            <div class="col-xs-1 text-left no-padding">
                <span>db_name</span>

            </div>
            <div class="col-xs-1 text-center no-padding">
                <div><span>visible</span></div>
                <div><input type="checkbox" class="select-all-js" data-class="column-visible-js"></div>

            </div>
            <div class="col-xs-1 text-center no-padding">
                <div><span>editable</span></div>
                <div><input type="checkbox" class="select-all-js" data-class="column-editable-js"></div>
            </div>
            <div class="col-xs-1 text-center no-padding">
                <div><span>visibleInRow</span></div>
                <div><input type="checkbox" class="select-all-js"
                            data-class="column-inRow-visible-js"></div>
            </div>
            <div class=" col-xs-1 no-padding">
                <div><span>editableInRow</span></div>
                <div><input type="checkbox" class="select-all-js" data-class="column-inRow-editable-js"></div>
            </div>
            <div class="col-xs-2 no-padding">
                <span>title</span>
            </div>
            <div class="col-xs-2 no-padding">
                <span>display-type</span>
            </div>
            <div class="col-xs-2 no-padding">
                <span>cl-db   cl-only   filter-type</span>
            </div>

        </div>
    </div>

    <div class="panel box box-primary">
        <div class="box-header with-border text-center">
            @foreach($columns as $column_name => $column)
                <div style="border:1px lightgrey solid;border-radius: 3px;margin-bottom: 10px;padding:5px;">
                    <div class="row no-margin ">
                        <div class="col-xs-1 text-left no-padding form-group">
                            {{$column_name}}
                        </div>
                        <div class="col-xs-1 text-center no-padding form-group">
                            <input class="column-visible-js" type="checkbox" value="1"
                                   @if($new==false)
                                   name="{{$columns[$column_name]['column_visible']['key']}}"
                                   @else
                                   name="new[columns][{{$table_number}}][{{$column_name}}][column_visible]"
                                   @endif
                                   @if (isset($columns)&&$columns[$column_name]['column_visible']['value']==1){
                                   checked
                                    @endif
                            >
                        </div>
                        <div class="col-xs-1 text-center no-padding form-group">
                            <input class="column-editable-js" value="1" type="checkbox"
                                   @if($new==false)
                                   name="{{$columns[$column_name]['column_editable']['key']}}"
                                   @else
                                   name="new[columns][{{$table_number}}][{{$column_name}}][column_editable]"
                                   @endif


                                   @if (isset($columns)&&$columns[$column_name]['column_editable']['value']==1)
                                   checked
                                    @endif
                            >
                        </div>
                        <div class="col-xs-1 text-center no-padding form-group">
                            <input class="column-inRow-visible-js" type="checkbox" value="1"

                                   @if($new==false)
                                   name="{{$columns[$column_name]['column_inRow_visible']['key']}}"
                                   @else
                                   name="new[columns][{{$table_number}}][{{$column_name}}][column_inRow_visible]"
                                   @endif

                                   @if (isset($columns)&&$columns[$column_name]['column_inRow_visible']['value']==1){
                                   checked
                                    @endif

                            >
                        </div>
                        <div class="col-xs-1 text-center no-padding form-group">
                            <input class="column-inRow-editable-js" value="1" type="checkbox"
                                   @if($new==false)
                                   name="{{$columns[$column_name]['column_inRow_editable']['key']}}"
                                   @else
                                   name="new[columns][{{$table_number}}][{{$column_name}}][column_inRow_editable]"
                                   @endif

                                   @if (isset($columns)&&$columns[$column_name]['column_inRow_editable']['value']==1)
                                   checked
                                    @endif
                            >
                        </div>
                        <div class="col-xs-2 text-center no-padding form-group">
                            <input class="form-control"

                                   @if($new==false)
                                   name="{{$columns[$column_name]['column_title']['key']}}"
                                   @else
                                   name="new[columns][{{$table_number}}][{{$column_name}}][column_title]"
                                   @endif
                                   type="text"
                                   placeholder="title for admin"
                                   value="{{$columns[$column_name]['column_title']['value'] or ''}}"
                            >
                        </div>
                        <div class="col-xs-2 text-center no-padding form-group">

                            <select class="form-control change-displayType-js"
                                    data-column_id="{{$columns[$column_name]['id']['value']}}"
                                    @if($new==false)
                                    name="{{$columns[$column_name]['column_display_type']['key']}}"
                                    @else
                                    name="new[columns][{{$table_number}}][{{$column_name}}][column_display_type]"
                                    @endif
                            >

                                @foreach($selects['cell_display_type'] as $type_name => $type)
                                    <option value="{{$type['value']}}"
                                            @if(isset($columns) && $columns[$column_name]['column_display_type']['value'] == $type['name'])
                                            selected
                                            @endif
                                    >
                                        {{$type['title']}}
                                    </option>
                                @endforeach

                            </select>

                        </div>

                        <div class="col-xs-2 text-center no-padding form-group">
                            <div class="input-group">
                                <span class="input-group-addon">
                                <input class="f" value="1" type="checkbox"
                                       @if($new==false)
                                       name="{{$columns[$column_name]['column_client_db_filter']['key']}}"
                                       @else
                                       name="new[columns][{{$table_number}}][{{$column_name}}][column_client_db_filter]"
                                       @endif

                                       @if (isset($columns)&&$columns[$column_name]['column_client_db_filter']['value']==1)
                                       checked
                                        @endif
                                >
                                </span>
                                <span class="input-group-addon">
                                <input class="f" value="1" type="checkbox"
                                       @if($new==false)
                                       name="{{$columns[$column_name]['column_client_session_filter']['key']}}"
                                       @else
                                       name="new[columns][{{$table_number}}][{{$column_name}}][column_client_session_filter]"
                                       @endif


                                       @if (isset($columns)&&$columns[$column_name]['column_client_session_filter']['value']==1)
                                       checked
                                        @endif
                                >
                                </span>
                                <select class="form-control change-filterType-js"
                                        data-column_id="{{$columns[$column_name]['id']['value']}}"
                                        @if($new==false)
                                        name="{{$columns[$column_name]['column_filter_type']['key']}}"
                                        @else
                                        name="new[columns][{{$table_number}}][{{$column_name}}][column_filter_type]"
                                        @endif
                                >

                                    @foreach($selects['column_filter_type'] as $filter_name => $filter)
                                        <option value="{{$filter['name']}}"
                                                @if(isset($columns[$column_name]['column_filter_type']['value']) &&
                                                $columns[$column_name]['column_filter_type']['value'] == $filter['name'])
                                                selected
                                                @endif
                                        >

                                            {{$filter['title']}}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                        </div>

                        <div class="pull-right">

                            <div class="pull-right">
                                <a data-toggle="collapse" data-parent="#accordion"
                                   href="#collapse_{{$columns[$column_name]['table_id']['value'].$columns[$column_name]['column_name']['value']}}"
                                   class="btn " aria-expanded="true"
                                   data-original-title="Edit"><i class="fa fa-pencil"></i></a>
                            </div>

                        </div>

                    </div>
                    <div id="collapse_{{$columns[$column_name]['table_id']['value'].$columns[$column_name]['column_name']['value']}}"
                         class="panel-collapse collapse"
                         aria-expanded="true">
                        <div class="box-body"
                             id="settings_{{$columns[$column_name]['table_id']['value'].$columns[$column_name]['column_name']['value']}}">
                            @if($columns[$column_name]['column_display_type']['value']=='selectbox')
                                @include('scholar::admin-constructor.blocks.block_chips.bc_columns_settings.b_selectbox_settings')
                            @endif

                            @if($columns[$column_name]['column_display_type']['value']=='image')
                                @include('scholar::admin-constructor.blocks.block_chips.bc_columns_settings.b_image_settings')
                            @endif

                            @if($columns[$column_name]['column_display_type']['value']=='selectbox_column')
                                @include('scholar::admin-constructor.blocks.block_chips.bc_columns_settings.b_selectbox_column_settings')
                            @endif
                            @if(isset($table_id))
                                @include('scholar::admin-constructor.blocks.block_chips.bc_columns_settings.b_filters_settings')
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
<?}?>

<style>
    .bc-columns-settings th, .bc-columns-settings td {
        color: #333;
    }

    .bc-columns-settings {
        max-height: 20vw;
        overflow: scroll;
    }

    thead tr {
    }

</style>

<script>
    $('.selectpicker').selectpicker({
        style: 'btn-default',
        size: 4
    });
</script>
