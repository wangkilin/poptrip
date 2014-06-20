<?php
class SubPages{
	/**
	 * @var 每页显示的条目数
	 */
	private  $each_disNums;//每页显示的条目数
	/**
	 * @var 总条目数
	 */
	private  $nums;//总条目数
	/**
	 * @var 当前被选中的页
	 */
	private  $current_page;//当前被选中的页
	 /**
	 * @var 每次显示的页数
	 */
	private  $sub_pages;//每次显示的页数
	
	var  $pageNums;//总页数
	private  $page_array = array();//用来构造分页的数组
	/**
	 * @var 每个分页的链接
	 */
	private  $subPage_link;//每个分页的链接
	/**
	 * @var 显示分页的类型
	 */
	private  $subPage_type;//显示分页的类型
	/*     __construct是SubPages的构造函数，用来在创建类的时候自动运行.
	 *@$each_disNums   每页显示的条目数
	 *@nums     总条目数
	 *@current_num     当前被选中的页
	 *@sub_pages       每次显示的页数  （每次可以直接选择的页码）
	 *@subPage_link    每个分页的链接
	 *@subPage_type    显示分页的类型          当@subPage_type=1的时候为普通分页模式
	 *example：   共4523条记录,每页显示10条,当前第1/453页 [首页] [上页] [下页] [尾页]
	 *当@subPage_type=2的时候为经典分页样式
	 *example：   当前第1/453页 [首页] [上页] 1 2 3 4 5 6 7 8 9 10 [下页] [尾页]
	 */

	function __construct($each_disNums,$nums,$current_page,$sub_pages,$subPage_link,$subPage_type)
	{     if($each_disNums>0&&$nums>0){
		$this->each_disNums=intval($each_disNums);
		$this->nums=intval($nums);
		if(!$current_page)
		{
			$this->current_page=1;
		}
		else{
			$this->current_page=intval($current_page);
		}
		$this->sub_pages=intval($sub_pages);
		$this->pageNums=ceil($nums/$each_disNums);
		if($this->pageNums<$current_page)
		{
			$this->current_page=$this->pageNums;
		}
		$this->subPage_link=$subPage_link;
		$this->show_SubPages($subPage_type);
		//echo $this->pageNums."--".$this->sub_pages;
	}
	}
	/*      __destruct析构函数，当类不在使用的时候调用，该函数用来释放资源。     */
	function __destruct(){
		unset($each_disNums);
		unset($nums);
		unset($current_page);
		unset($sub_pages);
		unset($pageNums);
		unset($page_array);
		unset($subPage_link);
		unset($subPage_type);
	}
	/*      show_SubPages函数用在构造函数里面。而且用来判断显示什么样子的分页       */
	function show_SubPages($subPage_type){
		if($subPage_type == 1){
			$this->subPageCss1();
		}
		elseif ($subPage_type == 2){
			$this->subPageCss2();
		}
		elseif($subPage_type == 3)
		{
			$this->subPageCss3();
		}
		elseif($subPage_type==4)
		{
			$this->subPageCss4();
		}
		elseif($subPage_type==5)
		{
			$this->subPageCss5();
		}
		elseif($subPage_type==6)
		{
			$this->subPageCss6();
		}
		elseif($subPage_type==7)
		{
			$this->subPageCss7();
		}
		elseif($subPage_type==8)
		{
			$this->subPageCss8();
		}
	}
	/*      用来给建立分页的数组初始化的函数。     */
	function initArray(){
		for($i=0;$i<$this->sub_pages;$i++){
			$this->page_array[$i]=$i;
		}
		return $this->page_array;
	}
	/*      construct_num_Page该函数使用来构造显示的条目      即使：[1][2][3][4][5][6][7][8][9][10]     */
	function construct_num_Page(){
		if($this->pageNums < $this->sub_pages){
			$current_array=array();
			for($i=0;$i<$this->pageNums;$i++){
				$current_array[$i]=$i+1;
			}
		}else{
			$current_array=$this->initArray();
			if($this->current_page <= 3){
				for($i=0;$i<count($current_array);$i++){
					$current_array[$i]=$i+1;
				}
			}elseif ($this->current_page <= $this->pageNums && $this->current_page > $this->pageNums - $this->sub_pages + 1 ){
				for($i=0;$i<count($current_array);$i++){
					$current_array[$i]=($this->pageNums)-($this->sub_pages)+1+$i;
				}
			}else{
				for($i=0;$i<count($current_array);$i++){
					$current_array[$i]=$this->current_page-2+$i;
				}
			}
		}
		return $current_array;
	}
	/*     构造普通模式的分页     共4523条记录,每页显示10条,当前第1/453页 [首页] [上页] [下页] [尾页]     */
	function subPageCss1(){
		$subPageCss1Str="";
		$subPageCss1Str.="共".$this->nums."条记录，";
		$subPageCss1Str.="每页显示".$this->each_disNums."条，";
		$subPageCss1Str.="当前第".$this->current_page."/".$this->pageNums."页 ";
		if($this->current_page > 1){
			$firstPageUrl=$this->subPage_link."1";
			$prewPageUrl=$this->subPage_link.($this->current_page-1);
			$subPageCss1Str.="[<a href='$firstPageUrl'>首页</a>] ";
			$subPageCss1Str.="[<a href='$prewPageUrl'>上一页</a>] ";
		}else {
			$subPageCss1Str.="[首页] ";
			$subPageCss1Str.="[上一页] ";
		}
		if($this->current_page < $this->pageNums){
			$lastPageUrl=$this->subPage_link.$this->pageNums;
			$nextPageUrl=$this->subPage_link.($this->current_page+1);
			$subPageCss1Str.=" [<a href='$nextPageUrl'>下一页</a>] ";
			$subPageCss1Str.="[<a href='$lastPageUrl'>尾页</a>] ";
		}else {
			$subPageCss1Str.="[下一页] ";
			$subPageCss1Str.="[尾页] ";
		}
		echo $subPageCss1Str;
	}
	/*     构造经典模式的分页     当前第1/453页 [首页] [上页] 1 2 3 4 5 6 7 8 9 10 [下页] [尾页]     */
	function subPageCss2(){
		$subPageCss2Str="";
		$subPageCss2Str.="当前第".$this->current_page."/".$this->pageNums."页 ";
		if($this->current_page > 1){
			$firstPageUrl=$this->subPage_link."1";
			$prewPageUrl=$this->subPage_link.($this->current_page-1);
			$subPageCss2Str.="[<a href='$firstPageUrl'>首页</a>] ";
			$subPageCss2Str.="[<a href='$prewPageUrl'>上一页</a>] ";
		}else {
			$subPageCss2Str.="[首页] ";
			$subPageCss2Str.="[上一页] ";
		}
		$a=$this->construct_num_Page();
		for($i=0;$i<count($a);$i++){
			$s=$a[$i];
			if($s == $this->current_page ){
				$subPageCss2Str.="[<span style='color:red;font-weight:bold;'>".$s."</span>]";
			}else{
				$url=$this->subPage_link.$s;
				$subPageCss2Str.="[<a href='$url'>".$s."</a>]";
			}
		}
		if($this->current_page < $this->pageNums){
			$lastPageUrl=$this->subPage_link.$this->pageNums;
			$nextPageUrl=$this->subPage_link.($this->current_page+1);
			$subPageCss2Str.=" [<a href='$nextPageUrl'>下一页</a>] ";
			$subPageCss2Str.="[<a href='$lastPageUrl'>尾页</a>] ";
		}else {
			$subPageCss2Str.="[下一页] ";
			$subPageCss2Str.="[尾页] ";
		}
		echo $subPageCss2Str;
	}
	/*     构造普通模式的分页     共4523条记录,每页显示10条,当前第1/453页 [首页] [上页] [下页] [尾页] --符合微酒店的分页样式    */
	function subPageCss3(){
		$subPageCss2Str="";
		$subPageCss2Str.="<a>当前第".$this->current_page."/".$this->pageNums."页</a>&nbsp;";
		if($this->current_page > 1){
			$firstPageUrl=$this->subPage_link."1";
			$prewPageUrl=$this->subPage_link.($this->current_page-1);
			$subPageCss2Str.="<a href='$firstPageUrl'>首页</a>&nbsp;";
			$subPageCss2Str.="<a href='$prewPageUrl'>上一页</a>&nbsp;";
		}else {
			$subPageCss2Str.="<a>首页 </a>&nbsp;";
			$subPageCss2Str.="<a>上一页</a>&nbsp;";
		}
		$a=$this->construct_num_Page();
		for($i=0;$i<count($a);$i++){
			$s=$a[$i];
			if($s == $this->current_page ){
				$subPageCss2Str.="<a  class=\"current\">".$s."</a>&nbsp;";
			}else{
				$url=$this->subPage_link.$s;
				$subPageCss2Str.="<a href='$url'>".$s."</a>&nbsp;";
			}
		}
		if($this->current_page < $this->pageNums){
			$lastPageUrl=$this->subPage_link.$this->pageNums;
			$nextPageUrl=$this->subPage_link.($this->current_page+1);
			$subPageCss2Str.=" <a href='$nextPageUrl'>下一页</a>&nbsp;";
			$subPageCss2Str.="<a href='$lastPageUrl'>尾页</a>&nbsp;";
		}else {
			$subPageCss2Str.="<a>下一页</a>&nbsp;";
			$subPageCss2Str.="<a>尾页</a>&nbsp;";
		}
		echo $subPageCss2Str;
	}
	/* 构造普通模式的分页  <|1...34 35 36 37 38 39 40 41 下一页|> 到 69 页 确定    --符合微酒店的分页样式    */
	function subPageCss4(){
		$subPageCss2Str="";
		$subPageCss2Str.="";
		if($this->current_page > 1){
			$firstPageUrl=$this->makeUrl($this->subPage_link, 1);//   $this->subPage_link."1";
			$prewPageUrl=$this->makeUrl($this->subPage_link, $this->current_page-1);//$this->subPage_link.($this->current_page-1);
			$subPageCss2Str.="<a href='$prewPageUrl' class=\"up\"><span></span></a>";
			$subPageCss2Str.="<div class=\"page_list basefix\"><a href='$firstPageUrl'>1</a>";

		}else {
			$subPageCss2Str.="<a href=\"#\" class=\"up disable_up\"><span></span></a><div class=\"page_list basefix\">";
			$subPageCss2Str.="<a class='current'>1</a>";
		}
		$a=$this->construct_num_Page();
		if(count($a)>1)
		{
			if($a[1]>2){
				//把。。。显示出来
				$subPageCss2Str.="<span class=\"ellipsis\">...</span>";
			}
		}
		$isHaveLast=false;//当前是否已经包含了最后一页
		if($a[count($a)-1]==$this->pageNums)
		{
			$isHaveLast=true;
		}
		for($i=0;$i<count($a);$i++){
			$s=$a[$i];
			if($s!=1){
				if($s == $this->current_page ){
					$subPageCss2Str.="<a   class=\"current\">".$s."</a>";
				}else{
					$url=$this->makeUrl($this->subPage_link, $s);//$this->subPage_link.$s;
					$subPageCss2Str.="<a href='$url'>".$s."</a>";
				}
			}
		}
		if($this->current_page < $this->pageNums){
			$lastPageUrl=$this->makeUrl($this->subPage_link, $this->pageNums);//$this->subPage_link.$this->pageNums;
			$nextPageUrl=$this->makeUrl($this->subPage_link, $this->current_page+1);//$this->subPage_link.($this->current_page+1);
			$lastPageShowPageNums="<span class=\"ellipsis\">...</span><a href='$lastPageUrl'>$this->pageNums</a>";
			if($isHaveLast)
			{
				$lastPageShowPageNums="";
			}
			$subPageCss2Str.=$lastPageShowPageNums."</div> <a href='$nextPageUrl' class=\"next\">下一页<span></span></a>";
			//$subPageCss2Str.="<a href='$lastPageUrl'>$this->pageNums</a>&nbsp;";
		}else {
			$lastPageShowPageNums="<span class=\"ellipsis\">...</span>$this->pageNums</a>";
			if($isHaveLast)
			{
				$lastPageShowPageNums="";
			}
			$subPageCss2Str.=$lastPageShowPageNums."</div><a class=\"next\">下一页<span></span></a>";
			//$subPageCss2Str.="<a>$this->pageNums</a>&nbsp;";
		}
		//如果是最后一个页码，则不显示...和最后那个数据
		echo $subPageCss2Str;
	}
	/* 构造普通模式的分页     上一页      3/19  下一页     */
	function subPageCss5(){
		$subPageCss1Str="";
		$prewPageUrl=$this->makeUrl($this->subPage_link, $this->current_page-1);//上一页
		$nextPageUrl=$this->makeUrl($this->subPage_link,$this->current_page+1);//下一页
		$yema="<span>".$this->current_page."/".$this->pageNums."</span>";//当前页/总页数

		if($this->current_page==1){
			//第一页
			if($this->pageNums!=1)
			{
				$subPageCss1Str="<a>上一页</a>".$yema."<a href='$nextPageUrl'>下一页</a>";
			}
			else
			{
				$subPageCss1Str="<a>上一页</a>".$yema."<a>下一页</a>";
			}
		}
		else if($this->pageNums==$this->current_page)
		{
			//最后一页
			$subPageCss1Str="<a href='$prewPageUrl'>上一页</a> ".$yema."<a>下一页</a>";
		}
		else{
			//中间页码
			$subPageCss1Str="<a href='$prewPageUrl'>上一页</a> ".$yema."<a href='$nextPageUrl'>下一页</a>";
		}
		echo $subPageCss1Str;
	}
	
/* 构造普通模式的分页,添加onclick  post  <|1...34 35 36 37 38 39 40 41 下一页|> 到 69 页 确定    --符合微酒店的分页样式    */
	function subPageCss6(){
		$subPageCss2Str="";
		$subPageCss2Str.="";
		if($this->current_page > 1){
			$firstPageUrl=$this->makeUrl($this->subPage_link, 1);//   $this->subPage_link."1";
			$prewPageUrl=$this->makeUrl($this->subPage_link, $this->current_page-1);//$this->subPage_link.($this->current_page-1);
			$subPageCss2Str.="<a onclick=\"CtripSelfPassParams('POST', $('#postDataUrl').value(), this.rel, '_self')\" href='javascript:;' rel='$prewPageUrl' class=\"up\"><span></span></a>";
			$subPageCss2Str.="<div class=\"page_list basefix\"><a  onclick=\"CtripSelfPassParams('POST', $('#postDataUrl').value(), this.rel, '_self')\" href='javascript:;' rel='$firstPageUrl'>1</a>";

		}else {
			$subPageCss2Str.="<a href=\"#\" class=\"up disable_up\"><span></span></a><div class=\"page_list basefix\">";
			$subPageCss2Str.="<a class='current'>1</a>";
		}
		$a=$this->construct_num_Page();
		if(count($a)>1)
		{
			if($a[1]>2){
				//把。。。显示出来
				$subPageCss2Str.="<span class=\"ellipsis\">...</span>";
			}
		}
		$isHaveLast=false;//当前是否已经包含了最后一页
		if($a[count($a)-1]==$this->pageNums)
		{
			$isHaveLast=true;
		}
		for($i=0;$i<count($a);$i++){
			$s=$a[$i];
			if($s!=1){
				if($s == $this->current_page ){
					$subPageCss2Str.="<a   class=\"current\">".$s."</a>";
				}else{
					$url=$this->makeUrl($this->subPage_link, $s);//$this->subPage_link.$s;
					$subPageCss2Str.="<a onclick=\"CtripSelfPassParams('POST', $('#postDataUrl').value(), this.rel, '_self')\" href='javascript:;' rel='$url'>".$s."</a>";
				}
			}
		}
		if($this->current_page < $this->pageNums){
			$lastPageUrl=$this->makeUrl($this->subPage_link, $this->pageNums);//$this->subPage_link.$this->pageNums;
			$nextPageUrl=$this->makeUrl($this->subPage_link, $this->current_page+1);//$this->subPage_link.($this->current_page+1);
			$lastPageShowPageNums="<span class=\"ellipsis\">...</span><a onclick=\"CtripSelfPassParams('POST', $('#postDataUrl').value(), this.rel, '_self')\" href='javascript:;' rel='$lastPageUrl'>$this->pageNums</a>";
			if($isHaveLast)
			{
				$lastPageShowPageNums="";
			}
			$subPageCss2Str.=$lastPageShowPageNums."</div> <a onclick=\"CtripSelfPassParams('POST', $('#postDataUrl').value(), this.rel, '_self')\" href='javascript:;' rel='$nextPageUrl' class=\"next\">下一页<span></span></a>";
			//$subPageCss2Str.="<a href='$lastPageUrl'>$this->pageNums</a>&nbsp;";
		}else {
			$lastPageShowPageNums="<span class=\"ellipsis\">...</span>$this->pageNums</a>";
			if($isHaveLast)
			{
				$lastPageShowPageNums="";
			}
			$subPageCss2Str.=$lastPageShowPageNums."</div><a class=\"next\">下一页<span></span></a>";
			//$subPageCss2Str.="<a>$this->pageNums</a>&nbsp;";
		}
		//如果是最后一个页码，则不显示...和最后那个数据
		echo $subPageCss2Str;
	}
	
/* 构造普通模式的分页 添加ONCLICK事件    上一页      3/19  下一页     */
	function subPageCss7(){
		$subPageCss1Str="";
		$prewPageUrl=$this->makeUrl($this->subPage_link, $this->current_page-1);//上一页
		$nextPageUrl=$this->makeUrl($this->subPage_link,$this->current_page+1);//下一页
		$yema="<span>".$this->current_page."/".$this->pageNums."</span>";//当前页/总页数

		if($this->current_page==1){
			//第一页
			if($this->pageNums!=1)
			{
				$subPageCss1Str="<a>上一页</a>".$yema."<a onclick=\"CtripSelfPassParams('POST',  this.rel,$('#postData').value(), '_self')\" href='javascript:;' rel='$nextPageUrl'>下一页</a>";
			}
			else
			{
				$subPageCss1Str="<a>上一页</a>".$yema."<a>下一页</a>";
			}
		}
		else if($this->pageNums==$this->current_page)
		{
			//最后一页
			$subPageCss1Str="<a onclick=\"CtripSelfPassParams('POST',  this.rel,$('#postData').value(), '_self')\" href='javascript:;' rel='$prewPageUrl'>上一页</a> ".$yema."<a>下一页</a>";
		}
		else{
			//中间页码
			$subPageCss1Str="<a onclick=\"CtripSelfPassParams('POST',  this.rel, $('#postData').value(), '_self')\" href='javascript:;' rel='$prewPageUrl'>上一页</a> ".$yema."<a onclick=\"CtripSelfPassParams('POST',  this.rel,$('#postData').value(), '_self')\" href='javascript:;' rel='$nextPageUrl'>下一页</a>";
		}
		echo $subPageCss1Str;
	}
	
	
/* 构造普通模式的分页  <|1...34 35 36 37 38 39 40 41 下一页|> 到 69 页 确定    --符合微酒店的分页样式    */
	function subPageCss8(){
		$subPageCss2Str="";
		$subPageCss2Str.="";
		if($this->current_page > 1){
			$firstPageUrl=$this->makeUrl($this->subPage_link, 1);//   $this->subPage_link."1";
			$prewPageUrl=$this->makeUrl($this->subPage_link, $this->current_page-1);//$this->subPage_link.($this->current_page-1);
			$subPageCss2Str.="<a onclick=\"CtripSelfPassParams('POST',  this.rel, $('#postData').value(), '_self')\" href='javascript:;' rel='$prewPageUrl' class=\"up\"><span></span></a>";
			$subPageCss2Str.="<div class=\"page_list basefix\"><a onclick=\"CtripSelfPassParams('POST',  this.rel, $('#postData').value(), '_self')\" href='javascript:;' rel='$firstPageUrl'>1</a>";

		}else {
			$subPageCss2Str.="<a href=\"#\" class=\"up disable_up\"><span></span></a><div class=\"page_list basefix\">";
			$subPageCss2Str.="<a class='current'>1</a>";
		}
		$a=$this->construct_num_Page();
		if(count($a)>1)
		{
			if($a[1]>2){
				//把。。。显示出来
				$subPageCss2Str.="<span class=\"ellipsis\">...</span>";
			}
		}
		$isHaveLast=false;//当前是否已经包含了最后一页
		if($a[count($a)-1]==$this->pageNums)
		{
			$isHaveLast=true;
		}
		for($i=0;$i<count($a);$i++){
			$s=$a[$i];
			if($s!=1){
				if($s == $this->current_page ){
					$subPageCss2Str.="<a   class=\"current\">".$s."</a>";
				}else{
					$url=$this->makeUrl($this->subPage_link, $s);//$this->subPage_link.$s;
					$subPageCss2Str.="<a onclick=\"CtripSelfPassParams('POST',  this.rel, $('#postData').value(), '_self')\" href='javascript:;' rel='$url'>".$s."</a>";
				}
			}
		}
		if($this->current_page < $this->pageNums){
			$lastPageUrl=$this->makeUrl($this->subPage_link, $this->pageNums);//$this->subPage_link.$this->pageNums;
			$nextPageUrl=$this->makeUrl($this->subPage_link, $this->current_page+1);//$this->subPage_link.($this->current_page+1);
			$lastPageShowPageNums="<span class=\"ellipsis\">...</span><a onclick=\"CtripSelfPassParams('POST',  this.rel, $('#postData').value(), '_self')\" href='javascript:;' rel='$lastPageUrl'>$this->pageNums</a>";
			if($isHaveLast)
			{
				$lastPageShowPageNums="";
			}
			$subPageCss2Str.=$lastPageShowPageNums."</div> <a onclick=\"CtripSelfPassParams('POST',  this.rel, $('#postData').value(), '_self')\" href='javascript:;' rel='$nextPageUrl' class=\"next\">下一页<span></span></a>";
			//$subPageCss2Str.="<a href='$lastPageUrl'>$this->pageNums</a>&nbsp;";
		}else {
			$lastPageShowPageNums="<span class=\"ellipsis\">...</span>$this->pageNums</a>";
			if($isHaveLast)
			{
				$lastPageShowPageNums="";
			}
			$subPageCss2Str.=$lastPageShowPageNums."</div><a class=\"next\">下一页<span></span></a>";
			//$subPageCss2Str.="<a>$this->pageNums</a>&nbsp;";
		}
		//如果是最后一个页码，则不显示...和最后那个数据
		echo $subPageCss2Str;
	}
	
	
	
	
	/**
	 *
	 * 将URL作转换
	 * @param $url--输入的URL
	 * @param $pagenum--当前的页码
	 */
	function makeUrl($url,$pagenum)
	{
		return str_replace("...",$pagenum,$url);
	}
}
?>
