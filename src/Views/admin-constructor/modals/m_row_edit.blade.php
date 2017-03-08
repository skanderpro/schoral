

<div class="modal fade col-xs-12 no-padding" id="edit_row" >
    <div class="modal-dialog" >
        <form class="modal-content form-horizontal form-block-settings-js " style="box-shadow: 6px 8px 16px 0 rgba(10, 11, 13, .6);">
            {{ csrf_field() }}
            <input type="hidden"
                   @if(isset($row_id)&&$row_id!='new')

                   @else
                   name="new[row][table_name]"
                   value="{{$table_settings['table_name']['value']}}"
                    @endif
            >

            <div class="modal-header" >
                <div class="row no-margin">
                    <div class="col-xs-10 col-md-10">
                        <h4 class="modal-title">@if(isset($row_id)&&$row_id!='new')Edit Row: {{$row_id}} in Table:
                            {{$table_settings['table_name']['value']}} @else Creating Row in
                            Table: {{$table_settings['table_title']['value']}}@endif
                        </h4>
                    </div>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
            </div>
            <div class="modal-body">


                    @if(isset($table_settings)&&isset($table_global_settings))
                        @include('scholar::admin-constructor.blocks.b_edit_row')
                    @endif

            </div>
            <div class="modal-footer" >
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal" id="close_modal">Close
                </button>
                @if(isset($row_id)&&$row_id!='new')
                    <button type="submit" class="btn btn-primary unit-save-later-js">
                        Save
                    </button>
                @else
                    <button type="submit" class="btn btn-primary unit-create-later-js">
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
