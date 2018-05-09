<?php

/**
 * Admin Part
 */

Route::group(['middleware' => 'web'], function () {
	Route::get('/admin/exit/full', 'Qubants\Scholar\Controllers\AdmConstructorController@exitPage');
	Route::get('/admin/authorization/check', 'Qubants\Scholar\Controllers\AdmScholarController@checkLoginPassword')->name('auth');
	Route::group(['middleware' => \Qubants\Scholar\Middleware\IsAdmin::class], function () {
//AdmCrudController:
		Route::post('/admin/save_unit', 'Qubants\Scholar\Controllers\AdmCrudController@saveUnit');
		Route::any('/admin/save_image', 'Qubants\Scholar\Controllers\AdmCrudController@saveImage');
		Route::get('/admin/save_filters', 'Qubants\Scholar\Controllers\AdmCrudController@saveFilters');
		Route::get('/admin/save_settings', 'Qubants\Scholar\Controllers\AdmCrudController@saveTypesSettings');
		Route::get('/admin/delete_create_unit', 'Qubants\Scholar\Controllers\AdmCrudController@deleteCreateUnit');
		Route::get('/admin/delete_unit', 'Qubants\Scholar\Controllers\AdmCrudController@deleteUnit');
		Route::get('/admin/create_later_unit', 'Qubants\Scholar\Controllers\AdmCrudController@createLaterUnit');
		Route::any('/admin/save_create_later_unit', 'Qubants\Scholar\Controllers\AdmCrudController@saveCreateLaterUnit');


		Route::get('/admin/load_modal_edit_row/{table_id?}/{row_id?}', 'Qubants\Scholar\Controllers\AdmConstructorController@loadModalEditRow');
		Route::get('/admin/load_modal_chips/{chips_name?}', 'Qubants\Scholar\Controllers\AdmConstructorController@loadModalChips');

		Route::get('/admin/page/{page_id?}/{current_view?}', 'Qubants\Scholar\Controllers\AdmScholarController@loadClientPage')->name('admin.page_client.load');

// Admin-Constructor
		Route::get('/admin/save_rights', 'Qubants\Scholar\Controllers\AdmCrudController@saveRights');
		Route::get('/admin/data_table', 'Qubants\Scholar\Controllers\AdmScholarController@dataTable');


		Route::get('/admin', 'Qubants\Scholar\Controllers\AdmScholarController@loadClientPage')->name('home');
		Route::get('/admin/{page?}', 'Qubants\Scholar\Controllers\AdmConstructorController@loadPage');

		Route::get('/admin/tables/settings/update', 'Qubants\Scholar\Controllers\AdmConstructorController@updateTablesSettingsGlobal');
		Route::get('/admin/page_constructor/{page_name?}/{page_view?}', 'Qubants\Scholar\Controllers\AdmConstructorController@loadPageConstructor');
		Route::get('/admin/load_modal/{modal_name?}', 'Qubants\Scholar\Controllers\AdmConstructorController@loadModal');

		Route::get('/admin/load_block/{block_name?}', 'Qubants\Scholar\Controllers\AdmConstructorController@loadBlockSettings');
		Route::get('/admin/load_block_chips/{block_chips_name?}', 'Qubants\Scholar\Controllers\AdmConstructorController@loadColumnsSettings');
		Route::post('/admin/save_block_settings/{page_id?}/{current_view?}/{block_id?}', 'Qubants\Scholar\Controllers\AdmConstructorController@saveBlockSettings');
		Route::get('/admin/edit_block_settings/{block_id?}', 'Qubants\Scholar\Controllers\AdmConstructorController@editBlockSettings');
		Route::get('/admin/save_blocks_position/{page_id?}/{current_view?}', 'Qubants\Scholar\Controllers\AdmConstructorController@saveBlockPosition');
		Route::get('/admin/delete_block/{page_id?}/{current_view?}/{block_id?}', 'Qubants\Scholar\Controllers\AdmConstructorController@deleteBlock');


		Route::get('/admin/test/{parameter?}', 'Qubants\Scholar\Controllers\AdmCrudController@getPage');

		Route::get('/admin/updateProduct/{table_name?}/{product_id?}/{data?}/{page_name?}/{column?}', 'Qubants\Scholar\Controllers\AdmConstructorController@updateProduct');
		Route::get('/admin/createRow/{table_name?}/{product_id?}/{data?}/{page_name?}', 'Qubants\Scholar\Controllers\AdmConstructorController@createRow');
		Route::get('/updateStructure/{table_name?}/{data?}/{column?}/{id?}', 'Qubants\Scholar\Controllers\AdmConstructorController@updateStructure');
		Route::get('/deleteStructure/{table_name}/{row_id}', 'Qubants\Scholar\Controllers\AdmConstructorController@deleteStructure');
		Route::get('/createCategoryStructure/{table_name}/{pid}', 'Qubants\Scholar\Controllers\AdmConstructorController@createCategoryStructure');
		Route::get('/openCategoryStructure/{page_name}/{pid}', 'Qubants\Scholar\Controllers\AdmConstructorController@openCategoryStructure');

		Route::get('/admin/action/updateTablesSettings', 'Qubants\Scholar\Controllers\AdmConstructorController@updateTablesSettings');


// Custom Blocks

		Route::post('/admin/send_email', 'Qubants\Scholar\Controllers\AdmBlockController@sendEmails')->name('custom.send_email');

	});
});