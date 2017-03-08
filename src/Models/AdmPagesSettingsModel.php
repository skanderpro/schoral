<?php

namespace Qubants\Scholar\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use DB;

class AdmPagesSettingsModel extends Model
{

    //table:
    public $table_pages_settings;
    public $table_pages_rights_settings;

    public $modelTables;
    public $modelColumns;
    public $modelBlocks;
    public $modelCrud;
    public $modelConfig;

    public function __construct()
    {
        $this->modelConfig=new AdmConfig();
        $this->table_pages_settings=$this->modelConfig->table_pages_admin;
        $this->table_pages_rights_settings=$this->modelConfig->table_pages_rights_settings;
        $this->modelBlocks = new AdmBlocksSettingsModel();
        $this->modelTables = new DbTablesSettings();
        $this->modelColumns = new DbColumnsSettings();
        $this->modelCrud = new AdmCrudModel();
    }

    public function getPageConfigurationById($page_id)
    {
        $config = $this->modelColumns->getPageConfig();
        $page = $this->getPageSettingsById($page_id);
        $blocks_id=$this->modelBlocks->getBlocksIdByPageId($page_id);
        $all_settings = $this->getPageKeysAndSettings($page_id, $config, $page);

        $all_blocks=$this->modelBlocks->getBlockConfigurationById($blocks_id);

        foreach ($all_blocks as $block_id =>$block){
            $all_settings['views'][$block['page_view']['value']][$block['id']['value']]=$block;
        }

        return $all_settings;
    }

    public function getPageKeysAndSettings($page_id, $page_config, $page)
    {
        foreach ($page['page'] as $setting_name => $setting_value) {
            $page_settings[$setting_name] = array(
                'key' => $this->modelCrud->getCellKeyByColumnIdAndRowId($page_config['pgs'][$setting_name]->id, $page_id, 'cell'),
                'value' => $setting_value);
        }
        $page_settings['rights']=$this->getAllRightsByPageId($page_id);
        return $page_settings;
    }

    public function getPageSettingsById($page_id)
    {
        $page['page'] = (array)DB::table($this->table_pages_settings)->where('id', $page_id)->first();
        return $page;
    }

    public function getAllPagesSettings(){
       return DB::table($this->table_pages_settings)->orderBy('weight')->get();
    }

    public function getAllPagesKeysSettings(){
        $table_id=$this->modelTables->getTableId($this->table_pages_settings);
        return $this->modelTables->getTablesKeysAndSettingsByTablesId($table_id);
    }

    public function getAllRightsByPageId($page_id){
        $rights_data=DB::table($this->table_pages_rights_settings)->where('page_id', $page_id)->get();
        $rights=[];
        $table_id=$this->modelTables->getTableId($this->table_pages_rights_settings);
        foreach ($rights_data as $right_number => $right){
            $rights[$right->right_group]['value']=$right->right_group;
            $rights[$right->right_group]['key']=$this->modelCrud->getRowKey($table_id, $right->id);
        }
        return $rights;
    }

    public function getAllPagesRights(){
        $rights_data=DB::table($this->table_pages_rights_settings)->get();
        $rights=[];
        foreach ($rights_data as $page_right_number => $page_right){
            $rights[$page_right->page_id][$page_right->right_group]=true;
        }
        return $rights;
    }


}


