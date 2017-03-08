<?
$table_number=0;
if (isset($block['table_main_id'])) {
    $table_main_id = $block['table_main_id'];
    $table_main = $block['tables'][$table_main_id];
    $table_main['columns'] = $block['columns'][$table_main['id']['value']];
    $columns_all = $block['columns'];


    if (isset($block['tables'])) {
        $tables_join = [];
        foreach ($block['tables'] as $table_id => $table) {
            if ($table['table_purpose']['value'] == 'join') {
                $tables_join[$table_id] = $table;
            }
        }
    }
}
?>
@if(isset($block))
    <input type="hidden" name="new[table_settings][block_id]" value="{{$block['id']['value']}}">
    <input type="hidden" name="{{'new[table]['.$table_number.'][table_purpose]'}}" value="main">
@endif

<div class="form-group col-xs-12 wrap-table-js" data-table_join_number=0>
    <div class="row">
        <div class="col-xs-2">
            <h3 class="no-margin">Settings:</h3>
        </div>
        <div class="col-xs-2">
            <h4>Database Table:</h4>
        </div>
        <div class="col-xs-3">
            <select class=" form-control select2 select2-hidden-accessible select-table-js " tabindex="-1"
                    aria-hidden="true" name="{{$table_main['table_name']['key'] or 'new[table]['.$table_number.'][table_name]'}}"
                    @if(isset($table_main['table_name'])) disabled @endif>
                <option value="none">choose table</option>

                @foreach($tables_global_settings as $table_name =>$table)
                    @if($table['table_visible']==1)
                        <option value="{{$table_name}}"
                                @if(isset($table_main)&&$table_main['table_name']['value']==$table_name)
                                selected
                                @endif
                        >
                            {{$table_name}}
                        </option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-2">
            <div class="checkbox">
                <label>
                    <input type="checkbox"
                           name="{{$table_main['table_visible']['key'] or 'new[table]['.$table_number.'][table_visible]'}}"
                           value="1"
                           @if(isset($table_main)&&$table_main['table_visible']['value']==1)
                           checked
                            @endif
                    >
                    Table_visible
                </label>
            </div>
        </div>
        <div class="col-xs-2">
            <div class="checkbox">
                <label>
                    <input type="checkbox"
                           name="{{$table_main['table_row_add']['key'] or 'new[table]['.$table_number.'][table_row_add]'}}"
                           value="1"
                           @if(isset($table_main)&&$table_main['table_row_add']['value']==1)
                           checked
                            @endif
                    >
                    row-add
                </label>
            </div>
        </div>
        <div class="col-xs-2">
            <div class="checkbox">
                <label>
                    <input type="checkbox"
                           name="{{$table_main['table_row_delete']['key'] or 'new[table]['.$table_number.'][table_row_delete]'}}"
                           value="1"
                           @if(isset($table_main)&&$table_main['table_row_delete']['value']==1)
                           checked
                            @endif
                    >
                    row-delete
                </label>
            </div>
        </div>
        <div class="col-xs-2">
            <div class="checkbox">
                <label>
                    <input type="checkbox"
                           name="{{$table_main['table_row_editable']['key'] or 'new[table]['.$table_number.'][table_row_editable]'}}"
                           value="1"
                           @if(isset($table_main)&&$table_main['table_row_editable']['value']==1)
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
                       name="{{$table_main['table_main_sort_id']['key'] or 'new[table]['.$table_number.'][table_main_sort_id]'}}"
                       id="table_main_sort_id" value="{{$table_main_id or 'new'}}"
                       @if(isset($table_main)&&$table_main['table_main_sort_id']['value']==$table_main_id)
                       checked
                        @endif
                >
                Main sort table
            </label>
        </div>


    </div>

    <div class="row">
        <div class="col-xs-2">
            <h4 class="pull-right">Color Theme:</h4>
        </div>
        <div class="col-xs-2">
            <select class=" form-control select2 select2-hidden-accessible" tabindex="-1"
                    aria-hidden="true" name="{{$table_main['table_color']['key'] or 'new[table]['.$table_number.'][table_color]'}}">
                @foreach($selects['table_color'] as $color_name =>$color)
                    <option value="{{$color['value']}}"
                            @if(isset($table_main)&&$table_main['table_color']['value']==$color_name )
                            selected
                            @endif
                    >{{$color['title']}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-xs-2 ">
            <h4 class="pull-right">Count records:</h4>
        </div>
        <div class="col-xs-2">
            <select class=" form-control select2 select2-hidden-accessible " tabindex="-1"
                    aria-hidden="true"
                    name="{{$table_main['table_count_records']['key'] or 'new[table]['.$table_number.'][table_count_records]'}}">

                @foreach($selects['table_count_records'] as $count_records => $count_item)
                    <option value="{{$count_item['value']}}"
                            @if(isset($table_main)&&$table_main['table_count_records']['value']==$count_item['value'])
                            selected
                            @endif
                    >{{$count_item['title']}}</option>
                @endforeach

            </select>
        </div>
    </div>
    <hr style="margin-bottom: 5px;">

    <div class="columns-settings-js">
        @if(isset($table_main['columns']))
            <?
            if (isset($table_main)) {
                $table = $table_main;
                $table_name = $table_main['table_name']['value'];
                $table_number = 0;
                $table_id=$table_main_id;
            }
            ?>
            @include('scholar::admin-constructor.blocks.block_chips.bc_columns_settings')
        @endif
    </div>
    <div class="row" style="margin-top:25px; ">
        <div class="col-xs-12 ">
            <h4 class="pull-left">Join Tables: </h4>
            <a class="btn btn-default add-table-join-js pull-right" name="" style="color:#333">
                <i class="fa fa-plus"></i>
            </a>
        </div>
    </div>
</div>


<div class="join-tables-js col-xs-12" data-count_tables="0">
    <?$i = 1; ?>
    @if(isset($tables_join))
        @foreach($tables_join as $table_id => $table)
            <?
            $table_key = 'table[' . $table_id . ']';
            $table_number = $i;
            $i++;
            $columns = $block['columns'][$table['id']['value']];
            $table['columns'] = $columns;
            ?>
            @include('scholar::admin-constructor.blocks.block_chips.bc_join_table')
        @endforeach
    @endif
</div>
