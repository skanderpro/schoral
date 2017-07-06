<?php

namespace Qubants\Scholar\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use DB;
use Illuminate\Database\Migrations\Migration;

class DbTablesSettings extends Model
{

	//table:
	public $table_tables_settings;

	//models:
	public $modelConfig;


	public function __construct() {
		$this->modelConfig           = new AdmConfig();
		$this->table_tables_settings = $this->modelConfig->table_tables_settings;
	}

	public function getDataTableResponse($table_main_id, $data_settings) {

		$modelColumns     = new DbColumnsSettings();
		$modelSelect      = new AdmSelectModel();
		$tables_id        = $this->getTablesIdByTableId($table_main_id);
		$settings         = $this->getAllSettingsByTablesId($tables_id);
		$block_id         = $this->getBlockIdByTableId($table_main_id);
		$table            = $settings['tables'][$block_id][$table_main_id];
		$config           = $modelColumns->getTableConfig();
		$columns_settings = [];

		foreach ($tables_id as $table_number => $tab_id) {
			$columns_settings[$tab_id] = $this->getTableColumnsConfiguration($block_id, $tab_id, $settings, $config);
		}

		$content_query_request = $this->getDataTableContent($table, $data_settings, $settings);

		$response['recordsTotal']    = $content_query_request['recordsTotal'];
		$response['recordsFiltered'] = $content_query_request['recordsFiltered'];

		$table_configuration_for_content = $this->getTableContentConfiguration($block_id, $table_main_id, $settings, true, $content_query_request);

		$selects = $modelSelect->getAllSelectsItems();

		$response['data'] = [];
		$cell_number      = 1;
		foreach ($table_configuration_for_content as $row_number => $row) {
			$row_content = [];
			foreach ($row['row_data'] as $table_id => $table_row) {
				$table       = $settings['tables'][$block_id][$table_id];
				$table_color = $table['table_color'];
				switch ($table_color) {
					case 'default':
						$color = 'rgba(100,100,100,0.1)';
						break;
					case 'green':
						$color = 'rgba(0,200,0,0.1)';
						break;
					case 'red':
						$color = 'rgba(200,0,0,0.1)';
						break;
					case 'blue':
						$color = 'rgba(0,0,200,0.1)';
						break;
					case 'blue':
						$color = 'rgba(0,0,100,0.1)';
						break;
					case 'purple':
						$color = 'rgba(100,0,200,0.1)';
						break;
					case 'orange':
						$color = 'rgba(250,100,0,0.1)';
						break;
					default:
						$color = 'rgba(100,100,100,0.1)';
				}

				foreach ($table_row as $column_name => $cell) {
					if ($columns_settings[$table_id][$column_name]['column_visible']['value'] == 1) {
						$row_content[] = view('scholar::admin-client.blocks.b_cell',
							['color' => $color, 'columns' => $columns_settings[$table_id], 'cell' => $cell, 'selects' => $selects, 'column_name' => $column_name, 'cell_number' => $cell_number])->render();

						$cell_number++;
					}

				}

				if ($table['table_row_editable'] == 1 || $table['table_row_delete'] == 1) {
					$row_key       = $row['row_key'][$table_id];
					$row_content[] = view('scholar::admin-client.blocks.b_button_row',
						['color' => $color, 'table_main' => $table, 'row_key' => $row_key])->render();

				}

			}

			$response['data'][] = $row_content;
		}
		return $response;
	}

	public function getTablesIdByTableId($table_id) {
		$modelBlocks = new AdmBlocksSettingsModel();
		$block_id    = $this->getBlockIdByTableId($table_id);
		$tables_id   = $this->getTablesIdByBlocksId(array($block_id));
		return $tables_id;

	}

	public function getBlockIdByTableId($table_id) {
		return DB::table($this->table_tables_settings)->select('block_id')->where('id', $table_id)->first()->block_id;
	}


	public function getTablesKeysAndSettingsByTablesId($tables_id, $get_content = true) {
		$modelColumns = new DbColumnsSettings();
		$config       = $modelColumns->getTableConfig();
		return $this->getTablesConfigurationByTablesId($config, $tables_id, $get_content);
	}

	public function getRowContentKeys($table_id, $row_id) {
		$modelColumns          = new DbColumnsSettings();
		$modelCrud             = new AdmCrudModel();
		$table_name            = $this->getTableNameByTableId($table_id);
		$content_query_data    = DB::table($table_name)->where('id', $row_id)->first();
		$columns_settings_data = $modelColumns->getColumnsSettingsByTableId($table_id);
		$columns_id            = [];
		foreach ($columns_settings_data as $column_number => $column) {
			$columns_id[$column->column_name] = $column->id;
		}
		$row_content = [];
		foreach ($content_query_data as $column_name => $column_value) {
			$row_content[$column_name]['value'] = $column_value;
			$column_id                          = $columns_id[$column_name];
			$row_content[$column_name]['key']   = $modelCrud->getCellKeyByColumnIdAndRowId($column_id, $row_id, 'cell');
		}
		return $row_content;
	}

	public function getGlobalTableKeysAndSettingsByTableId($table_id) {
		$table_name      = DB::table($this->table_tables_settings)->select('table_name')->where('id', $table_id)->first()->table_name;
		$table_global_id = $this->getTableId($table_name);
		return $this->getTablesKeysAndSettingsByTablesId($table_global_id);
	}

	public function getGlobalTablesSettings() {

		$table_settings_data = DB::table($this->table_tables_settings)->where('block_id', 1)->get();
		$tables_settings     = [];
		foreach ($table_settings_data as $table_number => $table) {
			$tables_settings[$table->table_name] = (array)$table;
		}

		return $tables_settings;
	}

	public function getTablesConfigurationByTablesId($config, $tables_id, $get_content = true) {
		$modelCrud = new AdmCrudModel();
		if (!is_array($tables_id)) {
			$one = true;
		} else {
			$one = false;
		}

		$settings = $this->getAllSettingsByTablesId($tables_id);

		$table_configuration = [];
		foreach ($settings['tables'] as $block_id => $settings_tables) {

			foreach ($settings_tables as $table_id => $table) {

				$table_settings_cur = $this->getTableSettingsConfiguration($table, $config, $one);

				$columns = $this->getTableColumnsConfiguration($block_id, $table_id, $settings, $config);

				$content_data = $this->getTableContent($table['id'], $block_id, $settings);

				$content_query = $this->getTableContentConfiguration($block_id, $table_id, $settings, $get_content, $content_data);

				$table_key = $modelCrud->getTableKey($table['id']);

				if ($one == true) {

					$table_configuration            = $table_settings_cur;
					$table_configuration['content'] = $content_query;
					$table_configuration['columns'] = $columns;

				} else {

					if ($table['block_id'] != 1) {
						if ($table['table_purpose'] == 'main') {
							$table_configuration[$table['block_id']]['table_main_id'] = $table['id'];
							$table_configuration[$table['block_id']]['content']       = $content_query;
						}
						$table_configuration[$table['block_id']]['tables'][$table['id']] = $table_settings_cur;

						$table_configuration[$table['block_id']]['columns'][$table['id']] = $columns;
					} else {
						$table_configuration[$table_key]            = $table_settings_cur;
						$table_configuration[$table_key]['columns'] = $columns;
					}
				}
			}
		}
		return $table_configuration;
	}

	public function getTableSettingsConfiguration($table, $config, $one) {
		$modelColumns   = new DbColumnsSettings();
		$modelCrud      = new AdmCrudModel();
		$table_settings = [];
		foreach ($table as $setting_name => $setting_value) {
			$table_settings[$setting_name] = array(
				'key'   => $modelCrud->getCellKeyByColumnIdAndRowId($config['tbls'][$setting_name]->id, $table['id'], 'cell'),
				'value' => $setting_value);
		}
		if ($table_settings['table_join_main']['value'] != '' && $table_settings['table_join_main'] != 'none') {
			$table_settings['table_join_main_columns'] = $modelColumns->getAllColumnsInTable($table_settings['table_join_main']['value']);
		}
		$table_settings['table_key'] = $modelCrud->getTableKey($table['id']);
		return $table_settings;
	}

	public function getTableColumnsConfiguration($block_id, $table_id, $settings, $config) {
		$modelCrud = new AdmCrudModel();

		$table   = $settings['tables'][$block_id][$table_id];
		$columns = [];
		foreach ($table as $setting_name => $setting_value) {

			foreach ($settings['columns'][$table['id']] as $column_name => $column) {
				foreach ($column as $setting_name => $setting_value) {
					$columns[$column_name][$setting_name] = array(
						'key'   => $modelCrud->getCellKeyByColumnIdAndRowId($config['clmns'][$setting_name]->id, $column['id'], 'cell'),
						'value' => $setting_value);
					if ($setting_name == 'column_display_type' && isset($settings['columns_types'][$column['id']])) {
						foreach ($settings['columns_types'][$column['id']] as $type_setting_name => $type_setting_row) {
							$columns[$column_name][$setting_name]['type_settings'][$type_setting_name] = array(
								'key'   => $modelCrud->getCellKeyByColumnIdAndRowId($config['clmns_types']['column_type_value']->id, $type_setting_row['id'], 'cell'),
								'value' => $type_setting_row['column_type_value']);
						}
						if ($setting_value == 'selectbox_column' && isset($settings['columns_types'][$column['id']]['stc_table_name'])) {
							$stc_table_name  = $settings['columns_types'][$column['id']]['stc_table_name']['column_type_value'];
							$stc_column_name = $settings['columns_types'][$column['id']]['stc_column_name']['column_type_value'];
							$stc_columns     = json_decode($settings['columns_types'][$column['id']]['stc_column_list']['column_type_value']);
							if (count($stc_columns) != 1 || $stc_columns[0] != '') {
								$items                                                          = $this->getAllItemsRowInTable($stc_table_name, $stc_column_name, $stc_columns);
								$columns[$column_name][$setting_name]['type_settings']['items'] = $items;
							}
						}


					}

					if ($setting_name == 'column_filter_type' && isset($settings['columns_filters'][$column['id']])) {
						switch ($columns[$column_name][$setting_name]['value']) {
							case 'select_all':
								$columns[$column_name][$setting_name]['column_filter_settings'] = array(
									'key'   => $modelCrud->getCellKeyByColumnIdAndRowId($config['clmns_filters']['column_filter_settings']->id,
										$settings['columns_filters'][$column['id']]['id'], 'json_cell'),
									'value' => json_decode($settings['columns_filters'][$column['id']]['column_filter_settings'])
								);
								$columns[$column_name][$setting_name]['all_filter_items']       = $this->getAllItemsFilterSelectAll($column['id']);
								break;
							case 'range':
							case 'range_date':
								$columns[$column_name][$setting_name]['column_filter_settings']     = array(
									'key'   => $modelCrud->getCellKeyByColumnIdAndRowId($config['clmns_filters']['column_filter_settings']->id,
										$settings['columns_filters'][$column['id']]['id'], 'cell'),
									'value' => $settings['columns_filters'][$column['id']]['column_filter_settings']
								);
								$columns[$column_name][$setting_name]['column_filter_settings_two'] = array(
									'key'   => $modelCrud->getCellKeyByColumnIdAndRowId($config['clmns_filters']['column_filter_settings_two']->id,
										$settings['columns_filters'][$column['id']]['id'], 'cell'),
									'value' => $settings['columns_filters'][$column['id']]['column_filter_settings_two']
								);
								break;
						}
					}

				}
			}
		}

		return $columns;
	}

	public function getTableContentConfiguration($block_id, $table_id, $settings, $get_content, $content) {
		$modelCrud = new AdmCrudModel();

		if (isset($content['data']) && is_array($content['data'])) {
			$table = $settings['tables'][$block_id][$table_id];

			$tables_data = [];
			$tables      = [];

			if ($get_content == true) {
				foreach ($content['data'] as $row_number => $row) {

					if ($row_number == 0) {


						foreach ($row as $column_key => $column_value) {

							$pet  = $column_key;
							$serg = explode('__', $pet);

							$tab_id                                  = $tables_data['table_id'][$column_key] = $serg[0];
							$tables_data['column_name'][$column_key] = $serg[1];

							if (!in_array($tab_id, $tables)) {

								$tables[$tab_id]['id'] = $tab_id;

							}
						}
					}
				}

				foreach ($tables as $table_id => $table_id) {
					$table_name = $settings['tables'][$block_id][$table_id]['table_name'];
					if ($this->hasPrimaryKeyByTableName($table_name) == true) {
						$tables[$table_id]['key']        = $this->getPrimaryKeyByTableName($table_name);
						$tables[$table_id]['column_key'] = $table_id . '__' . $tables[$table_id]['key'];
					} else {
						$tables[$table_id]['key']        = '';
						$tables[$table_id]['column_key'] = '';
					}

				}

				$tables_configuration = [];
				foreach ($content['data'] as $number => $row) {
					foreach ($row as $column_key => $column_value) {
						//нужно оптимизировать чтобы не получить row_key для каждой колонки.......
						$t_id = $tables_data['table_id'][$column_key];

						if ($tables[$t_id]['column_key'] == '') {
							$key = $number;
						} else {
							$key = $row[$tables[$t_id]['column_key']];
						}

						$row_key = $modelCrud->getRowKey($t_id, $key);

						$column_name = $tables_data['column_name'][$column_key];
						$column_id   = $settings['columns'][$t_id][$column_name]['id'];

						$tables_configuration[$number]['row_data'][$t_id][$column_name] =
							array(
								'value' => $column_value,
								'key'   => $modelCrud->getCellKeyByColumnIdAndRowId($column_id, $key, 'cell')
							);
						$tables_configuration[$number]['row_key'][$t_id]                = $row_key;
					}
				}


				return $tables_configuration;
			}
		} else {
			return false;
		}
	}


	public function getTableContent($table_main_id, $block_id, $settings) {
		$modelColumns = new DbColumnsSettings();
		$content      = $this->getQueryTableContent($table_main_id, $block_id, $settings);

		if ($content != false) {
			$content['query'] = $content['query']->limit(1);
			$content_query    = $content['query']->get();


			foreach ($content_query as $row_number => $row) {
				$content['data'][$row_number] = (array)$row;
			}

			return $content;

		}

	}

	public function getQueryTableContent($table_main_id, $block_id, $settings) {

		if ($settings['tables'][$block_id][$table_main_id]['table_purpose'] == 'main') {
			$table_main_name = $settings['tables'][$block_id][$table_main_id]['table_name'];
			$tables_join     = [];

			foreach ($settings['tables'][$block_id] as $table_id => $table) {
				if ($table['table_purpose'] == 'join') {
					$tables_join['tables'][$table['table_name']]  = $table;
					$tables_join['columns'][$table['table_name']] = $settings['columns'][$table_id];
				}
			}

			$content_query                                                   = DB::table($table_main_name);
			$content_query_settings                                          = [];
			$content_query_settings['main_table']['table'][$table_main_name] = $settings['tables'][$block_id][$table_main_id];

			foreach ($settings['columns'][$table_main_id] as $column_main_name => $column) {
				if ($column['column_visible'] == 1 || $column['column_name'] == 'id') {
					$content_query                                                      = $content_query->addSelect([$table_main_name . '.' . $column['column_name'] . ' as ' . $table_main_id . '__' . $column['column_name']]);
					$content_query_settings['main_table']['columns'][$column_main_name] = $column;
					$content_query_tables_settings[$table_main_id][$column_main_name]   = $column;
				}

			}

			if (isset($tables_join['tables'])) {
				$table_list = $this->getArrayJoinTables($tables_join, $table_main_name, $content_query, $content_query_settings, $content_query_tables_settings);
				$this->getJoins($table_list[$table_main_name], $content_query_settings, $content_query, $tables_join);
			}

			$content['query']    = $content_query;
			$content['settings'] = $content_query_settings;
			$content['keys']     = $content_query_tables_settings;

			return $content;

		} else {

			return false;

		}

	}

	public function getJoins($table_list, $content_query_settings, &$content_query, $tables_join) {
		foreach ($table_list as $table_name => $table_value) {

			$table = $tables_join['tables'][$table_name];

			$content_query->join($table_name, $table_name . '.' . $table['table_column_join'], '=', $table['table_join_main'] . '.' . $table['table_column_join_main']);
			if (is_array($table_value)) {
				$this->getJoins($table_list[$table_name], $content_query_settings, $content_query, $tables_join);
			}
		}
	}

	public function getArrayJoinTables($tables_join, $table_main_name, &$query, &$content_query_settings, &$content_query_tables_settings) {
		// dd($tables_join);
		$tables_list = array();
		foreach ($tables_join['tables'] as $table_name => $table) {
			if ($table['table_join_main'] == $table_main_name) {
				$serg = $this->getArrayJoinTables($tables_join, $table_name, $query, $content_query_settings, $content_query_tables_settings);
				if ($serg != 0) {
					$tables_list[$table_main_name] = $serg;
				} else {
					$tables_list[$table_main_name][$table_name] = 1;
				}
				foreach ($tables_join['columns'][$table_name] as $column_name => $column) {

					$query                                                                       = $query->addSelect([$table_name . '.' . $column['column_name'] . ' as ' . $table['id'] . '__' . $column['column_name']]);
					$content_query_settings['join_tables']['columns'][$table_name][$column_name] = $column;
					$content_query_tables_settings[$table['id']][$column['column_name']]         = $column;

				}
			}
		}
		if (isset($tables_list[$table_main_name]) && count($tables_list[$table_main_name]) > 0) {
			return $tables_list;
		} else {
			return 0;
		}
	}

	public function getDataTableContent($table, $data_settings, $settings) {
		$table_name = $table['table_name'];
		$block_id   = $table['block_id'];

		$search_row = $data_settings['search']['value'];

		$content = $this->getQueryTableContent($table['id'], $table['block_id'], $settings);

		$response['data']         = $content['query'];
		$response['recordsTotal'] = DB::table($table_name)->count();

		if ($search_row != '' && strlen($search_row) >= 2) {

			$i                = 0;
			$response['data'] = $response['data']->where(function ($query) use ($search_row, $content, $settings, $block_id) {
				foreach ($content['keys'] as $table_id => $tab) {
					$table_name = $settings['tables'][$block_id][$table_id]['table_name'];

					foreach ($tab as $column_name => $column) {

						$query->orWhere($table_name . '.' . $column_name, 'like', "%{$search_row}%");

					}

				}
			});

		}

		foreach ($settings['columns'] as $table_id => $columns) {
			$table_name = $settings['tables'][$block_id][$table_id]['table_name'];
			foreach ($columns as $column_name => $column) {
				if (isset($settings['columns_filters'][$column['id']]) && $column['column_filter_type'] != 'none') {

					$filter_type = $column['column_filter_type'];
					switch ($filter_type) {
						case 'select_all':
							$array_filter = json_decode($settings['columns_filters'][$column['id']]['column_filter_settings']);

							if (is_array($array_filter)) {
								$response['data'] = $response['data']->whereIn($table_name . '.' . $column_name, $array_filter);
							}
							if (isset($data_settings['filter'][$table_id][$column_name]['column_filter_settings']) &&
								is_array($data_settings['filter'][$table_id][$column_name]['column_filter_settings'])
							) {
								$array_filter     = $data_settings['filter'][$table_id][$column_name]['column_filter_settings'];
								$response['data'] = $response['data']->whereIn($table_name . '.' . $column_name, $array_filter);
							}

							break;
						case 'range':
						case 'range_date':
							$lower_limit = floatval($settings['columns_filters'][$column['id']]['column_filter_settings']);
							$upper_limit = floatval($settings['columns_filters'][$column['id']]['column_filter_settings_two']);

							if ($upper_limit != '') {
								$response['data'] = $response['data']->where($table_name . '.' . $column_name, '<', $upper_limit);
							}
							if ($lower_limit != '') {
								$response['data'] = $response['data']->where($table_name . '.' . $column_name, '>', $lower_limit);
							}

							if (isset($data_settings['filter'][$table_id][$column_name]['column_filter_settings'])) {
								$lower_limit_data = $data_settings['filter'][$table_id][$column_name]['column_filter_settings'];
								$response['data'] = $response['data']->where($table_name . '.' . $column_name, '>', $lower_limit_data);
							}

							if (isset($data_settings['filter'][$table_id][$column_name]['column_filter_settings_two'])) {
								$upper_limit      = $data_settings['filter'][$table_id][$column_name]['column_filter_settings_two'];
								$response['data'] = $response['data']->where($table_name . '.' . $column_name, '<', $upper_limit);
							}
							break;
					}
				}
			}
		}


		$response['recordsFiltered'] = $response['data']->count();
		$response['data']            = $response['data']->offset($data_settings['start']);

		$count_rows = $settings['tables'][$block_id][$table['id']]['table_count_records'];
		if ($data_settings['length'] == 0) {
			$response['data'] = $response['data']->limit($count_rows);
		} else {
			$response['data'] = $response['data']->limit($data_settings['length']);
		}

		if ($data_settings['order'][0]['column'] != '') {
			if ($data_settings['order'][0]['dir'] == 'asc' || $data_settings['order'][0]['dir'] == 'desc') {

				$column_order = '';

				foreach ($content['keys'] as $table_id => $table) {
					foreach ($table as $column_name => $column_value) {
						if ($data_settings['sort_table_id'] == $table_id && $data_settings['sort_column_name'] == $column_name) {
							$column_order     = $column_name;
							$table_name       = $settings['tables'][$block_id][$table_id]['table_name'];
							$response['data'] = $response['data']->orderBy($table_name . '.' . $column_order, $data_settings['order'][0]['dir']);
						}

					}
				}
			}
		}


		$response['data'] = $response['data']->get();
		$content['data']  = array();

		if (count($response['data']) > 0) {

			foreach ($response['data'] as $row_number => $row) {
				$content['data'][$row_number] = (array)$row;
			}

		}

		$response['data'] = $content['data'];
		return $response;
	}

	public function getAllItemsFilterSelectAll($column_id) {
		$model_columns = new DbColumnsSettings();
		$table_name    = $this->getTableNameByColumnId($column_id);
		$column_name   = $model_columns->getColumnNameByColumnId($column_id);
		$column_data   = DB::table($table_name)->select($column_name)->get();
		$column_values = [];
		foreach ($column_data as $number_row => $row) {
			if (!in_array($row->{$column_name}, $column_values))
				$column_values[] = $row->{$column_name};
		}
		return $column_values;
	}

	public function getTableIdByColumnId($column_id) {
		$model_columns          = new DbColumnsSettings();
		$table_columns_settings = $model_columns->table_columns_settings;
		return DB::table($table_columns_settings)->select('table_id')->where('id', $column_id)->first()->table_id;
	}

	public function getTableNameByColumnId($column_id) {
		return $this->getTableNameByTableId($this->getTableIdByColumnId($column_id));
	}

	public function getPrimaryKeyByTableName($table_name) {
		return array_first(DB::getDoctrineSchemaManager()->listTableDetails($table_name)->getPrimaryKeyColumns());
	}

	public function hasPrimaryKeyByTableName($table_name) {
		return DB::getDoctrineSchemaManager()->listTableDetails($table_name)->hasPrimaryKey();
	}

	public function getAllSettingsByTablesId($tables_id) {
		$modelColumns = new DbColumnsSettings();

		if (!is_array($tables_id)) {
			$tables_id = array($tables_id);
		}

		$settings['tables']          = $this->getTablesSettingsByTablesId($tables_id);
		$settings['columns']         = $modelColumns->getColumnsSettingsByTablesId($tables_id);
		$settings['columns_types']   = $modelColumns->getAllColumnTypeSettings();
		$settings['columns_filters'] = $modelColumns->getAllColumnFilterSettings();
		return $settings;
	}

	public function getAllItemsRowInTable($table_name, $column_name, $columns) {
		if (!in_array($column_name, $columns)) {
			$columns[] = $column_name;
		}
		$rows  = DB::table($table_name)->select($columns)->get();
		$items = [];
		foreach ($rows as $row_number => $row) {
			if (!in_array($row->{$column_name}, $items)) {
				$items[$row->{$column_name}] = (array)$row;
			}
		}
		return $items;
	}

	public function getTablesSettingsByTablesId($tables_id) {
		if (!is_array($tables_id)) {
			$tables_id = array($tables_id);
		}

		$tables_settings_data = DB::table($this->table_tables_settings)->whereIn('id', $tables_id)->get();
		if (count($tables_settings_data) > 0) {
			foreach ($tables_settings_data as $table_number => $table) {
				$tables_settings[$table->block_id][$table->id] = (array)$table;
			}
		} else {
			$tables_settings = array();
		}
		return $tables_settings;
	}

	public function getTablesIdByBlocksId($blocks_id) {
		$tables_id = [];
		$tables    = DB::table($this->table_tables_settings)->select('id')->whereIn('block_id', $blocks_id)->get();
		foreach ($tables as $table_number => $table) {
			$tables_id[] = $table->id;
		}
		return $tables_id;
	}

	public function getTableIdByNameAndPurpose($table_name, $table_purpose) {
		return DB::table($this->table_tables_settings)->select('id')
			->where([['table_name', '=', $table_name], ['table_purpose', '=', $table_purpose]])->first()->id;
	}

	public function getTablesGlobalId() {
		$data_id = DB::table($this->table_tables_settings)->select('id')->where('block_id', 1)->get();
		$id      = [];
		foreach ($data_id as $row => $table) {
			$id[] = $table->id;
		}
		return $id;
	}

	public function getGlobalTablesColumns() {
		$modelColumns = new DbColumnsSettings();
		$tables_id    = $this->getTablesGlobalId();
		$columns_all  = $modelColumns->getColumnsSettingsByTablesId($tables_id);
		return $columns_all;

	}

	public function getTableId($table_name) {
		return DB::table($this->table_tables_settings)->select('id')->where('table_name', $table_name)->first()->id;
	}

	public function getTableNameByTableId($table_id) {
		return DB::table($this->table_tables_settings)->select('table_name')->where('id', $table_id)->first()->table_name;
	}

	public function getTableContentIdByTableId($table_id) {
		$table_name = $this->getTableNameByTableId($table_id);
		return DB::table($this->table_tables_settings)->select('id')->where([['table_name', '=', $table_name], ['table_purpose', '=', 'content']])
			->orWhere([['table_name', '=', $table_name], ['table_purpose', '=', 'config']])->first()->id;
	}

	public function createTableUnit($table_data) {
		return DB::table($this->table_tables_settings)->insertGetId($table_data);
	}

	public function deleteTableUnit($table_id) {
		return DB::table($this->table_tables_settings)->where('id', $table_id)->delete();
	}

	public function createRowInTable($table_name, $row_data) {
		return DB::table($table_name)->insertGetId($row_data);
	}
}


