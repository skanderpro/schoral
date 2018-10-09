<div class="grid-stack">
    @foreach($page_configurations['views'][$current_view] as $block_id => $block)

        @if($block['block_type']['value']=='block_table_b')
            <div class="grid-stack-item block-wrap-js block-table-css"
                 data-block_id="{{$block['id']['value']}}"
                 data-block_type="{{$block['block_type']['value']}}"
                 data-gs-id="{{$block['id']['value']}}"
                 data-gs-x="{{$block['block_x']['value']}}" data-gs-y="{{$block['block_y']['value']}}"
                 data-gs-width="{{$block['block_width']['value']}}"
                 data-gs-height="{{$block['block_height']['value']}}">
                <div class="grid-stack-item-content">
                    <div class="block-panel-edit-css">
                        <div>

                            @if(isset($block['tables']))
                                @foreach($block['tables'] as $table_id => $table)
                                    <?
                                    $table_color = $table['table_color']['value'];
                                    switch ($table_color) {
                                        case 'default':
                                            $color = 'rgba(100,100,100,1)';
                                            break;
                                        case 'green':
                                            $color = 'rgba(0,200,0,1)';
                                            break;
                                        case 'red':
                                            $color = 'rgba(200,0,0,1)';
                                            break;
                                        case 'blue':
                                            $color = 'rgba(0,0,100,1)';
                                            break;
                                        case 'purple':
                                            $color = 'rgba(100,0,200,1)';
                                            break;
                                        case 'orange':
                                            $color = 'rgba(250,100,0,1)';
                                            break;
                                        default:
                                            $color='rgba(100,100,100,1)';
                                    }
                                    ?>
                                    @if($block['tables'][$table_id]['table_row_add']['value']==1)
                                <a class="btn btn-flat block-panel-edit-btn-css create-row-js" style="color:{{$color}};
                                      "
                                   data-table_id="{{$table_id}}"
                                >
                                    <i class="fa fa-plus"></i>
                                </a>
                                    @endif
                                @endforeach
                            @endif
                                <a class="btn btn-flat block-panel-edit-btn-css edit-filter-js ">
                                    <i class="fa fa-filter"></i>
                                </a>
                            <a class="btn btn-flat block-panel-edit-btn-css edit-block-js">
                                <i class="fa fa-pencil"></i>
                            </a>
                            <a class="btn btn-flat block-panel-edit-btn-css unit-delete-js" name="{{$block['key']}}">
                                <i class="fa fa-trash"></i>
                            </a>
                             <a class="btn btn-flat grid-stack-handle" >
                                <i class="fa fa-bars"></i>
                            </a>
                        </div>
                    </div>

                    @include('scholar::admin-client.blocks.cl_b_table')
                </div>
            </div>
        @elseif($block['block_type']['value']=='structure')
        @elseif($block['block_type']['value']=='block_custom_b')
            <div class="grid-stack-item block-custom-css block-wrap-js"
                 data-block_id="{{$block['id']['value']}}"
                 data-block_type="{{$block['block_type']['value']}}"
                 data-gs-id="{{$block['id']['value']}}"
                 data-gs-x="{{$block['block_x']['value']}}" data-gs-y="{{$block['block_y']['value']}}"
                 data-gs-width="{{$block['block_width']['value']}}"
                 data-gs-height="{{$block['block_height']['value']}}">
                <h2>{{$block['block_title']['value']}}</h2>

                <div class="grid-stack-item-content">
                    <div class="block-panel-edit-css">
                        <div>
                            <a class="btn btn-flat block-panel-edit-btn-css edit-block-js">
                                <i class="fa fa-pencil"></i>
                            </a>
                            <a class="btn btn-flat block-panel-edit-btn-css unit-delete-js" name="{{$block['key']}}">
                                <i class="fa fa-trash"></i>
                            </a>


                        </div>

                    </div>
                    {!! $block['content'] !!}
                </div>

            </div>
        @endif

    @endforeach
</div>

<style>

    .block-panel-edit-btn-css {
        color: darkred;

    }

    .block-panel-edit-btn-css:hover {
        color: green;
    }

    .block-panel-edit-css {
        display: none;
        position: absolute;
        z-index: 10;
        opacity: 0.8;
        top: 0;
        right: 0;
        background: gold;
        height:2.5vw;
        font-size:1.5vw;

    }
    .block-panel-edit-css a {

        font-size: 1vw;
        margin:0
    }

    .wrap-header{
        height:2.5vw;
        padding-top:0.5vw;
    }

    .wrap-header h3{
        font-size: 1.5vw;
        margin:0 !important;

    }
    .block-custom-css:hover .block-panel-edit-css {
        display: block !important;

    }

    .block-custom-css {
        background-color: white;
        border: gold 1px solid;
    }

    .block-table-css {
        padding: 10px;
        background-color: #eee;
    }

    .grid-stack-item-content {
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
    }

    .box {
        margin-bottom: 0 !important;
    }
</style>
