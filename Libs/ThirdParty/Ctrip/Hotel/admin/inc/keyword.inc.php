<?php 
include_once (WEBROOT.'include/db.class.php');//加载数据库操作类
include_once (WEBROOT.'Common/toolExt.php');//加载通用工具函数
/**
 * keyword
 * 网页关键字管理类 
 * @author wulx
 *
 */
class keyword{
	/**
	 * $id
	 * 关键字ID
	 * @var int
	 */
	var $id=0;
	/**
	 * $pagename
	 * 是哪个页面的关键字
	 * @var string
	 */
	var $pagename;
	/**
	 * $page
	 * 页面的索引名称
	 * @var string
	 */
	var $page;
	/**
	 * $title
	 * 标题的规则
	 * @var string
	 */
	var $title;
	/**
	 * $keywords
	 * 关键字的规则
	 * @var string
	 */
	var $keywords;
	/**
	 * $description
	 * 页面描述的规则
	 * @var string
	 */
	var $description;
	/**
	 * $times
	 * 更新的日期
	 * @var date
	 */
	var $times;
	/**
	 * $rule
	 * 规则定义
	 * @var string
	 */
	var $rule;
	
	
	private  $keywords_table ;
	private  $db=null;	

 function __construct(){
	 	require (WEBROOT.'appData/database.config.php');
	 	 
	 	$this->keywords_table= $cfg_dbprefix."keywords";
	 	 
	 	$this->db=new DB($cfg_dbhost, $cfg_dbuser, $cfg_dbpwd, $cfg_dbname, $cfg_dbcharset, $cfg_dbprefix);
		 
	 }
	 
	 function __destruct(){
	 	$this->db->close();
	 	
	 }
	
	/**
	 *  loadAll()
	 * 加载所有的关键字信息
	 * @return Array 
	 */
	function loadAll(){
		$sql="select * from $this->keywords_table";
		return $this->db->getAll($sql);
	}
	/**
	 *  loadSingle()
	 * 加载单个关键字信息,并赋值给当前Keyword实例的相关属性
	 * @return bool true/false 
	 */
	function loadSingle($id){
		$sql="select id,pagename,page,title,keywords,description,times,rule from $this->keywords_table where id=$id";
		 if ($row=$this->db->getRow($sql))
		 {
		 	//print_r($row);
		 	// 查询到的结果赋值给当前实例的属性
		 	$this->id=$row['id'];
		 	$this->pagename=$row['pagename'];
		 	$this->page=$row['page'];
		 	$this->title=$row['title'];
		 	$this->keywords=$row['keywords'];
		 	$this->description=$row['description'];
		 	$this->times=$row['times'];
		 	$this->rule=$row['rule'];		 	
		 	return true;
		 }
		 return false;		 
	}
	/**
	 * save($id)
	 * 保存当前指定的关键字信息
	 *  
	 * @return bool true/false 
	 */
	function save(){
		if ($this->id==0)
			return false;
		$where="id=$this->id";
		$keyword['PageName']=$this->pagename;
		$keyword['Page']=$this->page;
		$keyword['Title']=$this->title;
		$keyword['Keywords']=$this->keywords;
		$keyword['Description']=$this->description;
		$keyword['Times']=$this->times;
		$keyword['Rule']=$this->rule;
		$rs= $this->db->autoExecute($this->keywords_table,$keyword,"UPDATE",$where);
		if ($rs){
			return $this->createKeywordCache();
		}
		return false;
	}
	/**
	 * createKeywordCache
	 * 生成网站关键字缓存文件appData/db_keyword.php
	 */
	function createKeywordCache(){
		$keywords=$this->loadAll();
		if ($keywords!=null){
			$str='<?php ';
			$str.="\n/**  \n";
			$str.="* \$keywordsArray 网站关键字数组 ，索引为页面名 \n";
			$str.="* 如：'index.php'=>array('title'=>'首页','keywords'=>'酒店预订','description'=>'携程酒店预订'),  \n";
			$str.="* @var 二维数组 \n";
			$str.=" */ \n";
			$str.="\$keywordsArray=array( ";	
			
			foreach ($keywords as $v){
				//$str.="\n'".htmlEncode($v['Page'])."'=>array('title'=>'".htmlEncode($v['Title'])."','keywords'=>'".htmlEncode($v['Keywords'])."','description'=>'".htmlEncode($v['Description'])."'),";
				$str.="\n'".$v['Page']."'=>array('title'=>'".$v['Title']."','keywords'=>'".$v['Keywords']."','description'=>'".$v['Description']."'),";
		
			}
			$str=rtrim($str,',');
			$str.="\n)\n\n?>";
			
			$keywordsFile=WEBROOT.'appData/db_keyword.php';
			if (is_writable($keywordsFile)) {
				//打开文件
				if (!$fh = fopen($keywordsFile, 'wb+')) {
					$msg = "不能打开文件". $keywordsFile;
					$rs = '0';
				}
				// 写入内容
				elseif (fwrite($fh, $str) === FALSE) {
					$msg = "不能写入到文件 ".$keywordsFile;
					$rs = '0';
				} else {
					$msg = "配置文件".$keywordsFile."保存成功";
					$rs = '1';
					fclose($fh);
				}
			} else {
				$msg = "配置文件". $keywordsFile ."不可写,请赋其可写权限";
				$rs = '0';
			}
			$write_info['msg'] = $msg;
			$write_info['rs'] = $rs;
			return $write_info;
		}
	}
}

//test

/*$kw=new keyword();
$rs=$kw->loadAll();
if ($rs){
	foreach ($rs as $k=>$v){
		print_r($v);
		echo $v['ID']."   |   ".$v['PageName']."  |   ".$v[2]."   |   ".$v[3]."  |   ".$v[4]."   |   ".$v[5]."  |   <br/><br/>";
	}
}
echo "<br/><br/>";
 $kw->loadSingle(4);
 echo $kw->pagename;
 $kw->pagename="酒店预定";
 $kw->save();
 $kw->loadSingle(4);
 echo "<br/><br/>";
  echo $kw->pagename;*/
 
?>
