<?php
/**
 * 本文件用于从镜像服务器获取升级信息与文件
 * 并由用户自行控制升级
 *
 */
@header("Content-type: text/html; charset=utf-8"); 
 if (!defined(WEBROOT)){
	 	define(WEBROOT, preg_replace("/admin/", '', dirname(__FILE__)));
	 }
require_once (WEBROOT.'Common/Session.php');//加载Session处理类
include_once(WEBROOT.'admin/inc/ctriphttpdown.class.php');//HTTP下载类
include_once(WEBROOT.'admin/inc/update.inc.php');//
include_once(WEBROOT.'admin/inc/common.php');//


//验证是否登录 
$session=new Session();
if (!$session->contain('admin')){
	redirect('login.php');
}

if (isset($_GET))
	extract($_GET);
if (isset($_POST))
	extract($_POST);

if(empty($dopost)) $dopost = 'test';

$WEBROOTIsWrite = TestIsFileDir(WEBROOT);
if($WEBROOTIsWrite['writeable']!='1')die('你的服务器没有写入权限，请先检查您的权限');


//当前软件版本锁定文件
$verLockFile = WEBROOT.'/admin/inc/ver.txt';

if($fp = @fopen($verLockFile,'r')){
	$upTime = trim(fread($fp,64));
	fclose($fp);
	$oktime = substr($upTime,0,4).'-'.substr($upTime,4,2).'-'.substr($upTime,6,2);
}
if(empty($upTime)){
	echo "您本地的版本控制文件被删除，导致无法确定您的版本号，无法正常更新。请直接从http://u.ctrip.com下载最近版本。";
}else{
	
/**
 * 匹配需要升级版本
 */	

if($dopost=='test')
{
    //下载远程数据
   	$verlist=@file_get_contents($updateHost.'/data/updates.txt');
    $verlists = explode("\n", $verlist);
    
    //分析数据
    $updateVers = array();
    $upitems = $lastTime = '';
    $n = 0;
    $m = 0;//有无可用更新
    
    $lastUpdateInfo=explode(',',$verlists['0']);
    $lastTime=$lastUpdateInfo['0'];//最后版本时间
    
    foreach($verlists as $verstr)
    {
        $verstr=trim($verstr);
    	if( empty($verstr) || preg_match("#^\/\/#", $verstr) ){
        	continue ;
        }  
        list($vtime, $vmsg) = explode(',', $verstr);
        $vtime = (int)trim($vtime);
        $vmsg = trim($vmsg);
        if($vtime>$upTime)$m++;
        
       // if($vtime>$upTime){//若需要隐藏已升级补丁，则打开
        	$updateTime = ($vtime>$upTime)?$vtime:'';
            if($updateTime)
            $upitems .= ($upitems=='' ? $updateTime : ','.$updateTime);//需要升级的版本
            $updateVers[$n]['isupdate'] = ($vtime>$upTime)?'未升级':'已升级';
            $updateVers[$n]['vmsg'] = $vmsg;  //更新简介
            $updateVers[$n]['vtime'] = substr($vtime,0,4).'-'.substr($vtime,4,2).'-'.substr($vtime,6,2);//更新时间
            $n++;
       // }
    }
        
        echo "<div class='notice_content'><table style='width:100%;'><tbody>
        <form name='fup' action='manage.php' method='post' >\r\n";
        echo "<input type='hidden' name='dopost' value='getlist' />\r\n";
        echo "<input type='hidden' name='vtime' value='$lastTime' />\r\n";
        echo "<input type='hidden' name='upitems' value='$upitems' />\r\n";
        If($m==0)
        echo "<tr><td colspan='3'>您系统版本最后更新时间为：{$oktime}，当前没有可用的更新</td></tr>\r\n";
        else
        echo "<tr><td colspan='3'>您系统版本最后更新时间为：{$oktime}</td></tr>\r\n";
        $i=0;
        foreach($updateVers as $vers)
        {
        	$i++;
			if($i>5){//取前5条更新数据
				break;
			}
            echo "<tr><td>";
            echo "{$vers['vmsg']}</td><td>".$vers['vtime']."</td><td style='text-align:center;'>".$vers['isupdate']."</td></tr>\r\n";
        }
       If($m>0) echo "<tr><td colspan='3'><input type='submit' name='sb1' value=' 点击此获取所有更新文件，然后选择安装 '  style='cursor:pointer' /></td></tr>\r\n";
        echo "</form></tbody>
			</table>
		</div>";
}


/**
获取升级文件列表
*/
else if($dopost=='getlist')
{
    $upitemsArr = explode(',', $upitems);
    rsort($upitemsArr);
    
    $tmpdir=$vtime;//date('Ymd');//默认程序下载文件夹20130222
    $dhd = new CtripHttpDown();
    
    $fileArr = array();
    $f = 0;
    foreach($upitemsArr as $upitem)
    {
        $durl = $updateHost.'/data/'.$upitem.'.file.txt';
        $dhd->OpenUrl($durl);
        $filelist = $dhd->GetHtml();
        $filelist = trim( preg_replace("#[\r\n]{1,}#", "\n", $filelist) );
        if(!empty($filelist))
        {
            $filelists = explode("\n", $filelist);
            foreach($filelists as $filelist)
            {
                $filelist = trim($filelist);
                if(empty($filelist)) continue;
                $fs = explode(',', $filelist);
                
                $fs[0]=(string)$fs[0];
                
                if( empty($fs[1]) ) 
                {
                    $fs[1] = " 常规功能更新文件";
                }
                if(!isset($fileArr[$fs[0]])) 
                {
                    $fileArr[$fs[0]] = $upitem." ".trim($fs[1]);
                    $f++;
                }
            }
        }else{
        	//$unfilelist .=$unfilelist.",".$upitem.'.file.txt';
        }
    }
    $DataIsWrite = TestIsFileDir("../data/");
    
    $allFileList = '';
    if($f==0)
    {
        $allFileList = "<font color='red'><b>没发现可用的文件列表信息，可能是官方服务器存在问题，请稍后再尝试！</b></font>";
    }
    else
    {
        $allFileList .= "<div class='notice_content'><table style='width:100%;'><tbody><form name='fup' action='manage.php' method='post'>\r\n";
        $allFileList .= "<input type='hidden' name='vtime' value='$vtime' />\r\n";
        $allFileList .= "<input type='hidden' name='dopost' value='getfiles' />\r\n";
        $allFileList .= "<input type='hidden' name='upitems' value='$upitems' />\r\n";
        $allFileList .= "<tr><td>以下是需要下载的更新文件（路径相对于uninhotel的根目录）：</td></tr>";
        $filelists = explode("\n",$filelist);
         
        foreach($fileArr as $k=>$v) 
        {
            $allFileList .= "<tr><td><span style='display:none;'><input type='checkbox' name='files[]' value='{$k}'  checked='checked' /></span> $k({$v})</td></tr>";
        }
       $allFileList .="<tr><td><span style='display:none;'><input type='checkbox' name='files[]' value='admin/'  checked='checked' /></span> admin/inc/ver.txt(版本控制文件)</td></tr>"; 
       
        if($DataIsWrite['writeable']=='1'){
        	$allFileList .= "<tr><td>";
	        $allFileList .= "文件临时存放目录：data/<input type='text' name='tmpdir' style='width:110px' value='$tmpdir' />(请输入规范的文件夹名称 文件名不能包含下列任何字符 \ / : * ? \" < >)</td></tr>";
	        $allFileList .= "&nbsp;\r\n";
        	$allFileList .= "<tr><td><input type='submit' name='sb1' value=' 检测系统目录 ' /></td></tr>";
        }else{
        	
			$allFileList .= "<tr><td ><h2>根目录下data文件夹没有写权限，系统无法下载更新文件</h2></td></tr>";
		}
        $allFileList .="</form></tbody>
					</table>
				</div>";
    }
    
   // if($unfilelist)echo $unfilelist."文件未找到，请联系我们的管理员";
   // else
    echo $allFileList;
}
/**
下载文件所在文件夹验证
*/

else if($dopost=='getfiles')
{
    $cacheFiles = WEBROOT.'/data/updatetmp.inc';
    $skipnodir = (isset($skipnodir) ? 1 : 0);
    $adminDir = preg_replace("#(.*)[\/\\\\]#", "", dirname(__FILE__));
    $tmpdir=trim($tmpdir);
    if(empty($tmpdir))die('下载目录为必填内容。');
	if(is_dir("../data/".$tmpdir."/")){
    	$DataIsWrite = TestIsFileDir("../data/".$tmpdir."/");
    	if($DataIsWrite['writeable']!='1'){
    		echo "根目录data文件夹下{$tmpdir}文件夹没有写入权限，请更换下载文件";die;
    	}
    	
    }
    
    if(!isset($files))
    {
    	$dirinfos = "<p align='center' style='color:red'><br />您没有指定任何需要下载更新的文件，是否跳过这些更新？<br /><br />";
        $dirinfos .= "<a href='manage.php' class='np coolbg'>[跳过这些更新]</a> &nbsp; ";
    }
    else
    {
        $fp = fopen($cacheFiles, 'w');
        fwrite($fp, '<'.'?php'."\r\n");
        fwrite($fp, '$tmpdir = "'.$tmpdir.'";'."\r\n");
        fwrite($fp, '$vtime = '.$vtime.';'."\r\n");
        $dirs = array();
        $i = -1;
        
        
        foreach($files as $filename)
        {
            
        	$tfilename = $filename;
            $arr=explode('/',$tfilename);
         	if(count($arr)>'1'){//根目录下的无需新建
	            $curdir="../".$arr[0]."/";
	            @mkdir($curdir, 0777);
	            if( !isset($dirs[$curdir]) ) 
	            {
	            	$dirs[$curdir] = TestIsFileDir($curdir);
	            }
	            if($skipnodir==1 && $dirs[$curdir]['isdir'] == FALSE) 
	            {
	            	continue;
	            }else {
	                @mkdir($curdir, 0777);
	                $dirs[$curdir] = TestIsFileDir($curdir);
	            }
	            if($dirs[$curdir]['writeable']!='1')
	            fwrite($fp, '$unableDir['.$arr[0].']=1;'."\r\n");
         	}
         	$i++;
            fwrite($fp, '$files['.$i.'] = "'.$filename.'";'."\r\n");
        }
        fwrite($fp, '$fileConut = '.$i.';'."\r\n");
        
/*
        $items = explode(',', $upitems);
        foreach($items as $sqlfile)
        {
            fwrite($fp,'$sqls[] = "'.$sqlfile.'.sql";'."\r\n");
        }
*/        
        
        fwrite($fp, '?'.'>');
        fclose($fp);
        
        
        $dirinfos = '';
        if($i > -1)
        {
            $dirinfos = "<div class='notice_content'>";
            $dirinfos .= "本次升级需要在下面文件夹写入更新文件，请注意文件夹是否有写入权限：<br />\r\n";
            foreach($dirs as $curdir)
            {
                $dirinfos .= $curdir['name']." 状态：".($curdir['writeable'] ? "[√正常]" : "<font color='red'>[×不可写]</font>")."<br />\r\n";
            }
            $dirinfos .= "\r\n";
        }
        
	$msg = "如果检测时发现您没安装模块的文件夹有错误，可不必理会<br />";
    $msg .= "<a href=manage.php?dopost=down&curfile=0>确认目录状态都正常后，请点击开始下载文件&gt;&gt;</a></div><br />";
    }
    echo $dirinfos."<br>".$msg;
    
}
/**
下载文件并备份
*/
else if($dopost=='down')
{
   $cacheFiles = WEBROOT.'/data/updatetmp.inc';
    require_once($cacheFiles);
    $Backupdir=$tmpdir."backup";
    
    if(empty($startup))
    {
        if($fileConut==-1 || $curfile > $fileConut)
        {
           
            
       		MkTmpDir($Backupdir, 'admin/inc/');
			if(!@copy(WEBROOT.'/admin/inc/ver.txt',WEBROOT.'/data/'.$Backupdir.'/admin/inc/ver.txt')){
	        	AlertMsg("版本控制文件备份失败，请检查您的网络或者联系我们的管理员。","javascript:;");
	        	die;
	        }
        	
        	
        	$datavar=WEBROOT."data/".$tmpdir.'/admin/inc/';
        	
	        if(!is_dir($datavar)) 
	        {
	            mkdir($datavar, 0777) or die($datavar);
	        }
           
           
           $fp=fopen(WEBROOT."data/".$tmpdir.'/admin/inc/ver.txt',"w+");
		   fwrite($fp,$vtime);
		  
		   $todayTime=date('Ymd');
		   
		   $downloadfile = print_r($files, true);
		   $fp=fopen(WEBROOT."data/".$tmpdir."/sucess.txt","w+");//下载文件记录
		   fwrite($fp,"文件下载成功！\r\n本次更新时间为：");
		   fwrite($fp,$todayTime."\r\n");
		   fwrite($fp, "版本控制文件目录:admin/inc/ver.txt(更新时候请一起更新，否则将影响更新)\r\n");
		   fwrite($fp, "本次下载文件:\r\n");
		   foreach($files as $v){
		   		fwrite($fp, $v."\r\n"); 
		   }
		   fclose($fp);
		   
        	if(!file_exists(WEBROOT."data/".$tmpdir.'/admin/inc/ver.txt')|| !file_exists(WEBROOT."data/".$tmpdir.'/sucess.txt')){
	        	AlertMsg("data/".$tmpdir."/admin/inc/ver.txt 版本控制文件写入失败或者data/".$tmpdir.'/sucess.txt更新失败，请检查您的网络或者联系我们的管理员。',"javascript:;");
	        	die;
	        }
		   
		   if($unableDir){
		   		AlertMsg("完成所有远程文件获取操作：但因为您的目录没有写权限请直接使用[../data/{$tmpdir}]目录的文件手动升级。","javascript:;");
		   }else{
           		AlertMsg("完成所有远程文件获取操作：<a href='manage.php?dopost=apply'>&lt;&lt;点击此开始直接升级&gt;&gt;</a><br />您也可以直接使用[../data/{$tmpdir}]目录的文件手动升级。","javascript:;");
		   }	
	}else{
       
        
        	//下载更新文件
	        MkTmpDir($tmpdir, $files[$curfile]);
            $webCurlFile=str_replace(".php",".ctrip",$files[$curfile]);
            $downfile = $updateHost.'/data/'.$webCurlFile;
            
	       // $downfile = $updateHost.'/data/'.$files[$curfile].".txt";
            
          //  echo $downfile;die;
            
	        $dhd = new CtripHttpDown();
	        $dhd->OpenUrl($downfile);
	        $dhd->SaveToBin(WEBROOT.'/data/'.$tmpdir.'/'.$files[$curfile]);
	        $dhd->Close();
	        if(!file_exists('../data/'.$tmpdir.'/'.$files[$curfile])){
	        	AlertMsg("{$files[$curfile]}下载失败，请检查您的网络或者联系我们的管理员。","javascript:;");
	        	die;
	        }

	        //保存备份
	        MkTmpDir($Backupdir, $files[$curfile]);
	        if(file_exists(WEBROOT.$files[$curfile])){//若没有文件则不备份
		 		@copy(WEBROOT.$files[$curfile],WEBROOT.'/data/'.$Backupdir.'/'.$files[$curfile]);
	 			if(!file_exists(WEBROOT.'/data/'.$Backupdir.'/'.$files[$curfile])){
		        	AlertMsg("{$files[$curfile]}备份失败，请检查您的网络或者您服务器文件夹权限。","javascript:;");
		        	die;
	 			}
	        }else{
	        	MkWebDir($files[$curfile]);//若无文件生成所有所需文件夹
	        }
	
	    
	        echo "成功下载保存并备份文件:".$files[$curfile] ."<br>";
        	AlertMsg("成功下载保存并备份文件：{$files[$curfile]}； 继续下载下一个文件。","manage.php?dopost=down&curfile=".($curfile+1));
        }
    }
    
    /*  数据库更新相关
    else
    {
    	
    	MkTmpDir($tmpdir, 'sql.txt');
        $dhd = new CtripHttpDown();
        $ct = '';
        
        foreach($sqls as $sql)
        {
            $downfile = $updateHost.'/'.$sql;
            $dhd->OpenUrl($downfile);
            $ct .= $dhd->GetHtml();
        }
        $dhd->Close();
        $truefile = WEBROOT.'/data/'.$tmpdir.'/sql.txt';
        $fp = fopen($truefile, 'w');
        fwrite($fp, $ct);
        fclose($fp);

        AlertMsg("完成所有远程文件获取操作：<a href='manage.php?dopost=apply'>&lt;&lt;点击此开始直接升级&gt;&gt;</a><br />您也可以直接使用[../data/{$tmpdir}]目录的文件手动升级。","javascript:;");

        exit();
    }
    exit();*/
}
/**
应用升级
*/
else if($dopost=='apply')
{
    $cacheFiles = WEBROOT.'/data/updatetmp.inc';
    require_once($cacheFiles);
    $step='1';
    
    
    if(empty($step))
    {//数据库更新 待以后版本
/*
    	$truefile = WEBROOT.'/data/'.$tmpdir.'/sql.txt';
        $fp = fopen($truefile, 'r');
        $sql = @fread($fp, filesize($truefile));
        fclose($fp);
        if(!empty($sql))
        {
            $sqls = explode(";\r\n", $sql);
            foreach($sqls as $sql)
            {
                if(trim($sql)!='') 
                {
                  
                }
            }
        }
        ShowMsg("完成数据库更新，现在开始复制文件。","manage.php?dopost=apply&step=1");
        exit();*/
    }
    else
    {
        $sDir = WEBROOT."/data/$tmpdir";
        $tDir = WEBROOT;
        $badcp = 0;
        $adminDir = preg_replace("#(.*)[\/\\\\]#", "", dirname(__FILE__));
		$badFile="";
        if(isset($files) && is_array($files))
        {
            foreach($files as $f)
            {
                $tf = $f;
                if($f=='admin/')continue;
                if(file_exists($sDir.'/'.$f))
                {
                	
                	$rs = @copy($sDir.'/'.$f, $tDir.'/'.$tf);
                    if($rs) {
                     //   unlink($sDir.'/'.$f);
                    }
                    else {
                        $badcp++;
                        $badFile .=$f.",";
                    }
                }else{
                	$badcp++;	
                	$badFile .=$f.",";
                }
            }
        }

        $badmsg = '！';
        if($badcp > 0)
        {
            $badcp++;//若有失败更新，则版本控制文件也不更新
        	$badFile .="admin/inc/ver.txt";
        	$badmsg = "，其中失败 {$badcp} 个文件<div  style='word-break:break-all;'>({$badFile})</div>请从临时目录[../data/{$tmpdir}]中取出这几个文件手动升级.<br />若想还原，请从临时目录[../data/{$tmpdir}backup]中手动还原。";
        	AlertMsg("没能成功完成升级{$badmsg}","javascript:;");
        }else{

	        if( @copy($sDir.'/admin/inc/ver.txt', $tDir.'/admin/inc/ver.txt')){  
		        AlertMsg("成功完成升级!<br />若想还原，请从临时目录[../data/{$tmpdir}backup]中手动还原。<br />","javascript:;");
	        }else{
	        	AlertMsg("版本控制文件(admin/inc/ver.txt)更新失败，请手动更新。<br />若想还原，请从临时目录[../data/{$tmpdir}backup]中手动还原。<br />","javascript:;");
	        	
	        }
        }
        
    }
}

}