<?php

namespace app\services\system\lang;

use app\dao\system\lang\LangCountryDao;
use app\services\BaseServices;
use phoenix\exceptions\AdminException;
use phoenix\services\FormBuilder as Form;
use think\facade\Route as Url;

class LangCountryServices extends BaseServices
{
    /**
     * @param LangCountryDao $dao
     */
    public function __construct(LangCountryDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * 地区语言列表
     *
     * @param array $where
     *
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException|\ReflectionException
     */
    public function LangCountryList(array $where = []): array
    {
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->selectList($where, '*', $page, $limit, 'id desc', [], true)->toArray();
        /** @var LangTypeServices $langTypeServices */
        $langTypeServices = app()->make(LangTypeServices::class);
        $langTypeList     = $langTypeServices->getColumn([], 'language_name,file_name,id', 'id');
        foreach ($list as &$item) {
            if (isset($langTypeList[$item['type_id']])) {
                $item['link_lang'] = $langTypeList[$item['type_id']]['language_name'] . '(' . $langTypeList[$item['type_id']]['file_name'] . ')';
            } else {
                $item['link_lang'] = '';
            }
        }
        $count = $this->dao->count($where);
        return compact('list', 'count');
    }

    /**
     * 保存语言地区
     *
     * @param $id
     * @param $data
     *
     * @return bool
     */
    public function LangCountrySave($id, $data): bool
    {
        if ($id) {
            $res = $this->dao->update(['id' => $id], $data);
        } else {
            $res = $this->dao->save($data);
        }
        if (!$res) throw new AdminException(100007);
        $this->cacheDriver()->clear();
        return true;
    }

    /**
     * 删除语言地区
     *
     * @param $id
     *
     * @return bool
     */
    public function langCountryDel($id): bool
    {
        $res = $this->dao->delete($id);
        if (!$res) throw new AdminException(100008);
        $this->cacheDriver()->clear();
        return true;
    }
}
