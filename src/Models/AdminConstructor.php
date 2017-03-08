<?php

namespace Qubants\Scholar\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use DB;

class AdminConstructor extends Model
{//db:
    public $db_name;

    //tables:
    public $table_tables_settings;
    public $table_columns_settings;
    public $table_pages_admin;
    public $table_blocks_settings;

    public $table_pages_rights_settings;
    public $table_select_groups;
    public $table_select_items;
    public $table_users_settings;
    public $table_columns_types_settings;
    public $table_columns_filter_settings;

    //pages:
    public $page_tables_settings;
    public $page_adm_pages;
    public $page_site;
    public $page_constructor;


    //models:
    public $modelConfig;

    public function __construct()
    {
        $this->modelConfig = new AdmConfig();

        //db:
        $this->db_name = $this->modelConfig->db_name;

        //tables:
        $this->table_tables_settings = $this->modelConfig->table_tables_settings;
        $this->table_columns_settings = $this->modelConfig->table_columns_settings;
        $this->table_pages_admin = $this->modelConfig->table_pages_admin;
        $this->table_blocks_settings = $this->modelConfig->table_blocks_settings;
        $this->table_pages_rights_settings = $this->modelConfig->table_pages_rights_settings;
        $this->table_select_groups = $this->modelConfig->table_select_groups;
        $this->table_select_items = $this->modelConfig->table_select_items;
        $this->table_users_settings = $this->modelConfig->table_users_settings;
        $this->table_columns_types_settings=$this->modelConfig->table_columns_types_settings;
        $this->table_columns_filter_settings=$this->modelConfig->table_columns_filter_settings;

        //pages:
        $this->page_tables_settings = $this->modelConfig->page_tables_settings;
        $this->page_adm_pages = $this->modelConfig->page_adm_pages;
        $this->page_site = $this->modelConfig->page_site;
        $this->page_constructor = $this->modelConfig->page_constructor;
    }


    public function getBlocksSettingsByPage($page_id, $page_view)
    {
        $adminBlockConstructor = new AdminBlockConstructor();
        $all_blocks_settings = $adminBlockConstructor->getPageBlocksSettings($page_id, $page_view);
        return $all_blocks_settings;
    }


    public function loadInformationSchema($db_name = false)
    {
        if ($db_name == false) {
            $db_name = $this->db_name;
        }
        $this->upgradeDbTablesSettings();
        $db_tables_information = $this->getAllTablesColumns($db_name);
        return $db_tables_information;
    }

    public function upgradeDbTablesSettings($db_name = false)
    {
        if ($db_name == false) {
            $db_name = $this->db_name;
        }

        $this->createIfNotFoundAdmPagesSettings();
        /*$this->createIfNotFoundAdmPagesRights();*/
        $this->createIfNotFoundBlocksSettings();
        $this->createIfNotFoundDbTablesSettings();
        $this->createIfNotFoundDbColumnsSettings();
        $this->createIfNotFoundDbColumnsTypesSettings();
        $this->createIfNotFoundDbColumnsFilterSettings();
        $this->createIfNotFoundAdmUsers();
        $this->createIfNotFoundAdmSelectBox();

        $db_tables_information = $this->getAllTablesColumns($db_name);
        $pages_settings = $this->getAllPagesSettings();
        $blocks_settings = $this->getAllBlocksSettingsAndDeleteUndefined();
        $tables_settings = $this->getAllTablesSettingsAndDeleteUndefined($blocks_settings);
        $columns_settings = $this->getAllColumnsSettingsAndDeleteUndefined($tables_settings);

        $db_tables_exist = [];
        foreach ($tables_settings as $table_id => $table) {

            if (!isset($db_tables_information[$table->table_name])) {

                DB::table($this->table_tables_settings)->where('id', $table->id)->delete();
            } else {
                $db_tables_exist[$table->table_name]['exist'] = 1;
                if (isset($columns_settings[$table->id])) {

                    foreach ($columns_settings[$table_id] as $column_name => $column_settings) {

                        if (!isset($db_tables_information[$table->table_name][$column_name])) {

                            DB::table($this->table_columns_settings)->where('id', $columns_settings[$table_id][$column_name]['id'])->delete();
                        } else {
                            $db_tables_exist[$table_id][$column_name]['exist'] = 1;
                        }

                    }
                }
                foreach ($db_tables_information[$table->table_name] as $column_name) {

                    if (!isset($db_tables_exist[$table_id][$column_name]['exist']) || $db_tables_exist[$table_id][$column_name]['exist'] != 1) {

                        DB::table($this->table_columns_settings)->insert(
                            ['table_id' => $table_id, 'column_name' => $column_name, 'column_visible' => 1, 'column_title' => $column_name,
                                'column_display_type' => 'text', 'column_editable' => 0]
                        );

                    }
                }
            }

        }

        foreach ($db_tables_information as $table_name => $columns) {

            if (!isset($db_tables_exist[$table_name]['exist']) || $db_tables_exist[$table_name]['exist'] != 1) {
                if ($table_name == $this->table_pages_admin || $table_name == $this->table_blocks_settings ||
                    $table_name == $this->table_tables_settings || $table_name == $this->table_columns_settings||
                    $table_name == $this->table_columns_types_settings || $table_name == $this->table_columns_filter_settings
                ) {
                    $table_purpose = 'config';
                } else {
                    $table_purpose = 'content';
                }

                $table_id = DB::table($this->table_tables_settings)->insertGetId(
                    ['table_name' => $table_name, 'table_visible' => 1, 'table_title' => $table_name,
                        'block_id' => 1, 'table_purpose' => $table_purpose, 'table_row_delete' => 0, 'table_row_editable' => 0, 'table_color' => 'default',
                        'table_row_add' => 0, 'table_sort_column' => '', 'table_count_records' => 10]
                );

                foreach ($columns as $column_name) {
                    DB::table($this->table_columns_settings)->insert(
                        ['table_id' => $table_id, 'column_name' => $column_name, 'column_visible' => 1, 'column_title' => $column_name,
                            'column_display_type' => 'text', 'column_editable' => 0, 'column_filter_type'=>'none']
                    );
                }
            }
        }

    }


    public function getAllTablesColumns($db_name = false)
    {
        if ($db_name == false) {
            $db_name = $this->db_name;
        }

        $tables_titles = DB::select("select table_name from information_schema.tables where Table_Schema='$db_name'");
        $db_tables_information = [];
        foreach ($tables_titles as $table_name) {
            $columns = $this->getAllColumnsInTable($table_name, $db_name);
            $db_tables_information[$table_name->table_name] = [];
            foreach ($columns as $index => $column) {
                $db_tables_information[$table_name->table_name][$column->COLUMN_name] = $column->COLUMN_name;
            }
        }
        return $db_tables_information;
    }

    public function getAllColumnsInTable($table_name, $db_name = false)
    {
        if ($db_name == false) {
            $db_name = $this->db_name;
        }
        return DB::select("select COLUMN_name from information_schema.columns where 
                        Table_Schema='$db_name' AND table_name='$table_name->table_name' ORDER BY ORDINAL_POSITION ; ");
    }

    private function getAllPagesSettings()
    {
        $pages_settings = DB::table($this->table_pages_admin)->get();
        $pages = [];
        foreach ($pages_settings as $row_number => $page) {
            $pages[$page->id] = $page;
        }
        return $pages;
    }

    private function getAllBlocksSettingsAndDeleteUndefined()
    {
        $blocks_data = DB::table($this->table_blocks_settings)->get();
        $blocks = [];
        foreach ($blocks_data as $row_number => $block) {
            $blocks[$block->id] = $block;
        }
        return $blocks;
    }

    public function getAllTablesSettingsAndDeleteUndefined($blocks_settings)
    {
        $tables_settings_data = DB::table($this->table_tables_settings)->get();
        $tables_settings = [];
        foreach ($tables_settings_data as $number => $table) {
            if (!isset($blocks_settings[$table->block_id])) {
                DB::table($this->table_tables_settings)->where('id', $table->id)->delete();
            } else {
                $tables_settings[$table->id] = $table;
            }
        }
        return $tables_settings;
    }

    private function getAllColumnsSettingsAndDeleteUndefined($tables_settings)
    {

        $columns_settings_data = DB::table($this->table_columns_settings)->get();

        $columns_settings = [];

        foreach ($columns_settings_data as $column_id => $column) {

            if (!isset($tables_settings[$column->table_id])) {
                DB::table($this->table_columns_settings)->where('id', $column->id)->delete();
            } else {
                $columns_settings[$column->table_id][$column->column_name] = (array)$column;

            }
        }
        return $columns_settings;
    }

    private function createIfNotFoundAdmPagesSettings()
    {
        if (Schema::hasTable($this->table_pages_admin) == false) {
            Schema::create($this->table_pages_admin, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('name', 255);
                $table->string('title', 255);
                $table->string('fa_fa_icon', 255)->default('');
                $table->integer('weight');
                $table->tinyInteger('adm_visible')->default('0');
                $table->tinyInteger('client_visible')->default('0');
                $table->tinyInteger('constructor_visible')->default('0');
            });
            DB::table($this->table_pages_admin)->insert([
                ['id' => 1, 'name' => $this->page_site, 'title' => 'page_site', 'fa_fa_icon' => 'fa-university', 'weight' => 10, 'adm_visible' => 1, 'client_visible' => 0, 'constructor_visible' => 0],
                ['id' => 2, 'name' => $this->page_tables_settings, 'title' => 'База данных', 'fa fa_fa_icon' => 'fa-university', 'weight' => 20, 'adm_visible' => 1, 'client_visible' => 0, 'constructor_visible' => 0],
                ['id' => 3, 'name' => 'pages', 'title' => 'Страницы админки', 'fa fa_fa_icon' => 'fa-files-o', 'weight' => 30, 'adm_visible' => 1, 'client_visible' => 0, 'constructor_visible' => 1]
            ]);
        }

        if (Schema::hasTable($this->table_pages_rights_settings) == false) {
            Schema::create($this->table_pages_rights_settings, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->integer('page_id')->unsigned();
                $table->foreign('page_id')->references('id')->on($this->table_pages_admin)
                    ->onDelete('cascade')->onUpdate('cascade');
                $table->integer('right_group');
            });
        }
    }

    private function createIfNotFoundAdmSelectBox()
    {

        if (Schema::hasTable($this->table_select_groups) == false) {
            Schema::create($this->table_select_groups, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('name', 100);
                $table->string('title', 100);

            });

            DB::table($this->table_select_groups)->insert([
                ['name' => 'cell_display_type', 'title' => 'Типы отображения'],
                ['name' => 'rights', 'title' => 'Права доступа'],
                ['name' => 'table_color', 'title' => 'Цвет Таблицы'],
                ['name' => 'table_count_records', 'title' => 'Количество строк'],
                ['name' => 'column_filter_type', 'title' => 'Тип фильтра'],
                ['name' => 'selectors_id', 'title' => 'Группы Селектбоксов'],
                ['name' => 'user_status', 'title' => 'Статус пользователя'],
                ['name' => 'message_status', 'title' => 'Статус сообщения'],
                ['name' => 'forum_status', 'title' => 'Статус Форума'],
            ]);
        }

        if (Schema::hasTable($this->table_select_items) == false) {
            Schema::create($this->table_select_items, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->integer('group_id')->unsigned();
                $table->foreign('group_id')->references('id')->on($this->table_select_groups)
                    ->onDelete('cascade')->onUpdate('cascade');
                $table->string('name', 100);
                $table->string('title', 100);
                $table->string('value', 100);
            });

            DB::table($this->table_select_items)->insert([
                ['group_id' => 1, 'name' => 'text', 'title' => 'Текст', 'value' => 'text'],
                ['group_id' => 1, 'name' => 'image', 'title' => 'Картинка', 'value' => 'image'],
                ['group_id' => 1, 'name' => 'article', 'title' => 'Статья', 'value' => 'article'],
                ['group_id' => 1, 'name' => 'checkbox', 'title' => 'Чекбокс', 'value' => 'checkbox'],
                ['group_id' => 1, 'name' => 'icon', 'title' => 'Иконка', 'value' => 'icon'],
                ['group_id' => 1, 'name' => 'tiny', 'title' => 'Текстовый редактор', 'value' => 'tiny'],
                ['group_id' => 1, 'name' => 'selectbox', 'title' => 'Селектбокс', 'value' => 'selectbox'],
                ['group_id' => 1, 'name' => 'date', 'title' => 'Дата', 'value' => 'date'],
                ['group_id' => 1, 'name' => 'selectbox_column', 'title' => 'Селектбокс "колонка"', 'value' => 'selectbox_column'],
                ['group_id' => 2, 'name' => 'admin', 'title' => 'Администратор', 'value' => '1'],
                ['group_id' => 2, 'name' => 'manager', 'title' => 'Менеджер', 'value' => '2'],
                ['group_id' => 2, 'name' => 'user', 'title' => 'Юзер', 'value' => '3'],
                ['group_id' => 3, 'name' => 'default', 'title' => 'Серый', 'value' => 'default'],
                ['group_id' => 3, 'name' => 'green', 'title' => 'Зеленый', 'value' => 'green'],
                ['group_id' => 3, 'name' => 'blue', 'title' => 'Голубой', 'value' => 'blue'],
                ['group_id' => 3, 'name' => 'red', 'title' => 'Красный', 'value' => 'red'],
                ['group_id' => 3, 'name' => 'orange', 'title' => 'Оранжевый', 'value' => 'orange'],
                ['group_id' => 3, 'name' => 'purple', 'title' => 'Фиолетовый', 'value' => 'purple'],
                ['group_id' => 4, 'name' => '10', 'title' => '10 строк', 'value' => '10'],
                ['group_id' => 4, 'name' => '25', 'title' => '25 строк', 'value' => '25'],
                ['group_id' => 4, 'name' => '50', 'title' => '50 строк', 'value' => '50'],
                ['group_id' => 4, 'name' => '100', 'title' => '100 строк', 'value' => '100'],
                ['group_id' => 5, 'name' => 'none', 'title' => 'Нет', 'value' => 'none'],
                ['group_id' => 5, 'name' => 'select_all', 'title' => 'Выбрать из', 'value' => 'select_all'],
                ['group_id' => 5, 'name' => 'range', 'title' => 'Диапазон', 'value' => 'range'],
                ['group_id' => 5, 'name' => 'range_date', 'title' => 'Диапазон дат', 'value' => 'range_date'],
                ['group_id' => 6, 'name' => 'cell_display_type', 'title' => 'Типы отображения', 'value' => '1'],
                ['group_id' => 6, 'name' => 'rights', 'title' => 'Типы отображения', 'value' => '2'],
                ['group_id' => 6, 'name' => 'table_color', 'title' => 'Типы отображения', 'value' => '3'],
                ['group_id' => 6, 'name' => 'table_count_records', 'title' => 'Типы отображения', 'value' => '4'],
                ['group_id' => 6, 'name' => 'column_filter_type', 'title' => 'Типы отображения', 'value' => '5'],
                ['group_id' => 6, 'name' => 'selectors_id', 'title' => 'Типы отображения', 'value' => '6'],
                ['group_id' => 7, 'name' => 'active', 'title' => 'Активный', 'value' => '0'],
                ['group_id' => 7, 'name' => 'blocked', 'title' => 'Заблокирован', 'value' => '1'],
                ['group_id' => 7, 'name' => 'verified', 'title' => 'Верифицированный', 'value' => '2'],
                ['group_id' => 8, 'name' => 'new', 'title' => 'Новое', 'value' => '0'],
                ['group_id' => 8, 'name' => 'in_process', 'title' => 'В обработке', 'value' => '1'],
                ['group_id' => 8, 'name' => 'problem', 'title' => 'Проблема', 'value' => '2'],
                ['group_id' => 8, 'name' => 'closed', 'title' => 'Закрытое', 'value' => '3'],
                ['group_id' => 9, 'name' => 'new', 'title' => 'Новый', 'value' => '0'],
                ['group_id' => 9, 'name' => 'approved', 'title' => 'В обработке', 'value' => '1'],
                ['group_id' => 9, 'name' => 'system', 'title' => 'Системный', 'value' => '2'],
                ['group_id' => 9, 'name' => 'disabled', 'title' => 'Неактивный', 'value' => '3'],
            ]);
        }
    }

    private function createIfNotFoundAdmUsers()
    {
        if (Schema::hasTable($this->table_users_settings) == false) {
            Schema::create($this->table_users_settings, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('login', 100);
                $table->string('email', 100)->default('');
                $table->string('password', 100);
                $table->string('right', 100);
            });

            DB::table($this->table_users_settings)->insert([
                ['id' => 1, 'login' => 'krut', 'password' => '111', 'right' => 10]
            ]);
        }


    }


    private function createIfNotFoundBlocksSettings()
    {
        if (Schema::hasTable($this->table_blocks_settings) == false) {

            Schema::create($this->table_blocks_settings, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->integer('block_id')->unsigned();
                $table->foreign('block_id')->references('id')->on($this->table_blocks_settings)
                    ->onDelete('cascade')->onUpdate('cascade');
                $table->integer('page_id')->unsigned();
                $table->foreign('page_id')->references('id')->on($this->table_pages_admin)
                    ->onDelete('cascade')->onUpdate('cascade');
                $table->string('block_type', 100)->default('block_table_b');
                $table->string('block_title', 100)->default('Название Блока');
                $table->string('page_view', 100)->default('lg');
                $table->integer('block_x')->default('0');
                $table->integer('block_y')->default('100');
                $table->integer('block_height')->default('5');
                $table->integer('block_width')->default('12');
            });

            DB::table($this->table_blocks_settings)->insert([
                ['page_id' => 1, 'block_id' => 1, 'block_type' => 'corner', 'block_title' => 'corner',
                    'page_view' => '', 'block_x' => 0, 'block_y' => 0, 'block_height' => 0, 'block_width' => 0]
            ]);

        }
    }

    private function createIfNotFoundDbTablesSettings()
    {
        if (Schema::hasTable($this->table_tables_settings) == false) {

            Schema::create($this->table_tables_settings, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->integer('block_id')->unsigned();
                $table->foreign('block_id')->references('id')->on($this->table_blocks_settings)
                    ->onDelete('cascade')->onUpdate('cascade');
                $table->string('table_purpose', 50)->default('main');
                $table->string('table_name', 255);
                $table->string('table_title', 255)->default('');
                $table->tinyInteger('table_visible')->default('0');
                $table->tinyInteger('table_row_delete')->default('0');
                $table->tinyInteger('table_row_editable')->default('0');
                $table->tinyInteger('table_row_add')->default('0');
                $table->string('table_color', 255)->default('color');
                $table->string('table_sort_column', 255)->default('id');
                $table->integer('table_column_sort_direction')->default('0');
                $table->string('table_main_sort_id', 50)->default('0');
                $table->integer('table_count_records')->default('10');
                $table->string('table_join_main', 255)->default('');
                $table->string('table_column_join', 255)->default('');
                $table->string('table_column_join_main', 255)->default('');
            });

        }
    }

    private function createIfNotFoundDbColumnsSettings()
    {
        if (Schema::hasTable($this->table_columns_settings) == false) {
            Schema::create($this->table_columns_settings, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');

                $table->integer('table_id')->unsigned();
                $table->foreign('table_id')->references('id')->on($this->table_tables_settings)
                    ->onDelete('cascade')->onUpdate('cascade');
                $table->string('column_name', 255);
                $table->string('column_title', 255)->default('');
                $table->tinyInteger('column_visible')->default('0');
                $table->tinyInteger('column_editable')->default('0');
                $table->tinyInteger('column_inRow_visible')->default('0');
                $table->tinyInteger('column_inRow_editable')->default('0');
                $table->string('column_display_type', 50)->default('text');
                $table->string('column_filter_type', 50)->default('none');
                $table->tinyInteger('column_client_db_filter')->default('0');
                $table->tinyInteger('column_client_session_filter')->default('0');

            });

        }
    }

    private function createIfNotFoundDbColumnsTypesSettings()
    {
        if (Schema::hasTable($this->table_columns_types_settings) == false) {
            Schema::create($this->table_columns_types_settings, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');

                $table->integer('column_id')->unsigned();
                $table->foreign('column_id')->references('id')->on($this->table_columns_settings)
                    ->onDelete('cascade')->onUpdate('cascade');

                $table->string('column_type_setting', 255);
                $table->string('column_type_value', 255);
            });

        }
    }

    private function createIfNotFoundDbColumnsFilterSettings()
    {
        if (Schema::hasTable($this->table_columns_filter_settings) == false) {
            Schema::create($this->table_columns_filter_settings, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');

                $table->integer('column_id')->unsigned();
                $table->foreign('column_id')->references('id')->on($this->table_columns_settings)
                    ->onDelete('cascade')->onUpdate('cascade');

                $table->text('column_filter_settings')->nullable();
                $table->text('column_filter_settings_two')->nullable();

            });
        }
    }

   /* private function createIfNotFoundAdmPagesRights()
    {
        if (Schema::hasTable($this->table_columns_settings) == false) {
            Schema::create($this->table_columns_settings, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');

                $table->integer('table_id')->unsigned();
                $table->foreign('table_id')->references('id')->on($this->table_tables_settings)
                    ->onDelete('cascade')->onUpdate('cascade');

                $table->string('column_name', 255);
                $table->string('column_title', 255)->default('');
                $table->tinyInteger('column_visible')->default('1');
                $table->tinyInteger('column_editable')->default('0');
                $table->tinyInteger('column_inRow_visible')->default('1');
                $table->tinyInteger('column_inRow_editable')->default('1');
                $table->string('column_display_type', 50)->default('text');
            });

        }
    }*/

   public function createIfNotFoundAdminTables(){
       if (Schema::hasTable($this->table_blocks_settings) == false) {

           Schema::create($this->table_blocks_settings, function (Blueprint $table) {
               $table->engine = 'InnoDB';
               $table->increments('id');
               $table->integer('block_id')->unsigned();
               $table->foreign('block_id')->references('id')->on($this->table_blocks_settings)
                   ->onDelete('cascade')->onUpdate('cascade');
               $table->integer('page_id')->unsigned();
               $table->foreign('page_id')->references('id')->on($this->table_pages_admin)
                   ->onDelete('cascade')->onUpdate('cascade');
               $table->string('block_type', 100)->default('block_table_b');
               $table->string('block_title', 100)->default('Название Блока');
               $table->string('page_view', 100)->default('lg');
               $table->integer('block_x')->default('0');
               $table->integer('block_y')->default('100');
               $table->integer('block_height')->default('5');
               $table->integer('block_width')->default('12');
           });

           DB::table($this->table_blocks_settings)->insert([
               ['page_id' => 1, 'block_id' => 1, 'block_type' => 'corner', 'block_title' => 'corner',
                   'page_view' => '', 'block_x' => 0, 'block_y' => 0, 'block_height' => 0, 'block_width' => 0]
           ]);

       }

   }
}


