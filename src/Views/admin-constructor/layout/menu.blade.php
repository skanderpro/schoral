 <!-- END Sidebar -->
@if(Session::has('right')&&Session::get('right')==10)
    <ul class="sidebar-menu">
        <li class="treeview">
            <a href="#">
                <i class="fa fa-gears"></i>
                <span>Admin Pages</span>
                <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
                @foreach($admin_pages as $number_page=>$page)
                    @if($page->adm_visible==1)
                        <li>
                            <a href="/{{'admin/'.$page->name}}" class="my-href-js" data-serg="0" >
                                <i class="fa {{$page->fa_fa_icon}}"></i>
                                <span class="text-uppercase">{{$page->title}}</span>
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </li>
        <li class="treeview">
            <a href="#">
                <i class="fa fa-legal"></i>
                <span>Constructor Pages</span>
                <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
                @foreach($admin_pages as $number_page=>$page)
                    @if($page->constructor_visible==1)
                        <li>
                            <a href="/{{'admin/page_constructor/'.$page->id.'/lg'}}" class="my-href-js" data-serg="0"
                               style="padding-left: 0px !important;">
                                <i class="fa {{$page->fa_fa_icon}}"></i>
                                <span class="text-uppercase">{{$page->title}}</span>
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </li>
        <li class="treeview">
            <a href="#">
                <i class="fa fa-suitcase"></i>
                <span>Client Pages(admin)</span>
                <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
                @foreach($admin_pages as $number_page=>$page)
                    @if($page->client_visible==1&&isset($pages_rights[$page->id][1]))
                        <li>
                            <a href="/{{'admin/page/'.$page->id.'/lg'}}" class="my-href-js" data-serg="0" >
                                <i class="fa {{$page->fa_fa_icon}}"></i>
                                <span class="text-uppercase">{{$page->title}}</span>
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </li>
        <li class="treeview">
            <a href="#">
                <i class="fa fa-suitcase"></i>
                <span>Client Pages(manager)</span>
                <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
                @foreach($admin_pages as $number_page=>$page)
                    @if($page->client_visible==1&&isset($pages_rights[$page->id][2]))
                        <li>
                            <a href="{{route('admin.page_client.load',['page_id'=>$page->id,'current_view'=>'lg'])}}" class="my-href-js"   data-serg="0"  style="padding-left: 0px !important;">
                                <i class="fa {{$page->fa_fa_icon}}"></i>
                                <span class="text-uppercase">{{$page->title}}</span>
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </li>
    </ul>

@elseif(Session::has('right')&&Session::get('right')>0&&Session::get('right')<5)
    <ul class="sidebar-menu">
        @foreach($admin_pages as $number_page=>$page)
            @if($page->client_visible==1&&isset($pages_rights[$page->id][Session::get('right')])&&$pages_rights[$page->id][Session::get('right')]==true)
                <li>
                    <a href="/{{'admin/page/'.$page->id.'/lg'}}" class="my-href-js" data-serg="0">
                        <i class="fa {{$page->fa_fa_icon}}"></i>
                        <span class="text-uppercase">{{$page->title}}</span>
                    </a>
                </li>
            @endif
        @endforeach
    </ul>
@endif
<script>
    $(function () {
        var url = window.location.pathname;
        console.log(url);
        $(".my-href-js").each(function(){
            if(url==$(this).attr("href")){
                $(this).closest("ul").attr("style",'display:bloc !important ').addClass("menu-open").closest("li").addClass("active");
               $(this).addClass("active");
               $(this).attr("style",'color: #3c8dbc');
            }
            console.log($(this).data('serg'));
            $(this).attr('data-serg',1);
            $(this).addClass('serg-dima');
        })
    })
</script>