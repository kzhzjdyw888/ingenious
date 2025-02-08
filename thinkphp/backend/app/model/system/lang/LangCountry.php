<?php

namespace app\model\system\lang;

use phoenix\basic\BaseModel;
use phoenix\traits\ModelTrait;
use phoenix\utils\Str;
use think\model\concern\SoftDelete;

class LangCountry extends BaseModel
{
    use ModelTrait;

    /**
     * 数据表主键
     *
     * @var string
     */
    protected $pk = 'id';

    /**
     * 模型名称
     *
     * @var string
     */
    protected $name = 'lang_country';

    /**
     * 新增自动创建字符串id
     *
     * @param $model
     *
     * @return void
     */
    protected static function onBeforeInsert($model): void
    {
        $uuid                = !empty($model->{$model->pk}) ? $model->{$model->pk} : Str::uuid();
        $model->{$model->pk} = $uuid;
    }

    /**
     * type_id搜索器
     *
     * @param $query
     * @param $value
     */
    public function searchTypeIdAttr($query, $value)
    {
        if ($value !== '') $query->where('type_id', $value);
    }

    /**
     * code/name搜索器
     *
     * @param $query
     * @param $value
     */
    public function searchKeywordAttr($query, $value)
    {
        if ($value !== '') $query->where('name|code', 'like', '%' . $value . '%');
    }
}
