<?php
declare (strict_types=1);

namespace app\admin\controller\admin;

use think\facade\Request;
use app\common\service\AdminPermission as S;
use app\common\model\AdminPermission as M;

class Permission extends \app\admin\controller\Base
{
    protected array $middleware = ['AdminCheck', 'AdminPermission'];

    // 列表
    public function index(): \think\response\Json|string|null
    {
        if (Request::isAjax()) {
            return $this->getJson(M::getList());
        }
        return $this->fetch();
    }

    // 添加
    public function add(): \think\response\Json|string|null
    {
        if (Request::isAjax()) {
            return $this->getJson(S::goAdd(Request::post()));
        }

//        $options = $this->generateTreeOptions(get_tree(M::order('sort', 'asc')->select()->toArray()));
        return $this->fetch('', [
            'permissions' => get_tree(M::order('sort', 'asc')->select()->toArray()),
        ]);
    }

    // 编辑
    public function edit($id): \think\response\Json|string|null
    {
        if (Request::isAjax()) {
            return $this->getJson(S::goEdit(Request::post(), $id));

        }
        return $this->fetch('', M::getFind($id));
    }

    // 状态
    public function status(): ?\think\response\Json
    {
        $id = input('id');
        return $this->getJson(S::goStatus(Request::post('status'), $id));
    }

    // 删除
    public function remove(): ?\think\response\Json
    {
        $id = input('id');
        return $this->getJson(S::goRemove($id));
    }

    /**
     * 获取权限
     *
     * @param Request $request
     *
     * @return \think\response\Json|null
     */
    public function permission(Request $request): ?\think\response\Json
    {

        return $this->getJson(['data' => ['*']]);
    }

    private function generateTreeOptions($permissions, $currentLevel = 0, $parentId = 0)
    {
        $options = '';
        foreach ($permissions as $permission) {
            // 检查当前权限项的父ID是否与传入的父ID匹配
            if ($permission['pid'] == $parentId) {
                // 生成缩进（您可以根据需要调整缩进方式）
                $indent = str_repeat('&nbsp;&nbsp;┗━━', $currentLevel);
                // 生成<option>标签
                $options .= "<option value='{$permission['id']}'>{$indent}{$permission['title']}</option>";

                // 如果当前权限项有子项，则递归调用函数
                if (isset($permission['children']) && is_array($permission['children']) && count($permission['children']) > 0) {
                    $options .= $this->generateTreeOptions($permission['children'], $currentLevel + 1, $permission['id']);
                }
            }
        }
        return $options;
    }

}

