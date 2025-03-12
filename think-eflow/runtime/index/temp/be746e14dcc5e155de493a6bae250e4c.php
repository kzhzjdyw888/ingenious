<?php /*a:1:{s:71:"C:\DATA\MyMotion\main\module\thinkphp-eflow\view\index\index\index.html";i:1741745631;}*/ ?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title></title>
		<link href="/static/component/pear/css/pear.css" rel="stylesheet" />
		<link href="/static/admin/css/other/error.css" rel="stylesheet" />
	</head>
	<body>
		<div class="content" style="top: 30%;">
			<div class="content-r">
				<h1>Think-Eflow</h1>
				<p>欢迎使用</p>
				<button class="pear-btn pear-btn-primary">访问后台</button>
			</div>
		</div>
		<script src="/static/component/layui/layui.js"></script>
        <script src="/static/component/pear/pear.js"></script>
        <script>
            layui.use(['layer'], function () {
                var $ = layui.jquery;
                $('.pear-btn').on('click', function () {
                    location.href = '/admin';
                });
            })
            </script>
	</body>
</html>
