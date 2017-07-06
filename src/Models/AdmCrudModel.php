<?php

namespace Qubants\Scholar\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use DB;

class AdmCrudModel extends Model
{
	//db:
	public $db_name;

	//tables:
	public $table_tables_settings;
	public $table_columns_settings;
	public $table_pages_settings;
	public $table_blocks_settings;

	//pages:
	public $page_tables_settings;
	public $page_adm_pages;

	//models:
	public $modelConfig;
	public $modelTables;
	public $modelColumns;

	public function __construct() {
		$this->modelConfig  = new AdmConfig();
		$this->modelTables  = new DbTablesSettings();
		$this->modelColumns = new DbColumnsSettings();

		//db:
		$this->db_name = $this->modelConfig->db_name;

		//tables:
		$this->table_tables_settings  = $this->modelConfig->table_tables_settings;
		$this->table_columns_settings = $this->modelConfig->table_columns_settings;
		$this->table_pages_settings   = $this->modelConfig->table_pages_admin;
		$this->table_blocks_settings  = $this->modelConfig->table_blocks_settings;

		//pages:
		$this->page_tables_settings = $this->modelConfig->page_tables_settings;
		$this->page_adm_pages       = $this->modelConfig->page_adm_pages;
	}

	//get Super Keys:
	public function getCellKeyByColumnIdAndRowId($column_id, $row_id, $prefix) {
		return $prefix . '[' . $row_id . '][' . $column_id . ']';
	}

	public function getRowKey($table_id, $row_id) {
		$modelTables = new DbTablesSettings();
		//$table_id=$modelTables->getTableContentIdByTableId($table_id);
		return 'row[' . $table_id . '][' . $row_id . ']';
	}

	public function getColumnKey($column_id) {
		return 'column[' . $column_id . ']';
	}

	public function getTableKey($table_id) {
		return 'table[' . $table_id . ']';
	}

	public function getTableSettingKey($column_id, $table_id) {
		return 'table_s[' . $table_id . '][' . $column_id . ']';
	}

	public function getBlockKey($block_id) {
		return 'block[' . $block_id . ']';
	}

	public function getBlockSettingKey($column_id, $block_id) {
		return 'block_s[' . $block_id . '][' . $column_id . ']';
	}

	public function getPageKey($page_id) {
		return 'page[' . $page_id . ']';
	}


	//finish:: get Super Keys:

	//UPDATE  cell
	//
	public function updateCellByKeyData($key_data, $format = 'none') {

		if (is_array($key_data)) {
			foreach ($key_data as $row_id => $columns) {
				foreach ($columns as $column_id => $value) {
					if ($format == 'json') {
						$value = json_encode($value);
					}
					$table_name                                      = $this->modelTables->getTableNameByColumnId($column_id);
					$column_name                                     = $this->modelColumns->getColumnNameByColumnId($column_id);
					$tables_data[$table_name][$row_id][$column_name] = $value;

				}
			}
			foreach ($tables_data as $table_name => $rows) {
				foreach ($rows as $row_id => $row) {
					DB::table($table_name)->where('id', $row_id)->update($row);
				}
			}
			return $tables_data;
		}

	}

	//finish:: Update Cell

	//DELETE
	public function deleteUnit($key_data) {
		$modelBlocks = new AdmBlocksSettingsModel();
		$modelTables = new DbTablesSettings();
		foreach ($key_data as $unit_type => $units) {
			if (is_array($units)) {
				switch ($unit_type) {
					case 'block':

						foreach ($units as $unit_id => $value) {
							$modelBlocks->deleteBlockById($unit_id);
						}
						break;

					case 'table':
						$i = 0;
						foreach ($units as $table_id => $value) {

							$modelTables->deleteTableUnit($table_id);
						}
						break;

					case 'row':
						foreach ($units as $table_id => $row) {

							$row_id   = key($row);
							$table_id = $modelTables->getTableContentIdByTableId($table_id);
							$this->deleteTableRow($table_id, $row_id);

						}

						break;

				}
			}
		}

		return 1;
	}


	public function deleteTableRow($table_id, $row_id) {

		$table_name = $this->modelTables->getTableNameByTableId($table_id);
		return DB::table($table_name)->where('id', $row_id)->delete();
	}
	//FINISH DELETE
	//CREATE UNIT
	public function createLaterUnit($key_data, $format = 'none') {
		$modelBlocks  = new AdmBlocksSettingsModel();
		$modelTables  = new DbTablesSettings();
		$modelColumns = new DbColumnsSettings();
		$modelPages   = new AdmPagesSettingsModel();


		if (isset($key_data['page'])) {

		}

		if (isset($key_data['block']) && $key_data['block']['block_type'] != 'none') {
			$block_new_id = $modelBlocks->createBlockUnit($key_data['block']);
		}

		if (isset($key_data['table'])) {
			foreach ($key_data['table'] as $table_number => $table) {
				if (isset ($table['table_name']) && $table['table_name'] != 'none') {
					if (!isset($block_new_id)) {
						$table['block_id'] = $key_data['table_settings']['block_id'];
					} else {
						$table['block_id'] = $block_new_id;
					}
					$table_id[$table_number] = $modelTables->createTableUnit($table);
				}
			}
		}

		if (isset($key_data['columns']) && isset($table_id)) {
			foreach ($key_data['columns'] as $table_number => $columns) {
				if (isset($table_id[$table_number])) {
					foreach ($columns as $column_name => $column_data) {
						$column_data['column_name'] = $column_name;
						$column_data['table_id']    = $table_id[$table_number];
						$modelColumns->createColumnUnit($column_data);
					}
				}
			}
		}

		if (isset($key_data['column_type'])) {
			foreach ($key_data['column_type'] as $column_id => $column_type_setting) {
				$column_type_data['column_id'] = $column_id;
				foreach ($column_type_setting as $setting_name => $setting_value) {
					$column_type_data['column_type_setting'] = $setting_name;
					if ($format == 'json') {
						$column_type_data['column_type_value'] = json_encode($setting_value);
					} elseif ($format == 'none') {
						$column_type_data['column_type_value'] = $setting_value;

					}
					$modelColumns->createColumnTypeUnit($column_type_data);
				}

			}
		}


		if (isset($key_data['row'])) {

			$modelTables->createRowInTable($key_data['row']['table_name'], $key_data['row']['row_data']);

		}
		return 1;
	}


//Finish CREATE UNIT
	public
	function createRowByColumnId($key_data) {

		$table_name = '';
		if (is_array($key_data)) {
			foreach ($key_data['column_id'] as $column_id => $value) {
				$table_name             = $this->modelTables->getTableNameByColumnId($column_id);
				$column_name            = $this->modelColumns->getColumnNameByColumnId($column_id);
				$data_row[$column_name] = $value;
				$row_key['column_id']   = $column_id;
			}

			foreach ($key_data['column_name'] as $column_name => $value) {
				$data_row[$column_name] = $value;
			}


			$row_key['row_id'] = DB::table($table_name)->insertGetId($data_row);

			return $row_key;
		}
	}

//finish:: Update Cell
	public function deleteCreateRights($rights) {
		$modelConfig         = new AdmConfig();
		$page_id             = key($rights);
		$current_rights_data = DB::table($modelConfig->table_pages_rights_settings)->where('page_id', $page_id)->get();
		$current_rights      = [];

		foreach ($current_rights_data as $right_number => $right) {

			if ((is_array($rights[$page_id]) && !in_array($right->right_group, $rights[$page_id])) ||
				!is_array($rights[$page_id]) && $right->right_group != $rights[$page_id]
			) {
				DB::table($modelConfig->table_pages_rights_settings)->where('id', $right->id)->delete();
			} else {
				$current_rights[$right->right_group] = [$right->id];
			}
		}
		if (is_array($rights[$page_id])) {
			foreach ($rights[$page_id] as $right_number => $right_value) {
				if (!isset($current_rights[$right_value])) {
					DB::table($modelConfig->table_pages_rights_settings)->insert(
						['page_id' => $page_id, 'right_group' => $right_value]
					);

				}
			}
		}
	}


	public function deleteCreateFilters($filters) {
		$column_id   = key($filters);
		$filter_type = $filters[$column_id];
		$table_name  = $this->modelConfig->table_columns_filter_settings;
		if ($filter_type != 'none') {
			if (!DB::table($table_name)->where('column_id', $column_id)->first()) {
				DB::table($table_name)->insert(['column_id' => $column_id]);
			}
		} else {
			if (DB::table($table_name)->where('column_id', $column_id)->first()) {
				DB::table($table_name)->where('column_id', $column_id)->delete();
			}
		}

	}

	public function deleteCreateTypesSettings($settings) {
		$column_id     = key($settings);
		$settings_type = $settings[$column_id];
		$table_name    = $this->modelConfig->table_columns_types_settings;
		if ($settings_type == 'select_box') {
			if (!DB::table($table_name)->where('column_id', $column_id)->first()) {
				// DB::table($table_name)->insert(['column_id'=>$column_id]);
			}
		} else {
			if (count(DB::table($table_name)->where('column_id', $column_id)->get()) > 0) {
				DB::table($table_name)->where('column_id', $column_id)->delete();
			}
		}

	}

//get Id page, block , table , column BY BY BY ...............


	public
	function getBlockIdByTableId($table_id) {
		return DB::table($this->table_tables_settings)->select('block_id')->where('id', $table_id)->first()->block_id;
	}

	public
	function getBlockIdByColumnId($column_id) {
		return $this->getBlockIdByTableId($this->modelTables->getTableIdByColumnId($column_id));
	}

	public
	function getPageIdByBlockId($block_id) {
		return DB::table($this->table_blocks_settings)->select('page_id')->where('id', $block_id)->first()->page_id;
	}

	public
	function getPageIdByTableId($table_id) {
		return $this->getPageIdByBlockId($this->getBlockIdByTableId($table_id));
	}

	public
	function getPageIdByColumnId($column_id) {
		return $this->getPageIdByBlockId($this->getBlockIdByColumnId('$column_id'));
	}

//finish:: get Id page, block , table , column BY BY BY ...............

}


