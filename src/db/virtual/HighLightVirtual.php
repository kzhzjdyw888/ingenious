<?php
/**
 *+------------------
 * Lflow
 *+------------------
 * Copyright (c) 2023~2030 gitee.com/liu_guan_qing All rights reserved.本版权不可删除，侵权必究
 *+------------------
 * Author: Mr.April(405784684@qq.com)
 *+------------------
 */

namespace ingenious\db\virtual;

use ingenious\libs\base\BaseModel;
use think\model\concern\Virtual;

class HighLightVirtual extends BaseModel
{
    use Virtual;

    private array $history_node_names = [];
    private array $history_edge_names = [];
    private array $active_node_names = [];

    public function contains(string $propertyName, string $value): bool
    {
        $property = $this->{$propertyName};
        if (empty($property)) {
            return false;
        }
        return in_array($value, $property);
    }

    public function add(string $propertyName, string $value): self
    {
        $this->{$propertyName}[] = $value;
        return $this;
    }

    public function get(string $propertyName)
    {
        if (property_exists($this, $propertyName)) {
            return $this->{$propertyName};
        }
        return null;
    }

    /**
     * 重写toArray
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'history_node_names' => $this->history_node_names ?? [],
            'history_edge_names' => $this->history_edge_names ?? [],
            'active_node_names'  => $this->active_node_names ?? [],
        ];
    }
}


