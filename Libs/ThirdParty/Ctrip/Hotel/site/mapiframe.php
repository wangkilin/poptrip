<?php 
include_once ("../SDK.config.php");//配置文件加载--必须加载这个文件
?>
<!DOCTYPE html>
<html>
<head>
<script type="text/javascript" src="http://open.mapbar.com/apis/maps/free?<?php
	// 补救用户只在后台输入图吧key而不是构造字符串 
	$mapsfreestr = 'f=mapi&v=31&k=';
	if(substr($MapKey,0,14) == $mapsfreestr) echo $MapKey;
	else echo $mapsfreestr.$MapKey;
?>"></script>
<style type="text/css">
body { margin:0; padding:0; }
.searchresult_popname { position:absolute; }
.searchresult_popname span { float:left; height:30px; line-height:30px; padding:0 8px; border:2px solid #287BCE; background-color:#fff; color:#0053AA; border-radius:5px;font-size:12px; white-space:nowrap; }
.searchresult_popname2 { position:absolute; border-radius:5px; }
.searchresult_popname2 span { float:left; height:26px; line-height:26px; padding:0 15px; border:2px solid #EE5B78; color:#A44343; border-radius:5px; font-size:12px; background-color:#FFF; }
.invisible{display:none;}
.hidden{display:none;}
</style>
<script>
window.$ = function(str){
    return document.getElementById(str);
}
</script>
</head>
<body>
<div id="map"></div>
<input type="hidden" id="page_id" value="102024" />
</body>
</html>