<?php

namespace Qubants\Scholar\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use DB;

class AdmSelectModel extends Model
{
    public $modelConfig;
    public $modelTables;
    public $modelColumns;

    public $table_select_groups;
    public $table_select_items;


    public function __construct()
    {
        $this->modelConfig = new AdmConfig();
        $this->modelTables = new DbTablesSettings();
        $this->modelTables = new DbColumnsSettings();
        $this->table_select_groups = $this->modelConfig->table_select_groups;
        $this->table_select_items = $this->modelConfig->table_select_items;
    }

    public function getSelectGroupIdByName($name_select){
        return DB::table($this->table_select_groups)->select('id')->where('name', $name_select)->first()->id;
    }

    public function getSelectGroupNameById($select_id){
        return DB::table($this->table_select_groups)->select('name')->where('id', $select_id)->first()->name;

    }

    public function getItemsByGroupName($name_select){
        $select_id=$this->getSelectGroupIdByName($name_select);
        $select_items_data=DB::table($this->table_select_items)->where('group_id',$select_id)->get();
        $select_items=[];
        foreach ($select_items_data as $item_number => $item){
            $select_items[$item->name]=(array)$item;
        }
        return $select_items;
    }

    public function getAllSelectsItems(){
        $select_items_data=DB::table($this->table_select_items)->get();
        $select_items=[];
        foreach ($select_items_data as $item_number => $item){
            $select_items[$this->getSelectGroupNameById($item->group_id)][$item->name]=(array)$item;
        }
        return $select_items;
    }



}


