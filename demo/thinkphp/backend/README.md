# WorkingFlower 2.0 Demo 基于ThinkPHP8 +PearAdmin+LogicFlow流程设计器

## WorkingFloer 介绍

⭐⭐PHP WorkingFlower是一个基于ThinkPHP 8.0的开源工作流引擎，适用于企业应用中常见的业务流程。它以轻量、简单、灵巧为理念设计，致力于成为简单集成的多环境支持工作流引擎。以下是WorkingFlower引擎流的详细介绍：

轻量级：WorkingFlower具有强大的扩展性。非常适合在资源有限的环境中使用。
简单性：WorkingFlower的表设计简单，流程组件也十分简洁，包括start、end、task、custom、subprocess、decision、fork、join等元素，易于理解和使用。同时，2.0版本基于ThinkPHP 框架 ORM实现。
灵巧性：WorkingFlower暴露了大量的可扩展接口，支持流程设计器、流程引擎的组件模型自定义，如节点自定义、属性自定义、表单自定义等。这意味着用户可以根据自己的需求来扩展和定制流程功能。
数据持久层面支持：1.0版本为jdbc数据库支持有限 2.0版本采用thinkORM实现持久 解决支持多类型数据库，目前支持的数据库有sqlite pgsql oracle、mysql、sqlserver mongo等。
总的来说，WorkingFloer是一个功能强大且易于集成的开源工作流引擎，适用于各种企业应用的业务流程。它以轻量、简单、灵巧为理念，旨在提高工作效率、降低生产成本并提升企业竞争力。




## 设计器图集

![属性面板](https://foruda.gitee.com/images/1700640532197373837/078d3d7c_5445832.png "屏幕截图")

![输入图片说明](https://foruda.gitee.com/images/1700640649760547121/97c6960a_5445832.png "屏幕截图")

![输入图片说明](https://foruda.gitee.com/images/1700640698723935222/0173386d_5445832.png "屏幕截图")

![输入图片说明](https://foruda.gitee.com/images/1700640729129530289/9e9d2aa5_5445832.png "屏幕截图")

![输入图片说明](https://foruda.gitee.com/images/1700640774784152296/3087f6aa_5445832.png "屏幕截图")
![输入图片说明](https://foruda.gitee.com/images/1700640829904705712/ac7b6b1f_5445832.png "屏幕截图")
![输入图片说明](https://foruda.gitee.com/images/1700640872676949260/ad86f05d_5445832.png "屏幕截图")


## 引擎样列数据


```
{
	"name": "main01",
	"display_name": "主流程含（请款子流程）",
	"expire_time": "",
	"instance_url": "leaveForm",
	"instance_no_class": "",
	"type": "workingflower:process",
	"nodes": [
		{
			"id": "189dfefa-6603-46f2-89e4-23ec52eaeb32",
			"type": "workingflower:wfSubProcess",
			"x": 340,
			"y": 420,
			"properties": {
				"form": "leaveForm",
				"color": "#000000",
				"theme": "#FFFFFF",
				"width": "182",
				"height": "48",
				"stroke": "#1c4573",
				"version": "1.0",
				"process_name": "test04",
				"stroke_width": "2"
			},
			"text": {
				"x": 340,
				"y": 420,
				"value": "子流程-请款"
			}
		},
		{
			"id": "1238cc65-5757-484c-b31d-bd039645ceae",
			"type": "workingflower:start",
			"x": 180,
			"y": 180,
			"properties": [],
			"text": {
				"x": 180,
				"y": 220,
				"value": "开始"
			}
		},
		{
			"id": "3e517bac-d721-49e6-97d6-4e82debc8609",
			"type": "workingflower:task",
			"x": 340,
			"y": 180,
			"properties": {
				"color": "#000000",
				"field": [],
				"scope": "5",
				"theme": "#FFFFFF",
				"width": "120",
				"height": "40",
				"stroke": "#000000",
				"assignee": "",
				"task_type": "Major",
				"perform_type": "ANY",
				"stroke_width": "2",
				"back_permission": "1"
			},
			"text": {
				"x": 340,
				"y": 180,
				"value": "申请人"
			}
		},
		{
			"id": "1fdcfa3b-29d0-4929-b6ae-9905982d75b4",
			"type": "workingflower:decision",
			"x": 340,
			"y": 280,
			"properties": []
		},
		{
			"id": "ac2dbf64-a4cb-40bd-8e5b-59d514edf2d6",
			"type": "workingflower:task",
			"x": 560,
			"y": 280,
			"properties": {
				"color": "#000000",
				"field": [],
				"scope": "1",
				"theme": "#FFFFFF",
				"width": "120",
				"height": "40",
				"stroke": "#000000",
				"assignee": "admin",
				"task_type": "Major",
				"perform_type": "ANY",
				"stroke_width": "2",
				"back_permission": "1"
			},
			"text": {
				"x": 560,
				"y": 280,
				"value": "经理审批"
			}
		},
		{
			"id": "8aa0debf-4213-4aee-a5f8-19dfcc1cf152",
			"type": "workingflower:end",
			"x": 760,
			"y": 280,
			"properties": [],
			"text": {
				"x": 760,
				"y": 320,
				"value": "结束节点"
			}
		}
	],
	"edges": [
		{
			"id": "b628c46c-544f-4dc0-9881-fbccfa684ce0",
			"type": "workingflower:transition",
			"sourceNodeId": "1238cc65-5757-484c-b31d-bd039645ceae",
			"targetNodeId": "3e517bac-d721-49e6-97d6-4e82debc8609",
			"startPoint": {
				"x": 198,
				"y": 180
			},
			"endPoint": {
				"x": 280,
				"y": 180
			},
			"properties": [],
			"pointsList": [
				{
					"x": 198,
					"y": 180
				},
				{
					"x": 280,
					"y": 180
				}
			]
		},
		{
			"id": "868c2422-3120-41bf-99ae-4d400a88210e",
			"type": "workingflower:transition",
			"sourceNodeId": "3e517bac-d721-49e6-97d6-4e82debc8609",
			"targetNodeId": "1fdcfa3b-29d0-4929-b6ae-9905982d75b4",
			"startPoint": {
				"x": 340,
				"y": 200
			},
			"endPoint": {
				"x": 340,
				"y": 255
			},
			"properties": [],
			"pointsList": [
				{
					"x": 340,
					"y": 200
				},
				{
					"x": 340,
					"y": 230
				},
				{
					"x": 340,
					"y": 230
				},
				{
					"x": 340,
					"y": 225
				},
				{
					"x": 340,
					"y": 225
				},
				{
					"x": 340,
					"y": 255
				}
			]
		},
		{
			"id": "ec67871c-4efe-4a6b-b714-86a4a06742d4",
			"type": "workingflower:transition",
			"sourceNodeId": "1fdcfa3b-29d0-4929-b6ae-9905982d75b4",
			"targetNodeId": "ac2dbf64-a4cb-40bd-8e5b-59d514edf2d6",
			"startPoint": {
				"x": 365,
				"y": 280
			},
			"endPoint": {
				"x": 500,
				"y": 280
			},
			"properties": {
				"expr": "f_day &lt; 1000"
			},
			"text": {
				"x": 432.5,
				"y": 280,
				"value": "小于1000万"
			},
			"pointsList": [
				{
					"x": 365,
					"y": 280
				},
				{
					"x": 500,
					"y": 280
				}
			]
		},
		{
			"id": "f5644192-adfd-45a7-9e82-d708b3d6a94e",
			"type": "workingflower:transition",
			"sourceNodeId": "1fdcfa3b-29d0-4929-b6ae-9905982d75b4",
			"targetNodeId": "189dfefa-6603-46f2-89e4-23ec52eaeb32",
			"startPoint": {
				"x": 340,
				"y": 305
			},
			"endPoint": {
				"x": 340,
				"y": 400
			},
			"properties": {
				"expr": "f_day &gt; 1000"
			},
			"text": {
				"x": 340,
				"y": 352.5,
				"value": "大于1000万"
			},
			"pointsList": [
				{
					"x": 340,
					"y": 305
				},
				{
					"x": 340,
					"y": 400
				}
			]
		},
		{
			"id": "4858f4e7-09dd-46d7-8012-17236de45c11",
			"type": "workingflower:transition",
			"sourceNodeId": "189dfefa-6603-46f2-89e4-23ec52eaeb32",
			"targetNodeId": "ac2dbf64-a4cb-40bd-8e5b-59d514edf2d6",
			"startPoint": {
				"x": 400,
				"y": 420
			},
			"endPoint": {
				"x": 560,
				"y": 300
			},
			"properties": [],
			"pointsList": [
				{
					"x": 400,
					"y": 420
				},
				{
					"x": 560,
					"y": 420
				},
				{
					"x": 560,
					"y": 300
				}
			]
		},
		{
			"id": "8eb2d863-d5ca-4b7a-be3c-ab72d971f57a",
			"type": "workingflower:transition",
			"sourceNodeId": "ac2dbf64-a4cb-40bd-8e5b-59d514edf2d6",
			"targetNodeId": "8aa0debf-4213-4aee-a5f8-19dfcc1cf152",
			"startPoint": {
				"x": 620,
				"y": 280
			},
			"endPoint": {
				"x": 742,
				"y": 280
			},
			"properties": [],
			"pointsList": [
				{
					"x": 620,
					"y": 280
				},
				{
					"x": 742,
					"y": 280
				}
			]
		}
	]
}
```




## 安装教程

1. composer install
2. 修改前端配置lms_admin/ims/confing   对应url
3. 前端访问地址访问ip/lms_admin/index
4. 账号admin 密码123456 其他账号密码均为123456



## 伪静态

```
<IfModule mod_rewrite.c>
  Options +FollowSymlinks -Multiviews
  RewriteEngine On

  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteRule ^(.*)$ index.php?/$1 [QSA,PT,L]
</IfModule>
```





## ⭐交流群
QQ 321796659


## 使用案例
[后端工程] (http://43.138.153.216:8005/lms_admin/index.html)

[前端工程 vue设计器] (http://43.138.153.216:8003)



## ✨ 鸣谢  Thanks

- 感谢 [JetBrains](https://www.jetbrains.com) 提供生产力巨高的 `PHPStorm`和`WebStorm`
> 排名不分先后

- [top-think/think](https://github.com/top-think/think)
- [Layui](https://www.layui.com)
- [Senaker 国内最优秀的开源流程引擎Java](https://gitee.com/mldong)
- [logicFlow 优秀的表单设计器，流程引擎](https://site.logic-flow.cn/docs/#/zh/guide/start)
- [pearadmin pearadmin便捷高效的快速建站,后台开发框架](http://www.pearadmin.com/



