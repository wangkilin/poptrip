<?php
/**
 *
 * 系统控制酒店详情和酒店列表的统一控制 2012-10-10
 * @author cltang
 *
 */
class HotelUrlControl{
	/**
	 *
	 * @var 城市的ID,城市名称,酒店的ID--cityid,cityname,hotelID(在酒店详细时加上酒店的ID,列表不加)
	 * @var 字符串
	 */
	private  $city="";
	/**
	 *
	 * @var 入店时间，离店时间--checkindate,checkoutdate
	 * @var 字符串
	 */
	private  $cdate="";
	/**
	 *
	 * @var 星级，品牌--star;hotelbrand
	 * @var 字符串
	 */
	private $stb="";

	/**
	 *
	 * @var 价格区间--最低价格-最高价格
	 * @var 字符串
	 */
	private $price="";

	/**
	 *
	 * @var 酒店名称（模糊查询）
	 * @var 字符串
	 */
	private $hname="";
	/**
	 *
	 * @var 行政区ID,行政区名称-商业区ID,商业区名称-景区ID,景区名称
	 * @var 字符串
	 */
	private $lzod="";
	/**
	 *
	 * @var 酒店设施
	 * @var 字符串
	 */
	private $hf="";
	/**
	 *
	 * @var 排序的名称--Recommend,DESC
	 * @var 字符串
	 */
	private $oy="";
	/**
	 *
	 * @var 当前页码，每页显示数--例如1,10
	 * @var 字符串
	 */
	private $pf="";
	/**
	 * 
	 * @var 返回构造好的URL地址
	 * @var 字符串
	 */
	var $returnUrl="";
	/**
	 *
	 * @var 做系统中的酒店详细的URL地址
	 */
	function hotelDetailUrl()
	{
		$url=$this->getHostAndPort()."/site/hoteldetail.php?";
		$url=$url."city=".$this->city;//城市的ID,城市名称,酒店的ID
		//$url=$url."&cdate=".$this->cdate;//入店时间，离店时间
		//$url=$url."&stb=".$this->stb;//星级，品牌
		//$url=$url."&price=".$this->price;//价格区间
		//$url=$url."&hname=".$this->hname;// 酒店名称（模糊查询）
		//$url=$url."&lzod=".$this->lzod;//行政区ID,行政区名称-商业区ID,商业区名称-景区ID,景区名称
		//$url=$url."&hf=".$this->hf;//酒店设施
		//$url=$url."&oy=".$this->oy;//排序的名称
		//$url=$url."&pf=".$this->pf;//当前页码，每页显示数

		return   $url;
	}
	/**
	 *
	 * @var 做系统中的酒店列表的URL地址
	 */
	function hotelListUrl()
	{
		$url=$this->getHostAndPort()."/site/hotelsearch.php?";
		$url=$url."city=".$this->city;//城市的ID,城市名称
	//	$url=$url."&cdate=".$this->cdate;//入店时间，离店时间
		if($this->stb && $this->stb!=';')
		$url=$url."&stb=".$this->stb;//星级，品牌
	//	$url=$url."&price=".$this->price;//价格区间
		if($this->hname)
		$url=$url."&hname=".$this->hname;// 酒店名称（模糊查询）
		if($this->lzod)
		$url=$url."&lzod=".$this->lzod;//行政区ID,行政区名称-商业区ID,商业区名称-景区ID,景区名称
		if($this->hf)
		$url=$url."&hf=".$this->hf;//酒店设施
		//$url=$url."&oy=".$this->oy;//排序的名称
		$url=$url."&pf=".$this->pf;//当前页码，每页显示数

		return  $url;
	}
	/*
	 * 获取到域名及端口号
	 */
	public  function getHostAndPort()
	{
		global $UnionSite_domainName;
		return $UnionSite_domainName;//处理站点放在二级目录下面找不到路径，从配置中获取
		/*$port=$_SERVER['SERVER_PORT'];
		if($port=="80")
		{
			$port="";
		}
		else
		{
			$port=":".$port;
		}
		return   "http://".$_SERVER['SERVER_NAME'].$port;*/
	}

	/**
	 *
	 * @var 构造函数-获取酒店列表和详情的页面URL地址
	 * @param $city
	 * @param $cdate
	 * @param $stb
	 * @param $price
	 * @param $hname
	 * @param $lzod
	 * @param $hf
	 * @param $oy
	 * @param $pf
	 * @param $urlType 类型：detail-详情页；list-列表页
	 */
	function __construct($city,$cdate,$stb,$price,$hname,$lzod,$hf,$oy,$pf,$urlType)
	{
		//如果入店时间和离店时间为空，则生成一个当前时间和系统默认时间
		if($cdate==""||$cdate==null)
		{
			global $HotelSearchDayNums;
			$cdate=getDateYMD("-").",".getDateYMD_addDay("-",$HotelSearchDayNums);
		}
		//如果当前第几页和每页多个酒店
		if($pf==""||$pf==null)
		{
			$pf="1,10";
		}
		$this->cdate=$cdate;
		$this->city=$city;
		$this->stb=$stb;
		$this->price=$price;
		$this->hname=$hname;
		$this->lzod=$lzod;
		$this->hf=$hf;
		$this->oy=$oy;
		$this->pf=$pf;

		if($urlType=="detail")
		{
		    $this->returnUrl=$this->hotelDetailUrl();//调用获取详情页的地址
		}
		else if($urlType=="list")
		{
			$this->returnUrl=$this->hotelListUrl();//调用获取酒店列表的地址
		}
	}
	/**
	 *
	 * 析构函数，当类不在使用的时候调用，该函数用来释放资源
	 */
	function __destruct()
	{
		unset($city);
		unset($cdate);
		unset($stb);
		unset($price);
		unset($hname);
		unset($lzod);
		unset($hf);
		unset($oy);
		unset($pf);
		unset($urlType);
	}
}
?>