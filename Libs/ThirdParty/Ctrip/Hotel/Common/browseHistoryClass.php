<?php
/**
 * 浏览记录类
 * @author 携程 分销联盟 cltang
 * @copyright 2012-8-30
 */
class browse_history_class
{
	// 判断Cookie是否存在
	static function is_set($cookieName) {
		return isset($_COOKIE[$cookieName]);
	}

	// 获取某个Cookie值
	static function get($name) {
		$value   = $_COOKIE[$name];
		$value   =  unserialize(base64_decode($value));
		return $value;
	}

	// 设置某个Cookie值
	static function set($name,$value,$expire=300000) {
		$expire=300000;
		$path='/';
		$domain='http://ctrip.com';
		$expire =   !empty($expire)?    time()+$expire  : 0;
		$value   =  base64_encode(serialize($value));
		setcookie($name, $value,$expire,$path,$domain);
		$_COOKIE[$name]  =   $value;
	}

	// 删除某个Cookie值
	static function delete($name) {
		cookie::set($name,'',time()-3600);
		unset($_COOKIE[$name]);
	}

	// 清空Cookie值
	static function clear() {
		unset($_COOKIE);
	}
	/**
	 *
	 * @var 保存酒店浏览的历史记录
	 * @param string $name 关键字
	 * @param string $id 内容 样式=“ 1000|酒店1”
	 * @param int $maxNum 保存的历史记录的总个数
	 */
	static function setListHotel($name,$id,$maxNum){
		//做COOKIE是否禁用的判断
		if(browse_history_class::checkCookieIsReady()==true||browse_history_class::checkCookieIsReady()=="1"||browse_history_class::checkCookieIsReady()==1)
		{
			$TempNum = $maxNum;//cookie里面存储多少个浏览记录
			if(isset($_COOKIE[$name]))//判断是否设置了COOKIE
			{
				$RecentlyGoods=$_COOKIE[$name];
				//$RecentlyGoods="1000|酒店1,1001|酒店2,1003|酒店3"
				$RecentlyGoodsArray=explode(",", $RecentlyGoods);
				$RecentlyGoodsNum=count($RecentlyGoodsArray); //RecentlyGoodsNum 当前存储的变量个数
				if (in_array($id, $RecentlyGoodsArray))
				{
					//echo "已经存在,则不写入COOKIES <hr />";
				}
				else
				{
					if($RecentlyGoodsNum <$TempNum) //如果COOKIES中的元素小于指定的大小，则直接进行输入COOKIES
					{
						if(empty($RecentlyGoods))
						{
							setcookie($name, $id, time()+3600*50, '/');
						}
						else
						{
							$RecentlyGoodsNew=$RecentlyGoods.",".$id;
							setcookie($name, $RecentlyGoodsNew,time()+3600*50, '/');
						}
					}
					else //如果大于了指定的大小后，将第一个给删去，在尾部再加入最新的记录。
					{
						$pos=strpos($RecentlyGoods,",")+1; //第一个参数的起始位置
						$FirstString=substr($RecentlyGoods,0,$pos); //取出第一个参数
						$RecentlyGoods=str_replace($FirstString,"",$RecentlyGoods); //将第一个参数删除
						$RecentlyGoodsNew=$RecentlyGoods.",".$id; //在尾部加入最新的记录g
						setcookie($name, $RecentlyGoodsNew,time()+3600*50,'/');
					}
				}
			}
			else
			{
				setcookie($name,$id,time()+3600*50, '/');
			}
		}
		else{
			//	echo "对不起！您的浏览器不支持cookie！";
		}
	}
	/**
	 *
	 * @var 删除指定keys的cookie
	 * @param unknown_type $name
	 */
	static function deleteListHotel($name,$keys,$maxNum)
	{
		try {
			//先根据$name获取到指定的cookie
			$cookieOld=$_COOKIE[$name];
			$keys1=",".$keys.",";
			$keys2=",".$keys;
			$keys3=$keys.",";
			$keyTemp="";
			echo "<br/>older".$cookieOld;
			echo "<br/>1".strpos($cookieOld, $keys1);
			echo "<br/>2".strpos($cookieOld, $keys2);
			echo "<br/>3".strpos($cookieOld, $keys3);
			if(strpos($cookieOld, $keys1)>0)
			{
				$keyTemp=$keys3;//要保留一个逗号
			}
			else if(strpos($cookieOld, $keys2)>0)
			{
				$keyTemp=$keys2;
			}
			else
			{
				if(strpos($cookieOld,",")>0)
				{
					$keyTemp=$keys3;
				}
				else {
					$keyTemp=$keys;
				}
			}
			$cookieNew=str_replace($keyTemp,"",$cookieOld);
			echo "<br/>".$keyTemp."<br/>".$cookieNew;
			setcookie($name, $cookieNew,time()+3600*50, '/');
			return true;
		}
		catch(Exception $e){
			return  false;
		}
	}
	/**
	 *
	 * 判断当前的浏览器是否能使用cookie
	 */
	function checkCookieIsReady()
	{
		$coutw=false;
		setcookie("cookieTest_hotelSystem2012", "1",time()+3600*50,'/');
		if($_COOKIE["cookieTest_hotelSystem2012"]=="1")
		{
			$coutw=true;
		}
		//if($_COOKIE){$coutw=true;}
		//else{$coutw=false;}
		//echo json_encode($_COOKIE);
		return $coutw;
	}
}
?>
