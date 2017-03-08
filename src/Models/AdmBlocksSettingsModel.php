<?php

namespace Qubants\Scholar\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use DB;

class AdmBlocksSettingsModel extends Model
{

    //table:
    public $table_blocks_settings;
    public $table_columns_settings;

    public $modelConfig;
    public $modelTables;
    public $modelColumns;
    public $modelCrud;
    public $modelCustomsBlocks;

    public function __construct()
    {
        $this->modelConfig = new AdmConfig();

        $this->table_blocks_settings = $this->modelConfig->table_blocks_settings;
        $this->table_columns_settings = $this->modelConfig->table_columns_settings;

        $this->modelCustomsBlocks = new AdmCustomBlocksModel();
        $this->modelCrud = new AdmCrudModel();
        $this->modelTables = new DbTablesSettings();
        $this->modelColumns = new DbColumnsSettings();
    }

    public function getBlockConfigurationById($blocks_id)
    {
        $one = false;
        if (!is_array($blocks_id)) {
            $one = true;
            $blocks_id = array($blocks_id);
        }

        $config = $this->modelColumns->getBlockConfig();
        $blocks = $this->getBlocksSettingsById($blocks_id);

        $tables_id = $this->modelTables->getTablesIdByBlocksId($blocks_id);

        $all_tables_settings = $this->modelTables->getTablesKeysAndSettingsByTablesId($tables_id);

        $all_block_settings = [];
        foreach ($blocks as $block_number => $block) {

            if (isset($all_tables_settings[$block['id']])) {
                if ($one == true) {
                    $all_block_settings = $all_tables_settings[$block['id']];
                } else {
                    $all_block_settings[$block['id']] = $all_tables_settings[$block['id']];
                }
            }
            $all_block_settings[$block['id']]['key'] = 'block[' . $block['id'] . ']';
            if ($block['block_type'] == 'block_custom_b') {
                $all_block_settings[$block['id']]['content'] = $this->modelCustomsBlocks->getContentCustomBlock($block['id']);
            }

            foreach ($block as $setting_name => $setting_value) {
                if ($one == true) {
                    $all_block_settings[$setting_name] = array(
                        'key' => $this->modelCrud->getCellKeyByColumnIdAndRowId($config['blcks'][$setting_name]->id, $block['id'], 'cell'),
                        'value' => $setting_value);
                } else {
                    $all_block_settings[$block['id']][$setting_name] = array(

                        'key' => $this->modelCrud->getCellKeyByColumnIdAndRowId($config['blcks'][$setting_name]->id, $block['id'], 'cell'),
                        'value' => $setting_value);
                }
            }
        }

        return $all_block_settings;
    }


    public function getAllIdAndArrayKeysId(&$object_from_db)
    {
        if (count($object_from_db) > 0) {
            foreach ($object_from_db as $row_number => $row) {
                $new_array[$row->id] = (array)$row;
                $rows[] = $row->id;
            }
            $object_from_db = $new_array;
        } else {
            $rows = array();
        }
        return $rows;
    }

    public function getAllTablesIdAndArrayKeysId(&$tables_settings)
    {
        if (count($tables_settings) > 0) {
            foreach ($tables_settings as $table_number => $table) {
                $new_array[$table->block_id][$table->id] = (array)$table;
                $rows[] = $table->id;
            }
            $tables_settings = $new_array;
        } else {
            $rows = array();
        }
        return $rows;
    }

    public function getBlocksConfig()
    {
        return DB::table($this->table_blocks_settings)->get();
    }


    public function getBlocksIdByPageId($page_id)
    {
        $blocks_id = [];
        $blocks = DB::table($this->table_blocks_settings)->select('id')->where('page_id', $page_id)->get();
        foreach ($blocks as $block_number => $block) {
            $blocks_id[] = $block->id;
        }
        return $blocks_id;
    }

    public function getBlocksSettingsById($blocks_id)
    {

        $blocks_data = DB::table($this->table_blocks_settings)->whereIn('id', $blocks_id)->get();
        $blocks = [];
        foreach ($blocks_data as $block_number => $block) {
            $blocks[$block->id] = (array)$block;
        }
        return $blocks;
    }


    public function createBlockSettings($block_settings)
    {
        return DB::table($this->table_blocks_settings)->insertGetId($block_settings);
    }


    public function getColumnsSettingsByTableId($tables_id = false)
    {
        $conditions = [];
        if ($tables_id == false) {
            $conditions = null;
        } else if (is_array($tables_id)) {
            foreach ($tables_id as $table_id) {
                $conditions[] = ['table_id', '=', $table_id];
            }
        } else {
            $conditions[] = ['table_id', '=', $tables_id];
        }
        $columns_settings_data = DB::table($this->table_columns_settings)->orWhere($conditions)->get();
        $columns_settings = [];
        foreach ($columns_settings_data as $column) {
            $columns_settings[$column->column_name] = (array)$column;
        }
        return $columns_settings;
    }

    public function getAllColumnsSettings()
    {
        $this->getColumnsSettingsByTableId();
    }

    public function deleteBlockById($block_id)
    {
        return DB::table($this->table_blocks_settings)->where('id', $block_id)->delete();
    }

    public function createBlockUnit($block_data)
    {
        return DB::table($this->table_blocks_settings)->insertGetId($block_data);
    }
}


