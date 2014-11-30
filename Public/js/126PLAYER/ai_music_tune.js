if(NetEase==undefined){
var NetEase={};
}
NetEase.CommonShare=Class.create();
NetEase.CommonShare.prototype={
initialize:function(objName){
this.options=Object.extend(
{
},arguments[1]||{});
this.objName=objName;
this.resource={};
},
shareResource:function(type,resourceName,url){
if(UD.visitorRank<0){
showLoginDlg(UD.serverName);
return;
}
this.resource.type=type;
this.resource.name=resourceName;
this.resource.url=url;
var options=Object.extend({
title:'推荐给我的博友',
left:false,
top:false,
needCover:true
},arguments[3]||[]);
var validcodeUrl=this.genValidCodeImg();
if(!this.shareResourceLayer){
this.shareResourceLayer=jsWindowManager.createWindow('resource_share',{className:'layer-common-share',
left:options.left,top:options.top,width:410,height:310,title:options.title,needCover:options.needCover,notKeepPos:true});
RemindBean.getChummies(function(dataList){
this.chummyList=dataList;
this.shareResourceLayer.panel.innerHTML=this.jst_resource_share.processUseCache({itemList:this.chummyList,objName:this.objName,validcodeUrl:validcodeUrl});
}.bind(this));
}else{
this.shareResourceLayer.panel.innerHTML=this.jst_resource_share.processUseCache({itemList:this.chummyList,objName:this.objName,validcodeUrl:validcodeUrl});
}
this.shareResourceLayer.showWindow();
},
showNotice:function(n){
if($("commonShareObj_notice")){
$("commonShareObj_notice").innerHTML=n;
setTimeout(' $("commonShareObj_notice").innerHTML = "&nbsp;";',3000);
}
},
sendShareResource:function(){
var content=$F('common_share_msg').trim();
if(content.length>250){
dwrlog('附言不能超过250个字!',"error");
return;
}
var msgValcode=$F('commonShareObj_Valcode').trim();
if(msgValcode==undefined||msgValcode==null||msgValcode==""){
this.showNotice("请输入验证码!");
this.genValidCodeImg();
return;
}
else if(msgValcode!=undefined&&msgValcode!=null&&msgValcode!=""&&msgValcode.length<4){
this.showNotice("验证码为4位!");
this.genValidCodeImg();
return;
}
var receives=[];
if(this.chummyList&&this.chummyList.length>0){
var cbId;
for(var i=0;i<this.chummyList.length;i++){
cbId='common_share_cb_'+this.chummyList[i].userId;
if($(cbId)&&$(cbId).checked){
receives.push(this.chummyList[i].userId);
}
}
}
if(receives.length>0){
$("commonShareObj_submit_button").enable=false;
RemindBean.sendShareResource(receives,content,this.resource.name,this.resource.url,this.resource.type,msgValcode,{
callback:function(s){
$("commonShareObj_submit_button").enable=true;
if(s){
dwrlog('推荐给博友成功!','ok');
this.shareResourceLayer.hiddenWindow();
}else{
dwrlog('推荐给博友失败!','error');
}
}.bind(this),
errorHandler:function(errorString,ex){
if(ex==undefined||ex==null||ex.type=="CaptchaException"){
this.showNotice("验证码不正确!");
this.genValidCodeImg();
$("commonShareObj_submit_button").enable=true;
}
}.bind(this)
});
}else{
dwrlog("你没有选择关注的博友，不能分享!","error");
}
},
selectAll:function(){
var checked=$('common_share_selall').checked;
if(this.chummyList&&this.chummyList.length>0){
var cbId;
for(var i=0;i<this.chummyList.length;i++){
cbId='common_share_cb_'+this.chummyList[i].userId;
if($(cbId)){
$(cbId).checked=checked;
}
}
}
},
genValidCodeImg:function(){
var _iRandom=Math.floor(Math.random()*10001);
var _sId=(_iRandom+"_"+new Date().getTime()).toString();
var ss="/cap/captcha.jpgx?parentId="+encodeURIComponent(UD.hostId)+"&"+_sId;
if($("commonShareObj_valimg")){
$("commonShareObj_valimg").src=ss;
}
return ss;
},
jst_resource_share:new String('\
  <div class="left" style="margin-bottom:0;">\
   <div class="list">\
   {for item in itemList}\
    {if item.userId > 0 &&  item.userName!= null }\
        <div class="g_t_hide"><input type="checkbox" id="common_share_cb_${item.userId}"/><a href="http://${DomainMap.getParentDomain(item.userName)}/" target="_blank">${item.nickname|default:""|escape}</a></div> \
     {/if}\
   {/for}\
   </div>\
   <div class="ctrl">\
    <input type="checkbox" id="common_share_selall" onclick="${objName}.selectAll();"><label for="common_share_selall" onclick="${objName}.selectAll();">全选</label>\
   </div>\
  </div>\
  <div class="right">\
   <div>附言：</div>\
   <div><textarea id="common_share_msg"></textarea></div>\
   <div class="op" style="text-align:left">\
      <div class="g_t_14" ><span style="line-height:24px;">验证码：</span><input  style="width:50px;vertical-align:middle;padding:3px 2px 4px" value="" maxlength="4" id="commonShareObj_Valcode" /><img id="commonShareObj_valimg" class ="valcodeimg" style="vertical-align:middle;margin-left:5px;cursor:pointer;"  alt="验证码" src="${validcodeUrl}" title="点击刷新验证码" onclick="this.src=commonShareObj.genValidCodeImg();"/>\
      <input id="commonShareObj_submit_button" class="btncm btnok" type="button" value="确定" onclick="${objName}.sendShareResource()" style="vertical-align:middle;width:60px;"/>\
      </div>\
      <p style="margin-top:5px"><span id="commonShareObj_notice" style="color:red">&nbsp;</span></p>\
   </div>\
  </div><br class="g_p_clear" />\
 ')
}
var commonShareObj=new NetEase.CommonShare('commonShareObj');
if(!Control)var Control={};
Control.Slider=Class.create();
Control.Slider.prototype={
initialize:function(handle,track,options){
var slider=this;
if(handle instanceof Array){
this.handles=handle.collect(function(e){return $(e)});
}else{
this.handles=[$(handle)];
}
this.track=$(track);
this.options=options||{};
this.axis=this.options.axis||'horizontal';
this.increment=this.options.increment||1;
this.step=parseInt(this.options.step||'1');
this.range=this.options.range||$R(0,1);
this.value=0;
this.values=this.handles.map(function(){return 0});
this.firstInits=[];
this.spans=this.options.spans?this.options.spans.map(function(s){return $(s)}):false;
this.options.startSpan=$(this.options.startSpan||null);
this.options.endSpan=$(this.options.endSpan||null);
this.restricted=this.options.restricted||false;
this.maximum=this.options.maximum||this.range.end;
this.minimum=this.options.minimum||this.range.start;
this.alignX=parseInt(this.options.alignX||'0');
this.alignY=parseInt(this.options.alignY||'0');
this.trackLength=this.maximumOffset()-this.minimumOffset();
this.handleLength=this.isVertical()?
(this.handles[0].offsetHeight!=0?
this.handles[0].offsetHeight:this.handles[0].style.height.replace(/px$/,"")):
(this.handles[0].offsetWidth!=0?this.handles[0].offsetWidth:
this.handles[0].style.width.replace(/px$/,""));
this.active=false;
this.dragging=false;
this.disabled=false;
if(this.options.disabled)this.setDisabled();
this.allowedValues=this.options.values?this.options.values.sortBy(Prototype.K):false;
if(this.allowedValues){
this.minimum=this.allowedValues.min();
this.maximum=this.allowedValues.max();
}
this.eventMouseDown=this.startDrag.bindAsEventListener(this);
this.eventMouseUp=this.endDrag.bindAsEventListener(this);
this.eventMouseMove=this.update.bindAsEventListener(this);
this.handles.each(function(h,i){
i=slider.handles.length-1-i;
slider.setValue(parseFloat(
(slider.options.sliderValue instanceof Array?
slider.options.sliderValue[i]:slider.options.sliderValue)||
slider.range.start),i);
Element.makePositioned(h);
Event.observe(h,"mousedown",slider.eventMouseDown);
});
Event.observe(this.track,"mousedown",this.eventMouseDown);
this.initialized=true;
},
dispose:function(){
var slider=this;
Event.stopObserving(this.track,"mousedown",this.eventMouseDown);
Event.stopObserving(document,"mouseup",this.eventMouseUp);
Event.stopObserving(document,"mousemove",this.eventMouseMove);
this.handles.each(function(h){
Event.stopObserving(h,"mousedown",slider.eventMouseDown);
});
},
setDisabled:function(){
this.disabled=true;
},
setEnabled:function(){
this.disabled=false;
},
getNearestValue:function(value){
if(this.allowedValues){
if(value>=this.allowedValues.max())return(this.allowedValues.max());
if(value<=this.allowedValues.min())return(this.allowedValues.min());
var offset=Math.abs(this.allowedValues[0]-value);
var newValue=this.allowedValues[0];
this.allowedValues.each(function(v){
var currentOffset=Math.abs(v-value);
if(currentOffset<=offset){
newValue=v;
offset=currentOffset;
}
});
return newValue;
}
if(value>this.range.end)return this.range.end;
if(value<this.range.start)return this.range.start;
return value;
},
setValue:function(sliderValue,handleIdx){
if(!this.active){
this.activeHandle=this.handles[handleIdx];
this.activeHandleIdx=handleIdx;
this.updateStyles();
}
if(this.values[handleIdx]==sliderValue&&this.firstInits[handleIdx]){
return;
}
this.firstInits[handleIdx]=true;
handleIdx=handleIdx||this.activeHandleIdx||0;
if(this.initialized&&this.restricted){
if((handleIdx>0)&&(sliderValue<this.values[handleIdx-1]))
sliderValue=this.values[handleIdx-1];
if((handleIdx<(this.handles.length-1))&&(sliderValue>this.values[handleIdx+1]))
sliderValue=this.values[handleIdx+1];
}
sliderValue=this.getNearestValue(sliderValue);
this.values[handleIdx]=sliderValue;
this.value=this.values[0];
this.handles[handleIdx].style[this.isVertical()?'top':'left']=
this.translateToPx(sliderValue);
this.drawSpans();
if(!this.dragging||!this.event)this.updateFinished();
},
setValueBy:function(delta,handleIdx){
this.setValue(this.values[handleIdx||this.activeHandleIdx||0]+delta,
handleIdx||this.activeHandleIdx||0);
},
translateToPx:function(value){
return Math.round(((this.trackLength-this.handleLength)/(this.range.end-this.range.start))*(value-this.range.start))+"px";
},
translateToValue:function(offset){
return((offset/(this.trackLength-this.handleLength))*(this.range.end-this.range.start))+this.range.start;
},
getRange:function(range){
var v=this.values.sortBy(Prototype.K);
range=range||0;
return $R(v[range],v[range+1]);
},
minimumOffset:function(){
return(this.isVertical()?this.alignY:this.alignX);
},
maximumOffset:function(){
return(this.isVertical()?
(this.track.offsetHeight!=0?this.track.offsetHeight:
this.track.style.height.replace(/px$/,""))-this.alignY:
(this.track.offsetWidth!=0?this.track.offsetWidth:
this.track.style.width.replace(/px$/,""))-this.alignX);
},
isVertical:function(){
return(this.axis=='vertical');
},
drawSpans:function(){
var slider=this;
if(this.spans)
$R(0,this.spans.length-1).each(function(r){slider.setSpan(slider.spans[r],slider.getRange(r))});
if(this.options.startSpan)
this.setSpan(this.options.startSpan,
$R(0,this.values.length>1?this.getRange(0).min():this.value));
if(this.options.endSpan)
this.setSpan(this.options.endSpan,
$R(this.values.length>1?this.getRange(this.spans.length-1).max():this.value,this.maximum));
},
setSpan:function(span,range){
if(this.isVertical()){
span.style.top=this.translateToPx(range.start);
span.style.height=this.translateToPx(range.end-range.start+this.range.start);
}else{
span.style.left=this.translateToPx(range.start);
span.style.width=this.translateToPx(range.end-range.start+this.range.start);
}
},
updateStyles:function(){
if(this.options.hasSelectCss){
this.handles.each(function(h){Element.removeClassName(h,'selected')});
Element.addClassName(this.activeHandle,'selected');
}
},
startDrag:function(event){
if(Event.isLeftClick(event)){
if(!this.disabled){
this.active=true;
var handle=Event.element(event);
var pointer=[Event.pointerX(event),Event.pointerY(event)];
if(handle==this.track||handle.parentNode==this.track){
var offsets=Position.cumulativeOffset(this.track);
this.event=event;
this.setValue(this.translateToValue(
(this.isVertical()?pointer[1]-offsets[1]:pointer[0]-offsets[0])-(this.handleLength/2)
));
var offsets=Position.cumulativeOffset(this.activeHandle);
this.offsetX=(pointer[0]-offsets[0]);
this.offsetY=(pointer[1]-offsets[1]);
}else{
while((this.handles.indexOf(handle)==-1)&&handle.parentNode)
handle=handle.parentNode;
if(this.handles.indexOf(handle)!=-1){
this.activeHandle=handle;
this.activeHandleIdx=this.handles.indexOf(this.activeHandle);
this.updateStyles();
var offsets=Position.cumulativeOffset(this.activeHandle);
this.offsetX=(pointer[0]-offsets[0]);
this.offsetY=(pointer[1]-offsets[1]);
}
}
}
Event.stop(event);
Event.observe(document,"mouseup",this.eventMouseUp);
Event.observe(document,"mousemove",this.eventMouseMove);
}
},
update:function(event){
if(this.active){
if(!this.dragging)this.dragging=true;
this.draw(event);
if(navigator.appVersion.indexOf('AppleWebKit')>0)window.scrollBy(0,0);
Event.stop(event);
}
},
draw:function(event){
var pointer=[Event.pointerX(event),Event.pointerY(event)];
var offsets=Position.cumulativeOffset(this.track);
pointer[0]-=this.offsetX+offsets[0];
pointer[1]-=this.offsetY+offsets[1];
this.event=event;
this.setValue(this.translateToValue(this.isVertical()?pointer[1]:pointer[0]));
if(this.initialized&&this.options.onSlide)this.options.onSlide(this.values.length>1?this.values:this.value,this);
},
endDrag:function(event){
if(this.active&&this.dragging){
this.finishDrag(event,true);
Event.stop(event);
}
this.active=false;
this.dragging=false;
},
finishDrag:function(event,success){
this.active=false;
this.dragging=false;
this.updateFinished();
},
updateFinished:function(){
if(this.initialized&&this.options.onChange)
this.options.onChange(this.values.length>1?this.values:this.value,this);
this.event=null;
}
}
var MusicHelper={
stringMap:{},
testNset:function(id,string){
if(this.stringMap[id]!=string){
this.stringMap[id]=string;
var div=$(id);
if(div==null){
return true;
}
if(div.innerHTML!=undefined){
div.innerHTML=string==null?'':string;
}
return true;
}
return false;
},
get:function(id){
return this.stringMap[id];
},
test:function(id,value){
if(this.stringMap[id]!=value){
this.stringMap[id]=value;
return true;
}
return false;
},
reset:function(id){
this.stringMap[id]=null;
},
resetAll:function(){
this.stringMap={};
},
toggle:function(key){
this.stringMap[key]=this.stringMap[key]?false:true;
return this.stringMap[key];
},
defaultString:function(str,defaultStr){
if(str==null){
return defaultStr==null?'':defaultStr;
}
return str;
},
isBlank:function(str){
return(str==null)||((''+str).trim()=='');
},
formatTime:function(sec){
sec=sec>0?sec:0;
return this._toDigit(sec/60)+":"+this._toDigit(sec%60);
},
_toDigit:function(num){
num-=num%1;
return(num<10)?"0"+num:""+num;
},
isVaildMusic:function(url,onlyMp3){
if(url!=null&&(onlyMp3&&/^(.+)\.mp3/i.test(url))||(!onlyMp3&&/^(.+)\.mp3|mp4|mp3pro|mp2|mp1|mpa|m4a|wma|wav|au|mid|midi|rmi/i.test(url))){
if(url.indexOf("mp3.blog.163.com")>=0||url.indexOf("mp3.bimg.126.net")>=0)return true;
if(url.indexOf("blog.163.com")>=0)return false;
return true;
}
return false;
},
isListEmpty:function(list){
try{
if(list==null||list.length==0){
return true;
}
}catch(e){list=null;return true;}
return false;
},
switchButton:function(arrayIds,selectId){
arrayIds.each(function(id){
if($(id)){
if(id==selectId){
Element.show(id);
}else{
Element.hide(id);
}
}
});
},
toggleButton:function(idOn,idOff,isOn){
var key="toggle|"+idOn+"|"+idOff;
if(this.test(key,isOn)){
if($(idOff))
$(idOff).style.display=isOn?"none":"";
if($(idOn))
$(idOn).style.display=isOn?"":"none";
}
},
applyOverCss:function(id,show,className){
try{
if(show){
var reg=new RegExp("\\b"+className+"\\b","ig");
if(!reg.test($(id).className)){
$(id).className+=" "+className;
}
}else{
var reg=new RegExp("\\b"+className+"\\b","ig");
$(id).className=$(id).className.replace(reg,"");
delete reg;
}
}catch(e){}
},
toggleOverCss:function(id,remove,update){
this.applyOverCss(id,false,remove);
this.applyOverCss(id,true,update);
},
previewImg:function(id,target){
try{
$(target).src=$(id).value;
}catch(ex){}
},
parseParam:function(){
var params={};
var s=window.location.search;
if(s){
s=s.replace(/^\?/,'').replace(/\/$/,'');
var ps=s.split('&');
var t;
for(var i=0;i<ps.length;i++){
t=ps[i].split('=');
params[t[0]]=t[1]||'';
}
}
return params;
},
clearTask:function(task){
if(task){
window.clearTimeout(task);
window.clearInterval(task);
task=null;
}
},
randomList:function(list){
if(list==null)return;
var len=list.length;
for(var i=0;i<len;i++){
var j=i+Math.floor(Math.random()*(len-i));
var item=list[i];
list[i]=list[j];
list[j]=item;
}
},
addListener:function(obj,event,listener){
if(!$(obj)||!event||!listener)return;
Event.observe($(obj),event,listener);
},
setCookieValue:function(name,value,hours,path,domain,secure){
var str=escape(name)+"="+escape(value);
if(hours){
var nextTime=new Date();
nextTime.setHours(nextTime.getHours()+hours);
str+=";expires="+nextTime.toGMTString();
}
if(path)
str+=";path="+path;
if(domain)
str+=";domain="+domain;
if(secure)
str+=";secure";
document.cookie=str;
},
getCookieValue:function(name){
var pattern="(^|;)\\s*"+escape(name)+"=([^;]+)";
var m=document.cookie.match(pattern);
if(m&&m[2]){
return unescape(m[2]);
}else{
return null;
}
},
clearCookieValue:function(name,path,domain){
this.setCookieValue(name,"",-1,path,domain);
},
isIE:function(){
return(navigator.appVersion.indexOf("MSIE")!=-1)?true:false;
},
getIEVersion:function(){
var iVerNo=0;
var sVer=navigator.userAgent;
if(sVer.indexOf("MSIE")>-1){
var sVerNo=sVer.split(";")[1];
sVerNo=sVerNo.replace("MSIE","");
iVerNo=parseFloat(sVerNo);
}
return iVerNo;
}
}
var CommonPageCtrl=Class.create();
CommonPageCtrl.prototype={
initialize:function(pageSize,allSize){
this.pageSize=pageSize||0;
this.allSize=allSize||0;
this.curPage=0;
},
getCurPage:function(p,allSize){
allSize=allSize||this.allSize;
p=p||0;
if(allSize==0||this.pageSize==0)return[0,0];
var pages=(allSize%this.pageSize==0)?(allSize/this.pageSize):(parseInt(allSize/this.pageSize)+1);
if(p>0){
if(this.curPage+p<pages)this.curPage+=p;
else this.curPage=pages-1;
}else if(p<0){
if(this.curPage+p>=0)this.curPage+=p;
else this.curPage=0;
}
return[(this.curPage<0)?0:(this.curPage>(pages-1)?(pages-1):this.curPage),pages];
},
hasNext:function(allSize){
var pages=this.getCurPage(0,allSize);
if(this.curPage>=pages[1]){
return false;
}
return true;
},
next:function(allSize){
var pages=this.getCurPage(0,allSize);
this.curPage++;
return pages;
}
}
var SecurityPeriodicalExecuter=Class.create();
SecurityPeriodicalExecuter.prototype={
initialize:function(callback,frequency){
this.callback=callback;
this.frequency=frequency;
this.currentlyExecuting=false;
this.pid=setInterval(this._onTimerEvent.bind(this),this.frequency*1000);
},
clear:function(){
clearInterval(this.pid);
},
_onTimerEvent:function(){
if(!this.currentlyExecuting){
try{
this.currentlyExecuting=true;
this.callback();
}catch(e){}finally{
this.currentlyExecuting=false;
}
}
}
}
var MusicTask=Class.create();
MusicTask.prototype={
initialize:function(period){
this.taskList=[];
this.poller=new SecurityPeriodicalExecuter(this._statePoller.bind(this),period);
},
clear:function(){
this.poller.clear();
},
addTask:function(key,task){
if(key&&task){
var _t={key:key,task:task};
this.taskList.push(_t);
}
},
removeTask:function(key){
if(key)
this.taskList=this.taskList.reject(function(e){if(e.key==key)return true;return false;});
},
_statePoller:function(){
for(var i=0;i<this.taskList.length;i++){
if(this.taskList[i].task)
this.taskList[i].task();
}
}
}
String.prototype.trim=function(){return this.replace(/^\s+|\s+$/g,"")};
String.prototype.escape=function(){return this.replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/"/g,"&#34;").replace(/'/g,"&#39;");};
Function.prototype.delay=function(){
var __method=this,args=$A(arguments),time=args.shift(),object=args.shift();
var __callBack=function(){
return __method.apply(object,args);
}
return window.setTimeout(__callBack,time);
}
if(typeof deconcept=="undefined"){var deconcept=new Object();}if(typeof deconcept.util=="undefined"){deconcept.util=new Object();}if(typeof deconcept.SWFObjectUtil=="undefined"){deconcept.SWFObjectUtil=new Object();}deconcept.SWFObject=function(_1,id,w,h,_5,c,_7,_8,_9,_a){if(!document.getElementById){return;}this.DETECT_KEY=_a?_a:"detectflash";this.skipDetect=deconcept.util.getRequestParameter(this.DETECT_KEY);this.params=new Object();this.variables=new Object();this.attributes=new Array();if(_1){this.setAttribute("swf",_1);}if(id){this.setAttribute("id",id);}if(w){this.setAttribute("width",w);}if(h){this.setAttribute("height",h);}if(_5){this.setAttribute("version",new deconcept.PlayerVersion(_5.toString().split(".")));}this.installedVer=deconcept.SWFObjectUtil.getPlayerVersion();if(!window.opera&&document.all&&this.installedVer.major>7){deconcept.SWFObject.doPrepUnload=true;}if(c){this.addParam("bgcolor",c);}var q=_7?_7:"high";this.addParam("quality",q);this.setAttribute("useExpressInstall",false);this.setAttribute("doExpressInstall",false);var _c=(_8)?_8:window.location;this.setAttribute("xiRedirectUrl",_c);this.setAttribute("redirectUrl","");if(_9){this.setAttribute("redirectUrl",_9);}};deconcept.SWFObject.prototype={useExpressInstall:function(_d){this.xiSWFPath=!_d?"expressinstall.swf":_d;this.setAttribute("useExpressInstall",true);},setAttribute:function(_e,_f){this.attributes[_e]=_f;},getAttribute:function(_10){return this.attributes[_10];},addParam:function(_11,_12){this.params[_11]=_12;},getParams:function(){return this.params;},addVariable:function(_13,_14){this.variables[_13]=_14;},getVariable:function(_15){return this.variables[_15];},getVariables:function(){return this.variables;},getVariablePairs:function(){var _16=new Array();var key;var _18=this.getVariables();for(key in _18){_16[_16.length]=key+"="+_18[key];}return _16;},getSWFHTML:function(){var _19="";if(navigator.plugins&&navigator.mimeTypes&&navigator.mimeTypes.length){if(this.getAttribute("doExpressInstall")){this.addVariable("MMplayerType","PlugIn");this.setAttribute("swf",this.xiSWFPath);}_19="<embed type=\"application/x-shockwave-flash\" src=\""+this.getAttribute("swf")+"\" width=\""+this.getAttribute("width")+"\" height=\""+this.getAttribute("height")+"\" style=\""+this.getAttribute("style")+"\"";_19+=" id=\""+this.getAttribute("id")+"\" name=\""+this.getAttribute("id")+"\" ";var _1a=this.getParams();for(var key in _1a){_19+=[key]+"=\""+_1a[key]+"\" ";}var _1c=this.getVariablePairs().join("&");if(_1c.length>0){_19+="flashvars=\""+_1c+"\"";}_19+="/>";}else{if(this.getAttribute("doExpressInstall")){this.addVariable("MMplayerType","ActiveX");this.setAttribute("swf",this.xiSWFPath);}_19="<object id=\""+this.getAttribute("id")+"\" classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" width=\""+this.getAttribute("width")+"\" height=\""+this.getAttribute("height")+"\" style=\""+this.getAttribute("style")+"\">";_19+="<param name=\"movie\" value=\""+this.getAttribute("swf")+"\" />";var _1d=this.getParams();for(var key in _1d){_19+="<param name=\""+key+"\" value=\""+_1d[key]+"\" />";}var _1f=this.getVariablePairs().join("&");if(_1f.length>0){_19+="<param name=\"flashvars\" value=\""+_1f+"\" />";}_19+="</object>";}return _19;},write:function(_20){if(this.getAttribute("useExpressInstall")){var _21=new deconcept.PlayerVersion([6,0,65]);if(this.installedVer.versionIsValid(_21)&&!this.installedVer.versionIsValid(this.getAttribute("version"))){this.setAttribute("doExpressInstall",true);this.addVariable("MMredirectURL",escape(this.getAttribute("xiRedirectUrl")));document.title=document.title.slice(0,47)+" - Flash Player Installation";this.addVariable("MMdoctitle",document.title);}}if(this.skipDetect||this.getAttribute("doExpressInstall")||this.installedVer.versionIsValid(this.getAttribute("version"))){var n=(typeof _20=="string")?document.getElementById(_20):_20;n.innerHTML=this.getSWFHTML();return true;}else{if(this.getAttribute("redirectUrl")!=""){document.location.replace(this.getAttribute("redirectUrl"));}}return false;}};deconcept.SWFObjectUtil.getPlayerVersion=function(){var _23=new deconcept.PlayerVersion([0,0,0]);if(navigator.plugins&&navigator.mimeTypes.length){var x=navigator.plugins["Shockwave Flash"];if(x&&x.description){_23=new deconcept.PlayerVersion(x.description.replace(/([a-zA-Z]|\s)+/,"").replace(/(\s+r|\s+b[0-9]+)/,".").split("."));}}else{if(navigator.userAgent&&navigator.userAgent.indexOf("Windows CE")>=0){var axo=1;var _26=3;while(axo){try{_26++;axo=new ActiveXObject("ShockwaveFlash.ShockwaveFlash."+_26);_23=new deconcept.PlayerVersion([_26,0,0]);}catch(e){axo=null;}}}else{try{var axo=new ActiveXObject("ShockwaveFlash.ShockwaveFlash.7");}catch(e){try{var axo=new ActiveXObject("ShockwaveFlash.ShockwaveFlash.6");_23=new deconcept.PlayerVersion([6,0,21]);axo.AllowScriptAccess="always";}catch(e){if(_23.major==6){return _23;}}try{axo=new ActiveXObject("ShockwaveFlash.ShockwaveFlash");}catch(e){}}if(axo!=null){_23=new deconcept.PlayerVersion(axo.GetVariable("$version").split(" ")[1].split(","));}}}return _23;};deconcept.PlayerVersion=function(_29){this.major=_29[0]!=null?parseInt(_29[0]):0;this.minor=_29[1]!=null?parseInt(_29[1]):0;this.rev=_29[2]!=null?parseInt(_29[2]):0;};deconcept.PlayerVersion.prototype.versionIsValid=function(fv){if(this.major<fv.major){return false;}if(this.major>fv.major){return true;}if(this.minor<fv.minor){return false;}if(this.minor>fv.minor){return true;}if(this.rev<fv.rev){return false;}return true;};deconcept.util={getRequestParameter:function(_2b){var q=document.location.search||document.location.hash;if(_2b==null){return q;}if(q){var _2d=q.substring(1).split("&");for(var i=0;i<_2d.length;i++){if(_2d[i].substring(0,_2d[i].indexOf("="))==_2b){return _2d[i].substring((_2d[i].indexOf("=")+1));}}}return"";}};deconcept.SWFObjectUtil.cleanupSWFs=function(){var _2f=document.getElementsByTagName("OBJECT");for(var i=_2f.length-1;i>=0;i--){_2f[i].style.display="none";for(var x in _2f[i]){if(typeof _2f[i][x]=="function"){_2f[i][x]=function(){};}}}};if(deconcept.SWFObject.doPrepUnload){if(!deconcept.unloadSet){deconcept.SWFObjectUtil.prepUnload=function(){__flash_unloadHandler=function(){};__flash_savedUnloadHandler=function(){};window.attachEvent("onunload",deconcept.SWFObjectUtil.cleanupSWFs);};window.attachEvent("onbeforeunload",deconcept.SWFObjectUtil.prepUnload);deconcept.unloadSet=true;}}if(!document.getElementById&&document.all){document.getElementById=function(id){return document.all[id];};}var getQueryParamValue=deconcept.util.getRequestParameter;var FlashObject=deconcept.SWFObject;var SWFObject=deconcept.SWFObject;
var MusicConst={
NEXT_PLAY_DELAY:200,
ERROR_WAIT_DELAY:2000,
MAX_WAIT_TIME:6000,
MAX_BUFFER_TIME:6000,
MIN_MUSIC_LEN:25,
MAX_RETRY_COUNT:5,
MUSIC_CHECK_PERIOD:0.3,
MUSIC_TASK_PERIOD:0.5,
TEST_ALBUM:'test',
DIY_ALBUM:'diy',
SYS_MUSIC:1,
DIY_MUSIC:2,
SEARCH_MUSIC:-1,
CUSTOM_MUSIC:-2
};
MusicConst.useCom=MusicHelper.isIE();
MusicConst.useFlash=!MusicConst.useCom;
if(MusicConst.useFlash)
MusicConst.MUSIC_CHECK_PERIOD=0.5;
var MediaPlayer=Class.create();
MediaPlayer.prototype={
initialize:function(objId){
this.objId=objId;
this.player_id='$_neteasemusicobject';
this.psChangeHanlders=[];
this.osChangeHanlders=[];
this.md=document.createElement('div');
var mdiv=$('musicbox_div');
if(mdiv!=undefined){
mdiv.appendChild(this.md);
}else{
document.body.appendChild(this.md);
}
this.versionValid=false;
if(MusicConst.useCom){
this.md.style.display='none';
var musicObject=document.createElement('object');
musicObject.id=this.player_id;
this.md.appendChild(musicObject);
musicObject.classid="clsid:6BF52A52-394A-11d3-B153-00C04F79FAA6";
if(musicObject.settings!=undefined){
musicObject.settings.invokeURLs=false;
musicObject.settings.playCount=1;
musicObject.settings.autoStart=false;
musicObject.enableContextMenu=false;
musicObject.uiMode="none";
musicObject.attachEvent('PlayStateChange',this._playStateChange.bind(this));
musicObject.attachEvent('OpenStateChange',this._openStateChange.bind(this));
if(musicObject.versionInfo){
var tmpIndex=musicObject.versionInfo.indexOf(".");
var majorVersion=musicObject.versionInfo.substr(0,tmpIndex>0?tmpIndex:musicObject.versionInfo.length);
if(parseInt(majorVersion)>=7){
this.versionValid=true;
this.success=true;
}
}
}
}else{
var musicObject=new SWFObject(Const.STDomain+"/bin/MediaPlayer.swf",this.player_id,"1","1","9","#FFFFFF");
musicObject.addParam("wmode","transparent");
musicObject.addParam("allowScriptAccess","always");
musicObject.addParam("menu","false");
musicObject.addVariable("ready",this.objId+"._flashReady");
musicObject.write(this.md);
var version=deconcept.SWFObjectUtil.getPlayerVersion();
if(version.major>=9){
this.versionValid=true;
}
}
},
addPlayStateChangeHandler:function(handler){
if(handler)
this.psChangeHanlders.push(handler);
},
addOpenStateChangeHandler:function(handler){
if(handler)
this.osChangeHanlders.push(handler);
},
destory:function(){
if(this.success){
var musicObject=this.getMO();
if(musicObject&&MusicConst.useCom){
musicObject.detachEvent('PlayStateChange',this._playStateChange.bind(this));
musicObject.detachEvent('OpenStateChange',this._openStateChange.bind(this));
}
}
if(this.md)Element.remove(this.md);
},
_flashReady:function(){
if(MusicConst.useFlash&&this.versionValid){
this.success=true;
var musicPlayer=this.getMO();
if(musicPlayer){
setTimeout(function(){
musicPlayer.setPlayStateChangeListener(this.objId+"._playStateChange");
}.bind(this),100);
}
}
},
_playStateChange:function(s){
var len=this.psChangeHanlders.length;
for(var i=0;i<len;i++){
if(this.psChangeHanlders[i])
this.psChangeHanlders[i](s);
}
},
_openStateChange:function(s){
var len=this.osChangeHanlders.length;
for(var i=0;i<len;i++){
if(this.osChangeHanlders[i])
this.osChangeHanlders[i](s);
}
},
getMO:function(){
if(this.success){
return $(this.player_id);
}
return null;
},
play:function(){
var musicPlayer=this.getMO();
if(MusicConst.useCom){
if(musicPlayer&&musicPlayer.URL!=""&&musicPlayer.controls&&musicPlayer.controls.isAvailable('Play'))
musicPlayer.controls.play();
}else{
if(musicPlayer)
musicPlayer.play();
}
},
pause:function(){
var musicPlayer=this.getMO();
if(MusicConst.useCom){
if(musicPlayer&&musicPlayer.URL!=""&&musicPlayer.controls&&musicPlayer.controls.isAvailable('Pause'))
musicPlayer.controls.pause();
}else{
if(musicPlayer)
musicPlayer.pause();
}
},
stop:function(){
var musicPlayer=this.getMO();
if(MusicConst.useCom){
if(musicPlayer&&musicPlayer.controls&&musicPlayer.controls.isAvailable('Stop'))
musicPlayer.controls.stop();
}else{
if(musicPlayer)
musicPlayer.stop();
}
},
setURL:function(u){
var musicPlayer=this.getMO();
try{
if(musicPlayer&&MusicHelper.isVaildMusic(u,MusicConst.useFlash)){
if(MusicConst.useCom){
musicPlayer.URL=u;
}else{
musicPlayer.setURL(u);
}
return true;
}
}catch(ex){}
return false;
},
setVolume:function(volume){
var musicPlayer=this.getMO();
if(volume<0)
volume=0;
if(volume>100)
volume=100;
if(MusicConst.useCom){
if(musicPlayer&&musicPlayer.settings)
musicPlayer.settings.volume=volume;
}else{
if(musicPlayer)
musicPlayer.setVolume(volume);
}
},
setMute:function(mute){
var musicPlayer=this.getMO();
if(MusicConst.useCom){
if(musicPlayer&&musicPlayer.settings)
musicPlayer.settings.Mute=mute;
}else{
if(musicPlayer)
musicPlayer.setMute(mute);
}
},
isMute:function(){
var musicPlayer=this.getMO();
if(MusicConst.useCom){
if(musicPlayer&&musicPlayer.settings)
return musicPlayer.settings.Mute;
}else{
if(musicPlayer)
return musicPlayer.isMute();
}
return false;
},
getState:function(){
var musicPlayer=this.getMO();
if(MusicConst.useCom){
if(musicPlayer)
return musicPlayer.PlayState;
}else{
if(musicPlayer){
return musicPlayer.getPlayState();
}
}
return 0;
},
getOpenState:function(){
var musicPlayer=this.getMO();
if(MusicConst.useCom){
if(musicPlayer)
return musicPlayer.OpenState;
}
return 0;
},
getStateString:function(state){
var ss;
switch(state){
case-1:ss="播放错误";break;
case 0:ss="请选择曲目开始播放";break;
case 1:ss="已停止";break;
case 2:ss="已暂停";break;
case 3:ss="正在播放";break;
case 4:ss="正在快进";break;
case 5:ss="正在快退";break;
case 6:ss="缓冲";break;
case 7:ss="等待中";break;
case 8:ss="音乐结束";break;
case 9:ss="正在连接到媒体";break;
case 10:ss="准备就绪";break;
case 11:ss="重新连接";break;
default:ss="您的浏览器不支持此功能";break;
}
return ss;
},
getStatus:function(){
var musicPlayer=this.getMO();
if(MusicConst.useCom){
if(musicPlayer)
return musicPlayer.status;
}
return"";
},
getMediaBuffer:function(){
var musicPlayer=this.getMO();
if(MusicConst.useCom){
if(musicPlayer&&musicPlayer.network)
return musicPlayer.network.downloadProgress;
}else{
if(musicPlayer)
return musicPlayer.getBufferPercent();
}
return 0;
},
getMediumPos:function(){
var musicPlayer=this.getMO();
if(MusicConst.useCom){
if(musicPlayer&&musicPlayer.controls&&musicPlayer.controls.currentPosition)
return musicPlayer.controls.currentPosition;
}else{
if(musicPlayer)
return musicPlayer.getCurrentPosition();
}
return 0;
},
getMediumLength:function(){
var musicPlayer=this.getMO();
if(MusicConst.useCom){
if(musicPlayer&&musicPlayer.currentMedia)
return musicPlayer.currentMedia.duration;
}else{
if(musicPlayer)
return musicPlayer.getDuration();
}
return 0;
},
setMediumPos:function(pos){
var musicPlayer=this.getMO();
if(MusicConst.useCom){
if(musicPlayer&&musicPlayer.controls&&musicPlayer.controls.currentPosition)
musicPlayer.controls.currentPosition=pos;
}else{
if(musicPlayer)
musicPlayer.seek(pos);
}
},
setMediumPosPercent:function(percent){
var musicPlayer=this.getMO();
if(musicPlayer)
this.setMediumPos(this.getMediumLength()*percent);
},
loadNPlay:function(url){
if(this.setURL(url)){
this.play();
return true;
}else{
this.reset();
return false;
}
},
reset:function(){
this.setURL("");
this.stop();
}
};
var __trim=/(?:^\s+)|(?:\s+$)/g,
__empty=/^\s*$/,
__remap={a:{r:/\<|\>|\&|\r|\n|\s|\'|\"/g,'<':'&lt;','>':'&gt;','&':'&amp;',' ':'&nbsp;','"':'&quot;',"'":'&#39;','\n':'<br/>','\r':''}
,b:{r:/\&(?:lt|gt|amp|nbsp|#39|quot)\;|\<br\/\>/gi,'&lt;':'<','&gt;':'>','&amp;':'&','&nbsp;':' ','&#39;':"'",'&quot;':'"','<br/>':'\n'}
,c:{i:true,r:/\byyyy|yy|MM|M|dd|d|HH|H|mm|ms|ss|m|s\b/g}
,d:{r:/\'|\"/g,"'":"\\'",'"':'\\"'}};
window.U={};
window.U._$encode=function(_map,_content){
if(!_map||!_content||!_content.replace)return _content||'';
return _content.replace(_map.r,function($1){
var _result=_map[!_map.i?$1.toLowerCase():$1];
return _result!=null?_result:$1;
});
};
window.U._$escape=function(_content){
return U._$encode(__remap.a,_content);
};
var PlayList=Class.create();
PlayList.prototype={
initialize:function(){
this.play_list=[];
this.random_list=[];
this.random=false;
this.repeat=true;
MusicHelper.test("mode_random",false);
MusicHelper.test("mode_repeat",true);
},
loadList:function(play_list){
this.play_list=[];
if(play_list){
play_list.each(function(e){
e.name=U._$escape(e.name);
e.nickName=U._$escape(e.nickName);
e.author=U._$escape(e.author);
e.url=U._$escape(e.url);
this.play_list.push(e);
}.bind(this));
this._generateList();
}
this.index=0;
},
appendList:function(append_list){
if(append_list&&append_list.length>0){
var last_index=this.currentIndex();
append_list.each(function(e){this.play_list.push(e)}.bind(this));
this._generateList();
this.index=this._getIndex(last_index);
}
},
removeMusic:function(index){
var last_index=this.currentIndex();
var o_l=this.play_list.length;
this.play_list=this.play_list.reject(function(e,i){
if(i==index)return true;
return false;
}.bind(this));
if(this.play_list.length<o_l){
this._generateList();
if(index<last_index)
last_index--;
this.index=this._getIndex(last_index);
}
},
clearMusicList:function(){
this.play_list=[];
this._generateList();
this.index=0;
},
getList:function(){
return this.play_list;
},
getCount:function(){
if(this.play_list==null){
return 0;
}
return this.play_list.length;
},
get:function(index){
if(this.play_list==null||i<0||i>=this.play_list.length)
return null;
var len=this.random_list.length;
for(var i=0;i<len;i++){
if(this.random_list[i].index==index){
this.index=i;
break;
}
}
return this.play_list[this.currentIndex()];
},
current:function(){
if(this.play_list==null||this.play_list.length==0){
return null;
}
return this.play_list[this.currentIndex()];
},
currentIndex:function(){
if(this.play_list==null||this.play_list.length==0){
return 0;
}
return this.random_list[this.index].index;
},
prev:function(){
if(this.play_list==null||this.play_list.length==0){
return null;
}
this.index--;
if(this.index<0){
if(!this.repeat){
this.index++;
return null;
}
this._generateList();
this.index=this.play_list.length-1;
}
return this.play_list[this.currentIndex()];
},
next:function(){
if(this.play_list==null||this.play_list.length==0){
return null;
}
this.index++;
if(this.index>=this.play_list.length){
if(!this.repeat){
this.index--;
return null;
}
this._generateList();
this.index=0;
}
return this.play_list[this.currentIndex()];
},
hasNext:function(){
return this.play_list!=null&&(this.index<this.play_list.length-1);
},
setRandom:function(random){
if(MusicHelper.test("mode_random",random)){
this.random=random;
var last_index=this.currentIndex();
this._generateList();
this.index=this._getIndex(last_index);
}
},
setRepeat:function(repeat){
if(MusicHelper.test("mode_repeat",repeat)){
this.repeat=repeat;
}
},
getIndexByProp:function(v,prop){
if(this.play_list&&v&&prop){
for(var i=0;i<this.play_list.length;i++){
if(this.play_list[i][prop]==v){
return this._getIndex(i);
}
}
}
return 0;
},
_generateList:function(){
this.random_list=[];
var len=this.play_list.length;
for(var i=0;i<len;i++){
this.random_list[i]={index:i};
}
if(this.random){
for(var i=0;i<len;i++){
var j=i+Math.floor(Math.random()*(this.random_list.length-i));
var item=this.random_list[i];
this.random_list[i]=this.random_list[j];
this.random_list[j]=item;
}
}
},
_getIndex:function(c){
if(c==null)return null;
var len=this.random_list.length;
for(var i=0;i<len;i++){
if(this.random_list[i].index==c)
return i;
}
return 0;
}
}
var MusicSearch={
userName:null,
nickName:null,
avatar:null,
_generateRequestHeaders:function(){
if(MusicHelper.isBlank(MusicSearch.userName))return null;
if(MusicHelper.isBlank(MusicSearch.nickName))MusicSearch.nickName=MusicSearch.userName;
var headers=['request-username',encodeURIComponent(MusicSearch.userName),'request-nickname',encodeURIComponent(MusicSearch.nickName)];
if(!MusicHelper.isBlank(MusicSearch.avatar)){
headers.push('request-avatar');
headers.push(encodeURIComponent(MusicSearch.avatar));
}
return headers;
},
_isValidMusic:function(music){
return!(MusicHelper.isBlank(music.name)&&MusicHelper.isBlank(music.author));
},
getUrlListWidthDiy:function(music,callback,onfail){
if(music.musicType==MusicConst.DIY_MUSIC&&MusicHelper.isVaildMusic(music.url)){
music._urlList=[music.url];
callback(music);
return;
}
MusicSearch.getUrlList(music,callback,onfail);
},
getUrlList:function(music,callback,onfail){
MusicSearch.getUrlListDo=callback;
MusicSearch.music=music;
if(!!music.url){
music._urlList=[];
if(MusicHelper.isVaildMusic(music.url)){
music._urlList.unshift(music.url);
}
callback(music);
return;
}
var parameters={src:'blogold',username:MusicSearch.userName,type:1,limit:1,s:music.author+' '+music.name,callback:"MusicSearch.getUrlListCB"};
NetEase.LoadStaticJS.request(null,"http://s.music.163.com/search/get",parameters,null,'utf-8');
},
getUrlListCB:function(r){
MusicSearch.music._urlList=[];
if(!!r&&!!r.result&&!!r.result.songs){
var song=r.result.songs[0];
MusicSearch.music._urlList[0]=song.audio;
MusicSearch.music.cloudMusicId=song.id;
}
MusicSearch.getUrlListDo(MusicSearch.music);
},
getLrcTextWidthDiy:function(music,callback){
if(music.musicType==MusicConst.DIY_MUSIC&&music.diyMusicId!=null){
dwr.engine._execute('/dwr','DiyMusicBean','getMusicLyric',music.diyMusicId,music.diyMusicUserId,function(s){
if(!MusicHelper.isBlank(s)){
callback(s);
}else{
MusicSearch.getLrcText(music,callback);
}
});
}else{
MusicSearch.getLrcText(music,callback);
}
},
getLrcText:function(music,callback){
if(!MusicSearch._isValidMusic(music)||!music.cloudMusicId){
callback(null);
return;
}
MusicSearch.getLrcTextDo=callback;
var parameters={src:'blog',id:music.cloudMusicId,callback:"MusicSearch.getLrcTextCB"};
NetEase.LoadStaticJS.request(null,"http://music.163.com/api/song/media",parameters,null,'utf-8');
},
getLrcTextCB:function(r){
var lrcText=null;
if(!!r&&!!r.lyric){
var music=MusicSearch.music;
lrcText='[00:00.00]歌名: '+music.name+'\n[00:01.60]歌手: '+music.author+'\n'+r.lyric;
}
MusicSearch.getLrcTextDo(lrcText);
},
getMusicList:function(key,callback){
new Ajax.Request("/s/musicSearch.s",{
method:'get',
requestHeaders:MusicSearch._generateRequestHeaders(),
parameters:'n='+encodeURIComponent(key)+'&circle=1',
onComplete:function(request){
var musicList=null;
try{
musicList=eval(request.responseText);
}catch(e){}
callback(musicList);
}
})
},
getMusicCommendList:function(music,callback){
},
getMusicListenerList:function(music,callback){
},
tellUrlStat:function(urlList,index){
},
getSysMusicList:function(key,callback){
NetEase.LoadStaticJS.request(key,Const.STDomain+"/pub/music_board/"+NetEase.LoadStaticJS.genVersionByDay(1)+"/"+key,null,callback);
}
}
var MusicAlbumWrapManager=Class.create();
MusicAlbumWrapManager.prototype={
initialize:function(){
this.musicAlbumWrapList=[];
this.selected_album_wrap=null;
this.playing_album_wrap=null;
},
getAlbumWrapListByType:function(type){
var types;
if(typeof type=='string'){
types=[type];
}else{
types=type;
}
if(types.length==0)return[];
return this.musicAlbumWrapList.findAll(function(e){if(types.any(function(type){if(type==e.getType())return true;return false;}))return true;return false;});
},
getAlbumListByType:function(type){
var types;
if(typeof type=='string'){
types=[type];
}else{
types=type;
}
var returns=[];
if(types.length>0)
this.musicAlbumWrapList.each(function(e){if(types.any(function(type){if(type==e.getType())return true;return false;}))returns.push(e.getAlbum())});
return returns
},
getAlbumWrapById:function(id){
return this.musicAlbumWrapList.detect(function(e){if(e.getId()==id)return true;return false;});
},
getFirstAlbumWrap:function(){
return this.musicAlbumWrapList[0];
},
addAlbumWrapList:function(itemList){
if(!itemList)return;
this.musicAlbumWrapList=this.musicAlbumWrapList.concat(itemList);
},
removeAlbumWrap:function(id){
this.musicAlbumWrapList=this.musicAlbumWrapList.reject(function(e){if(e.getId()==id)return true;return false;});
},
selectAlbumWrap:function(id){
this.selected_album_wrap=this.getAlbumWrapById(id);
return this.selected_album_wrap;
},
playAlbumWrap:function(albumWrap){
this.playing_album_wrap=albumWrap;
return this.playing_album_wrap;
},
getSelectedAlbumWrap:function(){
return this.selected_album_wrap;
},
getSelectedAlbumWrapType:function(){
return this.selected_album_wrap?this.selected_album_wrap.getType():null;
},
getPlayingAlbumWrap:function(){
return this.playing_album_wrap;
},
getPlayingAlbumWrapType:function(){
return this.playing_album_wrap?this.playing_album_wrap.getType():null;
},
isAlbumWrapSelected:function(id){
return this.selected_album_wrap&&this.selected_album_wrap.getId()==id;
},
isAlbumWrapPlaying:function(id){
return this.playing_album_wrap&&this.playing_album_wrap.getId()==id;
},
isPlayingSelectedAlbumWrap:function(){
return this.selected_album_wrap==this.playing_album_wrap;
},
canSelectedAlbumAddMusic:function(){
var type=this.getSelectedAlbumWrapType();
return type==$MusicAlbumType.USER||type==$MusicAlbumType.TEST;
},
canSelectedAlbumRemoveMusic:function(){
var type=this.getSelectedAlbumWrapType();
return type!=$MusicAlbumType.YC;
},
canSelectedAlbumClearMusic:function(){
var type=this.getSelectedAlbumWrapType();
return type==$MusicAlbumType.TEST;
},
canSelectedAlbumRemove:function(){
var album=this.getSelectedAlbumWrap();
return album&&album.getType()==$MusicAlbumType.USER&&album.getId()!=0;
},
canSelectedAlbumCollect:function(){
var album=this.getSelectedAlbumWrap();
return album&&album.getType()==$MusicAlbumType.USER&&album.getId()!=0;
}
}
var IMusicAlbumWrap=Class.create();
IMusicAlbumWrap.prototype={
initialize:function(musicAlbum){
this.musicAlbum=musicAlbum;
this.albumId=this.musicAlbum.id;
this.musicList=null;
},
getId:function(){
return this.albumId;
},
getName:function(){
return this.musicAlbum.name;
},
getAlbum:function(){
return this.musicAlbum;
},
getMusicList:function(callback,refresh){
if(!callback)return this.musicList;
if(this.musicList&&!refresh){
callback(this.musicList);
}else{
this._loadMusic(callback);
}
},
setMusicList:function(itemList){
this._convertMusicList(itemList);
this.musicList=itemList;
},
getMusic:function(musicId){
return this._getMusic(musicId);
},
addMusicList:Prototype.emptyFunction(),
removeMusic:Prototype.emptyFunction(),
clearMusicList:Prototype.emptyFunction(),
_loadMusic:Prototype.emptyFunction(),
_convertMusicList:function(itemList,ext){
if(itemList!=null){
for(var i=0,l=itemList.length;i<l;i++){
var e=itemList[i];
if(e){
Object.extend(e,ext||{})
}
}
}
},
_getMusic:function(id){
if(this.musicList)
return this.musicList.detect(function(e){if(e.id==id)return true;return false;});
return null;
},
_deleteMusic:function(id){
var music;
if(this.musicList)
this.musicList=this.musicList.reject(function(e){if(e.id==id){music=e;return true;}return false;});
return music;
},
_clearMusic:function(){
this.musicList=[];
},
_appendMusicList:function(itemList){
if(!itemList)return;
this.musicList=this.musicList||[];
this.musicList=this.musicList.concat(itemList);
},
_detectDuplicate:function(e){
if(!this.musicList||this.musicList.length==0)return null;
var _duplicate=null;
this.musicList.any(function(o){
if(this._isMusicSame(o,e)){
_duplicate=o;
return true;
}
return false;
}.bind(this));
return _duplicate;
},
_isMusicSame:function(o1,o2){
return o1!=null&&o2!=null&&(o1.name==o2.name&&o1.author==o2.author);
}
};
function $registerMusicAlbum(musicAlbumPrototype,type){
Object.extend(musicAlbumPrototype,{
getType:function(){
return type;
}
});
}
var UserMusicAlbumWrap=Class.create();
Object.extend(Object.extend(UserMusicAlbumWrap.prototype,IMusicAlbumWrap.prototype),{
addMusicList:function(itemList,callback){
this._convertMusicList(itemList);
var _toPlayId=null;
itemList=itemList.reject(function(e){
var _d=this._detectDuplicate(e);
if(_d){
_toPlayId=_toPlayId?_toPlayId:_d.id;
return true;
}
return false;
}.bind(this));
if(itemList.length==0){
dwrlog('专辑已经存在该音乐!','info');
if(callback)
callback(itemList,_toPlayId);
}else{
var _musicNameList=[];
var _musicAuthorList=[];
var _musicUrlList=[];
var _musicTypeList=[];
var _musicLrcList=[];
itemList.each(function(e){
_musicNameList.push(MusicHelper.defaultString(e.name));
_musicAuthorList.push(MusicHelper.defaultString(e.author));
_musicUrlList.push(e.url);
_musicTypeList.push(MusicHelper.defaultString(e.musicType,MusicConst.SYS_MUSIC));
_musicLrcList.push(e.lrc);
});
_toPlayId=itemList[0].id;
MusicBean.addMusicList(_musicNameList,_musicAuthorList,
_musicUrlList,_musicTypeList,_musicLrcList,this.albumId,{
callback:function(_itemList){
if(MusicHelper.isListEmpty(_itemList)){
dwrlog('添加音乐失败!','error')
}else{
_itemList.each(
function(_e){
var _o=itemList.detect(this._isMusicSame.bind(this,_e));
if(_o){
_e.haveCircle=_o.haveCircle;
_e.circleMusic=_o.circleMusic;
_e.wapId=_o.wapId;
_e.duration=_o.duration;
}
}.bind(this)
);
this._appendMusicList(_itemList);
}
if(callback)callback(_itemList,_toPlayId);
}.bind(this),
errorHandler:function(errorString,ex){
dwrlog('添加音乐失败!','error');
}.bind(this)
}
);
}
},
removeMusic:function(musicId,callback){
MusicBean.deleteMusic(musicId,this.albumId,function(s){
var music=null;
if(s){
music=this._deleteMusic(musicId);
}else{
dwrlog('删除音乐失败!','error');
}
if(callback)
callback(s,music);
}.bind(this));
},
_loadMusic:function(callback){
MusicBean.getMusicListByAlbumId(this.albumId,function(_itemList){
this._convertMusicList(_itemList);
this.musicList=_itemList;
callback(this.musicList);
}.bind(this));
}
}
);
var BoardMusicAlbumWrap=Class.create();
Object.extend(Object.extend(BoardMusicAlbumWrap.prototype,IMusicAlbumWrap.prototype),{
removeMusic:function(musicId,callback){
var music=this._deleteMusic(musicId);
if(callback)
callback(true,music);
},
_loadMusic:function(callback){
MusicTrackBean.getMusicChartList(this.albumId,function(_itemList){
this._convertMusicList(_itemList,{isBoard:true});
this.musicList=_itemList;
callback(this.musicList);
}.bind(this));
}
}
);
var TestMusicAlbumWrap=Class.create();
Object.extend(Object.extend(TestMusicAlbumWrap.prototype,IMusicAlbumWrap.prototype),{
addMusicList:function(itemList,callback){
this._convertMusicList(itemList);
var _toPlayId=null;
itemList=itemList.reject(function(e){
var _d=this._detectDuplicate(e);
if(_d){
_toPlayId=_toPlayId?_toPlayId:_d.id;
return true;
}
return false;
}.bind(this));
if(itemList.length==0){
dwrlog('专辑已经存在该音乐!','info');
}else{
var _idList=[];
var _musicNameList=[];
var _musicAuthorList=[];
var _musicUrlList=[];
var _musicTypeList=[];
var i=0;
itemList.each(function(e){
if(MusicHelper.isBlank(e.id))
e.id="-"+new Date().getTime()+(i++);
e.isBoard=true;
_idList.push(e.id);
_musicNameList.push(MusicHelper.defaultString(e.name));
_musicAuthorList.push(MusicHelper.defaultString(e.author));
_musicUrlList.push(e.url);
_musicTypeList.push(MusicHelper.defaultString(e.musicType,MusicConst.SYS_MUSIC));
});
this._appendMusicList(itemList);
_toPlayId=itemList[0].id;
MusicBean.logAddAuditionList(_idList,_musicNameList,_musicAuthorList,
_musicUrlList,_musicTypeList,{timeout:2000});
}
if(callback)
callback(itemList,_toPlayId);
},
removeMusic:function(musicId,callback){
var music=this._deleteMusic(musicId);
if(music){
MusicBean.logDeleteAudition(musicId,{timeout:2000});
}
if(callback)
callback(true,music);
},
clearMusicList:function(callback){
var _t=this.musicList?this.musicList.length:0;
MusicBean.logClearAuditionList(function(s){
if(_t==0)s=true;
if(s){
this._clearMusic();
}else{
dwrlog('清空音乐列表失败!','error');
}
if(callback)
callback(s);
}.bind(this));
},
_loadMusic:function(callback){
var _callback=function(_itemList){
this._convertMusicList(_itemList,{isBoard:true});
this.musicList=_itemList;
callback(this.musicList);
}.bind(this);
MusicBean.getAuditionList({
callback:_callback,
errorHandler:_callback
});
}
}
);
var YCMusicAlbumWrap=Class.create();
Object.extend(Object.extend(YCMusicAlbumWrap.prototype,IMusicAlbumWrap.prototype),{
_loadMusic:function(callback){
DiyMusicBean.getMD5MusicList(0,0,function(_itemList){
this._convertMusicList(_itemList);
this.musicList=_itemList;
callback(this.musicList);
}.bind(this));
},
_convertMusicList:function(itemList){
if(itemList!=null){
itemList.each(function(e){
Object.extend(e,{author:e.artist,url:e.listenUrl,musicType:MusicConst.DIY_MUSIC,diyMusicId:e.id,diyMusicUserId:e.userId,isDiy:true})
});
}
}
}
);
var NavigatorMusicAlbmWrap=Class.create();
Object.extend(Object.extend(NavigatorMusicAlbmWrap.prototype,IMusicAlbumWrap.prototype),{
removeMusic:function(musicId,callback){
var music=this._deleteMusic(musicId);
if(music)
MusicTrackBean.voteMusicFavour(music.name,music.author,false);
if(callback)
callback(true,music);
},
_loadMusic:function(callback){
this.musicList=this._loadPageData();
if(this.musicList&&this.musicList.length>0){
callback(this.musicList);
return;
}
this.offsetIndex=(this.offsetIndex==null)?0:(this.offsetIndex+1)
MusicTrackBean.getMusicNavigatorList(this.offsetIndex,function(dataList){
if(dataList){
MusicHelper.randomList(dataList);
this._convertMusicList(dataList,{isBoard:true});
this.musicListAll=dataList;
this.musicPageCtrl=new CommonPageCtrl(20,dataList.length);
}else{
this.musicListAll=null;
this.musicPageCtrl=null;
}
this.musicList=this._loadPageData();
callback(this.musicList);
}.bind(this));
},
_loadPageData:function(){
if(this.musicPageCtrl&&this.musicPageCtrl.hasNext()){
var curPage=this.musicPageCtrl.next();
return this.musicListAll.slice(this.musicPageCtrl.pageSize*curPage[0],this.musicPageCtrl.pageSize*(curPage[0]+1));
}
return null;
}
}
);
var $MusicAlbumType={
USER:'user',BOARD:'board',TEST:'test',YC:'yc',NAV:'nav'
}
$registerMusicAlbum(UserMusicAlbumWrap.prototype,$MusicAlbumType.USER);
$registerMusicAlbum(BoardMusicAlbumWrap.prototype,$MusicAlbumType.BOARD);
$registerMusicAlbum(TestMusicAlbumWrap.prototype,$MusicAlbumType.TEST);
$registerMusicAlbum(YCMusicAlbumWrap.prototype,$MusicAlbumType.YC);
$registerMusicAlbum(NavigatorMusicAlbmWrap.prototype,$MusicAlbumType.NAV);
if(typeof MusicTrackBean=='undefined')
var MusicTrackBean={}
MusicTrackBean._path='/s-d';
MusicTrackBean.voteMusicRank=function(id,rank,callback){
if(UD.visitorName==null||UD.visitorRank<MusicConst.RANK_GUEST||UD.hostName==null)return;
dwr.engine.setRpcType(dwr.engine.ScriptTag);
dwr.engine._execute('http://api.blog.163.com/'+UD.hostName+'/dwr','MusicBeanNew','voteMusicRank',id,rank,{
callback:callback,
errorHandler:function(){if(callback)callback(false);},
headers:{'request-username':UD.visitorName},
httpMethod:"GET"
});
dwr.engine.setRpcType(dwr.engine.XMLHttpRequest);
}
MusicTrackBean.addCollectUser=function(names,authors,callback){
if(UD.visitorName==null||UD.visitorRank<MusicConst.RANK_GUEST)return;
dwr.engine._execute(MusicTrackBean._path,'MusicTrackBean','addCollectUser',names,authors,{
callback:callback,
errorHandler:function(){if(callback)callback(false);},
headers:{'request-username':UD.visitorName}
});
}
MusicTrackBean.voteMusicFavour=function(name,author,favour,callback){
if(UD.visitorName==null||UD.visitorRank<MusicConst.RANK_GUEST)return;
dwr.engine._execute(MusicTrackBean._path,'MusicTrackBean','voteMusicFavour',name,author,favour,{
callback:callback,
errorHandler:function(){if(callback)callback(false);},
headers:{'request-username':UD.visitorName}
});
}
MusicTrackBean.postMusicCircleComment=function(name,author,title,content,callback){
if(UD.visitorName==null||UD.visitorRank<MusicConst.RANK_GUEST)return;
dwr.engine._execute(MusicTrackBean._path,'MusicTrackBean','postMusicCircleComment',name,author,title,content,{
callback:callback,
errorHandler:function(){if(callback)callback(false);},
headers:{'request-username':UD.visitorName}
});
}
MusicTrackBean.setMusicAuthorFavour=function(likes,dislikes,callback){
if(UD.visitorName==null||UD.visitorRank<MusicConst.RANK_GUEST)return;
dwr.engine._execute(MusicTrackBean._path,'MusicTrackBean','setMusicAuthorFavour',likes,dislikes,{
callback:callback,
errorHandler:function(){if(callback)callback(false);},
headers:{'request-username':UD.visitorName}
});
}
MusicTrackBean.getMusicAuthorFavourAll=function(callback){
if(UD.visitorName==null||UD.visitorRank<MusicConst.RANK_GUEST)return;
dwr.engine._execute(MusicTrackBean._path,'MusicTrackBean','getMusicAuthorFavourAll',{
callback:callback,
errorHandler:function(){if(callback)callback(false);},
headers:{'request-username':UD.visitorName}
});
}
MusicTrackBean.getMusicNavigatorList=function(offset,callBack){
musicLoading._preHook();
new Ajax.Request("/s/navigator.s",{
method:'post',
requestHeaders:MusicSearch._generateRequestHeaders(),
parameters:'op=n&p='+offset,
onComplete:function(request){
musicLoading._postHook();
var musicList=null;
try{
musicList=eval(request.responseText);
}catch(e){}
callBack(musicList);
}
})
}
MusicTrackBean.getMusicChartList=function(type,callBack){
musicLoading._preHook();
new Ajax.Request("/s/navigator.s",{
method:'get',
parameters:'op=c'+'&t='+type,
onComplete:function(request){
musicLoading._postHook();
var musicList=null;
try{
musicList=eval(request.responseText);
}catch(e){}
callBack(musicList);
}
})
}
var MusicBoxControl=Class.create();
MusicBoxControl.prototype={
initialize:function(playList,mediaPlayer){
this.playList=playList;
this.mediaPlayer=mediaPlayer;
this.options=Object.extend({
objName:'musicBoxControl'
},arguments[2]||{});
this.started=false;
this.manualStop=true;
this.currentUrl=null;
this.isRandom=false;
this.isRepeat=true;
this.musicMode=1;
this.reStartCount=0;
this.mediaPlayer.setMute(false);
this.mediaPlayer.addPlayStateChangeHandler(this._playStateChange.bind(this));
Event.observe(window,"beforeunload",this.destory.bind(this));
},
start:function(clear,index){
if(clear){
this.reset();
}
if((this.playList.getCount()==0)||!MusicPlayGate.init(PLAY_MODE_PREEMT))return;
this.playAt(index?index:0);
},
reset:function(){
this.mediaPlayer.reset();
},
refreshPlayList:function(){return false},
playAt:function(i){
if(!this.mediaPlayer.success){
MusicHelper.clearTask(this._playAtTask);
this._playAtTask=this.playAt.delay(400,this,i);
return;
}
MusicPlayGate.setPlay(true);
MusicHelper.clearTask(this.delayHandler);
MusicHelper.clearTask(this._tellTask);
this.manualStop=false;
var music=this.playList.get(i);
this._getUrlNPlay(music);
},
reSelectNPlay:function(){
this.manualStop=false;
var music=this.playList.current();
this._getUrlNPlay(music,true);
},
playPrev:function(){
MusicPlayGate.setPlay(true);
this.manualStop=false;
var music=this.playList.prev();
this._getUrlNPlay(music);
},
playNext:function(){
MusicPlayGate.setPlay(true);
this.manualStop=false;
if(this.playList.hasNext()){
var music=this.playList.next();
this._getUrlNPlay(music);
}else{
if(!this.refreshPlayList()){
var music=this.playList.next();
this._getUrlNPlay(music);
}
}
},
play:function(){
MusicPlayGate.setPlay(true);
if(this.manualStop){
this.manualStop=false;
var music=this.playList.current();
this._getUrlNPlay(music);
}else{
this.manualStop=false;
this.mediaPlayer.play();
}
},
stop:function(){
MusicPlayGate.clearPlay();
this.manualStop=true;
this.mediaPlayer.stop();
},
pause:function(){
this.mediaPlayer.pause();
},
togglePlayNPause:function(){
if(this.mediaPlayer.getState()!=3){
this.play();
}
else{
this.pause();
}
},
setMute:function(mute){
this.mediaPlayer.setMute(mute);
},
setVolume:function(value){
this.mediaPlayer.setVolume(value);
},
setMediumPosPercent:function(value){
this.mediaPlayer.setMediumPosPercent(value);
},
getMusicMode:function(){
return this.musicMode;
},
setMusicMode:function(mode){
this.musicMode=mode;
this.isRandom=(this.musicMode==2)?true:false;
this.playList.setRandom(this.isRandom);
},
toggleMusicRepeat:function(){
this.isRepeat=!this.isRepeat;
this.playList.setRepeat(this.isRepeat);
},
destory:function(){
this.stop();
this.mediaPlayer.destory();
},
getCurrentMusic:function(){
var mp=this.mediaPlayer;
if(!mp||!mp.success)
return null;
var music={};
music.state=mp.getState();
music.openState=mp.getOpenState();
music.stateString=mp.getStateString(music.state);
music.status=mp.getStatus();
music.pos=mp.getMediumPos();
music.len=mp.getMediumLength();
if(music.state>1&&music.state<9)
music.buffer=mp.getMediaBuffer();
else
music.buffer=0;
music.currentUrl=this.currentUrl;
var cur=this.playList.current();
if(cur!=null){
music.name=cur.name;
music.author=cur.author;
music.desc=cur.description;
}else{
music.name=null;
music.author=null;
music.desc=null;
}
return music;
},
_getUrlNPlay:function(music,reSelect){
if(music==null)return;
this.started=true;
if(MusicHelper.isListEmpty(music._urlList)){
MusicSearch.getUrlListWidthDiy(music,function(music){
var url=this._selectUrl(music);
this._loadNPlay(url);
}.bind(this),
function(){
this.stop();
}.bind(this)
);
}else{
var url=this._selectUrl(music,reSelect);
this._loadNPlay(url);
}
},
_getCurMusicUrlRetryCount:function(){
var music=this.playList.current();
if(music&&music._urlList&&music._urlList.length>0){
var len=music._urlList.length;
if(len>MusicConst.MAX_RETRY_COUNT){
return len-1;
}
if(len*2>MusicConst.MAX_RETRY_COUNT){
return MusicConst.MAX_RETRY_COUNT;
}else{
return len*2-1;
}
}
return 0;
},
_selectUrl:function(music,reSelect){
if(music==null||MusicHelper.isListEmpty(music._urlList))return null;
if(reSelect){
if(music._currentUrlIndex==null)
music._currentUrlIndex=0;
else
music._currentUrlIndex++;
if(music._currentUrlIndex>=music._urlList.length){
music._currentUrlIndex=0;
}
}else{
this.reStartCount=0;
if(music._currentUrlIndex==null)music._currentUrlIndex=0;
}
return music._urlList[music._currentUrlIndex];
},
_tellStat:function(){
var music=this.playList.current();
if(music&&!music.isDiy&&!music._telled&&music._urlList&&music._urlList.length>0){
music._telled=true;
MusicHelper.clearTask(this._tellTask);
this._tellTask=this._tellHandler.delay(2000,this,music);
}
},
_tellHandler:function(music){
if(this.options.tellStat)
MusicSearch.tellUrlStat(music._urlList,music._currentUrlIndex?music._currentUrlIndex:0);
},
_tryNext:function(){
if(this.playList.getCount()==0){
this.reset();
return;
}
if(this.manualStop)return;
if(this.musicMode!=0&&this.playList.getCount()>1){
this.playNext();
}else{
if(this.reStartCount>this._getCurMusicUrlRetryCount()){
this.stop();
}else{
if(this.isRepeat)
this.play();
else
this.stop();
}
}
this.reStartCount=0;
},
_tryReSelect:function(){
if(this.manualStop)return;
if(++this.reStartCount<=this._getCurMusicUrlRetryCount()){
this.reSelectNPlay();
}else{
if(this.musicMode!=0&&this.playList.getCount()>1){
this.playNext();
}else{
this.stop();
}
this.reStartCount=0;
}
},
_loadNPlay:function(url){
MusicHelper.clearTask(this.delayHandler);
if(url==null){
this.delayHandler=this._tryNext.delay(MusicConst.ERROR_WAIT_DELAY,this);
return;
}
this.currentUrl=url;
if(!this.mediaPlayer.loadNPlay(url)){
this.delayHandler=this._tryReSelect.delay(MusicConst.NEXT_PLAY_DELAY,this);
}
},
_playStateChange:function(s){
MusicHelper.clearTask(this.delayHandler);
switch(s){
case 1:
this.delayHandler=this._tryNext.delay(MusicConst.NEXT_PLAY_DELAY,this);
break;
case-1:
case 10:
this.delayHandler=this._tryReSelect.delay(MusicConst.NEXT_PLAY_DELAY,this);
break;
case 9:
this.delayHandler=this._tryReSelect.delay(MusicConst.MAX_WAIT_TIME,this);
break;
case 6:
this.delayHandler=this._tryReSelect.delay(MusicConst.MAX_BUFFER_TIME,this);
break;
case 3:
if(this.mediaPlayer.getMediumLength()>MusicConst.MIN_MUSIC_LEN){
this._tellStat();
}else{
this.delayHandler=this._tryReSelect.delay(MusicConst.NEXT_PLAY_DELAY,this);
}
break;
}
}
}
var AbstractMusicBoxPanel=Class.create();
AbstractMusicBoxPanel.prototype={
initialize:function(musicBoxControl){
this.musicBoxControl=musicBoxControl;
this.options=Object.extend({
objName:'musicBoxPanel'
},arguments[1]||{});
this.currentMusic={};
this._initializeUI();
this._initializeEvent();
this._initSliders();
this._start();
},
_initializeUI:function(){},
_initializeEvent:function(){},
_initSliders:function(){
this.volumeSlider=new Control.Slider(this.volume_handle_id,this.volume_track_id,{
axis:'horizontal',
range:$R(0,100),
sliderValue:50,
onSlide:this._soundSlieHandler.bind(this),
onChange:this._soundChangeHandler.bind(this)
});
this.playTimeSlider=new Control.Slider(this.play_handle_id,this.play_track_id,{
axis:'horizontal',
range:$R(0,1),
sliderValue:0,
onSlide:this._playTimeSlideHandler.bind(this),
onChange:this._playTimeChangeHandler.bind(this)
});
},
_soundSlieHandler:function(value){
this.musicBoxControl.setVolume(value);
},
_soundChangeHandler:function(value){
this._soundSlieHandler(value);
},
_playTimeSlideHandler:function(value){},
_playTimeChangeHandler:function(value){
this._playTimeSlideHandler(value);
if(!this.isSettingPlaytime)
this.musicBoxControl.setMediumPosPercent(value);
},
_start:function(){
if(this.poller==null){
this.poller=
new SecurityPeriodicalExecuter(this._statePoller.bind(this),MusicConst.MUSIC_CHECK_PERIOD);
}
},
_statePoller:function(){
if(!this.musicBoxControl.started)return;
this.currentMusic=this.musicBoxControl.getCurrentMusic();
if(this.currentMusic==null)this.currentMusic={};
this._setPlayTimeSlider();
this._toggleControls();
this._renewSliders();
this._showMusicInfo();
this._focusCurrentMusic();
if(this.currentMusic.state==3&&this.currentMusic.pos>this.currentMusic.len+1){
this.musicBoxControl.playNext();
}
if(!MusicPlayGate.canPlay()){
this.musicBoxControl.stop();
}
if(this.options.debug){
var debug_string="";
for(var key in this.currentMusic){
debug_string+=key+" = "+this.currentMusic[key]+"<br>";
}
debug_string+='manualStop = '+this.musicBoxControl.manualStop;
$('debug').innerHTML=debug_string;
}
},
_setPlayTimeSlider:function(){
this.isSettingPlaytime=true;
if(this.playTimeSlider.dragging!=true&&MusicHelper.test("playtime",this.currentMusic.pos)){
if(this.currentMusic.pos>0&&this.currentMusic.len>0)
this.playTimeSlider.setValue(this.currentMusic.pos/this.currentMusic.len,0);
else
this.playTimeSlider.setValue(0,0);
}
this.isSettingPlaytime=false;
},
_toggleControls:function(){
this._togglePlayLoadStat();
this._togglePlayButtons();
},
_togglePlayLoadStat:function(){},
_togglePlayButtons:function(){},
_showMusicInfo:function(){},
_focusCurrentMusic:function(){},
_renewSliders:function(){
if(MusicHelper.test("play_track_width",$(this.play_track_id).offsetWidth)&&
this.playTimeSlider!=null){
this.playTimeSlider.dispose();
this.playTimeSlider=new Control.Slider(this.play_handle_id,this.play_track_id,{
axis:'horizontal',
range:$R(0,1),
sliderValue:0,
onSlide:this._playTimeSlideHandler.bind(this),
onChange:this._playTimeChangeHandler.bind(this)
});
}
}
}
var LrcPlayer=Class.create();
LrcPlayer.prototype={
initialize:function(zoneId,playList,mediaPlayer,musicTask){
this.showLrcZone=$(zoneId);
this.playList=playList;
this.mediaPlayer=mediaPlayer;
this.musicTask=musicTask;
this.options=Object.extend({
idPrefix:'lrc_',
highLightCss:'current',
lrc_handle:'$_lrc_handle',
lrc_track:'$_lrc_track'
},arguments[4]||{});
this.bLrcOK=false;
this.currentMusic=null;
this.lrcLineList=null;
this.lastFocusIndex=-1;
if(this.playList!=null&&this.mediaPlayer!=null)
this.musicTask.addTask("lrc_play",this._statePoller.bind(this));
this._initLrcSlider();
},
_initLrcSlider:function(){
this.lrcSlider=new Control.Slider(this.options.lrc_handle,this.options.lrc_track,{
axis:'vertical',
range:$R(0,1),
sliderValue:0,
onSlide:this._lrcSlideHandler.bind(this),
onChange:this._lrcChangeHandler.bind(this)
});
},
reset:function(){
this._resetLrc(true);
MusicHelper.reset("MUSIC_INDEX");
},
loadLrc:function(lrc){
this._resetLrc(true);
this.lrc=(lrc||'').escape();
this._loadLrcList();
this._showLrc();
},
_resetLrc:function(clear){
if(clear){
this.lrcLineList=null;
this.bLrcOK=false;
this.showLrcZone.innerHTML='<div class="lrc-empty">网易博客音乐播放器</div>';
}
var _pLi=$(this.options.idPrefix+this.lastFocusIndex);
if(_pLi)MusicHelper.applyOverCss(_pLi,false,this.options.highLightCss);
MusicHelper.reset("LRC_INDEX");
this.lastFocusIndex=-1;
this.lrcSlider.setValue(0,0);
},
highLight:function(time){
if(this.bLrcOK==false)return;
var index=this._getLrcIndex(time);
if(this.lrcLineList[index]==null||this.lrcLineList[index].t==-1)return;
if(MusicHelper.test("LRC_INDEX",index)){
try{
var _nLi=$(this.options.idPrefix+index);
if(_nLi&&_nLi.innerHTML.trim().length>0){
if(this.lastFocusIndex>=0){
var _pLi=$(this.options.idPrefix+this.lastFocusIndex);
if(_pLi)MusicHelper.applyOverCss(_pLi,false,this.options.highLightCss);
}
MusicHelper.applyOverCss(_nLi,true,this.options.highLightCss);
this.lrcSlider.setValue((_nLi.offsetTop-this.showLrcZone.offsetHeight*(1-0.618))/this.showLrcZone.scrollHeight,0);
this.lastFocusIndex=index;
}
}catch(ex){}
}
},
refresh:function(){
this.lrcSlider.dispose();
this._initLrcSlider();
},
_statePoller:function(){
if(!this.mediaPlayer.success)return;
this.currentMusic=this.playList.current();
if(this.currentMusic!=null){
if(MusicHelper.test("MUSIC_INDEX",this.currentMusic)){
if(this.currentMusic._lrcContent){
this.loadLrc(this.currentMusic._lrcContent);
}else{
MusicSearch.getLrcTextWidthDiy(this.currentMusic,function(s){
this.currentMusic._lrcContent=s;
this.loadLrc(s);
}.bind(this)
);
}
}
var stat=this.mediaPlayer.getState();
if(stat==3){
this.highLight(this.mediaPlayer.getMediumPos());
}else if(stat==1||stat==10){
this._resetLrc();
}
}else{
this._resetLrc(true);
}
},
_loadLrcList:function(){
if(this.lrc==null)return;
var lrcLineList=[];
var lrcLine;
var _lrc=this.lrc;
var _lines=_lrc.split('\n');
var _l;
var _items;
var _v;
var _t;
for(var i=0;i<_lines.length;i++){
_l=_lines[i].trim();
if(_l=='')continue;
_items=_l.split(']');
if(_items.length>=2){
_v=_items[_items.length-1].trim();
for(var j=0;j<_items.length-1;j++){
_t=this._getLrcTime(_items[j]);
if(_t>=0){
lrcLine={t:_t,v:_v};
lrcLineList.push(lrcLine);
}
}
}else{
lrcLineList.push({t:-1,v:_items[0]});
}
}
this.lrcLineList=lrcLineList.sortBy(function(e){return e.t;});
},
_getLrcTime:function(str){
str=str.replace('[','');
var _items=str.split(':');
if(_items.length<2)return-1;
try{
return parseInt(_items[0])*60+parseFloat(_items[1]);
}catch(ex){
return-1;
}
},
_showLrc:function(){
if(this.lrcLineList==null||this.lrcLineList.length<1){
var name=(this.currentMusic.name==null)?'':this.currentMusic.name;
var author=this.currentMusic.author;
if(author!=null)
this.showLrcZone.innerHTML='<div class="lrc-empty">'+name+' - '+author+'</div>';
else
this.showLrcZone.innerHTML='<div style="lrc-empty">'+name+'</div>';
return;
}
var _div=document.createElement("div");
var _ul=document.createElement("div");
_ul.className="items";
var _li;
for(var i=0;i<3;i++){
_li=document.createElement("div");
_li.className="item-empty";
_li.innerHTML='&nbsp;';
_ul.appendChild(_li);
}
this.lrcLineList.each(function(e,index){
_li=document.createElement("div");
_li.id=this.options.idPrefix+index;
_li.className="item";
_li.innerHTML=e.v;
_ul.appendChild(_li);
}.bind(this));
for(var i=0;i<3;i++){
_li=document.createElement("div");
_li.className="item-empty";
_li.innerHTML='&nbsp;';
_ul.appendChild(_li);
}
_div.appendChild(_ul);
this.showLrcZone.innerHTML=_div.innerHTML;
this.bLrcOK=true;
},
_getLrcIndex:function(time){
if(this.lrcLineList){
var len=this.lrcLineList.length;
if(time<=this.lrcLineList[0].t)return 0;
if(time>=this.lrcLineList[len-1].t)return len-1;
for(var i=0;i<len-1;i++){
if(time>=this.lrcLineList[i].t&&time<this.lrcLineList[i+1].t){
return i;
}
}
}
return 0;
},
_lrcSlideHandler:function(value){
this.showLrcZone.scrollTop=this.showLrcZone.scrollHeight*value;
},
_lrcChangeHandler:function(value){
this._lrcSlideHandler(value);
}
}
var MusicCommend=Class.create();
MusicCommend.prototype={
initialize:function(playList,mediaPlayer,musicTask){
this.playList=playList;
this.mediaPlayer=mediaPlayer;
this.musicTask=musicTask;
this.options=Object.extend({
objName:'musicCommend',
addCallback:Prototype.emptyFunction(),
commend_list:'$_commend_list',
listener_list:'$_listener_list',
commend_tab:'$_commend-tab'
},arguments[3]||{});
this.commendTab=$(this.options.commend_tab);
if(this.playList!=null&&this.mediaPlayer!=null)
this.musicTask.addTask("music_commend",this._statePoller.bind(this));
},
switchTab:function(className){
if(this.commendTab)this.commendTab.className=className;
},
addMusic:function(id){
var currentMusic=this.playList.current();
if(currentMusic&&currentMusic._commendList){
var music=currentMusic._commendList.detect(function(e){if(e.id==id)return true;return false;});
if(music&&this.options.addCallback){
this.options.addCallback(music);
}
}
},
_statePoller:function(){
if(!this.mediaPlayer.success)return;
var currentMusic=this.playList.current();
if(currentMusic==null){
MusicHelper.reset("MUSIC_COMMEND_INDEX");
if($(this.options.commend_list))
$(this.options.commend_list).innerHTML='';
if($(this.options.listener_list))
$(this.options.listener_list).innerHTML='';
return;
}
if(MusicHelper.test("MUSIC_COMMEND_INDEX",currentMusic)){
if(currentMusic.name&&currentMusic.author){
MusicHelper.clearTask(this.delayGetListenerTask);
this.delayGetListenerTask=this._delayGetListenerHandler.delay(500,this,currentMusic);
}
if(currentMusic._commendList){
this._showCommend(currentMusic._commendList);
}else if(currentMusic.name&&currentMusic.author){
MusicHelper.clearTask(this.delayGetCommendTask);
this.delayGetCommendTask=this._delayGetCommendHandler.delay(1500,this,currentMusic);
}
}
},
_delayGetCommendHandler:function(music){
MusicSearch.getMusicCommendList(music,function(musicList){
music._commendList=musicList;
this._showCommend(music._commendList);
}.bind(this));
},
_showCommend:function(commendList){
if($(this.options.commend_list))
$(this.options.commend_list).innerHTML=MusicTemplate.musicCommendListTemplate.processUseCache({objName:this.options.objName,itemList:commendList||[]});
},
_delayGetListenerHandler:function(music){
MusicSearch.getMusicListenerList(music,function(listenerList){
this._showListener(listenerList);
}.bind(this));
},
_showListener:function(listenerList){
if($(this.options.listener_list))
$(this.options.listener_list).innerHTML=MusicTemplate.musicListenerListTemplate.processUseCache({objName:this.options.objName,itemList:listenerList||[]});
}
}
var MusicLoading=Class.create();
MusicLoading.prototype={
initialize:function(baseObj){
this.baseObj=$(baseObj);
this.baseObj.style.position='relative';
this._init();
DWREngine.setPreHook(this._preHook.bind(this));
DWREngine.setPostHook(this._postHook.bind(this));
var _clearFunc=DWREngine._clearUp;
DWREngine._clearUp=function(batch){
if(batch&&!batch.completed){
this._postHook();
}
_clearFunc(batch);
}.bind(this);
},
_init:function(){
this.loadingZone=document.createElement('div');
this.loadingZone.style.display='none';
this.loadingZone.innerHTML='<table class="disable-zone"><tr><td><div class="loading-zone"><span class="i">&nbsp;</span>数据加载中。。。</div></td></tr></table>';
this.baseObj.appendChild(this.loadingZone);
},
_preHook:function(){
Element.show(this.loadingZone);
},
_postHook:function(){
Element.hide(this.loadingZone);
}
}
var isIE=MusicHelper.isIE();
var IEVer=MusicHelper.getIEVersion();
var Const={STDomain:"http://b.bst.126.net"}
var base64DecodeChars=new Array(
-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,
-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,
-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,62,-1,-1,-1,63,
52,53,54,55,56,57,58,59,60,61,-1,-1,-1,-1,-1,-1,
-1,0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,
15,16,17,18,19,20,21,22,23,24,25,-1,-1,-1,-1,-1,
-1,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,
41,42,43,44,45,46,47,48,49,50,51,-1,-1,-1,-1,-1);
function base64decode(str){
var c1,c2,c3,c4;
var i,len,out;
len=str.length;
i=0;
out="";
while(i<len){
do{
c1=base64DecodeChars[str.charCodeAt(i++)&0xff];
}while(i<len&&c1==-1);
if(c1==-1)
break;
do{
c2=base64DecodeChars[str.charCodeAt(i++)&0xff];
}while(i<len&&c2==-1);
if(c2==-1)
break;
out+=String.fromCharCode((c1<<2)|((c2&0x30)>>4));
do{
c3=str.charCodeAt(i++)&0xff;
if(c3==61)
return out;
c3=base64DecodeChars[c3];
}while(i<len&&c3==-1);
if(c3==-1)
break;
out+=String.fromCharCode(((c2&0XF)<<4)|((c3&0x3C)>>2));
do{
c4=str.charCodeAt(i++)&0xff;
if(c4==61)
return out;
c4=base64DecodeChars[c4];
}while(i<len&&c4==-1);
if(c4==-1)
break;
out+=String.fromCharCode(((c3&0x03)<<6)|c4);
}
return out;
}
var $_dwrLogger;
function dwrlog(msg,type){
try{
if(!$_dwrLogger)
$_dwrLogger=new NetEase.DwrLogger({fade:false,container:$('top-link')});
$_dwrLogger.appendMsg(msg,type);
}catch(ex){}
}
function showLoginDlg(){
var result=confirm("您必须先登录才能进行该操作。");
if(result){
window.location.href='http://blog.163.com/pub/services/outlogin.html?url='+window.location.href;
return false;
}
}
function doLogin(){
window.location.href='http://blog.163.com/pub/services/outlogin.html?url='+window.location.href;
}
var MusicPlayGate={
stoped:true,
init:function(preempt){
if(!this.stoped)return true;
this.curVersion=new Date().getTime();
if(preempt){
this.setPlay();
return true;
}else{
var map=this.getGateValueMap();
if(MusicHelper.isBlank(map.curVersion)||map.curVersion<=0){
this.setPlay();
return true;
}
return false;
}
},
canPlay:function(){
if(this.stoped)return false;
var map=this.getGateValueMap();
if(!MusicHelper.isBlank(map.curVersion)&&map.curVersion>this.curVersion){
this.stoped=true;
return false;
}
return true;
},
setPlay:function(){
if(!this.stoped)return;
this.curVersion=new Date().getTime();
this.setGateValue('curVersion',this.curVersion);
this.stoped=false;
},
clearPlay:function(){
if(this.stoped)return;
var map=this.getGateValueMap();
if(map.curVersion==this.curVersion){
this.setGateValue('curVersion',-1);
}
this.stoped=true;
},
getGateValueMap:function(){
var v=MusicHelper.getCookieValue("MUSIC_BOX_STATE");
var valueMap={};
if(MusicHelper.isBlank(v))return valueMap;
var vs=v.split(",");
valueMap.curVersion=vs[0];
valueMap.curMusic=vs[1];
valueMap.curAlbum=vs[2];
return valueMap;
},
setGateValue:function(id,value){
var map=this.getGateValueMap();
map[id]=value;
var v=map.curVersion+','+map.curMusic+','+map.curAlbum;
MusicHelper.setCookieValue("MUSIC_BOX_STATE",v,24,"/",".163.com");
}
}
var MusicBoxPanel=Class.create();
Object.extend(Object.extend(MusicBoxPanel.prototype,AbstractMusicBoxPanel.prototype),{
_initializeUI:function(){
this.musicLinkDiv=document.createElement('div');
this.musicLinkDiv.className='link-c';
this.musicLinkDiv.innerHTML=MusicTemplate.musicLinkTemplate;
this.volume_handle_id='$_volume_handle';
this.volume_track_id='$_volume_track';
this.play_handle_id='$_play_handle';
this.play_track_id='$_play_track';
},
_initializeEvent:function(){
MusicHelper.addListener('$_bt_prev','click',this.musicBoxControl.playPrev.bind(this.musicBoxControl));
MusicHelper.addListener('$_bt_next','click',this.musicBoxControl.playNext.bind(this.musicBoxControl));
MusicHelper.addListener('$_bt_play','click',this.musicBoxControl.togglePlayNPause.bind(this.musicBoxControl));
MusicHelper.addListener('$_bt_pause','click',this.musicBoxControl.togglePlayNPause.bind(this.musicBoxControl));
MusicHelper.addListener('$_bt_stop','click',this.musicBoxControl.stop.bind(this.musicBoxControl));
MusicHelper.addListener('$_bt_sound_min','click',function(){
this.volumeSlider.setValue(0,0);
this.musicBoxControl.setVolume(0);
}.bind(this));
MusicHelper.addListener('$_bt_sound_max','click',function(){
this.volumeSlider.setValue(100,0);
this.musicBoxControl.setVolume(100);
}.bind(this));
MusicHelper.addListener('$_bt_lrc','click',function(){
if(MusicHelper.toggle('toggle.lrc')){
MusicHelper.testNset("$_bt_lrc",'关闭歌词');
Element.addClassName('$_music_list_zone','lrc-status');
}else{
MusicHelper.testNset("$_bt_lrc",'打开歌词');
Element.removeClassName('$_music_list_zone','lrc-status');
}
}.bind(this));
},
_togglePlayLoadStat:function(){
if(MusicHelper.test("st_play_stat",this.currentMusic.state)){
Element.removeClassName('$_music_state','lcd_ratio_loading');
Element.removeClassName('$_music_state','lcd_ratio_stop');
if(this.currentMusic.state==3){
}else if(this.currentMusic.state==6||this.currentMusic.state==7||this.currentMusic.state==9){
Element.addClassName('$_music_state','lcd_ratio_loading');
}else{
Element.addClassName('$_music_state','lcd_ratio_stop');
}
}
},
_togglePlayButtons:function(){
MusicHelper.toggleButton("$_bt_pause","$_bt_play",this.currentMusic.state==3);
},
_showMusicInfo:function(){
var music=this.currentMusic;
var info;
if(MusicHelper.isBlank(music.author)){
info=MusicHelper.defaultString(music.name);
}else{
info=MusicHelper.defaultString(music.author)+' - '+MusicHelper.defaultString(music.name);
}
MusicHelper.testNset("$_music_cur_title",info);
MusicHelper.testNset("$_music_cur_pos",MusicHelper.formatTime(music.pos));
MusicHelper.testNset("$_music_cur_duration",MusicHelper.formatTime(music.len));
var currentIndex=this.musicBoxControl.playList.currentIndex();
var current=this.musicBoxControl.playList.current();
if(current&&music.state==3&&music.len>MusicConst.MIN_MUSIC_LEN&&MusicHelper.test("music.duration.current",current)){
if(UD.visitorRank==MusicConst.RANK_OWNER&&current.duration!=null&&current.duration<=0&&!current.isDiy&&!current.isBoard){
MusicBean.updateMusicDuration(current.id,parseInt(music.len));
}
current.duration=music.len;
$('music_duration_'+currentIndex).innerHTML=MusicHelper.formatTime(current.duration);
}
if($('$_play_buffer')&&MusicHelper.test("music.buffer",music.buffer)){
Element.setStyle('$_play_buffer',{width:parseInt($(this.play_track_id).offsetWidth*music.buffer/100)+"px"});
}
if(music.currentUrl){
var url=music.currentUrl;
if(url.length>25){
url=url.substring(0,20)+'...'+url.substring(url.length-5,url.length);
}
MusicHelper.testNset("$_music_from",'<a class="songurl" href="'+music.currentUrl+'" target="_blank">'+url+'</a>');
}
},
_focusCurrentMusic:function(){
if(!MusicHelper.get("music.noshowcurrent")){
var currentIndex=this.musicBoxControl.playList.currentIndex();
var current=this.musicBoxControl.playList.current();
var oldIndex=MusicHelper.get("music.index");
var count=this.musicBoxControl.playList.getCount();
if(MusicHelper.test("music.index",currentIndex)){
if(oldIndex!=null)
MusicHelper.applyOverCss('music_item_'+oldIndex,false,'cur-play');
if($('music_item_'+currentIndex)){
MusicHelper.reset('$_music_from');
MusicHelper.applyOverCss('music_item_'+currentIndex,true,'cur-play');
}
var scrollT=$('$_music_list').scrollTop;
var scrollH=$('$_music_list').scrollHeight;
if(scrollT<scrollH*(currentIndex-9)/count){
$('$_music_list').scrollTop=scrollH*(currentIndex-9)/count;
}else if(scrollT>scrollH*currentIndex/count){
$('$_music_list').scrollTop=scrollH*currentIndex/count;
}
}
}
},
reSelectNPlay:function(){
this.musicBoxControl.reSelectNPlay();
}
}
);
var MusicManager=Class.create();
MusicManager.prototype={
initialize:function(playList,musicBoxControl,lrcPlayer){
this.playList=playList;
this.musicBoxControl=musicBoxControl;
this.lrcPlayer=lrcPlayer;
this.albumWrapManager=new MusicAlbumWrapManager();
this.options=Object.extend({
objName:'musicManager'
},arguments[3]||{});
this.albumPageCtrl=new CommonPageCtrl(10);
this.jsWindowManager=jsWindowManager?jsWindowManager:new NetEase.JSWindowManager({allowDrag:false});
this.simplePageLayer=simplePageLayer?simplePageLayer:new NetEase.SimplePageLayer();
this._initUI();
this.musicBoxControl.refreshPlayList=function(){
if($('heart_cb').checked&&this.albumWrapManager.getPlayingAlbumWrapType()==$MusicAlbumType.NAV){
this.refreshNavAlbum();
return true;
}
return false;
}.bind(this);
},
loadAlbum:function(albumWrapList,albumId,musicId,extendList){
this.albumWrapManager.addAlbumWrapList(albumWrapList);
this._showUserAlbum();
this._showBoardAlbum();
this.selectAlbum(albumId,true,false,musicId,extendList);
},
selectAlbum:function(id,toPlay,reload,musicId,extendList){
var toSelect=!this.albumWrapManager.isAlbumWrapSelected(id);
var albumWrap=this.albumWrapManager.selectAlbumWrap(id);
if(albumWrap){
if(toPlay){
this.albumWrapManager.playAlbumWrap(albumWrap);
this._renderPlayingAlbum(albumWrap);
}
if(toSelect)
this._renderSelectAlbum(albumWrap);
MusicHelper.test("music.noshowcurrent",!this.albumWrapManager.isPlayingSelectedAlbumWrap());
albumWrap.getMusicList(function(_itemList){
if(!MusicHelper.isListEmpty(extendList)){
this._addMusicList(extendList,toPlay,albumWrap);
}else{
this._showMusicList(_itemList);
if(toPlay){
this.playList.loadList(_itemList);
this.musicBoxControl.start(true,this.playList.getIndexByProp(musicId,'id'));
}
}
}.bind(this),reload);
}
},
refreshNavAlbum:function(){
var albumWrap=this.albumWrapManager.getSelectedAlbumWrap();
this.selectAlbum(albumWrap.getId(),this.albumWrapManager.isPlayingSelectedAlbumWrap(),true);
},
showAddAlbumLayer:function(){
if(this.addAlbumLayer==null){
this.addAlbumLayer=this.jsWindowManager.createWindow('album_add',{
className:"layer-album-add",left:140,top:180,width:300,height:20,title:'新建专辑'
});
}
this.addAlbumLayer.panel.innerHTML=this._renderTemplate(MusicTemplate.albumAddTemplate);
this.addAlbumLayer.showWindow();
},
cancelAddAlbum:function(){
if(this.addAlbumLayer)
this.addAlbumLayer.hiddenWindow();
},
submitAddAlbum:function(){
var album_name=$F('album_add_name').trim();
if(album_name==""){
alert('专辑名称必须填写!');
return false;
}
if(UD.visitorRank<MusicConst.RANK_OWNER){
return this._fakeSubmitAlbum(album_name);
}
return this._submitAlbum(album_name);
},
addAlbum:function(album){
if(album){
this.albumWrapManager.addAlbumWrapList([new UserMusicAlbumWrap(album)]);
this._showUserAlbum();
this._renderSelectAlbum(this.albumWrapManager.getSelectedAlbumWrap());
}
this.cancelAddAlbum();
},
removeAlbum:function(){
var albumWrap=this.albumWrapManager.getSelectedAlbumWrap();
if(!this.albumWrapManager.canSelectedAlbumRemove()){
return;
}
if(UD.visitorRank<MusicConst.RANK_OWNER){
this._afterRemoveAlbum(albumWrap.getId(),true);
}else{
if(window.confirm("确认删除专辑?")){
MusicBean.deleteMusicAlbum(albumWrap.getId(),this._afterRemoveAlbum.bind(this,albumWrap.getId()));
}
}
},
playAt:function(i){
if(!this.albumWrapManager.isPlayingSelectedAlbumWrap()){
var albumWrap=this.albumWrapManager.getSelectedAlbumWrap();
this.albumWrapManager.playAlbumWrap(albumWrap);
this._renderPlayingAlbum(albumWrap);
this.playList.loadList(albumWrap.getMusicList());
MusicHelper.test("music.noshowcurrent",false);
}
this.musicBoxControl.playAt(i);
},
showAddMusicLayer:function(){
if(!this.albumWrapManager.canSelectedAlbumAddMusic())return;
if(!this.musicAddLayer){
this.musicAddLayer=this.jsWindowManager.createWindow('music_add',{
className:"layer-music-add",title:'添加音乐',left:200,top:140,width:300,height:20
});
}
this.musicAddLayer.panel.innerHTML=this._renderTemplate(MusicTemplate.addMusicTemplate);
this.musicAddLayer.showWindow();
$('search_key').focus();
new Ajax.Autocompleter('search_key','search_suggest','/s/suggest.s',{
method:'get',parameters:'t=music',paramName:'p',tokens:[]
});
Event.observe('search_key',"keypress",this.searchByKeyDown.bindAsEventListener(this));
},
searchMusic:function(){
var key=$F('search_key').trim();
if(!MusicHelper.isBlank(key))
MusicSearch.getMusicList(key,this._renderMusic.bind(this,key));
},
searchByKeyDown:function(event){
if(event.keyCode==Event.KEY_RETURN){
this.searchMusic();
}
},
selectAll:function(iStat){
var _objs=document.getElementsByTagName("input")
var _id;
for(var i=0;i<_objs.length;i++){
_id=_objs[i].id;
if(_objs[i].type.toLowerCase()=="checkbox"&&_id.indexOf("search_music_check_")==0){
if(iStat==0){
_objs[i].checked=false;
}else
if(iStat==1){
_objs[i].checked=true;
}else{
if(_objs[i].checked)
_objs[i].checked=false;
else
_objs[i].checked=true;
}
}
}
},
addMusic:function(){
var t=[];
this.searchMusicList.each(
function(e,index){
if($('search_music_check_'+index)&&$('search_music_check_'+index).checked==true){
t.push(e);
}
});
if(!MusicHelper.isListEmpty(t)){
this._addMusicList(t);
}else
this.musicAddLayer.hiddenWindow();
},
addMusicList:function(itemList,toPlay,albumId){
var albumWrap=this.albumWrapManager.getAlbumWrapById(albumId);
this._addMusicList(itemList,toPlay,albumWrap);
},
showMusicCustomLayer:function(){
if(!this.albumWrapManager.canSelectedAlbumAddMusic())return;
if(this.musicAddLayer)
this.musicAddLayer.hiddenWindow();
if(!this.musicCustomLayer){
this.musicCustomLayer=this.jsWindowManager.createWindow('music_custom',{
className:"layer-music-custom",title:'自定义添加歌曲',left:200,top:140,width:300,height:20
});
}
this.musicCustomLayer.showWindow();
this.musicCustomLayer.panel.innerHTML=this._renderTemplate(MusicTemplate.musicCustomTemplate);
},
addMusicCustom:function(){
var url=$F('custom_url').trim();
var name=$F('custom_name').trim();
var author=$F('custom_author').trim();
if(!MusicHelper.isVaildMusic(url)){
alert('歌曲链接不合法!');
return;
}
if(name==''){
alert('歌曲名必须填写!');
return;
}
this._addMusicList([{name:name,author:author,url:url,musicType:MusicConst.CUSTOM_MUSIC}]);
},
removeMusic:function(index,id){
var albumWrap=this.albumWrapManager.getSelectedAlbumWrap();
albumWrap.removeMusic(id,this._afterRemoveMusic.bind(this,index));
},
clearMusicList:function(){
if(window.confirm("是否清空列表?")){
var albumWrap=this.albumWrapManager.getSelectedAlbumWrap();
albumWrap.clearMusicList(this._afterClearMusicList.bind(this));
}
},
setMusicMode:function(mode,title){
$('$_bt_mode').innerHTML=title;
this.musicBoxControl.setMusicMode(mode);
},
toggleMusicRepeat:function(){
this.musicBoxControl.toggleMusicRepeat();
},
movePageUp:function(){
this._showUserAlbum(-1);
this._renderSelectAlbum(this.albumWrapManager.getSelectedAlbumWrap());
},
movePageDown:function(){
this._showUserAlbum(1);
this._renderSelectAlbum(this.albumWrapManager.getSelectedAlbumWrap());
},
collectMusicAlbum:function(){
if(!this.albumWrapManager.canSelectedAlbumCollect()){
return;
}
if(UD.visitorRank<MusicConst.RANK_GUEST){
return showLoginDlg();
}
var album=this.albumWrapManager.getSelectedAlbumWrap().getAlbum();
MusicBean.collectMusicAlbum(album.id,album.userId,function(s){
if(s==1){
dwrlog("收藏专辑成功!","ok");
}else if(s==-1){
dwrlog("收藏专辑个数超过最大限制!","info");
}else if(s==-2){
dwrlog("你已经收藏过该专辑!","info");
}else{
dwrlog("收藏专辑失败!","error");
}
}.bind(this));
},
collectMusic:function(){
var music=this.playList.current();
if(!music)return;
if(UD.visitorRank<MusicConst.RANK_GUEST){
return showLoginDlg();
}
if(UD.visitorRank==MusicConst.RANK_OWNER){
this._showMusicCollectLayer(music,this.albumWrapManager.getAlbumListByType($MusicAlbumType.USER));
}else{
if(this.visitorAlbumList!=null){
this._showMusicCollectLayer(music,this.visitorAlbumList);
}else{
MusicBean.getMusicAlbumListByVisit(-1,this._showMusicCollectLayer.bind(this,music));
}
}
},
submitCollectMusic:function(id,albumId){
var albumWrap=this.albumWrapManager.getAlbumWrapById(albumId);
var music=albumWrap.getMusic(id);
if(music==null){
if(this.musicCollectLayer)this.musicCollectLayer.hiddenWindow();
return;
}
albumId=$('album_collect_select').value;
if(music.isDiy){
DiyMusicBean.collectDiyMusic(id,albumId,function(s){
if(s){
dwrlog("收藏音乐成功!","ok");
if(this.musicCollectLayer)this.musicCollectLayer.hiddenWindow();
}else{
dwrlog("收藏音乐失败!","error");
}
}.bind(this));
}else{
if(UD.visitorRank==MusicConst.RANK_OWNER){
albumWrap=this.albumWrapManager.getAlbumWrapById(albumId);
var t=Object.extend(Object.extend({},music),{musicType:MusicHelper.defaultString(music.musicType,MusicConst.SYS_MUSIC)});
this._addMusicList([t],false,albumWrap);
}else{
MusicBean.collectMusicList([music.name],[MusicHelper.defaultString(music.author)],[MusicHelper.defaultString(music.url)],[MusicHelper.defaultString(music.musicType,MusicConst.SYS_MUSIC)],[MusicHelper.defaultString(music.lrc)],albumId,function(s){
if(s){
dwrlog("收藏音乐成功!","ok");
if(this.musicCollectLayer)this.musicCollectLayer.hiddenWindow();
MusicTrackBean.addCollectUser([music.name],[MusicHelper.defaultString(music.author)],Prototype.emptyFunction);
}else{
dwrlog("收藏音乐失败!","error");
}
}.bind(this));
}
}
},
postMusicComment:function(){
var music=this.playList.current();
if(!music)return;
if(UD.visitorRank<MusicConst.RANK_GUEST){
return showLoginDlg();
}
if(!this.musicCommentLayer){
this.musicCommentLayer=jsWindowManager.createWindow('music_comment',{
className:"layer-music-comment",title:'发表评论',left:200,top:200,width:300,height:20
});
}
this.musicCommentLayer.panel.innerHTML=this._renderTemplate(MusicTemplate.musicCommentTemplate,{music:music});
this.musicCommentLayer.showWindow();
},
submitMusicComment:function(name,author){
var title=$F('comment_title');
var content=$F('comment_content');
if(title.trim().length<3){
alert('标题至少3个字符!');
return;
}
if(content.trim().length<10){
alert('内容至少10个字符!');
return;
}
MusicTrackBean.postMusicCircleComment(name,author,title,content,function(s){
if(s){
dwrlog("发表评论成功!","ok");
}else{
dwrlog("发表评论失败!","error");
}
this.closeMusicComment();
}.bind(this));
},
closeMusicComment:function(){
if(this.musicCommentLayer){
this.musicCommentLayer.hiddenWindow();
}
},
downloadRing:function(wapId){
window.open('http://my.12530.com/newchannel/orderTone/'+wapId+'/1/5300/blog/-/-/order.htm','_blank');
},
downloadLrc:function(lrc,name,author){
window.open('http://s.blog.163.com/s/slrc.s?p='+lrc+'&n='+name+'&a='+author,'_blank','resizable=no,scrollbars=yes,status=yes,width=440px,height=400px');
},
testMusicFavour:function(){
if(UD.visitorRank<MusicConst.RANK_GUEST){
return showLoginDlg();
}
if(!this.musicFavourTestLayer){
MusicTrackBean.getMusicAuthorFavourAll(function(ss){
this.musicFavourTestLayer=this.jsWindowManager.createWindow('music_test',{
className:"layer-music-test",title:'音乐测试，测出我的音乐口味！',left:200,top:120,width:400,height:20
});
var like='',dislike='';
if(ss!=null&&ss.length==2){
like=ss[0];
dislike=ss[1];
}
this.musicFavourTestLayer.panel.innerHTML=this._renderTemplate(MusicTemplate.musicFavourTestTemplate,{like:like,dislike:dislike});
this.musicFavourTestLayer.showWindow();
new Ajax.Autocompleter('favour_author_like','favour_author_like_suggest','/s/suggest.s',{
method:'get',parameters:'t=author',paramName:'p'
});
new Ajax.Autocompleter('favour_author_dislike','favour_author_dislike_suggest','/s/suggest.s',{
method:'get',parameters:'t=author',paramName:'p'
});
}.bind(this));
}else{
this.musicFavourTestLayer.showWindow();
}
},
submitMusicFavourTest:function(){
if(UD.visitorRank<MusicConst.RANK_GUEST){
return showLoginDlg();
}
var likes=$F('favour_author_like');
var dislikes=$F('favour_author_dislike');
if(likes.split(/,|，|\n|\||\t/).length>10){
dwrlog("最多只能设置10个喜欢的歌手","error");
return;
}
if(dislikes.split(/,|，|\n|\||\t/).length>10){
dwrlog("最多只能设置10个不喜欢的歌手","error");
return;
}
MusicTrackBean.setMusicAuthorFavour(likes,dislikes,function(s){
if(s){
dwrlog("设置成功!","ok");
}else{
dwrlog("设置失败!","error");
}
this.closeMusicFavourTest();
}.bind(this));
},
closeMusicFavourTest:function(){
this.musicFavourTestLayer.hiddenWindow();
},
showMusicRank:function(id,type,rank,voteRank,voteUserCount,vote){
rank=(rank==null)?3:rank;
if(rank>5)rank=5;
voteRank=(voteRank==null)?0:voteRank;
voteUserCount=(voteUserCount==null)?0:voteUserCount;
var titles=['无法忍受','比较难听','一般','很好听','非常好听，强烈推荐'];
var ss;
if(vote){
ss=['<div class="vote-star vpic default-'+rank+'"><div class="spc vpic vote-dft">&nbsp;</div>'];
for(var i=0;i<=4;i++){
ss.push('<a class="spc vpic star'+i+'" title="'+titles[i]+'" href="#" onclick="'+this.options.objName+'.voteMusicRank(\''+id+'\',\''+type+'\','+(i+1)+');return false;">&nbsp;</a>');
}
}else{
ss=['<div class="vote-star vpic vote_star_d default-'+rank+'" title="总计 '+voteUserCount+' 人投票，共 '+voteRank+' 分"><div class="spc vpic vote-dft">&nbsp;</div>'];
}
ss.push('</div>');
return ss.join('');
},
voteMusicRank:function(id,type,rank){
if(UD.visitorRank<MusicConst.RANK_GUEST){
return showLoginDlg();
}
var albumWrap=this.albumWrapManager.getSelectedAlbumWrap();
var music=albumWrap.getMusic(id);
if(type=='r_music'){
if(music&&music.circleMusic){
MusicTrackBean.voteMusicRank(id,rank,function(s){
if(s){
dwrlog('投票成功!','ok');
music.circleMusic.voteUserCount++;
music.circleMusic.voteRank+=rank;
$(type+id).innerHTML=this.showMusicRank(id,type,rank,music.circleMusic.voteRank,music.circleMusic.voteUserCount,false);
}else{
dwrlog('投票失败!','error');
}
}.bind(this));
}
}else if(type=='r_diy'){
if(music){
if(UD.visitorRank==MusicConst.RANK_OWNER){
dwrlog('博主本人不能对自己的原创音乐投票!','error');
return;
}
DiyMusicBean.voteMusicRank(id,rank,function(s){
if(s){
dwrlog('投票成功!','ok');
music.voteUserCount++;
music.voteRank+=rank;
$(type+id).innerHTML=this.showMusicRank(id,type,rank,music.voteRank,music.voteUserCount,false);
}else{
dwrlog('投票失败!','error');
}
});
}
}
},
copyUrl:function(target){
target=$(target);
try{
if(UD.visitorRank==MusicConst.RANK_TEST)
target.innerHTML='http://blog.163.com/m/';
else
target.innerHTML='http://'+DomainMap.getParentDomain(UD.hostName)+'/m/?t=0&aid='+this.albumWrapManager.getSelectedAlbumWrap().getId();
var rng=document.body.createTextRange();
rng.moveToElementText(target);
rng.select();
rng.execCommand("Copy");
window.focus();
alert("复制音乐盒地址成功，可直接粘帖告诉博友");
}catch(ex){
alert("你的浏览器不支持直接复制操作，你可以手动复制浏览器地址栏地址");
};
},
visitMusicAlbum:function(){
if(UD.hostName)
window.open('http://'+DomainMap.getParentDomain(UD.hostName)+'/music/entry/'+this.albumWrapManager.getSelectedAlbumWrap().getId()+'/','_blank');
},
shareAlbum:function(){
if(UD.visitorRank<MusicConst.RANK_GUEST){
return showLoginDlg();
}
commonShareObj.shareResource('musicAlbum',this.albumWrapManager.getSelectedAlbumWrap().getName(),'http://'+DomainMap.getParentDomain(UD.hostName)+'/m/?t=0&aid='+this.albumWrapManager.getSelectedAlbumWrap().getId(),{needCover:false,left:180,top:150});
},
shareMusic:function(){
var music=this.playList.current();
if(!music)return;
if(UD.visitorRank<MusicConst.RANK_GUEST){
return showLoginDlg();
}
var url,type;
if(music.isDiy){
url='http://'+DomainMap.getParentDomain(UD.hostName)+'/music/diy/entry/'+music.id+'/';
type='diyMusic';
}else{
url='http://'+DomainMap.getParentDomain(UD.hostName)+'/m/?t=3&mid='+encodeURIComponent(music.id)+'&aid='+encodeURIComponent(music.albumId)+'/';
type='music';
}
commonShareObj.shareResource(type,music.name,url,{needCover:false,left:180,top:150,title:'点歌'});
},
search:function(event){
if($('$_search_form')&&(!event||event.keyCode==Event.KEY_RETURN)){
$("$_search_form").submit();
}
},
showAlbumGroup:function(obj,target,forceOpen){
if($(obj)&&$(target)){
if($(target).style.display=='none'||forceOpen){
MusicHelper.applyOverCss(obj,true,'toggle');
$(target).style.display='';
}else{
MusicHelper.applyOverCss(obj,false,'toggle');
$(target).style.display='none';
}
}
},
_initUI:function(){
if(UD.visitorRank==MusicConst.RANK_OWNER){
this._initAlbumManagerLayer();
}
this._initMusicModeLayer();
if(!this.musicBoxControl.mediaPlayer.versionValid){
var _c;
if(MusicConst.useCom){
_c="您需要升级Windows Media Player至7.0以上版本，才可以使用音乐盒功能！<br/><br/><a href='http://www.microsoft.com/windows/windowsmedia/cn/player/download/download.aspx' target='_blank'>Windows Media Player官方下载地址</a>";
}else{
_c="您需要升级flash player至9.0以上版本，才可以使用音乐盒功能！<br/><br/><a href='http://www.adobe.com/cn/products/flashplayer/' target='_blank'>Adobe Flash Player官方下载地址</a>";
}
this._showMessageBoxLayer(_c);
}
new Ajax.Autocompleter('$_search_key','$_search_suggest','/s/suggest.s',{
method:'get',parameters:'t=music',paramName:'p',tokens:[]
});
},
_initAlbumManagerLayer:function(){
Element.show('$_bt_album_manager');
var pos=Position.cumulativeOffset($('$_bt_album_manager'));
pos[1]+=$('$_bt_album_manager').offsetHeight;
this.albumManagerLayer=this.jsWindowManager.createWindow('album_manager',{
className:"layer-album-manager",panelClassName:false,left:pos[0],top:pos[1],width:80,height:20,hasSystemBar:false
});
this.albumManagerLayer.panel.innerHTML=this._renderTemplate(MusicTemplate.albumManagerTemplate,{host:UD.hostName});
this.simplePageLayer.addPageLayer(this.albumManagerLayer.windowHtml.id,'$_bt_album_manager',null,{
openHandler:this._showAlbumManagerLayer.bind(this),
closeHandler:this._closeAlbumManagerLayer.bind(this)
});
},
_initMusicModeLayer:function(){
var pos=Position.cumulativeOffset($('$_bt_mode'));
pos[1]+=$('$_bt_mode').offsetHeight;
this.musicModeLayer=this.jsWindowManager.createWindow('music_mode',{
className:"layer-music-mode",panelClassName:false,left:pos[0],top:pos[1],width:80,height:20,hasSystemBar:false
});
this.simplePageLayer.addPageLayer(this.musicModeLayer.windowHtml.id,'$_bt_mode',null,{
openHandler:this._showModeLayer.bind(this),
closeHandler:this._closeModeLayer.bind(this)
});
},
_showModeLayer:function(){
if(this.musicModeLayer!=null){
this.musicModeLayer.panel.innerHTML=this._renderTemplate(MusicTemplate.musicModeTemplate,{mode:this.musicBoxControl.getMusicMode(),isRepeat:this.musicBoxControl.isRepeat});
this.musicModeLayer.showWindow();
}
},
_closeModeLayer:function(){
if(this.musicModeLayer!=null)
this.musicModeLayer.hiddenWindow();
},
_showAlbumManagerLayer:function(){
if(this.albumWrapManager.canSelectedAlbumRemove()){
$('remove_album_item').style.display='block';
}else{
$('remove_album_item').style.display='none';
}
if(this.albumManagerLayer!=null)
this.albumManagerLayer.showWindow();
},
_closeAlbumManagerLayer:function(){
if(this.albumManagerLayer!=null)
this.albumManagerLayer.hiddenWindow();
},
_showUserAlbum:function(p){
MusicHelper.reset("album.select");
var albmList=this.albumWrapManager.getAlbumListByType([$MusicAlbumType.USER,$MusicAlbumType.YC]);
var albumSize=albmList.length;
var curPage=this.albumPageCtrl.getCurPage(p,albumSize);
albmList=albmList.slice(this.albumPageCtrl.pageSize*curPage[0],this.albumPageCtrl.pageSize*(curPage[0]+1));
$('$_album_list').innerHTML=MusicTemplate.albumListTemplate.processUseCache({objName:this.options.objName,albumList:albmList});
this._renderAlbumPage(curPage[0],curPage[1]);
},
_showBoardAlbum:function(){
$('$_album_list_system').innerHTML=this._renderTemplate(MusicTemplate.albumListTemplate,{albumList:this.albumWrapManager.getAlbumListByType([$MusicAlbumType.BOARD])});
},
_renderAlbumPage:function(curPage,allPage){
$('$_bt_album_up').style.visibility=(curPage<=0)?'hidden':'visible';
$('$_bt_album_down').style.visibility=(curPage>=allPage-1)?'hidden':'visible';
},
_renderSelectAlbum:function(albumWrap){
var _p=MusicHelper.get("album.select");
if(MusicHelper.test("album.select",albumWrap.getId())){
if(_p!=null)
MusicHelper.applyOverCss('album_item_'+_p,false,'selected');
MusicHelper.applyOverCss('album_item_'+albumWrap.getId(),true,'selected');
}
if(UD.visitorRank==MusicConst.RANK_OWNER||(UD.visitorRank==MusicConst.RANK_TEST)){
Element.hide('$_collect_album_zone');
if(this.albumWrapManager.canSelectedAlbumAddMusic()){
Element.show($('$_add_music_zone'));
if(this.albumWrapManager.canSelectedAlbumClearMusic()){
$('$_clear_music_list').style.visibility='visible';
}else{
$('$_clear_music_list').style.visibility='hidden';
}
}else{
Element.hide($('$_add_music_zone'));
}
}else{
Element.hide($('$_add_music_zone'));
if(this.albumWrapManager.canSelectedAlbumCollect()){
Element.show('$_collect_album_zone');
}else{
Element.hide('$_collect_album_zone');
}
}
if(albumWrap.getType()==$MusicAlbumType.NAV){
Element.addClassName('$_music_list_zone','heart-status');
}else{
Element.removeClassName('$_music_list_zone','heart-status');
}
},
_renderPlayingAlbum:function(albumWrap){
var _p=MusicHelper.get("album.playing");
if(MusicHelper.test("album.playing",albumWrap.getId())){
if(_p!=null){
MusicHelper.applyOverCss('album_item_'+_p,false,'play');
}
MusicHelper.applyOverCss('album_item_'+albumWrap.getId(),true,'play');
}
},
_getInitSelectIndex:function(){
if(this._initSelectMusicId!=null){
var id=this._initSelectMusicId;
this._initSelectMusicId=null;
return this.playList.getIndexByProp(id,'id');
}
return 0;
},
_showMusicList:function(itemList){
itemList=itemList||this.albumWrapManager.getSelectedAlbumWrap().getMusicList();
MusicHelper.reset('music.index');
var showDelete=false;
if(this.albumWrapManager.canSelectedAlbumRemoveMusic()&&(UD.visitorRank==MusicConst.RANK_OWNER||UD.visitorRank==MusicConst.RANK_TEST)){
showDelete=true;
}
if(!this.albumWrapManager.isPlayingSelectedAlbumWrap())
$('$_music_list').scrollTop=0;
$('$_music_list').innerHTML=this._renderTemplate(MusicTemplate.playListTemplate,{showDelete:showDelete,itemList:itemList});
},
_fakeSubmitAlbum:function(album_name){
var album={}
if(this._fakeId==null)this._fakeId=100;
album.id=this._fakeId++;
album.name=album_name;
$("album_add_zone").innerHTML='<div style="height:21px">提交中,请稍候...</div>';
this.addAlbum(album);
return true;
},
_submitAlbum:function(album_name){
$("album_add_zone").innerHTML='<div style="height:21px">提交中,请稍候...</div>';
MusicBean.addMusicAlbum({name:album_name},{
callback:this.addAlbum.bind(this),
errorHandler:function(errorString,ex){
this.cancelAddAlbum();
dwrlog('添加音乐专辑失败!','error');
}.bind(this)
});
return true;
},
_afterRemoveAlbum:function(id,s){
if(s){
this.albumWrapManager.removeAlbumWrap(id);
this._showUserAlbum();
var albumWrap=this.albumWrapManager.getFirstAlbumWrap();
if(albumWrap)
this.selectAlbum(albumWrap.getId());
}else{
dwrlog('删除专辑失败!','error');
}
},
_renderMusic:function(key,musicList){
if(MusicHelper.isListEmpty(musicList)){
$("search_tip").innerHTML='无法找到歌曲“'+key+'”，你可以<a href="#" onclick="'+this.options.objName+'.showMusicCustomLayer();return false;">自定义添加&gt;&gt;</a>';
return;
}
if(musicList.length==1){
this._addMusicList(musicList);
return;
}
this.searchMusicList=musicList;
this.musicAddLayer.showWindow();
this.musicAddLayer.panel.innerHTML=MusicTemplate.searchMusicResultTemplate.processUseCache({key:key,objName:this.options.objName});
$("search_music_list").innerHTML=MusicTemplate.searchMusicListTemplate.processUseCache({objName:this.options.objName,itemList:musicList});
},
_addMusicList:function(itemList,toPlay,albumWrap){
albumWrap=albumWrap||this.albumWrapManager.getSelectedAlbumWrap();
if(!albumWrap||MusicHelper.isListEmpty(itemList))return;
albumWrap.addMusicList(itemList,this._afterAddMusicList.bind(this,toPlay,albumWrap));
},
_afterAddMusicList:function(toPlay,albumWrap,itemList,toPlayId){
itemList=itemList||[];
if(this.musicAddLayer)
this.musicAddLayer.hiddenWindow();
if(this.musicCustomLayer)
this.musicCustomLayer.hiddenWindow();
if(this.musicCollectLayer)
this.musicCollectLayer.hiddenWindow();
if(toPlay){
this.selectAlbum(albumWrap.getId(),true,false,toPlayId);
}else{
if(this.albumWrapManager.isAlbumWrapSelected(albumWrap.getId())){
this._showMusicList();
}
if(this.albumWrapManager.isAlbumWrapPlaying(albumWrap.getId())){
var c=this.playList.getCount();
this.playList.appendList(itemList);
if(c==0){
this.musicBoxControl.start(true,0);
}
}
}
if(itemList.length>0&&itemList.length<=10)
MusicTrackBean.addCollectUser(itemList.pluck('name'),itemList.pluck('author'),Prototype.emptyFunction);
},
_afterRemoveMusic:function(index,s,music){
if(s){
this._showMusicList();
if(this.albumWrapManager.isPlayingSelectedAlbumWrap()){
var t=this.playList.currentIndex();
this.playList.removeMusic(index);
if(this.playList.getCount()==0){
this.lrcPlayer.reset();
this.musicBoxControl.reset();
}else if(t==index){
this.musicBoxControl.playAt(this.playList.currentIndex());
}
}
}
},
_afterClearMusicList:function(s){
if(s){
this._showMusicList();
if(this.albumWrapManager.isPlayingSelectedAlbumWrap()){
this.playList.clearMusicList();
this.lrcPlayer.reset();
this.musicBoxControl.reset();
}
}
},
_showMusicCollectLayer:function(music,albumList){
if(!this.musicCollectLayer){
this.musicCollectLayer=jsWindowManager.createWindow('music_collect',{
className:"",title:'收藏音乐',left:200,top:200,width:300,height:20
});
}
this.visitorAlbumList=albumList;
var albumWrap=this.albumWrapManager.getSelectedAlbumWrap();
this.musicCollectLayer.panel.innerHTML=this._renderTemplate(MusicTemplate.musicCollectTemplate,{id:music.id,albumId:albumWrap.getId()});
$('album_collect_select_zone').innerHTML=this._createCommonSelect(this.visitorAlbumList,'album_collect_select',-1,'95%');
this.musicCollectLayer.showWindow();
},
_showMessageBoxLayer:function(msg){
if(!this.messageBoxLayer){
this.messageBoxLayer=jsWindowManager.createWindow('message_box',{
className:"layer-message-box",title:'提示信息',left:200,top:200,width:300,height:20
});
}
this.messageBoxLayer.panel.innerHTML=msg;
this.messageBoxLayer.showWindow();
},
_renderTemplate:function(template,data){
data=data||{};
data.objName=this.options.objName;
return template.processUseCache(data);
},
_createCommonSelect:function(albumList,sId,sSelectId,sWidth){
var _sel='<select nohide="true" class="bd01 g_c_mpdin" id="'+sId+'" style="width:'+sWidth+'">';
var _albumList=albumList;
for(var i=0;i<_albumList.length;i++){
if(_albumList[i].id==sSelectId)
_sel+='<option value="'+_albumList[i].id+'" selected="true">'+_albumList[i].name+'</option>';
else
_sel+='<option value="'+_albumList[i].id+'">'+_albumList[i].name+'</option>';
}
_sel+='</select>';
return _sel;
}
}
var MusicTemplate={
playListTemplate:
new String('\
  {for item in itemList}\
  <div class="tr{if item_index%2==0} evn{else} odd{/if}" id="music_item_${item_index}">\
   <div class="td col-0"><a class="spc" hidefocus="true" title="点击播放" onclick="${objName}.playAt(${item_index});return false;">&nbsp;</a></div>\
   <div class="td col-1 thide"><a href="#" title="点击播放" onclick="${objName}.playAt(${item_index});return false;">${item.name|default:""|escape}</a></div>\
   <div class="td col-2">{if showDelete}<div class="spc" onclick="${objName}.removeMusic(${item_index},\'${item.id|default:-1}\');">&nbsp;</div>{else}<div class="empty">&nbsp;</div>{/if}</div>\
   <div class="td col-3 thide">{if item.haveCircle && item.circleMusic}<a href="http://q.163.com/musicSearch.fcs?fromBlog&a=${item.author|default:""|js_string}" target="_blank">${item.author|default:""|escape}</a>{else}${item.author|default:""|escape}{/if}</div>\
            <div class="td col-4" id="music_duration_${item_index}">${item.duration|default:0|toTimeLength}</div>\
            {if !item.isDiy}\
   <div class="td col-6 no-lrc">{if item.haveCircle && item.circleMusic && item.circleMusic.commentCount >=0}<a href="http://s.blog.163.com/s/circle.s?n=${item.name|default:""|js_string}&a=${item.author|default:""|js_string}" target="_blank">${item.circleMusic.commentCount|default:0}</a>{else}&nbsp;{/if}</div>\
            <div class="td col-7 no-lrc">{if item.haveCircle && item.circleMusic}<div id="r_music${item.id}">${musicManager.showMusicRank(item.id,\'r_music\',item.circleMusic.rank,item.circleMusic.voteRank,item.circleMusic.voteUserCount,true)}</div>{else}<div class="empty">&nbsp;</div>{/if}</div>\
            <div class="td col-8 no-lrc">{if item.haveCircle && item.circleMusic}${item.circleMusic.popularity|default:0}{else}&nbsp;{/if}</div>\
            {else}\
   <div class="td col-6 no-lrc"><a href="http://${item.userName|parentDomain}/music/diy/entry/${item.id}/" target="_blank">${item.commentCount|default:0}</a></div>\
            <div class="td col-7 no-lrc"><div id="r_diy${item.id}">${musicManager.showMusicRank(item.id,\'r_diy\',item.rank,item.voteRank,item.voteUserCount,true)}</div></div>\
            <div class="td col-8 no-lrc">${item.viewCount|default:0}</div>\
            {/if}\
          <br class="spc"/>\
         </div>\
  {/for}\
  '),
albumListTemplate:new String('{for item in albumList}\
   <div class="item" id="album_item_${item.id}"><div class="ct thide"><a hidefocus="true" href="#" onclick="${objName}.selectAlbum(\'${item.id}\');return false;"><span>&#183;</span>${item.name|default:""|escape}</a></div><div class="icn">&nbsp;</div></div>\
  {/for}\
 '),
addMusicTemplate:new String('\
  <div style="margin:10px">请输入歌曲名或歌手名，如“冰雨”，“刘德华”</div>\
  <div style="text-align:center;margin:10px;"><input type="text" id="search_key" maxlength="200" size="30" autocomplete="off"/></div>\
  <div id="search_suggest"></div>\
  <div style="margin:10px;" id="search_tip"></div>\
        <div style="margin:10px;text-align:center;"><input type="button" onclick="${objName}.searchMusic()" value="确 定" class="btncm btnok"/></div>\
 '),
searchMusicResultTemplate:new String('\
  <div style="margin:10px 0px 0px 10px;">搜索到与 ${key|default:""} 相关的音乐</div>\
  <div style="margin-left:10px;">没有你想要的歌曲？<a href="#" onclick="${objName}.showMusicCustomLayer();return false;">自定义添加&gt;&gt;</a></div>\
  <div style="margin:5px;" class="area-content-music layer-search-music">\
   <div class="header"><div class="g_p_left t0">&nbsp;</div><div class="g_p_left t1">歌曲名</div><div class="g_p_left t2">歌手</div><br class="g_p_clear" /></div>\
   <div class="content" id="search_music_list"></div>\
  </div>\
  <div style="margin:10px;">选择：<a href="#" onclick="${objName}.selectAll(1);return false;">全选</a> - <a href="#" onclick="${objName}.selectAll(-1);return false;">反选</a> - <a href="#" onclick="${objName}.selectAll(0);return false;">取消</a></div>\
  <div style="text-align:center;margin:10px;"><input class="btncm btnok" type="button" value="确定" onclick="${objName}.addMusic()"/></div>\
 '),
searchMusicListTemplate:new String('{for item in itemList}\
  <div id="search_music_item_${item_index}" class="item"><div class="g_p_left t0"><input id="search_music_check_${item_index}" type="checkbox" /></div><div class="g_p_left t1 g_t_hide">${item.name|default:""|escape}</div><div class="g_p_left t2 g_t_hide">${item.author|default:""|escape}</div><br class="g_p_clear" /></div>\
 {/for}'),
musicCustomTemplate:new String('\
  <div><label>歌曲地址：</label><input type="text" maxlength="200" id="custom_url" /><label>&nbsp;(必填)</label></div>\
  <div><label>歌曲名　：</label><input type="text" maxlength="50" id="custom_name" /><label>&nbsp;(必填)</label></div>\
  <div><label>歌手名　：</label><input type="text" maxlength="200" id="custom_author" /></div>\
  <div class="op"><input class="btncm btnok" type="button" value="确定" onclick="${objName}.addMusicCustom()"/></div>\
 '),
musicModeTemplate:new String('\
  <div class="menu">\
   <div class="item{if mode == 0} pic item_cur{/if}"><a onclick="${objName}.setMusicMode(0,\'单曲\');return false;" href="#">单曲播放</a></div>\
   <div class="item{if mode == 1} pic item_cur{/if}"><a onclick="${objName}.setMusicMode(1,\'顺序\');return false;" href="#">顺序播放</a></div>\
   <div class="item{if mode == 2} pic item_cur{/if}"><a onclick="${objName}.setMusicMode(2,\'随机\');return false;" href="#">随机播放</a></div>\
   <div class="line"></div>\
   <div class="item{if isRepeat} pic item_cur{/if}"><a onclick="${objName}.toggleMusicRepeat();return false;" href="#">循环播放</a></div>\
  </div>\
 '),
albumManagerTemplate:new String('\
  <div class="menu">\
   <div class="item"><a href="#" onclick="${objName}.showAddAlbumLayer();return false;">创建专辑</a></div>\
   <div id="remove_album_item" class="item"><a href="#" onclick="${objName}.removeAlbum();return false;">删除专辑</a></div>\
   {if host!=null}<div class="item"><a href="http://blog.163.com/${host}/musicalbum/edit/" target="_blank">管理专辑</a></div>{/if}\
  </div>\
 '),
albumAddTemplate:new String('\
      <div style="margin:10px;">请输入专辑名称</div>\
     <div style="margin:10px;text-align:center;"><input type="text" size="30" maxlength="200" id="album_add_name" /></div>\
     <div style="margin:10px;text-align:center;" id="album_add_zone"><input type="button" onclick="${objName}.submitAddAlbum();" value="确 定" class="btncm btnok"/></div>\
 '),
musicCommendListTemplate:new String('\
  {for item in itemList}\
  <div class="item thide"><span class="lnk_clr">&#183;</span><a href="#" hidefocus="true" onclick="${objName}.addMusic(\'${item.id}\');return false;">${item.name|default:""|escape}</a></div>\
  {forelse}<div style="color:#aaa">没有相关推荐</div>\
  {/for}\
 '),
musicListenerListTemplate:new String('\
  {for item in itemList}\
          <div class="item thide"><a href="http://blog.163.com/${decodeURIComponent(item.userName)|escape}/" target="_blank" title="${decodeURIComponent(item.nickName)|escape}"><img class="separa" src="${decodeURIComponent(item.avatar)|profile_img|escape}"/></a></div>\
  {forelse}<div style="color:#aaa">没有其他人在听</div>\
        {/for}\
 '),
musicLinkTemplate:new String('\
  <div class="songInfo" id="$_music_info"></div>\
  <div class="area-2">\
           <div class="msc-lists">\
              <label>相似歌曲：</label>\
              <div class="msc-case" id="$_commend_list"></div>\
              <br class="spc"/>\
           </div>\
           <div class="usr-lists">\
              <label>谁也在听：</label>\
              <div class="usr-case" id="$_listener_list"></div>\
              <br class="spc" />\
          </div>\
       </div>\
 '),
musicInfoTemplate:new String('\
    {if UD.visitorRank == MusicConst.RANK_OWNER && !music.isBoard}  <a href="#" onclick="musicManager.shareMusic();return false;">点歌</a>{else}\
    {if (!music.isDiy && music.musicType!=2)} <a href="#" onclick="musicManager.collectMusic();return false;">收藏歌曲</a>{/if}{/if}\
    {if !music.isDiy && music.haveCircle} | <a href="#" onclick="musicManager.postMusicComment();return false;">评论歌曲</a>{/if}\
 '),
musicCollectTemplate:new String('\
  <div style="margin:10px;"><label>选择专辑：</label><span id="album_collect_select_zone"></span></div>\
  <div style="text-align:center;margin:10px;"><input class="btncm btnok" type="button" value="确定" onclick="${objName}.submitCollectMusic(\'${id}\',\'${albumId}\');"/></div>\
 '),
musicCommentTemplate:new String('\
  <div><label>标题:</label> <input id="comment_title" type="text" size="25"/></div>\
  <div><label>内容:</label> <textarea id="comment_content"></textarea></div>\
        <div style="margin: 10px; text-align: center;"><input type="button" onclick="${objName}.submitMusicComment(\'${music.name|default:""|js_string}\',\'${music.author|default:""|js_string}\');" value="确定" class="btncm btnok"/><input type="button" onclick="${objName}.closeMusicComment();" value="取消" class="btncm btnok"/></div>\
 '),
musicFavourTestTemplate:new String('\
  <div>我非常喜欢的歌手（最多设置10个，用“，”分隔）</div>\
  <div><textarea id="favour_author_like">${like|default:""}</textarea></div>\
  <div id="favour_author_like_suggest"></div>\
  <div>我无法忍受的歌手（最多设置10个，用“，”分隔）</div>\
  <div><textarea id="favour_author_dislike">${dislike|default:""}</textarea></div>\
  <div id="favour_author_dislike_suggest"></div>\
        <div style="margin: 10px; text-align: center;"><input type="button" onclick="${objName}.submitMusicFavourTest();" value="确定" class="btncm btnok"/><input type="button" onclick="${objName}.closeMusicFavourTest();" value="跳过" class="btncm btnok"/></div>\
        <div>小贴士：对歌曲打分可以帮助我们更好的了解你的音乐口味</div>\
 ')
}
var PLAY_MODE_PREEMT=true;
var musicTask;
var mediaPlay;
var playList;
var lrcPlayer;
var musicBoxControl;
var musicBoxPanel;
var musicManager;
var musicCommend;
var musicLoading;
var jsWindowManager;
var simplePageLayer;
MusicConst.RANK_OWNER=10000;
MusicConst.RANK_GUEST=0;
MusicConst.RANK_ANONYMOUS=-100;
MusicConst.RANK_TEST=-10000;
var UD={
hostName:null,
hostNickname:null,
visitorName:null,
visitorNickname:null,
visitorAvatar:null,
visitorRank:MusicConst.RANK_TEST
};
UD.layer=UD.body=$('box-body');
var systemAlbumList=[{id:'new',name:'新歌推荐'},{id:'hot',name:'热门歌曲'},{id:'activities',name:'活动专辑'}];
function afterLogin(loginName){
if(!MusicHelper.isBlank(loginName)){
if(UD.visitorRank>MusicConst.RANK_TEST){
window.location.reload();
}else{
window.location='http://'+DomainMap.getParentDomain(loginName)+'/m/';
}
}
}
function appendMusicList(musicList){
var musics=convertMusicList(musicList);
musicManager.addMusicList(musics,false,MusicConst.TEST_ALBUM);
}
function convertMusicList(musicList){
var musics=[];
musicList=musicList||[];
for(var i=0,l=musicList.length;i<l;i++){
var e=musicList[i];
if(e){
musics.push(e);
}
}
return musics;
}
function init_musicbox(hostName,visitorName,visitorNickName,visitorAvatar,albumList){
UD.hostName=hostName;
UD.visitorName=visitorName;
UD.visitorNickname=visitorNickName;
UD.visitorAvatar=visitorAvatar;
if(!MusicHelper.isBlank(hostName)){
if(MusicHelper.isBlank(visitorName)){
UD.visitorRank=MusicConst.RANK_ANONYMOUS;
}else{
UD.visitorRank=(hostName==visitorName)?MusicConst.RANK_OWNER:MusicConst.RANK_GUEST;
}
}
MusicSearch.userName=visitorName;
MusicSearch.nickName=visitorNickName;
MusicSearch.avatar=visitorAvatar;
jsWindowManager=new NetEase.JSWindowManager({allowDrag:false});
simplePageLayer=new NetEase.SimplePageLayer();
musicTask=new MusicTask(MusicConst.MUSIC_TASK_PERIOD);
mediaPlay=new MediaPlayer("mediaPlay");
playList=new PlayList();
lrcPlayer=new LrcPlayer('$_lrc_zone',playList,mediaPlay,musicTask);
musicBoxControl=new MusicBoxControl(playList,mediaPlay,{objName:'musicBoxControl',tellStat:true});
musicBoxPanel=new MusicBoxPanel(musicBoxControl,{debug:false});
musicManager=new MusicManager(playList,musicBoxControl,lrcPlayer,{objName:'musicManager'});
musicCommend=new MusicCommend(playList,mediaPlay,musicTask,{objName:'musicCommend',
addCallback:function(music){musicManager.addMusicList([music],true,MusicConst.TEST_ALBUM);}
});
musicLoading=new MusicLoading('box-body');
albumList=albumList||[];
systemAlbumList=systemAlbumList||[];
if(!MusicHelper.isListEmpty(albumList)){
musicManager.showAlbumGroup('$_title_user','$_album_list_user',true);
}
if(!MusicHelper.isListEmpty(systemAlbumList)){
musicManager.showAlbumGroup('$_title_system','$_album_list_system',true);
}
var albumWrapList=[];
albumList.each(function(e){albumWrapList.push(new UserMusicAlbumWrap(e))});
albumWrapList.push(new TestMusicAlbumWrap({id:MusicConst.TEST_ALBUM,name:'音乐试听'}));
albumWrapList.push(new NavigatorMusicAlbmWrap({id:'navigator',name:'随心听听'}));
if(UD.visitorRank>MusicConst.RANK_TEST){
albumWrapList.push(new YCMusicAlbumWrap({id:MusicConst.DIY_ALBUM,name:'原创音乐'}));
}
systemAlbumList.each(function(e){albumWrapList.push(new BoardMusicAlbumWrap(e))});
var params=MusicHelper.parseParam();
var selectedId=albumWrapList[0].getId();
if(params['t']==1){
if(params['newPage']==1)
document.domain="163.com";
selectedId=MusicConst.TEST_ALBUM;
var musicList=(window.opener&&window.opener.musicObj)?window.opener.musicObj.getToPlayMusic():null;
musicManager.loadAlbum(albumWrapList,selectedId,null,convertMusicList(musicList));
}else if(params['t']==2){
selectedId=MusicConst.TEST_ALBUM;
var simple=params['s']==1;
var music={};
music.name=decodeURIComponent(params['n']);
music.author=decodeURIComponent(params['a']);
music.wapId=decodeURIComponent(params['w']);
if(params['l'])
music.lrc=decodeURIComponent(simple?params['l']:base64decode(params['l']));
if(params['u'])
music.url=decodeURIComponent(simple?params['u']:base64decode(params['u']));
musicManager.loadAlbum(albumWrapList,selectedId,null,convertMusicList([music]));
}else if(params['t']==3){
var aId=decodeURIComponent(params['aid']);
if(aId){
albumWrapList.each(function(e){
if(e.getId()==aId)
selectedId=aId;
}
)
}
musicManager.loadAlbum(albumWrapList,selectedId,decodeURIComponent(params['mid']));
}else{
var aId=decodeURIComponent(params['aid']);
if(aId){
albumWrapList.each(function(e){
if(e.getId()==aId)
selectedId=aId;
}
)
}
musicManager.loadAlbum(albumWrapList,selectedId);
}
}
