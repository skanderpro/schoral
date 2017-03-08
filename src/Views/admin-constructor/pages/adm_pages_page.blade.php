<!-- START ACCORDION & CAROUSEL-->

<div class="row">
    <div class="col-xs-12">
        <h2 class="page-header">Admin Pages</h2>

        <div class="box-group" id="accordion">
            <div class="box box-warning">
                <div class="box-header">
                    <div class="row text-center no-margin ">
                        <div class="col-xs-5 col-sm-3 col-md-2 text-left no-padding">
                            <span>Name</span>

                        </div>
                        <div class="col-xs-1 text-center no-padding">
                            <span>Admin <i class="fa fa-eye"></i></span>
                        </div>
                        <div class="col-xs-1 text-center no-padding">
                            <span>Constructor <i class="fa fa-eye"></i></span>

                        </div>
                        <div class="col-xs-1 text-center no-padding">
                            <span>Client <i class="fa fa-eye"></i></span>

                        </div>
                        <div class="hidden-xs col-sm-4 col-md-2 no-padding">
                            <span>Название страницы</span>

                        </div>
                        <div class="hidden-xs hidden-sm col-md-1 no-padding">
                            <span>fa-fa</span>
                        </div>
                        <div class="col-xs-3 col-sm-2 no-padding">
                            <span>Rights</span>
                        </div>
                        <div class="pull-right">

                            <a class="btn btn-circle btn-default show-tooltip" title=""
                               href="#"
                               data-original-title="Add new record">
                                <i class="fa fa-plus"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            @foreach($pages_settings['content'] as $row_key=>$page)
                <div class="panel box box-primary">
                    <div class="box-header with-border text-center">
                        <div class="row no-margin">
                            <div class="col-xs-5 col-sm-3 col-md-2 text-left no-padding">
                                <h4 class="box-title">
                                    <a data-toggle="collapse" data-parent="#accordion"
                                       href="#collapse_{{$page['id']['value']}}"
                                       aria-expanded="true" class="">
                                        {{$page['name']['value']}}
                                    </a>
                                </h4>
                            </div>
                            <div class="col-xs-1 text-center no-padding">
                                <input class="unit-save-js " type="checkbox" value="1"
                                       @if ($page['adm_visible']['value'] == 1) {
                                       checked
                                       @endif
                                       name="{{$page['adm_visible']['key']}}">
                            </div>
                            <div class="col-xs-1 text-center no-padding">
                                <input class="unit-save-js " type="checkbox" value="1"
                                       @if ($page['constructor_visible']['value'] == 1) {
                                       checked
                                       @endif
                                       name="{{$page['constructor_visible']['key']}}">

                            </div>
                            <div class="col-xs-1 text-center no-padding">
                                <input class="unit-save-js " type="checkbox" value="1"
                                       @if ($page['client_visible']['value'] == 1) {
                                       checked
                                       @endif
                                       name="{{$page['client_visible']['key']}}">

                            </div>
                            <div class="hidden-xs col-sm-4 col-md-2 ">
                                <input class="unit-save-js form-control" type="text"
                                       value="{{$page['title']['value']}}"
                                       name="{{$page['title']['key']}}">
                            </div>
                            <div class="hidden-xs hidden-sm col-md-1">
                                <span><i class="fa fa-{{$page['fa_fa_icon']['value']}}"></i> </span>
                            </div>

                            <div class="pull-right">
                                <div class="btn-group pull-right">
                                    <a href="#" data-page_id="{{$page['id']['value']}}"
                                       class="btn btn-sm btn-success btn-flat show-tooltip edit-page-js"
                                       data-original-title="Edit"><i class="fa fa-pencil"></i></a>
                                    <a href="#"
                                       class="btn btn-sm btn-danger btan-flat show-tooltip del-data"
                                       data-original-title="Delete">
                                        <i class="fa fa-trash-o"></i></a>
                                </div>
                            </div>
                        </div>
                        <div id="collapse_{{$page['id']['value']}}" class="panel-collapse collapse"
                             aria-expanded="true">
                            <div class="box-body">
                                Настройки страницы {{$page['title']['value']}}
                            </div>
                        </div>
                    </div>
                </div>

            @endforeach

        </div>
    </div>

    <!-- /.col -->
    <!-- /.col -->
</div>
<!-- /.row -->
<!-- END ACCORDION & CAROUSEL-->
<script>
    $(function () {
        $('.edit-page-js').on('click', function () {
            var page_id = $(this).data('page_id');
            window.location = '/admin/page_constructor/' + page_id + '/lg';

            return false;
        })
    })
</script>