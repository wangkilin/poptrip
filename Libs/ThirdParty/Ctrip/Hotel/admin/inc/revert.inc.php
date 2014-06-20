<?php 
include_once ("../appData/database.config.php");//加载整站系统的配置文件

$filedir="../data/dbbackup";

if(is_dir($filedir)){
	$dh = @dir($filedir);
	
	while(($filename=@$dh->read()) != false){
	    if(!preg_match("#sql$#", $filename))continue;
	    else 
	    $filelists[] = filesize("$filedir/$filename") >0?$filename:'';
	}
	$dh->close();
}else{
	echo '根目录下data/dbbackup/文件夹不存在，请先确保文件夹正常。';die;
}
if(!empty($_POST['dbbackup'])){
	
	if($_POST['pwd']!=$cfg_dbpwd){
		exit('数据库密码错误');	
	}
	
	 //打开文件  
    $fileName=$_POST['dbbackup'];
    $sqldata=file_get_contents($filedir .'/'. $fileName) ;

	ob_end_clean();
    header('Content-Encoding: none');
   	header('Content-Type: '.(strpos($HTTP_SERVER_VARS['HTTP_USER_AGENT'], 'MSIE') ? 'application/octetstream' : 'application/octet-stream'));
   	header('Content-Disposition: '.(strpos($HTTP_SERVER_VARS['HTTP_USER_AGENT'], 'MSIE') ? 'inline; ' : 'attachment; ').'filename="'.$fileName);
   	header('Content-Length: '.strlen($sqldata));
   	header('Pragma: no-cache');
   	header('Expires: 0');
   	echo $sqldata;	
} 


?>