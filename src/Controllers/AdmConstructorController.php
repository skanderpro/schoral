<?php

namespace Qubants\Scholar\Controllers;

use Qubants\Scholar\Models\AdmBlocksSettingsModel;
use Qubants\Scholar\Models\AdmConfig;
use Qubants\Scholar\Models\AdmCrudModel;
use Qubants\Scholar\Models\AdmBlockConstructor;
use Qubants\Scholar\Models\AdmConstructor;
use Qubants\Scholar\Models\AdmPagesSettingsModel;
use Qubants\Scholar\Models\AdmSelectModel;
use Qubants\Scholar\Models\AdmUsersModel;
use Qubants\Scholar\Models\DbColumnsSettings;
use Qubants\Scholar\Models\DbTablesSettings;
use DB;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Input;
use Session;


class AdmConstructorController extends BaseController
{
	//folders:
	public $folder_layouts = 'scholar::admin-constructor';
	public $folder_main_layout = 'layout';
	public $folder_start_layout = 'layout-start-page';
	public $folder_pages = 'pages';
	public $folder_blocks = 'blocks';
	public $folder_blocks_chips = 'block_chips';
	public $folder_panels = 'panels';
	public $folder_modals = 'modals';

	//layouts:
	public $layout_main = 'layout';
	public $layout_start_page = 'start-layout';

	//layouts_pages:
	public $layout_table_settings_page = 'db_tables_page';
	public $layout_adm_pages_page = 'adm_pages_page';
	public $layout_page_constructor_page = 'page_constructor';
	public $layout_authorization = 'authorization';

	public $layout_standart_page = 'standart_page';
	public $layout_standart_edit_page = 'standart_edit_page';
	public $layout_icons_page = 'icons_page';


	//models:
	public $adminConstructor;
	public $Crud;
	public $modelColumns;
	public $modelSelect;
	public $modelConfig;
	public $modelUsers;
	public $modelPages;

	public function __construct() {
		$this->modelConfig = new AdmConfig();

		$this->modelUsers       = new AdmUsersModel();
		$this->modelSelect      = new AdmSelectModel();
		$this->Crud             = new AdmCrudModel();
		$this->adminConstructor = new AdmConstructor();
		$this->modelColumns     = new DbColumnsSettings();
		$this->modelPages       = new AdmPagesSettingsModel();
	}

	public function updateTablesSettingsGlobal() {
		$this->adminConstructor->upgradeDbTablesSettings();
	}


	public function loadPage($page_name = false) {

		if (Session::has('right') && Session::get('right') > 0) {
			if ($page_name == false) {
				$page_name = $this->modelConfig->page_tables_settings;
			}
			$admin_pages  = DB::table($this->modelConfig->table_pages_admin)->orderBy('weight')->get();
			$pages_rights = $this->modelPages->getAllPagesRights();
			if (Session::get('right') == 10) {
				$content = $this->getContent($page_name);
			} else {
				$content = 'Стартовая страница';
			}
			return view($this->getUrlLayout('layout', $this->layout_main), ['content'      => $content, 'admin_pages' => $admin_pages,
																			'pages_rights' => $pages_rights]);
		} else {
			return view($this->getUrlLayout('layout', $this->layout_start_page), []);
		}

	}

	public function checkClient() {
		\Cookie::queue('scholar_a', time(), 60 * 24 * 30 * 6);
		return redirect()->route('auth');
	}

	public function exitPage() {
		if (Session::has('right')) {
			Session::forget('right');
		}
		Session::forget('login');
		return redirect()->route('auth');
	}


	public function getContent($page_name) {
		$modelTables = new DbTablesSettings();
		$modelPages  = new AdmPagesSettingsModel();
		$modelSelect = new AdmSelectModel();
		$content     = '';
		switch ($page_name) {
			case $this->adminConstructor->page_tables_settings:
				$tables_id       = $modelTables->getTablesGlobalId();
				$tables_settings = $modelTables->getTablesKeysAndSettingsByTablesId($tables_id, false);
				$admin_pages     = $modelPages->getAllPagesSettings(); //Настройки страниц.
				$content         = view($this->getUrlLayout('page', $this->layout_table_settings_page), ['pages'           => $admin_pages,
																										 'tables_settings' => $tables_settings, 'page_name' => 'db_tables_page']);
				break;
			case $this->adminConstructor->page_adm_pages:

				$pages_settings = $modelPages->getAllPagesKeysSettings();
				$select_rights  = $modelSelect->getItemsByGroupName('rights');
				$content        = view($this->getUrlLayout('page', $this->layout_adm_pages_page),
					['page_name'     => $page_name, 'pages_settings' => $pages_settings,
					 'select_rights' => $select_rights]);
				break;
			case 'icons':
				$content = view($this->getUrlLayout('panel', 'p_icons'), []);
				break;
		}
		return $content;
	}

	public function loadPageConstructor($page_id, $current_view = 'xs') {
		$admPagesSettingsModel = new AdmPagesSettingsModel();
		$modelSelect           = new AdmSelectModel();
		$admin_pages           = $admPagesSettingsModel->getAllPagesSettings();
		$page_configurations   = '';

		foreach ($admin_pages as $page) {
			if ($page->id == $page_id) {
				$page_configurations = $admPagesSettingsModel->getPageConfigurationById($page_id);
			}
		}

		$selects = $this->modelSelect->getAllSelectsItems();

		$content      = view($this->getUrlLayout('page', $this->layout_page_constructor_page), ['selects'     => $selects,
																								'admin_pages' => $admin_pages, 'current_view' => $current_view, 'page_configurations' => $page_configurations
		]);
		$pages_rights = $this->modelPages->getAllPagesRights();
		return view($this->getUrlLayout('layout', $this->layout_main), ['content'      => $content, 'admin_pages' => $admin_pages,
																		'pages_rights' => $pages_rights]);

	}

	public function getContentCustomBlock($block_id) {
		switch ($block_id) {
			case '68':
				return "Контент блока" . $block_id;
				break;
		}
	}


	public function loadModal($modal_name) {

		$block_config = $this->modelColumns->getUnitConfigBlock();
		$page_id      = Input::get('page_id');
		$page_view    = Input::get('page_view');
		switch ($modal_name) {
			case 'm_wrap_block':
				return view($this->getUrlLayout('modal', $modal_name), [
					'block_new' => $block_config, 'page_id' => $page_id, 'page_view' => $page_view]);
				break;
			case'm_icons':
				return view($this->getUrlLayout('modal', $modal_name), [
					'block_new' => $block_config, 'page_id' => $page_id, 'page_view' => $page_view]);

		}
	}

	public function loadModalChips($chips_name) {
		switch ($chips_name) {
			case 'bc_join_table':
				$table_number           = Input::get('count_join_tables') + 1;
				$modelTables            = new DbTablesSettings();
				$tables_global_settings = $modelTables->getGlobalTablesSettings();

				$selects = $this->modelSelect->getAllSelectsItems();
				return view($this->getUrlChips('block', $chips_name), [
					'tables_global_settings' => $tables_global_settings, 'selects' => $selects, 'table_number' => $table_number]);
				break;
			case 'bc_table_column_join_main':
				$selected_column = Input::get('selected_column');
				$table_name      = Input::get('main_join_table_name');

				if ($table_name != null) {

					$columns_join = $this->modelColumns->getAllColumnsInTable($table_name);

					return view($this->getUrlChips('block', $chips_name), [
						'columns_join' => $columns_join, 'selected_column' => $selected_column]);

				}
				break;

		}

	}

	public function loadModalEditRow($table_id, $row_id = 'new', DbTablesSettings $modelTables, DbColumnsSettings $modelColumns) {

		if ($row_id == 'id') {
			$row_key     = Input::get('row');
			$table_id    = key($row_key);
			$row_id      = key($row_key[$table_id]);
			$row_content = $modelTables->getRowContentKeys($table_id, $row_id);
		} else {
			$row_key     = null;
			$row_content = null;
		}
		$selects               = $this->modelSelect->getAllSelectsItems();
		$table_settings        = $modelTables->getTablesKeysAndSettingsByTablesId($table_id);
		$table_global_settings = $modelTables->getGlobalTableKeysAndSettingsByTableId($table_id);
		return view($this->getUrlLayout('modal', 'm_row_edit'), ['table_settings'        => $table_settings,
																 'table_global_settings' => $table_global_settings, 'row_id' => $row_id, 'row_key' => $row_key,
																 'row_content'           => $row_content, 'selects' => $selects]);
	}

	public function editBlockSettings($block_id, AdmBlocksSettingsModel $modelAdmBlock, DbTablesSettings $dbTablesSettings) {
		$block_configuration = $modelAdmBlock->getBlockConfigurationById($block_id);
		switch ($block_configuration['block_type']['value']) {
			case 'block_table_b':
				$modelTables             = new DbTablesSettings();
				$tables_global_settings  = $modelTables->getGlobalTablesSettings();
				$columns_global_settings = $modelTables->getGlobalTablesColumns();

				$tables  = $this->adminConstructor->loadInformationSchema();
				$selects = $this->modelSelect->getAllSelectsItems();

				return view($this->getUrlLayout('modal', 'm_wrap_block'), ['tables_global' => $tables, 'tables_global_settings' => $tables_global_settings,
																		   'block'         => $block_configuration, 'selects' => $selects, 'columns_global_settings' => $columns_global_settings]);
				break;
			case 'block_custom_b':
				return view($this->getUrlLayout('modal', 'm_wrap_block'), ['block' => $block_configuration]);
				break;
		}

	}

	public function loadBlockSettings($block_type) {
		$data = '';
		switch ($block_type) {
			case 'block_table_b':
				$modelTables             = new DbTablesSettings();
				$tables_global_settings  = $modelTables->getGlobalTablesSettings();
				$columns_global_settings = $modelTables->getGlobalTablesColumns();
				$tables                  = $this->adminConstructor->loadInformationSchema();
				$selects                 = $this->modelSelect->getAllSelectsItems();
				return view($this->getUrlLayout('block', $block_type), ['tables_global_settings' => $tables_global_settings,
																		'tables_global'          => $tables, 'selects' => $selects, 'columns_global_settings' => $columns_global_settings]);

				break;
			case 'block_custom_b':
				return view($this->getUrlLayout('block', $block_type), []);
				break;
			default:

		}

	}


	public function loadColumnsSettings($chips_name, DbTablesSettings $dbTablesSettings, DbColumnsSettings $dbColumnsSettings) {

		$data = '';
		switch ($chips_name) {
			case 'bc_columns_join_settings':
			case 'bc_columns_settings':
				$selects                 = $this->modelSelect->getAllSelectsItems();
				$table_name              = Input::get('table_name');
				$table_number            = Input::get('table_number');
				$table_id                = $dbTablesSettings->getTableId($table_name);
				$table                   = $dbTablesSettings->getTablesKeysAndSettingsByTablesId($table_id);
				$tables_global_settings  = $dbTablesSettings->getGlobalTablesSettings();
				$columns_global_settings = $dbTablesSettings->getGlobalTablesColumns();

				return view($this->getUrlChips('block', 'bc_columns_settings'), ['table_new'              => $table, 'selects' => $selects, 'table_number' => $table_number,
																				 'tables_global_settings' => $tables_global_settings, 'columns_global_settings' => $columns_global_settings]);
				break;
		}
	}


	public function updateTablesSettings() {
		$table_name  = Input::get('table_name');
		$column_name = Input::get('column_name');
		$param       = Input::get('param');
		$value       = Input::get('value');
		echo DB::table('db_tables_settings')->where([
			['table_name', '=', $table_name],
			['name', '=', $column_name]
		])->update([$param => $value]);
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

	public function saveBlockSettings($page_id, $current_view, $block_id, AdmBlockConstructor $admBlockConstructor) {
		$tables_settings                  = Input::get('table_settings');
		$tables_settings['table_purpose'] = 'main';
		$columns_settings                 = Input::get('columns_settings');
		$block_settings                   = Input::get('block_settings');
		$block_settings['parent_id']      = $page_id;
		$block_settings['parent']         = 'page';
		$block_settings['page_view']      = $current_view;
		$block_settings['page_id']        = $page_id;
		$block_settings['block_x']        = 2;
		$block_settings['block_y']        = 100;
		$block_settings['block_height']   = 4;
		$block_settings['block_width']    = 5;

		if ($block_id == 'new') {
			$admBlockConstructor->createBlock($block_settings, $tables_settings, $columns_settings);
		} else {
			//$admBlockConstructor->updateBlock($block_id, $block_type, $block_title, $block_configuration, $content_source);
		}


	}


	public function saveBlockPosition($page_id, $current_view, AdmBlockConstructor $admBlockConstructor) {
		$blocks_position = Input::get('blocks');
		$blocks          = json_decode($blocks_position);
		if (is_array($blocks)) {
			$admBlockConstructor->saveBlockPosition($page_id, $current_view, $blocks);
		} else {
			return '';
		}
	}

	public function deleteBlock($page_id, $current_view, $block_id, AdmBlockConstructor $admBlockConstructor) {
		$admBlockConstructor->deleteBlock($page_id, $current_view, $block_id);
		return redirect(route('admin.page_constructor.load', ['page_name' => $page_id, 'page_view' => $current_view]));
	}


}