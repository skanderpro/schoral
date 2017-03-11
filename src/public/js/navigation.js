$(function () {
    $('.change-bd').change(function () {
        var elem=$(this);
        var value = 0;
        if ($(this).prop('checked') == true) {
            value = 1;
        }else if($(this).val()!=""){
            value=$(this).val();
        }
        var table=elem.parent().parent().parent().parent();
        var table_name=table.data('table_name');
        var product_id = $(this).parent().parent().data('id');
        var column = $(this).parent().parent().children().index($(this).parent()) + 1;
        var db_title = table.find('tr th:nth-child(' + column + ')').data('db_title');
        var data={};
        data[db_title]=value;
        data=JSON.stringify(data);
        var page_name=$('#page_name').data('page_name');
        var url = '/admin/updateProduct/'+table_name+'/'+ product_id + '/'+ data+'/'+page_name+'/'+0;
        $.ajax({
            type: "GET",
            url: url,
            data: {table: table_name,page_name:page_name},
            success: function (msg) {
                if (msg != true) {
                    alert('Oшибка! Данные не изменены');
                    window.location.href='/admin';
                }


            }
        })


    });

    $('.change-db-tables-settings').change(function(){
        var elem=$(this);
        var value = 0;
        if ($(this).prop('checked') == true) {
            value = 1;
        }else if($(this).val()!=""){
            value=$(this).val();
        }
        var table_name=elem.closest('.table-settings-js').data('table_name');
        var column_name=elem.closest('.column-settings-js').data('column_name');
        var param=elem.attr('name');
        console.log(table_name+" "+column_name+" "+param+" "+value);
        $.ajax({
            type: "GET",
            url: '/admin/action/updateTablesSettings',
            data: {table_name:table_name,column_name:column_name,param:param,value:value},
            success: function (msg) {
                if(msg!=1){

                    alert("Ошибка! Данные не сохранены");
                }

            }
        })

    });


    $('body').on('click', '.edit-filter-js', function () {
        var table_wrap = $(this).closest('.block-wrap-js');
        table_wrap.find('.filter-js').slideToggle(500);
        return false;
    })
})




