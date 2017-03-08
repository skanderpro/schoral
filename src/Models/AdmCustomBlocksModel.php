<?php

namespace Qubants\Scholar\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use DB;
use Illuminate\Http\Request;
use Session;

class AdmCustomBlocksModel extends Model
{

    //table:
    public $table_users_settings;

    public $modelConfig;
    public $modelTables;
    public $modelColumns;
    public $modelCrud;

    public function __construct()
    {
        $this->modelConfig = new AdmConfig();

        $this->modelCrud = new AdmCrudModel();
        $this->modelTables = new DbTablesSettings();
        $this->modelColumns = new DbColumnsSettings();
    }

    public function getContentCustomBlock($block_id)
    {
        switch ($block_id) {
            case 24:
            	$users = DB::table('tbl_user')->select(['id','email','first_name','last_name'])->get();
                return view('scholar::admin-client.custom.send-email',['users'=>$users])->render();
                break;
        }
    }


}


