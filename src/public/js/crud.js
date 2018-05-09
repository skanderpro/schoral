/**
 * Created by admin on 02.12.2016.
 */
$(function () {
    $('body').on('change', '.unit-save-js', function () {
        var elem = $(this);
        var value = 0;
        if ($(this).attr('type') == 'checkbox') {
            if ($(this).prop('checked')) {
                value = 1;
            }
        } else {
            value = $(this).val();
        }

        var unit = {};
        unit[$(this).attr('name')] = value;
        $.ajax({
            type: "POST",
            url: '/admin/save_unit',
            data: unit,
            success: function (msg) {
                var table_data = elem.closest('.block-wrap-js').find('.table-settings-js').DataTable();
                table_data.draw(true);
            }
        })
        return false;
    })

	$('body').on('click', '.image-save-js', function () {
		var elem = $(this);
		var formData = new FormData();

		var hiddenFile = $("<input type=\"file\" name=\"file\" id=\"file1\" style=\"position:absolute;left:-9999px\" />");
		$('body').append(hiddenFile);
		hiddenFile.trigger('click');

		hiddenFile.change(function (e) {
			formData.append('file', hiddenFile.get(0).files[0]);
			formData.append(elem.data('name'), '');
			$.ajax({
				url: '/admin/save_image',
				data: formData,
				type: 'POST',
				contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
				processData: false,
				dataType: 'json',
				success: function (data) {
					var table_data = elem.closest('.block-wrap-js').find('.table-settings-js').DataTable();
					table_data.draw(true);
				}
			});
			return false;
		});
		return false;
	})

    $('body').on('change', '.unit-delete-create-js', function () {
        var value = 0;
        if ($(this).attr('type') == 'checkbox') {
            if ($(this).prop('checked')) {
                value = 1;
            }
        } else {
            value = $(this).val();
        }

        var unit = {};
        unit[$(this).attr('name')] = value;
        var page_id = $(this).closest('form').find('input[name="new_row[column_name][page_id]"]').val();
        var unit_name = $(this).closest('form').find('input[name="new_row[column_name][page_id]"]').attr('name');
        unit[unit_name] = page_id;
        $.ajax({
            type: "GET",
            url: '/admin/delete_create_unit',
            data: unit,
            success: function (msg) {
                window.location.reload();
            }
        })
        return false;


    })

    $('body').on('click', '.unit-delete-js', function () {
        var unit = $(this).attr('name');
        var message = confirm("Do you want delete" + unit + '?');
        if (message == true) {
            $.ajax({
                type: "GET",
                url: '/admin/delete_unit',
                data: unit,
                success: function (msg) {
                    console.log(msg);
                    window.location.reload();

                }
            })
            return false;
        } else {
            return false;
        }
    })


    $('body').on('click', '.unit-create-later-js', function () {
        var unit_settings = $(this).closest('form').serialize();
        $.ajax({
            type: "GET",
            url: '/admin/create_later_unit',
            data: unit_settings,
            success: function (msg) {
                console.log(msg);
                if (msg == true) {
                    window.location.reload();
                }
            }
        })
        return false;
    })
    $('body').on('click', '.unit-save-create-later-js', function () {
        var unit_settings = $(this).closest('form').serialize();
        $(this).closest('form').find('input[type=checkbox]').each(function () {
            if (!$(this).prop('checked')) {
                unit_settings = unit_settings + '&' + $(this).attr('name') + '=0';
            }
        });
        $(this).closest('form').find('select').each(function () {
            if ($(this).val()==undefined) {
                unit_settings = unit_settings + '&' + $(this).attr('name') + '=';
            }
        });
        $.ajax({
            type: "POST",
            url: '/admin/save_create_later_unit',
            data: unit_settings,
            success: function (msg) {

                if (msg == true) {
                    window.location.reload();
                }
            }
        })
        return false;
    })

    $('body').on('click', '.unit-save-later-js', function () {
        var unit_settings = $(this).closest('form').serialize();
        $(this).closest('form').find('input[type=checkbox]').each(function () {
            if (!$(this).prop('checked')) {
                unit_settings = unit_settings + '&' + $(this).attr('name') + '=0';
            }
        });
        $(this).closest('form').find('input[type=radio]').each(function () {
            if (!$(this).prop('checked')) {
                unit_settings = unit_settings + '&' + $(this).attr('name') + '=0';
            }
        });


        $.ajax({
            type: "POST",
            url: '/admin/save_unit',
            data: unit_settings,
            success: function (msg) {

                window.location.reload();

            }
        })
        return false;
    })


    $('body').on('change', '.change-rights-js', function () {
        var value = $(this).val();
        var unit = {};
        unit[$(this).attr('name')] = value;
        $.ajax({
            type: "GET",
            url: '/admin/save_rights',
            data: unit,
            success: function (msg) {

            }
        })
        return false;
    })
    $('body').on('change', '.change-filterType-js', function () {
        var column_id = $(this).data('column_id');
        var key = 'add_delete_row[' + column_id + ']';
        var value = $(this).val();
        var unit = {};
        unit[$(this).attr('name')] = value;
        unit[key] = value;
        console.log(unit);
        $.ajax({
            type: "GET",
            url: '/admin/save_filters',
            data: unit,
            success: function (msg) {


            }
        })
        return false;
    })

    $('body').on('change', '.change-displayType-js', function () {
        var column_id = $(this).data('column_id');
        var key = 'add_delete_row[' + column_id + ']';
        var value = $(this).val();
        var unit = {};
        unit[$(this).attr('name')] = value;
        unit[key] = value;
        console.log(unit);
        $.ajax({
            type: "GET",
            url: ' /admin/save_settings',
            data: unit,
            success: function (msg) {


            }
        })
        return false;
    })

    $('body').on('change', '.change-selectMultiple-js', function () {

        var value = $(this).val();
        var unit = {};
        unit[$(this).attr('name')] = value;
        $.ajax({
            type: "POST",
            url: ' /admin/save_unit',
            data: unit,
            success: function (msg) {
            }
        })
        return false;
    })

    $('body').on('hide.bs.select', '.selectpicker.client-js', function (e) {
        var table_data = $(this).closest('.block-wrap-js').find('.table-settings-js').DataTable();
        table_data.draw(true);
    });

    $('body').on('change', '.filter-client-session-js', function () {
        var table_data = $(this).closest('.block-wrap-js').find('.table-settings-js').DataTable();
        table_data.draw(true);
    })


})