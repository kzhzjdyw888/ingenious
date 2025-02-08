<?php

namespace app\services\system\lang;

use app\dao\system\lang\LangTypeDao;
use app\services\BaseServices;
use phoenix\exceptions\AdminException;
use phoenix\services\FormBuilder as Form;
use FormBuilder\Exception\FormBuilderException;
use think\facade\Route as Url;

class LangTypeServices extends BaseServices
{
    /**
     * @param LangTypeDao $dao
     */
    public function __construct(LangTypeDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * 获取语言类型列表
     *
     * @param array $where
     *
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException|\ReflectionException
     */
    public function langTypeList(array $where = []): array
    {
        [$page, $limit] = $this->getPageValue();
        $list  = $this->dao->selectList($where, '*', $page, $limit, '', [], true);
        $count = $this->dao->count($where);
        return compact('list', 'count');
    }

    /**
     * 保存语言类型
     *
     * @param array $data
     *
     * @return bool
     */
    public function langTypeSave(array $data): bool
    {
        if ($data['id']) {
            $this->dao->update($data['id'], $data);
            $id = $data['id'];
        } else {
            unset($data['id']);
            $res = $this->dao->save($data);
            if ($res) {
                //同步语言
                /** @var LangCodeServices $codeServices */
                $codeServices = app()->make(LangCodeServices::class);
                $list         = $codeServices->selectList(['type_id' => 1])->toArray();
                foreach ($list as $key => $item) {
                    unset($list[$key]['id']);
                    $list[$key]['type_id'] = $res->id;
                }
                $codeServices->saveAll($list);
            } else {
                throw new AdminException(100006);
            }
            $id = $res->id;
        }
        //设置默认
        if ($data['is_default'] == 1) $this->dao->update([['id', '<>', $id]], ['is_default' => 0]);
        $this->setDefaultLangName();
        return true;
    }

    /**
     * @author 等风来
     * @email  136327134@qq.com
     * @date   2023/2/10
     */
    public function setDefaultLangName()
    {
        $fileName = $this->dao->value(['is_default' => 1], 'file_name');
        $this->cacheDriver()->set('range_name', $fileName);
        app()->make(LangCodeServices::class)->cacheDriver()->clear();
    }

    /**
     * 修改语言类型状态
     *
     * @param $id
     * @param $status
     *
     * @return bool
     */
    public function langTypeStatus($id, $status): bool
    {
        $res = $this->dao->update(['id' => $id], ['status' => $status]);
        if (!$res) throw new AdminException(100015);
        $this->setDefaultLangName();
        return true;
    }

    /**
     * 删除语言类型
     *
     * @param string $id
     *
     * @return bool
     */
    public function langTypeDel(string $id='0'): bool
    {
        $this->dao->update(['id' => $id], ['delete_time' => time()]);
        /** @var LangCountryServices $countryServices */
        $countryServices = app()->make(LangCountryServices::class);
        $countryServices->update(['type_id' => $id], ['type_id' => 0]);
        /** @var LangCodeServices $codeServices */
        $codeServices = app()->make(LangCodeServices::class);
        $codeServices->delete(['type_id' => $id]);
        $this->setDefaultLangName();
        return true;
    }
}
