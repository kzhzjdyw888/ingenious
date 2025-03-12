<?php
declare (strict_types=1);

namespace app\admin\controller;

use app\common\util\Json;
use app\common\util\Tree;

class Base extends \app\BaseController
{

    protected $service;

    /**
     * 格式化树
     *
     * @param $items
     *
     * @return \think\Response
     */
    protected function formatTree($items): \think\Response
    {
        $format_items = [];
        foreach ($items as $item) {
            $format_items[] = [
                'name'  => $item->title ?? $item->name ?? $item->id,
                'value' => (string)$item->id,
                'id'    => $item->id,
                'pid'   => $item->pid,
            ];
        }
        $tree = new Tree($format_items);
        return Json::success('ok', $tree->getTree());
    }

    /**
     * 格式化表格树
     *
     * @param $data
     * @param $total
     *
     * @return \think\Response
     */
    protected function formatTableTree($data, $total): \think\Response
    {
        $tree  = new Tree($data->toArray());
        $items = $tree->getTree();
        return Json::success('ok', $items);
    }

    /**
     * 格式化下拉列表
     *
     * @param $items
     *
     * @return \think\Response
     */
    protected function formatSelect($items): \think\Response
    {
        $formatted_items = [];
        foreach ($items as $item) {
            $formatted_items[] = [
                'label' => $item->title ?? $item->name ?? $item->real_name ?? $item->id,
                'value' => $item->id,
            ];
        }
        return Json::success('ok', $formatted_items);
    }

    /**
     * 通用格式化
     *
     * @param $items
     * @param $total
     *
     * @return \think\Response
     */
    protected function formatNormal($items, $total): \think\Response
    {
        return Json::success('ok', compact('items', 'total'));
    }
}
