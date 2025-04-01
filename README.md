<h1 align="center" style="margin: 30px 0 30px; font-weight: bold;">Ingenious 工作流引擎</h1>
<h4 align="center">基于 PHP 面向对象编程（OOP） 设计的国产自研工作流引擎</h4>

<p align="center">
    <a href="https://gitee.com/ingenstream/ingenious/blob/master/LICENSE"><img src="https://img.shields.io/static/v1?label=License&message=Apache%202.0&color=blue"></a>
    <a href="#"><img src="https://compass.gitee.com/badge/sen9f094.svg" alt="OSS Compass Analyze" /></a>
    <a href="https://gitee.com/ingenstream/ingenious"><img src="https://img.shields.io/badge/Language-PHP8-orange?style=flat-square&logo=&#42"></a>
    <a href='https://gitee.com/ingenstream/ingenious/stargazers'><img src='https://gitee.com/ingenstream/ingenious/badge/star.svg?theme=dark' alt='star'></img></a>
    <a href="https://gitcode.com/motion-code/ingenious"><img src="https://gitcode.com/motion-code/ingenious/star/badge.svg"></a>
    <a href="#"><img src="https://img.shields.io/github/v/tag/kzhzjdyw888/ingenious.svg?label=Version"></a>
</p>

## 介绍
ingenious-v2是一款基于 PHP 面向对象编程（OOP）设计的国产自研工作流引擎，专为企业级应用而设计。它以灵活轻巧为核心理念，功能全面且强大，各组件设计独立而又高度协同，展现出卓越的可扩展性，完美适配大型项目的复杂需求。

## 核心功能

- 流程流转灵活：支持常规的流程流转操作，如跳转、回退、审批和任意跳转，确保流程能够灵活应对各种场景。
- 转办与终止：支持任务转办和终止功能，确保任务能够按照预期流转并最终回到发起人手中。
- 会签支持：无论是串行会签还是并行会签，该引擎都能轻松应对，确保多个参与者的意见能够得到有效整合。
- 业务项目独立：业务项目可以不依赖流程设计器进行开发，降低了业务与流程的耦合度，提高了系统的灵活性和可维护性。
- 权限配置灵活：支持角色、部门和用户等多维度的权限配置，确保系统的安全性。
- 丰富的扩展功能：支持监听器、参数传递、动态权限等高级功能，为开发者提供了丰富的扩展接口。同时，还支持互斥网关、并行网关等高级流程控制组件。
- 自定义任务与拦截器：支持自定义任务类型和处理逻辑，以及前置和后置拦截器的配置，满足个性化的业务需求。
- 子流程与委托：支持子流程的管理和委托功能，提高流程的可读性和可维护性。
- 时限控制与调度：提供时限控制功能，支持超时自动处理，并提供任务调度接口，方便用户进行任务管理。
- 事件订阅：支持事件订阅功能，允许用户在特定事件发生时执行自定义逻辑。
- ORM框架兼容：支持不同ORM框架系统使用，方便用户根据自身需求选择合适的ORM框架。

## 适配 v2-demo

为了方便用户快速上手和了解 Ingenious v2 工作流引擎的功能，我们提供了基于thinkphp 8.1+ 一键安装demo。这个 demo
包含了一个完整的项目示例，展示了如何使用 ingenious v2 引擎实现工作流管理功能。您可以体验到引擎的流程设计、任务管理、权限配置等核心功能，并可以根据自己的需求进行定制和扩展。

您可以通过访问分支v2-demo代码仓库来获取这个demo源码，并在本地环境中进行部署和运行。

## 主要特性

- PHP8强类型支持：采用PHP8强类型（严格模式）进行开发，提高代码的稳定性和可维护性。
- PSR规范升级：升级PSR规范依赖版本，确保代码符合最新的PHP开发标准。
- 主流框架支持：支持PHP主流框架如ThinkPHP、Webman、Laravel、Hyperf等，方便用户快速集成到现有项目中。
- 主流设计器兼容：支持主流流程设计器如logicFlow、AntV X6等，提供丰富的流程设计工具。

## 在线体验

* 

## 安装

* 安装Composer
* composer require madong/ingenious

## 链接

---

> 官方：
https://www.madong.tech/

> 演示地址:
[https://think-eflow.madong.tech/admin/login/index](https://think-eflow.madong.tech/admin/login/index)

> 腾讯频道:
[pd52261144](https://pd.qq.com/s/3edfwx2lm)

> 纷传圈子:
[https://pc.fenchuan8.com/#/index?forum=84868&yqm=M9RJ](https://pc.fenchuan8.com/#/index?forum=84868&yqm=M9RJ)


---

~~~
对您有帮助的话，你可以在下方赞助我们，让我们更好的维护开发，谢谢！
特别声明：坚决打击网络诈骗行为，严禁将本插件集成在任何违法违规的程序上。
~~~

如果对您有帮助，您可以点右上角 💘Star💘支持
