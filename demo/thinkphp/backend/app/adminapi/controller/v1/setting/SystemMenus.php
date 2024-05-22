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
namespace app\adminapi\controller\v1\setting;

use app\adminapi\controller\AuthController;
use app\services\system\admin\SystemMenusServices;
use app\services\system\route\SystemRouteCateServices;
use app\services\system\route\SystemRouteServices;
use think\App;

/**
 * 菜单权限
 * Class SystemMenus
 *
 * @package app\adminapi\controller\v1\setting
 */
class SystemMenus extends AuthController
{
    /**
     * SystemMenus constructor.
     *
     * @param App                 $app
     * @param SystemMenusServices $services
     */
    public function __construct(App $app, SystemMenusServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
        $this->request->filter(['addslashes', 'trim']);
    }

    /**
     * 菜单展示列表
     *
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException|\ReflectionException
     * @author 吴汐
     * @email  442384644@qq.com
     * @date   2023/05/06
     */
    public function index(): \think\Response
    {
        $where = $this->request->getMore([
            ['is_show', ''],
            ['keyword', ''],
            ['auth_type', ''],
        ]);
        return app('json')->success($this->services->getList($where, ['*']));
    }

    /**
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 等风来
     * @email  136327134@qq.com
     * @date   2023/4/14
     */
    public function unique()
    {
        $adminInfo = $this->request->adminInfo();
        [$menus, $uniqueAuth] = app()->make(SystemMenusServices::class)->getMenusList($adminInfo['roles'], (int)$adminInfo['level']);
        return app('json')->success(compact('menus', 'uniqueAuth'));
    }

    /**
     * 菜单获取
     *
     * @return \think\Response
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function permission()
    {
        $adminInfo = $this->request->adminInfo();
        $roles     = ['00f2cf25-61c2-4265-bab9-fc60569b40c6', '03c5a106-ed45-42d2-9467-e3448ed369b3', '08580779-066d-4b3b-9985-124df63625a4', '0a4aff69-ad1d-42b2-8634-00dc3e52360b', '0db53e02-4829-4413-ad96-00ff34fbfd2d', '0efa9244-f4b0-45a6-8781-cac2fff19abe', '0f690e32-1e73-4190-9f87-e349f129451b', '120640c4-6c67-4166-9a7b-8e08e4018cc1', '16a335ef-abea-4581-8de0-1f03d669792e', '170320e1-d592-4809-9447-f40b27fc7aa9', '18e6af3d-e99b-4b1c-be28-2a9547cf6eef', '1d244ec5-cf4e-445a-9374-aaddcb4d84f9', '1e52ad08-670a-4425-9e72-3925ba871dcf', '21abf252-b962-4379-bc19-381749e2cb80', '22900032-1fa4-4a53-bb04-f8b535ea9793', '22d89696-2c65-4925-bf5e-d1b253f9ec17', '25f880bd-856f-49fa-b59c-3f76b7d389c7', '2ac1fd8d-2ca4-4eea-bd1c-f3989096eea2', '2ace6826-3984-4718-9497-ab0dd463bbcb', '2b3e6ae9-7509-4be9-a669-26047ee2e769', '2b433461-3e8c-498d-85a0-33aafade3365', '2b84ae82-4c4a-49ac-8471-a3233b0703db', '2d1f9c58-f0bf-43ff-a729-fabadabd3b8c', '2e79d53d-3a7d-4e17-8944-bdd0b0f63df0', '30dd2e7c-3233-45d3-b538-c2985f9a6ce7', '31d43b76-ae60-4ce5-a04e-2d1d1ec2604b', '366472c2-cb67-4dd9-a8bb-2ae9f045b5c4', '382c1114-59db-4594-b870-da3ce1a29c56', '388b4b2f-5ca8-4cd5-804d-1716b8e02cd0', '3907df96-3152-4aba-9e23-a44575cc7251', '39c763a5-b3d7-4292-99d2-c07f3a04d62f', '3a07101c-46ac-4ad7-824d-b2bf0de65b90', '3b7afea6-ddd5-4532-8a3f-9c2e126e731a', '3d5e3491-7546-4046-acb3-d7b34fda210c', '3e2df7be-9e8c-4ce2-9ddb-61f13d3142bf', '3e74a70e-fb39-41d6-b3f1-abd93ca56014', '3eb2a028-3398-4e62-abe3-0c295a795065', '3f9e4f79-1b00-4ce7-9187-62bd91ace077', '428ee06e-c350-4c9e-8c29-1e59a9e97570', '4474d199-7089-4efb-bc68-b638a7aef300', '4811a205-a4ec-499a-ba85-30ca5ab95feb', '4a26ba61-676b-4842-a087-743eb0354203', '4c0175ac-5b37-4ef5-a2dc-c14caa7b6e42', '4dd18197-2477-421a-8e42-238c6298c7c9', '54480923-4b9e-4c85-90c8-19712b709bb2', '57f79696-1bd2-43f5-a411-75080735b700', '588383d3-d8fe-4023-b9e7-220baac09a0d', '59ca4011-f611-4514-a7ac-a404228e2e73', '5a22017d-6f19-41e8-936b-f70d405639dd', '5f4d2ab0-0cde-4bd3-829f-5a11d96b6440', '60fea859-c085-4b37-8d7a-d03e81afe73a', '63a64d73-40df-4275-af16-c7c0fd382bf2', '64308d28-674e-4e61-8700-b8b508162c5d', '67cfcfe5-7441-44fd-bc61-3e9dc3059362', '6b674c34-7d23-4a27-9a05-688ed3e9c38e', '6ee2d444-cd5f-45ca-a880-11976d16c210', '73aebdf5-fae6-4aa9-a319-8fcc48b6351b', '75300596-9c65-4e63-87c3-19c974e7bf1e', '7a5eaccc-0019-4064-8f83-7c564c431a7c', '7c259013-7919-4235-ab8c-bfcda9b51184', '7e72c463-d80a-4b83-937d-eb20d63a9636', '7ee73260-eaaf-463a-84ff-ac285055e0c1', '7fb96c9d-9eae-4701-af3c-4b41271a8aaf', '817c3d6e-2f2a-4af9-9b84-97e76029302d', '857bd274-30f4-429a-8004-a2e6d0ccb6de', '88cab609-0d7f-4394-a00e-ad24bd2c5ae5', '8b55c722-256a-40b3-ae27-1fdf99164094', '8c3e3149-4438-4b1c-b32c-10978b1a54ef', '91c5a906-911d-4222-b534-df2e5041e0dd', '92158f89-3eb8-462a-85c9-f9586075e274', '92bfab0f-88e6-47ee-b2f2-5d0916f11ef1', '94d4cd83-3d89-493d-97ba-27ded6503a8f', '982ac136-2353-47b2-9c81-c6b9dd5684c0', '99f5f7b2-d584-44f0-b93d-aee0b8030649', '9f1161f9-a3bb-42c8-9b8f-19b12aecf708', '9fb603da-e232-4beb-ad9b-2351e0274029', 'a16b6f89-bd60-4ffd-b833-337ccd8fed36', 'a51a3e37-c890-40b2-adf4-ca7f3856fd84', 'aaf1c674-956f-4349-8f61-b1d8d3285e20', 'ac632dc7-9f6e-4d07-9d50-0561a456417d', 'ac72da18-cd39-4a6c-baf7-d2f75ca7b4b7', 'b21c5ea2-3eb9-4d4e-8244-bdb3caf7ee80', 'b30dce1f-89e8-4563-b52c-5e85a836ebb1', 'b4c85694-38b6-4332-a62c-260cd597f257', 'b7ae5925-9e84-403e-b8a2-eb4528b11d89', 'ba789614-2967-4b16-99d0-b14f9ec98818', 'baa9d8a3-0ea0-4689-9489-0a5ca387409c', 'bad80eed-ef69-4e42-8d36-ceea6df674d1', 'bc9ac16f-c3bb-43a1-bd05-bbda718513ef', 'bd3fbcde-13b8-4c76-9293-755d349b3873', 'bdd9f5be-2460-41b9-8212-044bfae1a568', 'beb61b71-362f-4a59-ba8c-aba8135602a0', 'bec0e802-1606-4d96-a2a1-7a3bc68852d8', 'cb38b36a-c7ad-4899-bcbc-eee2d4291f69', 'd4538387-432c-4422-9618-3ffbedf83473', 'd65fe5a4-b207-4afa-8a88-7ca21dd7df7a', 'd6744062-bdbb-4f75-a83e-51cdb9d96f5a', 'd6872382-8bba-40be-953c-b999b1b5387c', 'd6c3dc71-a5a4-4468-bd6c-4c92c24d098b', 'd8884521-f21e-4468-a50b-b3978022cef2', 'd9579432-b71c-4b71-9f9d-606a7317446a', 'd97843a4-c154-4943-b8fa-0bbac76706cb', 'da6b2620-828d-49e1-82d2-dda9217b0f6f', 'db010262-fa5b-494b-a961-3e6a40ceaf71', 'dc147005-906d-4373-90a0-c331cbc07b79', 'dd097886-0cee-4731-bc9a-da48eacece91', 'e0db89ff-5832-4057-83a1-afb3af069b5e', 'e16554a1-0325-407f-9ec4-5a5b60138730', 'e19558fc-2a31-440b-b356-05a3c9af9804', 'e6c8ca0f-a219-41fe-9289-b07957104e7a', 'ec8940c7-5a43-460a-8778-6d85f0172c2b', 'ecdfd3a9-17b4-4f5d-bb8c-ebc1f189aa49', 'ee6010ce-a330-4217-9674-07435ce22833', 'f28e981b-07b1-40cd-9071-aea501269627', 'f478742b-8060-41ae-9162-4a08c18e28ea', 'f77a8ac8-6395-481d-a31e-c46b9f9b35c5', 'f858a70c-65e8-4edb-9e24-0d94c337b0d0', 'fc6c26c2-2abf-48d1-81d5-16e20c341b92', 'fcabee71-1473-4020-84d8-6f7eaa9ebfe5'];;
//        [$menus, $uniqueAuth] = app()->make(SystemMenusServices::class)->getMenusList($adminInfo['roles'] , (int)$adminInfo['level']);
        [$menus, $uniqueAuth] = app()->make(SystemMenusServices::class)->getMenusList($roles);
        return app('json')->success($menus);
    }

    /**
     * 保存菜单权限
     */
    public function save()
    {
        $data                 = $this->request->getMore([
            ['menu_name', ''],
            ['controller', ''],
            ['module', 'admin'],
            ['action', ''],
            ['icon', ''],
            ['params', ''],
            ['path', []],
            ['menu_path', ''],
            ['api_url', ''],
            ['methods', ''],
            ['unique_auth', ''],
            ['header', ''],
            ['is_header', 0],
            ['pid', 0],
            ['sort', 0],
            ['auth_type', 0],
            ['access', 1],
            ['is_show', 0],
            ['is_show_path', 0],
        ]);
        $data['is_show_path'] = $data['is_show'];
        if (!$data['menu_name'])
            return app('json')->fail(400198);
        $data['path'] = implode('/', $data['path']);
        if ($this->services->save($data)) {
            return app('json')->success(100021);
        } else {
            return app('json')->fail(100022);
        }
    }

    /**
     * 批量保存权限
     *
     * @return \think\Response
     * @author 等风来
     * @email  136327134@qq.com
     * @date   2023/4/11
     */
    public function batchSave(): \think\Response
    {
        $menus = $this->request->post('menus', []);
        if (!$menus) {
            return app('json')->fail(100026);
        }
        $data = [];

        $uniqueAuthAll = $this->services->getColumn(['delete_time' => null, 'is_show' => 1], 'unique_auth');
        $uniqueAuthAll = array_filter($uniqueAuthAll, function ($item) {
            return !!$item;
        });
        $uniqueAuthAll = array_unique($uniqueAuthAll);

        $uniqueFn = function ($path) use ($uniqueAuthAll) {
            $attPath    = explode('/', $path);
            $uniqueAuth = '';
            if ($attPath) {
                $pathData = [];
                foreach ($attPath as $vv) {
                    if (!str_contains($vv, '<')) {
                        $pathData[] = $vv;
                    }
                }
                $uniqueAuth = implode('-', $pathData);
            }

            if (in_array($uniqueAuth, $uniqueAuthAll)) {
                $uniqueAuth .= '-' . uniqid();
            }

            $uniqueAuthAll[] = $uniqueAuth;

            return $uniqueAuth;
        };

        foreach ($menus as $menu) {
            if (empty($menu['menu_name'])) {
                return app('json')->fail(400198);
            }
            if (isset($menu['unique_auth']) && $menu['unique_auth']) {
                $menu['unique_auth'] = explode('/', $menu['api_url']);
            }
            $data[] = [
                'methods'      => $menu['method'],
                'menu_name'    => $menu['menu_name'],
                'unique_auth'  => !empty($menu['unique_auth']) ? $menu['unique_auth'] : $uniqueFn($menu['api_url']),
                'api_url'      => $menu['api_url'],
                'pid'          => $menu['path'],
                'auth_type'    => 2,
                'is_show'      => 1,
                'is_show_path' => 1,
            ];
        }
        $this->services->saveAll($data);
        return app('json')->success(100021);
    }

    /**
     * 获取一条菜单权限信息
     *
     * @param int $id
     *
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function read(string $id): \think\Response
    {
        if (!$id) {
            return app('json')->fail(100026);
        }
        return app('json')->success($this->services->find($id));
    }

    /**
     * 修改菜单权限表单获取
     *
     * @param int $id
     *
     * @return \think\Response
     * @throws \FormBuilder\Exception\FormBuilderException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function edit($id): \think\Response
    {
        if (!$id) {
            return app('json')->fail(100100);
        }
        return app('json')->success($this->services->updateMenus((int)$id));
    }

    /**
     * 修改菜单
     *
     * @param $id
     *
     * @return mixed
     */
    public function update($id): mixed
    {
        if (!$id || !($menu = $this->services->get($id)))
            return app('json')->fail(100026);
        $data = $this->request->postMore([
            'menu_name',
            'controller',
            ['module', 'admin'],
            'action',
            'params',
            ['icon', ''],
            ['menu_path', ''],
            ['api_url', ''],
            ['methods', ''],
            ['unique_auth', ''],
            ['path', []],
            ['sort', 0],
            ['pid', 0],
            ['is_header', 0],
            ['header', ''],
            ['auth_type', 0],
            ['access', 1],
            ['is_show', 0],
            ['is_show_path', 0],
        ]);
        if (!$data['menu_name'])
            return app('json')->fail(400198);
        $data['path'] = implode('/', $data['path']);
        if ($this->services->update($id, $data))
            return app('json')->success(100001);
        else
            return app('json')->fail(100007);
    }

    /**
     * 删除指定资源
     *
     * @param int $id
     *
     * @return \think\Response
     */
    public function delete($id): \think\Response
    {
        if (!$id) {
            return app('json')->fail(100100);
        }

        if (!$this->services->delete($id)) {
            return app('json')->fail(100008);
        } else {
            return app('json')->success(100002);
        }
    }

    /**
     * 权限的开启和关闭，显示和隐藏
     *
     * @param $id
     *
     * @return mixed
     */
    public function show($id)
    {
        if (!$id) {
            return app('json')->fail(100100);
        }

        [$isShow, $isShowPath] = $this->request->postMore([['is_show', 0], ['is_show_path', 0]], true);
        if ($isShow == -1) {
            $res = $this->services->update($id, ['is_show_path' => $isShowPath]);
        } else {
            $res = $this->services->update($id, ['is_show' => $isShow, 'is_show_path' => $isShow]);
        }

        if ($res) {
            return app('json')->success(100001);
        } else {
            return app('json')->fail(100007);
        }
    }

    /**
     * 获取菜单数据
     *
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException|\ReflectionException
     */
    public function menus(): mixed
    {
        [$menus, $unique] = $this->services->getMenusList($this->adminInfo['roles'], (int)$this->adminInfo['level']);
        return app('json')->success(['menus' => $menus, 'unique' => $unique]);
    }

    /**
     * 获取路由分类
     *
     * @param SystemRouteCateServices $service
     *
     * @return \think\Response
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 等风来
     * @email  136327134@qq.com
     * @date   2023/4/25
     */
    public function ruleCate(SystemRouteCateServices $service): \think\Response
    {
        return app('json')->success($service->getAllList('adminapi'));
    }

    /**
     * 获取接口列表
     *
     * @param \app\services\system\route\SystemRouteServices $services
     *
     * @return \think\Response
     */
    public function ruleList(SystemRouteServices $services): \think\Response
    {
        $cateId = request()->get('cate_id', 0);
        //获取所有的路由
        $ruleList = $services->selectList(['cate_id' => $cateId, 'app_name' => 'adminapi'])->toArray();
        return app('json')->success($ruleList);
    }
}
