<?php

namespace Qubants\Scholar\Models;

use Illuminate\Database\Eloquent\Model;

class AdmCustomBlocksModel extends Model
{

	public $table_users_settings;

	public $modelConfig;
	public $modelTables;
	public $modelColumns;
	public $modelCrud;

	public function __construct() {
		$this->modelConfig = new AdmConfig();

		$this->modelCrud    = new AdmCrudModel();
		$this->modelTables  = new DbTablesSettings();
		$this->modelColumns = new DbColumnsSettings();
	}

	public function getContentCustomBlock($block_id) {
		$custom_blocks = config('courier.custom_blocks');
		if (!empty($custom_blocks[$block_id])) {
			$action     = $custom_blocks[$block_id];
			$class      = explode('@', $action)[0];
			$method     = explode('@', $action)[1];
			$app        = app();
			$controller = $app->make($class);
			return $controller->callAction($method);
		} else {
			return 'Custom block not found:' . $block_id;
		}
	}
}


