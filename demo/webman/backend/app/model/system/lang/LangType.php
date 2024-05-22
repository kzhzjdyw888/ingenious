<?php

namespace app\model\system\lang;

use app\common\traits\JwtAuthModelTrait;
use app\common\traits\ModelTrait;
use app\model\BaseModel;
use think\model\concern\SoftDelete;

class LangType extends BaseModel
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
    protected $name = 'lang_type';

    use SoftDelete;

    protected $autoWriteTimestamp = true;
    protected string $deleteTime = 'delete_time';

    protected $type = [
        'delete_time' => 'timestamp:Y-m-d H:i:s',
    ];

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
     * is_del搜索器
     *
     * @param $query
     * @param $value
     */
    public function searchIsDelAttr($query, $value)
    {
        if ($value !== '') $query->where('is_del', $value);
    }

    /**
     * status搜索器
     *
     * @param $query
     * @param $value
     */
    public function searchStatusAttr($query, $value)
    {
        if ($value !== '') $query->where('status', $value);
    }

    /**
     * language-name搜索器
     *
     * @param $query
     * @param $value
     */
    public function searchLanguageNameAttr($query, $value)
    {
        if ($value !== '') $query->where('language_name', 'like', $value . '%');
    }

    /**
     * file_name 搜索器
     *
     * @param $query
     * @param $value
     */
    public function searchFileNameAttr($query, $value)
    {
        if ($value !== '') $query->where('file_name', $value);
    }

}
