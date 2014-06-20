<?php
/**
 *
 * 负责URL伪静态化处理
 * @author cltang
 *
 */
class url {
	/**
	 *
	 * 将传入的URL地址伪静态化
	 * @param unknown_type $url
	 */
	public function addurl($url) {
		//$file = explode(".",$filename);
		//$val = explode("=",$value);
		return $url=$this->MakeUrlHtml($url);// =$this->getHostAndPort()."/".$file[0]."/".$val[0]."/".$val[1].".html";
	}

	public function pdurl($inUrl){
		//取得当前url
		$getUrl=empty($_SERVER['HTTP_X_REWRITE_URL'])?$_SERVER['REQUEST_URI']:$_SERVER['HTTP_X_REWRITE_URL'];//iis下支持伪静态
		$url = $this->getHostAndPort().$getUrl;
		//标准的url地址
		$newurl = $this->addurl($inUrl);
     
		if($url != $newurl){
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: ".$newurl);
			exit(); 

   }

	
            
            
	}
	/*
	 * 获取到域名及端口号
	 */
	public  function getHostAndPort()
	{
		$port=$_SERVER['SERVER_PORT'];
		if($port=="80")
		{
			$port="";
		}
		else
		{
			$port=":".$port;
		}
		return "http://".$_SERVER['SERVER_NAME'].$port;
	}
	/**
	 * 获取当前URL的全地址
	 * Enter description here ...
	 */
	public function getUrlAll()
	{
		return $this->getHostAndPort().$_SERVER["REQUEST_URI"];
	}

	/******************************
	 使用参考：
	 将此http://www.***.cn/company/companyshow.php?action=list&page=2
	 地址生成http://www.***.cn/company/companyshow.php/action_list/page_2.htm形式
	 $url="http://www.***.cn/company/companyshow.php?action=list&page=2";
	 $urlStr=MakeUrlHtml($url);
	 echo $urlStr;
	 *****************************
	 *将指定的URL地址通过规则变成符合SEO伪静态化的URL地址
	 */
	function MakeUrlHtml($url)
	{
		if(strpos($url,"?")>0)
		{
			$durl=parse_url($url);
			$urlStr="";
			if($durl['host']) $urlStr.=$durl['scheme']."://".$durl['host'].$this->getPort();
			if($durl['path']) $urlStr.=$durl['path'];

			$urlStr=str_replace(".php","",$urlStr);
			if($durl["query"]){
				$durl=explode("&",$durl["query"]);
				if($durl!=null&&$durl!="")
				{
					foreach($durl as $surl)
					{
						$gurl=explode("=",$surl);
						//$eurl[]=$gurl[0]."_".urlencode($gurl[1]);
						$eurl[]=$gurl[0]."_".$gurl[1];
					}
				}
				$tmpurl=join("/",$eurl).".html";
				$urlStr.="/".$tmpurl;
			}
		}
		else {
			$urlStr=str_replace(".php",".html",$url);
		}
		return $urlStr;
	}

	/******************************
	 描 述：完成将伪静态地址还原成$_GET形式
	 使用参考：
	 将此http://www.***.cn/company/companyshow.php/action_list/page_2.htm地址
	 还原出$_GET["action"]=list/$_GET["page"]=2
	 ParseUrl();
	 *****************************
	 *将伪静态化后的url地址还原
	 */
	function ParseUrl(){
		if($_SERVER['PATH_INFO']!=""){
			$pathinfo=substr($_SERVER['PATH_INFO'],1);
			$pathinfo=str_replace(".htm","",$pathinfo);
			$tmp=explode("/",$pathinfo);
			foreach($tmp as $val){
				$path=explode("_",$val);
				$count=count($path);
				for($i=0;$i<$count;$i+=2){
			 	$_GET[$path[$i]]=$path[$i+1];
				}
			}
		}
	}
	/*
	 * 获取到域名及端口号
	 */
	function getPort()
	{
		$port=$_SERVER['SERVER_PORT'];
		if($port=="80")
		{
			$port="";
		}
		else
		{
			$port=":".$port;
		}
		return  $port;
	}
}
?>