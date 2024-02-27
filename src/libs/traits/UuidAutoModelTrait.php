<?php

namespace ingenious\libs\traits;

use Ramsey\Uuid\Uuid;

trait UuidAutoModelTrait
{

    /**
     * 新增自动创建字符串id
     *
     * @param $model
     *
     * @return void
     */
    protected static function onBeforeInsert($model): void
    {
        $uuid                = !empty($model->{$model->pk}) ? $model->{$model->pk} : Uuid::uuid4()->toString();
        $model->{$model->pk} = $uuid;
    }

}
