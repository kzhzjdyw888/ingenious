<?php

namespace app\adminapi\controller\system;

use app\common\Json;
use support\Container;
use support\Request;
use think\facade\Db;
use app\adminapi\controller\AuthController;
use app\services\system\databackup\SystemDatabackupServices;

/**
 * 数据备份
 * Class SystemDatabackup
 *
 * @package app\admin\controller\system
 */
class SystemDatabackup extends AuthController
{

    public function __construct()
    {
        parent::__construct();
        $this->services = Container::make(SystemDatabackupServices::class);
    }

    /**
     * 获取数据库表
     */
    public function index(Request $request): \support\Response
    {
        return Json::success($this->services->getDataList());
    }

    /**
     * 查看表结构 详情
     */
    public function read(Request $request): \support\Response
    {
        $tablename = $request->input('tablename', '');
        return Json::success($this->services->getRead($tablename));
    }

    /**
     * 更新数据表或者表字段备注
     *
     * @param \support\Request $request
     *
     * @return \support\Response
     * @throws \think\db\exception\BindParamException
     * @author 吴汐
     * @email  442384644@qq.com
     * @date   2023/04/11
     */
    public function updateMark(Request $request): \support\Response
    {
        [$table, $field, $type, $mark, $is_field] = $request->postMore([
            ['table', ''],
            ['field', ''],
            ['type', ''],
            ['mark', ''],
            ['is_field', 0],
        ], true);
        if ($is_field == 0) {
            $sql = "ALTER TABLE $table COMMENT '$mark'";
        } else {
            $sql = "ALTER TABLE $table MODIFY COLUMN $field $type COMMENT '$mark'";
        }
        Db::execute($sql);
        return Json::success(100024);
    }

    /**
     * 优化表
     */
    public function optimize(Request $request): \support\Response
    {
        $tables = $this->request->param('tables', '', 'htmlspecialchars');
        $res    = $this->services->getDbBackup()->optimize($tables);
        return Json::success($res ? 100047 : 100048);
    }

    /**
     * 修复表
     */
    public function repair(Request $request): \support\Response
    {
        $tables = $request->input('tables', '');
        $res    = $this->services->getDbBackup()->repair($tables);
        return Json::success($res ? 100049 : 100050);
    }

    /**
     * 备份表
     */
    public function backup(Request $request): \support\Response
    {
        $tables = $request->input('tables', '');
        $data = $this->services->backup($tables);
        return Json::success(100051);
    }

    /**
     * 获取备份记录表
     */
    public function fileList(Request $request): \support\Response
    {
        return Json::success($this->services->getBackup());
    }

    /**
     * 删除备份记录表
     */
    public function delFile(Request $request): \support\Response
    {
        $filename = intval(request()->post('filename'));
        $files    = $this->services->getDbBackup()->delFile($filename);
        return Json::success(100002);
    }

    /**
     * 导入备份记录表
     */
    public function import(Request $request): \support\Response
    {
        [$part, $start, $time] = $this->request->postMore([
            [['part', 'd'], 0],
            [['start', 'd'], 0],
            [['time', 'd'], 0],
        ], true);
        $db = $this->services->getDbBackup();
        if (is_numeric($time) && !$start) {
            $list = $db->getFile('timeverif', $time);
            if (is_array($list)) {
                session::set('backup_list', $list);
                return Json::success(400307, array('part' => 1, 'start' => 0));
            } else {
                return Json::fail(400308);
            }
        } else if (is_numeric($part) && is_numeric($start) && $part && $start) {
            $list  = session::get('backup_list');
            $start = $db->setFile($list)->import($start);
            if (false === $start) {
                return Json::fail(400309);
            } elseif (0 === $start) {
                if (isset($list[++$part])) {
                    $data = array('part' => $part, 'start' => 0);
                    return Json::success(400310, $data);
                } else {
                    session::delete('backup_list');
                    return Json::success(400311);
                }
            } else {
                $data = array('part' => $part, 'start' => $start[0]);
                if ($start[1]) {
                    $rate = floor(100 * ($start[0] / $start[1]));
                    return Json::success(400310, $data);
                } else {
                    $data['gz'] = 1;
                    return Json::success(400310, $data);
                }
            }
        } else {
            return Json::fail(100100);
        }
    }

    /**
     * 下载备份记录表
     */
    public function downloadFile(Request $request): \support\Response
    {
        $time = intval(request()->param('time'));
        return Json::success(['key' => $this->services->getDbBackup()->downloadFile($time, 0, true)]);
    }

}
