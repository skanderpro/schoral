<div style="background-color:{{$color}};">
    @if($table_main['table_row_delete']==1)
        <a class="btn unit-delete-js pull-right" name="{{$row_key}}" style="margin-top:-2px;color:red; float:left;">
                <i class="fa fa-trash"></i>
        </a>
    @endif
    @if($table_main['table_row_editable']==1)
        <a class="btn edit-row-js pull-right" name="{{$row_key}}" style="margin-top:-2px;color:green">
                <i class="fa fa-pencil"></i>
        </a>
    @endif
</div>