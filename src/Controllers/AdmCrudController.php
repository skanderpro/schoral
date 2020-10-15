<?php

namespace Qubants\Scholar\Controllers;

use Illuminate\Http\Request;
use Qubants\Scholar\Models\AdmBlocksSettingsModel;
use Qubants\Scholar\Models\AdmCrudModel;
use Qubants\Scholar\Models\AdmBlockConstructor;
use Qubants\Scholar\Models\AdmConstructor;
use Qubants\Scholar\Models\AdmPagesSettingsModel;
use Qubants\Scholar\Models\DbColumnsSettings;
use Qubants\Scholar\Models\DbTablesSettings;
use DB;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Input;


class AdmCrudController extends BaseController
{
	//folders:
	public $folder_layouts = 'admin-constructor';
	public $folder_main_layout = 'layout';
	public $folder_pages = 'pages';
	public $folder_blocks = 'blocks';
	public $folder_blocks_chips = 'block_chips';
	public $folder_panels = 'panels';
	public $folder_modals = 'modals';

	//layouts:
	public $layout_main = 'layout';

	//layouts_pages:
	public $layout_table_settings_page = 'db_tables_page';
	public $layout_adm_pages_page = 'adm_pages_page';
	public $layout_page_constructor_page = 'page_constructor';

	public $layout_standart_page = 'standart_page';
	public $layout_standart_edit_page = 'standart_edit_page';
	public $layout_icons_page = 'icons_page';

	public $adminConstructor;
	public $modelCrud;
	public $modelPages;
	public $modelTables;
	public $modelBlocks;

	public function __construct() {
		$this->modelTables      = new DbTablesSettings();
		$this->modelBlocks      = new AdmBlocksSettingsModel();
		$this->adminConstructor = new AdmConstructor();
		$this->modelCrud        = new AdmCrudModel();
		$this->modelPages       = new AdmPagesSettingsModel();
	}

	public function saveUnit() {

		$key_data      = Input::get('cell');
		$key_data_json = Input::get('json_cell');


		if (is_array($key_data)) {
			$this->modelCrud->updateCellByKeyData($key_data, 'none');
		}
		if (is_array($key_data_json)) {
			$this->modelCrud->updateCellByKeyData($key_data_json, 'json');
		}
		return 1;
	}

	public function saveImage(Request $request) {
		$key_data      = Input::get('cell');
		$key_data_json = Input::get('json_cell');

		$modelColumns = new DbColumnsSettings();

		foreach ($key_data as $row_id => &$row) {
			foreach ($row as $column_id => $value) {
				$column_settings = $modelColumns->getColumnsTypeSettingsByColumnId($column_id, 'image_folder');
				if (empty($column_settings)) continue;

				$folder = $column_settings->column_type_value;

				if ($request->isMethod('post')) {
					if ($request->hasFile('file')) {
						$file = $request->file('file');
						$file->move(public_path() . '/' . $folder, $file->getClientOriginalName() );
						$row[$column_id] = '/' . $folder .'/'.$file->getClientOriginalName();


						$table_name  = $this->modelTables->getTableNameByColumnId($column_id);
						$column_name = $modelColumns->getColumnNameByColumnId($column_id);

						$item_date = DB::table($table_name)->where('id', $row_id)->first();
						if(is_file(public_path() . $item_date->{$column_name})){
//							unlink(public_path() . $item_date->{$column_name});
						}
					}
				}
			}
		}

		if (is_array($key_data)) {
			$this->modelCrud->updateCellByKeyData($key_data, 'none');
		}
		if (is_array($key_data_json)) {
			$this->modelCrud->updateCellByKeyData($key_data_json, 'json');
		}
		return 1;
	}

	public function saveFilters() {
		$key_row = Input::get('add_delete_row');
		$this->modelCrud->deleteCreateFilters($key_row);
		return 1;
	}

	public function saveTypesSettings() {
		$key_row = Input::get('add_delete_row');
		$this->modelCrud->deleteCreateTypesSettings($key_row);
		return 1;
	}

	public function saveRights() {
		$rights = Input::get('row');
		$this->modelCrud->deleteCreateRights($rights);
		return 1;
	}

	public function deleteCreateUnit() {
		$key_data['new_row'] = Input::get('new_row');
		$key_data['cell']    = Input::get('cell');
		if ($key_data['new_row'] != null) {
			$row_key             = $this->modelCrud->createRowByColumnId($key_data['new_row']);
			$block_id            = $row_key['row_id'];
			$block_configuration = $this->modelBlocks->getBlockConfigurationById($block_id);
			switch ($block_configuration['block_type']['value']) {
				case 'block_table_b':
					$tables = $this->adminConstructor->loadInformationSchema();
					return view($this->getUrlLayout('modal', 'm_wrap_block'), ['tables_global' => $tables,
																			   'block'         => $block_configuration]);
					break;
			}
		}
	}

	public function deleteUnit() {

		$key_data['block']  = Input::get('block');
		$key_data['row']    = Input::get('row');
		$key_data['table']  = Input::get('table');
		$key_data['column'] = Input::get('column');
		$key_data['page']   = Input::get('page');
		print_r($this->modelCrud->deleteUnit($key_data));

	}


	public function createLaterUnit() {
		$key_data = Input::get('new');
		return $this->modelCrud->createLaterUnit($key_data);
	}

	public function saveCreateLaterUnit() {
		$key_data['create']      = Input::get('new');
		$key_data_json['create'] = Input::get('json_new');
		$key_data['save']        = Input::get('cell');
		$key_data_json['save']   = Input::get('json_cell');


		$this->modelCrud->updateCellByKeyData($key_data['save']);
		$this->modelCrud->updateCellByKeyData($key_data_json['save'], 'json');
		$this->modelCrud->createLaterUnit($key_data_json['create'], 'json');

		return $this->modelCrud->createLaterUnit($key_data['create']);

	}


	public function getPage($page_id) {
		$page = $this->modelPages->getPageConfigurationById($page_id);
		print_r($page);
		exit();
	}


	public function getUrlLayout($layout_type, $layout_name) {
		return $this->getUrlFolderLayout($layout_type) . '.' . $layout_name;
	}

	public function getUrlFolderLayout($layout_type) {
		$FOLDER = '';
		switch ($layout_type) {
			case 'panel':
				$FOLDER = $this->folder_panels;
				break;
			case 'page':
				$FOLDER = $this->folder_pages;
				break;
			case 'block':
				$FOLDER = $this->folder_blocks;
				break;
			case 'modal':
				$FOLDER = $this->folder_modals;
				break;
			case 'layout':
				$FOLDER = $this->folder_main_layout;
				break;
		}
		return $this->folder_layouts . "." . $FOLDER;
	}


	public function getUrlChips($layout_type, $chips_name) {
		return $this->getUrlFolderLayout($layout_type) . '.' . $this->folder_blocks_chips . '.' . $chips_name;
	}


}