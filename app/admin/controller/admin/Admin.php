<?php
declare (strict_types=1);

namespace app\admin\controller\admin;

use think\facade\Request;
use think\facade\Db;
use app\common\service\AdminAdmin as S;
use app\common\model\AdminAdmin as M;
use think\response\Json;

class Admin extends \app\admin\controller\Base
{
    protected array $middleware = ['AdminCheck', 'AdminPermission'];

    // 列表
    public function index()
    {
        if (Request::isAjax()) {
            return $this->getJson(M::getList());
        }
        return $this->fetch();
    }

    // 添加
    public function add()
    {
        if (Request::isAjax()) {
            return $this->getJson(S::goAdd(Request::post()));
        }
        return $this->fetch();
    }

    // 编辑
    public function edit($id)
    {
        if (Request::isAjax()) {
            return $this->getJson(S::goEdit(Request::post(), $id));

        }
        return $this->fetch('', ['model' => M::find($id)]);
    }

    // 状态
    public function status(): Json
    {
        return $this->getJson(S::goStatus(Request::post('status'), Request::post('id')));
    }

    // 删除
    public function remove(): ?Json
    {
        $id = input('id');
        return $this->getJson(S::goRemove($id));
    }

    // 批量删除
    public function batchRemove(): ?Json
    {
        return $this->getJson(S::goBatchRemove(Request::post('ids')));
    }

    // 用户分配角色
    public function role(): Json|string|null
    {
        $id = input('id');
        if (Request::isAjax()) {
            return $this->getJson(S::goRole(Request::post('roles'), $id));
        }
        return $this->fetch('', M::getRole($id));
    }

    // 用户分配直接权限
    public function permission(): Json|string|null
    {
        $id = input('id');
        if (Request::isAjax()) {
            return $this->getJson(S::goPermission(Request::post('permissions'), $id));
        }
        return $this->fetch('', M::getPermission($id));
    }

    // 回收站
    public function recycle(): Json|string|null
    {
        if (Request::isAjax()) {
            return $this->getJson(S::goRecycle());
        }
        return $this->fetch();
    }

    // 用户日志
    public function log(): Json|string|null
    {
        if (Request::isAjax()) {
            return $this->getJson(M::getLog());
        }
        return $this->fetch();
    }

    // 清空日志
    public function removeLog(): ?Json
    {
        $desc = Db::name('admin_admin_log')->order('id', 'desc')->find();
        if ($desc) {
            Db::name('admin_admin_log')->where('id', '<', $desc['id'])->delete(true);
        } else {
            Db::name('admin_admin_log')->delete(true);
        }
        return $this->getJson();
    }

}
