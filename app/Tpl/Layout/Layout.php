<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Virtual Classes Schedule Management - V4.3.2</title>
		<link type="text/css" href="__PUBLIC__/style/ui-lightness/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
		<link href="__PUBLIC__/style/calendar.css" rel="stylesheet" type="text/css">
		<link href="__PUBLIC__/style/vc_style.css" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="__PUBLIC__/js/calendar.js"></script>
		<script src="__PUBLIC__/js/jquery.1.8.2.js"></script>
		<script src="__PUBLIC__/js/jquery-ui-1.8.16.custom.min.js"></script>
		<script src="__PUBLIC__/js/widget.js"></script>
		<?php if(ACTION_NAME=='editItem' || ACTION_NAME=='addItem') {?>
		<script src="__PUBLIC__/js/tiny_mce/tiny_mce.js"></script>
		<?php }?>
	</head>
	<body>
	<include file="Layout:head" />
	{__CONTENT__}
	<include file="Layout:foot" />
    </body>
</html>