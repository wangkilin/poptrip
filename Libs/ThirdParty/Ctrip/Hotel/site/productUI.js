function ctrip_toDecimal(x){  
            var f = parseFloat(x);  
            if (isNaN(f)) {  
                return;  
            }  
            f=Math.round(x)+'.0';
			return f;  
}
function ctrip_dealDate(a,b){
	var tmp=a.split(" ");
	return tmp[0].match(/\-\d+\-\d+/)[0].replace(/^\-+/g,"").replace("-","月")+"日 "+tmp[1].match(/\d+:\d+/)+" - "+b.match(/\d+:\d+/);
}
(function(){

var isWinOpen = false;
var isShowData = true;
function init(){
	// (function(){
	/*
		*opt callback
		data
		siteCallback
	*/
var product=function(opt){
    this.setWinOpen = function(b){
        isWinOpen = b;
   }; 
	this.AllianceId=0;
	this.SId=0;
	this.OuId="";
	this.tmpls='';
	this.ifm=null;
	this.urlConfig=null;
	this.siteCallback=null;
	if(opt && opt.data)
		this.urlConfig=opt.data;
	if(opt && opt.siteCallback)
		this.siteCallback=opt.siteCallback;
	this.opt=opt || null;
	//this.datas=this.data;
	// this.ifmID='main';
	this.DESCode='';
	this.hoteltmp='<div style="display:;" class="show_list_box show_hotel_box"><div class="show_list show_hotel"><ul>{{each searchResult.hotel}}<li class="{{if config.hotel.isPic==false}}no_pic{{/if}}"><div class="li_box">{{if config.hotel.isPic==true}}<a target="blank" class="result_list_pic" href="${hotel.url}"><img style="width:100px; height:75px;" alt="${hotel.Name}" src="${url}"></a>{{/if}}<div class="result_list_info"><h3 class="hd"><a target= "_blank " title="${hotel.Name}" href="${hotel.url}">${hotel.Name}</a></h3>{{if config.hotel.isLevel==true}}<div class="range range_${level}"></div>{{/if}}{{if config.hotel.isGrede==true}}<div class="grade"><span>${ctrip_toDecimal(mark)}分</span><em>/${peoples}人评论</em></div>{{/if}}{{if config.hotel.isPrice==true}}<div class="price"><dfn>&yen;</dfn><em>${Math.round(minPrice)}</em><span>起</span></div>{{/if}}</div></div></li>{{/each}}</ul></div></div>';
	this.tickettmp='<div style="" class="show_list_box show_flights_box"><div class="show_list show_flights"><ul>{{each searchResult.ticket}}<li class="{{if config.hotel.isPic==false}}no_pic{{/if}}"><div class="li_box"><p class="time">${ctrip_dealDate(Ddate,Adata)}</p>{{if config.ticket.isBE==true}}<p class="line">${DairPort}-${AairPort}</p>{{/if}}<p class="type">${FlightInfo.code}{{if config.ticket.isType==true}} ${FlightInfo.type}{{/if}} {{if config.ticket.isRebate==true}} {{if FlightInfo.discount == 10}} 全价 {{else  FlightInfo.discount <1}}  {{else}} ${FlightInfo.discount}折 {{/if}} {{/if}}</p><p class="price">{{if config.ticket.isPrice==true}}<dfn>&yen;</dfn><em>${price}</em><span class="wake">(不含税)</span>{{/if}}<a target= "_blank " class="book" href="${bookingUrl}">预订</a></p></div></li>{{/each}}</ul></div></div>';
	
	this.holidaytmp='<div style="" class="show_list_box show_activites_box"><div class="show_list show_activites"><ul>{{each searchResult.holiday}}<li class="{{if config.holiday.isPic==false}}no_pic{{/if}}"><div class="li_box">{{if config.holiday.isPic==true}}<a target= "_blank " class="result_list_pic" href="${lineInfo.info}"><img alt="${lineInfo.name}" src="${url}"></a>{{/if}}<div class="result_list_info"><h3 class="hd"><a target= "_blank " title="${lineInfo.name}" href="${lineInfo.info}">${lineInfo.name}</a></h3>{{if config.holiday.isPrice==true}}<div class="price">{{if parseInt(minPrice) < 0}}实时计价{{else}}<dfn>&yen;</dfn><em>${minPrice}</em><span>起</span>{{/if}}</div>{{/if}}\
	                {{if config.holiday.isType==true}}<span class="{{if type=="团队游"}}sr_team{{else type=="自由行"}}sr_free{{else type=="邮 轮"}}sr_boat{{else type=="签 证"}}sr_visa{{else type=="周边游"}}sr_side{{else type=="机票+酒店"}}sr_flight_hotel{{else type=="巴士+酒店"}}sr_flight_hotel{{else type=="火车+酒店"}}sr_flight_hotel{{else}}sr_ticket{{/if}}">${type}</span>{{/if}}\
	                </div>{{if config.holiday.isDescribe==true}}<p class="detail"><em style="display:none">${remark}</em></p>{{/if}}</div></li>{{/each}}</ul></div></div>';
	
	this.grouptmp='<div style="display:;" class="show_list_box show_hotel_box show_group_box">\
						<div class="show_list show_group">\
							<ul>\
								{{each searchResult.group}}\
								<li class="{{if config.group.isPic==false}}no_pic{{/if}}">\
									<div class="li_box">\
										{{if config.group.isPic==true}}\
										<a class="result_list_pic" href="${bookurl}" target="_blank"><img style="width:100px;height:75px" alt="${name}" src="${picurl}"></a>\
										{{/if}}\
										<div class="result_list_info">\
											<h3 class="hd"><a title="${name}" href="${bookurl}"  target="_blank">${name}</a></h3>\
											{{if config.group.isPrice==true}}<div class="old_price">原价：<del><dfn>&yen;</dfn><em>${productprice}</em></del></div>{{/if}}\
											{{if config.group.isNowPrice==true}}<div class="now_price">现价：<span class="price"><dfn>&yen;</dfn><em>${price}</em><span>起</span></span></div>{{/if}}\
											{{if config.group.isLeavings==true}}<div class="last_data">${endtime}</div>{{/if}}\
										</div>\
									</div>\
								</li>\
								{{/each}}\
							</ul>\
						</div>\
					</div>';
	this.stylelib=['<style type="text/css">a {color:${config.diyTheme.linkText};} .tab_type_box a:hover,.tab_type_box a.current,.tab_type_box a.current:hover{background-color:${config.diyTheme.searchArea};}.show_search_box {background-color:${config.diyTheme.searchArea};}.main_box {background-color:${config.diyTheme.pageBackColor};}.tab_type_box a span {border-right-color:${config.diyTheme.border};}.tab_type_box a:hover span,.tab_type_box a.current span,.tab_type_box a.current:hover span {border-right-color:${config.diyTheme.border};}.show_search_box {border-bottom-color:${config.diyTheme.border};border-top-color:${config.diyTheme.border};}.main_box {border:1px solid ${config.diyTheme.border};border-bottom:0 none;}.show_list_sort {border-bottom-color:${config.diyTheme.border};}.no_foot .show_page_box {border-bottom:1px solid ${config.diyTheme.border};}.footer{background-color:${config.diyTheme.footBackColor};}</style> ','<style type="text/css">a,.tab_type_box a:hover span,.tab_type_box a.current span,.tab_type_box a.current:hover span {color:${config.diyTheme.linkText};}.show_box_v3 {border:1px solid ${config.diyTheme.border};}.show_list_sort {border-bottom-color:${config.diyTheme.border};}.tab_type_box {border-bottom:2px solid ${config.diyTheme.border};}.show_box_v3,.show_search_box {background-color:${config.diyTheme.pageBackColor};}.show_page_box,#b2b_content{border:solid ${config.diyTheme.pageBackColor}; border-width:0 9px;}</style>','<style type="text/css">a,.tab_type_box a:hover span,.tab_type_box a.current span,.tab_type_box a.current:hover span {color:${config.diyTheme.linkText};}.show_box_v3 {border:5px solid ${config.diyTheme.pageBackColor};}.tab_type_box {background-color:${config.diyTheme.pageBackColor};}.search_box button {background-color:${config.diyTheme.pageBackColor};}.tab_type_box a span {border:solid ${config.diyTheme.pageBackColor};border-width:0 1px 0 0;}.show_search_box,.footer,.tab_type_box a:hover,.tab_type_box a.current,.tab_type_box a.current:hover {background-color:${config.diyTheme.searchArea};}.show_search_box {border-bottom:1px solid ${config.diyTheme.border};}.footer {border-top:1px solid ${config.diyTheme.border};}</style>',
'<style type="text/css">a {color:${config.diyTheme.linkText};} </style> '];
	
	this.init();
};

product.prototype={
	init:function(){
		this.tmpls='';
		this.tmpls+='<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">';
		this.tmpls+='<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
		this.tmpls+='<title>搜索结果展示_v2</title>';//<link rel="stylesheet" href="http://webresource.ctrip.com/styles/union/get_code_v3/show_box.css" media="screen" />';
		this.tmpls+='<style type="text/css" media="all">';
		this.tmpls+='@charset "utf-8";/*===== 效果展示的reset模块 =====*/html,body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,code,form,fieldset,legend,input,textarea,p,blockquote,th,td,em,button {margin:0;padding:0;}body {font-size:12px;line-height:1.5;font-family:Simsun,sans-serif;color:#333333;}img,fieldset {border:0 none;margin:0;padding:0;}input, textarea {font-size:12px;}table {border-collapse:collapse;}a {text-decoration:none;}a:hover {text-decoration:underline;}h1,h2,h3,h4,h5 {font-family:simsun,sans-serif;}ul li {list-style:none;}/*===== 效果展示的基础模块 =====*/.show_box_v3:after,.show_box_v3 .main_box:after {clear:both;display:block;content:".";height:0;visibility:hidden;font-size:0;line-height:0;}.show_box_v3 {zoom:1;}.show_box_v3 .main_box {zoom:1;}#b2b_content {position:relative;overflow:hidden;zoom:1;}.footer {height:28px;padding:5px 20px;}.footer .logo {display:block;width:125px;height:29px;overflow:hidden;line-height:999em;font-size:0;content:"";}* html .footer .logo {cursor:pointer;}* html .footer .logo img {padding-top:30px;filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'http://pic.ctrip.com/union/get_code_v2/logo.png\',sizingMethod=\'crop\');}/*===== 星级图标 =====*/.range {width:50px;height:10px;overflow:hidden;margin:1px 0;line-height:999em;font-size:0;content:"";background:url(http://pic.ctrip.com/union/get_code_v2/btn_search.png) no-repeat 0 -48px;}div.range_1 {width:10px;}div.range_2 {width:20px;}div.range_3 {width:30px;}div.range_4 {width:40px;}div.range_5 {width:50px;}/*===== 公共部分样式 =====*//* 排序 */.c_sort {position:relative;z-index:10; height:25px; padding:10px; font-family:arial, simsun; *zoom:1; }.c_sort:after { content:"."; display:block; height:0; clear:both; overflow:hidden; visibility:hidden; }.c_sort .c_sort_select { position:relative; float:left; margin-right:10px; height:25px; line-height:25px; }/* sort_btn_select */.c_sort_btn_select a { position:relative; }.c_sort_btn_select .select { padding-left:0; margin-left:1px; background:none; border-left:1px solid #999; }	.c_sort_btn_select .select span { padding-left:8px; }.c_sort_btn_select .select dfn { font-family:arial; font-style:normal; }.c_sort a, .c_sort b, .c_sort i, .c_sort span, .c_sort_range, .btn_range { background-image:url("http://pic.c-ctrip.com/common/un_sort_v2.png"); background-repeat:no-repeat; }/* sort_bg */.c_sort a { float:left; padding-left:8px; background-position:0 0; color:#333; outline:none; }.c_sort a:hover { text-decoration:none; outline:none; _color:#333; }.c_sort span { float:left; padding-right:23px; background-position:right -26px; background-color:#E5F2FE; }.c_sort a span,.c_sort a b { cursor:pointer; }.c_sort a:hover, .c_sort_btn_range a:hover, .c_sort_btn_select .btn:hover { background-position:0 -78px; }.c_sort a:hover span { background-position:right -104px; }.c_sort_select span,.c_sort_btn_select .select span { padding-right:28px; background-position:right -52px; }.c_sort_select a:hover span,.c_sort_btn_select a:hover span { background-position:right -130px; }.sort_btn_click a, .sort_btn_click a:hover, .sort_twoway_click a, .sort_btn_range_click a, .sort_btn_select_click .btn, .sort_default_click a, .sort_default_click a:hover,  .click_nohover a:hover { background-position:0 -298px; }.sort_default_click a, sort_default_click a b, .sort_default_click a span, .sort_btn_click a, .sort_btn_click a b, .sort_btn_click a span, .click_nohover a, .click_nohover a b, .click_nohover a span { cursor:default; }.sort_btn_click span, .sort_btn_click a:hover span, .sort_default_click span, .sort_default_click a:hover span, .sort_twoway_click span, .btn ,.click_nohover a:hover span { background-position:right -324px; }/* sort_b */.c_sort_select b, .c_sort_btn_select .select b{ position:absolute; top:11px; right:6px; width:7px; height:4px; background-position:-56px -350px; overflow:hidden; }.c_sort_select a:hover b, .c_sort_btn_select .select:hover b { width:4px; height:7px;top:9px; right:8px; background-position:-64px -350px; }.c_sort_btn b, .c_sort_btn_range b , .c_sort_btn_select b { position:absolute; top:8px; right:8px; width:7px; height:10px; overflow:hidden; }.c_sort_btn .up, .c_sort_btn_range .up ,.c_sort_btn_select .up { background-position:0 -350px; }.c_sort_btn .down, .c_sort_btn_range .down ,.c_sort_btn_select .down { background-position:-8px -350px; }.sort_btn_click .up, .sort_btn_range_click .up , .sort_btn_select_click .up { background-position:-16px -350px; }.sort_btn_click .down, .sort_btn_range_click .down , .sort_btn_select_click .down{ background-position:-24px -350px; }.c_sort_twoway i, .c_sort_btn_range i, .c_sort_btn_select .btn i { position:absolute; top:8px; right:8px; width:7px; height:10px; background-position:-32px -350px; overflow:hidden; }.sort_twoway_click i.up, .sort_btn_range_click i.up, .sort_btn_select_click .btn i.up { background-position:-40px -350px; }.sort_twoway_click i.down, .sort_btn_range_click i.down, .sort_btn_select_click .btn i.down { background-position:-48px -350px; }/* sort_list */.c_sort_list { position:absolute; left:0; top:24px; padding:5px 0; border:1px solid #478DCB; background-color:#FFF; white-space:nowrap; z-index:3; }.c_sort_list a { float:none; display:block; height:20px; padding:0 8px; background-image:none; line-height:20px; }.c_sort_list a:hover { background-color:#E8F4FF; }.c_sort_list .value_nono {height:0; margin:6px 10px; border-top:1px dashed #999; overflow:hidden; }	.c_sort_list dfn { font-family:arial; font-style:normal; }.show_list_sort {padding:10px 0;margin:0 20px;border-bottom-width:1px;border-bottom-style:dashed;}/* tab选项卡样式 */.tab_type_box {position:relative;overflow:hidden;margin-bottom:-1px;z-index:2;font-size:0;letter-spacing:-1px;white-space:nowrap;zoom:1;}.tab_type_box:after {clear:both;display:block;content:".";height:0;visibility:hidden;font-size:0;line-height:0;}.tab_type_box a {display:inline-block;width:25%;height:34px;overflow:hidden;vertical-align:middle;}.tab_type_box a:hover,.tab_type_box a.current,.tab_type_box a.current:hover {height:35px;text-decoration:none;}.tab_type_box a span {display:block;height:100%;overflow:hidden;text-align:center;font-size:14px;line-height:34px;white-space:nowrap;}.tab_type_box a:hover span,.tab_type_box a.current span,.tab_type_box a.current:hover span {cursor:pointer;}.tab_type_box a.current span {font-weight:bold;}.tab_type_box .ico {display:inline-block;overflow:hidden;margin-right:5px;_margin-top:4px;vertical-align:-4px;line-height:99em;font-size:0;content:"";background:url(http://pic.ctrip.com/union/get_code_v2/ico_tab.png) no-repeat 0 0;}.tab_type_box .ico_hotel {width:17px;height:21px;background-position:0 0;}.tab_type_box .ico_vacation {width:21px;height:24px;vertical-align:-6px;background-position:-64px 0;}.tab_type_box .ico_fligts {width:19px;height:20px;background-position:-32px 0;}.tab_type_box .ico_group {width:19px;height:21px;background-position:-96px 0;}/* 搜索区域 */.search_list {width:100%;overflow:hidden;padding:10px 0;zoom:1;}*+html .search_list {padding-bottom:15px;}.search_list ul {zoom:1;}.search_list ul:after {clear:both;display:block;content:".";height:0;visibility:hidden;font-size:0;line-height:0;}.search_list li {float:left;width:24.9%;min-width:230px;_width:auto;height:24px;overflow:hidden;margin:0 0 5px 0;padding:3px 0;}.search_list li .search_box {position:relative;padding-left:18px;zoom:1;}.search_list li .search_box:after {clear:both;display:block;content:".";height:0;visibility:hidden;font-size:0;line-height:0;}.search_list .hd {float:left;margin-right:10px;line-height:24px;*vertical-align:4px;}* html .search_list li.search_radio_box .hd {vertical-align:1px;}.search_list li.search_radio_box .input_area {width:150px;}.search_list li.search_radio_box .input_area input {margin-left:-3px\9;}.search_list input {width:140px;height:20px;padding:2px 4px 0 4px;color:#666666;border:1px solid #9F9F9F;background-color:#FFFFFF;}.search_list .price input {width:50px;}*+html .search_list .price input {width:45px;}.search_list .f_type_ir {width:auto;vertical-align:middle;margin:0 5px;border:0 none;background:none;}.search_list .f_type_ir input {margin:0;}.search_list .hd_to {margin:0 4px;}.search_list select {width:150px;height:22px;padding:1px 0;color:#666666;border:1px solid #9F9F9F;}.search_list .input_area {float:left;}.search_box button {position:relative;width:100px;height:30px;overflow:hidden;margin-top:-3px;line-height:30px;font-weight:bold;font-size:14px;color:#444444;border:0 none;background:url(http://pic.ctrip.com/union/get_code_v2/btn_search.png) no-repeat 0 0;cursor:pointer;}/* 输入框自适应时的搜索区域 */.min_box .search_list li {width:100%;min-width:100%;}.min_box .search_list li.search_radio_box {height:auto;overflow:hidden;zoom:1;}.min_box .search_list li.search_radio_box label {float:left;white-space:nowrap;}.min_box .search_list li .search_box {padding-left:76px;padding-right:25px;}.min_box .search_list li .search_select_box {padding-right:15px;}.min_box .search_list li .search_btn_box {text-align:center;}.min_box .search_list .hd {position:absolute;top:0;left:18px;width:58px;height:24px;z-index:2;zoom:1;}.min_box .search_list li label,.min_box .search_list .input_area {float:none;}.min_box .search_box button {position:absolute;left:76px;top:0;}.min_box .search_list .input_area {width:100%;}.min_box .search_list input,.min_box .search_list select {width:100%;}.min_box .search_list .f_type_ir {width:auto;}.min_box .search_list .price_area input {float:left;width:29%;}.min_box .search_list .price_area .hd_to {float:left;width:20%;height:24px;overflow:hidden;margin:0;text-align:center;line-height:26px;}.min_box #b2b_content,.min_box .show_page_box,.min_box .tab_type_box .ico {display:none;}/* 数据列表区域样式 */.show_list_box:after {clear:both;display:block;content:".";height:0;visibility:hidden;font-size:0;line-height:0;}.show_list_box {padding:20px 20px 10px;zoom:1;}.show_list ul:after {clear:both;display:block;content:".";height:0;visibility:hidden;font-size:0;line-height:0;}.show_list {position:relative;width:100%;overflow:hidden;}.show_list ul {margin:-30px 0 0 -18px;zoom:1;}.show_list li {position:relative;float:left;width:230px;height:77px;overflow:hidden;margin-top:30px;}.show_list .li_box {position:relative;height:77px;overflow:hidden;margin:0 0 0 18px;zoom:1;padding-left:110px;}.show_list .no_pic .li_box {padding-left:0;}.show_flights_box .show_list li {position:relative;float:left;height:77px;overflow:hidden;margin:30px 0 -16px;padding-bottom:15px;border-bottom:1px dashed #C4BCCC;}.show_flights_box .show_list .li_box {position:relative;overflow:visible;margin:0 0 0 18px;padding-left:0;zoom:1;}.show_flights_box .show_list p {padding-bottom:1px;line-height:19px;}.show_flights_box .show_list .book {display:inline-block;width:50px;height:20px;overflow:hidden;margin-bottom:-2px;margin-bottom:0\9;line-height:20px;text-align:center;text-decoration:none;vertical-align:-3px;vertical-align:-2px\9;*vertical-align:0;font-size:12px;color:#333333;border:0 none;background:url(http://pic.ctrip.com/union/get_code_v2/btn_search.png) no-repeat 0 -80px;cursor:pointer;}.show_list .result_list_pic {position:absolute;top:0;left:0;width:100px;height:75px;overflow:hidden;margin-right:10px;border:1px solid #9A9997;}.show_list .no_pic .result_list_pic {display:none;}.show_list .hd {height:21px;overflow:hidden;line-height:20px;white-space:nowrap;text-overflow:ellipsis;}.show_list .hd a {font-size:14px;}.show_list .grade {white-space:nowrap;color:#666666;}.show_list .grade a {font-weight:bold;font-size:15px;font-family:Tahoma;}.show_list .grade em {font-style:normal;}.show_list .old_price {white-space:nowrap;letter-spacing:-.5px;}.show_list .old_price strong {font-weight:normal;}.show_list .old_price dfn {font-style:normal;font-family:arial;font-size:12px;}.show_list .old_price em {font-style:normal;}.show_list .now_price {margin-top:-3px;}.show_list .price {margin:-3px 0;margin-top:0\9;color:#E56700;}.show_list .price dfn {font-style:normal;font-family:arial;font-size:12px;}.show_list .price em {padding:0 5px 0 1px;font-style:normal;font-weight:bold;font-size:17px;}.show_list .price .wake {color:#666666;}/*===== 度假类型 =====*/.sr_team,.sr_free,.sr_boat,.sr_visa,.sr_side,.sr_ticket,.sr_traffic,.sr_flight_hotel {float:left;height:17px;padding:1px 2px 0;color:#fff;margin-top:8px;text-align:center;_display:inline;_zoom:1;}.sr_team {background-color:#287bce;}.sr_free {background-color:#f18759;}.sr_boat {background-color:#8953a1;}.sr_visa {background-color:#d48f57;}.sr_side {background-color:#60bccc;}.sr_ticket {background-color:#7ebd7b;}.sr_traffic {background-color:#9eb857;}.sr_flight_hotel {background-color:#00853E;}/*===== 效果展示的翻页部分 =====*/.show_page_box {padding:8px;line-height:20px;text-align:center;font-family:Arial;font-size:14px;}.show_page_box a {margin:0 2px;text-decoration:underline;}.show_page_box a:hover {text-decoration:none;}.show_page_box .current {text-decoration:none;font-weight:bold;margin:0 2px;cursor:default;}.show_page_box .c_page_list {display:inline;}/*===== loading加载 =====*/.c_loading {padding:8px;font:bold 14px simsun;}.c_loading img {padding:0 0.5em 6px 0;vertical-align:middle;}';
		this.tmpls+='</style>';
		this.tmpls+='<!-- 皮肤样式的切换 --><link rel="stylesheet" href="${config.skinUrl}" media="screen" />';
		//this.tmpls+='<!-- 主题颜色的更换 --><link rel="stylesheet" href="${config.themeUrl}" media="screen" /><!-- 用户自定义颜色的变化 -->';
		this.tmpls+='{{if config.isDiytheme==true}}{{if config.skinNum==0}}'+this.stylelib[0];
		this.tmpls+='{{else config.skinNum==1}}'+this.stylelib[1];
		this.tmpls+='{{else config.skinNum==2}}'+this.stylelib[2];
		this.tmpls+='{{else config.skinNum==3}}'+this.stylelib[3];
		this.tmpls+='{{/if}}{{/if}}';
		this.tmpls+='</head>';
		this.tmpls+='<body style="background:none;"><div id="_maincontents"  class="{{if config.showLogo==true && config.pageWidth<269}}show_box_v3 min_box{{else config.showLogo==false && config.pageWidth<269}}show_box_v3 min_box no_foot{{else config.showLogo==false && config.pageWidth>269}}show_box_v3  no_foot{{else config.showLogo==true && config.pageWidth>269}}show_box_v3{{/if}}"    ${config.widthStyle}>';
		this.tmpls+='<!-- 主要内容区域 开始 --><div id="_main_box" class="main_box">';
		this.tmpls+='<div id="b2b_searchbox" style="overflow:hidden;height:auto;clear:both;*position:relative">'
		this.tmpls+='<!-- 搜索的标题 开始 --><div id="b2b_title">${config.siteType}<input type="hidden" value="1" name="requestID" id="requestID" >\</div><!-- 搜索的标题 结束 -->';
		this.tmpls+='<!-- 搜索列表 开始 --><div class="show_search_box" id="b2b_searchOption">';
		this.tmpls+='<!-- 搜索 开始 -->{{if config.siteTypeValue=="hotel" || config.siteMode=="all"}}<div style="{{if config.siteTypeValue!="hotel"}}display:none;{{/if}}" class="search_list hotel_search_list" id="hotelOption" style="*position:relative" ><ul><li class="search_radio_box"><div class="search_box"><span class="hd">酒店类型</span><div class="input_area"><label><input type="radio" name="hotelType" checked="checked" class="f_type_ir" />国内</label><label><input type="radio" name="hotelType" class="f_type_ir" />海外</label></div></div></li>{{each config.searchOption.hotel}}<li><div class="search_box"><span class="hd">${name}</span>${value}</li>{{/each}}<li><input type="hidden" value="" name="cityId" id="cityId">\
								<input type="hidden" value="" name="city" id="city">\
								<input type="hidden" value="" name="DistrictId" id="DistrictId">\
								<input type="hidden" value="" name="country" id="country">\
								<input type="hidden" value="" name="oricity" id="oricity">\
								<input type="hidden" value="9" name="searchtype" id="searchtype">\
								<input type="hidden" value="2012-1-6" name="perdate" id="perdate">\
								<input type="hidden" value="2012-3-6" name="postdate" id="postdate">\
								<input type="hidden" value="1" name="submittype"><div class="search_box"><button type="button" id="hotelsubmit">搜索</button></div></li></ul></div>{{/if}}';
		                        
		this.tmpls+='{{if config.siteTypeValue=="ticket" || config.siteMode=="all"}}<div class="search_list flights_search_list" style="{{if config.siteTypeValue!="ticket"}}display:none;{{/if}}" id="ticketOption" style="*position:relative"> <form onsubmit="return false;" method="post" action="" id="flightForm" name="flightForm"><ul><li class="search_radio_box"><div class="search_box"><span class="hd">机票类型</span><div class="input_area"><label><input type="radio" name="flightSwitch" checked="checked" class="f_type_ir" faction="http://flights.ctrip.com/Domestic/ShowFareFirst.aspx" value="0" />国内</label><label><input type="radio" name="flightSwitch" class="f_type_ir" value="1" faction="http://flights.ctrip.com/International/ShowFareFirst.aspx" />国际</label></div></div></li>{{each config.searchOption.ticket}}<li><div class="search_box"><span class="hd">${name}</span>${value}</li>{{/each}}<li><div class="search_box"><button type="button" id="ticketSearchBt">搜索</button></div></li></ul>\
		<input type="hidden" value="" name="HomeCityID">\
						<input type="hidden" value="" name="homecity" id="homecity">\
						<input type="hidden" value="" name="destcity1" id="destcity1">\
						<input type="hidden" value="" name="homecityOut" id="homecityOut">\
						<input type="hidden" value="" name="destcity1Out" id="destcity1Out">\
						<input type="hidden" value="" name="DestCity2">\
						<input type="hidden" value="All" name="startPeriod">\
						<input type="hidden" value="All" name="startPeriod2">\
						<input type="hidden" value="All" name="airlineChoice">\
						<input type="hidden" value="Y" name="DSeatClass">\
						<input type="hidden" value="Point" name="ADateChoice">\
						<input type="hidden" value="Point" name="DDateChoice">\
						<input type="hidden" value="" name="today">\
						<input type="hidden" value="" name="Destcity">\
						<input type="hidden" value="" name="DestcityCode" id="DestcityCode">\
						<input type="hidden" value="" name="SendCity">\
						<input type="hidden" value="" name="PType">\
						<input type="hidden" value="ADU" name="childtype">\
						<input type="hidden" value="" name="flightclass">\
						<input type="hidden" value="" name="HomeCityID">\
						<input type="hidden" value="" name="destcityID">\
						<input type="hidden" value="" name="ticketagencyID">\
						<input type="hidden" value="" name="ticketagency_list">\
						<input type="hidden" value="1" name="quantity">\
						<input type="hidden" id="flightTag" value="true">\
						</form></div>{{/if}}';
		this.tmpls+='{{if config.siteTypeValue=="holiday" || config.siteMode=="all"}}<div style="{{if config.siteTypeValue!="holiday"}}display:none;{{/if}}" id="holidayOption" class="search_list activites_search_list" style="*position:relative">\
						<input type="hidden" value="" name="text1ID" id="text1ID" >\
						<input type="hidden" value="" name="pkgdestCityID" id="pkgdestCityID" >\
		<ul>{{each config.searchOption.holiday}}<li class="${classs}"><div class="search_box ${classss}"><span class="hd">${name}</span>${value}</div></li>{{/each}}<li><div class="search_box"><button type="button" id="holidaysubmit">搜索</button></div></li></ul></div>{{/if}}<!-- 机票搜索 结束 -->';
		
		this.tmpls+='<!--团购搜索 开始-->{{if config.siteTypeValue=="group" || config.siteMode=="all"}} <div style="{{if config.siteTypeValue!="group"}}display:none;{{/if}}" id="groupOption" class="search_list activites_search_list" style="*position:relative"><ul>\
		{{each config.searchOption.group}}<li class="${classs}"><div class="search_box ${classss}"><span class="hd">${name}</span>${value}</div></li>{{/each}}\
		<li><div class="search_box"><button type="button" id="groupSearchBt">搜索</button></div></li></ul></div> {{/if}}';
		
		this.tmpls+='<!--团购搜索 结束-->  </div><!-- 搜索列表 结束 -->'
		
		this.tmpls+='';
		//this.tmpls+='<div id="listSort" class="show_page_box"></div>';
		// this.tmpls+='<div class="c_loading" id="b2b_loading"><img src="http://pic.ctrip.com/common/loading.gif" alt="">查询中，请稍后...</div>';
		this.tmpls+='</div>'
		this.tmpls+='<div id="b2b_content" style="visibility:hidden">'
		this.tmpls+='<div class="c_loading" id="b2b_loading" style="display:none" ><img src="http://pic.ctrip.com/common/loading.gif" alt="">查询中，请稍后...</div>'
		this.tmpls+='<div class="show_list_sort c_sort" id="listSort" style="display:none">'
		this.tmpls+='<div class="c_sort_select" id="selSort" ><a href="javascript:void(0);"><b></b><span>默认排序：携程推荐</span></a><div class="c_sort_list" id="sort_select" style="width:142px;display:none;">\
		            <a href="javascript:void(0);" name="minPrice|desc">价格由高到低</a>\
                    <a href="javascript:void(0);" name="minPrice|asc">价格由低到高</a>\
					<div class="value_nono"></div>\
                    <a href="javascript:void(0);" name="level|desc">星级由高到低</a>\
                    <a href="javascript:void(0);" name="level|asc">星级由低到高</a>\
					<div class="value_nono"></div>\
                    <a href="javascript:void(0);" name="mark|desc">点评分数由高到低</a>\
					<div class="value_nono"></div>\
					<a href="javascript:void(0);" name="default">默认排序</a>\
                 </div></div>'
		this.tmpls+='</div>';
		this.tmpls+='<div id="b2b_show_list"></div></div>';
		this.tmpls+='<div id="b2b_page" class="show_page_box" style="visibility:hidden"></div>';
		//this.tmpls+='{{if config.siteTypeValue=="hotel"}}'+this.hoteltmp;
		//this.tmpls+='{{else config.siteTypeValue=="ticket"}}'+this.tickettmp;//+'{{/if}}';
		//this.tmpls+='{{else config.siteTypeValue=="holiday"}}'+this.holidaytmp+'{{/if}}';
		////this.tmpls+='</ul></div></div><!-- 机票展示列表 结束 -->';
		//this.tmpls+='<!-- 翻页 开始 -->${pageIndex}<!-- 翻页 结束 -->';
		this.tmpls+='</div>	<!-- 主要内容区域 结束 -->';
		this.tmpls+='<!-- 底部版权 开始 -->	<div id="b2b_foot" class="footer" style="{{if config.showLogo==true}}display:block{{else}}display:none{{/if}}"><a href="javascript:void(0);" class="logo"><img src="http://pic.ctrip.com/union/get_code_v2/logo.png" alt="" /></a></div>	<!-- 底部版权 结束 --></div>';
		this.tmpls+='</body></html>';
		if(!window['b2b_ctrip_v2']) {
			this.updateView();
		}
	},
	data:{
			config:{
				widthStyle:'',
				skinUrl:null,//'http://webresource.ctrip.com/styles/union/get_code_v2/skin_.css',
				themeUrl:null,//'http://webresource.ctrip.com/styles/union/get_code_v2/theme_.css',
				siteTypeValue:'hotel', //st
				siteMode:'simple', //all	//sm
				hotel:{isPic:false,isGrede:false,isPrice:false,isLevel:false}, //hp
				ticket:{isBE:false,isType:false,isPrice:false,isRebate:false}, //tp
				holiday:{isPic:false,isType:false,isPrice:false,isDescribe:false}, //hop
				group:{isPic:true,isPrice:true,isNowPrice:true,isLeavings:true},//gr
				skinId:0,  //si 
				themeId:0,  //ti
				isDiytheme:false, //是否显示自定义样式 //id
				showLogo:false,    //sl
				showData:true,	  //sd
				minBox:false,
				pageWidth:540,
				skin:0,
				pageHeight:0,
				footHeight:38,
				col:2,
				row:5,
				groupDefaultCity:null,
				groupDefaultCityPY:null,
				hotelDefaultCity:null,
				ticketDefaultBegin:null,
				ticketDefaultBeginOut:'上海',
				ticketDefaultEndOut:'香港',
				ticketDefaultEnd:null,
				holidayDefaultCity:null,
				holidayAimCity:null,
				flightDepTime:null,
				flightArriveTime:null,
				hotelInTime:null,
				hotelOutTime:null,
				hotelhidD:null,
				hotelDefaultInterCity:'香港',
				hotelInertHid:20058,
				holidayHidD:null,
				holidayHidA:null,
				ticketHidD:null,
				ticketHidDOut:'SHA',
				thicketHidA:null,
				thicketHidAOut:'HKG',
				siteType:'<h3>搜索机票</h3>',
				searchOption:{},		//搜索选项   //so
				diyTheme:{}, //自定义颜色数据
				hostAddress : ''
			},
			searchResult:{hotel:[{url:'http://pic.ctrip.com/union/get_code_v2/zzz_hotel_pic.jpg',hotel:{Name:'好东西好东西',url:'afdf'},level:1,mark:5.0,peoples:25,minPrice:'800.00'}],
					ticket:[{dateDiff:'9月15日 14:00 - 16:00',airPort:'上海虹桥',diffPort:'北京首都',FlightInfo:{code:'mu5137',type:"经济舱",discount:"3.7"},price:800,bookingUrl:'aaa'}],
					holiday:[{url:'',lineInfo:{name:'asdf',info:''},minPrice:'800.00',type:'adsf',remark:'afaf'}]
			},
			pageIndex:'<div class="show_page_box"><a target= "_blank" href="###" class="page_arrow">&lt;</a> <span class="current">1</span> <a href="###">2</a> <a href="###">3</a> <a href="###">4</a> <a href="###">5</a><a href="###" class="page_arrow">&gt;</a></div>'
	},
	abback:{st:'siteTypeValue',sm:'siteMode',smi:'siteModeItems',hp:'hotel',tp:'ticket',hop:'holiday',gr:'group',si:'skinId',ti:'themeId',had:'hostAddress', num: 'skinNum',
				id:'isDiytheme',sl:'showLogo',sd:'showData',hsp:'hotelOption',tsp:'ticketOption',hosp:'holidayOption',grp:'groupOption',lt:'linkText',hd:'header',sa:'searchArea',pt:'priceText',hc:'headBackColor',dc:'dataBackColor',fc:'footBackColor',pc:'pageBackColor',da:'dataArea',bd:'border',ht:'headText',diy:'diyTheme',opt:'opt',pw:'pageWidth',cl:'col',rw:'row',gri:'groupDefaultCityID',grd:'groupDefaultCity',gpy:'groupDefaultCityPY',hdc:'hotelDefaultCity',tdb:'ticketDefaultBegin',hfc:'holidayDefaultCity',fv:'fixedValue',ifs:'isFixed',so:'searchOption',hac:'holidayAimCity',tde:'ticketDefaultEnd',ph:'pageHeight',ft:'footHeight',allid:'AllianceId',sid:'SId',oid:'OuId',hdt:'hotelInTime',hat:'hotelOutTime',tdt:'flightDepTime',fat:'flightArriveTime',hhd:'hotelhidD',hod:'holidayHidD',hoa:'holidayHidA',thd:'ticketHidD',tha:'thicketHidA'},
	analypara:function(para){
		if(para)
			this.urlConfig=para;
		if(this.urlConfig==null)
			return;
		var url=this.urlConfig;
		var opt=url.split('&');
		var config=new Object();
		
		for(var i=0;i<opt.length;i++){
			config[this.abback[opt[i].split('=')[0]]]=unescape(opt[i].split("=")[1]);
		}
		//________________reset object Value_____________
		this.data.config.groupDefaultCity = null;
		this.data.config.groupDefaultCityID = null;
		this.data.config.groupDefaultCityPY = null;
		//________________read__readwidthStyle___________
	
	    this.data.config.pageWidth=config['pageWidth'];  //可精简
		this.data.config.pageHeight=config['pageHeight'];
		var styles='width:'+config['pageWidth']+'px';
		this.data.config.skinId=config['skinId'];
		var skin=config['skinNum']?config['skinNum']:config['skinId']
	    switch(skin){
			   case '0':
			    styles='width:'+(config['pageWidth'])+'px';
			    break;
			  case '1':
			    styles='width:'+(config['pageWidth']-2)+'px';
			    break;
			  case '2':
			    styles='width:'+(config['pageWidth']-10)+'px';
			    break
			  case '3':
			     styles='width:'+(config['pageWidth']-3)+'px';
				if(this.data.config['showLogo']==false){
				 styles='width:'+(config['pageWidth']-2)+'px';
				}
			}

		this.data.config.widthStyle='style="'+styles+'"';

	   this.data.config.widthStyle='style="'+styles+'"';
		
		this.data.config.footHeight=config['footHeight'];
		this.data.config.headerHeight=config['headerHeight'];
		this.data.config.skinNum=config['skinNum'];
		if(!config['skinNum']){
		 if(config['skinId']==0){config['skinId']=0;this.data.config.skin='0';}
		 if(config['skinId']==1){config['skinId']=5;this.data.config.skin='1';}
		 if(config['skinId']==2){config['skinId']=10;this.data.config.skin='2';}
		 if(config['skinId']==3){config['skinId']=13;this.data.config.skin='3';}
		}
		  
		this.data.config.skinUrl=this.defineData.skinUrl+config['skinId']+'.css';
		//__________________read__theme________________________
		if(config['themeId']==0){
			this.data.config.themeUrl='';
		}else{
			this.data.config.themeUrl=this.defineData.themeUrl+config['themeId']+'.css';
		}
		//_______________read__siteTypeValue____
		this.data.config.siteTypeValue=config['siteTypeValue'] || 'hotel';
		//____________read____
		this.data.config.siteMode=config['siteMode'] || 'simple';
		//_____read___show_______________________
		var setTrue=function(obj,opt,splits){
           for( var n in obj){
            obj[n]=false;
           }
           var arr=opt.split(splits);
           for(var i=0;i<arr.length;i++){
            if(arr[i])
             obj[arr[i]]=true;
           }
        }

		if(this.data.config.siteMode=='simple'){ 
			setTrue(this.data.config[this.data.config.siteTypeValue],config[this.data.config.siteTypeValue],'|');
			if(this.data.config.siteTypeValue=='hotel'){
				this.data.config.hotelInTime=config['hotelInTime'];
				this.data.config.hotelOutTime=config['hotelOutTime'];
			}
			else if(this.data.config.siteTypeValue=='ticket'){
				this.data.config.flightDepTime=config['flightDepTime'];
				this.data.config.flightArriveTime=config['flightArriveTime'];
			}
		}else{
			setTrue(this.data.config['hotel'],config['hotel'],'|');
			setTrue(this.data.config['ticket'],config['ticket'],'|');
			setTrue(this.data.config['holiday'],config['holiday'],'|');
			setTrue(this.data.config['group'], config['group'],'|');
			this.data.config.hotelInTime=config['hotelInTime'];
			this.data.config.hotelOutTime=config['hotelOutTime'];
			this.data.config.flightDepTime=config['flightDepTime'];
			this.data.config.flightArriveTime=config['flightArriveTime'];
		}
		//____________id diytheme__________________
		if(config['opt'].indexOf('id')!=-1)
			this.data.config['isDiytheme']=true;
		else
			this.data.config['isDiytheme']=false;
		if(config['opt'].indexOf('sl')!=-1)
			this.data.config['showLogo']=true;
		else
			this.data.config['showLogo']=false;
		if(config['opt'].indexOf('sd')!=-1)
			this.data.config['showData']=true;
		else
			this.data.config['showData']=false;

		if(config['opt'].indexOf('id')!=-1)
			this.data.config['isDiytheme']=true;
		
		else
			this.data.config['isDiytheme']=false;
		
		
		//____________默认城市________________________
		if(config['hotelDefaultCity']){
			this.data.config['hotelDefaultCity']=unescape(config['hotelDefaultCity']);
			this.data.config['hotelhidD']=config['hotelhidD'];
		}
		if(config['ticketDefaultBegin']){
			this.data.config['ticketDefaultBegin']=unescape(config['ticketDefaultBegin']);
			this.data.config['ticketHidD']=config['ticketHidD'];
		}
		if(config['ticketDefaultEnd']){
			this.data.config['ticketDefaultEnd']=unescape(config['ticketDefaultEnd']);
			this.data.config['thicketHidA']=config['thicketHidA'];
		}
		if(config['holidayDefaultCity']){
			this.data.config['holidayDefaultCity']=unescape(config['holidayDefaultCity']);
			this.data.config['holidayHidD']=config['holidayHidD'];
		}
		if(config['holidayAimCity']){
			this.data.config['holidayAimCity']=unescape(config['holidayAimCity']);
			this.data.config['holidayHidA']=config['holidayHidA'];
		}
		if(config['groupDefaultCity']){
			this.data.config['groupDefaultCity']=unescape(config['groupDefaultCity']);
		}
		if(config['groupDefaultCityID']){
			this.data.config['groupDefaultCityID']=config['groupDefaultCityID'];
		}
		if(config['groupDefaultCityPY']){
		    this.data.config['groupDefaultCityPY']=config['groupDefaultCityPY'];
		}
		if(this.data.config['siteMode']=='simple'){
			this.data.config.siteType = this.defineData.siteType[this.data.config.siteTypeValue];
		}else{
		    var defineData = this.defineData;
			this.data.config.siteType = (function(){
			    var html = ['<div class="tit"><!--<h3>搜 索</h3>--><div class="tab_type_box"><div class="tab_box" id="changeSite">'];
			    if (config['siteModeItems']) {
			        for(var i = 0,l = config['siteModeItems'].split('|').length; i< l; i++){
			            switch(config['siteModeItems'].split('|')[i]){
			                case "h":
			                    html.push('<a href="javascript:void(0)" name="hotel" class="current"><span><b class="ico ico_hotel"></b>酒店</span></a>');
			                    break;
			                case "d":
			                    html.push('<a href="javascript:void(0)" name="holiday" class=""><span><b class="ico ico_vacation"></b>度假</span></a>');
			                    break;
			                case "s":
			                    html.push('<a href="javascript:void(0)" name="ticket" class=""><span><b class="ico ico_fligts"></b>机票</span></a>');
			                    break;
			                case "g":
			                    html.push('<a href="javascript:void(0)" name="group" class="last_child"><span><b class="ico ico_group"></b>团购</span></a>');
			                    break;
			            }
			        }
			        html.push('</div></div></div>');
			        return html.join('');
			    } else return defineData.siteType['all'];
			})();
		}
		//_____________________________________________
		var setOptionValue=function(defData,siteType){
			var arr=defData;
			var tmp=new Array();	
			var opt=config[siteType+'Option'].split('|');
			for(var i=0;i<opt.length;i++){
				tmp.push(arr[parseInt(opt[i])]);
			}
			return tmp;
		}
		if(this.data.config['siteMode']=='simple'){
			this.data.config.searchOption[this.data.config.siteTypeValue]=setOptionValue(this.defineData.searchOption[this.data.config.siteTypeValue],this.data.config.siteTypeValue);
		}else{
			this.data.config.searchOption['hotel']=setOptionValue(this.defineData.searchOption['hotel'],'hotel');
			this.data.config.searchOption['ticket']=setOptionValue(this.defineData.searchOption['ticket'],'ticket');
			this.data.config.searchOption['holiday']=setOptionValue(this.defineData.searchOption['holiday'],'holiday');
			this.data.config.searchOption['group']=setOptionValue(this.defineData.searchOption['group'],'group');
		}
		//颜色数据
		if(this.data.config.isDiytheme==true){
			this.data.config.diyTheme;
			var arr=config['diyTheme'].split('|');
			for(var i=0;i<arr.length;i++){
				
				this.data.config.diyTheme[this.abback[arr[i].split('.')[0]]]=arr[i].split('.')[1];
			}
		}
		if(config['AllianceId'])
			this.AllianceId=config['AllianceId'];
		if(config['SId'])
			this.SId=config['SId'];
		if(config['OuId'])
			this.OuId=config['OuId'];
		
		//__________set host address____________
		if (config['hostAddress']) {
		    this.hostAddress = (function () {
	            var hostUrl;
	            
                switch(config['hostAddress']) {
                    case 'ctrip':
                        return 'http://u.ctrip.com/';
                    case 'uat':
                        return 'http://u.uat.sh.ctriptravel.com/';
                    case 'test':
                        return 'http://u.test.sh.ctriptravel.com/';
                    case 'dev':
                        return 'http://u.dev.sh.ctriptravel.com/';
                    default:
                        return 'http://u.ctrip.com/';
                }
	            
	        })()
		} else {
		    this.hostAddress = 'http://u.ctrip.com/';
		}
		
		//______________________________________
		this.datas=null;
		this.datas=this.data;
	},
	//opt是url参数 在系统内由 unioncustom.js提供 在第三方为空
	updateView:function(opt){
		var pa;
		if(opt && opt.data)
			pa=opt.data;
			
		//pa没有传 则证明现在是在第三方引用
		if(pa==null){
			//var sc=$("script");
			//这个id是第三方引用时才有的
			var scr=$("#ctrip_union_product_20121221")[0];
			pa=scr.src.split('?')[1];
		}
		//解析url参数 解析到product.data.config
		this.analypara(pa);
		var rv;
		if(opt && opt.position)
			rv=this.writedom(opt.position);
		else
			rv=this.writedom();
		if(this.opt && this.opt.callback && rv==true){
			this.opt.callback(this.ifm,this);
		}
		
	},
	writedom:function(appendPosi){
		if(!this.datas || !this.datas.config.searchOption)
			return false;
		var x=document.createElement('iframe');
		this.ifm=x;
		x.id=this.ifmID;
		//x.frameborder='0';
		x.setAttribute('frameBorder', '0');
		x.src='about:blank';
		x.style.border='none';
		if(!window["b2b_ctrip_v2"]){x.scrolling = "no";}		

		x.height=this.datas.config.pageHeight;
		x.width=this.datas.config.pageWidth;
		if(!appendPosi || appendPosi==null){
			//var sc=$("script");
			var scr=$("#ctrip_union_product_20121221")[0];
			scr.parentNode.appendChild(x);
		}
		else{
			$("#"+appendPosi)[0].innerHTML = '';
			$("#"+appendPosi)[0].appendChild(x);
		}
		this.ifm=x;
		var w=x.contentWindow;
		var showvalue=$.tmpl.render(this.tmpls,this.datas);
		w.document.open();
		w.document.write(showvalue);
		w.document.close();
		if(window["b2b_ctrip_v2"] && window["b2b_ctrip_current_content_height"]){
           // $("#undefined")[0].contentWindow.document.getElementById('b2b_content').style.height = window["b2b_ctrip_current_content_height"] + 'px';
			//$("#undefined")[0].contentWindow.document.getElementById('_main_box').style.height =this.data.config.pageHeight-38 + 'px';
        };
		this.bindEvent();
		return true;
	},
	bindEvent:function(){
		var self=this;
		var doc=this.ifm.contentDocument || this.ifm.contentWindow.document;
		var changeSiteList=$("#changeSite",doc).find("a");
		if(changeSiteList.length>0){
				changeSiteList[0].className="current";
			changeSiteList.bind("click",function(e){
				var types=$(this)[0].name;
				
				for(var i=0;i<changeSiteList.length;i++){
				changeSiteList[i].className=""
				if (i==changeSiteList.length-1)changeSiteList[i].className="last_child";
				$("#b2b_content",doc)[0].style.visibility='hidden'
			    $("#b2b_page",doc)[0].style.visibility='hidden'
				}
				if($(this)[0].className=="last_child"){
				$(this)[0].className="current last_child";
				}else{
				$(this)[0].className="current";
				}
				self.ifm.contentWindow.document.getElementById("hotelOption").style.display='none';
				self.ifm.contentWindow.document.getElementById("ticketOption").style.display='none';
				self.ifm.contentWindow.document.getElementById("holidayOption").style.display='none';
				self.ifm.contentWindow.document.getElementById("groupOption").style.display='none';
				self.ifm.contentWindow.document.getElementById(types+"Option").style.display='';
				self.datas.config.siteTypeValue=types;
				self.datas.config.headerHeight=parseInt($("#show_search_box",doc).offset()['height']) ;
				content.dataType=types
				//content.accountPageInfo(self.datas.config);
				if(self.siteCallback){
			
					self.siteCallback(types);
				}
				if($("#ctrip_union_product_20121221")[0] || !$("#ctrip_union_product_20121221")[0]){
					if($("#showDataList")[0]){self.data.config.showData=$("#showDataList")[0].checked}
                   // var searchBoxHeight = parseInt($("#b2b_searchbox", doc).offset()['height']);
		            var bootBoxHeight =38;
			        var searchBoxHeight=content.getSearchBoxHeight(doc,self.data.config)
				 if((self.data.config.showData && self.data.config.pageHeight-searchBoxHeight-bootBoxHeight>content.minContentHeight) && (self.data.config.pageWidth>=270)){
		            var siteTypeValue=self.data.config.siteTypeValue
		             $("#b2b_searchOption",doc)[0].style.height="auto"
                    switch (types){
                      case 'hotel': $.event.trigger($('#hotelsubmit',doc), 'click');
					        break;
			           case 'holiday': $.event.trigger($('#holidaysubmit',doc),'click');
					        break;
				        case 'ticket': $.event.trigger($('#ticketSearchBt',doc),'click');
				            break;
				        case 'group' : $.event.trigger($('#groupSearchBt',doc),'click');
					        break;
                };
              $("#b2b_searchOption",doc)[0].style.height="auto";		   
		      }else{
		      
		      $("#b2b_searchOption",doc)[0].style.height=content.getMainboxHeight(doc,self.data.config,"title")+"px";		  
		     
		      }
				}
				  

				
				
			});
		}
		if($("#flightway",doc).length>0){
			$("#flightway",doc).bind("change",function(e){
				var types=$("#flightway",doc)[0].value;
				if($("#ADatePeriod1",doc).length>0 && types=="Simple"){

					$("#ADatePeriod1",doc)[0].parentNode.parentNode.parentNode.style.display="none";
				}else if($("#ADatePeriod1",doc).length>0 && types=="Double"){
					$("#ADatePeriod1",doc)[0].parentNode.parentNode.parentNode.style.display="";
				}
			});
		}
		//设置默认城市
		if(this.datas.config.hotelDefaultCity && $("#cityname",doc).length>0){
			$("#cityname",doc)[0].value=this.datas.config.hotelDefaultCity;
			$("#citynameInter",doc)[0].value=this.datas.config.hotelDefaultInterCity;
			$("#cityId",doc)[0].value=this.datas.config.hotelhidD;
			$("#DistrictId",doc)[0].value=this.datas.config.hotelInertHid;
		}
		if(this.datas.config.ticketDefaultBegin && $("#homecity_name",doc).length>0){
			$("#homecity_name",doc)[0].value=this.datas.config.ticketDefaultBegin;
			$("#homecity_nameOut",doc)[0].value=this.datas.config.ticketDefaultBeginOut;
			$("#homecity",doc)[0].value=this.datas.config.ticketHidD;
			$("#homecityOut",doc)[0].value=this.datas.config.ticketHidDOut;
		}
		if(this.datas.config.ticketDefaultEnd && $("#destcity1_name",doc).length>0){
			$("#destcity1_name",doc)[0].value=this.datas.config.ticketDefaultEnd;
			$("#destcity1_nameOut",doc)[0].value=this.datas.config.ticketDefaultEndOut;
			$("#destcity1",doc)[0].value=this.datas.config.thicketHidA;
			$("#destcity1Out",doc)[0].value=this.datas.config.thicketHidAOut;
		}
		if(this.datas.config.holidayDefaultCity && $("#text1",doc).length>0){
			$("#text1",doc)[0].value=this.datas.config.holidayDefaultCity;
			$("#text1ID",doc)[0].value=this.datas.config.holidayHidD;
		}
		if(this.datas.config.holidayAimCity && $("#pkgdestCity",doc).length>0){
			$("#pkgdestCity",doc)[0].value=this.datas.config.holidayAimCity;
			$("#pkgdestCityID",doc)[0].value=this.datas.config.holidayHidA;
		}
		if($("#groupCity",doc).length>0){
			if(this.datas.config.groupDefaultCity)
				$("#groupCity",doc)[0].value=this.datas.config.groupDefaultCity;
			else
				$("#groupCity",doc)[0].value="";
		}
		if($("#grouphidCity",doc).length>0){
			if(this.datas.config.groupDefaultCityID)
				$("#grouphidCity",doc)[0].value=this.datas.config.groupDefaultCityID;
			else
				$("#grouphidCity",doc)[0].value="";
		}
		if($("#grouphidCityPinYin",doc).length>0){
			if(this.datas.config.groupDefaultCityPY)
				$("#grouphidCityPinYin",doc)[0].value=this.datas.config.groupDefaultCityPY;
			else
				$("#grouphidCityPinYin",doc)[0].value="";
		}
		//设置默认时间
		if(this.datas.config.hotelInTime){
			if(this.datas.config.hotelInTime.toDate()<new Date()){
			var now=new Date
			var month=now.getMonth()+1
			var days=now.getDate()
			if(month<10)month=0+""+month
			if(days<10)days=0+""+days
			this.datas.config.hotelInTime=(now.getFullYear()+'-'+month+'-'+days)
			}
			
			if($("#starttime",doc).length>0)
				$("#starttime",doc)[0].value=this.datas.config.hotelInTime;
			else if($("#hotelOption",doc).length>0){
			   
				var tmp=doc.createElement("input");
				tmp.id="starttime";
				tmp.type="hidden";
				tmp.value=this.datas.config.hotelInTime;
				$("#hotelOption",doc).append(tmp);
			}
		}
		if(this.datas.config.hotelOutTime){
		if(this.datas.config.hotelOutTime.toDate()<new Date()){
		    var ahotelOutTime=this.datas.config.hotelOutTime.split('-')
			var ahotelInTime=this.datas.config.hotelInTime.split('-')
			 var now=new Date()
			var month=now.getMonth()+1
			var days=now.getDate()+1
			if(month<10)month=0+""+month
			if(days<10)days=0+""+days
			this.datas.config.hotelOutTime=(now.getFullYear()+'-'+month+'-'+days)
			
			}
			if($("#deptime",doc).length>0)
				$("#deptime",doc)[0].value=this.datas.config.hotelOutTime;
			else if($("#hotelOption",doc).length>0){
				var tmp=doc.createElement("input");
				tmp.id="deptime";
				tmp.type="hidden";
				tmp.value=this.datas.config.hotelOutTime;
				$("#hotelOption",doc).append(tmp);
			}
		}
		if(this.datas.config.flightDepTime){
		   if(this.datas.config.flightDepTime.toDate()<new Date()){
		   var now=new Date()
			var month=now.getMonth()+1
			var days=now.getDate()
			if(month<10)month=0+""+month
			if(days<10)days=0+""+days
			this.datas.config.flightDepTime=(now.getFullYear()+'-'+month+'-'+days)
			
			}
		
			if($("#DDatePeriod1",doc).length>0)
				$("#DDatePeriod1",doc)[0].value=this.datas.config.flightDepTime;
			else if($("#ticketOption",doc).length>0){
				var tmp=doc.createElement("input");
				tmp.id="DDatePeriod1";
				tmp.type="hidden";
				tmp.value=this.datas.config.flightDepTime;
				$("#ticketOption",doc).append(tmp);	
			}
		}
		if(this.datas.config.flightArriveTime){
		if(this.datas.config.flightArriveTime.toDate()<new Date()){
		  var now=new Date()
			var month=now.getMonth()+1
			var days=now.getDate()+1
			if(month<10)month=0+""+month
			if(days<10)days=0+""+days
			this.datas.config.flightArriveTime=(now.getFullYear()+'-'+month+'-'+days)
			
			}
			if($("#ADatePeriod1",doc).length>0)
				$("#ADatePeriod1",doc)[0].value=this.datas.config.flightArriveTime;
			else if($("#ticketOption",doc).length>0){
				var tmp=doc.createElement("input");
				tmp.id="ADatePeriod1";
				tmp.type="hidden";
				tmp.value=this.datas.config.flightArriveTime;
				$("#ticketOption",doc).append(tmp);
			}
		}
	},
	defineData:{
		skinUrl:'http://webresource.ctrip.com/styles/union/get_code_v3/skin_',
		//themeUrl:'http://webresource.ctrip.com/styles/union/get_code_v2/theme_',
		themeUrl:'http://webresource.ctrip.com/styles/union/get_code_v3/skin_',
		siteType:{hotel:'<!--<h3>搜索酒店</h3>-->',holiday:'<!--<h3>搜索度假</h3>-->',ticket:'<!--<h3>搜索机票</h3>-->',group:'<!--<h3>搜索团购</h3>-->',all:'<div class="tit"><!--<h3>搜 索</h3>--><div class="tab_type_box"><div class="tab_box"><a class="current" href="javascript:void(0)" ><span><b class="ico ico_hotel"></b>酒店</span></a><a class="" href="javascript:void(0)"><span><b class="ico ico_vacation"></b>度假</span></a><a href="javascript:void(0)"><span><b class="ico ico_fligts"></b>机票</span></a><a class="last_child" href="javascript:void(0)"><span><b class="ico ico_group"></b>团购</span></a></div></div></div>'},
		siteTypeValue:['hotel','holiday','ticket','group','all'],
		searchOption:{hotel:[{name:'入住城市',abb:'0',id:'cityname',isMust:true,value:'<label class="input_area"><input type="text" name="cityname" id="cityname" value="" autocomplete="off"><input id="citynameInter" name="citynameInter" type="text" value=""  style="display: none;"  autocomplete="off" /></label>'},
							{name:'入住日期',abb:"1",id:'starttime',isMust:false,value:'<label class="input_area"><input id="starttime" type="text" value="" /></label>'},
							{name:'离店日期',abb:"2",id:'deptime',isMust:false,value:'<label class="input_area"><input id="deptime" type="text" value="" /></label>'},
							{name:'价格范围',abb:"3",id:'priceRange',isMust:false,value:'<label class="input_area"><select id="priceRange"><option value="0-50000">不限</option><option value="100-200">100 - 200</option><option value="200-500">200 - 500</option><option value="500-800">500 - 800</option><option value="800-50000">800以上</option></select></label>'},
							{name:'酒店类型',abb:"6",id:'hotelTypes',isMust:false,value:'<label class="input_area"><select id="hotelTypes"><option value="f">不限</option><option value="t">酒店式公寓</option><option value="p">度假型酒店</option></select></label>'},
							{name:'酒店级别',abb:"5",isMust:false,id:'hotelLevel',value:'<label class="input_area"><select id="hotelLevel"><option value="1,2,3,4,5">不限</option><option value="5">五星级/豪华</option><option value="4">四星级/高档</option><option value="3">三星级/舒适</option><option value="2">二星级以下/经济</option></select></label>'},
							{name:'酒店名称',abb:"4",isMust:false,id:'hotelName',value:'<label class="input_area"><input id="hotelName" type="text" value="" /></label>'}
							
						],
						ticket:[
							{name:'航程类型',abb:"0",isMust:false,id:'ticketTypes',value:'<label class="input_area"><select id="flightway"><option value="Double">往返</option><option value="Simple">单程</option></select></label>'},
							{name:'出发城市',abb:"1",isMust:true,id:'homecity_name',value:'<label class="input_area"><input id="homecity_name" type="text" value="" /><input id="homecity_nameOut" style="display:none" type="text" value="" /></label>'},
							{name:'到达城市',abb:"2",isMust:true,id:'destcity1_name',value:'<label class="input_area"><input id="destcity1_name" type="text" value="" /><input id="destcity1_nameOut" style="display:none" type="text" value="" /></label>'},
							{name:'出发日期',abb:"3",isMust:false,id:'DDatePeriod1',value:'<label class="input_area"><input id="DDatePeriod1" type="text" value="" /></label>'},
							{name:'返回日期',abb:"4",isMust:false,id:'ADatePeriod1',value:'<label class="input_area"><input id="ADatePeriod1" type="text" value="" /></label>'},
							{name:'舱位等级',abb:"5",isMust:false,id:'siteLevel',value:'<label class="input_area"><select id="siteLevel"><option value="Y">经济舱</option><option value="F">头等舱</option><option value="C">公务舱</option></select></label>'},
							{name:'乘客人数',abb:"6",isMust:false,id:'ticketpeoples',value:'<label class="input_area"><input id="ticketpeoples" type="text" value="" /></label>'}
							//{name:'乘客年龄',abb:"ta",isMust:false,value:'<select><option value="">成人</option><option value="">儿童</option></select>'}
						],
						holiday:[{name:'出 发 地',abb:"0",isMust:true,id:'text1',value:'<label class="input_area"><input id="text1" type="text" value="" /></label>'},
							{name:'目 的 地',abb:"1",isMust:true,id:'pkgdestCity',value:'<label class="input_area"><input type="text" id="pkgdestCity" value="" /></label>'},
							{name:'价格范围',abb:"2",isMust:false,id:'holidayPrice',value:'<div class="input_area"><input id="holidayPrice" type="text" value="" /><span class="hd_to">到</span><input type="text" id="holidayPrice2" value="" /></div>',classs:"price",classss:'price_area'}
						],
						group:[
							{name:'团购城市', abb:"0",isMust:true, id:'groupDeptCity',value:'<label class="input_area"><input id="groupCity" name="groupCity" style="width: 110px; color: gray;" value=""/><input type="hidden" value="" id="grouphidCity" name="grouphidCity" /><input type="hidden" value="" id="grouphidCityPinYin" /></label>'},
							{name:'价格区间',abb:'1',isMust:false,id:'Group_PriceDiff',value:'<div class="input_area"><input id="groupdayprice" type="text" value="" /><span class="hd_to">到</span><input type="text" id="groupdayprice2" value="" /></div>', classs:'price',classss:'price_area'},
							{name:'排序方式',abb:'2', isMust:false,id:'groupSortType',value:'<label class="input_area"><select id="select_groupsortType"><option value="0">默认</option><option value="1">折扣从高到低</option><option value="2">折扣从低到高</option><option value="3">价格从高到低</option><option value="4">价格从低到高</option><option value="5">销量从高到低</option><option value="6">销量从低到高</option><option value="9">产品最新开团</option><option value="10">产品即将到期</option><option value="7">星级从高到低</option><option value="8">星级从低到高</option></select></label>'}
						]
					},
		widthStyle:{fixedWidth:[270,540,800],
		            fixedHeight:[827,543,401],
					autoInfo:{width:280,col:1,row:5}
					},
		diyTheme:[{linkText:'',header:'',searchArea:'',border:'',priceText:'',headBackColor:'',dataBackColor:'',footBackColor:''},
					{linkText:'',priceText:'',searchArea:'',pageBackColor:'',dataArea:'',border:'',headText:''},
					{linkText:'',priceText:'',searchArea:'',headText:''},
					{linkText:'',priceText:'',searchArea:'',headText:''}
			]
	},
	getDateDiff : function (startTime, endTime, diffType) {
        if(startTime.length <= 0 || endTime.length <= 0) return 1;
        startTime = startTime.replace(/\-/g, "/");
        endTime = endTime.replace(/\-/g, "/");
        diffType = diffType.toLowerCase();
        var sTime = new Date(startTime);      //开始时间
        var eTime = new Date(endTime);  //结束时间
        var divNum = 1;
        switch (diffType) {
            case "second": divNum = 1000; break;
            case "minute":divNum = 1000 * 60;break;
            case "hour":divNum = 1000 * 3600;break;
            case "day":divNum = 1000 * 3600 * 24;break;
            default:break;
        }
        return parseInt((eTime.getTime() - sTime.getTime()) / parseInt(divNum)) < 0 ? 0 : parseInt((eTime.getTime() - sTime.getTime()) / parseInt(divNum));
    },
	redirectUrl : function (Url) {
		var RedirectPageUrl = [ctripUnion.hostAddress + 'union/CtripRedirect.aspx?jumpUrl='];
		
		if(Url){
			RedirectPageUrl.push(escape(Url));
			RedirectPageUrl.push('&TypeID=2');
			RedirectPageUrl.push('&AllianceID=' + content.ctripUnion.AllianceId);
			RedirectPageUrl.push('&SID=' + content.ctripUnion.SId);
			RedirectPageUrl.push('&OUID=' + content.ctripUnion.OuId);
			window.open(RedirectPageUrl.join(''));
		}
	},
	maxHeight: function(doc, type){
         var hotel = $("#hotelOption", doc),
             ticket = $("#ticketOption", doc),
             holiday = $("#holidayOption", doc),
             group = $("#groupOption", doc),
             searchBox=$("#b2b_searchbox",doc);
         var searchHeight
         hotel.css('display', '');
         ticket.css('display', '');
         holiday.css('display', '');
         group.css('display', '');
              window.allHeight = {
                 h: hotel.offset()['height'],
                 t: ticket.offset()['height'],
                 d: holiday.offset()['height'],
                 g: group.offset()['height']
             }

           var maxHeight=Math.max(window.allHeight.h,window.allHeight.t,window.allHeight.d,window.allHeight.g)
           var b2bTitle= $("#b2b_title", doc).offset()['height'];
                searchHeight=parseInt(maxHeight+b2bTitle)
         if(type != 'hotel')hotel.css('display', 'none');
         if(type != 'ticket') ticket.css('display', 'none');
         if(type != 'holiday') holiday.css('display', 'none');
         if(type != 'group') group.css('display', 'none');
         
       return searchHeight  

	   
     },
    getChangeSiteType:function(doc){
    //  var doc=this.ifm.contentDocument || this.ifm.contentWindow.document; 
      var doc=this.ifm.contentWindow.document;  
      var siteTypeValue='hotel';
	  var changeSiteList=$("#changeSite", doc).find('a')
		   for(var i=0;i<changeSiteList.length;i++ ){
		      if(changeSiteList[i].className=='current'||changeSiteList[i].className=='current last_child' ){
		        siteTypeValue=changeSiteList[i].name
		       }
		   }
		  return siteTypeValue
	},
	renderData: function(custObj, action){
	    if(custObj){
		    this.data.config.pageHeight= custObj.pageHeight;
            this.data.config.pageWidth = custObj.pageWidth;
        }
        content.accountPageInfo(custObj, action);
	},
	hostAddress : null,
	currentCity : {},
	groupCityAddress : "@Beijing|北京|1|bj|@Xian|西安|10|xa|@Lanzhou|兰州|100|lz|@Jiangshan|江山|1000|js|@Fengcheng|丰城|1003|fc|@Ningguo|宁国|1005|ng|@Xuancheng|宣城|1006|xc|@Ningxiang|宁乡|1011|nx|@Lingbao|灵宝|1023|lb|@Ma'anshan|马鞍山|1024|mas|@Anning|安宁|10254||@Yangling|杨凌|10270|YL|@Luoning|洛宁|10271|LN|@Danba|丹巴|10272|DB|@Juxian|莒县|10273|JX|@Hongya|洪雅|10296|HY|@Changle|昌乐|10297|CL|@Maoxian|茂县|10298|MX|@Huhehaote|呼和浩特|103|hhht|@Weinan|渭南|1030|wn|@Zhongning|中宁|1035|zn|@Haiyang|海阳|1037|hy|@Laiyang|莱阳|1038|ly|@Pingyao|平遥|104|py|@Gaomi|高密|1040|gm|@Jiaozhou|胶州|1043|jz|@Qingzhou|青州|1044|qz|@Tieling|铁岭|1048|tl|@Taiyuan|太原|105|ty|@Huludao|葫芦岛|1050|hld|@Xingcheng|兴城|1051|xc|@Jiangyou|江油|1054|jy|@Wutaishan|五台山|106|wts|@Rruzhou|汝州|1060|rz|@Bazhou|霸州|1068|bz|@Renqiu|任丘|1069|rq|@Suning|肃宁|1070|sn|@Liaocheng|聊城|1071|lc|@Heze|菏泽|1074|hz|@Bozhou|亳州|1078|bz|@Macheng|麻城|1079|mc|@Linzhi|林芝|108|lz|@Manzhouli|满洲里|1083|mzl|@Luohe|漯河|1088|lh|@Leping|乐平|1089|lp|@Kashi|喀什|109|ks|@Dingzhou|定州|1090|dz|@Jincheng|晋城|1092|jc|@Jiaozuo|焦作|1093|jz|@Xuchang|许昌|1094|xc|@Houma|侯马|1095|hm|@Panzhihua|攀枝花|1097|pzh|@Dunhuang|敦煌|11|dh|@Yan'an|延安|110|ya|@Guang'an|广安|1100|ga|@Maoming|茂名|1105|mm|@Rizhao|日照|1106|rz|@Changxing|长兴|1107|cx|@Xianyang|咸阳|111|xy|@Jishou|吉首|1110|js|@Shaoyang|邵阳|1111|sy|@Yulin|玉林|1113|yl|@Baicheng|白城|1116|bc|@Suizhou|随州|1117|sz|@Baoji|宝鸡|112|bj|@Jingmen|荆门|1121|jm|@Yiyang|益阳|1125|yy|@Suihua|绥化|1128|sh|@Wuhai|乌海|1133|wh|@Xingyi|兴义|1139|xy|@Baise|百色|1140|bs|@Jiagedaqi|加格达奇|1143|jgdq|@Meishan|眉山|1148|ms|@Benxi|本溪|1155|bx|@Jinchang|金昌|1158|jc|@Tongchuan|铜川|118|tc|@Huayin|华阴|119|hy|@Nanjing|南京|12|nj|@Yancheng|盐城|1200|yc|@Ninghai|宁海|1201|nh|@Tongli|同里|1205|tl|@Pinghu|平湖|1206|ph|@Cixi|慈溪|1208|cx|@Linhai|临海|1209|lh|@Shengzhou|嵊州|1212|sz|@Wuxue|武穴|1219|wx|@Daocheng|稻城|1222|dc|@Jiangdu|江都|1223|jd|@Yizheng|仪征|1224|yz|@Nandaihe|南戴河|1226|ndh|@Tongren|铜仁|1227|tr|@Puyang|濮阳|1232|py|@Dazhou|达州|1233|dz|@Xining|西宁|124|xn|@Hancheng|韩城|128|hc|@Hanzhong|汉中|129|hz|@Wuxi|无锡|13|wx|@Yingkou|营口|1300|yk|@Songyuan|松原|1303|sy|@Yongji|永济|1315|yj|@Shuozhou|朔州|1317|sz|@Ge'ermu|格尔木|132|gem|@Dongmingxian|东明县|1322|dmx|@Qiandaohu|千岛湖|1332|qdh|@Honghezhou|红河州|1341|hhz|@Wenshan|文山|1342|ws|@Jiexiu|介休|135|jx|@Liyang|溧阳|1358|ly|@Datong|大同|136|dt|@Deqing|德清|1367|dq|@Changzhi|长治|137|cz|@Dezhou|德州|1370|dz|@Suining|遂宁|1371|sn|@Songpan|松潘|1372|sp|@Liancheng|连城|1373|lc|@Linfen|临汾|139|lf|@Suzhou|苏州|14|sz|@Yuncheng|运城|140|yc|@Baotou|包头|141|bt|@Conghua|从化|1421|ch|@Qingyuan|清远|1422|qy|@Enping|恩平|1428|ep|@Qufu|曲阜|143|qf|@Shanwei|汕尾|1436|sw|@Jinan|济南|144|jn|@Taishun|泰顺|1443|ts|@Laiwu|莱芜|1452|lw|@Jinzhong|晋中|1453|jz|@Jiyuan|济源|1454|jy|@Antu|安图|1466|at|@Qinhuangdao|秦皇岛|147|qhd|@Suqian|宿迁|1472|sq|@Qiqihaer|齐齐哈尔|149|qqhe|@Xiaogan|孝感|1490|xg|@Yangzhou|扬州|15|yz|@Mudanjiang|牡丹江|150|mdj|@Gaobeidian|高碑店|1501|gbd|@Guigang|贵港|1518|gg|@Chibi|赤壁|1521|cb|@Laiyuan|涞源|1522|ly|@Baiyin|白银|1541|by|@Mohe|漠河|155|mh|@Zhijiang|枝江|1557|zj|@Ziyang|资阳|1560|zy|@Suizhong|绥中|1564|sz|@Jixi|鸡西|157|jx|@Changchun|长春|158|cc|@Jilin|吉林|159|jl|@Neijiang|内江|1597|nj|@Zhenjiang|镇江|16|zj|@Mishan|密山|1609|ms|@Hegang|鹤岗|1611|hg|@Shuangyashan|双鸭山|1617|sys|@Aershan|阿尔山|1658|aes|@Kelamayi|克拉玛依|166|klmy|@Yabuli|亚布力|1664|ybl|@Hailin|海林|1666|hl|@Fangchenggang|防城港|1677|fcg|@Caoxian|曹县|1696|cx|@Hangzhou|杭州|17|hz|@Ruichang|瑞昌|1700|rc|@Liu'an|六安|1705|la|@hailuogou|海螺沟|1706|hlg|@libo|荔波|1708|lb|@Ankang|安康|171|ak|@Akesu|阿克苏|173|aks|@Aletai|阿勒泰|175|alt|@Anqing|安庆|177|aq|@Anshan|鞍山|178|as|@Anshun|安顺|179|as|@Jinjiang|晋江|1803|jj|@laixi|莱西|1804|lx|@Anyang|安阳|181|ay|@tengchong|腾冲|1819|tc|@Bengbu|蚌埠|182|bb|@binzhou|滨州|1820|bz|@xingan|兴安|1822|xa|@Xiangshan|象山|1823|xs|@Jintan|金坛|1839|jt|@Pingxiang|萍乡|1840|px|@Baoding|保定|185|bd|@Yuxi|玉溪|186|yx|@Beidaihe|北戴河|187|bdh|@Xiantao|仙桃|1882|xt|@feicheng|肥城|1884|fc|@Beihai|北海|189|bh|@Laibin|来宾|1892|lb|@Qinzhou|钦州|1899|qz|@Zhoushan|舟山|19|zs|@Mizhixian|米脂县|1937|mzx|@Dingxing|定兴|1980|dx|@Xushui|徐水|1983|xs|@Pingyuanxian|平原县|19953|PYX|@Qingyunxian|庆云县|19954|QYX|@Qingyuan|庆元|19955|QY|@Gaotangxian|高唐县|19956|GTX|@Shanghai|上海|2|sh|@Changde|常德|201|cd|@Chifeng|赤峰|202|cf|@Wuan|武安|2033|wa|@Wenzhuangcun|文庄村|2040|wzc|@Changsha|长沙|206|cs|@Chaoyang|朝阳|211|zy|@Lushan|鲁山|2122|ls|@Changzhou|常州|213|cz|@Chuzhou|滁州|214|cz|@Chaozhou|潮州|215|cz|@Cangzhou|沧州|216|cz|@Chizhou|池州|218|cz|@Shaoxing|绍兴|22|sx|@Dandong|丹东|221|dd|@Dengfeng|登封|222|df|@Dongguan|东莞|223|dg|@Qianan|迁安|2230|qa|@Huangshan|黄山|23|hs|@Daqing|大庆|231|dq|@Shaodong|邵东|2339|sd|@Dongying|东营|236|dy|@Deyang|德阳|237|dy|@Danyang|丹阳|238|dy|@Jiujiang|九江|24|jj|@Meng|蒙自|2431|mz|@Jianshui|建水|2442|js|@Enshi|恩施|245|es|@Fuding|福鼎|246|fd|@Xiamen|厦门|25|xm|@Foshan|佛山|251|fs|@Fushun|抚顺|252|fs|@Fuxin|阜新|254|fx|@Delingha|德令哈|2542|dlh|@Luntai|轮台|2549|lt|@Daying|大英|2552|dy|@Fuyang|富阳|256|fy|@Fuyang|阜阳|257|fy|@Fuzhou|福州|258|fz|@Wuyishan|武夷山|26|wys|@Mian|绵竹|2625|mz|@Guangyuan|广元|267|gy|@Ganzhou|赣州|268|gz|@Zhangjiajie|张家界|27|zjj|@Huaibei|淮北|272|hb|@Handan|邯郸|275|hd|@Hefei|合肥|278|hf|@Chengdu|成都|28|cd|@Heihe|黑河|281|hh|@Huaihua|怀化|282|hh|@Hami|哈密|285|hm|@Huainan|淮南|287|hn|@Huashan|华山|288|hs|@Wenxi|闻喜|2886|wx|@Hengshui|衡水|290|hs|@Huangshi|黄石|292|hs|@Hetian|和田|294|ht|@Shangzhi|尚志|2966|sz|@Hengyang|衡阳|297|hy|@Huizhou|惠州|299|hz|@Tianjin|天津|3|tj|@Shenzhen|深圳|30|sz|@Jingdezhen|景德镇|305|jdz|@Meizhou|梅州|3053|mz|@Longquan|龙泉|3055|lq|@Jinggangshan|井冈山|307|jgs|@Jinhua|金华|308|jh|@Zhuhai|珠海|31|zh|@Penglai|蓬莱|310|pl|@Jiangmen|江门|316|jm|@Jiamusi|佳木斯|317|jms|@Jining|济宁|318|jn|@Guangzhou|广州|32|gz|@Guyuan|固原|321|gy|@zhoukou|周口|3221|zk|@pingdingshan|平顶山|3222|pds|@bijie|毕节|3225|bj|@Jurong|句容|3230|jr|@dongtai|东台|3233|dt|@Jiangyin|江阴|325|jy|@Jiayuguan|嘉峪关|326|jyg|@Jinzhou|锦州|327|jz|@langzhong|阆中|3275|lz|@huashuiwan|花水湾|3276|hsw|@yaan|雅安|3277|ya|@Jingzhou|荆州|328|jz|@Kuche|库车|329|kc|@Guilin|桂林|33|gl|@Kuerle|库尔勒|330|kel|@hengdian|横店|3309|hd|@Kaifeng|开封|331|kf|@kanasi|喀纳斯|3326|kns|@Kaili|凯里|333|kl|@Kaiping|开平|335|kp|@Kunming|昆明|34|km|@Langfang|廊坊|340|lf|@Longhai|龙海|341|lh|@Lushan|庐山|344|ls|@Leshan|乐山|345|ls|@Lishui|丽水|346|ls|@Longyan|龙岩|348|ly|@Xishuangbanna|西双版纳|35|xsbn|@Luoyang|洛阳|350|ly|@Liaoyang|辽阳|351|ly|@Liaoyuan|辽源|352|ly|@Lianyungang|连云港|353|lyg|@Liuzhou|柳州|354|lz|@Luzhou|泸州|355|lz|@Dali|大理|36|dl|@Dehong|德宏|365|dh|@Lijiang|丽江|37|lj|@Mianyang|绵阳|370|my|@Nan'an|南安|374|na|@Ningbo|宁波|375|nb|@Nanchang|南昌|376|nc|@Nanchong|南充|377|nc|@Ningde|宁德|378|nd|@Guiyang|贵阳|38|gy|@Nanning|南宁|380|nn|@Hsinchu|新竹|3845|xz|@Tainan|台南|3847|tn|@Taitung|台东|3848|td|@Taichung|台中|3849|tz|@Nanyang|南阳|385|ny|@shouguang|寿光|3863|sg|@Panjin|盘锦|387|pj|@Pingliang|平凉|388|pl|@siyang|泗阳|3881|sy|@Fuzhou|抚州|3884|fz|@huang gang|黄冈|3885|hg|@baishan|白山|3886|bs|@Bayannaoer|巴彦淖尔|3887|byne|@Puning|普宁|389|pn|@Wulumuqi|乌鲁木齐|39|wlmq|@jimo|即墨|3906|jm|@wendeng|文登|3908|wd|@jiaonan|胶南|3909|jn|@yiyuan|沂源|3913|yy|@daye|大冶|3914|dy|@Laizhou|莱州|3915|lz|@Fuqing|福清|3917|fq|@Tianmen|天门|3920|tm|@Chuxiong|楚雄|3921|cx|@Hai'an|海安|3923|ha|@yuhuan|玉环|3925|yh|@jingjiang|靖江|3926|jj|@dexing|德兴|3927|dx|@deqin|德钦|3928|dq|@pizhou|邳州|3929|pz|@yunfu|云浮|3933|yf|@yingcheng|应城|3935|yc|@yangzhong|扬中|3937|yz|@zhongxiang|钟祥|3938|zx|@pingdu|平度|3943|pd|@longkou|龙口|3946|lk|@Pingxiang|凭祥|396|px|@bazhong|巴中|3966|bz|@dongxing|东兴|3967|dx|@guiping|桂平|3968|gp|@hechi|河池|3969|hc|@gaoan|高安|3970|ga|@E'erduosi|鄂尔多斯|3976|eeds|@Taixing|泰兴|3980|tx|@jiyang|济阳|3989|jy|@Puer|普洱|3996|pe|@Chongqing|重庆|4|cq|@Tulufan|吐鲁番|40|tlf|@xiajin|夏津|4013|xj|@YongCheng|永城|4020|yc|@jiangyan|姜堰|4026|jy|@dafeng|大丰|4029|df|@Qingyang|庆阳|404|qy|@Quanzhou|泉州|406|qz|@Quzhou|衢州|407|qz|@Ruian|瑞安|408|ra|@Lasa|拉萨|41|ls|@Shangrao|上饶|411|sr|@Ruili|瑞丽|412|rl|@gaoyou|高邮|4125|gy|@Kangding|康定|4130|kd|@yangchengxian|阳城县|4131|ycx|@xinmi|新密|4136|xm|@hunchun|珲春|4137|hc|@rugao|如皋|4139|rg|@BOXING|博兴|4141|bx|@ZHUCHENG|诸城|4144|zc|@hezhou|贺州|4146|hz|@qianjiang|潜江|4154|qj|@boao|博鳌|4159|ba|@Liuyang|浏阳|4185|ly|@Haikou|海口|42|hk|@Suifenhe|绥芬河|421|sfh|@shizuishan|石嘴山|4216|szs|@Shaoguan|韶关|422|sg|@zhaoyuan|招远|4251|zy|@Hulunbeier|呼伦贝尔|4255|hlbe|@Shijiazhuang|石家庄|428|sjz|@Sanya|三亚|43|sy|@Sanmenxia|三门峡|436|smx|@Sanming|三明|437|sm|@Shannan|山南|439|sn|@Wenchang|文昌|44|wc|@Siping|四平|440|sp|@Shangqiu|商丘|441|sq|@Sihui|泗水|443|ss|@Shishi|石狮|444|ss|@Shaoshan|韶山|446|ss|@Shantou|汕头|447|st|@Shaowu|邵武|448|sw|@Wanning|万宁|45|wn|@Shenyang|沈阳|451|sy|@Shiyan|十堰|452|sy|@Tai'an|泰安|454|ta|@Tonghua|通化|456|th|@Tongliao|通辽|458|tl|@Tongling|铜陵|459|tl|@Wuzhishan|五指山|46|wzs|@Tonglu|桐庐|460|tl|@Tianshui|天水|464|ts|@Tangshan|唐山|468|ts|@Tiantai|天台|470|tt|@Wudangshan|武当山|474|wds|@Weifang|潍坊|475|wf|@Wuhan|武汉|477|wh|@Wuhu|芜湖|478|wh|@Weihai|威海|479|wh|@Dongfang|东方|48|df|@Wujiang|吴江|481|wj|@Yong'an|永安|485|ya|@Wuyuan|婺源|489|wy|@Wenzhou|温州|491|wz|@Wuzhou|梧州|492|wz|@Xichang|西昌|494|xc|@Xiangyang|襄阳|496|xy|@Xiahe|夏河|497|xh|@Haerbin|哈尔滨|5|heb|@Ding'an|定安|50|da|@Xilinhaote|锡林浩特|500|xlht|@Xinxiang|新乡|507|xx|@Xinyang|信阳|510|xy|@Xuzhou|徐州|512|xz|@Yibin|宜宾|514|yb|@Yichang|宜昌|515|yc|@CHIAYI|嘉义|5152|jy|@Yichun|伊春|517|yc|@Yichun|宜春|518|yc|@Qionghai|琼海|52|qh|@Suzhou|宿州|521|sz|@Yanji|延吉|523|yj|@Yulin|榆林|527|yl|@Yining|伊宁|529|yn|@Yantai|烟台|533|yt|@Yingtan|鹰潭|534|yt|@Yiwu|义乌|536|yw|@Yixing|宜兴|537|yx|@Yueyang|岳阳|539|yy|@Baoting|保亭|54|bt|@Yuyao|余姚|540|yy|@Yanzhou|兖州|541|yz|@Zibo|淄博|542|zb|@Zigong|自贡|544|zg|@Zunhua|遵化|545|zh|@Zhanjiang|湛江|547|zj|@Zhuji|诸暨|548|zj|@Lingshui|陵水|55|ls|@Zhangjiakou|张家口|550|zjk|@Zhumadian|驻马店|551|zmd|@Zhaoqing|肇庆|552|zq|@Zhongshan|中山|553|zs|@Zhaotong|昭通|555|zt|@Zhongwei|中卫|556|zw|@Zunyi|遵义|558|zy|@PINGTUNG|屏东|5589|pd|@Zhengzhou|郑州|559|zz|@Zhangzhou|漳州|560|zz|@Zhouzhuang|周庄|561|zz|@Chengde|承德|562|cd|@Linyi|临沂|569|ly|@Danzhou|儋州|57|dz|@Jiaxing|嘉兴|571|jx|@Changdu|昌都|575|cd|@Huai'an|淮安|577|ha|@Taizhou|台州|578|tz|@Taizhou|泰州|579|tz|@Hong Kong|香港|58|xg|HongKong|XiangGang@Tongxiang|桐乡|580|tx|@Haiyan|海盐|582|hy|@Jiuhuashan|九华山|583|jhs|@Chaohu|巢湖|589|ch|@Macau|澳门|59|am|aoMen@Shangyu|上虞|595|sy|@Jiashan|嘉善|596|js|@Lanxi|兰溪|597|lx|@Xiangtan|湘潭|598|xt|@Dalian|大连|6|dl|@Zhuzhou|株洲|601|zz|@Xinyu|新余|603|xy|@Liupanshui|六盘水|605|lps|@Chenzhou|郴州|612|cz|@Zaozhuang|枣庄|614|zz|@Taipei|台北|617|tb|taibei@Wenling|温岭|619|wl|@Yandangshan|雁荡山|620|yds|@Zhangjiagang|张家港|621|zjg|@Jinyun|缙云|652|jy|@Taicang|太仓|654|tc|@Shennongjia|神农架|657|snj|@Jiande|建德|658|jd|@Anji|安吉|659|aj|@Xianggelila|香格里拉|660|xgll|@Jiuquan|酒泉|662|jq|@Zhangye|张掖|663|zy|@Wuwei|武威|664|ww|@Putian|莆田|667|pt|@Yangjiang|阳江|692|yj|@Heyuan|河源|693|hy|@HUALIEN|花莲|6954|hl|@Xuyi|盱眙|696|xy|@Qidong|启东|697|qd|@Qingdao|青岛|7|qd|@Kaohsiung|高雄|720|gx|@KINMEN|金门|7203|jm|@taishan|台山|729|ts|@Yueqing|乐清|732|lq|@Guanghan|广汉|750|gh|@wulanchabu|乌兰察布|7518|wlcb|@zoucheng|邹城|7519|zc|@Longsheng|龙胜|7521|ls|@yunlin|云林|7523|yl|@nantou|南投|7524|nt|@Beichuanxian|北川县|7525|bcx|@Tanghai|唐海|7530|th|@Daxinxian|大新县|7531|dxx|@pingyang|平阳|7533|py|@changji|昌吉|7534|cj|@pingyi|平邑|7536|py|@Maerkang|马尔康|7540|mek|@ziyuan|资源|7541|zy|@chishui|赤水|7544|cs|@Linzhou|林州|7545|lz|@Alashan|阿拉善|7548|als|@Luding|泸定|7549|ld|@Dongyang|东阳|755|dy|@Shangluo|商洛|7551|sl|@Pingdingxian|平定县|7552|pdx|@qionglai|邛崃|7553|ql|@rushan|乳山|7554|rs|@Rudong|如东|7557|rd|@sanmen|三门|7558|sm|@Haimen|海门|7559|hm|@Fopingxian|佛坪县|7568|fpx|@Taoyuan(TW)|桃园|7570|tyx|@Panan|磐安|7571|pa|@shehong|射洪|7575|sh|@Tianchang|天长|7577|tc|@Xinghua|兴化|7578|xh|@Donggang|东港|7579|dg|@kaihua|开化|7586|kh|@wuzhong|吴忠|7587|wz|@Changshan|常山|7590|cs|@Zhangqiu|章丘|7593|zq|@Yuanyang|元阳|7594|yy|@tianquan|天全|7599|tq|@Zhuozhou|涿州|7605|zz|@Lipu|荔浦|7607|lp|@Yilan|宜兰|7614|yl|@Honghu|洪湖|7618|hh|@Renhuai|仁怀|7619|rh|@xilingxueshan|西岭雪山|7622|xlxs|@Guangrao|广饶|7625|gr|@Botou|泊头|7629|bt|@yishui|沂水|7630|ys|@Lvliang|吕梁|7631|ll|@Luanchuan|栾川|7637|lc|@Eerguna|额尔古纳|7638|eegn|@Huanghua|黄骅|7644|hh|@Changge|长葛|7650|cg|@Ningcheng|宁城|7651|nc|@Xinbeishi|新北市|7662|tbx|@Suichang|遂昌|7665|sc|@Cangnan|苍南|7666|cn|@Luotianxian|罗田县|7667|ltx|@Qixia|栖霞|7669|qx|@Longhushan|龙虎山|7670|lhs|@Fengcheng|凤城|7671|fc|@Yunchengxian|郓城县|7673|ycx|@Changdao|长岛|7674|cd|@yinanxian|沂南县|7675|ynx|@Jingning|景宁|7679|jn|@Xuexiang|雪乡|7681|xx|@Luxi|泸西|7682|lx|@Danjiangkou|丹江口|7685|djk|@Gaocheng|藁城|7687|gc|@Beizhen|北镇|7698|bz|@Wulianxian|五莲县|7700|wlx|@Wuyang|舞阳|7703|wy|@Longnan|陇南|7707|ln|@Guangshan|光山|7710|gs|@Pingluoxian|平罗县|7712|plx|@Huangzhongxian|湟中县|7713|hzx|@JuNanXian|莒南县|7714|jnx|@Dayixian|大邑县|7716|dyx|@Wugongshan|武功山|7724|wgs|@Helan|贺兰|7727|hl|@Dongping|东平县|7728|dpx|@Yanshan|盐山|7733|ys|@Anqiu|安丘|7736|aq|@Jianyang|简阳|7744|jy|@Jinxiang|金乡|7745|jx|@Yijinhuoluoqi|伊金霍洛旗|7748|yjhlq|@Dalateqi|达拉特旗|7749|dltq|@Zouping|邹平|7758|zp|@Hejian|河间|7759|hj|@Yuzhou|禹州|7766|yz|@Xintai|新泰|7771|xt|@Lichuan|利川|7779|lc|@Jimunai|吉木乃|7782|jmn|@Yunhe|云和|7789|yh|@Etuokeqi|鄂托克旗|7793|etkq|@Baigou|白沟|7799|bg|@Huangnanzangzuzizhizhou|黄南藏族自治州|7802|hnzzzzz|@HongJiangShi|洪江市|7803|hjs|@Penghu|澎湖|7805|ph|@Ningyangxian|宁阳县|7806|nyx|@Haibei|海北|7807|hb|@Mazu|马祖|7808|mz|@Miaoli|苗栗|7809|ml|@Jilong|基隆|7810|jl|@Zhanghua|彰化|7811|zh|@Fakuxian|法库县|7823|fkx|@Yanshouxian|延寿县|7828|ysx|@Chengxian|成县|7829|cx|@Hongyuanxian|红原县|7835|hyx|@Wencheng|文成|7836|wc|@Qihexian|齐河县|7839|qhx|@Dongtou|洞头|7841||@Chipingxian|茌平县|7842||@Eryuan|洱源|7843||@Nantong|南通|82|nt|@Kunshan|昆山|83|ks|@Rongcheng|荣成|833|rc|@Haining|海宁|84|hn|@Yongjia|永嘉|85|yj|@Huzhou|湖州|86|hz|@Fenghuang|凤凰|866|fh|@Xianju|仙居|868|xj|@Fenghua|奉化|87|fh|@Sanqingshan|三清山|870|sqs|@Yangshuo|阳朔|871|ys|@Xinchang|新昌|872|xc|@Xinyi|新沂|895|xy|@Linan|临安|90|la|@Yangquan|阳泉|907|yq|@Tengzhou|滕州|909|tz|@Jiuzhaigou|九寨沟|91|jzg|@Xiangxiang|湘乡|917|xx|@Loudi|娄底|918|ld|@Rikaze|日喀则|92|rkz|@Lengshuijiang|冷水江|920|lsj|@Pujiang|浦江|929|pj|@Jian|吉安|933|ja|@Xianning|咸宁|937|xn|@Dujiangyan|都江堰|94|djy|@Leiyang|耒阳|940|ly|@Yongxiu|永修|943|YX|@Tongcheng|桐城|944|tc|@Tianzhushan|天柱山|945|tzs|@Xingtai|邢台|947|xt|@Emeishan|峨眉山|95|ems|@Hebi|鹤壁|951|hb|@Jieyang|揭阳|956|jy|@Yanshi|偃师|957|ys|@Gongyi|巩义|958|gy|@Wuyi|武义|959|wy|@Changshu|常熟|96|cs|@Yongkang|永康|960|yk|@Qingtian|青田|961|qt|@Haicheng|海城|963|hc|@Wafang|瓦房店|966|wfd|@Dashiqiao|大石桥|967|dsq|@Xingyang|荥阳|969|yy|@Ali|阿里|97|al|@Yongzhou|永州|970|yz|@Longyou|龙游|973|ly|@Duyun|都匀|975|dy|@Liling|醴陵|981|ll|@Qujing|曲靖|985|qj|@Zhenyuan|镇远|986|zy|@Yinchuan|银川|99|yc|@Ezhou|鄂州|992|ez|@Luguhu|泸沽湖景区(丽江)|D105_37||@tianmushan|天目山景区(临安)|D1435_90||@tianmuhu|天目湖景区(溧阳)|D1437_1358||@Xitang|西塘景区(嘉善)|D15_596||@Putuoshan|普陀山景区(舟山)|D16_19||@fuxianhu|抚仙湖景区(玉溪)|D2080_186||@Changbaishan|长白山景区(安图)|D268_1466||@Changbaishan|长白山景区(白山)|D268_3886||@wuzhen|乌镇景区(桐乡)|D508_580||@Nanxun|南浔景区(湖州)|D80_86||@Moganshan|莫干山景区(德清)|D87_1367||@"
};



/**
 * mod & event init;
 * alter by lixn@ctrip.com
 */

//address 1.0
;(function(a){function f(a,b){this._init(a,b)}function g(a){function d(){var a=this;b.each(function(b,d){b[0]==a?(b.addClass("hot_selected"),c[d].style.display=""):(b.removeClass("hot_selected"),c[d].style.display="none")})}var b=a.find("span"),c=a.find("ul");if(!b.length)return;var e=30;for(var f=0,g=b.length;f<g;f++)e+=b[f].offsetWidth;var h=a.find("div").first();h[0]&&e>278&&h.css("width",e+"px"),b.bind("mousedown",d),d.apply(b[0])}function h(a){}var b={name:"address",version:"1.0",init:function(){},uninit:function(){},module:f},c=100,d={change:1},e="_"+b.name+"_"+b.version;a.extend(f.prototype,{target:null,target_get:function(){return this.target},name:null,name_get:function(){return this.name},name_set:function(a){this.name=a,this._checkEnable()},source:null,source_get:function(c){return c?c in this.source?a.copy(this.source[c]):(a.error("mod ("+b.name+","+b.version+") source_get","invalid key "+c),null):a.copy(this.source)},source_set:function(b){b?this.source=a.extend(this.source||{},b):this.source=null,this._refresh()},jsonpSource:null,jsonpSource_get:function(){return this.jsonpSource},jsonpSource_set:function(c){this.jsonpSource=c,c?(this.source=null,this.jsonpSource=c,a.loader.jsonp(this.jsonpSource,{charset:this.charset,onload:function(a){this.source_set(a)}.bind(this)})):this.source?this.jsonpSource=null:a.error("mod ("+b.name+","+b.version+") jsonpSource_set","invalid source "+c)},jsonpFilter:null,jsonpFilter_get:function(){return this.jsonpFilter},jsonpFilter_set:function(a){this.jsonpFilter=a},sort:["^0$","^1$","^3+$","^0","^1","^3+","0","1","3+"],sort_get:function(){return a.copy(this.sort)},sort_set:function(b){this.sort=a.copy(b),this._sortReString=null,this._refresh()},display:{left:0,right:1,suggestion:1,value:1},display_get:function(c){return c?c in this.display?a.copy(this.display[c]):(a.error("mod ("+b.name+","+b.version+") display_get","invalid key "+c),null):a.copy(this.display)},display_set:function(b){b?a.extend(this.display,b):this.display={},this._refresh()},relate:{},relate_get:function(c){return c?c in this.relate?a.copy(this.relate[c]):(a.error("mod ("+b.name+","+b.version+") relate_get","invalid key "+c),null):a.copy(this.relate)},relate_set:function(b){b?this.relate=a.copy(b):this.relate={},this._refresh()},message:{suggestion:"从下列城市选择",filterResult:"${val}，按拼音排序",noFilterResult:"对不起，找不到： ${val}"},message_set:function(b){b?a.extend(this.message,b):this.message={},this._refresh()},message_get:function(c){return c?c in this.message?a.copy(this.message[c]):(a.error("mod ("+b.name+","+b.version+") message_get","invalid key "+c),null):a.copy(this.message)},offset:null,offset_set:function(b){b?this.offset=a.copy(b):this.offset=null,this._refresh()},offset_get:function(b){return a.copy(this.offset)},minLength:1,row:12,isAutoCorrect:!1,isFocusNext:!1,template:{suggestion:'\t\t\t\t<div class="c_address_box">\t\t\t\t\t<div class="c_address_hd">${message.suggestion}</div>\t\t\t\t\t<div class="c_address_bd">\t\t\t\t\t\t<ol class="c_address_ol">\t\t\t\t\t\t\t{{enum(key) data}}\t\t\t\t\t\t\t\t<li><span>${key}</span></li>\t\t\t\t\t\t\t{{/enum}}\t\t\t\t\t\t</ol>\t\t\t\t\t\t{{enum(key,arr) data}}\t\t\t\t\t\t\t<ul class="c_address_ul layoutfix">\t\t\t\t\t\t\t\t{{each arr}}\t\t\t\t\t\t\t\t\t<li><a data="${data}" href="javascript:void(0);">${display}</a></li>\t\t\t\t\t\t\t\t{{/each}}\t\t\t\t\t\t\t</ul>\t\t\t\t\t\t{{/enum}}\t\t\t\t\t</div>\t\t\t\t</div>\t\t\t',suggestionStyle:"\t\t\t\t.c_address_box { background-color: #fff; font-size: 12px; width: 290px; }\t\t\t\t.c_address_box a { text-decoration: none; }\t\t\t\t.c_address_hd { height: 24px; border-color: #2C7ECF; border-style: solid; border-width: 1px 1px 0; background-color: #67A1E2; color:#CEE3FC; line-height: 24px; padding-left: 10px; }                .c_address_hd strong{color:#fff;}\t\t\t\t.c_address_bd { border-color: #999999; border-style: solid; border-width: 0 1px 1px; overflow: hidden; padding:10px; }\t\t\t\t.c_address_ol { margin:0; padding:0 0 20px; border-bottom: 1px solid #5DA9E2; }\t\t\t\t.c_address_ol li { color: #005DAA; cursor: pointer; float: left; height: 20px; line-height: 20px; list-style-type: none; text-align: center; }\t\t\t\t.c_address_ol li span { padding:0 8px; white-space:nowrap; display:block; }\t\t\t\t.c_address_ol li .hot_selected { display:block; padding:0 7px; background-color: #FFFFFF; border-color: #5DA9E2; border-style: solid; border-width: 1px 1px 0; color: #000000; font-weight: bold; }\t\t\t\t.c_address_ul { width: 100%; margin:0; padding: 4px 0 0; }\t\t\t\t.c_address_ul li { float: left; height: 24px; overflow: hidden; width: 67px; }\t\t\t\t.c_address_ul li a { display: block; height: 22px;  border: 1px solid #FFFFFF; color: #1148A8; line-height: 22px; padding-left: 5px; }\t\t\t\t.c_address_ul li a:hover { background-color: #E8F4FF; border: 1px solid #ACCCEF; text-decoration: none; }\t\t\t",suggestionInit:g,filter:'\t\t\t\t<div class="c_address_select">\t\t\t\t\t<div class="c_address_wrap">\t\t\t\t\t\t<div class="c_address_hd">{{if hasResult}}{{tmpl message.filterResult}}{{else}}{{tmpl message.noFilterResult}}{{/if}}</div>\t\t\t\t\t\t<div class="c_address_list" style="">\t\t\t\t\t\t\t{{each list}}\t\t\t\t\t\t\t\t<a href="javascript:void(0);" data="${data}" style="display: block;"><span>${left}</span>${right}</a>\t\t\t\t\t\t\t{{/each}}\t\t\t\t\t\t</div>\t\t\t\t\t\t{{if page.max>-1}}\t\t\t\t\t\t\t<div class="c_address_pagebreak" style="display: block;">\t\t\t\t\t\t\t\t{{if page.current>0}}\t\t\t\t\t\t\t\t\t<a href="javascript:void(0);" page="${page.current-1}">&lt;-</a>\t\t\t\t\t\t\t\t{{/if}}\t\t\t\t\t\t\t\t{{if page.current<2}}\t\t\t\t\t\t\t\t\t{{loop(index) Math.min(5,page.max+1)}}\t\t\t\t\t\t\t\t\t\t<a href="javascript:void(0);"{{if page.current==index}} class="address_current"{{/if}} page="${index}">${index+1}</a>\t\t\t\t\t\t\t\t\t{{/loop}}\t\t\t\t\t\t\t\t{{else page.current>page.max-2}}\t\t\t\t\t\t\t\t\t{{loop(index) Math.max(0,page.max-4),page.max+1}}\t\t\t\t\t\t\t\t\t\t<a href="javascript:void(0);"{{if page.current==index}} class="address_current"{{/if}} page="${index}">${index+1}</a>\t\t\t\t\t\t\t\t\t{{/loop}}\t\t\t\t\t\t\t\t{{else}}\t\t\t\t\t\t\t\t\t{{loop(index) Math.max(0,page.current-2),Math.min(page.current+3,page.max+1)}}\t\t\t\t\t\t\t\t\t\t<a href="javascript:void(0);"{{if page.current==index}} class="address_current"{{/if}} page="${index}">${index+1}</a>\t\t\t\t\t\t\t\t\t{{/loop}}\t\t\t\t\t\t\t\t{{/if}}\t\t\t\t\t\t\t\t{{if page.current<page.max}}\t\t\t\t\t\t\t\t\t<a href="javascript:void(0);" page="${page.current+1}">-&gt;</a>\t\t\t\t\t\t\t\t{{/if}}\t\t\t\t\t\t\t</div>\t\t\t\t\t\t{{/if}}\t\t\t\t\t</div>\t\t\t\t</div>\t\t\t',filterStyle:"\t\t\t\t.c_address_hd { height: 24px; border-color: #2C7ECF; border-style: solid; border-width: 1px 1px 0; background-color: #67A1E2; color: #fff; line-height: 24px; padding-left: 10px; }\t\t\t\t.c_address_bd { border-color: #999999; border-style: solid; border-width: 0 1px 1px; overflow: hidden; padding:10px; }\t\t\t\t.c_address_select { width:222px; /*height:355px;*/height:auto; font-family: Arial, Simsun; font-size: 12px; }\t\t\t\t.c_address_wrap { width: 220px; /*height:349px; min-height: 305px;*/height:auto; margin: 0; padding: 0 0 4px; border: 1px solid #969696; background:#fff; text-align: left; }\t\t\t\t.c_address_hd { margin:-1px; }\t\t\t\t.c_address_list { margin: 0; padding: 0; /*height:300px;*/height:auto; }\t\t\t\t.c_address_list span { float: right; font: 10px/22px verdana; margin: 0; overflow: hidden; padding: 0; text-align: right; white-space: nowrap; width: 110px; }\t\t\t\t.c_address_list a { border-bottom: 1px solid #FFFFFF; border-top: 1px solid #FFFFFF; color: #0055AA; cursor: pointer; display: block; height: 22px; line-height: 22px; min-height: 22px; overflow: hidden; padding: 1px 9px 0; text-align: left; text-decoration: none; }\t\t\t\t.c_address_list a.hover { background: none repeat scroll 0 0 #E8F4FF; border-bottom: 1px solid #7F9DB9; border-top: 1px solid #7F9DB9; }\t\t\t\t.address_selected { background: none repeat scroll 0 0 #FFE6A6; color: #FFFFFF; height: 22px; }\t\t\t\t.c_address_pagebreak { line-height: 25px; margin: 0; padding: 0; text-align: center; }\t\t\t\t.c_address_pagebreak a { color: #0055AA; display: inline-block; font-family: Arial, Simsun, sans-serif; font-size: 14px; margin: 0; padding: 0 4px; text-align: center; text-decoration: underline; width: 15px; }\t\t\t\ta.address_current { color: #000; text-decoration: none; }\t\t\t",filterInit:h},validate:function(a,b){var c=this,d=this.target.value().trim().replace(/[\|@]/g,"");if(!d)return this._unselect(!0,!0),!1;if(this.jsonpFilter)return this._filterDataByJsonp(this.jsonpFilter,d,a,!0,function(a){a?(c._select(a,!0),b&&b(!0)):(c._unselect(!1,!0),b&&b(!1))}),!1;var e=this._filterData(this.source.data,d,a,!0);return e?(this._select(e,!0),b&&b(!0),!0):(this._unselect(!1,!0),b&&b(!1),!1)},bind:function(a,b,c){return this._event("bind",a,b,c),this},unbind:function(a,b){return this._event("unbind",a,b),this},trigger:function(a,b){return this._event("trigger",a,b),this},_enable:!1,_parentDocument:null,_parentWindow:null,_iframeDocument:null,_iframeWindow:null,_iframeObject:null,_iframeContainter:null,_lastIframeSize:null,_iframeClock:null,_iframeStyle:"width:0;height:0;position:absolute;top:-100000px;left:-100000px;z-index:200;",_placeHolder:a.browser.isIE?'<pre style="display:none;">placeholder</pre>':"",_isFocus:!1,_focusClock:null,_lastValue:null,_isCharIn:!1,_suggestionContainer:null,_suggestionStyle:"position:absolute;top:-100000px;left:-100000px;z-index:200;",_isSuggestionRender:!1,_filterContainer:null,_filterStyle:"position:absolute;top:-100000px;left:-100000px;z-index:200;",_sortReString:null,_displayRegExp:null,_displayHash:null,_colsHash:null,_lastFilterData:null,_lastFilterRendarData:null,_lastSelect:null,_suggestionEnable:!1,_filterEnable:!1,_filterCount:0,_selectFlag:!1,_init:function(c,d){if(!d.name||a.type(name)!="string"){a.error("mod ("+b.name+","+b.version+") init","invalid name "+name);return}var e=this;this.target=a(c),this.name=d.name,this.row=d.row||this.row,this.source=d.source||this.source,this.jsonpSource=d.jsonpSource||this.jsonpSource,this.charset=d.charset||cQuery.config("charset"),this.source||this.jsonpSource&&a.loader.jsonp(this.jsonpSource,{charset:this.charset,onload:function(a){this.source_set(a)}.bind(this)}),this.jsonpFilter=d.jsonpFilter||this.jsonpFilter,d.sort&&a.type(d.sort)=="array"&&(this.sort=d.sort,this._sortReString=null),d.display&&(this.display=a.extend(!0,{},this.display,d.display)),this.relate=d.relate||this.relate,d.message&&(this.message=a.extend(!0,{},this.message,d.message)),d.offset&&(this.offset=d.offset),this.minLength=d.minLength||this.minLength,this.priority=d.priority||this.priority,this.isAutoCorrect=d.isAutoCorrect||this.isAutoCorrect,this.isFocusNext=d.isFocusNext||this.isFocusNext,d.template&&(this.template=a.extend(!0,{},this.template,d.template)),this.isIframe="isIframe"in d?!!d.isIframe:function(){var b=e.target[0].ownerDocument,c=b.defaultView||b.parentWindow;try{var d=c.frameElement;return d&&(e._parentDocument=d.ownerDocument,e._parentWindow=e._parentDocument.defaultView||e._parentDocument.parentWindow,e._iframeObject=a(e._parentDocument.createElement("iframe")),e._iframeObject.css(e._iframeStyle),e._iframeObject[0].frameBorder=0,e._iframeObject.prependTo(e._parentDocument.body),e._iframeObject.html('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="content-type" content="text/html; charset=utf-8" /><title>address</title><style>html,body{padding:0;margin:0;overflow:hidden;}</style></head><body></div></body></html>',function(){e._iframeWindow=e._iframeObject[0].contentWindow||e._iframeObject[0].window,e._iframeDocument=e._iframeWindow.document,e._iframeContainer=a(e._iframeDocument.createElement("container")),e._iframeContainer.css("position","absolute"),e._iframeContainer.appendTo(e._iframeDocument.body)})),!!d}catch(f){return!1}}(),a.bindMethod(this),this._initTmpl(),this._initEvent(),this.uninit=this._uninit()},_initTmpl:function(){},_initEvent:function(){this.target.bind("focus",this._focus),this.target.bind("blur",this._blur),this.target.bind("keydown",this._keydown),this.target.bind("keypress",this._keypress),this.target.bind("mouseup",this._mouseup),this.target.bind("mousedown",this._focus),this.target.bind("keydown",this._focus)},_initCols:function(){if(this._colsHash)return;var a=this._colsHash={};if(this.source.alias)for(var b=0,c=this.source.alias.length;b<c;b++)this._colsHash[this.source.alias[b]]=b},_initSort:function(){this._initCols();if(this._sortReString)return;var b=this._sortReString={accurate:[],vague:[]},c=0,d=0;for(var e=0,f=this.sort.length;e<f;e++){var g=this.sort[e].match(/^(\^?)([^\^\$\|@\r\n\+]+)(\+?)(\$?)$/);if(g){if(/^\d$/.test(g[2]))g[2]=parseInt(g[2],10);else{if(!(g[2]in this._colsHash)){a.error("address._initSort","invalid sort column "+g[2]);continue}g[2]=this._colsHash[g[2]]}var h=+g[2]||g[3]?"([^\\|@]*\\|){"+g[2]+(g[3]?",":"")+"}":"";b.accurate[c++]=["@("+h,"","(\\|[^@]*)?)(?=@)"],b.vague[d++]=["@("+h+(g[1]?"":"[^\\|@]*"),"",(g[4]?"(\\|[^@]*)?":"[^@]*")+")(?=@)"]}else a.error("address._initSort","invalid sort rule "+this.sort[e])}},_initDisplay:function(){this._initCols();if(this._displayHash)return;var b=this._displayHash={},c,d;for(c in this.display){d=this.display[c];if(/^\d$/.test(d))b[c]=parseInt(d,10);else{if(!(d in this._colsHash)){b[c]=null,a.error("address._initDisplay","invalid display column "+arr[1]);continue}b[c]=this._colsHash[d]}}},_refresh:function(){this._colsHash=null,this._sortReString=null,this._lastValue=null,this._isSuggestionRender=!1,this._isFocus&&this._focusInterval()},_checkEnable:function(){},_focus:function(b){if(this._isFocus){!this._suggestionEnable&&!this._filterEnable&&this._showSuggestion();return}this._isFocus=!0,this._isCharIn=!1,this._lastValue=null,this._clearFilter(),this._focusInterval(),clearInterval(this._focusClock);var d=this.isIframe?this._iframeContainer:a.container;this._suggestionContainer&&this._suggestionContainer.appendTo(d),this._filterContainer&&this._filterContainer.appendTo(d),this._focusClock=setInterval(this._focusInterval,c),this.isIframe&&(clearInterval(this._iframeClock),this._iframeClock=setInterval(this._fixIframeSize,c));var e=this.target[0];switch(b.type){case"mousedown":setTimeout(function(){e.select()});break;case"focus":e.select()}},_blur:function(){var a=this;this._isFocus=!1,this._hiddenSuggestion(),this._hiddenFilter(),clearInterval(this._focusClock),this.isIframe&&clearInterval(this._iframeClock);if(!this._selectFlag&&this.isAutoCorrect){if(!this.source){this._unselect(!0,!0);return}this._unselect(!1,!0),this.validate(!this.isAutoCorrect,function(b){b||a._unselect(!0,!1)})}},_keypress:function(){this._isCharIn=!0},_keydown:function(b){switch(b.keyCode){case 13:if(this._filterEnable){var c=this._lastSelect;if(c){var d=c[0].getAttribute("data");this._select(d)}}break;case 37:case 39:if(this._filterEnable){var e=this._lastFilterRendarData;if(e&&e.page.max>=0)switch(b.keyCode){case 37:e.page.current>0&&this._updateFilter(null,null,e.page.current-1);break;case 39:e.page.current<e.page.max&&this._updateFilter(null,null,e.page.current+1)}}break;case 38:case 40:if(this._filterEnable){var c=this._lastSelect,e=this._lastFilterRendarData;if(c!==null&&e){var f=this._filterContainer.find("*[data]"),g=f.indexOf(this._lastSelect);if(g!=-1){var h=e.list.length;g=(g+h+b.keyCode-39)%h,this._showHover(f[g])}}}break;default:return a.browser.isIPadUCWeb&&(this._isCharIn=!0),!0}return b.stop(),!1},_mouseup:function(){var a=this.target[0];a.releaseCapture&&a.releaseCapture()},_focusInterval:function(){var a=this.target.value().trim().replace(/[\|@]/g,"");if(!this.source||this._lastValue===a)return;this._lastValue!==null&&(this._isCharIn=!0),this._lastValue=a,a.length>=this.minLength&&this._isCharIn?this._showFilter(a):this._showSuggestion()},_showSuggestion:function(){this._suggestionEnabled=!0,this._hiddenFilter(),this._clearFilter();if(!this._suggestionContainer){if(this.isIframe){var b=this._iframeDocument.createElement("div");b.id="address_suggestionContainer_"+this.target.uid(),a(b).appendTo(this._iframeContainer)}else{var b=document.createElement("div");b.id="address_suggestionContainer_"+this.target.uid(),b.style.cssText=this._suggestionStyle,a(b).appendTo(a.container)}this._suggestionContainer=a(b),this._suggestionContainer.bind("mousedown",this._filterMousedown)}if(!this._isSuggestionRender){this._isSuggestionRender=!0;var c=[],d=0;a.type(this.template.suggestionStyle)=="string"&&(c[d++]=this._placeHolder+"<style>"+this.template.suggestionStyle.replace(/(\s*)([^\{\}]+)\{/g,"$1#"+this._suggestionContainer[0].id+" $2{")+"</style>");var e={data:this.source.suggestion,message:this.message};c[d++]=a.tmpl.render(this.template.suggestion,e),this._suggestionContainer.html(c.join("")),a.type(this.template.suggestionInit)=="function"&&this.template.suggestionInit(this._suggestionContainer)}this.isIframe?(this._suggestionContainer.css("display",""),this._fixIframeSize(),this._iframeObject.offsetA(this.target,this.offset)):(this._suggestionContainer.offset(this.target,this.offset),this._suggestionContainer.cover())},_fixIframeSize:function(){if(!this.isIframe)return;var a=this._iframeContainer[0],b=a.offsetWidth+"px",c=a.offsetHeight+"px";if(this._lastIframeSize&&b==this._lastIframeSize.width&&c==this._lastIframeSize.height)return;this._lastIframeSize={width:b,height:c},this._iframeObject.css(this._lastIframeSize)},_hiddenSuggestion:function(){this._suggestionEnable=!1,this._suggestionContainer&&(this.isIframe?(this._suggestionContainer.css("display","none"),this._iframeObject.css(this._iframeStyle),this._lastIframeSize=null):(this._suggestionContainer.css(this._suggestionStyle),this._suggestionContainer.uncover()))},_showFilter:function(b){this._filterEnable=!0,this._filterCount++,this._hiddenSuggestion();if(!this._filterContainer){if(this.isIframe){var c=this._iframeDocument.createElement("div");c.id="address_filterContainer_"+this.target.uid(),a(c).appendTo(this._iframeContainer)}else{var c=document.createElement("div");c.id="address_filterContainer_"+this.target.uid(),c.style.cssText=this._filterStyle,a(c).appendTo(a.container)}this._filterContainer=a(c),this._filterContainer.bind("mouseover",this._filterMouseover),this._filterContainer.bind("mousedown",this._filterMousedown)}b=b.replace(/[\|@]/g,""),this.jsonpFilter?this._filterDataByJsonp(this.jsonpFilter,b):this._filterData(this.source.data,b)},_clearFilter:function(){this._lastFilterData=null,this._lastFilterRendarData=null,this._lastSelect=null},_filterMouseover:function(a){var b=a.target;while(b&&b.tagName!="A")b=b.parentNode;if(b){var c=b.getAttribute("data");if(c){this._showHover(b);return}}},_filterMousedown:function(b){var c=b.target;while(c&&c.tagName!="A")c=c.parentNode;if(c){var d=c.getAttribute("data");if(d)if(a.browser.isIE){var e=this;c.onclick=function(){e._select(d)},this._isSuggestionRender=!1}else this._select(d);var f=c.getAttribute("page");f&&this._updateFilter("","",+f)}return b.stop(),c=this.target[0],c.setCapture&&c.setCapture(),!1},_hiddenFilter:function(){this._filterEnable=!1,this._filterContainer&&(this.isIframe?(this._filterContainer.css("display","none"),this._iframeObject.css(this._iframeStyle),this._lastIframeSize=null):(this._filterContainer.css(this._filterStyle),this._filterContainer.uncover()))},_filterData:function(a,b,c,d){this._initSort(),this._initDisplay();var e=this,f=[],g=0,h=a,i=this._sortReString[c?"accurate":"vague"],j=b.toReString();for(var k=0,l=i.length;k<l;k++){i[k][1]=j;var m=new RegExp(i[k].join(""),"gi"),n=[],o=0;h=h.replace(m,function(a,b){var c=b.split("|"),d={left:c[e._displayHash.left]||"",right:c[e._displayHash.right]||"",data:b};return n[o++]=d,""});if(o){n.sort(this._sortData);if(d)return n[0].data;f[g++]=n}}if(d)return!1;f=Array.prototype.concat.apply([],f),this._updateFilter(f,b)},_filterDataByJsonp:function(b,c,d,e,f){this._initSort(),this._initDisplay();var g=[],h=0,i=this._filterCount;b=a.tmpl.render(b,{key:escape(c),accurate:d?1:0}),a.loader.jsonp(b,{charset:this.charset,onload:function(b){if((this._filterEnable||e)&&i==this._filterCount){var c=b.data.split("@");for(var d=0,j=c.length;d<j;d++)if(c[d]){if(e){a.type(f)=="function"&&f(c[d]);return}var l=c[d].split("|"),m={left:l[this._displayHash.left]||"",right:l[this._displayHash.right]||"",data:c[d]};g[h++]=m}this._updateFilter(g,b.key)}}.bind(this)});if(e)return!1},_updateFilter:function(b,c,d){var e=[],f=0;a.type(this.template.filterStyle)=="string"&&(e[f++]=this._placeHolder+"<style>"+this.template.filterStyle.replace(/(\s*)([^\{\}]+)\{/g,"$1#"+this._filterContainer[0].id+" $2{")+"</style>");var g=0;a.type(b)=="array"?this._lastFilterData=b:(b=this._lastFilterData,g=this._lastSelect?this._filterContainer.find("*[data]").indexOf(this._lastSelect):-1),d=d||0;var h=Math.ceil(b.length/this.row)-1,i=Math.min(Math.max(0,d),h),j={val:c||this._lastValue,hasResult:!0,list:h+1?b.slice(i*this.row,Math.min((i+1)*this.row,b.length)):null,page:{max:h,current:i},message:this.message};if(j.list)this._lastFilterRendarData=j;else{j=this._lastFilterRendarData;if(!j){this._clearFilter(),this._hiddenFilter();return}c&&(j.val=c,j.hasResult=!1)}e[f++]=a.tmpl.render(this.template.filter,j),this._filterContainer.html(e.join("")),a.type(this.template.filterInit)=="function"&&this.template.filterInit(this._filterContainer),g=Math.max(0,Math.min(g,j.list.length-1));var k=this._filterContainer.find("*[data]");this._showHover(k[g]),this.isIframe?(this._filterContainer.css("display",""),this._fixIframeSize(),this._iframeObject.offsetA(this.target,this.offset)):(this._filterContainer.offset(this.target,this.offset),this._filterContainer.cover())},_sortData:function(a,b){return a.left>b.left?1:a.left==b.left?0:-1},_showHover:function(b){b?b=a(b):b=this._filterContainer.find("*[data]:first");if(this._lastSelect){if(this._lastSelect[0]==b[0])return;this._lastSelect.removeClass("hover")}b.addClass("hover"),this._lastSelect=b},_getItems:function(a){this._initCols();var b=a.split("|"),c={length:b.length};for(var d=0,e=b.length;d<e;d++)c[d]=b[d];var f=this.source.alias;if(f)for(var d=0,e=f.length;d<e;d++)c[f[d]]=b[d];return c},_select:function(b,c){var d=this;this._selectFlag=!0,setTimeout(function(){d._selectFlag=!1}),this._initDisplay();var e=b.split("|"),f=e[this._displayHash.value]||"";this._lastValue=f.trim(),this.target.value(f),this._clearFilter(),this._hiddenSuggestion(),this._hiddenFilter();if(this.relate)for(var g in this.relate)if(this.relate.hasOwnProperty(g)){var h=a(this.relate[g]);if(!h[0]){a.error("address._select","invalid relate element");continue}if(/^\d$/.test(g))g=parseInt(g,10);else{if(!(g in this._colsHash)){a.error("address._select","invalid relate column "+g);continue}g=this._colsHash[g]}h.value(e[g]||"")}this.trigger("change",{value:f,data:b,items:this._getItems(b)});if(!c&&this.isFocusNext){var i=this.target[0].form;if(!i){a.error("address._select","invalid form");return}var j=i.elements;for(var k=0,l=j.length-1;k<l;k++)if(j[k]==this.target[0]){j[k+1].focus();return}}},_unselect:function(b,c){b&&(this._lastValue=null,this.target.value(""),this._clearFilter(),this._hiddenSuggestion(),this._hiddenFilter(),this.trigger("change",{value:"",data:"",items:null}));if(c&&this.relate)for(var d in this.relate)if(this.relate.hasOwnProperty(d)){var e=a(this.relate[d]);if(!e[0]){a.error("address._select","invalid relate element");continue}e.value("")}},_event:function(b,c){switch(a.type(c)){case"string":c=[c];break;case"array":break;default:a.error("address."+b,"Invalid types "+c);return}var f;for(var g=0,h=c.length;g<h;g++){f=c[g].trim();if(!f||a.type(f)!="string"){a.error("address."+b,"Invalid type "+f);continue}f in d?this.target[b].apply(this.target,[f+e].concat(Array.prototype.slice.call(arguments,2))):a.error("address."+b,"Unsupport type "+f)}},_uninit:function(){}}),a.mod.reg(b)})(cQuery);

$.mod.load('calendar','1.0');
//$.mod.load('address','1.0');
$.mod.load('page','1.2');
$.mod.load('validate','1.0');
	var ins;
	var mod = {
		doc: null,
		pop: null,
		init: function(doc,ifr){
			if(!ins){
				ins = $(document).regMod('validate','1.0',{
					callback:function () {
						mod.pop = this.publicMethods();
					}
				});
			}
			
			this.doc = doc;
			
			this.inputTipInit(doc);
			this.addressInit(doc);
			this.calendarInit(doc,ifr);
		},
		warn: function(targ,msg,pos){
			if(typeof targ == 'string'){
				targ = $('#'+targ,this.doc)
			}else{
				targ = $(targ)
			}
			if(this.pop){
				if(msg){
					var pos = pos || "rm_lm";
					this.pop.show({
						$obj: targ,
						data: msg,
						position: pos,
						iframe:true
					});
				}else{
					this.pop.hide();
				}
			}
		},
		inputTipInit: function(doc){
			$('input[type="text"]', doc).each(function(item,i){
				if(item.value() != ''){
					item.bind('focus',function(){
						if(this.value == this.defaultValue){
							this.value = '';
							this.style.color = '#333';
						}
					}).bind('blur', function(){
						if(this.value == ''){
							this.value = this.defaultValue;
							this.style.color = '#777';
						}
					});
				}
			});
		},
		addressInit: function(doc){
				//=酒店
				if($('#cityname',doc).length > 0){
					var a1 = $('#cityname',doc).regMod('address','1.0',{
						name:'cityname',
						jsonpSource:'http://webresource.c-ctrip.com/code/cquery/resource/address/hotel/index/city_gb2312.js',
						isFocusNext:false,
						charset: 'gb2312',
						isAutoCorrect:true,
						row: 6,
						relate: {
							'id': $('#cityId', doc)
						}
					});
					// a1.method('validate');
				}
				if($('#citynameInter',doc).length > 0){
					var a2 = $('#citynameInter',doc).regMod('address','1.0',{
						name:'citynameInter',
						jsonpSource:'http://webresource.ctrip.com/code/cquery/resource/address/hotelintl/online/city_gb2312.js',
						isFocusNext:false,
						charset: 'gb2312',
						row: 6,
						isAutoCorrect:true,
						relate: {
							'id': $('#DistrictId', doc)
						}
					});
				}
				//=机票
				if($('#homecity_name',doc).length > 0){
					var ta1 = $('#homecity_name',doc).regMod('address','1.0',{
						name:'homecity_name',
						//jsonpSource:'http://webresource.c-ctrip.com/code/cquery/resource/address/hotel/index/city_gb2312.js',
						//'http://webresource.ctrip.com/code/cquery/resource/address/flight/flight_gb2312.js',
						// http://webresource.ctrip.com/code/cquery/resource/address/flight/flight_gb2312.js
						source: {
						     //alias=['name_py','name','id','jianpin','name_search','pname','pname_pingying','pjianpin'];
						     suggestion:{
								'热门':[{display:"北京",data:"|北京|BJS"},{display:"上海",data:"|上海|SHA"},{display:"广州",data:"|广州|CAN"},{display:"深圳",data:"|深圳|SZX"},{display:"成都",data:"|成都|CTU"},{display:"杭州",data:"|杭州|HGH"},{display:"武汉",data:"|武汉|WUH"},{display:"西安",data:"|西安|SIA"},{display:"重庆",data:"|重庆|CKG"},{display:"青岛",data:"|青岛|TAO"},{display:"长沙",data:"|长沙|CSX"},{display:"南京",data:"|南京|NKG"},{display:"厦门",data:"|厦门|XMN"},{display:"昆明",data:"|昆明|KMG"},{display:"大连",data:"|大连|DLC"},{display:"天津",data:"|天津|TSN"},{display:"郑州",data:"|郑州|CGO"},{display:"三亚",data:"|三亚|SYX"},{display:"济南",data:"|济南|TNA"},{display:"福州",data:"|福州|FOC"}]
							},
							alias: ['name_py','name','code','alias'],
							//data: ''
							data:"@Aletai|阿勒泰|AAT|@Xingyi|兴义|ACX|@Baise|百色|AEB|@Ankang|安康|AKA|@Akesu|阿克苏|AKU|@Anshan|鞍山|AOG|@Anqing|安庆|AQG|@Anshun|安顺|AVA|@Baotou|包头|BAV|@Beihai|北海|BHY|@Bole|博乐|BPL|@Changdu|昌都|BPX|@Baoshan|保山|BSD|@Guangzhou|广州|CAN|@Changde|常德|CGD|@Zhengzhou|郑州|CGO|@Changchun|长春|CGQ|@Chaoyang|朝阳|CHG|@Chifeng|赤峰|CIF|@Changzhi|长治|CIH|@Chongqing|重庆|CKG|@Changsha|长沙|CSX|@Chengdu|成都|CTU|@Changzhou|常州|CZX|@Datong|大同|DAT|@Daxian|达县|DAX|@Dandong|丹东|DDG|@Diqing|迪庆|DIG|@Dalian|大连|DLC|@Dali|大理市|DLU|@Dunhuang|敦煌|DNH|@Dongying|东营|DOY|@Daqing|大庆|DQA|@E'erduosi|鄂尔多斯|DSN|@Zhangjiajie|张家界|DYG|@Enshi|恩施|ENH|@Yan'an|延安|ENY|@ELIANHAOTE|二连浩特|ERL|@Fuzhou|福州|FOC|@Fuyang|阜阳|FUG|@Foshan|佛山|FUO|@Guanghan|广汉|GHN|@Ge'ermu|格尔木|GOQ|@Guangyuan|广元|GYS|@Guyuan|固原|GYU|@Haikou|海口|HAK|@Handan|邯郸|HDG|@Heihe|黑河|HEK|@Huhehaote|呼和浩特|HET|@Hefei|合肥|HFE|@Hangzhou|杭州|HGH|@Huai'an|淮安|HIA|@Huaihua|怀化|HJJ|@Hong Kong|香港|HKG|XiangGang@Hailaer|海拉尔|HLD|@Wulanhaote|乌兰浩特|HLH|@Hami|哈密市|HMI|@Haerbin|哈尔滨|HRB|@Zhoushan|舟山|HSN|@Hetian|和田市|HTN|@HUALIEN|花莲|HUN|HuaLian@Taizhou|台州|HYN|@Hanzhong|汉中|HZG|@LIPING|黎平|HZH|@Yinchuan|银川|INC|@Qiemo|且末|IQM|@Qingyang|庆阳|IQN|@Jingdezhen|景德镇|JDZ|@Jiayuguan|嘉峪关|JGN|@Jinggangshan|井冈山|JGS|@Xishuangbanna|西双版纳|JHG|@Jinchang|金昌|JIC|@Jilin|吉林|JIL|@Qianjiang|黔江|JIQ|@Jiujiang|九江|JIU|@Jinjiang|晋江|JJN|@Jiamusi|佳木斯|JMU|@Jining|济宁|JNG|@Jinzhou|锦州|JNZ|@Quzhou|衢州|JUZ|@Jixi|鸡西|JXA|@Jiuzhaigou|九寨沟|JZH|@Kuche|库车|KCA|@Kangding|康定|KGT|@Kashi|喀什市|KHG|@Kaohsiung|高雄|KHH|GaoXiong@Nanchang|南昌|KHN|@kanasi|喀纳斯|KJI|@Kunming|昆明|KMG|@Ganzhou|赣州|KOW|@Kuerle|库尔勒|KRL|@Kelamayi|克拉玛依|KRY|@Guiyang|贵阳|KWE|@Guilin|桂林|KWL|@Longyan|龙岩|LCX|@Yichun|伊春|LDS|@Guanghua|光化|LHK|@Lanzhou|兰州|LHW|@Liangping|梁平|LIA|@Lijiang|丽江|LJG|@libo|荔波|LLB|@Yongzhou|永州|LLF|@Lincang|临沧|LNJ|@Dehong|德宏|LUM|@Lasa|拉萨|LXA|@Linxi|林西|LXI|@Luoyang|洛阳|LYA|@Lianyungang|连云港|LYG|@Linyi|临沂|LYI|@Liuzhou|柳州|LZH|@Luzhou|泸州|LZO|@Linzhi|林芝|LZY|@Mudanjiang|牡丹江|MDG|@Macau|澳门|MFM|AoMen@Mianyang|绵阳|MIG|@Meizhou|梅州|MXZ|@MAKUNG|马公|MZG|@Nanchong|南充|NAO|@Beijing|北京|BJS|@Changbaishan|长白山|NBS|@Qiqihaer|齐齐哈尔|NDG|@Ningbo|宁波|NGB|@Ali|阿里|NGQ|@Nanjing|南京|NKG|@Nalati|那拉提|NLT|@Nanning|南宁|NNG|@Nanyang|南阳|NNY|@Nantong|南通|NTG|@Manzhouli|满洲里|NZH|@Mohe|漠河|OHE|@Shanghai|上海|SHA|@Panzhihua|攀枝花|PZI|@Rikaze|日喀则|RKZ|@Bayannaoer|巴彦淖尔|RLK|@Taichung|台中|TXG|TaiZhong@Shenyang|沈阳|SHE|@Qinhuangdao|秦皇岛|SHP|@Shashi|沙市|SHS|@Shijiazhuang|石家庄|SJW|@Jieyang|揭阳|SWA|@Puer|普洱|SYM|@Sanya|三亚|SYX|@Shenzhen|深圳|SZX|@Qingdao|青岛|TAO|@Tacheng|塔城|TCG|@tengchong|腾冲|TCZ|@Tongren|铜仁市|TEN|@Tongliao|通辽|TGO|@Tianshui|天水|THQ|@Jinan|济南|TNA|@Taipei|台北|TPE|TaiBei@Tianjin|天津|TSN|@Taitung|台东|TTT|@Tangshan|唐山|TVS|@Huangshan|黄山|TXN|@Taiyuan|太原|TYN|@Wulumuqi|乌鲁木齐|URC|@Yulin|榆林|UYN|@Weifang|潍坊|WEF|@Weihai|威海|WEH|@Wenshan|文山县|WNH|@Wenzhou|温州|WNZ|@Wuhai|乌海|WUA|@Wuhan|武汉|WUH|@Wuyishan|武夷山|WUS|@Wuxi|无锡|WUX|@Wuzhou|梧州|WUZ|@WANZHOU|万州|WXN|@Xiangyang|襄阳|XFN|@Xichang|西昌|XIC|@Xilinhaote|锡林浩特|XIL|@Xian|西安|SIA|@Xiamen|厦门|XMN|@Xining|西宁|XNN|@Xuzhou|徐州|XUZ|@Yibin|宜宾|YBP|@Yuncheng|运城|YCU|@Aershan|阿尔山|YIE|@Yichang|宜昌|YIH|@Yining|伊宁市|YIN|@Yiwu|义乌|YIW|@Yanji|延吉|YNJ|@Yantai|烟台|YNT|@Yancheng|盐城|YNZ|@Yangzhou|扬州|YTY|@yushu|玉树县|YUS|@Zhangye|张掖|YZY|@Zhaotong|昭通|ZAT|@Zhongshan|中山|ZGN|@Zhanjiang|湛江|ZHA|@Zhongwei|中卫|ZHY|@Zhuhai|珠海|ZUH|@Dazhou|达州|DAX|@dali|大理|DLU|@hami|哈密|HMI|@hetian|和田|HTN|@Huangyan|黄岩|HYN|@Jinghong|景洪|JHG|@kashi|喀什|KHG|@Liancheng|连城|LCX|@Mangshi|芒市|LUM|@MEI XIAN|梅县|MXZ|@Putuoshan|普陀山|HSN|@Quanzhou|泉州|JJN|@Shanhaiguan|山海关|SHP|@Shantou|汕头|SWA|@Shishi|石狮|JJN|@Simao|思茅|SYM|@tongren|铜仁|TEN|@wenshan|文山|WNH|@Xianggelila|香格里拉|DIG|@Xiangfan|襄樊|XFN|@Yili|伊犁|YIN|@yushu|玉树|YUS|@zhijiang|芷江|HJJ|@Bengbu|蚌埠|BFU|@Bozhou|亳州||@Chaohu|巢湖|589|@Chizhou|池州|218|@Chuzhou|滁州|214|@Huaibei|淮北|272|@Huainan|淮南|287|@Liu'an|六安||@Ma'anshan|马鞍山||@Suzhou|宿州|521|@Tongling|铜陵|459|@Wuhu|芜湖|WHU|@Xuancheng|宣城||@Nanping|南平|606|@Ningde|宁德|378|@Putian|莆田|667|@Sanming|三明|437|@Yong'an|永安|485|@Zhangzhou|漳州|560|@Baiyin|白银||@Dingxi|定西||@linxia|临夏||@Pingliang|平凉|388|@Wuwei|武威|664|@Yumen|玉门||@Chaoyang|潮阳|212|@Chaozhou|潮州|215|@Chenghai|澄海|629|@Dongguan|东莞|DGM|@Panyu|番禺|397|@Heyuan|河源|693|@Huizhou|惠州|HUZ|@jiexi|揭西||@Kaiping|开平|335|@Qingyuan|清远||@Sanshui|三水|445|@Shanwei|汕尾||@Shaoguan|韶关|422|@Shunde|顺德|210|@yunfu|云浮||@Zengcheng|增城||@Zhaoqing|肇庆|552|@Fangchenggang|防城港||@he’shan|合山||@hechi|河池||@Qinzhou|钦州||@Yulin|玉林||@bijie|毕节||@Kaili|凯里|333|@Liupanshui|六盘水|605|@Renhuai|仁怀||@Danzhou|儋州|057|@Qionghai|琼海|052|@Wanning|万宁|045|@Baoding|保定|185|@Beidaihe|北戴河|187|@Cangzhou|沧州|216|@Chengde|承德|562|@Hengshui|衡水|290|@Langfang|廊坊|340|@Nandaihe|南戴河||@Xingtai|邢台|XNT|@Zhangjiakou|张家口|550|@Zhuozhou|涿州||@Zunhua|遵化|545|@Anyang|安阳|AYN|@Hebi|鹤壁||@Jiaozuo|焦作||@Kaifeng|开封|331|@Luohe|漯河||@pingdingshan|平顶山||@Puyang|濮阳||@Sanmenxia|三门峡|436|@Shangqiu|商丘|441|@Xinxiang|新乡|507|@Xinyang|信阳|510|@Xuchang|许昌||@zhoukou|周口||@Zhumadian|驻马店|551|@Jiagedaqi|加格达奇||@Qitaihe|七台河|846|@Suihua|绥化||@Ezhou|鄂州||@Huanggang|黄冈||@Huangshi|黄石|XAA|@Jingmen|荆门||@Jingzhou|荆州|328|@Laohekou|老河口|342|@Shiyan|十堰|452|@Suizhou|随州||@Wuxue|武穴||@Xianning|咸宁||@xiangcheng|襄城||@Xiaogan|孝感||@Chenzhou|郴州|612|@Dayong|大庸|747|@Hengyang|衡阳|HNY|@Jishou|吉首||@jinshi|津市||@Leiyang|耒阳||@Loudi|娄底||@Shaoyang|邵阳||@Xiangtan|湘潭|598|@Yiyang|益阳||@Yueyang|岳阳|539|@Zhuzhou|株洲|zuz|@Baicheng|白城||@hunchun|珲春||@Liaoyuan|辽源|352|@Siping|四平|440|@Songyuan|松原||@binzhou|滨州||@Dezhou|德州||@Heze|菏泽||@Laiwu|莱芜||@Qufu|曲阜|143|@Rizhao|日照||@Taian|泰安|454|@Zaozhuang|枣庄|614|@Zibo|淄博|542|@dongtai|东台||@Haimen|海门||@Kunshan|昆山|083|@Qidong|启东|697|@Suzhou|苏州|SZV|@Taicang|太仓|654|@tongzhou|通州||@Tongli|同里||@Yizheng|仪征||@Yixing|宜兴|537|@Zhangjiagang|张家港|621|@Zhenjiang|镇江|016|@Zhouzhuang|周庄|561|@Jian|吉安|KNC|@Pingxiang|萍乡||@Ruichang|瑞昌||@Shangrao|上饶|411|@Xinyu|新余|603|@Yichun|宜春|518|@Yingtan|鹰潭|534|@Zhangshu|樟树||@Benxi|本溪||@Fushun|抚顺|252|@Fuxin|阜新|254|@Huludao|葫芦岛||@Liaoyang|辽阳|351|@Panjin|盘锦|387|@Tieling|铁岭||@Yingkou|营口||@alashanzuoqi|阿拉善左旗||@Genhe|根河||@Jining|集宁||@shizuishan|石嘴山||@wuzhong|吴忠||@Delingha|德令哈||@Gong|共和||@maqin|玛沁||@tongren|同仁||@Jincheng|晋城||@Linfen|临汾|139|@Shuozhou|朔州||@Xinzhou|忻州|513|@Yangquan|阳泉||@Baoji|宝鸡|112|@Huayin|华阴|119|@Shangluo|商洛||@Tongchuan|铜川|118|@Weinan|渭南||@Xingping|兴平||@Deyang|德阳|DEY|@Dujiangyan|都江堰|094|@Fuling|涪陵|249|@Guang'an|广安||@huaying|华蓥||@langzhong|阆中||@Leshan|乐山|345|@Maerkang|马尔康||@Neijiang|内江||@Suining|遂宁||@yaan|雅安||@Yongchuan|永川|516|@naqu|那曲||@Atushi|阿图什||@changji|昌吉||@fukang|阜康||@Fuyun|富蕴|FYN|@Kuitun|奎屯||@Shihezi|石河子|426|@tulufan|吐鲁番||@Anning|安宁||@chuxiong|楚雄||@gejiu|个旧||@Kaiyuan|开远||@luxi|潞西||@Qujing|曲靖||@Xuanwei|宣威||@Yuxi|玉溪|186|@Cixi|慈溪||@Fenghua|奉化|087|@Haining|海宁|084|@Haiyan|海盐|582|@Huzhou|湖州|086|@Jiaxing|嘉兴|571|@Jinhua|金华|308|@Lanxi|兰溪|597|@Lishui|丽水|346|@Linan|临安|090|@Linhai|临海||@Pinghu|平湖||@Ruian|瑞安|408|@Shaoxing|绍兴|022|@Shengzhou|嵊州||@Yuyao|余姚|540|@Zhuji|诸暨|548|@Ruili|瑞丽|412|@White Mountain|白山|WMO|@Suqian|宿迁||@Taizhou|泰州|579|@Beijing(NANYUAN)|北京(南苑)|BJS,NAY|@Beijing(CAPITAL)|北京(首都)|BJS,PEK|@Shanghai(PU DONG)|上海(浦东)|SHA,PVG|@Shanghai(HONGQIAO)|上海(虹桥)|SHA,SHA|@"
						},
						isFocusNext:false,
						charset: 'gb2312',
						isAutoCorrect:true,
						relate: {
							'code': $('#homecity', doc)		//=todo 数据源 ID,code对应的值
						}
					});
					// ta1.method('validate',false);
				}
				if($('#destcity1_name',doc).length > 0){
					var ta2 = $('#destcity1_name',doc).regMod('address','1.0',{
						name:'destcity1_name',
						//jsonpSource:'http://webresource.ctrip.com/code/cquery/resource/address/flight/flight_gb2312.js',
						source: {
						     //alias=['name_py','name','id','jianpin','name_search','pname','pname_pingying','pjianpin'];
						     suggestion:{
								'热门':[{display:"北京",data:"|北京|BJS"},{display:"上海",data:"|上海|SHA"},{display:"广州",data:"|广州|CAN"},{display:"深圳",data:"|深圳|SZX"},{display:"成都",data:"|成都|CTU"},{display:"杭州",data:"|杭州|HGH"},{display:"武汉",data:"|武汉|WUH"},{display:"西安",data:"|西安|SIA"},{display:"重庆",data:"|重庆|CKG"},{display:"青岛",data:"|青岛|TAO"},{display:"长沙",data:"|长沙|CSX"},{display:"南京",data:"|南京|NKG"},{display:"厦门",data:"|厦门|XMN"},{display:"昆明",data:"|昆明|KMG"},{display:"大连",data:"|大连|DLC"},{display:"天津",data:"|天津|TSN"},{display:"郑州",data:"|郑州|CGO"},{display:"三亚",data:"|三亚|SYX"},{display:"济南",data:"|济南|TNA"},{display:"福州",data:"|福州|FOC"}]
							},
							alias: ['name_py','name','code','alias'],
							//data: ''
							data:"@Aletai|阿勒泰|AAT|@Xingyi|兴义|ACX|@Baise|百色|AEB|@Ankang|安康|AKA|@Akesu|阿克苏|AKU|@Anshan|鞍山|AOG|@Anqing|安庆|AQG|@Anshun|安顺|AVA|@Baotou|包头|BAV|@Beihai|北海|BHY|@Bole|博乐|BPL|@Changdu|昌都|BPX|@Baoshan|保山|BSD|@Guangzhou|广州|CAN|@Changde|常德|CGD|@Zhengzhou|郑州|CGO|@Changchun|长春|CGQ|@Chaoyang|朝阳|CHG|@Chifeng|赤峰|CIF|@Changzhi|长治|CIH|@Chongqing|重庆|CKG|@Changsha|长沙|CSX|@Chengdu|成都|CTU|@Changzhou|常州|CZX|@Datong|大同|DAT|@Daxian|达县|DAX|@Dandong|丹东|DDG|@Diqing|迪庆|DIG|@Dalian|大连|DLC|@Dali|大理市|DLU|@Dunhuang|敦煌|DNH|@Dongying|东营|DOY|@Daqing|大庆|DQA|@E'erduosi|鄂尔多斯|DSN|@Zhangjiajie|张家界|DYG|@Enshi|恩施|ENH|@Yan'an|延安|ENY|@ELIANHAOTE|二连浩特|ERL|@Fuzhou|福州|FOC|@Fuyang|阜阳|FUG|@Foshan|佛山|FUO|@Guanghan|广汉|GHN|@Ge'ermu|格尔木|GOQ|@Guangyuan|广元|GYS|@Guyuan|固原|GYU|@Haikou|海口|HAK|@Handan|邯郸|HDG|@Heihe|黑河|HEK|@Huhehaote|呼和浩特|HET|@Hefei|合肥|HFE|@Hangzhou|杭州|HGH|@Huai'an|淮安|HIA|@Huaihua|怀化|HJJ|@Hong Kong|香港|HKG|XiangGang@Hailaer|海拉尔|HLD|@Wulanhaote|乌兰浩特|HLH|@Hami|哈密市|HMI|@Haerbin|哈尔滨|HRB|@Zhoushan|舟山|HSN|@Hetian|和田市|HTN|@HUALIEN|花莲|HUN|HuaLian@Taizhou|台州|HYN|@Hanzhong|汉中|HZG|@LIPING|黎平|HZH|@Yinchuan|银川|INC|@Qiemo|且末|IQM|@Qingyang|庆阳|IQN|@Jingdezhen|景德镇|JDZ|@Jiayuguan|嘉峪关|JGN|@Jinggangshan|井冈山|JGS|@Xishuangbanna|西双版纳|JHG|@Jinchang|金昌|JIC|@Jilin|吉林|JIL|@Qianjiang|黔江|JIQ|@Jiujiang|九江|JIU|@Jinjiang|晋江|JJN|@Jiamusi|佳木斯|JMU|@Jining|济宁|JNG|@Jinzhou|锦州|JNZ|@Quzhou|衢州|JUZ|@Jixi|鸡西|JXA|@Jiuzhaigou|九寨沟|JZH|@Kuche|库车|KCA|@Kangding|康定|KGT|@Kashi|喀什市|KHG|@Kaohsiung|高雄|KHH|GaoXiong@Nanchang|南昌|KHN|@kanasi|喀纳斯|KJI|@Kunming|昆明|KMG|@Ganzhou|赣州|KOW|@Kuerle|库尔勒|KRL|@Kelamayi|克拉玛依|KRY|@Guiyang|贵阳|KWE|@Guilin|桂林|KWL|@Longyan|龙岩|LCX|@Yichun|伊春|LDS|@Guanghua|光化|LHK|@Lanzhou|兰州|LHW|@Liangping|梁平|LIA|@Lijiang|丽江|LJG|@libo|荔波|LLB|@Yongzhou|永州|LLF|@Lincang|临沧|LNJ|@Dehong|德宏|LUM|@Lasa|拉萨|LXA|@Linxi|林西|LXI|@Luoyang|洛阳|LYA|@Lianyungang|连云港|LYG|@Linyi|临沂|LYI|@Liuzhou|柳州|LZH|@Luzhou|泸州|LZO|@Linzhi|林芝|LZY|@Mudanjiang|牡丹江|MDG|@Macau|澳门|MFM|AoMen@Mianyang|绵阳|MIG|@Meizhou|梅州|MXZ|@MAKUNG|马公|MZG|@Nanchong|南充|NAO|@Beijing|北京|BJS|@Changbaishan|长白山|NBS|@Qiqihaer|齐齐哈尔|NDG|@Ningbo|宁波|NGB|@Ali|阿里|NGQ|@Nanjing|南京|NKG|@Nalati|那拉提|NLT|@Nanning|南宁|NNG|@Nanyang|南阳|NNY|@Nantong|南通|NTG|@Manzhouli|满洲里|NZH|@Mohe|漠河|OHE|@Shanghai|上海|SHA|@Panzhihua|攀枝花|PZI|@Rikaze|日喀则|RKZ|@Bayannaoer|巴彦淖尔|RLK|@Taichung|台中|TXG|TaiZhong@Shenyang|沈阳|SHE|@Qinhuangdao|秦皇岛|SHP|@Shashi|沙市|SHS|@Shijiazhuang|石家庄|SJW|@Jieyang|揭阳|SWA|@Puer|普洱|SYM|@Sanya|三亚|SYX|@Shenzhen|深圳|SZX|@Qingdao|青岛|TAO|@Tacheng|塔城|TCG|@tengchong|腾冲|TCZ|@Tongren|铜仁市|TEN|@Tongliao|通辽|TGO|@Tianshui|天水|THQ|@Jinan|济南|TNA|@Taipei|台北|TPE|TaiBei@Tianjin|天津|TSN|@Taitung|台东|TTT|@Tangshan|唐山|TVS|@Huangshan|黄山|TXN|@Taiyuan|太原|TYN|@Wulumuqi|乌鲁木齐|URC|@Yulin|榆林|UYN|@Weifang|潍坊|WEF|@Weihai|威海|WEH|@Wenshan|文山县|WNH|@Wenzhou|温州|WNZ|@Wuhai|乌海|WUA|@Wuhan|武汉|WUH|@Wuyishan|武夷山|WUS|@Wuxi|无锡|WUX|@Wuzhou|梧州|WUZ|@WANZHOU|万州|WXN|@Xiangyang|襄阳|XFN|@Xichang|西昌|XIC|@Xilinhaote|锡林浩特|XIL|@Xian|西安|SIA|@Xiamen|厦门|XMN|@Xining|西宁|XNN|@Xuzhou|徐州|XUZ|@Yibin|宜宾|YBP|@Yuncheng|运城|YCU|@Aershan|阿尔山|YIE|@Yichang|宜昌|YIH|@Yining|伊宁市|YIN|@Yiwu|义乌|YIW|@Yanji|延吉|YNJ|@Yantai|烟台|YNT|@Yancheng|盐城|YNZ|@Yangzhou|扬州|YTY|@yushu|玉树县|YUS|@Zhangye|张掖|YZY|@Zhaotong|昭通|ZAT|@Zhongshan|中山|ZGN|@Zhanjiang|湛江|ZHA|@Zhongwei|中卫|ZHY|@Zhuhai|珠海|ZUH|@Dazhou|达州|DAX|@dali|大理|DLU|@hami|哈密|HMI|@hetian|和田|HTN|@Huangyan|黄岩|HYN|@Jinghong|景洪|JHG|@kashi|喀什|KHG|@Liancheng|连城|LCX|@Mangshi|芒市|LUM|@MEI XIAN|梅县|MXZ|@Putuoshan|普陀山|HSN|@Quanzhou|泉州|JJN|@Shanhaiguan|山海关|SHP|@Shantou|汕头|SWA|@Shishi|石狮|JJN|@Simao|思茅|SYM|@tongren|铜仁|TEN|@wenshan|文山|WNH|@Xianggelila|香格里拉|DIG|@Xiangfan|襄樊|XFN|@Yili|伊犁|YIN|@yushu|玉树|YUS|@zhijiang|芷江|HJJ|@Bengbu|蚌埠|BFU|@Bozhou|亳州||@Chaohu|巢湖|589|@Chizhou|池州|218|@Chuzhou|滁州|214|@Huaibei|淮北|272|@Huainan|淮南|287|@Liu'an|六安||@Ma'anshan|马鞍山||@Suzhou|宿州|521|@Tongling|铜陵|459|@Wuhu|芜湖|WHU|@Xuancheng|宣城||@Nanping|南平|606|@Ningde|宁德|378|@Putian|莆田|667|@Sanming|三明|437|@Yong'an|永安|485|@Zhangzhou|漳州|560|@Baiyin|白银||@Dingxi|定西||@linxia|临夏||@Pingliang|平凉|388|@Wuwei|武威|664|@Yumen|玉门||@Chaoyang|潮阳|212|@Chaozhou|潮州|215|@Chenghai|澄海|629|@Dongguan|东莞|DGM|@Panyu|番禺|397|@Heyuan|河源|693|@Huizhou|惠州|HUZ|@jiexi|揭西||@Kaiping|开平|335|@Qingyuan|清远||@Sanshui|三水|445|@Shanwei|汕尾||@Shaoguan|韶关|422|@Shunde|顺德|210|@yunfu|云浮||@Zengcheng|增城||@Zhaoqing|肇庆|552|@Fangchenggang|防城港||@he’shan|合山||@hechi|河池||@Qinzhou|钦州||@Yulin|玉林||@bijie|毕节||@Kaili|凯里|333|@Liupanshui|六盘水|605|@Renhuai|仁怀||@Danzhou|儋州|057|@Qionghai|琼海|052|@Wanning|万宁|045|@Baoding|保定|185|@Beidaihe|北戴河|187|@Cangzhou|沧州|216|@Chengde|承德|562|@Hengshui|衡水|290|@Langfang|廊坊|340|@Nandaihe|南戴河||@Xingtai|邢台|XNT|@Zhangjiakou|张家口|550|@Zhuozhou|涿州||@Zunhua|遵化|545|@Anyang|安阳|AYN|@Hebi|鹤壁||@Jiaozuo|焦作||@Kaifeng|开封|331|@Luohe|漯河||@pingdingshan|平顶山||@Puyang|濮阳||@Sanmenxia|三门峡|436|@Shangqiu|商丘|441|@Xinxiang|新乡|507|@Xinyang|信阳|510|@Xuchang|许昌||@zhoukou|周口||@Zhumadian|驻马店|551|@Jiagedaqi|加格达奇||@Qitaihe|七台河|846|@Suihua|绥化||@Ezhou|鄂州||@Huanggang|黄冈||@Huangshi|黄石|XAA|@Jingmen|荆门||@Jingzhou|荆州|328|@Laohekou|老河口|342|@Shiyan|十堰|452|@Suizhou|随州||@Wuxue|武穴||@Xianning|咸宁||@xiangcheng|襄城||@Xiaogan|孝感||@Chenzhou|郴州|612|@Dayong|大庸|747|@Hengyang|衡阳|HNY|@Jishou|吉首||@jinshi|津市||@Leiyang|耒阳||@Loudi|娄底||@Shaoyang|邵阳||@Xiangtan|湘潭|598|@Yiyang|益阳||@Yueyang|岳阳|539|@Zhuzhou|株洲|zuz|@Baicheng|白城||@hunchun|珲春||@Liaoyuan|辽源|352|@Siping|四平|440|@Songyuan|松原||@binzhou|滨州||@Dezhou|德州||@Heze|菏泽||@Laiwu|莱芜||@Qufu|曲阜|143|@Rizhao|日照||@Taian|泰安|454|@Zaozhuang|枣庄|614|@Zibo|淄博|542|@dongtai|东台||@Haimen|海门||@Kunshan|昆山|083|@Qidong|启东|697|@Suzhou|苏州|SZV|@Taicang|太仓|654|@tongzhou|通州||@Tongli|同里||@Yizheng|仪征||@Yixing|宜兴|537|@Zhangjiagang|张家港|621|@Zhenjiang|镇江|016|@Zhouzhuang|周庄|561|@Jian|吉安|KNC|@Pingxiang|萍乡||@Ruichang|瑞昌||@Shangrao|上饶|411|@Xinyu|新余|603|@Yichun|宜春|518|@Yingtan|鹰潭|534|@Zhangshu|樟树||@Benxi|本溪||@Fushun|抚顺|252|@Fuxin|阜新|254|@Huludao|葫芦岛||@Liaoyang|辽阳|351|@Panjin|盘锦|387|@Tieling|铁岭||@Yingkou|营口||@alashanzuoqi|阿拉善左旗||@Genhe|根河||@Jining|集宁||@shizuishan|石嘴山||@wuzhong|吴忠||@Delingha|德令哈||@Gong|共和||@maqin|玛沁||@tongren|同仁||@Jincheng|晋城||@Linfen|临汾|139|@Shuozhou|朔州||@Xinzhou|忻州|513|@Yangquan|阳泉||@Baoji|宝鸡|112|@Huayin|华阴|119|@Shangluo|商洛||@Tongchuan|铜川|118|@Weinan|渭南||@Xingping|兴平||@Deyang|德阳|DEY|@Dujiangyan|都江堰|094|@Fuling|涪陵|249|@Guang'an|广安||@huaying|华蓥||@langzhong|阆中||@Leshan|乐山|345|@Maerkang|马尔康||@Neijiang|内江||@Suining|遂宁||@yaan|雅安||@Yongchuan|永川|516|@naqu|那曲||@Atushi|阿图什||@changji|昌吉||@fukang|阜康||@Fuyun|富蕴|FYN|@Kuitun|奎屯||@Shihezi|石河子|426|@tulufan|吐鲁番||@Anning|安宁||@chuxiong|楚雄||@gejiu|个旧||@Kaiyuan|开远||@luxi|潞西||@Qujing|曲靖||@Xuanwei|宣威||@Yuxi|玉溪|186|@Cixi|慈溪||@Fenghua|奉化|087|@Haining|海宁|084|@Haiyan|海盐|582|@Huzhou|湖州|086|@Jiaxing|嘉兴|571|@Jinhua|金华|308|@Lanxi|兰溪|597|@Lishui|丽水|346|@Linan|临安|090|@Linhai|临海||@Pinghu|平湖||@Ruian|瑞安|408|@Shaoxing|绍兴|022|@Shengzhou|嵊州||@Yuyao|余姚|540|@Zhuji|诸暨|548|@Ruili|瑞丽|412|@White Mountain|白山|WMO|@Suqian|宿迁||@Taizhou|泰州|579|@Beijing(NANYUAN)|北京(南苑)|BJS,NAY|@Beijing(CAPITAL)|北京(首都)|BJS,PEK|@Shanghai(PU DONG)|上海(浦东)|SHA,PVG|@Shanghai(HONGQIAO)|上海(虹桥)|SHA,SHA|@"
						},
						isFocusNext:false,
						charset: 'gb2312',
						isAutoCorrect:true,
						relate: {
							'2': $('#destcity1', doc)	//=todo 数据源 ID,code对应的值
						}
					});
					// ta2.method('validate',false);
				}
				if($('#homecity_nameOut',doc).length > 0){
					var ita1 = $('#homecity_nameOut',doc).regMod('address','1.0',{
						name:'homecity_nameOut',
						jsonpSource:'http://webresource.ctrip.com/code/cquery/resource/address/flightintl/flightintl_start.js',
						isFocusNext:false,
						charset: 'gb2312',
						isAutoCorrect:true,
						relate: {
							'id': $('#homecityOut', doc)		//=todo 数据源 ID,code对应的值
						}
					});
					// ta1.method('validate',false);
				}
				if($('#destcity1_nameOut',doc).length > 0){
					var ita2 = $('#destcity1_nameOut',doc).regMod('address','1.0',{
						name:'destcity1_nameOut',
						jsonpSource:'http://webresource.ctrip.com/code/cquery/resource/address/flightintl/flightintl_dest.js',
						isFocusNext:false,
						charset: 'gb2312',
						isAutoCorrect:true,
						relate: {
							'3': $('#destcity1Out', doc)	//=todo 数据源 ID,code对应的值
						}
					});
					// ta2.method('validate',false);
				}
				
				//=holiday
				
				if($('#text1',doc).length >0){
					var ha1 = $('#text1',doc).regMod('address','1.0',{
						name:'text1',
						source: {
							suggestion:{
								'热门城市':[
									{display:"北京",data:"|北京|1"},{display:"上海",data:"|上海|2"},{display:"广州",data:"|广州|32"},{display:"深圳",data:"|深圳|30"},{display:"南京",data:"|南京|12"},{display:"杭州",data:"|杭州|17"},{display:"成都",data:"|成都|28"},{display:"厦门",data:"|厦门|25"},{display:"武汉",data:"|武汉|477"},{display:"青岛",data:"|青岛|7"},{display:"沈阳",data:"|沈阳|451"},{display:"天津",data:"|天津|3"}
								],
								'省会城市':[
									{display:"长春",data:"|长春|158"},{display:"长沙",data:"|长沙|206"},{display:"福州",data:"|福州|258"},{display:"贵阳",data:"|贵阳|38"},{display:"合肥",data:"|合肥|278"},{display:"海口",data:"|海口|42"},{display:"济南",data:"|济南|144"},{display:"昆明",data:"|昆明|34"},{display:"拉萨",data:"|拉萨|41"},{display:"兰州",data:"|兰州|100"},{display:"南昌",data:"|南昌|376"},{display:"南宁",data:"|南宁|380"},{display:"太原",data:"|太原|105"},{display:"西安",data:"|西安|10"},{display:"西宁",data:"|西宁|124"},{display:"银川",data:"|银川|99"},{display:"郑州",data:"|郑州|559"},{display:"哈尔滨",data:"|哈尔滨|5"},{display:"石家庄",data:"|石家庄|428"},{display:"呼和浩特",data:"|呼和浩特|103"},{display:"乌鲁木齐",data:"|乌鲁木齐|39"}
								],
								'其他城市':[
									{display:"包头",data:"|包头|141"},{display:"重庆",data:"|重庆|4"},{display:"常州",data:"|常州|213"},{display:"大连",data:"|大连|6"},{display:"东莞",data:"|东莞|undefined"},{display:"佛山",data:"|佛山|undefined"},{display:"江门",data:"|江门|undefined"},{display:"喀什",data:"|喀什|109"},{display:"绵阳",data:"|绵阳|370"},{display:"宁波",data:"|宁波|375"},{display:"泉州",data:"|泉州|406"},{display:"汕头",data:"|汕头|447"},{display:"苏州",data:"|苏州|undefined"},{display:"台州",data:"|台州|578"},{display:"威海",data:"|威海|479"},{display:"无锡",data:"|无锡|13"},{display:"温州",data:"|温州|491"},{display:"西昌",data:"|西昌|494"},{display:"徐州",data:"|徐州|512"},{display:"运城",data:"|运城|140"},{display:"延吉",data:"|延吉|523"},{display:"榆林",data:"|榆林|527"},{display:"烟台",data:"|烟台|533"},{display:"义乌",data:"|义乌|536"},{display:"中山",data:"|中山|undefined"},{display:"珠海",data:"|珠海|31"},{display:"海拉尔",data:"|海拉尔|142"}
								]
							},
							alias:['pinyin','cityName','cityId'],
							//data: ''
							data:"@Beijing|北京|1|@Shanghai|上海|2|@Guangzhou|广州|32|@Shenzhen|深圳|30|@Nanjing|南京|12|@Hangzhou|杭州|17|@Chengdu|成都|28|@Xiamen|厦门|25|@Wuhan|武汉|477|@Qingdao|青岛|7|@Shenyang|沈阳|451|@Tianjin|天津|3|@Changchun|长春|158|@Changsha|长沙|206|@Fuzhou|福州|258|@Guiyang|贵阳|38|@Hefei|合肥|278|@Haikou|海口|42|@Jinan|济南|144|@Kunming|昆明|34|@Lasa|拉萨|41|@Lanzhou|兰州|100|@Nanchang|南昌|376|@Nanning|南宁|380|@Taiyuan|太原|105|@Xi'an|西安|10|@Xining|西宁|124|@Yinchuan|银川|99|@Zhengzhou|郑州|559|@Haerbin|哈尔滨|5|@Shijiazhuang|石家庄|428|@Huhehaote|呼和浩特|103|@Wulumuqi|乌鲁木齐|39|@Baotou|包头|141|@Chongqing|重庆|4|@Changzhou|常州|213|@Dalian|大连|6|@Dongguan|东莞|undefined|@Foshan|佛山|undefined|@Jiangmen|江门|undefined|@Kashi|喀什|109|@Mianyang|绵阳|370|@Ningbo|宁波|375|@Quanzhou|泉州|406|@Shantou|汕头|447|@Suzhou|苏州|undefined|@Taizhou|台州|578|@Weihai|威海|479|@Wuxi|无锡|13|@Wenzhou|温州|491|@Xichang|西昌|494|@Xuzhou|徐州|512|@Yuncheng|运城|140|@Yanji|延吉|523|@Yulin|榆林|527|@Yantai|烟台|533|@Yiwu|义乌|536|@Zhongshan|中山|undefined|@Zhuhai|珠海|31|@Hailaer|海拉尔|142|@"
						},
						template:{
							suggestion:'\
								<div id="pkgStartCityDiv" class="departures">\
									<h4 class="departures_title">${message.suggestion}</h4>\
									{{enum(key, arr) data}}\
										<h5{{if key != "热门城市"}} class="dotline"{{/if}}>${key}</h5>\
										{{each arr}}\
										<a data="${data}" href="javascript:void(0);" \
										{{if display.length > 3}} class="widthfix" {{/if}}>${display}</a>\
										{{/each}}\
									{{/enum}}\
								</div>',
							suggestionStyle: 'h5 {margin:0;padding:0;}\
									a { color: #4d4d4d; text-decoration: none; }\
									a:hover {color:#0053aa;text-decoration: underline;}\
									.departures{width:273px;padding:6px 10px;border-color:#999;border-style:solid;border-width:0 1px 1px;box-shadow: 2px 3px 4px #999;background:white;font-size: 12px;line-height: 1.5;font-family: Simsun,sans-serif;color: #4d4d4d;}\
									.package_choice .departures {margin:-18px 0 0 -86px;}\
									.departures h4{height:24px;line-height:24px;font-size:12px;color:#ffffff;padding-left:10px;border-color:#2c7ecf;border-style:solid;border-width:1px 1px 0 1px;background-color:#67a1e2;margin:-6px -11px 0;}\
									.departures h5 { clear:both; font-size:12px; line-height:22px; color:#999999; font-weight: normal; }\
									.departures h5.dotline {clear:both;margin-top:4px;border-top:1px dashed #999;padding-top:4px;}\
									.departures a{display: -moz-inline-stack; display: inline-block; *display: inline; zoom: 1;\
									height:20px;width:36px;margin:0;padding:0 0 0 2px;border:solid 1px #fff;line-height: 20px;}\
									.departures a.widthfix {width:54px;}\
									.departures a:hover {background-color:#e8f4ff;text-decoration:none;border-color:#acccef;}\
									.destination{width:370px;padding:10px;background:#fff;font-family:Simsun;position:absolute;z-index:200;margin:184px 0 0 83px;*margin:184px 0 0 -635px;border-color:#999;border-style:solid;border-width:0 1px 1px;box-shadow: 2px 3px 4px #666;}\
									.tips{height:24px;line-height:24px;font-size:12px;color:#ffffff;padding-left:10px;background-color:#67A1E2;\
									border-color:#2c7ecf;border-style:solid;border-width:1px 1px 0 1px;margin:-10px -11px 0 -11px;}\
									.bound{width:100%;}\
									.destination dl{width:175px;float:left;	overflow:hidden;}\
									* html .destination dl{width:175px;}\
									.destination .brand{margin-left:16px;}\
									.destination dl dt{font-weight:normal;border-bottom:1px #aeaeae dashed;height:24px;line-height:24px;color:#535353;}\
									.destination dl dd{margin:2px;float:left;}\
									.destination a {display: -moz-inline-stack; display: inline-block; *display: inline; zoom: 1;white-space:nowrap; height: 18px;margin:0 5px 0 0;padding: 0 2px;border:1px solid #fff;}\
									.destination a:hover{background-color:#e8f4ff;border:1px solid #acccef; text-decoration: none;}'
						},
						isFocusNext:false,
						charset: 'gb2312',
						isAutoCorrect:true,
						relate: {
							'cityId': $('#text1ID', doc)
						}
					});
					// ha1.method('validate',false);
				}
				if($('#pkgdestCity',doc).length > 0){
					var ha2=$('#pkgdestCity',doc).regMod('address','1.0',{
						name:'pkgdestCity',
						//jsonpSource:'http://webresource.ui.sh.ctriptravel.com/code/cquery/resource/address/vacation/cch/cch_gb2312.js',
						jsonpSource:'http://webresource.ctrip.com/code/cquery/resource/address/vacation/cch/cch_gb2312.js',
						isFocusNext:false,
						charset: 'gb2312',
						isAutoCorrect:false,
						relate: {
							'ids': $('#pkgdestCityID', doc)
						}
					});
				}
				//=group
				if($("#groupCity",doc).length>0){
					$("#groupCity",doc).regMod('address','1.0',{
						name:'groupCity',
						source : {
						    suggestion:{
						        "热门":[{display:"北京",data:"beijing|北京|1"},{display:"上海",data:"shanghai|上海|2"},{display:"天津",data:"tianjin|天津|3"},{display:"重庆",data:"chongqing|重庆|4"},{display:"大连",data:"dalian|大连|6"},{display:"青岛",data:"qingdao|青岛|7"},{display:"西安",data:"xian|西安|10"},{display:"南京",data:"nanjing|南京|12"},{display:"苏州",data:"suzhou|苏州|14"},{display:"杭州",data:"hangzhou|杭州|17"},{display:"厦门",data:"xiamen|厦门|25"},{display:"成都",data:"chengdu|成都|28"},{display:"深圳",data:"shenzhen|深圳|30"},{display:"广州",data:"guangzhou|广州|32"},{display:"三亚",data:"sanya|三亚|43"},{display:"香港",data:"xianggang|香港|58"},{display:"济南",data:"jinan|济南|144"},{display:"宁波",data:"ningbo|宁波|375"},{display:"沈阳",data:"shenyang|沈阳|451"},{display:"武汉",data:"wuhan|武汉|477"}]
						    },
						    alias : ['pinyin','cityname','cityId'],
						    data : "@Beijing|北京|1|bj|@Xian|西安|10|xa|@Lanzhou|兰州|100|lz|@Jiangshan|江山|1000|js|@Fengcheng|丰城|1003|fc|@Ningguo|宁国|1005|ng|@Xuancheng|宣城|1006|xc|@Ningxiang|宁乡|1011|nx|@Lingbao|灵宝|1023|lb|@Ma'anshan|马鞍山|1024|mas|@Anning|安宁|10254||@Yangling|杨凌|10270|YL|@Luoning|洛宁|10271|LN|@Danba|丹巴|10272|DB|@Juxian|莒县|10273|JX|@Hongya|洪雅|10296|HY|@Changle|昌乐|10297|CL|@Maoxian|茂县|10298|MX|@Huhehaote|呼和浩特|103|hhht|@Weinan|渭南|1030|wn|@Zhongning|中宁|1035|zn|@Haiyang|海阳|1037|hy|@Laiyang|莱阳|1038|ly|@Pingyao|平遥|104|py|@Gaomi|高密|1040|gm|@Jiaozhou|胶州|1043|jz|@Qingzhou|青州|1044|qz|@Tieling|铁岭|1048|tl|@Taiyuan|太原|105|ty|@Huludao|葫芦岛|1050|hld|@Xingcheng|兴城|1051|xc|@Jiangyou|江油|1054|jy|@Wutaishan|五台山|106|wts|@Rruzhou|汝州|1060|rz|@Bazhou|霸州|1068|bz|@Renqiu|任丘|1069|rq|@Suning|肃宁|1070|sn|@Liaocheng|聊城|1071|lc|@Heze|菏泽|1074|hz|@Bozhou|亳州|1078|bz|@Macheng|麻城|1079|mc|@Linzhi|林芝|108|lz|@Manzhouli|满洲里|1083|mzl|@Luohe|漯河|1088|lh|@Leping|乐平|1089|lp|@Kashi|喀什|109|ks|@Dingzhou|定州|1090|dz|@Jincheng|晋城|1092|jc|@Jiaozuo|焦作|1093|jz|@Xuchang|许昌|1094|xc|@Houma|侯马|1095|hm|@Panzhihua|攀枝花|1097|pzh|@Dunhuang|敦煌|11|dh|@Yan'an|延安|110|ya|@Guang'an|广安|1100|ga|@Maoming|茂名|1105|mm|@Rizhao|日照|1106|rz|@Changxing|长兴|1107|cx|@Xianyang|咸阳|111|xy|@Jishou|吉首|1110|js|@Shaoyang|邵阳|1111|sy|@Yulin|玉林|1113|yl|@Baicheng|白城|1116|bc|@Suizhou|随州|1117|sz|@Baoji|宝鸡|112|bj|@Jingmen|荆门|1121|jm|@Yiyang|益阳|1125|yy|@Suihua|绥化|1128|sh|@Wuhai|乌海|1133|wh|@Xingyi|兴义|1139|xy|@Baise|百色|1140|bs|@Jiagedaqi|加格达奇|1143|jgdq|@Meishan|眉山|1148|ms|@Benxi|本溪|1155|bx|@Jinchang|金昌|1158|jc|@Tongchuan|铜川|118|tc|@Huayin|华阴|119|hy|@Nanjing|南京|12|nj|@Yancheng|盐城|1200|yc|@Ninghai|宁海|1201|nh|@Tongli|同里|1205|tl|@Pinghu|平湖|1206|ph|@Cixi|慈溪|1208|cx|@Linhai|临海|1209|lh|@Shengzhou|嵊州|1212|sz|@Wuxue|武穴|1219|wx|@Daocheng|稻城|1222|dc|@Jiangdu|江都|1223|jd|@Yizheng|仪征|1224|yz|@Nandaihe|南戴河|1226|ndh|@Tongren|铜仁|1227|tr|@Puyang|濮阳|1232|py|@Dazhou|达州|1233|dz|@Xining|西宁|124|xn|@Hancheng|韩城|128|hc|@Hanzhong|汉中|129|hz|@Wuxi|无锡|13|wx|@Yingkou|营口|1300|yk|@Songyuan|松原|1303|sy|@Yongji|永济|1315|yj|@Shuozhou|朔州|1317|sz|@Ge'ermu|格尔木|132|gem|@Dongmingxian|东明县|1322|dmx|@Qiandaohu|千岛湖|1332|qdh|@Honghezhou|红河州|1341|hhz|@Wenshan|文山|1342|ws|@Jiexiu|介休|135|jx|@Liyang|溧阳|1358|ly|@Datong|大同|136|dt|@Deqing|德清|1367|dq|@Changzhi|长治|137|cz|@Dezhou|德州|1370|dz|@Suining|遂宁|1371|sn|@Songpan|松潘|1372|sp|@Liancheng|连城|1373|lc|@Linfen|临汾|139|lf|@Suzhou|苏州|14|sz|@Yuncheng|运城|140|yc|@Baotou|包头|141|bt|@Conghua|从化|1421|ch|@Qingyuan|清远|1422|qy|@Enping|恩平|1428|ep|@Qufu|曲阜|143|qf|@Shanwei|汕尾|1436|sw|@Jinan|济南|144|jn|@Taishun|泰顺|1443|ts|@Laiwu|莱芜|1452|lw|@Jinzhong|晋中|1453|jz|@Jiyuan|济源|1454|jy|@Antu|安图|1466|at|@Qinhuangdao|秦皇岛|147|qhd|@Suqian|宿迁|1472|sq|@Qiqihaer|齐齐哈尔|149|qqhe|@Xiaogan|孝感|1490|xg|@Yangzhou|扬州|15|yz|@Mudanjiang|牡丹江|150|mdj|@Gaobeidian|高碑店|1501|gbd|@Guigang|贵港|1518|gg|@Chibi|赤壁|1521|cb|@Laiyuan|涞源|1522|ly|@Baiyin|白银|1541|by|@Mohe|漠河|155|mh|@Zhijiang|枝江|1557|zj|@Ziyang|资阳|1560|zy|@Suizhong|绥中|1564|sz|@Jixi|鸡西|157|jx|@Changchun|长春|158|cc|@Jilin|吉林|159|jl|@Neijiang|内江|1597|nj|@Zhenjiang|镇江|16|zj|@Mishan|密山|1609|ms|@Hegang|鹤岗|1611|hg|@Shuangyashan|双鸭山|1617|sys|@Aershan|阿尔山|1658|aes|@Kelamayi|克拉玛依|166|klmy|@Yabuli|亚布力|1664|ybl|@Hailin|海林|1666|hl|@Fangchenggang|防城港|1677|fcg|@Caoxian|曹县|1696|cx|@Hangzhou|杭州|17|hz|@Ruichang|瑞昌|1700|rc|@Liu'an|六安|1705|la|@hailuogou|海螺沟|1706|hlg|@libo|荔波|1708|lb|@Ankang|安康|171|ak|@Akesu|阿克苏|173|aks|@Aletai|阿勒泰|175|alt|@Anqing|安庆|177|aq|@Anshan|鞍山|178|as|@Anshun|安顺|179|as|@Jinjiang|晋江|1803|jj|@laixi|莱西|1804|lx|@Anyang|安阳|181|ay|@tengchong|腾冲|1819|tc|@Bengbu|蚌埠|182|bb|@binzhou|滨州|1820|bz|@xingan|兴安|1822|xa|@Xiangshan|象山|1823|xs|@Jintan|金坛|1839|jt|@Pingxiang|萍乡|1840|px|@Baoding|保定|185|bd|@Yuxi|玉溪|186|yx|@Beidaihe|北戴河|187|bdh|@Xiantao|仙桃|1882|xt|@feicheng|肥城|1884|fc|@Beihai|北海|189|bh|@Laibin|来宾|1892|lb|@Qinzhou|钦州|1899|qz|@Zhoushan|舟山|19|zs|@Mizhixian|米脂县|1937|mzx|@Dingxing|定兴|1980|dx|@Xushui|徐水|1983|xs|@Pingyuanxian|平原县|19953|PYX|@Qingyunxian|庆云县|19954|QYX|@Qingyuan|庆元|19955|QY|@Gaotangxian|高唐县|19956|GTX|@Shanghai|上海|2|sh|@Changde|常德|201|cd|@Chifeng|赤峰|202|cf|@Wuan|武安|2033|wa|@Wenzhuangcun|文庄村|2040|wzc|@Changsha|长沙|206|cs|@Chaoyang|朝阳|211|zy|@Lushan|鲁山|2122|ls|@Changzhou|常州|213|cz|@Chuzhou|滁州|214|cz|@Chaozhou|潮州|215|cz|@Cangzhou|沧州|216|cz|@Chizhou|池州|218|cz|@Shaoxing|绍兴|22|sx|@Dandong|丹东|221|dd|@Dengfeng|登封|222|df|@Dongguan|东莞|223|dg|@Qianan|迁安|2230|qa|@Huangshan|黄山|23|hs|@Daqing|大庆|231|dq|@Shaodong|邵东|2339|sd|@Dongying|东营|236|dy|@Deyang|德阳|237|dy|@Danyang|丹阳|238|dy|@Jiujiang|九江|24|jj|@Meng|蒙自|2431|mz|@Jianshui|建水|2442|js|@Enshi|恩施|245|es|@Fuding|福鼎|246|fd|@Xiamen|厦门|25|xm|@Foshan|佛山|251|fs|@Fushun|抚顺|252|fs|@Fuxin|阜新|254|fx|@Delingha|德令哈|2542|dlh|@Luntai|轮台|2549|lt|@Daying|大英|2552|dy|@Fuyang|富阳|256|fy|@Fuyang|阜阳|257|fy|@Fuzhou|福州|258|fz|@Wuyishan|武夷山|26|wys|@Mian|绵竹|2625|mz|@Guangyuan|广元|267|gy|@Ganzhou|赣州|268|gz|@Zhangjiajie|张家界|27|zjj|@Huaibei|淮北|272|hb|@Handan|邯郸|275|hd|@Hefei|合肥|278|hf|@Chengdu|成都|28|cd|@Heihe|黑河|281|hh|@Huaihua|怀化|282|hh|@Hami|哈密|285|hm|@Huainan|淮南|287|hn|@Huashan|华山|288|hs|@Wenxi|闻喜|2886|wx|@Hengshui|衡水|290|hs|@Huangshi|黄石|292|hs|@Hetian|和田|294|ht|@Shangzhi|尚志|2966|sz|@Hengyang|衡阳|297|hy|@Huizhou|惠州|299|hz|@Tianjin|天津|3|tj|@Shenzhen|深圳|30|sz|@Jingdezhen|景德镇|305|jdz|@Meizhou|梅州|3053|mz|@Longquan|龙泉|3055|lq|@Jinggangshan|井冈山|307|jgs|@Jinhua|金华|308|jh|@Zhuhai|珠海|31|zh|@Penglai|蓬莱|310|pl|@Jiangmen|江门|316|jm|@Jiamusi|佳木斯|317|jms|@Jining|济宁|318|jn|@Guangzhou|广州|32|gz|@Guyuan|固原|321|gy|@zhoukou|周口|3221|zk|@pingdingshan|平顶山|3222|pds|@bijie|毕节|3225|bj|@Jurong|句容|3230|jr|@dongtai|东台|3233|dt|@Jiangyin|江阴|325|jy|@Jiayuguan|嘉峪关|326|jyg|@Jinzhou|锦州|327|jz|@langzhong|阆中|3275|lz|@huashuiwan|花水湾|3276|hsw|@yaan|雅安|3277|ya|@Jingzhou|荆州|328|jz|@Kuche|库车|329|kc|@Guilin|桂林|33|gl|@Kuerle|库尔勒|330|kel|@hengdian|横店|3309|hd|@Kaifeng|开封|331|kf|@kanasi|喀纳斯|3326|kns|@Kaili|凯里|333|kl|@Kaiping|开平|335|kp|@Kunming|昆明|34|km|@Langfang|廊坊|340|lf|@Longhai|龙海|341|lh|@Lushan|庐山|344|ls|@Leshan|乐山|345|ls|@Lishui|丽水|346|ls|@Longyan|龙岩|348|ly|@Xishuangbanna|西双版纳|35|xsbn|@Luoyang|洛阳|350|ly|@Liaoyang|辽阳|351|ly|@Liaoyuan|辽源|352|ly|@Lianyungang|连云港|353|lyg|@Liuzhou|柳州|354|lz|@Luzhou|泸州|355|lz|@Dali|大理|36|dl|@Dehong|德宏|365|dh|@Lijiang|丽江|37|lj|@Mianyang|绵阳|370|my|@Nan'an|南安|374|na|@Ningbo|宁波|375|nb|@Nanchang|南昌|376|nc|@Nanchong|南充|377|nc|@Ningde|宁德|378|nd|@Guiyang|贵阳|38|gy|@Nanning|南宁|380|nn|@Hsinchu|新竹|3845|xz|@Tainan|台南|3847|tn|@Taitung|台东|3848|td|@Taichung|台中|3849|tz|@Nanyang|南阳|385|ny|@shouguang|寿光|3863|sg|@Panjin|盘锦|387|pj|@Pingliang|平凉|388|pl|@siyang|泗阳|3881|sy|@Fuzhou|抚州|3884|fz|@huang gang|黄冈|3885|hg|@baishan|白山|3886|bs|@Bayannaoer|巴彦淖尔|3887|byne|@Puning|普宁|389|pn|@Wulumuqi|乌鲁木齐|39|wlmq|@jimo|即墨|3906|jm|@wendeng|文登|3908|wd|@jiaonan|胶南|3909|jn|@yiyuan|沂源|3913|yy|@daye|大冶|3914|dy|@Laizhou|莱州|3915|lz|@Fuqing|福清|3917|fq|@Tianmen|天门|3920|tm|@Chuxiong|楚雄|3921|cx|@Hai'an|海安|3923|ha|@yuhuan|玉环|3925|yh|@jingjiang|靖江|3926|jj|@dexing|德兴|3927|dx|@deqin|德钦|3928|dq|@pizhou|邳州|3929|pz|@yunfu|云浮|3933|yf|@yingcheng|应城|3935|yc|@yangzhong|扬中|3937|yz|@zhongxiang|钟祥|3938|zx|@pingdu|平度|3943|pd|@longkou|龙口|3946|lk|@Pingxiang|凭祥|396|px|@bazhong|巴中|3966|bz|@dongxing|东兴|3967|dx|@guiping|桂平|3968|gp|@hechi|河池|3969|hc|@gaoan|高安|3970|ga|@E'erduosi|鄂尔多斯|3976|eeds|@Taixing|泰兴|3980|tx|@jiyang|济阳|3989|jy|@Puer|普洱|3996|pe|@Chongqing|重庆|4|cq|@Tulufan|吐鲁番|40|tlf|@xiajin|夏津|4013|xj|@YongCheng|永城|4020|yc|@jiangyan|姜堰|4026|jy|@dafeng|大丰|4029|df|@Qingyang|庆阳|404|qy|@Quanzhou|泉州|406|qz|@Quzhou|衢州|407|qz|@Ruian|瑞安|408|ra|@Lasa|拉萨|41|ls|@Shangrao|上饶|411|sr|@Ruili|瑞丽|412|rl|@gaoyou|高邮|4125|gy|@Kangding|康定|4130|kd|@yangchengxian|阳城县|4131|ycx|@xinmi|新密|4136|xm|@hunchun|珲春|4137|hc|@rugao|如皋|4139|rg|@BOXING|博兴|4141|bx|@ZHUCHENG|诸城|4144|zc|@hezhou|贺州|4146|hz|@qianjiang|潜江|4154|qj|@boao|博鳌|4159|ba|@Liuyang|浏阳|4185|ly|@Haikou|海口|42|hk|@Suifenhe|绥芬河|421|sfh|@shizuishan|石嘴山|4216|szs|@Shaoguan|韶关|422|sg|@zhaoyuan|招远|4251|zy|@Hulunbeier|呼伦贝尔|4255|hlbe|@Shijiazhuang|石家庄|428|sjz|@Sanya|三亚|43|sy|@Sanmenxia|三门峡|436|smx|@Sanming|三明|437|sm|@Shannan|山南|439|sn|@Wenchang|文昌|44|wc|@Siping|四平|440|sp|@Shangqiu|商丘|441|sq|@Sihui|泗水|443|ss|@Shishi|石狮|444|ss|@Shaoshan|韶山|446|ss|@Shantou|汕头|447|st|@Shaowu|邵武|448|sw|@Wanning|万宁|45|wn|@Shenyang|沈阳|451|sy|@Shiyan|十堰|452|sy|@Tai'an|泰安|454|ta|@Tonghua|通化|456|th|@Tongliao|通辽|458|tl|@Tongling|铜陵|459|tl|@Wuzhishan|五指山|46|wzs|@Tonglu|桐庐|460|tl|@Tianshui|天水|464|ts|@Tangshan|唐山|468|ts|@Tiantai|天台|470|tt|@Wudangshan|武当山|474|wds|@Weifang|潍坊|475|wf|@Wuhan|武汉|477|wh|@Wuhu|芜湖|478|wh|@Weihai|威海|479|wh|@Dongfang|东方|48|df|@Wujiang|吴江|481|wj|@Yong'an|永安|485|ya|@Wuyuan|婺源|489|wy|@Wenzhou|温州|491|wz|@Wuzhou|梧州|492|wz|@Xichang|西昌|494|xc|@Xiangyang|襄阳|496|xy|@Xiahe|夏河|497|xh|@Haerbin|哈尔滨|5|heb|@Ding'an|定安|50|da|@Xilinhaote|锡林浩特|500|xlht|@Xinxiang|新乡|507|xx|@Xinyang|信阳|510|xy|@Xuzhou|徐州|512|xz|@Yibin|宜宾|514|yb|@Yichang|宜昌|515|yc|@CHIAYI|嘉义|5152|jy|@Yichun|伊春|517|yc|@Yichun|宜春|518|yc|@Qionghai|琼海|52|qh|@Suzhou|宿州|521|sz|@Yanji|延吉|523|yj|@Yulin|榆林|527|yl|@Yining|伊宁|529|yn|@Yantai|烟台|533|yt|@Yingtan|鹰潭|534|yt|@Yiwu|义乌|536|yw|@Yixing|宜兴|537|yx|@Yueyang|岳阳|539|yy|@Baoting|保亭|54|bt|@Yuyao|余姚|540|yy|@Yanzhou|兖州|541|yz|@Zibo|淄博|542|zb|@Zigong|自贡|544|zg|@Zunhua|遵化|545|zh|@Zhanjiang|湛江|547|zj|@Zhuji|诸暨|548|zj|@Lingshui|陵水|55|ls|@Zhangjiakou|张家口|550|zjk|@Zhumadian|驻马店|551|zmd|@Zhaoqing|肇庆|552|zq|@Zhongshan|中山|553|zs|@Zhaotong|昭通|555|zt|@Zhongwei|中卫|556|zw|@Zunyi|遵义|558|zy|@PINGTUNG|屏东|5589|pd|@Zhengzhou|郑州|559|zz|@Zhangzhou|漳州|560|zz|@Zhouzhuang|周庄|561|zz|@Chengde|承德|562|cd|@Linyi|临沂|569|ly|@Danzhou|儋州|57|dz|@Jiaxing|嘉兴|571|jx|@Changdu|昌都|575|cd|@Huai'an|淮安|577|ha|@Taizhou|台州|578|tz|@Taizhou|泰州|579|tz|@Hong Kong|香港|58|xg|HongKong|XiangGang@Tongxiang|桐乡|580|tx|@Haiyan|海盐|582|hy|@Jiuhuashan|九华山|583|jhs|@Chaohu|巢湖|589|ch|@Macau|澳门|59|am|aoMen@Shangyu|上虞|595|sy|@Jiashan|嘉善|596|js|@Lanxi|兰溪|597|lx|@Xiangtan|湘潭|598|xt|@Dalian|大连|6|dl|@Zhuzhou|株洲|601|zz|@Xinyu|新余|603|xy|@Liupanshui|六盘水|605|lps|@Chenzhou|郴州|612|cz|@Zaozhuang|枣庄|614|zz|@Taipei|台北|617|tb|taibei@Wenling|温岭|619|wl|@Yandangshan|雁荡山|620|yds|@Zhangjiagang|张家港|621|zjg|@Jinyun|缙云|652|jy|@Taicang|太仓|654|tc|@Shennongjia|神农架|657|snj|@Jiande|建德|658|jd|@Anji|安吉|659|aj|@Xianggelila|香格里拉|660|xgll|@Jiuquan|酒泉|662|jq|@Zhangye|张掖|663|zy|@Wuwei|武威|664|ww|@Putian|莆田|667|pt|@Yangjiang|阳江|692|yj|@Heyuan|河源|693|hy|@HUALIEN|花莲|6954|hl|@Xuyi|盱眙|696|xy|@Qidong|启东|697|qd|@Qingdao|青岛|7|qd|@Kaohsiung|高雄|720|gx|@KINMEN|金门|7203|jm|@taishan|台山|729|ts|@Yueqing|乐清|732|lq|@Guanghan|广汉|750|gh|@wulanchabu|乌兰察布|7518|wlcb|@zoucheng|邹城|7519|zc|@Longsheng|龙胜|7521|ls|@yunlin|云林|7523|yl|@nantou|南投|7524|nt|@Beichuanxian|北川县|7525|bcx|@Tanghai|唐海|7530|th|@Daxinxian|大新县|7531|dxx|@pingyang|平阳|7533|py|@changji|昌吉|7534|cj|@pingyi|平邑|7536|py|@Maerkang|马尔康|7540|mek|@ziyuan|资源|7541|zy|@chishui|赤水|7544|cs|@Linzhou|林州|7545|lz|@Alashan|阿拉善|7548|als|@Luding|泸定|7549|ld|@Dongyang|东阳|755|dy|@Shangluo|商洛|7551|sl|@Pingdingxian|平定县|7552|pdx|@qionglai|邛崃|7553|ql|@rushan|乳山|7554|rs|@Rudong|如东|7557|rd|@sanmen|三门|7558|sm|@Haimen|海门|7559|hm|@Fopingxian|佛坪县|7568|fpx|@Taoyuan(TW)|桃园|7570|tyx|@Panan|磐安|7571|pa|@shehong|射洪|7575|sh|@Tianchang|天长|7577|tc|@Xinghua|兴化|7578|xh|@Donggang|东港|7579|dg|@kaihua|开化|7586|kh|@wuzhong|吴忠|7587|wz|@Changshan|常山|7590|cs|@Zhangqiu|章丘|7593|zq|@Yuanyang|元阳|7594|yy|@tianquan|天全|7599|tq|@Zhuozhou|涿州|7605|zz|@Lipu|荔浦|7607|lp|@Yilan|宜兰|7614|yl|@Honghu|洪湖|7618|hh|@Renhuai|仁怀|7619|rh|@xilingxueshan|西岭雪山|7622|xlxs|@Guangrao|广饶|7625|gr|@Botou|泊头|7629|bt|@yishui|沂水|7630|ys|@Lvliang|吕梁|7631|ll|@Luanchuan|栾川|7637|lc|@Eerguna|额尔古纳|7638|eegn|@Huanghua|黄骅|7644|hh|@Changge|长葛|7650|cg|@Ningcheng|宁城|7651|nc|@Xinbeishi|新北市|7662|tbx|@Suichang|遂昌|7665|sc|@Cangnan|苍南|7666|cn|@Luotianxian|罗田县|7667|ltx|@Qixia|栖霞|7669|qx|@Longhushan|龙虎山|7670|lhs|@Fengcheng|凤城|7671|fc|@Yunchengxian|郓城县|7673|ycx|@Changdao|长岛|7674|cd|@yinanxian|沂南县|7675|ynx|@Jingning|景宁|7679|jn|@Xuexiang|雪乡|7681|xx|@Luxi|泸西|7682|lx|@Danjiangkou|丹江口|7685|djk|@Gaocheng|藁城|7687|gc|@Beizhen|北镇|7698|bz|@Wulianxian|五莲县|7700|wlx|@Wuyang|舞阳|7703|wy|@Longnan|陇南|7707|ln|@Guangshan|光山|7710|gs|@Pingluoxian|平罗县|7712|plx|@Huangzhongxian|湟中县|7713|hzx|@JuNanXian|莒南县|7714|jnx|@Dayixian|大邑县|7716|dyx|@Wugongshan|武功山|7724|wgs|@Helan|贺兰|7727|hl|@Dongping|东平县|7728|dpx|@Yanshan|盐山|7733|ys|@Anqiu|安丘|7736|aq|@Jianyang|简阳|7744|jy|@Jinxiang|金乡|7745|jx|@Yijinhuoluoqi|伊金霍洛旗|7748|yjhlq|@Dalateqi|达拉特旗|7749|dltq|@Zouping|邹平|7758|zp|@Hejian|河间|7759|hj|@Yuzhou|禹州|7766|yz|@Xintai|新泰|7771|xt|@Lichuan|利川|7779|lc|@Jimunai|吉木乃|7782|jmn|@Yunhe|云和|7789|yh|@Etuokeqi|鄂托克旗|7793|etkq|@Baigou|白沟|7799|bg|@Huangnanzangzuzizhizhou|黄南藏族自治州|7802|hnzzzzz|@HongJiangShi|洪江市|7803|hjs|@Penghu|澎湖|7805|ph|@Ningyangxian|宁阳县|7806|nyx|@Haibei|海北|7807|hb|@Mazu|马祖|7808|mz|@Miaoli|苗栗|7809|ml|@Jilong|基隆|7810|jl|@Zhanghua|彰化|7811|zh|@Fakuxian|法库县|7823|fkx|@Yanshouxian|延寿县|7828|ysx|@Chengxian|成县|7829|cx|@Hongyuanxian|红原县|7835|hyx|@Wencheng|文成|7836|wc|@Qihexian|齐河县|7839|qhx|@Dongtou|洞头|7841||@Chipingxian|茌平县|7842||@Eryuan|洱源|7843||@Nantong|南通|82|nt|@Kunshan|昆山|83|ks|@Rongcheng|荣成|833|rc|@Haining|海宁|84|hn|@Yongjia|永嘉|85|yj|@Huzhou|湖州|86|hz|@Fenghuang|凤凰|866|fh|@Xianju|仙居|868|xj|@Fenghua|奉化|87|fh|@Sanqingshan|三清山|870|sqs|@Yangshuo|阳朔|871|ys|@Xinchang|新昌|872|xc|@Xinyi|新沂|895|xy|@Linan|临安|90|la|@Yangquan|阳泉|907|yq|@Tengzhou|滕州|909|tz|@Jiuzhaigou|九寨沟|91|jzg|@Xiangxiang|湘乡|917|xx|@Loudi|娄底|918|ld|@Rikaze|日喀则|92|rkz|@Lengshuijiang|冷水江|920|lsj|@Pujiang|浦江|929|pj|@Jian|吉安|933|ja|@Xianning|咸宁|937|xn|@Dujiangyan|都江堰|94|djy|@Leiyang|耒阳|940|ly|@Yongxiu|永修|943|YX|@Tongcheng|桐城|944|tc|@Tianzhushan|天柱山|945|tzs|@Xingtai|邢台|947|xt|@Emeishan|峨眉山|95|ems|@Hebi|鹤壁|951|hb|@Jieyang|揭阳|956|jy|@Yanshi|偃师|957|ys|@Gongyi|巩义|958|gy|@Wuyi|武义|959|wy|@Changshu|常熟|96|cs|@Yongkang|永康|960|yk|@Qingtian|青田|961|qt|@Haicheng|海城|963|hc|@Wafang|瓦房店|966|wfd|@Dashiqiao|大石桥|967|dsq|@Xingyang|荥阳|969|yy|@Ali|阿里|97|al|@Yongzhou|永州|970|yz|@Longyou|龙游|973|ly|@Duyun|都匀|975|dy|@Liling|醴陵|981|ll|@Qujing|曲靖|985|qj|@Zhenyuan|镇远|986|zy|@Yinchuan|银川|99|yc|@Ezhou|鄂州|992|ez|@Luguhu|泸沽湖景区(丽江)|D105_37||@tianmushan|天目山景区(临安)|D1435_90||@tianmuhu|天目湖景区(溧阳)|D1437_1358||@Xitang|西塘景区(嘉善)|D15_596||@Putuoshan|普陀山景区(舟山)|D16_19||@fuxianhu|抚仙湖景区(玉溪)|D2080_186||@Changbaishan|长白山景区(安图)|D268_1466||@Changbaishan|长白山景区(白山)|D268_3886||@wuzhen|乌镇景区(桐乡)|D508_580||@Nanxun|南浔景区(湖州)|D80_86||@Moganshan|莫干山景区(德清)|D87_1367||@"
						},
						isFocusNext:false,
						charset: 'gb2312',
						isAutoCorrect:true,
						relate: {
							'cityId': $('#grouphidCity', doc),
							'pinyin': $('#grouphidCityPinYin', doc)
						}
					})
				}
		},
		/**
		 * doc = ifr.contentDocument || ifr.contentWindow.document;
		 */
		calendarInit: function(doc,ifr){
			   var ifm = document.createElement('iframe');
			   ifm.src="about:blank";
			   // ifm.id="calendarIfm";
			   ifm.style.display="none";
			   ifm.style.height="215px";
			   ifm.style.width= document.all ? "370px" : "371px";
			   ifm.style.position="absolute";
			   //ifm.frameBorder="0";
			   ifm.setAttribute('frameBorder', '0');
			   ifm.style.zIndex=9999;
			   ifm.border="0";
			   ifm.marginWidth="0";
			   ifm.marginHeight="0";
			   ifm.scrolling="no";
			   document.body.appendChild(ifm);
		   ifm.contentWindow.document.open();
		   ifm.contentWindow.document.write('<div style="position:absolute;display:none;width:369px;*width:370px" id="calendars" class="calendar_wrap" ><div class="calendar_month calendar" hidefocus="true" id="calendar0" >{$left}</div><div class="calendar_month calendar" hidefocus="true" id="calendar1" style="padding-left: 0;">{$right}</div></div>');

			ifm.contentWindow.document.close();
			var calendarWin = ifm.contentWindow || ifm.window;
			var inputWin = ifr.contentWindow || ifr.window;
			if($('#starttime',doc).length > 0){
				$('#starttime',doc).regMod('calendar','1.0',{
					options:{
						showWeek:false,
						inputWin:inputWin,
						calendarWin:calendarWin,
						calendarIframe:ifm,
						inputIframe:ifr
					},
					listeners:{
						onChange:function(input,value){
							if(input.id=="starttime"){
								this.inputWin.document.getElementById('deptime').setAttribute('startDate',value);
								this.inputWin.document.getElementById('deptime').focus();
							}
						}
					}
				});
			}
			if($('#deptime',doc).length > 0){
				$('#deptime',doc).regMod('calendar','1.0',{
					options:{
						showWeek:false,
						inputWin:inputWin,
						calendarWin:calendarWin,
						calendarIframe:ifm,
						inputIframe:ifr
					}
				});
			}
			//=ticket
			if($('#DDatePeriod1',doc).length > 0){
				$('#DDatePeriod1',doc).regMod('calendar','1.0',{
					options:{
						showWeek:false,
						inputWin:inputWin,
						calendarWin:calendarWin,
						calendarIframe:ifm,
						inputIframe:ifr
					},
					listeners:{
						onChange:function(input,value){
							if(input.id=="DDatePeriod1"){
								inputWin.document.getElementById('ADatePeriod1').setAttribute('startDate',input.value);
								this.inputWin.document.getElementById('ADatePeriod1').focus();
							}
						}
					}
				});
			}
			if($('#ADatePeriod1',doc).length > 0){
				$('#ADatePeriod1',doc).regMod('calendar','1.0',{
					options:{
						showWeek:false,
						inputWin:inputWin,
						calendarWin:calendarWin,
						calendarIframe:ifm,
						inputIframe:ifr
					}
				});
			}
			
		}
		
	}
	
	var hash = {
		holiday: {},
		hotel: {},
		ticket: {},
		group:{}
		}
	var content = {
		itemHeight: '107',
	    itemWidth : '230',
	    //pageHeight: '28',
	    footHeight: '28',
	    contentHeight: '',
		curRow:2,
		curCol:2,
		targ: null,
		sortTarg:null,
		targList:null,
		sortSel:null,
		pagediv:null,
		minContentHeight:189,
		//listSort:null,
		current: 1,
		count: 4,		//=分页显示的数据数目
		ctripUnion: null,
		loading: null,
		objCount:null,
		sortArr : [{'type':'minPrice|desc','text':'价格由高到低'},
		{'type':'minPrice|asc','text':'价格由低到高'},
		{'type':'level|desc','text':'星级由高到低'},
		{'type':'level|asc','text':'星级由低到高'},
		{'type':'mark|desc','text':'点评分数由高到低'}],
	    sorttmp:'<div class="c_sort_select"><a href="javascript:void(0);"><b></b><span>默认排序：携程推荐</span></a>\
		<div class="c_sort_list" style="width:142px;display:;" id="sort_select">{{each opts}}<a href="javascript:void(0);" name="${type}">${text}</a>{{/each}}</div></div>',
		doc: null,
		url: '',
		_url:null,
		callback: null,
		dataType: 'hotel',
		
		init: function(doc){
			var ctripUnion = this.ctripUnion;
			var config = ctripUnion.datas.config;
			//=初始化
			this.doc = doc;	
			this.targ = doc.getElementById('b2b_content');
			//this.loading = '<div class="c_loading" id="b2b_loading"><img src="http://pic.ctrip.com/common/loading.gif" alt="">查询中，请稍后...</div>';
			
			this.loading = $('#b2b_loading', content.doc);
			
			this.targList = doc.getElementById('b2b_show_list');
			this.sortTarg=doc.getElementById('listSort');
			this.sortSel=doc.getElementById('selSort');
			this.sortOption=doc.getElementById('sort_select');
			//this.searchBoxHeight=doc.getElementById('show_search_box').offsetHeight;
			
			this.nomsg = '<p style="padding:10px 20px;">对不起，没有找到符合条件的结果，请修改搜索条件后重新尝试。</p>'
			this.pagediv = $('#b2b_page', content.doc);
			this.dataType = config.siteTypeValue;
			//this.searchBoxHeight =parseInt($("#b2b_searchbox", this.doc).offset()['height']);
			var searchTag=this.dataType+"Option"
			this.searchBoxHeight=parseInt($("#"+searchTag,this.doc).offset()['height'])+parseInt($("#b2b_title",this.doc).offset()['height'])
		    this.bootBoxHeight = parseInt($("#b2b_foot", this.doc).offset()['height']) ;
			this.idParam = '&AllianceId=' + ctripUnion.AllianceId + '&sid=' + ctripUnion.SId + '&ouid=' + ctripUnion.OuId;
			if(config.siteMode=='all')this.searchBoxHeight= ctripUnion.maxHeight(this.doc, ctripUnion.data.config.siteTypeValue);
			var skin=config.skinNum?config.skinNum:ctripUnion.datas.config.skinId;
			    if(skin==1)this.searchBoxHeight+=10;
				if(skin==2)this.searchBoxHeight+=5;
				if(skin==3)this.searchBoxHeight+=10;
			   if($("#showLogo",doc)[0]){config.showLogo=$("#showLogo",doc)[0].checked}
			  if(config.showLogo==false){
			    if(skin==0)this.searchBoxHeight+=4;
			    if(skin==1)this.searchBoxHeight+=20;
				if(skin==2)this.searchBoxHeight+=10;
				if(skin==3)this.searchBoxHeight+=20;
			}
			if(config.pageHeight<this.searchBoxHeight+this.bootBoxHeight){ 
			if($("#panelcurrentHeight")[0]){
			$("#panelcurrentHeight")[0].value=this.searchBoxHeight+this.bootBoxHeight;
			union.data.config.pageHeight=$("#panelcurrentHeight")[0].value
			config.pageHeight=$("#panelcurrentHeight")[0].value;
			$("#panelcurrentHeight")[0].style.backgroundColor="#FF0000";
			$('#colTipDiv')[0].innerHTML="在这些搜索选择条件下 高度已经达到了最小.系统已经自动为您添加了合适的高度。";
			}
			}else{if($("#panelcurrentHeight")[0]){$("#panelcurrentHeight")[0].style.backgroundColor="";$('#colTipDiv')[0].style.display="none";} }
			if($("#_main_box", this.doc)[0]){
			   var mainboxHeight=content.getMainboxHeight(this.doc,config)
			  $("#_main_box", this.doc)[0].style.height =mainboxHeight+ 'px';
			} 
			
			this.curRow=Math.floor((config.pageHeight-this.searchBoxHeight-this.bootBoxHeight-45-36)/this.itemHeight);
			this.curCol=Math.floor(config.pageWidth/this.itemWidth);
			this.count = this.curRow * this.curCol>0?this.curRow * this.curCol:0;
			if(this.count==0 || !config.showData || config.pageWidth<270 || config.pageHeight-this.searchBoxHeight-38<content.minContentHeight){
			  $("#b2b_searchOption",this.doc)[0].style.height=content.getMainboxHeight(this.doc,config,"title")+ 'px';
			  
			}
		},
		getSearchBoxHeight:function(doc,config){
		    var ctripUnion = content.ctripUnion;
			var config = ctripUnion.datas.config;
			var searchTitle=0;
			if(config.siteMode=="all"){searchTitle=parseInt($("#b2b_title", doc).offset()['height'])}
			var searchTag=content.dataType+"Option"
		    var searchBoxHeight = parseInt($("#"+searchTag, doc).offset()['height'])+searchTitle;

		    // bootBoxHeight = 38;
			var skin=config.skinNum?config.skinNum:ctripUnion.datas.config.skinId;
			    if(skin==0)searchBoxHeight+=0;
			    if(skin==1)searchBoxHeight+=10;
				if(skin==2)searchBoxHeight+=5;
				if(skin==3)searchBoxHeight+=10;
			 if($("#showLogo",doc)[0]){config.showLogo=$("#showLogo",doc)[0].checked}
			if(config.showLogo==false){
			    if(skin==0)searchBoxHeight+=4;
			    if(skin==1)searchBoxHeight+=20;
				if(skin==2)searchBoxHeight+=10;
				if(skin==3)searchBoxHeight+=20;
			}
			return searchBoxHeight
		
		},
		getMainboxHeight:function(doc,config){
		    var ctripUnion = content.ctripUnion;
			var config = ctripUnion.datas.config;
			var b2bTitlea=0;
			if(config.siteMode=="all"){b2bTitlea=parseInt($("#b2b_title", doc).offset()['height'])}
			 if($("#showLogo",doc)[0]){config.showLogo=$("#showLogo",doc)[0].checked}
		    var mainboxHeight=parseInt(config.pageHeight) - parseInt($("#b2b_foot", doc).offset()['height']);
		    var skin=config.skinNum?config.skinNum:ctripUnion.datas.config.skinId;
		    
		     
			  switch(skin){
			     case '0':
			           mainboxHeight-=1;
					    if(config.showLogo==false){mainboxHeight-=1}
					     if(config.showLogo==false&&config.showData==false){mainboxHeight-=1;}
			     break;
			     case '1':
			           mainboxHeight-=12;
			          if(config.showLogo==false&&config.showData==true){mainboxHeight-=0;}
			          if(config.showLogo==false&&config.showData==false){mainboxHeight-=0;}
			     break;
			     case '2':
			         mainboxHeight-=11;
			          if(config.showLogo==false&&config.showData==true){mainboxHeight+=1}
			          if(config.showLogo==false&&config.showData==false){mainboxHeight+=10;}
			     break;
			     case '3':
			        mainboxHeight-=2;
			          if(config.showLogo==false&&config.showData==true){mainboxHeight-=0}
			          if(config.showLogo==false&&config.showData==false){mainboxHeight-=0;}
			     break;
			  
			  }
			  if(arguments.length>2) mainboxHeight=mainboxHeight-b2bTitlea;
			  return mainboxHeight
		},
		//modify in 20120628 s27110 
		//根据指定宽、高度,计算应显示多少条数据.
		accountPageInfo: function(action, isAccount){
		    var isReRender = false;
		    var isSearchOld=false;
		    var ctripUnion = this.ctripUnion;
		    var config = ctripUnion.datas.config;
		   // var searchBoxHeight = parseInt($("#b2b_searchbox", this.doc).offset()['height']);
			var searchBoxWidth =parseInt($("#b2b_searchbox", this.doc).offset()['width']);
		    var pageBoxHeight = 36;//$("#b2b_page", this.doc).offset()['height'];
		    var bootBoxHeight = parseInt($("#b2b_foot", this.doc).offset()['height']);
		    var sortBoxHeight = 45;//$("#listSort", this.doc).offset()['height'];
			var searchBoxHeight=content.getSearchBoxHeight(this.doc,config)
			
		    this.contentHeight = parseInt(config.pageHeight) - searchBoxHeight - pageBoxHeight - bootBoxHeight;
			this.contentHeight = this.contentHeight > 0 ? this.contentHeight : 0;	
			if($("#b2b_content", this.doc)[0]) {
			$("#b2b_content", this.doc)[0].style.height = this.contentHeight + 'px';
			}
		    var curRow = Math.floor((this.contentHeight - sortBoxHeight) / this.itemHeight);
		    if($("#showLogo",this.doc)[0]){config.showLogo=$("#showLogo",this.doc)[0].checked}
			if(config.showLogo==false){curRow = Math.floor((this.contentHeight - sortBoxHeight) / this.itemHeight);}
		    var curCol = Math.floor(config.pageWidth  / this.itemWidth);
		    var styleCol = Math.ceil(config.pageWidth  / this.itemWidth);
		    if(this.count != curRow * curCol) isReRender = true;
		    else isReRender = false;
		    this.count = curRow * curCol < 0 ? 0 : curRow * curCol;
	        //if(this.count == 0 && config.pageWidth < 270){
	        var getSearHeight=content.getMainboxHeight(this.doc,config,"title")
		    if( config.pageWidth < 270 ){
		        $("#_maincontents", this.doc).addClass('min_box');
		        $("#b2b_searchOption",this.doc)[0].style.height=content.getMainboxHeight(this.doc,config,"title")+"px";		        
		    } else {
		        $("#_maincontents", this.doc).removeClass('min_box');
		       // $("#b2b_searchOption",this.doc)[0].style.height="auto" ;
		    };
		    var serHeight=searchBoxHeight
		    if(config.siteMode=='all'){serHeight= ctripUnion.maxHeight(this.doc, ctripUnion.data.config.siteTypeValue);}
		     if(config.pageHeight<serHeight+38){ 
		            config.showLogo=$("#showLogo")[0].checked;
		           // 
			        if($("#panelcurrentHeight")[0]){
			        $("#panelcurrentHeight")[0].value=serHeight+bootBoxHeight
			        union.data.config.pageHeight=$("#panelcurrentHeight")[0].value;
			        config.pageHeight=$("#panelcurrentHeight")[0].value;
			        $("#panelcurrentHeight")[0].style.backgroundColor="#FF0000";
			        $('#colTipDiv')[0].innerHTML="在这些搜索选择条件下 高度已经达到了最小.系统已经自动为您添加了合适的高度。";
			        }
			     
			         
			           $("#_main_box", this.doc)[0].style.height =content.getMainboxHeight(this.doc,config)+ 'px';
			           if(!config.showLogo){
			             $("#b2b_searchOption",this.doc)[0].style.height=content.getMainboxHeight(this.doc,config,"title")+38+"px";
			           }
		              
			}else{
			         if($("#panelcurrentHeight")[0]){$("#panelcurrentHeight")[0].style.backgroundColor="";$('#colTipDiv')[0].style.display="none";}
			         
			         
			          }	
		    
		    
		  
	         if(this.count ==0 && $("#ctrip_union_product_20121221")[0]){content.targ.style.visibility='hidden';
				         content.pagediv.css('visibility','hidden');}
		     if(window["b2b_ctrip_v2"]){
                if(this.count == 0){
                     bootBoxHeight=38
                    config.showLogo=$("#showLogo")[0].checked
				     if(config.pageHeight-searchBoxHeight-38<=content.minContentHeight){
				            content.targ.style.visibility='hidden';
				            content.pagediv.css('visibility','hidden');
				            $("#b2b_searchOption",this.doc)[0].style.height=content.getMainboxHeight(this.doc,config,"title")+"px";
				          }else{
				          $("#b2b_searchOption",this.doc)[0].style.height=content.getMainboxHeight(this.doc,config,"title")+"px";
				          }
				} 
                if(this.count > 0&&config.pageWidth >269 && config.showData  && config.pageHeight-searchBoxHeight-38>=content.minContentHeight){
                  $("#b2b_searchOption",this.doc)[0].style.height="auto" 
                  config.showData=$("#showDataList")[0].checked
				}else{
				    content.targ.style.visibility='hidden';
				    content.pagediv.css('visibility','hidden');
				   $("#b2b_searchOption",this.doc)[0].style.height=content.getMainboxHeight(this.doc,config,"title")+"px";
				}  
            };	
              
		    //action 只有在改变宽,高度时才有值
		    //isReRender 只有在应有条数 与 现有条数不符时才为真
		    //hash[this.dataType][1] 数据还没有请求到的时候, 不会重绘
		    //isAccount 确保只计算条数时  不进行重绘.
			//if(!hash[content.dataType][1]){content.getJSON()}  ctripUnion
		    if(action && isReRender && hash[content.dataType][1] && !isAccount){
		        this.reRenderData(this.count);
		    }
		},
		//根据数组的某一个值排序
		sortByType:function(arr,pro,way){
		    arr.sort(function(a,b){
		        return a[pro]*1 - (b[pro]*1);
		    })
		    if(way == 'desc' ){
		        return arr.reverse();
		    } else {
		        return arr;
		    }
		},
		//绑定排序事件
		 bindEvents: function() {
            var sort = $('#sort_select', content.doc);
            var b2bcontent = $("#b2b_content", content.doc);
            var selSort = $('#selSort', content.doc).find("a");
            selSort.bind('mouseover',  function() {
                if (b2bcontent.offset()['height'] < 205) {
                    b2bcontent[0].style.overflow = 'visible';
                }
                content.sortOption.style.display = '';
            })
            selSort.bind('mouseout',  function() {
                content.sortOption.style.display = 'none';
                b2bcontent[0].style.overflow = 'hidden';
            })
            sort.bind('mouseout',  function() {
                content.sortOption.style.display = 'none';
                b2bcontent[0].style.overflow = 'hidden';
            })
            sort.bind('mouseover',  function() {
                if (b2bcontent.offset()['height'] < 205) {
                    b2bcontent[0].style.overflow = 'visible';
                }
                content.sortOption.style.display = '';
            })
            sort.find("a").bind('click', 
                function() {
                    // alert($(this)[0].name)
                    var obj = {};
                    var dat;
                    var val = $(this)[0].name;
                    $('#selSort', content.doc)[0].getElementsByTagName('span')[0].innerHTML=$(this)[0].innerHTML
                    //将原始数据复制到一个全局变量
                    if (!window['originalHash']) {
                        window['originalHash'] = hash[content.dataType]['1'].slice();
                        window['requestTime'] = hash['timestamp'];
                    } else if (window['originalHash'] && window['requestTime'] != hash['timestamp']) {
                        //比对全局变量和hash的timestamp 如果不同 更新全局变量
                        window['originalHash'] = hash[content.dataType]['1'].slice();
                        window['requestTime'] = hash['timestamp'];
                    }
                    //判断排序类型 假如是默认 使用复制的全局变量为其赋值
                    if (val != 'default') {
                        var dat = hash[content.dataType]['1'];
                        var type = val.split('|')[0];
                        var way = val.split('|')[1];
                        dat = content.sortByType(dat, type, way);
 
                    } else {
                        dat = window['originalHash'].slice();
						hash[content.dataType]['1'] = dat;
 
                    }
                    //重绘页面
                    content.reRenderData(content.count)
                    content.sortOption.style.display = 'none'
 
                    })
        },
		//重新绘制数据区域
		reRenderData: function(num){
		    var obj = {};
		    var ctripUnion = this.ctripUnion;
		    var config = ctripUnion.datas.config;
			var dat = hash[this.dataType][1].slice(0, num);
			//var searchBoxHeight = parseInt($("#b2b_searchbox", this.doc).offset()['height']);
	        var   searchBoxHeight=content.getSearchBoxHeight(this.doc,config)
		    var bootBoxHeight = 38;

		    if(dat.length >0){
		       content.pagediv.css('visibility','visible');
			   content.sortTarg.style.display="none";
				content.loading.css('display','none');
			//	if(hash[this.dataType][1].length-1>=num ){
				ctripUnion.datas.searchResult[this.dataType] = dat;
			    obj.config = ctripUnion.datas.config;
			    obj.searchResult = ctripUnion.datas.searchResult;
				
		      
			//	}
		        var tpl = this.getTpl();
			    var ct = $.tmpl.render(tpl,obj);  
			    content.doc.charset="utf-8";   
				this.targList.innerHTML = ct;
				
				var current=1
				var page=Math.ceil(this.count*current / 100)>=0? Math.ceil(this.count*current / 100):0;
				
				
				if(config.pageHeight-searchBoxHeight-bootBoxHeight<content.minContentHeight){
				content.targ.style.visibility='hidden';
				content.loading.css('display','none');
				this.targList.innerHTML = '';
				content.sortTarg.style.display="none";
				content.pagediv.css('visibility','hidden');
				}else{
				if($("#showDataList")[0]){ config.showData=$("#showDataList")[0].checked}
				if(config.showData==false){
				  content.targ.style.visibility='hidden';
				   content.pagediv.css('visibility','hidden');
				}else{
				content.targ.style.visibility='';
				content.sortTarg.style.display = '';
				content.pagediv.css('visibility','');
				}
				
				}
				//var max = Math.ceil(hash[this.dataType][page].length / num);
			   //var max=Math.ceil(content.objCount/num)
			     
			     var max=Math.ceil(content.objCount/num)
				    content.current = 1;
				   content.pageInit(1, max, content.pageChange);
			} else {
				this.targList.innerHTML = '';
                content.pagediv.css('visibility','hidden');
				
			}
		},
		getJSON: function(page,cache){
		    
			var pages = page || 1, dtype = content.dataType;
			var cache = cache || false;
			var _url = this.url + this.idParam + '&pages=' + pages;
			var ctripUnion = this.ctripUnion;
		    var config = ctripUnion.datas.config;
			if (!cache) {
			    _url += '&fct=' + (new Date()).getTime();
			}
			//解决 ajax 响应覆盖的问题 modify by hailiangli@Ctrip.com
			var requestID = $("#hid_Ctrip_Union_ajaxRequest_dynamic_id")[0] || $("#requestID",this.doc)[0];
			var isLoad = false;
			
			if ( requestID ) {
			    requestID.value = parseInt(requestID.value) + 1;
			    _url += '&requestid='+requestID.value;
			} else {
			    _url += '&requestid=1';
			};
		    var bootBoxHeight =38;
			var searchBoxHeight=content.getSearchBoxHeight(this.doc,config)
		    if($("#ctrip_union_product_20121221")[0]){
			   if(config.pageHeight-searchBoxHeight-bootBoxHeight<content.minContentHeight){ 
			   return
			   }
			}
			if(config.pageHeight-searchBoxHeight-bootBoxHeight<content.minContentHeight){ 
			content.targ.style.visibility='hidden';
			content.loading.css('display','none');
			content.sortTarg.style.display="none";
			content.pagediv.css('visibility','hidden');
			}else{
			if($("#showDataList")[0]){config.showData=$("#showDataList")[0].checked}
			if(config.showData){
			content.targ.style.visibility='';
			content.loading.css('display','');
			content.targList.innerHTML = '';
			content.sortTarg.style.display="none";
			content.pagediv.css('visibility','hidden');
			}else{
			content.targ.style.visibility='hidden';
			content.pagediv.css('visibility','hidden');
			}
			}
			
			var curRow=Math.floor((config.pageHeight-searchBoxHeight-parseInt($("#b2b_foot", this.doc).offset()['height'])-45-36)/content.itemHeight);
			var curCol=Math.floor(config.pageWidth/content.itemWidth);
			content.count = curRow * curCol>0? curRow * curCol :  0;
			$.jsonp(_url+';charset=gb2312',{
			    charset:'gb2312',
				onload: function(obj){
				
				    if ( requestID ) {
				        if ( obj.requestID ) {
				            if ( parseInt(requestID.value) === parseInt(obj.requestID) ) {
				                isLoad = true;
				            }
				        } else isLoad = true;
				    } else {
					    isLoad = true;
					}
				   if(config.pageHeight-searchBoxHeight-bootBoxHeight<content.minContentHeight && config.showData && !$("#ctrip_union_product_20121221")[0]){ 
				  	  if(config.siteMode == 'all'){
				            if(/GetHotelList/gi.test(_url)){
				               hash["hotel"][pages] = obj.data;
				               content.objCount=obj.count; 
				           }
				           if(/GetAirPlanList/gi.test(_url)){
				              hash['ticket'][pages] = obj.data;
				              content.objCount=obj.count;
				           }
				            if(/GetPkgList/gi.test(_url)){
				             hash['holiday'][pages] = obj.data;
				             content.objCount=obj.count;
				           }
				            if(/GetGroupList/gi.test(_url)){
				             hash['group'][pages] =obj.data;
				             content.objCount=obj.count;
				           }
					   }}
					if (isLoad) {
					    if(obj.count < 1){
					          hash[dtype] = {};
					           content.loading.css('display','none');
						       content.pagediv.css('visibility','hidden');
                               content.targList.innerHTML = content.nomsg;
                              	content.sortTarg.style.display = 'none';
                              if(content.callback) content.callback(content.doc);
                              return false;
                        }
						if(config.pageHeight-searchBoxHeight-bootBoxHeight<content.minContentHeight){ 
						content.targ.style.visibility='hidden';
						content.targList.innerHTML = '';
						content.pagediv.css('visibility','hidden');
						}else{
						if($("#showDataList")[0]){config.showData=$("#showDataList")[0].checked}
						if(config.showData){
						content.targ.style.visibility='';
						content.loading.css('display','none');
						content.pagediv.css('visibility','visible');
						content.sortTarg.style.display = 'block';
						content.targList.style.display = '';
						}else{
						 content.targ.style.visibility='hidden';
						 content.pagediv.css('visibility','hidden');
						}
						
                        }
                         
					    if(pages == 1){
					        content.objCount=0;
						    hash[dtype] = {};
						   //content.ctripUnion.renderData(null, 1);
						    if(content.count > 0){
						        var max = Math.ceil(obj.count / content.count);
						        //var max = Math.floor(obj.count / content.count);
						        content.current = 1;
						        content.pageInit(1, max, content.pageChange);
						    }
					    }
					    content.objCount=obj.count;
					     
                        hash[dtype][pages] = obj.data;
                       
                        content.ctripUnion.renderData(null, 1);
					    hash['timestamp'] = new Date().getTime();
						
					   content.parseData(pages);
					}
				},
				onerror: function(){
				    
					hash[dtype] = {};
                   content.loading.css('display','none');
					content.pagediv.css('visibility','hidden');
                   content.targList.innerHTML = content.nomsg;
                   content.sortTarg.style.display = 'none';
				}
			});
		},
		parseData: function(page){
			var obj = {}
			var count = content.count, 
				cur = content.current,
				dtype = content.dataType,
				ctripUnion = content.ctripUnion;
			var config = ctripUnion.datas.config;
			var searchResult = ctripUnion.datas.searchResult;
			
			var data = hash[dtype][page] || [];
			//=取数据
			
			   if( (count * (cur - 1)) % 100+count>100) {
			      cur=1
			   }else{
			   cur = content.current
			   }
			   
			var start = (count * (cur - 1)) % 100;
			var end = start+count;
		//	if( ( start+count) > data.length-1 && document.all) {
			// end = data.length-1}
			if(data.length > 0){
				data = data.slice(start, end);
				//ie 下最后一个对象可能出现undefind
				if(!data[data.length - 1]){
				    data.pop();
				};
				
				searchResult[dtype] = data;
				obj.config = config;
				obj.searchResult = searchResult;
				this.updateContent(obj);
			}
		},
		updateContent: function(dat){
		  content.doc.charset="utf-8";
			var tpl = this.getTpl();
			
			var ct = $.tmpl.render(tpl,dat);
			
			this.targList.innerHTML = ct;
			var ct2 = $.tmpl.render(this.sorttmp,{opts:this.sortArr});
			
		    content.bindEvents();
			if(this.callback) this.callback(this.doc);
		},
		getTpl: function(){
			var ctripUnion = content.ctripUnion;
			var config = ctripUnion.datas.config;
			var searchResult = ctripUnion.datas.searchResult;
			var tpl = '';
			if(config){
				switch(config.siteTypeValue){
					case 'holiday': tpl = ctripUnion.holidaytmp;
						break;
					case 'ticket': tpl = ctripUnion.tickettmp;
						break;
					case 'hotel': tpl = ctripUnion.hoteltmp;
						break;
					case 'group' : tpl = ctripUnion.grouptmp;
						break;
				}
			}
			return tpl;
		},
		pageChange: function(current){
			var count = content.count;
			var c = count * current;
			var page = Math.ceil( c / 100);
			var dtype = content.dataType;
			content.current = current;
			if(hash[dtype][page]){
				content.parseData(page);
			}else{
				//=需重新请求数据
				content.getJSON(page);
			}
		},
		pageInit: function(current,max,callback){
			// alert(current);
			var config = {
				options:{
					max:max,
					step:5,
					current:current,
					prevText: '&lt',
					nextText: '&gt',
					splitText: "...",
					goto:false,
					showText:false,
					isUpdate: false
				},
				methods:{

				},
				listeners:{
					onChange: callback
				},
				template: {
					pageList: '<div ${className}>${page}</div>',
					page: '<a ${className} href="javascript:void(0);">${pageNo}</a>',
					total: '<span ${className}>${pageInfo}</span>',
					split: '<span ${className}>${splitText}</span>',
					goto:'<div class="c_pagevalue">到 <input type="text" class="c_page_num" name="" /> 页<input type="button" class="c_page_submit" value="确定" name="" /></div>',
					prev:'<a ${className} href="javascript:void(0);">${pageNo}</a>',
					next:'<a ${className} href="javascript:void(0);">${pageNo}</a>'
				},
				classNames: {
					prev: 'c_up',
					next: 'c_down',
					prev_no:'c_up_nocurrent',
					next_no:'c_down_nocurrent',
					list: 'c_page_list layoutfix',
					action: 'select',
					disabled: 'disabled',
					split: 'c_page_ellipsis',
					total: 'page_total',
					current: 'current'
				}
			};
			// targ.unregMod('page', '1.2');
			if(this.page){
				var targ = $('#b2b_page', content.doc);
				config.options.isUpdate = true;
				this.page.method('init',targ[0],config);
			}else{
				var targ = $('#b2b_page', content.doc);
				this.page = targ.regMod('page','1.2',config);
			}
		}
	}
	
	var groupValidate = function(doc,obj){
		var _url = content.ctripUnion.hostAddress + 'union/Ajax/AjaxServiceForSearch.ashx?action=GetGroupList';
		var paramobj = {
			incity: null,
			incityid: null,
			incityPY: null,
			sprice:null,
			eprice:null,
			orderby:null,
			pageSize:null
		};
		function getElement(id){
			if(typeof id == 'string'){
				return doc.getElementById(id);
			}
			return id;
		};
		function verifyKeyPress(tag, defaultValue, max) {
			var regOnlyInt = /^[1-9]\d*$/
			,regOnlyNum = /^\d+$/
			,res;
			
			if (tag.value.length == 0) return true;
			
			if (regOnlyNum.test(tag.value)) res = regOnlyInt.test(parseInt(tag.value))
			else res = false;
			
			res && arguments.length == arguments.callee.length && (function () {
				res = parseInt(tag.value) <= parseInt(max);
			})();
			
			!res && (function () {
				tag.value = defaultValue || '';
			})();
			
			return res;
		};
		function isNull(id){
			var targ = getElement(id);
			var v = targ.value;
			if(v == '' || v == targ.defaultValue){
				return true;
			}
			return false;
		};
		function CheckPrice(){
			var s=false,e=false;
			if($('#groupdayprice',doc).length>0){
				if($('#groupdayprice',doc)[0].value.length > 0){
					paramobj.sprice = $('#groupdayprice',doc)[0].value;
					s = true;
				} else {
					paramobj.sprice = null;
					s = false;
				}
			}
			if($('#groupdayprice2',doc).length>0){
				if($('#groupdayprice2',doc)[0].value.length > 0){
					paramobj.eprice = $('#groupdayprice2',doc)[0].value;
					e = true;
				} else {
					paramobj.eprice = null;
					e = false;
				}
			}
			if(s&&e){
				if(parseInt($('#groupdayprice',doc)[0].value) > parseInt($('#groupdayprice2',doc)[0].value)){
					mod.warn($('#groupdayprice',doc)[0],'起始价格不能大于截止价格!');
					return false;
				}
			}
			return true;
		};
		function CheckCityName(){
			if($("#groupCity",doc).length>0){
				paramobj.incity = escape($("#groupCity",doc)[0].value);
			};
			if($("#grouphidCity",doc).length>0){
				paramobj.incityid = $("#grouphidCity",doc)[0].value;
			};
			if ($('#grouphidCityPinYin',doc).length>0) {
			    paramobj.incityPY = $('#grouphidCityPinYin',doc)[0].value;
			}
		};
		function setUrl(){
		var param = '';
			mod.warn();
			if (!CheckPrice()) return;
			CheckCityName();
			if($('#select_groupsortType',doc).length>0){
				paramobj.orderby = $('#select_groupsortType',doc)[0].value;
			};
			
			for(var o in paramobj){
				if(paramobj[o])
					param += '&' + o + '=' + paramobj[o];
			}
			
			content.url = _url + param;
			return  content.url
		}
		 
		 var showdata = content.ctripUnion.datas.config.showData;   
			if( !$("#ctrip_union_product_20121221")[0] || $("#ctrip_union_product_20121221")[0]){
			content.url=setUrl()
			content.getJSON();
			}
        
		$("#groupSearchBt",doc).bind('click', function(){
		    if( $("#showDataList")[0]){
		    var showDataC=union.data.config.showData;
		    }else{ 
		    var showDataC=content.ctripUnion.datas.config.showData;  
		    }
		    var config=content.ctripUnion.datas.config
			//var searchBoxHeight = parseInt($("#b2b_searchbox", doc).offset()['height']);
		    var bootBoxHeight =38;
		    var searchBoxHeight=content.getSearchBoxHeight(doc,config)
			   if((config.pageHeight-searchBoxHeight-bootBoxHeight<content.minContentHeight && content.ctripUnion.datas.config.showData) || (config.pageWidth < 270)){ 
			     // content.ctripUnion.datas.config.showData=false
				   showDataC=false;  
			   }
		     if(showDataC ){
		         content.url=setUrl() 
			     content.getJSON();
			} else{
			    CheckCityName();
			    content.url=setUrl()  
				var jumpUrl = 'http://tuan.ctrip.com/Group/';
				if (paramobj.incityPY && paramobj.incity) jumpUrl += 'city_' + paramobj.incityPY.toLowerCase() + '/';
				else if(paramobj.incityid){
				    var reg = new RegExp('\\@.[^@]{1,}\\|.[^@]\\|' + paramobj.incityid + '\\|');
				    jumpUrl += 'city_' + content.ctripUnion.groupCityAddress.match(reg)[0].split('|')[0].replace('@','').toLowerCase() + '/';
				}
				if (paramobj.orderby) jumpUrl += 'sort_' + paramobj.orderby + '/';
				if (paramobj.sprice) jumpUrl += 'lprice_' + paramobj.sprice + '/';
				if (paramobj.eprice) jumpUrl += 'uprice_' + paramobj.eprice + '/';
				
				content.ctripUnion.redirectUrl(jumpUrl);
				
			
           }
		   
		  
		});
		if($('#groupdayprice',doc).length>0){
			$('#groupdayprice',doc).bind('keyup', function(){
				verifyKeyPress(this,"0","999999");
			});
		};
		if($('#groupdayprice2',doc).length>0){
			$('#groupdayprice2',doc).bind('keyup', function(){
				verifyKeyPress(this,"0","999999");
			});
		}
	};
	var hotelValidate = function(doc,obj){
		var _url = 'http://u.ctrip.com/union/Ajax/AjaxServiceForSearch.ashx?action=GetHotelList';
		//var _url = 'http://u.dev.sh.ctriptravel.com/union/Ajax/AjaxServiceForSearch.ashx?action=GetHotelList';
		var paramobj = {
			incity: '',
			indate: '',
			outdate: '',
			sprice: '',
			eprice: '',
			hoteltype: '',
			hotelname: '',
			star: '',
			area: 'd'
		}
		var hotelparam = {
		    incity: 'city',
			indate: 'starttime',
			outdate: 'deptime',
			sprice: 'begprice',
			eprice: 'endprice',
			hoteltype: 'hoteltype',
			hotelname: 'hotelname',
			star: 'star',
			area: 'area'
		}
		function getElement(id){
			if(typeof id == 'string'){
				return doc.getElementById(id);
			}
			return id;
		}
		function isTWCity(){ //判断如果是台湾的城市，跳转到海外酒店
			var cv = cityname.value;
			var TW = ["台北", "高雄", "垦丁", "台北县", "桃园县"];
			for(var i=0,l=TW.length; i<l; i++){
				// if(cv == $s2t(TW[i])){return true;}
				if(cv == TW[i]){return true;}
			}
			return false;
		}
		function isNull(id){
			var targ = getElement(id);
			var v = targ.value;
			if(v == '' || v == targ.defaultValue){
				return true;
			}
			return false;
		}
		function checkCityName(targ){
			var targ = getElement(targ);
			if(isNull(targ)){
				targ.className = 'f_error';
				mod.warn(targ,'请输入宾馆所在城市');
				return false;
			}
			targ.className = '';
			return targ.value;
		}
		var flag = [];
		var tmsg = {
			starttime: [
					'请输入入住时间',
					'入住时间不符合格式规范或无效的日期'
				],
			deptime: [
					'请输入离店时间',
					'离店时间不符合格式规范或无效的日期'
			]
		}
		function checkTime(id){

			var perdate = getElement("perdate").value,
				postdate = getElement("postdate").value;
			var perdateCalc= perdate.toDate(),
				postdateCalc=postdate.toDate();
			var targ = getElement(id),msg = tmsg[id];
			if (isNull(targ)){
				targ.className = "f_error";
				mod.warn(targ, msg[0]);
				return false;
			}
			var v = targ.value.toDate();
			if(!v){
				targ.className = "f_error";
				mod.warn(targ,msg[1]);
				return false;
			}
			
			flag[id] = v;
			if(id == 'starttime'){
				if(perdateCalc&&flag['starttime']<perdateCalc){
					targ.className = "f_error";
					mod.warn(targ,"入住时间不能早于"+perdate);
					return false;
				}
			}
			
			if(id=="deptime"){
				if(flag.starttime && flag.deptime){
					if (flag.deptime <= flag.starttime){
						targ.className = "f_error";
						mod.warn('deptime',"离店时间不能早于或等于入住时间");
						return false;
					}else if (flag.deptime-flag.starttime>2419200000){
						targ.className = "f_error";
						mod.warn(targ,"入住时间段不能超过28天");
						return false;
					}
				}
			}
			targ.className = "";
			return targ.value;
		};
		
		function adjustCityId(sid){
            var id = parseInt(sid, 10);
            return id < 20000 ? id - 100 : id < 80000 ? id - 20000 : id - 80000;
        };

		var cityname = 'cityname';
		
		$('.f_type_ir[name="hotelType"]', doc).each(function(item, i){
			item.bind('click',function(){
				if(i === 0 && cityname != 'cityname'){
					//=国内酒店
					getElement('citynameInter').style.display = 'none';
					getElement('cityname').style.display = 'inline';
					cityname = 'cityname';
					paramobj.area = 'd';
					if( $("#showDataList")[0]){
		               var showDataC=union.data.config.showData;
		             }else{ 
		              var showDataC=content.ctripUnion.datas.config.showData;   
		              }
					 if(showDataC){
					 triggerClick('hotel', doc);
					 }
					
				}else{
					//=国际酒店
					getElement('citynameInter').style.display = 'inline';
					getElement('cityname').style.display = 'none';
					cityname = 'citynameInter';
					paramobj.area = 'o';
					if( $("#showDataList")[0]){
		               var showDataC=union.data.config.showData;
		             }else{ 
		              var showDataC=content.ctripUnion.datas.config.showData;   
		              }
					 if(showDataC){
					 triggerClick('hotel', doc);
					 }
				}
			});
		});
		var param = '', pass = true, t = null, arr = [], v = '';

		var showdata = content.ctripUnion.datas.config.showData;
	
		 if( !$("#ctrip_union_product_20121221")[0] || $("#ctrip_union_product_20121221")[0]){
		   param = '';
			mod.warn();
			for(var i in obj){
				var o = obj[i], v = '';
				if(o.id){
					if(o.id == 'cityname'){
						if(v = checkCityName(cityname)){
							if(paramobj.area == 'd'){
								paramobj.incity = getElement('cityId').value;
							}
							else{
								paramobj.incity = adjustCityId(getElement('DistrictId').value);
							}
							pass = true;
						}else{
							pass = false;
							break
						}
					}//else if((o.id == 'starttime' || o.id == 'deptime') && getElement(o.id)){
					  //modify by lhl 2012-1-31
					  else if(o.id == 'starttime' && getElement(o.id).offsetWidth > 0) {
						if(v =  checkTime(o.id)){
							paramobj.indate = v;
							pass = true;
						}else{
							pass = false;
							break
						}
					}else if(o.id == 'deptime' && getElement(o.id).offsetWidth > 0){
						if(v =  checkTime(o.id)){
							paramobj.outdate = v;
							pass = true;
						}else{
							pass = false;
							break
						}
					}else{
						if(t = getElement(o.id)){
							v = t.value;
							switch(o.id){
								case 'priceRange': arr = v.split('-'), paramobj.sprice = arr[0], paramobj.eprice = arr[1];
									break;
								case 'hotelTypes': paramobj.hoteltype = v;
									break;
								case 'hotelLevel': paramobj.star = v;
									break;
								case 'hotelName': paramobj.hotelname = v;
									break;
							}
						}
					}
				}
			}
			
			//显示数据列表
			if(pass){
				//=发送请求
				for(var a in paramobj){
					if(paramobj[a]){
						param += '&' + a + '=' + paramobj[a];
					}
				}
				content.url = _url + param;
				content.getJSON();
			   //content.url=setUrl()
			}
		 
		 
		 }
		
		$('#hotelsubmit',doc).bind('click', function(){
		    param = '';
			mod.warn();
			for(var i in obj){
				var o = obj[i], v = '';
				if(o.id){
					if(o.id == 'cityname'){
						if(v = checkCityName(cityname)){
							if(paramobj.area == 'd'){
								paramobj.incity = getElement('cityId').value;
							}
							else{
								paramobj.incity = adjustCityId(getElement('DistrictId').value);
							}
							pass = true;
						}else{
							pass = false;
							break
						}
					}//else if((o.id == 'starttime' || o.id == 'deptime') && getElement(o.id)){
					  //modify by lhl 2012-1-31
					  else if(o.id == 'starttime' && getElement(o.id).offsetWidth > 0) {
						if(v =  checkTime(o.id)){
							paramobj.indate = v;
							pass = true;
						}else{
							pass = false;
							break
						}
					}else if(o.id == 'deptime' && getElement(o.id).offsetWidth > 0){
						if(v =  checkTime(o.id)){
							paramobj.outdate = v;
							pass = true;
						}else{
							pass = false;
							break
						}
					}else{
						if(t = getElement(o.id)){
							v = t.value;
							switch(o.id){
								case 'priceRange': arr = v.split('-'), paramobj.sprice = arr[0], paramobj.eprice = arr[1];
									break;
								case 'hotelTypes': paramobj.hoteltype = v;
									break;
								case 'hotelLevel': paramobj.star = v;
									break;
								case 'hotelName': paramobj.hotelname = v;
									break;
							}
						}
					}
				}
			}
			
			//显示数据列表
			if(pass){
				//=发送请求
				for(var a in paramobj){
					if(paramobj[a]){
						param += '&' + a + '=' + paramobj[a];
					}
				}
				content.url = _url + param;
		    if( $("#showDataList")[0]){
		         
		    var showDataC=union.data.config.showData;
		    }else{ 
		    var showDataC=content.ctripUnion.datas.config.showData;  
		    }
		      var config=content.ctripUnion.datas.config
		      var bootBoxHeight =38;
	    	  var searchBoxHeight=content.getSearchBoxHeight(doc,config)
			   if((config.pageHeight-searchBoxHeight-bootBoxHeight<content.minContentHeight && content.ctripUnion.datas.config.showData) || (config.pageWidth < 270)){ 
			     // content.ctripUnion.datas.config.showData=false
				   showDataC=false;  
			   }

		   if(showDataC ){ 
				  content.getJSON();
					
				}else{
				  //if(config.siteMode=='all')
					//=跳转
					var hotelurl = "";

			        if(paramobj.area == 'o') hotelurl = "http://hotels.ctrip.com/international/ShowHotelList.aspx?searchtype=9&submittype=1";
			        else if(paramobj.area == 'd') hotelurl = "http://hotels.ctrip.com/domestic/showhotellist.aspx?searchtype=9&submittype=1";
			        
			        for(var o in paramobj) {
			            if (paramobj[o]) {
			                hotelurl += "&" + hotelparam[o] + "=" + paramobj[o];
			            }
			        }
					
			        //入住天数
			        if (paramobj.indate && paramobj.outdate) {
			            hotelurl += "&rooms=" + content.ctripUnion.getDateDiff(paramobj.indate, paramobj.outdate, "DAY");
			        }
					content.ctripUnion.redirectUrl(hotelurl);
					
				}
			}
		});
	};
	var ticketValidate= function(doc){
	    var isSetUrl=true
		doc.forms["flightForm"]["today"].value="2012-01-10";  //要改
		if($("#ticketSearchBt",doc).length > 0){
			$("#ticketSearchBt",doc).bind('click',function(e){
			    isSetUrl=true;
			    isWinOpen = true;
				tovalidate(doc);
			});
		
		}
		
		var flightSwitch = $("*[name='flightSwitch']",doc);
		 if(flightSwitch){
				flightSwitch.bind("click", function(e){
				        isWinOpen = false;
						var e = e || window.event;
						var tag= e.srcElement || e.target;
						if(tag.value==0){
							$("#homecity_name",doc)[0].style.display="";
							$("#destcity1_name",doc)[0].style.display="";
							$("#homecity_nameOut",doc)[0].style.display="none";
							$("#destcity1_nameOut",doc)[0].style.display="none";
							  if( $("#showDataList")[0]){
		    var showDataC=union.data.config.showData;
		    }else{ 
		    var showDataC=content.ctripUnion.datas.config.showData;   
		    }
		   if(showDataC ){ triggerClick('ticket', doc);  }
							
						}else{
							$("#homecity_name",doc)[0].style.display="none";
							$("#destcity1_name",doc)[0].style.display="none";
							$("#homecity_nameOut",doc)[0].style.display="";
							$("#destcity1_nameOut",doc)[0].style.display="";
						  if( $("#showDataList")[0]){
		    var showDataC=union.data.config.showData;
		    }else{ 
		    var showDataC=content.ctripUnion.datas.config.showData;   
		    }
		   if(showDataC ){ triggerClick('ticket', doc);  }
						}
						//if(tag.value==0){ //国内
						//	flightTag=$("flightTag").value = true; //回退保存时使用
						//}
					    //else 
						//	flightTag=$("flightTag").value =false;
						//切换时更替source
						//setFlightSource();
						//清空原本的数据
						//resetFlight();
				});
		}
		var showdata = content.ctripUnion.datas.config.showData;
		var tovalidate=function(doc){
			var form=doc.forms["flightForm"];
			var flightway=form["flightway"];
			var flightBackFlag=$("#flightBackFlag")[0]; //div
			var homecity=form["homecity_name"],destcity1=form["destcity1_name"];
			var homecityOut=form["homecity_nameOut"],destcity1Out=form["destcity1_nameOut"];
			var HomeCityID=form["HomeCityID"],destcityID=form["destcityID"]; //hidden
			var DDatePeriod1=form["DDatePeriod1"],ADatePeriod1=form["ADatePeriod1"],today=form["today"];
			var destcity1Code=$("#destcity1",doc);
			var flightTag = true; //是否是国内机票
			var flightSwitch = $("*[name='flightSwitch']",doc);  //需要改变
			var _url = 'http://u.ctrip.com/union/Ajax/AjaxServiceForSearch.ashx?action=';
			//var _url = 'http://u.dev.sh.ctriptravel.com/union/Ajax/AjaxServiceForSearch.ashx?action=';
			//var _url = 'http://u.test.sh.ctriptravel.com/union/Ajax/AjaxServiceForSearch.ashx?action=';
			function setFlightSource(){
				if(flightTag){ //国内机票
					//flightSwitch.$("a")[0].className = "current";
					//flightSwitch.$("a")[1].className = "";
					//flightSwitch.className = "flts_channel_dom";
					$(homecity).module.address.source = "fltDomestic";
					$(destcity1).module.address.source = "fltDomestic";
					form.action = flightSwitch[0].getAttribute("faction");
				}else{   //国际机票
					//flightSwitch.$("a")[0].className = "";
					//flightSwitch.$("a")[1].className = "current";
					//flightSwitch.className ="flts_channel_int";
					
					//$(homecity).module.address.source = "fltInternationalStart" ;  //要改 数据源
					//$(destcity1).module.address.source = "fltInternational";
					form.action = flightSwitch[1].getAttribute("faction");
				}
				
			}
			
			function resetFlight(){
				homecity.value = destcity1.value = DDatePeriod1.value = ADatePeriod1.value = "";
				//$(homecity).module.notice.check();  // 改4行
				//$(destcity1).module.notice.check();
				//$(DDatePeriod1).module.notice.check();
				//$(ADatePeriod1).module.notice.check();
				//setRadioValue(flightway, "Single");
				//flightBackFlag.style.visibility = "hidden";
			}
			
			
			
			var beginvalidate=function(){
				var isIntFlt=function (){
					if($("*[name='flightSwitch']",doc)[0].checked==true)
						return false;
					else
						return true;
				}
				if(isIntFlt()){
					homecity=form["homecity_nameOut"],destcity1=form["destcity1_nameOut"];
				}
				if (homecity.value.trim()==""){
					homecity.className = "f_error";
					if(isIntFlt())
						mod.warn("homecity_nameOut","请选择您的出发城市");
					else
						mod.warn("homecity_name","请选择您的出发城市");
					return false;
				} else {
					mod.warn();
					homecity.className = "";
				}
				if (destcity1 && destcity1.value.trim()==""){
					destcity1.className = "f_error";
					if(isIntFlt())
						mod.warn("destcity1_nameOut","请选择您的到达城市");
					else
						mod.warn("destcity1_name","请选择您的到达城市");
					return false;
				} else if(destcity1){
					destcity1.className = "";
				}
				if (homecity && destcity1 && homecity.value==destcity1.value){
					destcity1.className = "f_error";
					if(isIntFlt())
						mod.warn("destcity1_nameOut","您选择的出发城市与到达城市相同,请重新选择");
					else
						mod.warn("destcity1_name","您选择的出发城市与到达城市相同,请重新选择");
					return false;
				} else if(destcity1) {
					destcity1.className = "";
				}
				var d1;
				var d4;
				if(DDatePeriod1){	
					if (DDatePeriod1.value.trim()==""){
						DDatePeriod1.className = "f_error";
						mod.warn("DDatePeriod1","请选择您的出发日期");
						return false;
					} else {
						DDatePeriod1.className = "";
					}
					d1=DDatePeriod1.value.toDate();;
					if (!d1){
						DDatePeriod1.className = "f_error";
						mod.warn("DDatePeriod1","出发日期不符合格式规范或无效的日期");
						return false;
					} else {
						DDatePeriod1.className = "";
					}
					var d3=today.value.toDate();
					if (d3>d1){
						DDatePeriod1.className = "f_error";
						mod.warn("DDatePeriod1","出发日期不能早于" +today.value);
						return false;
					} else {
						DDatePeriod1.className = "";
					}
					d4 =new Date(d3.getFullYear()+1,d3.getMonth(),d3.getDate());
					if(d4<d1){
						DDatePeriod1.className = "f_error";
						mod.warn("DDatePeriod1","只能查询一年内航班");
						return false;
					} else {
						DDatePeriod1.className = "";
					}
				}
				//返回日期可为空
				//if (ADatePeriod1.isNull()&&flightway.value=="Double"){
					//setRadioValue(flightway,"Single");
					//changeFlightType();
				//}
				if (ADatePeriod1 && ADatePeriod1.offsetWidth>0){
					var d2=ADatePeriod1.value.toDate();
					if (!d2){
						ADatePeriod1.className = "f_error";
						mod.warn("ADatePeriod1","返回日期不符合格式规范或无效的日期");
						//$alert(ADatePeriod1,$s2t("返回日期不符合格式规范或无效的日期"), false);
						return false;
					} else {
						ADatePeriod1.className = "";
					}
					if (d2<d1){
						ADatePeriod1.className = "f_error";
						mod.warn("ADatePeriod1","返回日期不能早于出发日期");
						//$alert(ADatePeriod1,$s2t("返回日期不能早于出发日期")+DDatePeriod1.value, false);
						return false;
					} else {
						ADatePeriod1.className = "";
					}
					//首页机票日期输入限定优化 一年内可选
					if(d4<d2){
						ADatePeriod1.className = "f_error";
						mod.warn("ADatePeriod1","只能查询一年内航班");
						//$alert(ADatePeriod1,$s2t("只能查询一年内航班"), false);
						return false;
					} else {
						ADatePeriod1.className = "";
					}
				}

				//国际机票判断
				var PType=form["PType"];
				var flightclass=form["flightclass"];
				
				if (isIntFlt()){
					
					//将上海(虹桥)，上海(浦东)，北京(首都)，北京(南苑)过滤成上海|北京
					var specialCity = ["上海(虹桥)","上海(浦东)","北京(首都)","北京(南苑)"];
					var hc = homecity.value;
					for(var i=0,l=specialCity.length;i<l;i++){
						if(hc==specialCity[i]){
							homecity.value = hc.replace(/\(.+\)/,"");
							break;
						}
					}
					if(ADatePeriod1 && ADatePeriod1.value.trim()=="")
						ADatePeriod1.value="";
					flightclass.value="I";
					//出发城市
					if (!fillCode("fltInternationalStart",homecity,HomeCityID)){
						homecity.className = "f_error";
					mod.warn("homecity_nameOut","你选择的出发城市没有前往"+destcity1.value+"的航班，请重新选择");
						return false;
					} else {
						homecity.className = "";
					}
					//目的城市
					if (!fillCode("fltInternational",destcity1,destcityID)){
						destcity1.className = "f_error";
						mod.warn("homecity_nameOut","你选择的出发城市没有前往该目的城市的航班，请重新选择");
						return false;
					} else {
						destcity1.className = "";
					}
					form["ticketagency_list"].value=form["homecity_nameOut"].value;
					form["ticketagencyID"].value=form["homecityOut"].value;
					//form.action="http://flights.ctrip.com/International/ShowFareFirst.aspx";
					//form.action="http://flights."+getDomain()+"/International/ShowFareFirst.aspx";
				}
				var datas=[];
 				if(DDatePeriod1)
					datas.push("&ddate="+DDatePeriod1.value);
				//if(ADatePeriod1 && $("#flightway",doc)[0] && $("#flightway",doc)[0].value=="Double")
				if(ADatePeriod1 && ADatePeriod1.offsetWidth>0) {
					datas.push("&bdate="+ADatePeriod1.value);
					datas.push("&ismu=1");
			    }
				if(form["siteLevel"] && form["siteLevel"].value)
					datas.push("&classLevel="+form["siteLevel"].value);
				if(form["ticketpeoples"])
					datas.push("&class="+form["ticketpeoples"].value);
				var urls=null;
				if(!isIntFlt()){
					datas.push("&dcity="+$("#homecity",doc)[0].value);
					datas.push("&acity="+$("#destcity1",doc)[0].value);
					urls=_url+"GetAirPlanList";
				}else{
					datas.push("&dcity="+$("#homecityOut",doc)[0].value);
					datas.push("&acity="+$("#destcity1Out",doc)[0].value);
				
					urls=_url+"GetOverSeasAirList";
				}
				urls += datas.join('');
				content.url =urls;
                if(!isSetUrl){
				content.getJSON();
				}else{
	      if( $("#showDataList")[0]){
		    var showDataC=union.data.config.showData;
		    }else{ 
		    var showDataC=content.ctripUnion.datas.config.showData;  
		    }
		    var config=content.ctripUnion.datas.config
		    var bootBoxHeight =38;
		    var searchBoxHeight=content.getSearchBoxHeight(doc,config)
			   if((config.pageHeight-searchBoxHeight-bootBoxHeight<content.minContentHeight && content.ctripUnion.datas.config.showData) || (config.pageWidth<270)){ 
			     // content.ctripUnion.datas.config.showData=false
				   showDataC=false;  
			   }
		   if(showDataC ){
				content.url =urls;
					content.getJSON();
				}else{
					//=跳转
					if (!isWinOpen) return true;
					//debugger;
					var airplanurl;
					if (!isIntFlt()) {
					    airplanurl = "http://flights.ctrip.com/domestic/showfarefirst.aspx?allianceid="+content.ctripUnion.AllianceId+"&sid="+content.ctripUnion.SId+"&ouid="+content.ctripUnion.OuId + "&flightsearchtype=S&passengertype=ADU";
					    airplanurl += "&dcityname1=" + escape($("#homecity_name",doc).value());
					    airplanurl += "&acityname1=" + escape($("#destcity1_name",doc).value());
					    airplanurl += "&dcity1=" + $("#homecity",doc)[0].value;
					    airplanurl += "&acity1=" + $("#destcity1",doc)[0].value;
					    
					    //往返时间
					    //控件是否存在，若存在是否被显示
					    //出发日期
					    var sDate = (function(){
					        if(DDatePeriod1)
					            if(DDatePeriod1.offsetWidth > 0)
					                return true;
					            else return false;
					        else return false;
					    })();
					    //返程日期
					    var eDate = (function(){
					        if(ADatePeriod1)
					            if(ADatePeriod1.offsetWidth > 0)
					                return true;
					            else return false;
					        else return false;
					    })();
					    
					    if (sDate && eDate) {
					        airplanurl += "&flightway=double";
					        airplanurl += "&ddate1=" + DDatePeriod1.value + "&ddate2=" + ADatePeriod1.value;
					    } else if (!sDate && !eDate) {
					        airplanurl += "&flightway=single";
					        var nowDate = new Date();
					        airplanurl += "&ddate1=" + nowDate.getFullYear() + "-" + (nowDate.getMonth() + 1) + "-" + nowDate.getDate();
					    } else {
					        airplanurl += "&flightway=single";
					        airplanurl += "&ddate1=" + DDatePeriod1.value;
					    }
					    //舱位级别
					    if(form["siteLevel"] && form["siteLevel"].value) {
					        airplanurl += "&classtype=" + (form["siteLevel"].value === "0" ? "" : "CF");
					    } else {
					        airplanurl += "&classtype=";
					    }
    					
					    //人数
					    if (form["ticketpeoples"]) {
					        airplanurl += "&passengerquantity=" + form["ticketpeoples"].value;
					    }
					} else {
					    airplanurl = "http://flights.ctrip.com/international/ShowFareFirst.aspx?allianceid="+content.ctripUnion.AllianceId+"&sid="+content.ctripUnion.SId+"&ouid="+content.ctripUnion.OuId;
					    var nowDate = new Date();
					    nowDate = nowDate.getFullYear() + "-" + (nowDate.getMonth() + 1) + "-" + nowDate.getDate();
					    airplanurl += "&dcity=" + $("#homecityOut",doc)[0].value;
					    airplanurl += "&acity=" + $("#destcity1Out",doc)[0].value;
						
					    var sDate = (function(){
					        if(DDatePeriod1)
					            if(DDatePeriod1.offsetWidth > 0)
					                return true;
					            else return false;
					        else return false;
					    })();
					    //返程日期
					    var eDate = (function(){
					        if(ADatePeriod1)
					            if(ADatePeriod1.offsetWidth > 0)
					                return true;
					            else return false;
					        else return false;
					    })();
						if (sDate && eDate) {
					        airplanurl += "&flightway=double";
							airplanurl += "&flighttype=D";
							airplanurl += "&relddate=" + window['ctrip'].getDateDiff(nowDate, DDatePeriod1.value, "DAY");
					        airplanurl += "&relrdate=" + window['ctrip'].getDateDiff(nowDate, ADatePeriod1.value, "DAY");
					    } else if (!sDate && !eDate) {
					        airplanurl += "&flightway=single";
							airplanurl += "&flighttype=S";
					        airplanurl += "&relddate=0";
					    } else {
					        airplanurl += "&flightway=single";
						    airplanurl += "&flighttype=S";
					        airplanurl += "&relddate=" + window['ctrip'].getDateDiff(nowDate, DDatePeriod1.value, "DAY");
					    }
					
						
					//	
					    /*if (DDatePeriod1 && ADatePeriod1) {
					        airplanurl += "&flighttype=D";
					        airplanurl += "&relddate=" + window['ctrip'].getDateDiff(nowDate, DDatePeriod1.value, "DAY");
					        airplanurl += "&relrdate=" + window['ctrip'].getDateDiff(nowDate, ADatePeriod1.value, "DAY");
					    } else if (!DDatePeriod1 && !ADatePeriod1) {
					        airplanurl += "&flighttype=S";
					        airplanurl += "&relddate=0";
					    } else {
					        airplanurl += "&flighttype=S";
					        airplanurl += "&relddate=" + window['ctrip'].getDateDiff(nowDate, DDatePeriod1.value, "DAY");
					    }*/
					}
					
					content.ctripUnion.redirectUrl(airplanurl);
				
				}
				}
				return true;
			};
			function getDomain(){
				var arr=location.hostname.match(/(big5\.)?ctrip\.com|(big5\.)?([^\.]+).sh.ctriptravel.com$/);
				return arr&&!/^local$/i.test(arr[2])?arr[0]:"ctrip.com";
			}
			function fillCode(sourceName,fromObj,toObj){
				//var source=$$.module.address.source[sourceName]; //要改
				//if (!source)
				//	return false;
				//var re=new RegExp("@[^\\|]*\\|"+fromObj.value.replace(/([\.\\\/\+\*\?\[\]\{\}\(\)\^\$\|])/g,"\\$1")+"[^@]*","i");
				//var arr=source.match(re);
				//if (!arr)
				//	return false;
				//toObj.value=arr[0].match(/^@[^\|]*\|[^\|]*\|([^\|@]*)/)[1];
				return true;
			}
			beginvalidate();
		}
		
		if( !$("#ctrip_union_product_20121221")[0] || $("#ctrip_union_product_20121221")[0]){
		   isSetUrl=false;
		  tovalidate(doc);
		}
	
	}
	
	var holidayValidate = function(doc, obj){
		var _url = 'http://u.ctrip.com/union/Ajax/AjaxServiceForSearch.ashx?action=GetPkgList';
		//var _url = 'http://u.dev.sh.ctriptravel.com/union/Ajax/AjaxServiceForSearch.ashx?action=GetPkgList';
		
		function getElement(id){
			if(typeof id == 'string'){
				return doc.getElementById(id);
			}
			return id;
		}
		function isNull(id){
			var targ = getElement(id);
			var v = targ.value;
			if(v == '' || v == targ.defaultValue){
				return true;
			}
			return false;
		}
		function getValue(id){
			var targ = getElement(id);
			var v = targ.value;
			if(v == '' || v == targ.defaultValue){
				return '';
			}
			return v;
		}
		function checkCityName(targ){
			var targ = getElement(targ);
			if(isNull(targ)){
				targ.className = 'f_error';
				mod.warn(targ, '请输入出发城市');
				return false;
			}
			targ.className = '';
			return targ.value;
		}
		var param = {
				dcity: '',		//出发城市
				acity: '',		//到达城市
				sprice: 0,		//起始价格
				eprice: 0		//截止价格  (两个价格必需成对出现,否则无效)
			};
		var showdata = content.ctripUnion.datas.config.showData;
		function setUrl(){
		 var c1 = getElement('text1'), c2 = getElement('pkgdestCity');
			mod.warn();
			if(isNull(c1)){
				c1.className = 'f_error';
				mod.warn(c1, '请选择您的出发地！');
				return false;
			}else{
				c1.className = '';
				param.dcity = getElement('text1ID').value;
			}
			if(c2){
				if(!isNull(c2)){
				//	param.acity = getElement('pkgdestCityID').value;
					param.acity = escape(getElement('pkgdestCity').value);
				}else{
					c2.className = 'f_error';
					mod.warn(c2, '请选择您的目的地！');
					return false;
				}
			}
			
			//=价格范围
			if(getElement('holidayPrice')){
				var p1 = getElement('holidayPrice'), p2 = getElement('holidayPrice2');
				var p1v = getValue(p1), p2v = getValue(p2);
				if(p1v && !p1v.isInt()){
					p1.className = 'f_error';
					mod.warn(p1,'请输入一个整数价格！');
					return false;
				}
				if(p2v && !p2v.isInt()){
					p2.className = 'f_error';
					mod.warn(p2, '请输入一个整数价格！');
					return false;
				}
				if(p1v > p2v){
					param.sprice = p1v;
					param.eprice = p2v;
				}else{
					param.sprice = p1v;
					param.eprice = p2v;
				}
			}
			var _param = '', a;
			for(a in param){
				if(param[a]){
					_param += '&'+ a + '=' + param[a];
				}
			}
			content.url = _url + _param;
		    return  content.url
		}
	if( !$("#ctrip_union_product_20121221")[0] || $("#ctrip_union_product_20121221")[0]){
		content.url=setUrl()
		content.getJSON();
		}
		
		$('#holidaysubmit',doc).bind('click', function(){
			//text1 pkgdestCity
			  if( $("#showDataList")[0]){
		    var showDataC=union.data.config.showData;
		    }else{ 
		    var showDataC=content.ctripUnion.datas.config.showData;   
		    }
		     var config=content.ctripUnion.datas.config
		     var bootBoxHeight =38;
			 var searchBoxHeight=content.getSearchBoxHeight(doc,config)
			   if((config.pageHeight-searchBoxHeight-bootBoxHeight<content.minContentHeight && content.ctripUnion.datas.config.showData) ||(config.pageWidth<270)){ 
			     // content.ctripUnion.datas.config.showData=false
				   showDataC=false;  
			   }
		   if(showDataC ){
			      content.url=setUrl()
				  
				  content.getJSON();
			}else{
				//=非AJAX请求
		       setUrl()
				//var holidayurl = "http://vacations.ctrip.com/booking/Preview/PkgSearchResult2012.aspx?SearchType=D&FilterPara=";
				var holidayurl="http://vacations.ctrip.com/booking/Preview/PkgSearchResult2012.aspx?SearchType=U"
                if(param.dcity) holidayurl += param.dcity;
                if(param.sprice) holidayurl += "W" + param.sprice;
                if(param.eprice) holidayurl += "X" + param.eprice;
               // if(param.acity) holidayurl += "&searchvalue=" + escape(param.acity); 
               // if(param.acity) holidayurl += "&searchText=" + escape(param.acity);
				 if(param.acity) holidayurl += "&searchvalue=" +param.acity; 
                 if(param.acity) holidayurl += "&searchText=" +param.acity;
				content.ctripUnion.redirectUrl(holidayurl);
				
			}
		});
	}

	var ctripUnion=new product({callback:init,siteCallback:function(v,d){
		content.dataType = v;
		//triggerClick(v,content.doc);
	}});
	if(window['b2b_ctrip_v2']){
		window['ctrip']=ctripUnion;
		
		content.callback=function(doc){
		    var config = content.ctripUnion.datas.config;
			
		    if(window["ctripHcallback"]){
		
				b2bContent=$("#b2b_content",doc)[0]
			    window["ctripHcallback"](b2bContent);
		    }
	    }
	}
	
	/**
	 *=内容更新后的回调函数=
	 *@param {DOM Object} iframe.document;
	 content.callback = function(doc){
		//do something
	 }
	 */

	function init(ifr,ctripUnion){
		var ifr = ifr,
			config = ctripUnion.datas.config;
		var doc = ifr.contentDocument || ifr.contentWindow.document;
		
		content.ctripUnion = ctripUnion;
		mod.init(doc,ifr);
		if(content.page) content.page = null;
		content.init(doc);
		
		ifr.height = config.pageHeight;

		if(config.siteMode == 'simple'){
			if(config.siteTypeValue == 'hotel'){
			   
				hotelValidate(doc, ctripUnion.defineData.searchOption.hotel);
			}
			if(config.siteTypeValue == 'ticket'){
				ticketValidate(doc,ctripUnion.defineData.searchOption.ticket);
			}
			if(config.siteTypeValue == 'holiday'){
				holidayValidate(doc, ctripUnion.defineData.searchOption.holiday);
			}
			
			if(config.siteTypeValue == 'group'){
			 
				groupValidate(doc, ctripUnion.defineData.searchOption.group);
			}
			
		}else{
		    if(config.siteTypeValue == 'hotel'){
			 ticketValidate(doc,ctripUnion.defineData.searchOption.ticket);
			 holidayValidate(doc, ctripUnion.defineData.searchOption.holiday);
			 groupValidate(doc, ctripUnion.defineData.searchOption.group);
			 hotelValidate(doc, ctripUnion.defineData.searchOption.hotel);
			}
			if(config.siteTypeValue == 'ticket'){
			
			 holidayValidate(doc, ctripUnion.defineData.searchOption.holiday);
			 groupValidate(doc, ctripUnion.defineData.searchOption.group);
			 hotelValidate(doc, ctripUnion.defineData.searchOption.hotel);
			 ticketValidate(doc,ctripUnion.defineData.searchOption.ticket);
			}
			if(config.siteTypeValue == 'holiday'){
			
			 groupValidate(doc, ctripUnion.defineData.searchOption.group);
			 hotelValidate(doc, ctripUnion.defineData.searchOption.hotel);
			 ticketValidate(doc,ctripUnion.defineData.searchOption.ticket);
			 holidayValidate(doc, ctripUnion.defineData.searchOption.holiday);
			}
			
			if(config.siteTypeValue == 'group'){
			
			 hotelValidate(doc, ctripUnion.defineData.searchOption.hotel);
			 ticketValidate(doc,ctripUnion.defineData.searchOption.ticket);
			 holidayValidate(doc, ctripUnion.defineData.searchOption.holiday);
			  groupValidate(doc, ctripUnion.defineData.searchOption.group);
			}
		    /* hotelValidate(doc, ctripUnion.defineData.searchOption.hotel)
			 ticketValidate(doc,ctripUnion.defineData.searchOption.ticket);
			 holidayValidate(doc, ctripUnion.defineData.searchOption.holiday);
			 groupValidate(doc, ctripUnion.defineData.searchOption.group);*/

			if (window['b2b_ctrip_v2']) {
                ctripUnion.maxHeight(doc, ctripUnion.data.config.siteTypeValue);
            }
			
		} 
	
		//if (!ctripUnion.datas.config.showData && window['b2b_ctrip_v2']) {
		if (window['b2b_ctrip_v2']) {
		    content.callback(doc);
		}
	
		//triggerClick(config.siteTypeValue,doc, ctripUnion);
	}

	function triggerClick(dtype,doc, obj){
		var ctripUnion = obj || content.ctripUnion;
		var bootBoxHeight = parseInt($("#b2b_foot", doc).offset()['height']);
	    if(content.ctripUnion.datas.config.showLogo==false){bootBoxHeight=38}
		var searchBoxHeight= content.getSearchBoxHeight(doc,ctripUnion.datas.config)
		if((ctripUnion.datas.config.showData&&content.ctripUnion.datas.config.pageHeight-searchBoxHeight-bootBoxHeight>content.minContentHeight)&&(content.ctripUnion.datas.config.pageWidth>=270)){
			switch(dtype){
				case 'hotel': $.event.trigger($('#hotelsubmit',doc), 'click');
					break;
				case 'holiday': $.event.trigger($('#holidaysubmit',doc),'click');
					break;
				case 'ticket': $.event.trigger($('#ticketSearchBt',doc),'click');
					break;
	          	case 'group' : $.event.trigger($('#groupSearchBt',doc),'click');
					break;
			}
		}
	}

}

window.onload = function(){
if(!window['cQuery']){
 var _scr = document.createElement('script');
 _scr.type = 'text/javascript';

 _scr.src = 'http://webresource.ctrip.com/code/cquery/cQuery_110421.js';
  document.body.appendChild(_scr);
 if(document.attachEvent){
  _scr.onreadystatechange = function(){
   if(_scr.readyState == 'loaded' ||  _scr.readyState=='complete'){
    _scr.onreadystatechange=null;
    init && init();
   }
  }
 }else{
  _scr.onload = init;
 }
 
}else{
 init();
}
};
//=end

})();
