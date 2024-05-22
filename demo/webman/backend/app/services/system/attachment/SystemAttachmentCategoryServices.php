<?php
// +----------------------------------------------------------------------
// | CRMEB [ CRMEB赋能开发者，助力企业发展 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2023 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEB并不是自由软件，未经许可不能去掉CRMEB相关版权
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------
declare (strict_types=1);

namespace app\services\system\attachment;

use app\services\BaseServices;
use app\dao\system\attachment\SystemAttachmentCategoryDao;
use phoenix\exceptions\AdminException;


/**
 * SystemAttachmentCategoryServices
 *
 * @author Mr.April
 * @since  1.0
 * @method get($id) 获取一条数据
 * @method count($where) 获取条件下数据总数
 */
class SystemAttachmentCategoryServices extends BaseServices
{

    /**
     * SystemAttachmentCategoryServices constructor.
     *
     * @param SystemAttachmentCategoryDao $dao
     */
    public function __construct(SystemAttachmentCategoryDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * 获取分类列表
     *
     * @param array $where
     *
     * @return array
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getAll(array $where): array
    {
        $list = $this->dao->getList($where);
        if ($where['all'] == 1) {
            $list = $this->tidyMenuTier($list);
        } else {
            foreach ($list as &$item) {
                $item['title'] = $item['name'];
                if ($where['name'] == '' && $this->dao->count(['pid' => $item['id']])) {
                    $item['loading']  = false;
                    $item['children'] = [];
                }
            }
        }
        return compact('list');
    }

    /**
     * 格式化列表
     *
     * @param        $menusList
     * @param string $pid
     * @param array  $navList
     *
     * @return array
     */
    public function tidyMenuTier($menusList, string $pid = '-1', array $navList = []): array
    {
        foreach ($menusList as $k => $menu) {
            $menu['title'] = $menu['name'];
            if ($menu['pid'] == $pid) {
                unset($menusList[$k]);
                $menu['children'] = $this->tidyMenuTier($menusList, $menu['id']);
                if (count($menu['children'])) {
                    $menu['expand'] = true;
                } else {
                    unset($menu['children']);
                }
                $navList[] = $menu;
            }
        }
        return $navList;
    }

    /**
     * 保存新建的资源
     *
     * @param array $data
     *
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function save(array $data): mixed
    {
        if ($this->dao->getOne(['name' => $data['name']])) {
            throw new AdminException(400101);
        }
        $res = $this->dao->save($data);
        if (!$res) throw new AdminException(100022);
        return $res;
    }

    /**
     * 保存修改的资源
     *
     * @param string $id
     * @param array  $data
     *
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function update(string $id, array $data)
    {
        $attachment = $this->dao->getOne(['name' => $data['name']]);
        if ($attachment && $attachment['id'] != $id) {
            throw new AdminException(400101);
        }
        $res = $this->dao->update($id, $data);
        if (!$res) {
            throw new AdminException(100007);
        }
    }

    /**
     * 删除分类
     *
     * @param string $id
     */
    public function del(string $id): void
    {
        $count = $this->dao->getCount(['pid' => $id]);
        if ($count) {
            throw new AdminException(400102);
        } else {
            $res = $this->dao->delete($id);
            if (!$res) throw new AdminException(400102);
        }
    }

    /**
     * 获取一条数据
     *
     * @param $where
     *
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getOne($where)
    {
        return $this->dao->getOne($where);
    }
}
