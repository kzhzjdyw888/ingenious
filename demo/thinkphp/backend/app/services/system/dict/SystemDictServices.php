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

namespace app\services\system\dict;

use app\dao\system\dict\SystemDictDao;
use app\services\BaseServices;
use phoenix\exceptions\AdminException;

class SystemDictServices extends BaseServices
{

    /**
     * 构造方法
     * SystemLogServices constructor.
     *
     * @param \app\dao\system\dict\SystemDictDao $dao
     */
    public function __construct(SystemDictDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * 获取字典选项列表
     *
     * @param array $where
     *
     * @return array
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getDictList(array $where): array
    {
        [$page, $limit] = $this->getPageValue();
        $where['name'] = $this->dictNameToOptionName($where['name']);
        $list          = $this->dao->selectList($where, '*', $page, $limit, 'create_time asc', [], true);
        //去除字典前缀
        foreach ($list as $key => $value) {
            $position           = strpos($value['name'], "dict_") + 5;
            $list[$key]['name'] = substr($value['name'], $position);
        }
        $count = $this->dao->count($where);
        return compact('list', 'count');
    }

    /**
     * 字典保存
     *
     * @param string $name
     * @param        $values
     *
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function saveDict(string $name, $values): mixed
    {
        if (!preg_match('/[a-zA-Z]/', $name)) {
            throw new AdminException('字典名只能包含字母');
        }
        $option_name = $this->dictNameToOptionName($name);
        if ($this->dao->getName($option_name)) {
            return app('json')->fail(400188);
        }
        $format_values = $this->filterValue($values);
        $data          = [
            'name'  => $option_name,
            'value' => json_encode($format_values),
        ];
        return $this->save($data);
    }

    /**
     * 字典名到option名转换
     *
     * @param string $name
     *
     * @return string
     */
    public function dictNameToOptionName(string $name): string
    {
        return "dict_$name";
    }

    /**
     * 过滤值
     *
     * @param array $values
     *
     * @return array
     * @throws BusinessException
     */
    public function filterValue(array $values): array
    {
        $format_values = [];
        foreach ($values as $item) {
            if (!isset($item['value']) || !isset($item['name'])) {
                throw new AdminException('字典格式错误');
            }
            $format_values[] = ['value' => $item['value'], 'name' => $item['name']];
        }
        return $format_values;
    }

    /**
     * 字典详情
     *
     * @param $id
     *
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function read($id): array
    {
        $data        = $this->dao->get($id);
        $position    = strpos($data->name, "dict_") + 5;
        $data->name  = substr($data->name, $position);
        $data->value = json_decode($data->value, 1);
        return $data->getData();
    }

    /**
     * 更新字典
     *
     * @param $id
     * @param $data
     *
     * @return mixed
     */
    public function updateDict($id, $data): mixed
    {
        if (!preg_match('/[a-zA-Z]/', $data['name'])) {
            throw new AdminException('字典名只能包含字母');
        }
        $option_name = $this->dictNameToOptionName($data['name']);
        $where       = [['name', '=', $option_name], ['id', '<>', $id]];
        if ($this->dao->getCount($where)) {
            throw new AdminException(400188);
        }

        $format_values = $this->filterValue($data['value']);
        $upd           = [
            'name'  => $option_name,
            'value' => json_encode($format_values),
        ];
        return $this->update($id, $upd);
    }
}
