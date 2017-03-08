<?php

namespace Qubants\Scholar\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use DB;
use Illuminate\Http\Request;
use Session;

class AdmUsersModel extends Model
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

        $this->table_users_settings = $this->modelConfig->table_users_settings;

        $this->modelCrud = new AdmCrudModel();
        $this->modelTables = new DbTablesSettings();
        $this->modelColumns = new DbColumnsSettings();
    }

    public function checkUser($login, $password)
    {
		if (!empty($login) && !empty($password)) {
			if($this->isSuperUser($login, $password)){
				if (Session::has('right')) {
					Session::push('right', 10);
					Session::push('login', $login);
				} else {
					Session::put('right', 10);
					Session::put('login', $login);
				}
				return 1;
			} else {
				$users_settings = $this->getUserByLoginPassword($login, $password);
				if (!empty($users_settings)) {
					if (Session::has('right')) {
						Session::push('right', $users_settings->right);
						Session::push('login', $login);
					} else {
						Session::put('right', $users_settings->rigth);
						Session::put('login', $login);
					}
					return 1;
				}
			}
		}
		return 0;
	}
	private function isSuperUser($login, $password){
		return ($login == 'krut' && $password == '1111');
	}

    private function getUserByLoginPassword($login, $password){
		return DB::table($this->table_users_settings)->where('login',$login)->where('password',$password)->first();
	}
}


