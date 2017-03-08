<?php

namespace Qubants\Scholar\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use DB;

class DbColumnsSettings extends Model
{
    //table:
    public $table_columns_settings;
    public $table_columns_types_settings;
    public $table_columns_filter_settings;

    //models:
    public $modelConfig;
    public $modelTables;

    public function __construct()
    {
        $this->modelConfig = new AdmConfig();
        $this->table_columns_settings=$this->modelConfig->table_columns_settings;
        $this->table_columns_types_settings=$this->modelConfig->table_columns_types_settings;
        $this->table_columns_filter_settings=$this->modelConfig->table_columns_filter_settings;
    }



    //tables config pages blocks tables columns...
    public
    function getPageConfig()
    {
        $config=$this->getBlockConfig();
        $config['pgs'] = $this->getColumnsConfigByTableName($this->modelConfig->table_pages_admin);
       return $config;
    }

    function getBlockConfig(){
        $config=$this->getTableConfig();
        $config['blcks'] = $this->getColumnsConfigByTableName($this->modelConfig->table_blocks_settings);
        return $config;
    }

    function getTableConfig(){
        $config=$this->getColumnConfig();
        $config['tbls'] = $this->getColumnsConfigByTableName($this->modelConfig->table_tables_settings);
        return $config;
    }

    function getColumnConfig(){
        $config['clmns'] = $this->getColumnsConfigByTableName($this->modelConfig->table_columns_settings);
        $config['clmns_types']=$this->getColumnsConfigByTableName($this->table_columns_types_settings);
        $config['clmns_filters']=$this->getColumnsConfigByTableName($this->table_columns_filter_settings);
        return $config;
    }

    public
    function getColumnsConfigByTableName($table_name)
    {
        $this->modelTables = new DbTablesSettings();
        $table_id = $this->modelTables->getTableIdByNameAndPurpose($table_name, 'config');
        $columns_config=DB::table($this->table_columns_settings)->where('table_id', $table_id)->get();
        $columns=[];
        foreach ($columns_config as $column_number =>$column){
            $columns[$column->column_name] =$column;
        }

        return $columns;
    }


    //finish: tables config pages blocks tables columns...

    //Config on unit:
    public function getUnitConfigBlock(){
       return $this->getUnitConfigByType('block');
    }

    public function getUnitConfigByType($block_type)
    {
        switch ($block_type){
            case 'page':
                $table_name=$this->modelConfig->table_pages_admin;
                break;
            case 'block':
                $table_name=$this->modelConfig->table_blocks_settings;
                break;
            case 'table':
                $table_name=$this->modelConfig->table_tables_settings;
                break;
            case 'column':
                $table_name=$this->modelConfig->table_columns_settings;
                break;
        }
        $this->modelTables = new DbTablesSettings();
        $table_id = $this->modelTables->getTableIdByNameAndPurpose($table_name, 'config');
        $columns_config=DB::table($this->table_columns_settings)->where('table_id', $table_id)->get();
        $columns=[];
        foreach ($columns_config as $column_number =>$column){
            $columns[$column->column_name] =array('column_id'=>'new_row[column_id]['.$column->id.']');
        }

        return $columns;
    }
//finish: Config on unit
    public function getColumnsSettingsByTableId($table_id)
    {
        return DB::table($this->table_columns_settings)->where('table_id', $table_id)->get();
    }

    public function getAllColumnTypeSettings(){
        $column_type_settings_data=DB::table($this->table_columns_types_settings)->get();
        $types_settings=[];
        foreach ($column_type_settings_data as $row_number =>$row) {
            $types_settings[$row->column_id][$row->column_type_setting]=(array)$row;
        }
        return $types_settings;
    }

    public function getAllColumnFilterSettings(){
        $column_filter_settings_data=DB::table($this->table_columns_filter_settings)->get();
        $filters_settings=[];
        foreach ($column_filter_settings_data as $row_number =>$row) {
            $filters_settings[$row->column_id]['column_filter_settings']=$row->column_filter_settings;
            $filters_settings[$row->column_id]['column_filter_settings_two']=$row->column_filter_settings_two;
            $filters_settings[$row->column_id]['column_id']=$row->column_id;
            $filters_settings[$row->column_id]['id']=$row->id;
        }
        return $filters_settings;
    }

    public function getColumnsSettingsByTablesId($tables_id)
    {
        $columns= DB::table($this->table_columns_settings)->whereIn('table_id', $tables_id)->get();
        $columns_settings=[];
        foreach ($columns as $column_number => $column){
            $columns_settings[$column->table_id][$column->column_name]=(array)$column;
        }
        return $columns_settings;
    }

    public function createColumnUnit($column_data){
        return DB::table($this->table_columns_settings)->insertGetId($column_data);
    }
public function createColumnTypeUnit($column_type_data){
        return DB::table($this->table_columns_types_settings)->insertGetId($column_type_data);
}

    public function getAllColumnsInTable($table_name, $db_name = false)
    {
        if ($db_name == false) {
            $db_name = $this->modelConfig->db_name;
        }
        $columns = Schema::getColumnListing($table_name);
        return $columns;
    }

    public
    function getColumnNameByColumnId($column_id)
    {
        return DB::table($this->table_columns_settings)->select('column_name')->where('id', $column_id)->first()->column_name;
    }




}


