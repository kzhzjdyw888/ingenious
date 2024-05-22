<?php
namespace app\model\system\setting;


use phoenix\basic\BaseModel;
use phoenix\traits\ModelTrait;
use think\db\Query;

class SystemConfig extends BaseModel
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
    protected $name = 'system_options';

    //软删除，查询时会自动加上 xxx IS NULL
    use \think\model\concern\SoftDelete;


    protected string $deleteTime = 'delete_time';

    /**
     * 字典名称搜索器（含前缀）
     *
     * @param \think\db\Query $query
     * @param string          $value
     * @param                 $data
     */
    public function searchNameLikeAttr(Query $query, string $value, $data)
    {
        if ($value) {
            $query->whereLike('name', $value . '%');

        }
    }
}
