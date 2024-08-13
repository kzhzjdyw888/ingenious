<?php
/**
 * Copyright (C) 2024 Ingenstream
 * This software is licensed under the Apache-2.0 license.
 * A copy of the license can be found at http://www.apache.org/licenses/LICENSE-2.0
 * Official Website: http://www.ingenstream.cn
 * Author: Mr. April <405784684@qq.com>
 * Project: Ingenious
 * Repository: https://gitee.com/ingenstream/ingenious
 */

namespace ingenious\libs\traits;

/**
 * Trait ModelTrait
 *
 * @package crmeb\traits
 */
trait ModelTrait
{
    /**
     * 时间段搜索器
     *
     * @param Model $query
     * @param       $value
     * @param       $data
     */
    public function searchTimeAttr($query, $value, $data): void
    {
        if ($value) {
            $timeKey = $data['timeKey'] ?? 'create_time';
            if (is_array($value)) {
                $startTime = $value[0] ?? 0;
                $endTime   = $value[1] ?? 0;
                if ($startTime || $endTime) {
                    if ($startTime == $endTime || $endTime == strtotime(date('Y-m-d', $endTime))) {
                        $endTime = $endTime + 86399;
                    }
                    $query->whereBetween($timeKey, [$startTime, $endTime]);
                }
            } elseif (is_string($value)) {
                switch ($value) {
                    case 'today':
                    case 'week':
                    case 'month':
                    case 'year':
                    case 'yesterday':
                    case 'last year':
                    case 'last week':
                    case 'last month':
                        $query->whereTime($timeKey, $value);
                        break;
                    case 'quarter':
                        [$startTime, $endTime] = $this->getMonth();
                        $query->whereBetween($timeKey, [strtotime($startTime), strtotime($endTime)]);
                        break;
                    case 'lately7':
                        $query->whereBetween($timeKey, [strtotime("-7 day"), time()]);
                        break;
                    case 'lately30':
                        $query->whereBetween($timeKey, [strtotime("-30 day"), time()]);
                        break;
                    default:
                        if (strstr($value, '-') !== false) {
                            [$startTime, $endTime] = explode('-', $value);
                            $startTime = trim($startTime) ? strtotime($startTime) : 0;
                            $endTime   = trim($endTime) ? strtotime($endTime) : 0;
                            if ($startTime && $endTime) {
                                if ($startTime == $endTime || $endTime == strtotime(date('Y-m-d', $endTime))) {
                                    $endTime = $endTime + 86399;
                                }
                                $query->whereBetween($timeKey, [$startTime, $endTime]);
                            } else if (!$startTime && $endTime) {
                                $query->whereTime($timeKey, '<', $endTime + 86400);
                            } else if ($startTime && !$endTime) {
                                $query->whereTime($timeKey, '>=', $startTime);
                            }
                        }
                        break;
                }
            }
        }
    }

    /**
     * 获取本季度 time
     *
     * @param int $ceil
     *
     * @return array
     */
    public function getMonth(int $ceil = 0)
    {
        if ($ceil != 0) {
            $season = ceil(date('n') / 3) - $ceil;
        } else {
            $season = ceil(date('n') / 3);
        }
        $firstday = date('Y-m-01', mktime(0, 0, 0, ($season - 1) * 3 + 1, 1, date('Y')));
        $lastday  = date('Y-m-t', mktime(0, 0, 0, $season * 3, 1, date('Y')));
        return [$firstday, $lastday];
    }

    /**
     * 获取某个字段内的值
     *
     * @param                $value
     * @param string         $filed
     * @param string         $valueKey
     * @param array|string[] $where
     *
     * @return mixed
     */
    public function getFieldValue($value, string $filed, ?string $valueKey = '', ?array $where = [])
    {
        $model = $this->where($filed, $value);
        if ($where) {
            $model->where(...$where);
        }
        return $model->value($valueKey ?: $filed);
    }
}
