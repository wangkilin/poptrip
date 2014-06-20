<?php
include_once (WEBROOT.'include/db.class.php');
include_once ("../appData/database.config.php");//加载整站系统的配置文件
//验证是否登录
if (!$session->contain('admin')){
	exit("访问受限.......");
}


if($_POST){
	if($_POST['pwd']!=$cfg_dbpwd){
		exit('数据库密码错误');	
	}
	$filelist='';
	$db=new DB($cfg_dbhost, $cfg_dbuser, $cfg_dbpwd, $cfg_dbname, $cfg_dbcharset, $cfg_dbprefix);
	$sqlquery = trim($_POST['sqlquery']);
	if(preg_match("#drop(.*)#i", $sqlquery) ||preg_match("#delete(.*)#", $sqlquery)){
        $filelist= "<span style='font-size:10pt'>删除'数据表'或'数据库'或删除的语句不允许在这里执行。</span>";
    }else{
    	//运行查询语句
	    if(preg_match("#^select #i", $sqlquery)){
	    	$rows = $db->query($sqlquery);
	        if($db->getNums($rows)<=0)
	        {
	            $filelist= "运行SQL：{$sqlquery}，无返回记录！";
	        }
	        else
	        {
	            $filelist= "运行SQL：{$sqlquery}，共有".$db->getNums($rows)."条记录，最大返回100条！";
	        }
	        $j = 0;
	        while($row = $db->fetch_array($rows))
	        {
	            $j++;
	            if($j > 100)
	            {
	                break;
	            }
	            $filelist .= "<hr size=1 width='100%'/>";
	            $filelist .= "记录：$j";
	            $filelist .= "<hr size=1 width='100%'/>";
	            foreach($row as $k=>$v)
	            {
	                $filelist .= "<font color='red'>{$k}：</font>{$v}<br/>\r\n";
	            }
	        }
	    }else{
	    	if($db->query($sqlquery)){
	    		$filelist= "运行SQL：{$sqlquery}成功，<br>共有".$db->affected_row()."条记录有改变！";
	    	}else{
	    		$filelist="运行SQL失败，请检查你的SQL语句。";
	    	}
	    	
	    }
    	
    }
	
}



?>

<form method='post' >
<table cellspacing="0" cellpadding="0" class="mod_table3">
	<tbody>

		<tr>
			<th style="width: 100px;vertical-align: top;">sql命令行：</th>
			<td style="width: 300px;" ><textarea  name="sqlquery" cols='80' rows='8'><?php echo $sqlquery?></textarea></td>
		<td>(不要执行drop,delete命令)</td>
		</tr>
		
		<tr>
			<th >数据库密码：</th>
			<td style="width: 300px;" ><input type='password' style="height: 25px; width:90px" name='pwd'>(请输入您安装数据时候的密码)</td>
		<td></td>
		</tr>
	
		<tr>
			<th><input type="hidden" value="submit" name="save"/></th>
			<td colspan="2"><input type="submit" value="提  交" class="btn_orange" />
		</tr>
	</tbody>
</table>
</form>
<?php echo $filelist;?>





