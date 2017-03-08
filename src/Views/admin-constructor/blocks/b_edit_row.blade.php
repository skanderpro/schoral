<?

if (isset($table_settings['columns']) && isset($table_global_settings['columns'])) {
    $columns = $table_settings['columns'];
    $columns_global = $table_global_settings['columns'];
}

?>

@foreach($columns as $column_name => $column)
    @include('scholar::admin-constructor.blocks.block_chips.b_edit_row_cell')
@endforeach

<style>
    .edit-row-css {
        margin: 1vw;
        font-size: 1vw;
    }
</style>