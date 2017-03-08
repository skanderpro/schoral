<div class="modal modal-primary fade col-xs-12 no-padding" id="create_block">
    <div class="modal-dialog">
        <form class="modal-content col-xs-12 no-padding form-block-settings-js" style="box-shadow: 6px 8px 16px 0 rgba(10, 11, 13, .6);">
            {{ csrf_field() }}
            @if(isset($block['id']['value']))

            @else
            <input type="hidden" name="new[block][page_view]"
                   value="{{$page_view}}"
            >

            <input type="hidden" name="new[block][block_id]"
                   value="1"
            >

            <input type="hidden" id="page_id"  name="new[block][page_id]"
                   value="{{$page_id}}"
            >

            @endif
            <div class="modal-header">
                <div class="row no-margin">

                    <div class="col-xs-3 col-md-3">
                        <h4 class="modal-title">@if(isset($block))Edit Block: {{$block['block_title']['value']}}   @else
                                Creating Block . . .@endif </h4>
                    </div>
                    <div class="col-xs-3 col-md-3">
                        <?
                        $options = array('block_table_b' => 'Table', 'block_structure_b' => 'Structure', 'block_edit_row_b' => 'Edit-Row', 'block_custom_b' => 'Custom');
                        ?>
                        <select class=" form-control select2 select2-hidden-accessible select-block-type-js"
                                tabindex="-1" aria-hidden="true"
                                name="{{$block['block_type']['key'] or 'new[block][block_type]'}}"
                                @if(isset($block)) disabled @endif>
                            <option selected="selected" value="none">block_type</option>
                            @foreach($options as $option_value =>$option_name)
                                <option
                                        @if(isset($block['block_type']['value'])&& $block['block_type']['value']==$option_value)
                                        selected
                                        @endif
                                        value="{{$option_value}}"
                                >
                                    {{$option_name}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xs-2">
                        <h4 class="pull-right">Table Title:</h4>
                    </div>
                    <div class="col-xs-3 ">
                        <input class="form-control" name="{{$block['block_title']['key'] or 'new[block][block_title]'}}"
                               value="<?if (isset($block['block_title']['value'])) {
                                   echo $block['block_title']['value'];
                               } elseif (isset($block['block_type']['value'])) {
                                   echo $block['block_type']['value'];
                               }

                               ?>"
                               placeholder="input title"
                        >
                    </div>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>


            </div>
            <div class="modal-body">
                <div class="row modal-block-settings">
                    @if(isset($block)&&$block['block_type']['value']=='block_table_b')
                        @include('scholar::admin-constructor.blocks.block_table_b')
                    @elseif(isset($block)&&$block['block_type']['value']=='block_custom_b')
                        <p style="padding:20px;font-size: 20px;">Block ID = {{$block['id']['value']}}</p>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal" id="close_modal">
                    Close
                </button>

                @if(isset($block))
                    <button type="submit" class="btn btn-outline unit-save-create-later-js">
                        Save
                    </button>
                @else
                    <button type="submit" class="btn btn-outline unit-create-later-js">
                        Create
                    </button>
                @endif
            </div>
        </form>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<style>
    @media (min-width: 768px) {
        .modal-dialog {
            width: 60%;
            margin: 5vw 20%;
        }
    }

    @media (max-width: 768px) {
        .modal-dialog {
            width: 90%;
            margin: 5%;
        }
    }

    .modal-content {
        width: 100%;
    }

    .text-grey {
        color: #444;
    }

</style>
