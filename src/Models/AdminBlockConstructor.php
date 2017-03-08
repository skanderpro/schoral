<?php

namespace Qubants\Scholar\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use DB;

class AdminBlockConstructor extends Model
{
    public $modelConfig;
    //table:
    public $table_blocks_settings;

    public function __construct()
    {
        $this->modelConfig = new AdmConfig();

        //table:
        $this->table_blocks_settings = $this->modelConfig->table_blocks_settings;
    }


    public function createBlock($block_settings, $tables_settings, $columns_settings)
    {
        $model_BlocksSettings = new AdmBlocksSettingsModel();
        $model_TablesSettings = new DbTablesSettings();
        $model_ColumnsSettings = new DbColumnsSettings();

        $block_id = $model_BlocksSettings->createBlockSettings($block_settings);
        $tables_settings['block_id'] = $block_id;
        $table_id = $model_TablesSettings->createTableUnit($tables_settings);//надо доработать для нескольких таблиц

        $model_ColumnsSettings->createColumnsSettings($columns_settings, $table_id);
    }

    public function updateBlock($block_id, $block_type, $block_title, $block_configuration, $content_source)
    {
        DB::table($this->table_blocks_settings)->where('id', $block_id)->update(
            ['block_type' => $block_type, 'block_settings' => $block_configuration, 'content_source' => $content_source,
                'block_title' => $block_title]
        );
    }

    public function getPageBlocksSettings($page_id, $page_view)
    {
        $blocks = DB::table($this->table_blocks_settings)->where([['page_id', '=', $page_id], ['page_view', '=', $page_view]])->get();
        $blocks_data = [];
        foreach ($blocks as $block_configuration) {
            $block_content = $this->getBlockContent($block_configuration->block_type, $block_configuration->content_source);
            $blocks_data[] = array('block_configuration' => $block_configuration, 'block_content' => $block_content);
        }
        return $blocks_data;
    }

    public function getBlockSettingsById($block_id)
    {
        return DB::table($this->table_blocks_settings)->where('id', $block_id)->first();
    }

    public function saveBlockPosition($page_id, $page_view, $blocks)
    {
        if (is_array($blocks)) {
            foreach ($blocks as $block) {
                DB::table($this->table_blocks_settings)->where([['page_id', '=', $page_id], ['page_view', '=', $page_view], ['id', '=', $block->id]])->update([
                    'block_x' => $block->x,
                    'block_y' => $block->y,
                    'block_height' => $block->height,
                    'block_width' => $block->width
                ]);

            }
            return true;
        } else {
            return false;
        }
    }

    public function deleteBlock($page_id, $current_view, $block_id)
    {
        DB::table($this->table_blocks_settings)->where([['page_id', '=', $page_id], ['page_view', '=', $current_view], ['id', '=', $block_id]])->delete();
    }

    public function getBlockContent($block_type, $content_source)
    {
        $dbTablesSettings = new DbTablesSettings();
        $block_content = '';
        if ($block_type == 'block_table_b') {
            $content_source = json_decode($content_source);
            if (isset($content_source->table_id)) {
                $table_id = $content_source->table_id;
                $table_name = $dbTablesSettings->getTableNameByTableId($table_id);
                if (isset($content_source->count_records) && $content_source->count_records != '') {
                    $count_records = $content_source->count_records;
                } else {
                    $count_records = 10;
                }
                $block_content = DB::table($table_name)->limit($count_records)->get();
            }
        }
        return $block_content;
    }

}


