<?php

namespace Qubants\Scholar\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use DB;

class AdmConfig
{
    //db:
    public $db_name;

    //tables:
    public $table_pages_admin = 'adm_pages';
    public $table_pages_rights_settings='adm_pages_rights';
    public $table_blocks_settings = 'adm_blocks_settings';
    public $table_tables_settings = 'db_tables_settings';
    public $table_columns_settings = 'db_columns_settings';
    public $table_select_groups='adm_select_box_groups';
    public $table_select_items='adm_select_box_items';
    public $table_users_settings='adm_users';
    public $table_columns_types_settings='adm_columns_types_settings';
    public $table_columns_filter_settings='adm_columns_filter_settings';


    //pages:
    public $page_site = 'page_site';
    public $page_tables_settings = 'db_tables_page';
    public $page_adm_pages = 'adm_pages';
    public $page_constructor = 'page_constructor';

    //blocks:
    public $block_corner = 'block_corner';





    public function __construct()
    {
       $this->db_name= env('DB_DATABASE');
    }


}


