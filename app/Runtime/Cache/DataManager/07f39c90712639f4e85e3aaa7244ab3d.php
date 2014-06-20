<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
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
	<div class="headArea"><img alt="logo" src="__PUBLIC__/img/logo.png"></div>
<div class="globalNav">
     <div class="<?php echo null===$TableManageIndex ? 'selected':''?>"><span class="home"><a href="<?php echo U(GROUP_NAME.'/'.MODULE_NAME .'/index');?>">Home</a></span></div>
     <?php if(is_array($ManageInfos)): foreach($ManageInfos as $_id=>$_info): if((NULL !== $TableManageIndex) AND ($_id == $TableManageIndex)): ?><div class="selected">
          <span class="virtualClass">
            <a href="<?php echo U(GROUP_NAME.'/'.MODULE_NAME .'/manageTable', array('tableId'=>$_id));?>#"><?php echo (is_array($_info)?$_info["title"]:$_info->title); ?></a></div>
          </span>
        <?php else: ?>
          <div class="">
            <span class="virtualClass">
            <a href="<?php echo U(GROUP_NAME.'/'.MODULE_NAME .'/manageTable', array('tableId'=>$_id));?>" class="menuLink">
            <?php echo (is_array($_info)?$_info["title"]:$_info->title); ?>
            </a>
            </span>
          </div><?php endif; endforeach; endif; ?>
     <!--
     <?php if(is_array($ManageInfos)): $_id = 0; $__LIST__ = $ManageInfos;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$_info): $mod = ($_id % 2 );++$_id; if(($_id-1) === $TableManageIndex): ?><div class="selected">
          <span class="virtualClass">
           <a href="<?php echo U(GROUP_NAME.'/'.MODULE_NAME .'/index', array('tableId'=>($_id-1)));?>#"><?php echo (is_array($_info)?$_info["desc"]:$_info->desc); ?></a>
          </span>
          </div>
        <?php else: ?>
          <div class="">
          <span class="virtualClass">
           <a href="<?php echo U(GROUP_NAME.'/'.MODULE_NAME .'/index', array('tableId'=>($_id-1)));?>"><?php echo (is_array($_info)?$_info["desc"]:$_info->desc); ?></a>
          </span>
          </div><?php endif; endforeach; endif; else: echo "" ;endif; ?>
     -->

</div>



	

<div id="content">
	<div class="selectors">
		<div class="mainTitle">
			<span><?php echo (is_array($ManageInfos[$TableManageIndex])?$ManageInfos[$TableManageIndex]["desc"]:$ManageInfos[$TableManageIndex]->desc); ?></span>
		</div>
		<?php echo ($FilterContent); ?>
	</div>
	<div class="Classes">
	<?php echo ($MainContent); ?>
	</div>
</div>

	<div id="foot">
 <i>Copyright &copy;</i>
</div>
    </body>
</html>