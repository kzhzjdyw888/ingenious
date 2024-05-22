<?php

namespace app;

/**
 * 控制器基础类
 */
abstract class BaseController
{
    /**
     * Request实例
     *
     * @var
     */
    protected $request;


    /**
     * 控制器中间件
     *
     * @var array
     */
    protected $middleware = [];

    /**
     * @var
     */
    protected $services;

    /**
     * 需要授权的接口地址
     *
     * @var string[]
     */
    private $authRule = [];

    /**
     * 构造方法
     *
     * @access public
     */
    public function __construct()
    {
        $this->request = request();
        $this->initialize();
    }

    /**
     * @return mixed
     */
    abstract protected function initialize();

}
