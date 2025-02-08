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

namespace app\services\system\admin;

//use app\jobs\CheckQueueJob;
//use app\services\BaseServices;
//use app\services\order\StoreOrderServices;
//use app\services\product\product\StoreProductReplyServices;
//use app\services\product\product\StoreProductServices;
//use app\services\user\UserExtractServices;
use app\dao\system\admin\SystemAdminDao;
use app\model\system\admin\SystemAdmin;
use app\services\BaseServices;
use phoenix\basic\BaseJobs;
use phoenix\basic\BaseModel;
use phoenix\exceptions\AdminException;
use phoenix\services\CacheService;
use phoenix\services\FormBuilder;
use phoenix\services\workerman\ChannelService;
use think\facade\Config;
use think\facade\Event;
use think\Model;

/**
 * 管理员service
 * Class SystemAdminServices
 *
 * @package app\services\system\admin
 * @method getAdminIds(int $level) 根据管理员等级获取管理员id
 * @method getOrdAdmin(string $field, int $level) 获取低于等级的管理员名称和id
 */
class SystemAdminServices extends BaseServices
{

    /**
     * SystemAdminServices constructor.
     *
     * @param SystemAdminDao $dao
     */
    public function __construct(SystemAdminDao $dao)
    {
        $this->dao = $dao;

    }

    /**
     * 获取管理员详情
     *
     * @param string $id
     *
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function find(string $id): array
    {
        $adminInfo = $this->dao->get($id);
        if (!$adminInfo) {
            throw new AdminException(100026);
        }

        /** @var SystemAdminDeptServices $systemAdminDeptServices */
        $systemAdminDeptServices = app()->make(SystemAdminDeptServices::class);
        $adminInfo->dept_id      = $systemAdminDeptServices->column(['admin_id' => $id], 'dept_id')[0] ?? '';

        /** @var SystemAdminPostServices $systemAdminPostServices */
        $systemAdminPostServices = app()->make(SystemAdminPostServices::class);
        $adminInfo->post_id      = $systemAdminPostServices->column(['admin_id' => $id], 'post_id')[0] ?? '';

        return $adminInfo->getData();
    }

    /**
     * 管理员登陆
     *
     * @param string $account
     * @param string $password
     *
     * @return array|bool|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException|\ReflectionException
     */
    public function verifyLogin(string $account, string $password, $isPwd = true)
    {
        $adminInfo = $this->dao->accountByAdmin($account);

        if (!$adminInfo) return false;
        if ($isPwd) {
            if (!password_verify($password, $adminInfo->pwd)) return false;
        }
        if (!$adminInfo->status) {
            throw new AdminException(400595);
        }
        /** @var SystemAdminDeptServices $systemAdminDeptServices */
        $systemAdminDeptServices = app()->make(SystemAdminDeptServices::class);
        $adminInfo->dept_id      = $systemAdminDeptServices->column(['admin_id' => $adminInfo->id], 'dept_id')[0] ?? '';

        /** @var SystemAdminPostServices $systemAdminPostServices */
        $systemAdminPostServices = app()->make(SystemAdminPostServices::class);
        $adminInfo->post_id      = $systemAdminPostServices->column(['admin_id' => $adminInfo->id], 'post_id')[0] ?? '';

        if (!empty($adminInfo->dept_id)) {
            /** @var SystemRoleDeptServices $systemRoleDeptServices */
            $systemRoleDeptServices = app()->make(SystemRoleDeptServices::class);
            $deptRoleId             = $systemRoleDeptServices->column(['dept_id' => $adminInfo->dept_id], 'role_id');
        }

        if (!empty($adminInfo->post_id)) {
            /** @var SystemRolePostServices $systemRolePostServices */
            $systemRolePostServices = app()->make(SystemRolePostServices::class);
            $postRoleId             = $systemRolePostServices->column(['post_id' => $adminInfo->post_id], 'role_id');
        }

        $adminInfo->role_id = array_merge_recursive($deptRoleId ?? [], $postRoleId ?? []);

        /** @var SystemRoleMenuServices $systemRoleMenuServices */
        $systemRoleMenuServices = app()->make(SystemRoleMenuServices::class);
        $adminInfo->roles       = $systemRoleMenuServices->column(['role_id' => $adminInfo->role_id], 'menu_id');

        $adminInfo->last_time = time();
        $adminInfo->last_ip   = app('request')->ip();
        $adminInfo->login_count++;
        $adminInfo->save();
        return $adminInfo;
    }

    /**
     * 文件管理员登陆
     *
     * @param string $account
     * @param string $password
     *
     * @return array|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function verifyFileLogin(string $account, string $password)
    {
        $adminInfo = $this->dao->accountByAdmin($account);
        if (!$adminInfo) {
            throw new AdminException(400594);
        }
        if (!$adminInfo->status) {
            throw new AdminException(400595);
        }
        if (!password_verify($password, $adminInfo->file_pwd)) {
            throw new AdminException(400140);
        }
        $adminInfo->last_time = time();
        $adminInfo->last_ip   = app('request')->ip();
        $adminInfo->login_count++;
        $adminInfo->save();
        return $adminInfo;
    }

    /**
     * 后台登陆获取菜单获取token
     *
     * @param string $account
     * @param string $password
     * @param string $type
     * @param string $key
     *
     * @return array|bool
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function login(string $account, string $password, string $type, string $key = '', $isPwd = true)
    {
        $adminInfo = $this->verifyLogin($account, $password, $isPwd);
        if (!$adminInfo) return false;

        $tokenInfo = $this->createToken($adminInfo->id, $type, $adminInfo->pwd);

        /** @var SystemMenusServices $services */
        $services = app()->make(SystemMenusServices::class);
        [$menus, $uniqueAuth] = $services->getMenusList($adminInfo->roles, (int)$adminInfo->level);

        //获取队列状态
        $remind = Config::get('app.console_remind', false);
        if ($remind) {
            [$queue, $timer] = Event::until('AdminLoginListener', [$key]);
        }
        return [
            'token'             => $tokenInfo['token'],
            'expires_time'      => $tokenInfo['params']['exp'],
            'menus'             => $menus,
            'unique_auth'       => $uniqueAuth,
            'user_info'         => [
                'id'        => $adminInfo->getData('id'),
                'account'   => $adminInfo->getData('account'),
                'head_pic'  => get_file_link($adminInfo->getData('head_pic')),
                'level'     => $adminInfo->getData('level'),
                'real_name' => $adminInfo->getData('real_name'),
            ],
            'logo'              => sys_config('site_logo'),
            'logo_square'       => sys_config('site_logo_square'),
            'version'           => get_phoenix_version(),
            'newOrderAudioLink' => get_file_link(sys_config('new_order_audio_link', '')),
            'queue'             => $queue ?? true,
            'timer'             => $timer ?? true,
            'site_name'         => sys_config('site_name'),
        ];
    }

    /**
     * 获取登陆前的login等信息
     *
     * @return array
     */
    public function getLoginInfo()
    {
        $key = uniqid();
        CheckQueueJob::dispatch([$key]);
        $data = [
            'slide'          => sys_data('admin_login_slide') ?? [],
            'logo_square'    => sys_config('site_logo_square'),//透明
            'logo_rectangle' => sys_config('site_logo'),//方形
            'login_logo'     => sys_config('login_logo'),//登陆
            'site_name'      => sys_config('site_name'),
            'copyright'      => sys_config('nncnL_crmeb_copyright', ''),
            'version'        => get_crmeb_version(),
            'key'            => $key,
            'login_captcha'  => 0,
        ];
        if (CacheService::get('login_captcha', 1) > 1) {
            $data['login_captcha'] = 1;
        }
        return $data;
    }

    /**
     * 管理员列表
     *
     * @param array $where
     *
     * @return array
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getAdminList(array $where)
    {
        [$page, $limit] = $this->getPageValue();
        $list  = $this->dao->getList($where, $page, $limit);
        $count = $this->dao->count($where);
//        /** @var SystemRoleServices $service */
//        $service = app()->make(SystemRoleServices::class);
//        $allRole = $service->getRoleArray();
//        foreach ($list as &$item) {
//            if ($item['roles']) {
//                $roles = [];
//                foreach ($item['roles'] as $id) {
//                    if (isset($allRole[$id])) $roles[] = $allRole[$id];
//                }
//                if ($roles) {
//                    $item['roles'] = implode(',', $roles);
//                } else {
//                    $item['roles'] = '';
//                }
//            }
//            $item['_add_time'] = date('Y-m-d H:i:s', $item['add_time']);
//            $item['_last_time'] = $item['last_time'] ? date('Y-m-d H:i:s', $item['last_time']) : '';
//        }
        return compact('list', 'count');
    }

    /**
     * 创建管理员表单
     *
     * @param int   $level
     * @param array $formData
     *
     * @return mixed
     * @throws \FormBuilder\Exception\FormBuilderException
     */
    public function createAdminForm(int $level, array $formData = [])
    {
        $f[] = $this->builder->input('account', '管理员账号', $formData['account'] ?? '')->required('请填写管理员账号');
        $f[] = $this->builder->input('pwd', '管理员密码')->type('password')->required('请填写管理员密码');
        $f[] = $this->builder->input('conf_pwd', '确认密码')->type('password')->required('请输入确认密码');
        $f[] = $this->builder->input('real_name', '管理员姓名', $formData['real_name'] ?? '')->required('请输入管理员姓名');

        /** @var SystemRoleServices $service */
        $service = app()->make(SystemRoleServices::class);
        $options = $service->getRoleFormSelect($level);
        if (isset($formData['roles'])) {
            foreach ($formData['roles'] as &$item) {
                $item = intval($item);
            }
        }
        $f[] = $this->builder->select('roles', '管理员身份', $formData['roles'] ?? [])->setOptions(FormBuilder::setOptions($options))->multiple(true)->required('请选择管理员身份');
        $f[] = $this->builder->radio('status', '状态', $formData['status'] ?? 1)->options([['label' => '开启', 'value' => 1], ['label' => '关闭', 'value' => 0]]);
        return $f;
    }

    /**
     * 添加管理员form表单获取
     *
     * @param int $level
     *
     * @return array
     * @throws \FormBuilder\Exception\FormBuilderException
     */
    public function createForm(int $level)
    {
        return create_form('管理员添加', $this->createAdminForm($level), $this->url('/setting/admin'));
    }

    /**
     * 创建管理员
     *
     * @param array $data
     *
     * @return bool
     * @throws \ReflectionException
     */
    public function create(array $data): bool
    {
        if ($data['conf_pwd'] != $data['pwd']) {
            throw new AdminException(400264);
        }
        unset($data['conf_pwd']);

        if (strlen(trim($data['pwd'])) < 6 || strlen(trim($data['pwd'])) > 32) {
            throw new AdminException(400762);
        }

        if ($this->dao->count(['account' => $data['account'], 'is_del' => 0])) {
            throw new AdminException(400596);
        }

        $data['pwd']         = $this->passwordHash($data['pwd']);
        $data['create_time'] = time();
        $data['head_pic']    = '/statics/system_images/admin_head_pic.png';

        return $this->transaction(function () use ($data) {
            if ($this->dao->save($data)) {
                CacheService::clear();
                return true;
            } else {
                throw new AdminException(100022);
            }
        });
    }

    /**
     * 修改管理员表单
     *
     * @param int $level
     * @param int $id
     *
     * @return array
     * @throws \FormBuilder\Exception\FormBuilderException
     */
    public function updateForm(int $level, int $id)
    {
        $adminInfo = $this->dao->get($id);
        if (!$adminInfo) {
            throw new AdminException(400594);
        }
        if ($adminInfo->is_del) {
            throw new AdminException(400452);
        }
        return create_form('管理员修改', $this->createAdminForm($level, $adminInfo->toArray()), $this->url('/setting/admin/' . $id), 'PUT');
    }

    /**
     * 修改管理员
     *
     * @param string $id
     * @param array  $data
     *
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException|\ReflectionException
     */
    public function save(string $id, array $data): bool
    {
        if (!$adminInfo = $this->dao->get($id)) {
            throw new AdminException(400594);
        }
        if ($adminInfo->delete_time) {
            throw new AdminException(400452);
        }
        //修改密码
        if ($data['pwd']) {

            if (!$data['conf_pwd']) {
                throw new AdminException(400263);
            }

            if ($data['conf_pwd'] != $data['pwd']) {
                throw new AdminException(400264);
            }

            if (strlen(trim($data['pwd'])) < 6 || strlen(trim($data['pwd'])) > 32) {
                throw new AdminException(400762);
            }

            $adminInfo->pwd = $this->passwordHash($data['pwd']);
        }
        //修改账号
        if (isset($data['account']) && $data['account'] != $adminInfo->account && $this->dao->isAccountUsable($data['account'], $id)) {
            throw new AdminException(400596);
        }
        $adminInfo->real_name         = $data['real_name'] ?? $adminInfo->real_name;
        $adminInfo->account           = $data['account'] ?? $adminInfo->account;
        $adminInfo->status            = $data['status'];
        $adminInfo->remark            = $data['remark'];
        $adminInfo->cell_phone_number = $data['cell_phone_number'];
        $adminInfo->email             = $data['email'];
        $adminInfo->sex               = $data['sex'];
        $adminInfo->address           = $data['address'];
        if ($adminInfo->save()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 设置管理员-部门/职位
     *
     * @param string $id
     * @param array  $data
     *
     * @return true
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function setPosition(string $id, array $data = []): bool
    {
        $adminInfo = $this->dao->get($id);
        if (!$adminInfo) {
            throw new AdminException(400451);
        }
        if ($adminInfo->delete_time) {
            throw new AdminException(400452);
        }
        $adminInfo->dept_id = $data['dept_id'] ?? '';
        $adminInfo->post_id = $data['post_id'] ?? '';

        /** @var SystemAdminDeptServices $systemAdminDeptServices */
        $systemAdminDeptServices = app()->make(SystemAdminDeptServices::class);
        $systemAdminDeptServices->delete($adminInfo->id, 'admin_id');
        if ($adminInfo->dept_id) {
            $systemAdminDeptServices->save(['dept_id' => $adminInfo->dept_id, 'admin_id' => $adminInfo->id]);
        }
        /** @var SystemAdminPostServices $systemAdminPostServices */
        $systemAdminPostServices = app()->make(SystemAdminPostServices::class);
        $systemAdminPostServices->delete($adminInfo->id, 'admin_id');
        if ($adminInfo->post_id) {
            $systemAdminPostServices->save(['post_id' => $adminInfo->post_id, 'admin_id' => $adminInfo->id]);
        }
        return true;
    }

    /**
     * 修改当前管理员信息
     *
     * @param int   $id
     * @param array $data
     *
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function updateAdmin(string $id, array $data): bool
    {
        $adminInfo = $this->dao->get($id);
        if (!$adminInfo)
            throw new AdminException(400451);
        if ($adminInfo->delete_time) {
            throw new AdminException(400452);
        }
        if (!empty($data['real_name'])) {
            $adminInfo->real_name = $data['real_name'];
        }
        if ($data['pwd']) {
            if (!password_verify($data['pwd'], $adminInfo['pwd']))
                throw new AdminException(400597);
            if (!$data['new_pwd'])
                throw new AdminException(400598);
            if (!$data['conf_pwd'])
                throw new AdminException(400263);
            if ($data['new_pwd'] != $data['conf_pwd'])
                throw new AdminException(400264);
            $adminInfo->pwd = $this->passwordHash($data['new_pwd']);
        }
        if (!empty($data['head_pic'])) {
            $adminInfo->head_pic = $data['head_pic'];
        }
        $adminInfo->remarks           = $data['remarks'];
        $adminInfo->cell_phone_number = $data['cell_phone_number'];
        $adminInfo->email             = $data['email'];
        $adminInfo->sex               = $data['sex'];
        $adminInfo->address           = $data['address'];

        if ($adminInfo->save()) {
            CacheService::clear();
            return true;
        } else {
            return false;
        }
    }

    /**
     * 设置当前管理员文件管理密码
     *
     * @param int   $id
     * @param array $data
     *
     * @return bool
     */
    public function setFilePassword(int $id, array $data)
    {
        $adminInfo = $this->dao->get($id);
        if (!$adminInfo)
            throw new AdminException(400451);
        if ($adminInfo->is_del) {
            throw new AdminException(400452);
        }
        if ($data['file_pwd']) {
            if ($adminInfo->level != 0) throw new AdminException(400611);
            if (!$data['conf_file_pwd'])
                throw new AdminException(400263);
            if ($data['file_pwd'] != $data['conf_file_pwd'])
                throw new AdminException(400264);
            $adminInfo->file_pwd = $this->passwordHash($data['file_pwd']);
        }
        if ($adminInfo->save())
            return true;
        else
            return false;
    }

    /** 后台订单下单，评论，支付成功，后台消息提醒
     *
     * @param $event
     */
    public function adminNewPush()
    {
        try {
            /** @var StoreOrderServices $orderServices */
            $orderServices    = app()->make(StoreOrderServices::class);
            $data['ordernum'] = $orderServices->count(['is_del' => 0, 'status' => 1, 'shipping_type' => 1]);
            /** @var StoreProductServices $productServices */
            $productServices   = app()->make(StoreProductServices::class);
            $data['inventory'] = $productServices->count(['type' => 5]);
            /** @var StoreProductReplyServices $replyServices */
            $replyServices      = app()->make(StoreProductReplyServices::class);
            $data['commentnum'] = $replyServices->count(['is_reply' => 0]);
            /** @var UserExtractServices $extractServices */
            $extractServices    = app()->make(UserExtractServices::class);
            $data['reflectnum'] = $extractServices->getCount(['status' => 0]);//提现
            $data['msgcount']   = intval($data['ordernum']) + intval($data['inventory']) + intval($data['commentnum']) + intval($data['reflectnum']);
            ChannelService::instance()->send('ADMIN_NEW_PUSH', $data);
        } catch (\Exception $e) {
        }
    }

    public function getAdministratorsList(array $where): array
    {
        [$page, $limit] = $this->getPageValue();
        $list = $this->selectList($where, '*', $page, $limit, '', ['departments', 'positions'], true);
        foreach ($list as $value) {
            $deptList  = $value->getData('departments');
            $postList  = $value->getData('positions');
            $dept_id   = [];
            $dept_name = [];
            $post_id   = [];
            $post_name = [];
            if (!empty($deptList)) {
                foreach ($deptList as $dept) {
                    $dept_name[] = $dept->getData('dept_name');
                    $dept_id[]   = $dept->getData('dept_id');
                }
            }
            if (!empty($postList)) {
                foreach ($postList as $post) {
                    $post_name[] = $post->getData('post_name');
                    $post_id[]   = $post->getData('post_id');
                }
            }
            $value->set('dept_name', implode(',', $dept_name));
            $value->set('post_name', implode(',', $post_name));
            $value->set('dept_id', implode(',', $dept_id));
            $value->set('post_id', implode(',', $post_id));
        }
        if ($list != null) {
            $list->hidden(['pwd']);
            $list = $list->toArray();
        } else {
            $list = [];
        }
        $count = $this->dao->count($where);
        return compact('list', 'count');

    }
}
