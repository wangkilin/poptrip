var gNameSpace={
register:function(sNameSpace){
var _aDomains=sNameSpace.split('.');
var _oParent=window;
for(var i=0,l=_aDomains.length;i<l;i++){
_oParent=(_oParent[_aDomains[i]]=_oParent[_aDomains[i]]||{});
}
}
};
var gCodeLoad={
load:function(aCode,bSeq,fnOnLoad){
for(var i=0,l=aCode.length;i<l;i++){
this._aCodeSrc.push(aCode[i]);
}
this._bSequence=bSeq;
this._fnOnLoad=fnOnLoad||function(){};
this._oLoadParam=arguments[3]||{};
if(bSeq){
this._fnLoadOne();
}else{
this._fnLoadAll();
}
if(!this._oInterval)
this._oInterval=setInterval(this._fnCheck,50);
},
regLoaded:function(sGUID){
for(var i=0,l=this._aCodeSrc.length;i<l;i++){
if(this._aCodeSrc[i][0]==sGUID){
this._aCodeLoad.push(sGUID);
return;
}
}
},
_fnLoadAll:function(){
var _oScript=null;
for(var i=0,l=this._aCodeSrc.length;i<l;i++){
_oScript=document.createElement('script');
_oScript.type='text/javascript';
_oScript.src=this._aCodeSrc[i][1];
document.body.appendChild(_oScript);
}
},
_fnLoadOne:function(){
this._iCurLoad++;
var _oScript=document.createElement('script');
_oScript.type='text/javascript';
_oScript.src=this._aCodeSrc[this._iCurLoad][1];
document.body.appendChild(_oScript);
},
_fnCheck:function(){
var _oThis=gCodeLoad;
if(!_oThis._bSequence){
if(_oThis._aCodeSrc.length!=_oThis._aCodeLoad.length)
return;
}else{
var _iLoadedLen=_oThis._aCodeLoad.length;
if(_oThis._aCodeSrc[_oThis._iCurLoad][0]!=_oThis._aCodeLoad[_iLoadedLen-1]){
return;
}
if(_oThis._iCurLoad!=_oThis._aCodeSrc.length-1){
_oThis._fnLoadOne();
return;
}
}
clearInterval(_oThis._oInterval);
try{
_oThis._fnOnLoad(_oThis._oLoadParam);
}catch(e){
if(/MSIE/.test(navigator.userAgent))
alert(e.message);
else if(navigator.userAgent.indexOf("Firefox")!=-1){
var _sMessage=e.fileName+'\nLine: '+e.lineNumber+'\n'+e.message+'\n'+e.stack;
alert(_sMessage);
}
}
},
_bSequence:false,
_iCurLoad:-1,
_fnOnLoad:function(){},
_oLoadParam:{},
_oInterval:null,
_aCodeLoad:[],
_aCodeSrc:[]
};
gNameSpace.register('NEUtil');
NEUtil.MD5={
hex:function(sValue){
return this._fnBin2Hex(this._fnCore(this._fnStr2Bin(sValue)),sValue.length*this._nChrsz);
},
str:function(sValue){
return this._fnStr2B64(this._fnCore(this._fnStr2Bin(sValue)),sValue.length*this._nChrsz);
},
_fnBin2Hex:function(aBinArray){
var _sHexTab=this._sHexTab;
var _sStr="";
for(var i=0,l=aBinArray.length*4;i<l;i++){
_sStr+=_sHexTab.charAt((aBinArray[i>>2]>>((i%4)*8+4))&0xF);
_sStr+=_sHexTab.charAt((aBinArray[i>>2]>>((i%4)*8))&0xF);
}
return _sStr;
},
_fnStr2Bin:function(sStr){
var _aBin=[];
var _nMask=(1<<this._nChrsz)-1;
for(var i=0,l=sStr.length*this._nChrsz;i<l;i+=this._nChrsz)
_aBin[i>>5]|=(sStr.charCodeAt(i/this._nChrsz)&_nMask)<<(i%32);
return _aBin;
},
_fnCmm:function(q,a,b,x,s,t){
return this._fnAdd(this._fnBr(this._fnAdd(this._fnAdd(a,q),this._fnAdd(x,t)),s),b);
},
_fnFf:function(a,b,c,d,x,s,t){
return this._fnCmm((b&c)|((~b)&d),a,b,x,s,t);
},
_fnGg:function(a,b,c,d,x,s,t){
return this._fnCmm((b&d)|(c&(~d)),a,b,x,s,t);
},
_fnHh:function(a,b,c,d,x,s,t){
return this._fnCmm(b^c^d,a,b,x,s,t);
},
_fnIi:function(a,b,c,d,x,s,t){
return this._fnCmm(c^(b|(~d)),a,b,x,s,t);
},
_fnAdd:function(nX,nY){
var _nLsw=(nX&0xFFFF)+(nY&0xFFFF);
var _nMsw=(nX>>16)+(nY>>16)+(_nLsw>>16);
return(_nMsw<<16)|(_nLsw&0xFFFF);
},
_fnBr:function(nNum,nCount){
return(nNum<<nCount)|(nNum>>>(32-nCount));
},
_fnCore:function(sX,nLen){
sX[nLen>>5]|=0x80<<((nLen)%32);
sX[(((nLen+64)>>>9)<<4)+14]=nLen;
var _a=1732584193;
var _b=-271733879;
var _c=-1732584194;
var _d=271733878;
for(var i=0,l=sX.length;i<l;i+=16){
var _olda=_a;
var _oldb=_b;
var _oldc=_c;
var _oldd=_d;
_a=this._fnFf(_a,_b,_c,_d,sX[i+0],7,-680876936);
_d=this._fnFf(_d,_a,_b,_c,sX[i+1],12,-389564586);
_c=this._fnFf(_c,_d,_a,_b,sX[i+2],17,606105819);
_b=this._fnFf(_b,_c,_d,_a,sX[i+3],22,-1044525330);
_a=this._fnFf(_a,_b,_c,_d,sX[i+4],7,-176418897);
_d=this._fnFf(_d,_a,_b,_c,sX[i+5],12,1200080426);
_c=this._fnFf(_c,_d,_a,_b,sX[i+6],17,-1473231341);
_b=this._fnFf(_b,_c,_d,_a,sX[i+7],22,-45705983);
_a=this._fnFf(_a,_b,_c,_d,sX[i+8],7,1770035416);
_d=this._fnFf(_d,_a,_b,_c,sX[i+9],12,-1958414417);
_c=this._fnFf(_c,_d,_a,_b,sX[i+10],17,-42063);
_b=this._fnFf(_b,_c,_d,_a,sX[i+11],22,-1990404162);
_a=this._fnFf(_a,_b,_c,_d,sX[i+12],7,1804603682);
_d=this._fnFf(_d,_a,_b,_c,sX[i+13],12,-40341101);
_c=this._fnFf(_c,_d,_a,_b,sX[i+14],17,-1502002290);
_b=this._fnFf(_b,_c,_d,_a,sX[i+15],22,1236535329);
_a=this._fnGg(_a,_b,_c,_d,sX[i+1],5,-165796510);
_d=this._fnGg(_d,_a,_b,_c,sX[i+6],9,-1069501632);
_c=this._fnGg(_c,_d,_a,_b,sX[i+11],14,643717713);
_b=this._fnGg(_b,_c,_d,_a,sX[i+0],20,-373897302);
_a=this._fnGg(_a,_b,_c,_d,sX[i+5],5,-701558691);
_d=this._fnGg(_d,_a,_b,_c,sX[i+10],9,38016083);
_c=this._fnGg(_c,_d,_a,_b,sX[i+15],14,-660478335);
_b=this._fnGg(_b,_c,_d,_a,sX[i+4],20,-405537848);
_a=this._fnGg(_a,_b,_c,_d,sX[i+9],5,568446438);
_d=this._fnGg(_d,_a,_b,_c,sX[i+14],9,-1019803690);
_c=this._fnGg(_c,_d,_a,_b,sX[i+3],14,-187363961);
_b=this._fnGg(_b,_c,_d,_a,sX[i+8],20,1163531501);
_a=this._fnGg(_a,_b,_c,_d,sX[i+13],5,-1444681467);
_d=this._fnGg(_d,_a,_b,_c,sX[i+2],9,-51403784);
_c=this._fnGg(_c,_d,_a,_b,sX[i+7],14,1735328473);
_b=this._fnGg(_b,_c,_d,_a,sX[i+12],20,-1926607734);
_a=this._fnHh(_a,_b,_c,_d,sX[i+5],4,-378558);
_d=this._fnHh(_d,_a,_b,_c,sX[i+8],11,-2022574463);
_c=this._fnHh(_c,_d,_a,_b,sX[i+11],16,1839030562);
_b=this._fnHh(_b,_c,_d,_a,sX[i+14],23,-35309556);
_a=this._fnHh(_a,_b,_c,_d,sX[i+1],4,-1530992060);
_d=this._fnHh(_d,_a,_b,_c,sX[i+4],11,1272893353);
_c=this._fnHh(_c,_d,_a,_b,sX[i+7],16,-155497632);
_b=this._fnHh(_b,_c,_d,_a,sX[i+10],23,-1094730640);
_a=this._fnHh(_a,_b,_c,_d,sX[i+13],4,681279174);
_d=this._fnHh(_d,_a,_b,_c,sX[i+0],11,-358537222);
_c=this._fnHh(_c,_d,_a,_b,sX[i+3],16,-722521979);
_b=this._fnHh(_b,_c,_d,_a,sX[i+6],23,76029189);
_a=this._fnHh(_a,_b,_c,_d,sX[i+9],4,-640364487);
_d=this._fnHh(_d,_a,_b,_c,sX[i+12],11,-421815835);
_c=this._fnHh(_c,_d,_a,_b,sX[i+15],16,530742520);
_b=this._fnHh(_b,_c,_d,_a,sX[i+2],23,-995338651);
_a=this._fnIi(_a,_b,_c,_d,sX[i+0],6,-198630844);
_d=this._fnIi(_d,_a,_b,_c,sX[i+7],10,1126891415);
_c=this._fnIi(_c,_d,_a,_b,sX[i+14],15,-1416354905);
_b=this._fnIi(_b,_c,_d,_a,sX[i+5],21,-57434055);
_a=this._fnIi(_a,_b,_c,_d,sX[i+12],6,1700485571);
_d=this._fnIi(_d,_a,_b,_c,sX[i+3],10,-1894986606);
_c=this._fnIi(_c,_d,_a,_b,sX[i+10],15,-1051523);
_b=this._fnIi(_b,_c,_d,_a,sX[i+1],21,-2054922799);
_a=this._fnIi(_a,_b,_c,_d,sX[i+8],6,1873313359);
_d=this._fnIi(_d,_a,_b,_c,sX[i+15],10,-30611744);
_c=this._fnIi(_c,_d,_a,_b,sX[i+6],15,-1560198380);
_b=this._fnIi(_b,_c,_d,_a,sX[i+13],21,1309151649);
_a=this._fnIi(_a,_b,_c,_d,sX[i+4],6,-145523070);
_d=this._fnIi(_d,_a,_b,_c,sX[i+11],10,-1120210379);
_c=this._fnIi(_c,_d,_a,_b,sX[i+2],15,718787259);
_b=this._fnIi(_b,_c,_d,_a,sX[i+9],21,-343485551);
_a=this._fnAdd(_a,_olda);
_b=this._fnAdd(_b,_oldb);
_c=this._fnAdd(_c,_oldc);
_d=this._fnAdd(_d,_oldd);
}
return Array(_a,_b,_c,_d);
},
_bHexcase:false,
_sB64pad:'',
_nChrsz:8,
_sHexTab:this._bHexcase?'0123456789ABCDEF':'0123456789abcdef',
_sTab:'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'
};
gCodeLoad.regLoaded('{F6707A8B-BD1E-413e-AE9F-04A9B6A9506C}');
if(dwr==null)var dwr={};
if(dwr.engine==null)dwr.engine={};
if(DWREngine==null)var DWREngine=dwr.engine;
dwr.engine.setErrorHandler=function(handler){
dwr.engine._errorHandler=handler;
};
dwr.engine.setWarningHandler=function(handler){
dwr.engine._warningHandler=handler;
};
dwr.engine.setTextHtmlHandler=function(handler){
dwr.engine._textHtmlHandler=handler;
}
dwr.engine.setTimeout=function(timeout){
dwr.engine._timeout=timeout;
};
dwr.engine.setPreHook=function(handler){
dwr.engine._preHook=handler;
};
dwr.engine.setPostHook=function(handler){
dwr.engine._postHook=handler;
};
dwr.engine.setHeaders=function(headers){
dwr.engine._headers=headers;
};
dwr.engine.setParameters=function(parameters){
dwr.engine._parameters=parameters;
};
dwr.engine.XMLHttpRequest=1;
dwr.engine.IFrame=2;
dwr.engine.ScriptTag=3;
dwr.engine.setRpcType=function(newType){
if(newType!=dwr.engine.XMLHttpRequest&&newType!=dwr.engine.IFrame&&newType!=dwr.engine.ScriptTag){
dwr.engine._handleError(null,{name:"dwr.engine.invalidRpcType",message:"RpcType must be one of dwr.engine.XMLHttpRequest or dwr.engine.IFrame or dwr.engine.ScriptTag"});
return;
}
dwr.engine._rpcType=newType;
};
dwr.engine.setHttpMethod=function(httpMethod){
if(httpMethod!="GET"&&httpMethod!="POST"){
dwr.engine._handleError(null,{name:"dwr.engine.invalidHttpMethod",message:"Remoting method must be one of GET or POST"});
return;
}
dwr.engine._httpMethod=httpMethod;
};
dwr.engine.setOrdered=function(ordered){
dwr.engine._ordered=ordered;
};
dwr.engine.setAsync=function(async){
dwr.engine._async=async;
};
dwr.engine.setActiveReverseAjax=function(activeReverseAjax){
if(activeReverseAjax){
if(dwr.engine._activeReverseAjax)return;
dwr.engine._activeReverseAjax=true;
dwr.engine._poll();
}
else{
if(dwr.engine._activeReverseAjax&&dwr.engine._pollReq)dwr.engine._pollReq.abort();
dwr.engine._activeReverseAjax=false;
}
};
dwr.engine.setPollType=function(newPollType){
if(newPollType!=dwr.engine.XMLHttpRequest&&newPollType!=dwr.engine.IFrame){
dwr.engine._handleError(null,{name:"dwr.engine.invalidPollType",message:"PollType must be one of dwr.engine.XMLHttpRequest or dwr.engine.IFrame"});
return;
}
dwr.engine._pollType=newPollType;
};
dwr.engine.defaultErrorHandler=function(message,ex){
dwr.engine._debug("Error: "+ex.name+", "+ex.message,true);
if(message==null||message=="")alert("A server error has occured. More information may be available in the console.");
else if(message.indexOf("0x80040111")!=-1)dwr.engine._debug(message);
else{};
};
dwr.engine.defaultWarningHandler=function(message,ex){
dwr.engine._debug(message);
};
dwr.engine.beginBatch=function(){
if(dwr.engine._batch){
dwr.engine._handleError(null,{name:"dwr.engine.batchBegun",message:"Batch already begun"});
return;
}
dwr.engine._batch=dwr.engine._createBatch();
};
dwr.engine.endBatch=function(options){
var batch=dwr.engine._batch;
if(batch==null){
dwr.engine._handleError(null,{name:"dwr.engine.batchNotBegun",message:"No batch in progress"});
return;
}
dwr.engine._batch=null;
if(batch.map.callCount==0)return;
if(options)dwr.engine._mergeBatch(batch,options);
if(dwr.engine._ordered&&dwr.engine._batchesLength!=0){
dwr.engine._batchQueue[dwr.engine._batchQueue.length]=batch;
}
else{
dwr.engine._sendData(batch);
}
};
dwr.engine.setPollMethod=function(type){dwr.engine.setPollType(type);};
dwr.engine.setMethod=function(type){dwr.engine.setRpcType(type);};
dwr.engine.setVerb=function(verb){dwr.engine.setHttpMethod(verb);};
dwr.engine._origScriptSessionId="${scriptSessionId}";
dwr.engine._sessionCookieName="${sessionCookieName}";
dwr.engine._allowGetForSafariButMakeForgeryEasier="${allowGetForSafariButMakeForgeryEasier}";
dwr.engine._scriptTagProtection="${scriptTagProtection}";
dwr.engine._defaultPath="${defaultPath}";
dwr.engine._scriptSessionId=null;
dwr.engine._getScriptSessionId=function(){
if(dwr.engine._scriptSessionId==null){
dwr.engine._scriptSessionId=dwr.engine._origScriptSessionId+Math.floor(Math.random()*1000);
}
return dwr.engine._scriptSessionId;
};
dwr.engine._errorHandler=dwr.engine.defaultErrorHandler;
dwr.engine._warningHandler=dwr.engine.defaultWarningHandler;
dwr.engine._preHook=null;
dwr.engine._postHook=null;
dwr.engine._batches={};
dwr.engine._batchesLength=0;
dwr.engine._batchQueue=[];
dwr.engine._rpcType=dwr.engine.XMLHttpRequest;
dwr.engine._httpMethod="POST";
dwr.engine._ordered=false;
dwr.engine._async=true;
dwr.engine._batch=null;
dwr.engine._timeout=0;
dwr.engine._DOMDocument=["Msxml2.DOMDocument.6.0","Msxml2.DOMDocument.3.0","Msxml2.DOMDocument.4.0","Msxml2.DOMDocument.5.0","MSXML2.DOMDocument","MSXML.DOMDocument","Microsoft.XMLDOM"];
dwr.engine._XMLHTTP=["Msxml2.XMLHTTP.6.0","Msxml2.XMLHTTP.3.0","Msxml2.XMLHTTP.4.0","MSXML2.XMLHTTP.5.0","MSXML2.XMLHTTP","Microsoft.XMLHTTP"];
dwr.engine._activeReverseAjax=false;
dwr.engine._pollType=dwr.engine.XMLHttpRequest;
dwr.engine._outstandingIFrames=[];
dwr.engine._pollReq=null;
dwr.engine._pollCometInterval=200;
dwr.engine._pollRetries=0;
dwr.engine._maxPollRetries=0;
dwr.engine._textHtmlHandler=null;
dwr.engine._headers=null;
dwr.engine._parameters=null;
dwr.engine._postSeperator="\n";
dwr.engine._defaultInterceptor=function(data){return data;}
dwr.engine._urlRewriteHandler=dwr.engine._defaultInterceptor;
dwr.engine._contentRewriteHandler=dwr.engine._defaultInterceptor;
dwr.engine._replyRewriteHandler=dwr.engine._defaultInterceptor;
dwr.engine._nextBatchId=0;
dwr.engine._propnames=["rpcType","httpMethod","async","timeout","errorHandler","warningHandler","textHtmlHandler"];
dwr.engine._partialResponseNo=0;
dwr.engine._partialResponseYes=1;
dwr.engine._partialResponseFlush=2;
dwr.engine._unload=function(){
if(dwr.engine._batchesLength>0){
for(var i=0;i<dwr.engine._nextBatchId;i++){
var batch=dwr.engine._batches[i];
dwr.engine._abortRequest(batch);
}
}
}
dwr.engine.setUnloadHandler=function(){
if(window.attachEvent){
window.attachEvent('onbeforeunload',dwr.engine._unload);
}
else{
window.addEventListener('beforeunload',dwr.engine._unload,false);
}
}
dwr.engine.setUnloadHandler();
dwr.engine._execute=function(path,scriptName,methodName,vararg_params){
var singleShot=false;
if(dwr.engine._batch==null){
dwr.engine.beginBatch();
singleShot=true;
}
var batch=dwr.engine._batch;
var args=[];
for(var i=0;i<arguments.length-3;i++){
args[i]=arguments[i+3];
}
if(batch.path==null){
batch.path=path;
}
else{
if(batch.path!=path){
dwr.engine._handleError(batch,{name:"dwr.engine.multipleServlets",message:"Can't batch requests to multiple DWR Servlets."});
return;
}
}
var callData;
var lastArg=args[args.length-1];
if(typeof lastArg=="function"||lastArg==null)callData={callback:args.pop()};
else callData=args.pop();
dwr.engine._mergeBatch(batch,callData);
batch.handlers[batch.map.callCount]={
exceptionHandler:callData.exceptionHandler,
callback:callData.callback
};
if(batch.httpMethod=='GET'){
var cid="";
for(prop in batch.map){
if(prop!="scriptSessionId")
cid+=prop+":"+batch.map[prop]+"_";
}
cid=NEUtil.MD5.hex(cid);
batch.map["scriptSessionId"]=dwr.engine._origScriptSessionId+cid;
}
var prefix="c"+batch.map.callCount+"-";
batch.map[prefix+"scriptName"]=scriptName;
batch.map[prefix+"methodName"]=methodName;
batch.map[prefix+"id"]=batch.map.callCount;
for(i=0;i<args.length;i++){
dwr.engine._serializeAll(batch,[],args[i],prefix+"param"+i);
}
batch.map.callCount++;
if(singleShot)dwr.engine.endBatch();
};
dwr.engine._poll=function(overridePath){
if(!dwr.engine._activeReverseAjax)return;
var batch=dwr.engine._createBatch();
batch.map.id=0;
batch.map.callCount=1;
batch.isPoll=true;
if(navigator.userAgent.indexOf("Gecko/")!=-1){
batch.rpcType=dwr.engine._pollType;
batch.map.partialResponse=dwr.engine._partialResponseYes;
}
else if(document.all){
batch.rpcType=dwr.engine.IFrame;
batch.map.partialResponse=dwr.engine._partialResponseFlush;
}
else{
batch.rpcType=dwr.engine._pollType;
batch.map.partialResponse=dwr.engine._partialResponseNo;
}
batch.httpMethod="POST";
batch.async=true;
batch.timeout=0;
batch.path=(overridePath)?overridePath:dwr.engine._defaultPath;
batch.preHooks=[];
batch.postHooks=[];
batch.errorHandler=dwr.engine._pollErrorHandler;
batch.warningHandler=dwr.engine._pollErrorHandler;
batch.handlers[0]={
callback:function(pause){
dwr.engine._pollRetries=0;
setTimeout("dwr.engine._poll()",pause);
}
};
dwr.engine._sendData(batch);
if(batch.rpcType==dwr.engine.XMLHttpRequest){
dwr.engine._checkCometPoll();
}
};
dwr.engine._pollErrorHandler=function(msg,ex){
dwr.engine._pollRetries++;
dwr.engine._debug("Reverse Ajax poll failed (pollRetries="+dwr.engine._pollRetries+"): "+ex.name+" : "+ex.message);
if(dwr.engine._pollRetries<dwr.engine._maxPollRetries){
setTimeout("dwr.engine._poll()",10000);
}
else{
dwr.engine._debug("Giving up.");
}
};
dwr.engine._createBatch=function(){
var batch={
map:{
callCount:0,
scriptSessionId:dwr.engine._getScriptSessionId()
},
charsProcessed:0,paramCount:0,
headers:[],parameters:[],
isPoll:false,headers:{},handlers:{},preHooks:[],postHooks:[],
rpcType:dwr.engine._rpcType,
httpMethod:dwr.engine._httpMethod,
async:dwr.engine._async,
timeout:dwr.engine._timeout,
errorHandler:dwr.engine._errorHandler,
warningHandler:dwr.engine._warningHandler,
textHtmlHandler:dwr.engine._textHtmlHandler
};
if(dwr.engine._preHook)batch.preHooks.push(dwr.engine._preHook);
if(dwr.engine._postHook)batch.postHooks.push(dwr.engine._postHook);
var propname,data;
if(dwr.engine._headers){
for(propname in dwr.engine._headers){
data=dwr.engine._headers[propname];
if(typeof data!="function")batch.headers[propname]=data;
}
}
if(dwr.engine._parameters){
for(propname in dwr.engine._parameters){
data=dwr.engine._parameters[propname];
if(typeof data!="function")batch.parameters[propname]=data;
}
}
return batch;
}
dwr.engine._mergeBatch=function(batch,overrides){
var propname,data;
for(var i=0;i<dwr.engine._propnames.length;i++){
propname=dwr.engine._propnames[i];
if(overrides[propname]!=null)batch[propname]=overrides[propname];
}
if(overrides.preHook!=null)batch.preHooks.unshift(overrides.preHook);
if(overrides.postHook!=null)batch.postHooks.push(overrides.postHook);
if(overrides.headers){
for(propname in overrides.headers){
data=overrides.headers[propname];
if(typeof data!="function")batch.headers[propname]=data;
}
}
if(overrides.parameters){
for(propname in overrides.parameters){
data=overrides.parameters[propname];
if(typeof data!="function")batch.map["p-"+propname]=""+data;
}
}
};
dwr.engine._getJSessionId=function(){
var cookies=document.cookie.split(';');
for(var i=0;i<cookies.length;i++){
var cookie=cookies[i];
while(cookie.charAt(0)==' ')cookie=cookie.substring(1,cookie.length);
if(cookie.indexOf(dwr.engine._sessionCookieName+"=")==0){
return cookie.substring(11,cookie.length);
}
}
return"";
}
dwr.engine._checkCometPoll=function(){
for(var i=0;i<dwr.engine._outstandingIFrames.length;i++){
var text="";
var iframe=dwr.engine._outstandingIFrames[i];
try{
text=dwr.engine._getTextFromCometIFrame(iframe);
}
catch(ex){
dwr.engine._handleWarning(iframe.batch,ex);
}
if(text!="")dwr.engine._processCometResponse(text,iframe.batch);
}
if(dwr.engine._pollReq){
var req=dwr.engine._pollReq;
var text=req.responseText;
dwr.engine._processCometResponse(text,req.batch);
}
if(dwr.engine._outstandingIFrames.length>0||dwr.engine._pollReq){
setTimeout("dwr.engine._checkCometPoll()",dwr.engine._pollCometInterval);
}
};
dwr.engine._getTextFromCometIFrame=function(frameEle){
var body=frameEle.contentWindow.document.body;
if(body==null)return"";
var text=body.innerHTML;
if(text.indexOf("<PRE>")==0||text.indexOf("<pre>")==0){
text=text.substring(5,text.length-7);
}
return text;
};
dwr.engine._processCometResponse=function(response,batch){
if(batch.charsProcessed==response.length)return;
if(response.length==0){
batch.charsProcessed=0;
return;
}
var firstStartTag=response.indexOf("//#DWR-START#",batch.charsProcessed);
if(firstStartTag==-1){
batch.charsProcessed=response.length;
return;
}
var lastEndTag=response.lastIndexOf("//#DWR-END#");
if(lastEndTag==-1){
return;
}
if(response.charCodeAt(lastEndTag+11)==13&&response.charCodeAt(lastEndTag+12)==10){
batch.charsProcessed=lastEndTag+13;
}
else{
batch.charsProcessed=lastEndTag+11;
}
var exec=response.substring(firstStartTag+13,lastEndTag);
dwr.engine._receivedBatch=batch;
dwr.engine._eval(exec);
dwr.engine._receivedBatch=null;
};
dwr.engine._sendData=function(batch){
batch.map.batchId=dwr.engine._nextBatchId++;
dwr.engine._batches[batch.map.batchId]=batch;
dwr.engine._batchesLength++;
batch.completed=false;
for(var i=0;i<batch.preHooks.length;i++){
batch.preHooks[i]();
}
batch.preHooks=null;
if(batch.timeout&&batch.timeout!=0){
batch.interval=setInterval(function(){dwr.engine._abortRequest(batch);},batch.timeout);
}
if(batch.rpcType==dwr.engine.XMLHttpRequest){
if(window.XMLHttpRequest){
batch.req=new XMLHttpRequest();
}
else if(window.ActiveXObject&&!(navigator.userAgent.indexOf("Mac")>=0&&navigator.userAgent.indexOf("MSIE")>=0)){
batch.req=dwr.engine._newActiveXObject(dwr.engine._XMLHTTP);
}
}
var prop,request;
if(batch.req){
if(batch.async){
batch.req.onreadystatechange=function(){dwr.engine._stateChange(batch);};
}
if(batch.isPoll){
dwr.engine._pollReq=batch.req;
batch.req.batch=batch;
}
var indexSafari=navigator.userAgent.indexOf("Safari/");
if(indexSafari>=0){
var version=navigator.userAgent.substring(indexSafari+7);
if(parseInt(version,10)<400){
if(dwr.engine._allowGetForSafariButMakeForgeryEasier=="true")batch.httpMethod="GET";
else dwr.engine._handleWarning(batch,{name:"dwr.engine.oldSafari",message:"Safari GET support disabled. See getahead.org/dwr/server/servlet and allowGetForSafariButMakeForgeryEasier."});
}
}
batch.mode=batch.isPoll?dwr.engine._ModePlainPoll:dwr.engine._ModePlainCall;
request=dwr.engine._constructRequest(batch);
try{
batch.req.open(batch.httpMethod,request.url,batch.async);
try{
for(prop in batch.headers){
var value=batch.headers[prop];
if(typeof value=="string")batch.req.setRequestHeader(prop,value);
}
if(!batch.headers["Content-Type"])batch.req.setRequestHeader("Content-Type","text/plain");
}
catch(ex){
dwr.engine._handleWarning(batch,ex);
}
batch.req.send(request.body);
if(!batch.async)dwr.engine._stateChange(batch);
}
catch(ex){
dwr.engine._handleError(batch,ex);
}
}
else if(batch.rpcType!=dwr.engine.ScriptTag){
var idname=batch.isPoll?"dwr-if-poll-"+batch.map.batchId:"dwr-if-"+batch.map["c0-id"];
batch.div=document.createElement("div");
batch.div.innerHTML="<iframe src='javascript:void(0)' frameborder='0' style='width:0px;height:0px;border:0;' id='"+idname+"' name='"+idname+"'></iframe>";
document.body.appendChild(batch.div);
batch.iframe=document.getElementById(idname);
batch.iframe.batch=batch;
batch.mode=batch.isPoll?dwr.engine._ModeHtmlPoll:dwr.engine._ModeHtmlCall;
if(batch.isPoll)dwr.engine._outstandingIFrames.push(batch.iframe);
request=dwr.engine._constructRequest(batch);
if(batch.httpMethod=="GET"){
batch.iframe.setAttribute("src",request.url);
}
else{
batch.form=document.createElement("form");
batch.form.setAttribute("id","dwr-form");
batch.form.setAttribute("action",request.url);
batch.form.setAttribute("target",idname);
batch.form.target=idname;
batch.form.setAttribute("method",batch.httpMethod);
for(prop in batch.map){
var value=batch.map[prop];
if(typeof value!="function"){
var formInput=document.createElement("input");
formInput.setAttribute("type","hidden");
formInput.setAttribute("name",prop);
formInput.setAttribute("value",value);
batch.form.appendChild(formInput);
}
}
document.body.appendChild(batch.form);
batch.form.submit();
}
}
else{
batch.httpMethod="GET";
batch.mode=batch.isPoll?dwr.engine._ModePlainPoll:dwr.engine._ModePlainCall;
request=dwr.engine._constructRequest(batch);
batch.script=document.createElement("script");
batch.script.id="dwr-st-"+batch.map["c0-id"];
batch.script.src=request.url;
document.body.appendChild(batch.script);
}
};
dwr.engine._ModePlainCall="/call/plaincall/";
dwr.engine._ModeHtmlCall="/call/htmlcall/";
dwr.engine._ModePlainPoll="/call/plainpoll/";
dwr.engine._ModeHtmlPoll="/call/htmlpoll/";
dwr.engine._constructRequest=function(batch){
if(batch.path.charAt(0)=='/')
batch.path=batch.path.substring(1);
var request={url:batch.path+batch.mode,body:null};
if(batch.isPoll==true){
request.url+="ReverseAjax.dwr";
}
else if(batch.map.callCount==1){
request.url+=batch.map["c0-scriptName"]+"."+batch.map["c0-methodName"]+".dwr";
}
else{
request.url+="Multiple."+batch.map.callCount+".dwr";
}
var sessionMatch=location.href.match(/jsessionid=([^?]+)/);
if(sessionMatch!=null){
request.url+=";jsessionid="+sessionMatch[1];
}
var prop;
if(batch.httpMethod=="GET"){
batch.map.callCount=""+batch.map.callCount;
request.url+="?";
for(prop in batch.map){
if(typeof batch.map[prop]!="function"){
request.url+=encodeURIComponent(prop)+"="+encodeURIComponent(batch.map[prop])+"&";
}
}
request.url=request.url.substring(0,request.url.length-1);
}
else{
request.body="";
for(prop in batch.map){
if(typeof batch.map[prop]!="function"){
request.body+=prop+"="+batch.map[prop]+dwr.engine._postSeperator;
}
}
request.body=dwr.engine._contentRewriteHandler(request.body);
}
request.url=dwr.engine._urlRewriteHandler(request.url);
return request;
};
dwr.engine._stateChange=function(batch){
var toEval;
if(batch.completed){
dwr.engine._debug("Error: _stateChange() with batch.completed");
return;
}
var req=batch.req;
try{
if(req.readyState!=4)return;
}
catch(ex){
dwr.engine._handleWarning(batch,ex);
dwr.engine._clearUp(batch);
return;
}
try{
var reply=req.responseText;
reply=dwr.engine._replyRewriteHandler(reply);
var status=req.status;
if(reply==null||reply==""){
dwr.engine._handleWarning(batch,{name:"dwr.engine.missingData",message:"No data received from server"});
}
else if(status!=200){
dwr.engine._handleError(batch,{name:"dwr.engine.http."+status,message:req.statusText});
}
else{
var contentType=req.getResponseHeader("Content-Type");
if(!contentType.match(/^text\/plain/)&&!contentType.match(/^text\/javascript/)){
if(contentType.match(/^text\/html/)&&typeof batch.textHtmlHandler=="function"){
batch.textHtmlHandler();
}
else{
dwr.engine._handleWarning(batch,{name:"dwr.engine.invalidMimeType",message:"Invalid content type: '"+contentType+"'"});
}
}
else{
if(batch.isPoll&&batch.map.partialResponse==dwr.engine._partialResponseYes){
dwr.engine._processCometResponse(reply,batch);
}
else{
if(reply.search("//#DWR")==-1){
dwr.engine._handleWarning(batch,{name:"dwr.engine.invalidReply",message:"Invalid reply from server"});
}
else{
toEval=reply;
}
}
}
}
}
catch(ex){
dwr.engine._handleWarning(batch,ex);
}
dwr.engine._callPostHooks(batch);
dwr.engine._receivedBatch=batch;
if(toEval!=null)toEval=toEval.replace(dwr.engine._scriptTagProtection,"");
dwr.engine._eval(toEval);
dwr.engine._receivedBatch=null;
dwr.engine._clearUp(batch);
};
dwr.engine._remoteHandleCallback=function(batchId,callId,reply){
var batch=dwr.engine._batches[batchId];
if(batch==null){
dwr.engine._debug("Warning: batch == null in remoteHandleCallback for batchId="+batchId,true);
return;
}
dwr.engine._callPostHooks(batch);
try{
var handlers=batch.handlers[callId];
if(!handlers){
dwr.engine._debug("Warning: Missing handlers. callId="+callId,true);
}
else if(typeof handlers.callback=="function")handlers.callback(reply);
}
catch(ex){
dwr.engine._handleError(batch,ex);
}
};
dwr.engine._remoteHandleException=function(batchId,callId,ex){
var batch=dwr.engine._batches[batchId];
if(batch==null){dwr.engine._debug("Warning: null batch in remoteHandleException",true);return;}
dwr.engine._callPostHooks(batch);
var handlers=batch.handlers[callId];
if(handlers==null){dwr.engine._debug("Warning: null handlers in remoteHandleException",true);return;}
if(ex.message==undefined)ex.message="";
if(typeof handlers.exceptionHandler=="function")handlers.exceptionHandler(ex.message,ex);
else if(typeof batch.errorHandler=="function")batch.errorHandler(ex.message,ex);
};
dwr.engine._remoteHandleBatchException=function(ex,batchId){
var searchBatch=(dwr.engine._receivedBatch==null&&batchId!=null);
if(searchBatch){
dwr.engine._receivedBatch=dwr.engine._batches[batchId];
}
if(ex.message==undefined)ex.message="";
dwr.engine._handleError(dwr.engine._receivedBatch,ex);
if(searchBatch){
dwr.engine._receivedBatch=null;
dwr.engine._clearUp(dwr.engine._batches[batchId]);
}
};
dwr.engine._remotePollCometDisabled=function(ex,batchId){
dwr.engine.setActiveReverseAjax(false);
var searchBatch=(dwr.engine._receivedBatch==null&&batchId!=null);
if(searchBatch){
dwr.engine._receivedBatch=dwr.engine._batches[batchId];
}
if(ex.message==undefined)ex.message="";
dwr.engine._handleError(dwr.engine._receivedBatch,ex);
if(searchBatch){
dwr.engine._receivedBatch=null;
dwr.engine._clearUp(dwr.engine._batches[batchId]);
}
};
dwr.engine._remoteBeginIFrameResponse=function(iframe,batchId){
if(iframe!=null)dwr.engine._receivedBatch=iframe.batch;
dwr.engine._callPostHooks(dwr.engine._receivedBatch);
};
dwr.engine._remoteEndIFrameResponse=function(batchId){
dwr.engine._clearUp(dwr.engine._receivedBatch);
dwr.engine._receivedBatch=null;
};
dwr.engine._eval=function(script){
if(script==null)return null;
if(script==""){dwr.engine._debug("Warning: blank script",true);return null;}
return eval(script);
};
dwr.engine._abortRequest=function(batch){
if(batch&&!batch.completed){
clearInterval(batch.interval);
dwr.engine._clearUp(batch);
if(batch.req)batch.req.abort();
dwr.engine._handleError(batch,{name:"dwr.engine.timeout",message:"Timeout"});
}
};
dwr.engine._callPostHooks=function(batch){
if(batch.postHooks){
for(var i=0;i<batch.postHooks.length;i++){
batch.postHooks[i]();
}
batch.postHooks=null;
}
}
dwr.engine._clearUp=function(batch){
if(!batch){dwr.engine._debug("Warning: null batch in dwr.engine._clearUp()",true);return;}
if(batch.completed=="true"){dwr.engine._debug("Warning: Double complete",true);return;}
if(batch.div)batch.div.parentNode.removeChild(batch.div);
if(batch.iframe){
for(var i=0;i<dwr.engine._outstandingIFrames.length;i++){
if(dwr.engine._outstandingIFrames[i]==batch.iframe){
dwr.engine._outstandingIFrames.splice(i,1);
}
}
batch.iframe.parentNode.removeChild(batch.iframe);
}
if(batch.form)batch.form.parentNode.removeChild(batch.form);
if(batch.req){
if(batch.req==dwr.engine._pollReq)dwr.engine._pollReq=null;
delete batch.req;
}
if(batch.map&&batch.map.batchId){
delete dwr.engine._batches[batch.map.batchId];
dwr.engine._batchesLength--;
}
batch.completed=true;
if(dwr.engine._batchQueue.length!=0){
var sendbatch=dwr.engine._batchQueue.shift();
dwr.engine._sendData(sendbatch);
}
};
dwr.engine._handleError=function(batch,ex){
if(typeof ex=="string")ex={name:"unknown",message:ex};
if(ex.message==null)ex.message="";
if(ex.name==null)ex.name="unknown";
if(batch&&typeof batch.errorHandler=="function")batch.errorHandler(ex.message,ex);
else if(dwr.engine._errorHandler)dwr.engine._errorHandler(ex.message,ex);
dwr.engine._clearUp(batch);
};
dwr.engine._handleWarning=function(batch,ex){
if(typeof ex=="string")ex={name:"unknown",message:ex};
if(ex.message==null)ex.message="";
if(ex.name==null)ex.name="unknown";
if(batch&&typeof batch.warningHandler=="function")batch.warningHandler(ex.message,ex);
else if(dwr.engine._warningHandler)dwr.engine._warningHandler(ex.message,ex);
dwr.engine._clearUp(batch);
};
dwr.engine._serializeAll=function(batch,referto,data,name){
if(data==null){
batch.map[name]="null:null";
return;
}
switch(typeof data){
case"boolean":
batch.map[name]="boolean:"+data;
break;
case"number":
batch.map[name]="number:"+data;
break;
case"string":
batch.map[name]="string:"+encodeURIComponent(data);
break;
case"object":
if(data instanceof String)batch.map[name]="String:"+encodeURIComponent(data);
else if(data instanceof Boolean)batch.map[name]="Boolean:"+data;
else if(data instanceof Number)batch.map[name]="Number:"+data;
else if(data instanceof Date)batch.map[name]="Date:"+data.getTime();
else if(data&&data.join)batch.map[name]=dwr.engine._serializeArray(batch,referto,data,name);
else batch.map[name]=dwr.engine._serializeObject(batch,referto,data,name);
break;
case"function":
break;
default:
dwr.engine._handleWarning(null,{name:"dwr.engine.unexpectedType",message:"Unexpected type: "+typeof data+", attempting default converter."});
batch.map[name]="default:"+data;
break;
}
};
dwr.engine._lookup=function(referto,data,name){
var lookup;
for(var i=0;i<referto.length;i++){
if(referto[i].data==data){
lookup=referto[i];
break;
}
}
if(lookup)return"reference:"+lookup.name;
referto.push({data:data,name:name});
return null;
};
dwr.engine._serializeObject=function(batch,referto,data,name){
var ref=dwr.engine._lookup(referto,data,name);
if(ref)return ref;
if(data.nodeName&&data.nodeType){
return dwr.engine._serializeXml(batch,referto,data,name);
}
var reply="Object_"+dwr.engine._getObjectClassName(data)+":{";
var element;
for(element in data){
if(typeof data[element]!="function"){
batch.paramCount++;
var childName="c"+dwr.engine._batch.map.callCount+"-e"+batch.paramCount;
dwr.engine._serializeAll(batch,referto,data[element],childName);
reply+=encodeURIComponent(element)+":reference:"+childName+", ";
}
}
if(reply.substring(reply.length-2)==", "){
reply=reply.substring(0,reply.length-2);
}
reply+="}";
return reply;
};
dwr.engine._errorClasses={"Error":Error,"EvalError":EvalError,"RangeError":RangeError,"ReferenceError":ReferenceError,"SyntaxError":SyntaxError,"TypeError":TypeError,"URIError":URIError};
dwr.engine._getObjectClassName=function(obj){
if(obj&&obj.constructor&&obj.constructor.toString)
{
var str=obj.constructor.toString();
var regexpmatch=str.match(/function\s+(\w+)/);
if(regexpmatch&&regexpmatch.length==2){
return regexpmatch[1];
}
}
if(obj&&obj.constructor){
for(var errorname in dwr.engine._errorClasses){
if(obj.constructor==dwr.engine._errorClasses[errorname])return errorname;
}
}
if(obj){
var str=Object.prototype.toString.call(obj);
var regexpmatch=str.match(/\[object\s+(\w+)/);
if(regexpmatch&&regexpmatch.length==2){
return regexpmatch[1];
}
}
return"Object";
};
dwr.engine._serializeXml=function(batch,referto,data,name){
var ref=dwr.engine._lookup(referto,data,name);
if(ref)return ref;
var output;
if(window.XMLSerializer)output=new XMLSerializer().serializeToString(data);
else if(data.toXml)output=data.toXml;
else output=data.innerHTML;
return"XML:"+encodeURIComponent(output);
};
dwr.engine._serializeArray=function(batch,referto,data,name){
var ref=dwr.engine._lookup(referto,data,name);
if(ref)return ref;
var reply="Array:[";
for(var i=0;i<data.length;i++){
if(i!=0)reply+=",";
batch.paramCount++;
var childName="c"+dwr.engine._batch.map.callCount+"-e"+batch.paramCount;
dwr.engine._serializeAll(batch,referto,data[i],childName);
reply+="reference:";
reply+=childName;
}
reply+="]";
return reply;
};
dwr.engine._unserializeDocument=function(xml){
var dom;
if(window.DOMParser){
var parser=new DOMParser();
dom=parser.parseFromString(xml,"text/xml");
if(!dom.documentElement||dom.documentElement.tagName=="parsererror"){
var message=dom.documentElement.firstChild.data;
message+="\n"+dom.documentElement.firstChild.nextSibling.firstChild.data;
throw message;
}
return dom;
}
else if(window.ActiveXObject){
dom=dwr.engine._newActiveXObject(dwr.engine._DOMDocument);
dom.loadXML(xml);
return dom;
}
else{
var div=document.createElement("div");
div.innerHTML=xml;
return div;
}
};
dwr.engine._newActiveXObject=function(axarray){
var returnValue;
for(var i=0;i<axarray.length;i++){
try{
returnValue=new ActiveXObject(axarray[i]);
break;
}
catch(ex){}
}
return returnValue;
};
dwr.engine._debug=function(message,stacktrace){
var written=false;
try{
if(window.console){
if(stacktrace&&window.console.trace)window.console.trace();
window.console.log(message);
written=true;
}
else if(window.opera&&window.opera.postError){
window.opera.postError(message);
written=true;
}
}
catch(ex){}
if(!written){
var debug=document.getElementById("dwr-debug");
if(debug){
var contents=message+"<br/>"+debug.innerHTML;
if(contents.length>2048)contents=contents.substring(0,2048);
debug.innerHTML=contents;
}
}
};
dwr.engine.unserializeDocument=function(xml){
return dwr.engine._unserializeDocument(xml);
};
if(dwr==null)var dwr={};
if(dwr.engine==null)dwr.engine={};
if(DWREngine==null)var DWREngine=dwr.engine;
if(MusicBean==null)var MusicBean={};
MusicBean._path='/dwr';
MusicBean.getWapCount=function(callback){
dwr.engine._execute(MusicBean._path,'MusicBean','getWapCount',callback);
}
MusicBean.setWapCount=function(p0,callback){
dwr.engine._execute(MusicBean._path,'MusicBean','setWapCount',p0,callback);
}
MusicBean.getMusicAlbumList=function(p0,callback){
dwr.engine._execute(MusicBean._path,'MusicBean','getMusicAlbumList',p0,callback);
}
MusicBean.getMusicAlbumListByVisit=function(p0,callback){
dwr.engine._execute(MusicBean._path,'MusicBean','getMusicAlbumListByVisit',p0,callback);
}
MusicBean.getMusicAlbumById=function(p0,callback){
dwr.engine._execute(MusicBean._path,'MusicBean','getMusicAlbumById',p0,callback);
}
MusicBean.addMusicAlbum=function(p0,callback){
dwr.engine._execute(MusicBean._path,'MusicBean','addMusicAlbum',p0,false,callback);
}
MusicBean.updateMusicAlbumName=function(p0,p1,callback){
dwr.engine._execute(MusicBean._path,'MusicBean','updateMusicAlbumName',p0,p1,callback);
}
MusicBean.updateMusicAlbumTag=function(p0,p1,callback){
dwr.engine._execute(MusicBean._path,'MusicBean','updateMusicAlbumTag',p0,p1,callback);
}
MusicBean.updateMusicAlbumDescription=function(p0,p1,callback){
dwr.engine._execute(MusicBean._path,'MusicBean','updateMusicAlbumDescription',p0,p1,callback);
}
MusicBean.updateMusicAlbumTop=function(p0,callback){
dwr.engine._execute(MusicBean._path,'MusicBean','updateMusicAlbumTop',p0,callback);
}
MusicBean.updateAlbumStyle=function(p0,callback){
dwr.engine._execute(MusicBean._path,'MusicBean','updateAlbumStyle',p0,callback);
}
MusicBean.updateMusicAlbumStyle=function(p0,p1,callback){
dwr.engine._execute(MusicBean._path,'MusicBean','updateMusicAlbumStyle',p0,p1,callback);
}
MusicBean.deleteMusics=function(p0,p1,callback){
dwr.engine._execute(MusicBean._path,'MusicBean','deleteMusics',p0,p1,callback);
}
MusicBean.deleteMusic=function(p0,p1,callback){
dwr.engine._execute(MusicBean._path,'MusicBean','deleteMusic',p0,p1,callback);
}
MusicBean.deleteMusicAlbum=function(p0,callback){
dwr.engine._execute(MusicBean._path,'MusicBean','deleteMusicAlbum',p0,callback);
}
MusicBean.deleteMusicAlbumPhoto=function(p0,callback){
dwr.engine._execute(MusicBean._path,'MusicBean','deleteMusicAlbumPhoto',p0,callback);
}
MusicBean.getMusicList=function(p0,p1,p2,p3,callback){
dwr.engine._execute(MusicBean._path,'MusicBean','getMusicList',p0,p1,p2,p3,false,callback);
}
MusicBean.getMusicListByAlbumId=function(p0,callback){
dwr.engine._execute(MusicBean._path,'MusicBean','getMusicListByAlbumId',p0,false,callback);
}
MusicBean.getPlayList=function(callback){
dwr.engine._execute(MusicBean._path,'MusicBean','getPlayList',false,callback);
}
MusicBean.addMusic=function(p0,callback){
dwr.engine._execute(MusicBean._path,'MusicBean','addMusic',p0,false,callback);
}
MusicBean.addMusicList=function(p0,p1,p2,p3,p4,p5,callback){
dwr.engine._execute(MusicBean._path,'MusicBean','addMusicList',p0,p1,p2,p3,p4,p5,false,callback);
}
MusicBean.collectMusicAlbum=function(p0,p1,p2,p3,p4,p5,callback){
dwr.engine._execute(MusicBean._path,'MusicBean','collectMusicAlbum',p0,p1,p2,p3,p4,p5,false,callback);
}
MusicBean.collectMusicAlbum=function(p0,p1,callback){
dwr.engine._execute(MusicBean._path,'MusicBean','collectMusicAlbum',p0,p1,callback);
}
MusicBean.getMusicAlbumCollectionCount=function(callback){
dwr.engine._execute(MusicBean._path,'MusicBean','getMusicAlbumCollectionCount',callback);
}
MusicBean.getMusicAlbumCollectionList=function(p0,p1,callback){
dwr.engine._execute(MusicBean._path,'MusicBean','getMusicAlbumCollectionList',p0,p1,callback);
}
MusicBean.deleteMusicAlbumCollection=function(p0,callback){
dwr.engine._execute(MusicBean._path,'MusicBean','deleteMusicAlbumCollection',p0,callback);
}
MusicBean.updateMusic=function(p0,callback){
dwr.engine._execute(MusicBean._path,'MusicBean','updateMusic',p0,false,callback);
}
MusicBean.getOnlineMusicList=function(p0,p1,p2,p3,p4,callback){
dwr.engine._execute(MusicBean._path,'MusicBean','getOnlineMusicList',p0,p1,p2,p3,p4,false,callback);
}
MusicBean.getMusicCommentList=function(p0,p1,p2,p3,callback){
dwr.engine._execute(MusicBean._path,'MusicBean','getMusicCommentList',p0,p1,p2,p3,callback);
}
MusicBean.addMusicComment=function(p0,p1,p2,p3,callback){
dwr.engine._execute(MusicBean._path,'MusicBean','addMusicComment',p0,p1,p2,p3,false,callback);
}
MusicBean.deleteComment=function(p0,p1,p2,p3,callback){
dwr.engine._execute(MusicBean._path,'MusicBean','deleteComment',p0,p1,p2,p3,callback);
}
MusicBean.getMusicAlbumVisitor=function(p0,callback){
dwr.engine._execute(MusicBean._path,'MusicBean','getMusicAlbumVisitor',p0,callback);
}
MusicBean.voteMusicAlbumRank=function(p0,p1,p2,callback){
dwr.engine._execute(MusicBean._path,'MusicBean','voteMusicAlbumRank',p0,p1,p2,callback);
}
MusicBean.updateMusicDuration=function(p0,p1,callback){
dwr.engine._execute(MusicBean._path,'MusicBean','updateMusicDuration',p0,p1,callback);
}
MusicBean.collectMusic=function(p0,p1,callback){
dwr.engine._execute(MusicBean._path,'MusicBean','collectMusic',p0,p1,callback);
}
MusicBean.collectMusicList=function(p0,p1,p2,p3,p4,p5,callback){
dwr.engine._execute(MusicBean._path,'MusicBean','collectMusicList',p0,p1,p2,p3,p4,p5,false,callback);
}
MusicBean.getRecentMusicCommentsByOffset=function(p0,p1,p2,callback){
dwr.engine._execute(MusicBean._path,'MusicBean','getRecentMusicCommentsByOffset',p0,p1,p2,callback);
}
MusicBean.removeBatchComment=function(p0,p1,callback){
dwr.engine._execute(MusicBean._path,'MusicBean','removeBatchComment',p0,p1,callback);
}
MusicBean.addAboutmeMusicComment=function(p0,p1,p2,p3,callback){
dwr.engine._execute(MusicBean._path,'MusicBean','addAboutmeMusicComment',p0,p1,p2,p3,false,callback);
}
MusicBean.getAuditionList=function(callback){
dwr.engine._execute(MusicBean._path,'MusicBean','getAuditionList',false,callback);
}
MusicBean.logAddAuditionList=function(p0,p1,p2,p3,p4,callback){
dwr.engine._execute(MusicBean._path,'MusicBean','logAddAuditionList',p0,p1,p2,p3,p4,false,callback);
}
MusicBean.logDeleteAudition=function(p0,callback){
dwr.engine._execute(MusicBean._path,'MusicBean','logDeleteAudition',p0,false,callback);
}
MusicBean.logClearAuditionList=function(callback){
dwr.engine._execute(MusicBean._path,'MusicBean','logClearAuditionList',false,callback);
}
MusicBean.getComment=function(p0,p1,p2,callback){
dwr.engine._execute(MusicBean._path,'MusicBean','getComment',p0,p1,p2,callback);
}
MusicBean.getMainCommentCount=function(p0,p1,callback){
dwr.engine._execute(MusicBean._path,'MusicBean','getMainCommentCount',p0,p1,callback);
}
MusicBean.deleteCommentForMsg=function(p0,p1,callback){
dwr.engine._execute(MusicBean._path,'MusicBean','deleteCommentForMsg',p0,p1,callback);
}
if(dwr==null)var dwr={};
if(dwr.engine==null)dwr.engine={};
if(DWREngine==null)var DWREngine=dwr.engine;
if(DiyMusicBean==null)var DiyMusicBean={};
DiyMusicBean._path='/dwr';
DiyMusicBean.deleteMusic=function(p0,p1,callback){
dwr.engine._execute(DiyMusicBean._path,'DiyMusicBean','deleteMusic',p0,p1,callback);
}
DiyMusicBean.deleteMusics=function(p0,p1,callback){
dwr.engine._execute(DiyMusicBean._path,'DiyMusicBean','deleteMusics',p0,p1,callback);
}
DiyMusicBean.getMusicListViewAll=function(p0,p1,callback){
dwr.engine._execute(DiyMusicBean._path,'DiyMusicBean','getMusicListViewAll',p0,p1,callback);
}
DiyMusicBean.getMD5MusicListViewAll=function(p0,p1,callback){
dwr.engine._execute(DiyMusicBean._path,'DiyMusicBean','getMD5MusicListViewAll',p0,p1,false,callback);
}
DiyMusicBean.getMusicList=function(p0,p1,callback){
dwr.engine._execute(DiyMusicBean._path,'DiyMusicBean','getMusicList',p0,p1,callback);
}
DiyMusicBean.getMD5MusicList=function(p0,p1,callback){
dwr.engine._execute(DiyMusicBean._path,'DiyMusicBean','getMD5MusicList',p0,p1,false,callback);
}
DiyMusicBean.updateMusicName=function(p0,p1,callback){
dwr.engine._execute(DiyMusicBean._path,'DiyMusicBean','updateMusicName',p0,p1,callback);
}
DiyMusicBean.updateMusicIntroduction=function(p0,p1,callback){
dwr.engine._execute(DiyMusicBean._path,'DiyMusicBean','updateMusicIntroduction',p0,p1,callback);
}
DiyMusicBean.updateMusicLyric=function(p0,p1,callback){
dwr.engine._execute(DiyMusicBean._path,'DiyMusicBean','updateMusicLyric',p0,p1,callback);
}
DiyMusicBean.updateMusicPrivacy=function(p0,p1,p2,p3,callback){
dwr.engine._execute(DiyMusicBean._path,'DiyMusicBean','updateMusicPrivacy',p0,p1,p2,p3,callback);
}
DiyMusicBean.voteMusicRank=function(p0,p1,callback){
dwr.engine._execute(DiyMusicBean._path,'DiyMusicBean','voteMusicRank',p0,p1,callback);
}
DiyMusicBean.voteMusicStyleRank=function(p0,callback){
dwr.engine._execute(DiyMusicBean._path,'DiyMusicBean','voteMusicStyleRank',p0,callback);
}
DiyMusicBean.getDownloadUrl=function(p0,callback){
dwr.engine._execute(DiyMusicBean._path,'DiyMusicBean','getDownloadUrl',p0,callback);
}
DiyMusicBean.getMD5DownloadUrl=function(p0,callback){
dwr.engine._execute(DiyMusicBean._path,'DiyMusicBean','getMD5DownloadUrl',p0,false,callback);
}
DiyMusicBean.collectDiyMusic=function(p0,p1,callback){
dwr.engine._execute(DiyMusicBean._path,'DiyMusicBean','collectDiyMusic',p0,p1,callback);
}
DiyMusicBean.checkAuthor=function(p0,callback){
dwr.engine._execute(DiyMusicBean._path,'DiyMusicBean','checkAuthor',p0,callback);
}
DiyMusicBean.getMusicCommentList=function(p0,p1,p2,p3,callback){
dwr.engine._execute(DiyMusicBean._path,'DiyMusicBean','getMusicCommentList',p0,p1,p2,p3,callback);
}
DiyMusicBean.getMainCommentCount=function(p0,p1,callback){
dwr.engine._execute(DiyMusicBean._path,'DiyMusicBean','getMainCommentCount',p0,p1,callback);
}
DiyMusicBean.addMusicComment=function(p0,p1,p2,p3,callback){
dwr.engine._execute(DiyMusicBean._path,'DiyMusicBean','addMusicComment',p0,p1,p2,p3,false,callback);
}
DiyMusicBean.deleteComment=function(p0,p1,p2,p3,callback){
dwr.engine._execute(DiyMusicBean._path,'DiyMusicBean','deleteComment',p0,p1,p2,p3,callback);
}
DiyMusicBean.getDiyMusicVisitor=function(callback){
dwr.engine._execute(DiyMusicBean._path,'DiyMusicBean','getDiyMusicVisitor',callback);
}
DiyMusicBean.addMusicCommentMsgCenter=function(p0,callback){
dwr.engine._execute(DiyMusicBean._path,'DiyMusicBean','addMusicCommentMsgCenter',p0,false,callback);
}
if(dwr==null)var dwr={};
if(dwr.engine==null)dwr.engine={};
if(DWREngine==null)var DWREngine=dwr.engine;
if(RemindBean==null)var RemindBean={};
RemindBean._path='/dwr';
RemindBean.sendUserMsg=function(p0,p1,p2,callback){
dwr.engine._execute(RemindBean._path,'RemindBean','sendUserMsg',p0,p1,p2,false,callback);
}
RemindBean.getMailCount=function(p0,callback){
NEPlatform.DwrHook.Switch.shutDownHook();
dwr.engine._execute(RemindBean._path,'RemindBean','getMailCount',p0,false,false,callback);
}
RemindBean.getChummies=function(callback){
dwr.engine._execute(RemindBean._path,'RemindBeanNew','getChummies',callback);
}
RemindBean.sendUserMsgWithValcode=function(p0,p1,p2,p3,callback){
dwr.engine._execute(RemindBean._path,'RemindBean','sendUserMsgWithValcode',p0,p1,p2,p3,false,callback);
}
RemindBean.sendShareResource=function(p0,p1,p2,p3,p4,p5,callback){
dwr.engine._execute(RemindBean._path,'RemindBean','sendShareResource',p0,p1,p2,p3,p4,p5,false,callback);
}
var Prototype={
Version:'1.4.0',
ScriptFragment:'(?:<script.*?>)((\n|\r|.)*?)(?:<\/script>)',
emptyFunction:function(){},
K:function(x){return x}
}
var Class={
create:function(){
return function(){
this.initialize.apply(this,arguments);
}
}
}
var Abstract=new Object();
Object.extend=function(destination,source){
for(property in source){
destination[property]=source[property];
}
return destination;
}
Object.inspect=function(object){
try{
if(object==undefined)return'undefined';
if(object==null)return'null';
return object.inspect?object.inspect():object.toString();
}catch(e){
if(e instanceof RangeError)return'...';
throw e;
}
}
Function.prototype.bind=function(){
var __method=this,args=$A(arguments),object=args.shift();
return function(){
return __method.apply(object,args.concat($A(arguments)));
}
}
Function.prototype.bindAsEventListener=function(object){
var __method=this;
return function(event){
return __method.call(object,event||window.event);
}
}
Function.prototype.bindEventWithArgs=function(){
var __method=this,args=$A(arguments),object=args.shift();
return function(event){
return __method.apply(object,args.concat($A(arguments)).concat(event||window.event));
}
}
Object.extend(Number.prototype,{
succ:function(){
return this+1;
},
times:function(iterator){
$R(0,this,true).each(iterator);
return this;
}
});
var Try={
these:function(){
var returnValue;
for(var i=0;i<arguments.length;i++){
var lambda=arguments[i];
try{
returnValue=lambda();
break;
}catch(e){}
}
return returnValue;
}
}
var PeriodicalExecuter=Class.create();
PeriodicalExecuter.prototype={
initialize:function(callback,frequency){
this.callback=callback;
this.frequency=frequency;
this.currentlyExecuting=false;
this.registerCallback();
},
registerCallback:function(){
setInterval(this.onTimerEvent.bind(this),this.frequency*1000);
},
onTimerEvent:function(){
if(!this.currentlyExecuting){
try{
this.currentlyExecuting=true;
this.callback();
}finally{
this.currentlyExecuting=false;
}
}
}
}
function $(){
var elements=new Array();
for(var i=0;i<arguments.length;i++){
var element=arguments[i];
if(typeof element=='string')
element=document.getElementById(element);
if(arguments.length==1)
return element;
elements.push(element);
}
return elements;
}
Object.extend(String.prototype,{
stripTags:function(){
return this.replace(/<\/?[^>]+>/gi,'');
},
stripScripts:function(){
return this.replace(new RegExp(Prototype.ScriptFragment,'img'),'');
},
extractScripts:function(){
var matchAll=new RegExp(Prototype.ScriptFragment,'img');
var matchOne=new RegExp(Prototype.ScriptFragment,'im');
return(this.match(matchAll)||[]).map(function(scriptTag){
return(scriptTag.match(matchOne)||['',''])[1];
});
},
evalScripts:function(){
return this.extractScripts().map(eval);
},
escapeHTML:function(){
var div=document.createElement('div');
var text=document.createTextNode(this);
div.appendChild(text);
return div.innerHTML;
},
toQueryParams:function(){
var pairs=this.match(/^\??(.*)$/)[1].split('&');
return pairs.inject({},function(params,pairString){
var pair=pairString.split('=');
params[pair[0]]=pair[1];
return params;
});
},
toArray:function(){
return this.split('');
},
camelize:function(){
var oStringList=this.split('-');
if(oStringList.length==1)return oStringList[0];
var camelizedString=this.indexOf('-')==0
?oStringList[0].charAt(0).toUpperCase()+oStringList[0].substring(1)
:oStringList[0];
for(var i=1,len=oStringList.length;i<len;i++){
var s=oStringList[i];
camelizedString+=s.charAt(0).toUpperCase()+s.substring(1);
}
return camelizedString;
},
inspect:function(){
return"'"+this.replace('\\','\\\\').replace("'",'\\\'')+"'";
}
});
String.prototype.parseQuery=String.prototype.toQueryParams;
var $break=new Object();
var $continue=new Object();
var Enumerable={
each:function(iterator){
var index=0;
try{
this._each(function(value){
try{
iterator(value,index++);
}catch(e){
if(e!=$continue)throw e;
}
});
}catch(e){
if(e!=$break)throw e;
}
},
all:function(iterator){
var result=true;
this.each(function(value,index){
result=result&&!!(iterator||Prototype.K)(value,index);
if(!result)throw $break;
});
return result;
},
any:function(iterator){
var result=false;
this.each(function(value,index){
if(result=!!(iterator||Prototype.K)(value,index))
throw $break;
});
return result;
},
collect:function(iterator){
var results=[];
this.each(function(value,index){
results.push(iterator(value,index));
});
return results;
},
detect:function(iterator){
var result;
this.each(function(value,index){
if(iterator(value,index)){
result=value;
throw $break;
}
});
return result;
},
findAll:function(iterator){
var results=[];
this.each(function(value,index){
if(iterator(value,index))
results.push(value);
});
return results;
},
include:function(object){
var found=false;
this.each(function(value){
if(value==object){
found=true;
throw $break;
}
});
return found;
},
inject:function(memo,iterator){
this.each(function(value,index){
memo=iterator(memo,value,index);
});
return memo;
},
invoke:function(method){
var args=$A(arguments).slice(1);
return this.collect(function(value){
return value[method].apply(value,args);
});
},
max:function(iterator){
var result;
this.each(function(value,index){
value=(iterator||Prototype.K)(value,index);
if(value>=(result||value))
result=value;
});
return result;
},
min:function(iterator){
var result;
this.each(function(value,index){
value=(iterator||Prototype.K)(value,index);
if(value<=(result||value))
result=value;
});
return result;
},
pluck:function(property){
var results=[];
this.each(function(value,index){
results.push(value[property]);
});
return results;
},
reject:function(iterator){
var results=[];
this.each(function(value,index){
if(!iterator(value,index))
results.push(value);
});
return results;
},
sortBy:function(iterator){
return this.collect(function(value,index){
return{value:value,criteria:iterator(value,index)};
}).sort(function(left,right){
var a=left.criteria,b=right.criteria;
return a<b?-1:a>b?1:0;
}).pluck('value');
},
toArray:function(){
return this.collect(Prototype.K);
},
inspect:function(){
return'#<Enumerable:'+this.toArray().inspect()+'>';
}
}
Object.extend(Enumerable,{
map:Enumerable.collect,
find:Enumerable.detect,
select:Enumerable.findAll,
member:Enumerable.include,
entries:Enumerable.toArray
});
var $A=Array.from=function(iterable){
if(!iterable)return[];
if(iterable.toArray){
return iterable.toArray();
}else{
var results=[];
for(var i=0;i<iterable.length;i++)
results.push(iterable[i]);
return results;
}
}
Object.extend(Array.prototype,Enumerable);
Array.prototype._reverse=Array.prototype.reverse;
Object.extend(Array.prototype,{
_each:function(iterator){
for(var i=0;i<this.length;i++)
iterator(this[i]);
},
clear:function(){
this.length=0;
return this;
},
first:function(){
return this[0];
},
last:function(){
return this[this.length-1];
},
compact:function(){
return this.select(function(value){
return value!=undefined||value!=null;
});
},
flatten:function(){
return this.inject([],function(array,value){
return array.concat(value.constructor==Array?
value.flatten():[value]);
});
},
without:function(){
var values=$A(arguments);
return this.select(function(value){
return!values.include(value);
});
},
indexOf:function(object){
for(var i=0;i<this.length;i++)
if(this[i]==object)return i;
return-1;
},
reverse:function(inline){
return(inline!==false?this:this.toArray())._reverse();
},
shift:function(){
var result=this[0];
for(var i=0;i<this.length-1;i++)
this[i]=this[i+1];
this.length--;
return result;
},
inspect:function(){
return'['+this.map(Object.inspect).join(', ')+']';
}
});
var Hash={
_each:function(iterator){
for(key in this){
var value=this[key];
if(typeof value=='function')continue;
var pair=[key,value];
pair.key=key;
pair.value=value;
iterator(pair);
}
},
keys:function(){
return this.pluck('key');
},
values:function(){
return this.pluck('value');
},
merge:function(hash){
return $H(hash).inject($H(this),function(mergedHash,pair){
mergedHash[pair.key]=pair.value;
return mergedHash;
});
},
toQueryString:function(){
return this.map(function(pair){
return pair.map(encodeURIComponent).join('=');
}).join('&');
},
inspect:function(){
return'#<Hash:{'+this.map(function(pair){
return pair.map(Object.inspect).join(': ');
}).join(', ')+'}>';
}
}
function $H(object){
var hash=Object.extend({},object||{});
Object.extend(hash,Enumerable);
Object.extend(hash,Hash);
return hash;
}
ObjectRange=Class.create();
Object.extend(ObjectRange.prototype,Enumerable);
Object.extend(ObjectRange.prototype,{
initialize:function(start,end,exclusive){
this.start=start;
this.end=end;
this.exclusive=exclusive;
},
_each:function(iterator){
var value=this.start;
do{
iterator(value);
value=value.succ();
}while(this.include(value));
},
include:function(value){
if(value<this.start)
return false;
if(this.exclusive)
return value<this.end;
return value<=this.end;
}
});
var $R=function(start,end,exclusive){
return new ObjectRange(start,end,exclusive);
}
var Ajax={
getTransport:function(){
return Try.these(
function(){return new ActiveXObject('Msxml2.XMLHTTP')},
function(){return new ActiveXObject('Microsoft.XMLHTTP')},
function(){return new XMLHttpRequest()}
)||false;
},
activeRequestCount:0
}
Ajax.Responders={
responders:[],
_each:function(iterator){
this.responders._each(iterator);
},
register:function(responderToAdd){
if(!this.include(responderToAdd))
this.responders.push(responderToAdd);
},
unregister:function(responderToRemove){
this.responders=this.responders.without(responderToRemove);
},
dispatch:function(callback,request,transport,json){
this.each(function(responder){
if(responder[callback]&&typeof responder[callback]=='function'){
try{
responder[callback].apply(responder,[request,transport,json]);
}catch(e){}
}
});
}
};
Object.extend(Ajax.Responders,Enumerable);
Ajax.Responders.register({
onCreate:function(){
Ajax.activeRequestCount++;
},
onComplete:function(){
Ajax.activeRequestCount--;
}
});
Ajax.Base=function(){};
Ajax.Base.prototype={
setOptions:function(options){
this.options={
method:'post',
asynchronous:true,
parameters:''
}
Object.extend(this.options,options||{});
},
responseIsSuccess:function(){
return this.transport.status==undefined
||this.transport.status==0
||(this.transport.status>=200&&this.transport.status<300);
}
}
Ajax.Request=Class.create();
Ajax.Request.Events=
['Uninitialized','Loading','Loaded','Interactive','Complete'];
Ajax.Request.prototype=Object.extend(new Ajax.Base(),{
initialize:function(url,options){
this.transport=Ajax.getTransport();
this.setOptions(options);
this.request(url);
},
request:function(url){
var parameters=this.options.parameters||'';
if(parameters.length>0)parameters+='&_=';
try{
this.url=url;
if(this.options.method=='get'&&parameters.length>0)
this.url+=(this.url.match(/\?/)?'&':'?')+parameters;
Ajax.Responders.dispatch('onCreate',this,this.transport);
this.transport.open(this.options.method,this.url,
this.options.asynchronous);
if(this.options.asynchronous){
this.transport.onreadystatechange=this.onStateChange.bind(this);
setTimeout((function(){this.respondToReadyState(1)}).bind(this),10);
}
this.setRequestHeaders();
var body=this.options.postBody?this.options.postBody:parameters;
this.transport.send(this.options.method=='post'?body:null);
}catch(e){
this.dispatchException(e);
}
},
setRequestHeaders:function(){
var requestHeaders=
['X-Requested-With','XMLHttpRequest',
'X-Prototype-Version',Prototype.Version];
if(this.options.method=='post'){
requestHeaders.push('Content-type',
'application/x-www-form-urlencoded');
if(this.transport.overrideMimeType)
requestHeaders.push('Connection','close');
}
if(this.options.requestHeaders)
requestHeaders.push.apply(requestHeaders,this.options.requestHeaders);
for(var i=0;i<requestHeaders.length;i+=2)
this.transport.setRequestHeader(requestHeaders[i],requestHeaders[i+1]);
},
onStateChange:function(){
var readyState=this.transport.readyState;
if(readyState!=1)
this.respondToReadyState(this.transport.readyState);
},
header:function(name){
try{
return this.transport.getResponseHeader(name);
}catch(e){}
},
evalJSON:function(){
try{
return eval(this.header('X-JSON'));
}catch(e){}
},
evalResponse:function(){
try{
return eval(this.transport.responseText);
}catch(e){
this.dispatchException(e);
}
},
respondToReadyState:function(readyState){
var event=Ajax.Request.Events[readyState];
var transport=this.transport,json=this.evalJSON();
if(event=='Complete'){
try{
(this.options['on'+this.transport.status]
||this.options['on'+(this.responseIsSuccess()?'Success':'Failure')]
||Prototype.emptyFunction)(transport,json);
}catch(e){
this.dispatchException(e);
}
if((this.header('Content-type')||'').match(/^text\/javascript/i))
this.evalResponse();
}
try{
(this.options['on'+event]||Prototype.emptyFunction)(transport,json);
Ajax.Responders.dispatch('on'+event,this,transport,json);
}catch(e){
this.dispatchException(e);
}
if(event=='Complete')
this.transport.onreadystatechange=Prototype.emptyFunction;
},
dispatchException:function(exception){
(this.options.onException||Prototype.emptyFunction)(this,exception);
Ajax.Responders.dispatch('onException',this,exception);
}
});
Ajax.Updater=Class.create();
Object.extend(Object.extend(Ajax.Updater.prototype,Ajax.Request.prototype),{
initialize:function(container,url,options){
this.containers={
success:container.success?$(container.success):$(container),
failure:container.failure?$(container.failure):
(container.success?null:$(container))
}
this.transport=Ajax.getTransport();
this.setOptions(options);
var onComplete=this.options.onComplete||Prototype.emptyFunction;
this.options.onComplete=(function(transport,object){
this.updateContent();
onComplete(transport,object);
}).bind(this);
this.request(url);
},
updateContent:function(){
var receiver=this.responseIsSuccess()?
this.containers.success:this.containers.failure;
var response=this.transport.responseText;
if(!this.options.evalScripts)
response=response.stripScripts();
if(receiver){
if(this.options.insertion){
new this.options.insertion(receiver,response);
}else{
Element.update(receiver,response);
}
}
if(this.responseIsSuccess()){
if(this.onComplete)
setTimeout(this.onComplete.bind(this),10);
}
}
});
Ajax.PeriodicalUpdater=Class.create();
Ajax.PeriodicalUpdater.prototype=Object.extend(new Ajax.Base(),{
initialize:function(container,url,options){
this.setOptions(options);
this.onComplete=this.options.onComplete;
this.frequency=(this.options.frequency||2);
this.decay=(this.options.decay||1);
this.updater={};
this.container=container;
this.url=url;
this.start();
},
start:function(){
this.options.onComplete=this.updateComplete.bind(this);
this.onTimerEvent();
},
stop:function(){
this.updater.onComplete=undefined;
clearTimeout(this.timer);
(this.onComplete||Prototype.emptyFunction).apply(this,arguments);
},
updateComplete:function(request){
if(this.options.decay){
this.decay=(request.responseText==this.lastText?
this.decay*this.options.decay:1);
this.lastText=request.responseText;
}
this.timer=setTimeout(this.onTimerEvent.bind(this),
this.decay*this.frequency*1000);
},
onTimerEvent:function(){
this.updater=new Ajax.Updater(this.container,this.url,this.options);
}
});
document.getElementsByClassName=function(className,parentElement){
var children=($(parentElement)||document.body).getElementsByTagName('*');
return $A(children).inject([],function(elements,child){
if(child.className.match(new RegExp("(^|\\s)"+className+"(\\s|$)")))
elements.push(child);
return elements;
});
}
if(!window.Element){
var Element=new Object();
}
Object.extend(Element,{
visible:function(element){
return $(element).style.display!='none';
},
toggle:function(){
for(var i=0;i<arguments.length;i++){
var element=$(arguments[i]);
Element[Element.visible(element)?'hide':'show'](element);
}
},
hide:function(){
for(var i=0;i<arguments.length;i++){
var element=$(arguments[i]);
element.style.display='none';
}
},
show:function(){
for(var i=0;i<arguments.length;i++){
var element=$(arguments[i]);
element.style.display='';
}
},
remove:function(element){
try{
element=$(element);
element.parentNode.removeChild(element);
if(element.outerHTML)element.outerHTML='';
}catch(e){}
},
update:function(element,html){
$(element).innerHTML=html.stripScripts();
setTimeout(function(){html.evalScripts()},10);
},
getHeight:function(element){
element=$(element);
return element.offsetHeight;
},
classNames:function(element){
return new Element.ClassNames(element);
},
hasClassName:function(element,className){
if(!(element=$(element)))return;
return Element.classNames(element).include(className);
},
addClassName:function(element,className){
if(!(element=$(element)))return;
return Element.classNames(element).add(className);
},
removeClassName:function(element,className){
if(!(element=$(element)))return;
return Element.classNames(element).remove(className);
},
replaceClassName:function(element,oldClass,newClass){
if(!(element=$(element)))return;
this.removeClassName(element,oldClass);
this.addClassName(element,newClass);
},
cleanWhitespace:function(element){
element=$(element);
for(var i=0;i<element.childNodes.length;i++){
var node=element.childNodes[i];
if(node.nodeType==3&&!/\S/.test(node.nodeValue))
Element.remove(node);
}
},
empty:function(element){
return $(element).innerHTML.match(/^\s*$/);
},
scrollTo:function(element){
element=$(element);
var x=element.x?element.x:element.offsetLeft,
y=element.y?element.y:element.offsetTop;
window.scrollTo(x,y);
},
getStyle:function(element,style){
element=$(element);
var value=element.style[style.camelize()];
if(!value){
if(document.defaultView&&document.defaultView.getComputedStyle){
var css=document.defaultView.getComputedStyle(element,null);
value=css?css.getPropertyValue(style):null;
}else if(element.currentStyle){
value=element.currentStyle[style.camelize()];
}
}
if(window.opera&&['left','top','right','bottom'].include(style))
if(Element.getStyle(element,'position')=='static')value='auto';
return value=='auto'?null:value;
},
setStyle:function(element,style){
element=$(element);
for(name in style)
element.style[name.camelize()]=style[name];
},
getDimensions:function(element){
element=$(element);
if(Element.getStyle(element,'display')!='none')
return{width:element.offsetWidth,height:element.offsetHeight};
var els=element.style;
var originalVisibility=els.visibility;
var originalPosition=els.position;
els.visibility='hidden';
els.position='absolute';
els.display='';
var originalWidth=element.clientWidth;
var originalHeight=element.clientHeight;
els.display='none';
els.position=originalPosition;
els.visibility=originalVisibility;
return{width:originalWidth,height:originalHeight};
},
makePositioned:function(element){
element=$(element);
var pos=Element.getStyle(element,'position');
if(pos=='static'||!pos){
element._madePositioned=true;
element.style.position='relative';
if(window.opera){
element.style.top=0;
element.style.left=0;
}
}
},
undoPositioned:function(element){
element=$(element);
if(element._madePositioned){
element._madePositioned=undefined;
element.style.position=
element.style.top=
element.style.left=
element.style.bottom=
element.style.right='';
}
},
makeClipping:function(element){
element=$(element);
if(element._overflow)return;
element._overflow=element.style.overflow;
if((Element.getStyle(element,'overflow')||'visible')!='hidden')
element.style.overflow='hidden';
},
undoClipping:function(element){
element=$(element);
if(element._overflow)return;
element.style.overflow=element._overflow;
element._overflow=undefined;
}
});
Element.removeChild=Element.remove;
var Toggle=new Object();
Toggle.display=Element.toggle;
Abstract.Insertion=function(adjacency){
this.adjacency=adjacency;
}
Abstract.Insertion.prototype={
initialize:function(element,content){
this.element=$(element);
this.content=content.stripScripts();
if(this.adjacency&&this.element.insertAdjacentHTML){
try{
this.element.insertAdjacentHTML(this.adjacency,this.content);
}catch(e){
if(this.element.tagName.toLowerCase()=='tbody'){
this.insertContent(this.contentFromAnonymousTable());
}else{
throw e;
}
}
}else{
this.range=this.element.ownerDocument.createRange();
if(this.initializeRange)this.initializeRange();
this.insertContent([this.range.createContextualFragment(this.content)]);
}
setTimeout(function(){content.evalScripts()},10);
},
contentFromAnonymousTable:function(){
var div=document.createElement('div');
div.innerHTML='<table><tbody>'+this.content+'</tbody></table>';
return $A(div.childNodes[0].childNodes[0].childNodes);
}
}
var Insertion=new Object();
Insertion.Before=Class.create();
Insertion.Before.prototype=Object.extend(new Abstract.Insertion('beforeBegin'),{
initializeRange:function(){
this.range.setStartBefore(this.element);
},
insertContent:function(fragments){
fragments.each((function(fragment){
this.element.parentNode.insertBefore(fragment,this.element);
}).bind(this));
}
});
Insertion.Top=Class.create();
Insertion.Top.prototype=Object.extend(new Abstract.Insertion('afterBegin'),{
initializeRange:function(){
this.range.selectNodeContents(this.element);
this.range.collapse(true);
},
insertContent:function(fragments){
fragments.reverse(false).each((function(fragment){
this.element.insertBefore(fragment,this.element.firstChild);
}).bind(this));
}
});
Insertion.Bottom=Class.create();
Insertion.Bottom.prototype=Object.extend(new Abstract.Insertion('beforeEnd'),{
initializeRange:function(){
this.range.selectNodeContents(this.element);
this.range.collapse(this.element);
},
insertContent:function(fragments){
fragments.each((function(fragment){
this.element.appendChild(fragment);
}).bind(this));
}
});
Insertion.After=Class.create();
Insertion.After.prototype=Object.extend(new Abstract.Insertion('afterEnd'),{
initializeRange:function(){
this.range.setStartAfter(this.element);
},
insertContent:function(fragments){
fragments.each((function(fragment){
this.element.parentNode.insertBefore(fragment,
this.element.nextSibling);
}).bind(this));
}
});
Element.ClassNames=Class.create();
Element.ClassNames.prototype={
initialize:function(element){
this.element=$(element);
},
_each:function(iterator){
this.element.className.split(/\s+/).select(function(name){
return name.length>0;
})._each(iterator);
},
set:function(className){
this.element.className=className;
},
add:function(classNameToAdd){
if(this.include(classNameToAdd))return;
this.set(this.toArray().concat(classNameToAdd).join(' '));
},
remove:function(classNameToRemove){
if(!this.include(classNameToRemove))return;
this.set(this.select(function(className){
return className!=classNameToRemove;
}).join(' '));
},
toString:function(){
return this.toArray().join(' ');
}
}
Object.extend(Element.ClassNames.prototype,Enumerable);
var Field={
clear:function(){
for(var i=0;i<arguments.length;i++)
$(arguments[i]).value='';
},
focus:function(element){
$(element).focus();
},
select:function(element){
$(element).select();
},
activate:function(element){
element=$(element);
element.focus();
if(element.select)
element.select();
}
}
var Form={
serialize:function(form){
var elements=Form.getElements($(form));
var queryComponents=new Array();
for(var i=0;i<elements.length;i++){
var queryComponent=Form.Element.serialize(elements[i]);
if(queryComponent)
queryComponents.push(queryComponent);
}
return queryComponents.join('&');
},
getElements:function(form){
form=$(form);
var elements=new Array();
for(tagName in Form.Element.Serializers){
var tagElements=form.getElementsByTagName(tagName);
for(var j=0;j<tagElements.length;j++)
elements.push(tagElements[j]);
}
return elements;
},
disable:function(form){
var elements=Form.getElements(form);
for(var i=0;i<elements.length;i++){
var element=elements[i];
element.blur();
element.disabled='true';
}
},
enable:function(form){
var elements=Form.getElements(form);
for(var i=0;i<elements.length;i++){
var element=elements[i];
element.disabled='';
}
},
reset:function(form){
$(form).reset();
}
}
Form.Element={
serialize:function(element){
element=$(element);
var method=element.tagName.toLowerCase();
var parameter=Form.Element.Serializers[method](element);
if(parameter){
var key=encodeURIComponent(parameter[0]);
if(key.length==0)return;
if(parameter[1].constructor!=Array)
parameter[1]=[parameter[1]];
return parameter[1].map(function(value){
return key+'='+encodeURIComponent(value);
}).join('&');
}
},
getValue:function(element){
element=$(element);
var method=element.tagName.toLowerCase();
var parameter=Form.Element.Serializers[method](element);
if(parameter)
return parameter[1];
}
}
Form.Element.Serializers={
input:function(element){
switch(element.type.toLowerCase()){
case'submit':
case'hidden':
case'password':
case'text':
return Form.Element.Serializers.textarea(element);
case'checkbox':
case'radio':
return Form.Element.Serializers.inputSelector(element);
}
return false;
},
inputSelector:function(element){
if(element.checked)
return[element.name,element.value];
},
textarea:function(element){
return[element.name,element.value];
},
select:function(element){
return Form.Element.Serializers[element.type=='select-one'?
'selectOne':'selectMany'](element);
},
selectOne:function(element){
var value='',opt,index=element.selectedIndex;
if(index>=0){
opt=element.options[index];
value=opt.value;
if(!value&&!('value'in opt))
value=opt.text;
}
return[element.name,value];
},
selectMany:function(element){
var value=new Array();
for(var i=0;i<element.length;i++){
var opt=element.options[i];
if(opt.selected){
var optValue=opt.value;
if(!optValue&&!('value'in opt))
optValue=opt.text;
value.push(optValue);
}
}
return[element.name,value];
}
}
var $F=Form.Element.getValue;
Abstract.TimedObserver=function(){}
Abstract.TimedObserver.prototype={
initialize:function(element,frequency,callback){
this.frequency=frequency;
this.element=$(element);
this.callback=callback;
this.lastValue=this.getValue();
this.registerCallback();
},
registerCallback:function(){
setInterval(this.onTimerEvent.bind(this),this.frequency*1000);
},
onTimerEvent:function(){
var value=this.getValue();
if(this.lastValue!=value){
this.callback(this.element,value);
this.lastValue=value;
}
}
}
Form.Element.Observer=Class.create();
Form.Element.Observer.prototype=Object.extend(new Abstract.TimedObserver(),{
getValue:function(){
return Form.Element.getValue(this.element);
}
});
Form.Observer=Class.create();
Form.Observer.prototype=Object.extend(new Abstract.TimedObserver(),{
getValue:function(){
return Form.serialize(this.element);
}
});
Abstract.EventObserver=function(){}
Abstract.EventObserver.prototype={
initialize:function(element,callback){
this.element=$(element);
this.callback=callback;
this.lastValue=this.getValue();
if(this.element.tagName.toLowerCase()=='form')
this.registerFormCallbacks();
else
this.registerCallback(this.element);
},
onElementEvent:function(){
var value=this.getValue();
if(this.lastValue!=value){
this.callback(this.element,value);
this.lastValue=value;
}
},
registerFormCallbacks:function(){
var elements=Form.getElements(this.element);
for(var i=0;i<elements.length;i++)
this.registerCallback(elements[i]);
},
registerCallback:function(element){
if(element.type){
switch(element.type.toLowerCase()){
case'checkbox':
case'radio':
Event.observe(element,'click',this.onElementEvent.bind(this));
break;
case'password':
case'text':
case'textarea':
case'select-one':
case'select-multiple':
Event.observe(element,'change',this.onElementEvent.bind(this));
break;
}
}
}
}
Form.Element.EventObserver=Class.create();
Form.Element.EventObserver.prototype=Object.extend(new Abstract.EventObserver(),{
getValue:function(){
return Form.Element.getValue(this.element);
}
});
Form.EventObserver=Class.create();
Form.EventObserver.prototype=Object.extend(new Abstract.EventObserver(),{
getValue:function(){
return Form.serialize(this.element);
}
});
if(!window.Event){
var Event=new Object();
}
Object.extend(Event,{
KEY_BACKSPACE:8,
KEY_TAB:9,
KEY_RETURN:13,
KEY_ESC:27,
KEY_LEFT:37,
KEY_UP:38,
KEY_RIGHT:39,
KEY_DOWN:40,
KEY_DELETE:46,
element:function(event){
return event.target||event.srcElement;
},
isLeftClick:function(event){
return(((event.which)&&(event.which==1))||
((event.button)&&(event.button==1)));
},
pointerX:function(event){
return event.pageX||(event.clientX+
(document.documentElement.scrollLeft||document.body.scrollLeft));
},
pointerY:function(event){
return event.pageY||(event.clientY+
(document.documentElement.scrollTop||document.body.scrollTop));
},
cursorX:function(event){
return(event.pageX||event.clientX)+UD.window.scrollLeft;
},
cursorY:function(event){
return(event.pageY||event.clientY)+UD.window.scrollTop;
},
stop:function(event){
if(event.preventDefault){
event.preventDefault();
event.stopPropagation();
}else{
event.returnValue=false;
event.cancelBubble=true;
}
},
stopBubble:function(event){
if(event.stopPropagation){
event.stopPropagation();
}else{
event.cancelBubble=true;
}
},
findElement:function(event,tagName){
var element=Event.element(event);
while(element.parentNode&&(!element.tagName||
(element.tagName.toUpperCase()!=tagName.toUpperCase())))
element=element.parentNode;
return element;
},
observers:false,
_observeAndCache:function(element,name,observer,useCapture){
if(!this.observers)this.observers=[];
if(element.addEventListener){
this.observers.push([element,name,observer,useCapture]);
element.addEventListener(name,observer,useCapture);
}else if(element.attachEvent){
this.observers.push([element,name,observer,useCapture]);
element.attachEvent('on'+name,observer);
}
},
unloadCache:function(){
if(!Event.observers)return;
for(var i=0;i<Event.observers.length;i++){
Event.stopObserving.apply(this,Event.observers[i]);
Event.observers[i][0]=null;
}
Event.observers=false;
},
observe:function(element,name,observer,useCapture){
var element=$(element);
useCapture=useCapture||false;
if(name=='keypress'&&
(navigator.appVersion.match(/Konqueror|Safari|KHTML/)
||element.attachEvent))
name='keydown';
this._observeAndCache(element,name,observer,useCapture);
},
stopObserving:function(element,name,observer,useCapture){
var element=$(element);
useCapture=useCapture||false;
if(name=='keypress'&&
(navigator.appVersion.match(/Konqueror|Safari|KHTML/)
||element.detachEvent))
name='keydown';
if(element.removeEventListener){
element.removeEventListener(name,observer,useCapture);
}else if(element.detachEvent){
element.detachEvent('on'+name,observer);
}
}
});
Event.observe(window,'unload',Event.unloadCache,false);
var Position={
includeScrollOffsets:false,
prepare:function(){
this.deltaX=window.pageXOffset
||document.documentElement.scrollLeft
||document.body.scrollLeft
||0;
this.deltaY=window.pageYOffset
||document.documentElement.scrollTop
||document.body.scrollTop
||0;
},
realOffset:function(element){
var valueT=0,valueL=0;
do{
valueT+=element.scrollTop||0;
valueL+=element.scrollLeft||0;
element=element.parentNode;
}while(element);
return[valueL,valueT];
},
cumulativeOffset:function(element){
var valueT=0,valueL=0;
do{
valueT+=element.offsetTop||0;
valueL+=element.offsetLeft||0;
element=element.offsetParent;
}while(element);
return[valueL,valueT];
},
positionedOffset:function(element){
var valueT=0,valueL=0;
do{
valueT+=element.offsetTop||0;
valueL+=element.offsetLeft||0;
element=element.offsetParent;
if(element){
p=Element.getStyle(element,'position');
if(p=='relative'||p=='absolute')break;
}
}while(element);
return[valueL,valueT];
},
offsetParent:function(element){
if(element.offsetParent)return element.offsetParent;
if(element==document.body)return element;
while((element=element.parentNode)&&element!=document.body)
if(Element.getStyle(element,'position')!='static')
return element;
return document.body;
},
within:function(element,x,y){
if(this.includeScrollOffsets)
return this.withinIncludingScrolloffsets(element,x,y);
this.xcomp=x;
this.ycomp=y;
this.offset=this.cumulativeOffset(element);
return(y>=this.offset[1]&&
y<this.offset[1]+element.offsetHeight&&
x>=this.offset[0]&&
x<this.offset[0]+element.offsetWidth);
},
withinIncludingScrolloffsets:function(element,x,y){
var offsetcache=this.realOffset(element);
this.xcomp=x+offsetcache[0]-this.deltaX;
this.ycomp=y+offsetcache[1]-this.deltaY;
this.offset=this.cumulativeOffset(element);
return(this.ycomp>=this.offset[1]&&
this.ycomp<this.offset[1]+element.offsetHeight&&
this.xcomp>=this.offset[0]&&
this.xcomp<this.offset[0]+element.offsetWidth);
},
overlap:function(mode,element){
if(!mode)return 0;
if(mode=='vertical')
return((this.offset[1]+element.offsetHeight)-this.ycomp)/
element.offsetHeight;
if(mode=='horizontal')
return((this.offset[0]+element.offsetWidth)-this.xcomp)/
element.offsetWidth;
},
clone:function(source,target){
source=$(source);
target=$(target);
target.style.position='absolute';
var offsets=this.cumulativeOffset(source);
target.style.top=offsets[1]+'px';
target.style.left=offsets[0]+'px';
target.style.width=source.offsetWidth+'px';
target.style.height=source.offsetHeight+'px';
},
page:function(forElement){
var valueT=0,valueL=0;
var element=forElement;
do{
valueT+=element.offsetTop||0;
valueL+=element.offsetLeft||0;
if(element.offsetParent==document.body)
if(Element.getStyle(element,'position')=='absolute')break;
}while(element=element.offsetParent);
element=forElement;
do{
valueT-=element.scrollTop||0;
valueL-=element.scrollLeft||0;
}while(element=element.parentNode);
return[valueL,valueT];
},
clone:function(source,target){
var options=Object.extend({
setLeft:true,
setTop:true,
setWidth:true,
setHeight:true,
offsetTop:0,
offsetLeft:0
},arguments[2]||{})
source=$(source);
var p=Position.page(source);
target=$(target);
var delta=[0,0];
var parent=null;
if(Element.getStyle(target,'position')=='absolute'){
parent=Position.offsetParent(target);
delta=Position.page(parent);
}
if(parent==document.body){
delta[0]-=document.body.offsetLeft;
delta[1]-=document.body.offsetTop;
}
if(options.setLeft)target.style.left=(p[0]-delta[0]+options.offsetLeft)+'px';
if(options.setTop)target.style.top=(p[1]-delta[1]+options.offsetTop)+'px';
if(options.setWidth)target.style.width=source.offsetWidth+'px';
if(options.setHeight)target.style.height=source.offsetHeight+'px';
},
absolutize:function(element){
element=$(element);
if(element.style.position=='absolute')return;
Position.prepare();
var offsets=Position.positionedOffset(element);
var top=offsets[1];
var left=offsets[0];
var width=element.clientWidth;
var height=element.clientHeight;
element._originalLeft=left-parseFloat(element.style.left||0);
element._originalTop=top-parseFloat(element.style.top||0);
element._originalWidth=element.style.width;
element._originalHeight=element.style.height;
element.style.position='absolute';
element.style.top=top+'px';;
element.style.left=left+'px';;
element.style.width=width+'px';;
element.style.height=height+'px';;
},
relativize:function(element){
element=$(element);
if(element.style.position=='relative')return;
Position.prepare();
element.style.position='relative';
var top=parseFloat(element.style.top||0)-(element._originalTop||0);
var left=parseFloat(element.style.left||0)-(element._originalLeft||0);
element.style.top=top+'px';
element.style.left=left+'px';
element.style.height=element._originalHeight;
element.style.width=element._originalWidth;
}
}
if(/Konqueror|Safari|KHTML/.test(navigator.userAgent)){
Position.cumulativeOffset=function(element){
var valueT=0,valueL=0;
do{
valueT+=element.offsetTop||0;
valueL+=element.offsetLeft||0;
if(element.offsetParent==document.body)
if(Element.getStyle(element,'position')=='absolute')break;
element=element.offsetParent;
}while(element);
return[valueL,valueT];
}
}
document.ready=function(callBack){
var _isRead=false;
var _c=function(){
if(!_isRead){
callBack();
_isRead=true;
}
};
var _b=navigator.userAgent.toLowerCase();
if((/mozilla/.test(_b)&&!/(compatible|webkit)/.test(_b))||(/opera/.test(_b))){
Event.observe(document,"DOMContentLoaded",_c);
}else if(/msie/.test(_b)&&!/opera/.test(_b)){
if(!this.__count)this.__count=0;
var _id='__ie_init'+this.__count++;
document.write("<scr"+"ipt id="+_id+" defer=true "+
"src=//:><\/script>");
var _s=$(_id);
if(_s){
_s.onreadystatechange=function(){
if(this.readyState!="complete")return;
this.parentNode.removeChild(this);
_c();
};
}
_s=null;
}else if(/webkit/.test(_b)){
var _i=setInterval(function(){
if(document.readyState=="loaded"||
document.readyState=="complete"){
clearInterval(_i);
_i=null;
_c();
}
},10);
}else{
Event.observe(window,"load",_c);
}
}
if(NetEase==undefined){
var NetEase={};
}
NetEase.CrossDomainRequest=Class.create();
NetEase.CrossDomainRequest.prototype={
initialize:function(url,options){
this.url=url;
this.options=Object.extend({charset:'utf-8'},options||{});
if(/^https?:\/\/.+$/i.test(url)){
NetEase.LoadStaticJS.request(null,this.url,this.options.parameters,function(reply){
this.options.onComplete((typeof reply=="object")?reply:{responseText:reply});
}.bind(this),this.options.charset);
}else{
new Ajax.Request(this.url,this.options);
}
}
}
NetEase.LoadStaticJS={_index:0,_handles:{}};
NetEase.LoadStaticJS.request=function(key,url,params,callback,charset){
key=key||'LoadStaticJS-'+this._index++;
this._handles[key]=callback;
var script=document.createElement("script");
if(charset){
script.setAttribute("charset",charset);
}
script.src=this._genUrl(key,url,params);
document.body.appendChild(script);
}
NetEase.LoadStaticJS.genVersionByDay=function(day){
if(!day)day=1;
var _baseTime=new Date(2007,9,1).getTime();
var _nowTime=new Date();
_nowTime=new Date(_nowTime.getFullYear(),_nowTime.getMonth(),_nowTime.getDate()).getTime();
var _diffTime=(_nowTime-_baseTime)<0?0:(_nowTime-_baseTime);
var _version=Math.floor(_diffTime/(day*24*3600000))+1000+'';
if(_version.length!=4)_version='';
return _version;
}
NetEase.LoadStaticJS._remoteCallBack=function(key,reply){
if(this._handles[key]){
this._handles[key](reply);
this._handles[key]=null;
}
}
NetEase.LoadStaticJS._genUrl=function(key,url,params){
if(url==null){alert("url can't be null!");return;}
url+=(url.indexOf('?')>0)?'&':'?';
if(params){
if(typeof params=="object"){
for(var p in params){
if(typeof params[p]!="function"){
url+=encodeURIComponent(p)+"="+encodeURIComponent(params[p])+"&";
}
}
}else if(typeof params!="function"){
url+=params+"&";
}
}
url+='&_request=x&_jsonType=2&_key='+encodeURIComponent(key);
return url;
}
Element.getOpacity=function(element){
var opacity;
if(opacity=Element.getStyle(element,"opacity"))
return parseFloat(opacity);
if(opacity=(Element.getStyle(element,"filter")||'').match(/alpha\(opacity=(.*)\)/))
if(opacity[1])return parseFloat(opacity[1])/100;
return 1.0;
}
Element.setOpacity=function(element,value){
element=$(element);
var els=element.style;
if(value==1){
els.opacity='0.999999';
if(/MSIE/.test(navigator.userAgent))
els.filter=Element.getStyle(element,'filter').replace(/alpha\([^\)]*\)/gi,'');
}else{
if(value<0.00001)value=0;
els.opacity=value;
if(/MSIE/.test(navigator.userAgent))
els.filter=Element.getStyle(element,'filter').replace(/alpha\([^\)]*\)/gi,'')+
"alpha(opacity="+value*100+")";
}
}
Element.getInlineOpacity=function(element){
element=$(element);
var op;
op=element.style.opacity;
if(typeof op!="undefined"&&op!="")return op;
return"";
}
Element.setInlineOpacity=function(element,value){
element=$(element);
var els=element.style;
els.opacity=value;
}
Element.setStyle=function(element,style){
element=$(element);
for(k in style)element.style[k.camelize()]=style[k];
}
Element.childrenWithClassName=function(element,className){
return $A($(element).getElementsByTagName('*')).select(
function(c){return Element.hasClassName(c,className)});
}
Element.collectTextNodesIgnoreClass=function(element,className){
return $A($(element).childNodes).collect(function(node){
return(node.nodeType==3?node.nodeValue:
((node.hasChildNodes()&&!Element.hasClassName(node,className))?
Element.collectTextNodesIgnoreClass(node,className):''));
}).flatten().join('');
}
var Effect={
multiple:function(element,effect){
var elements;
if(((typeof element=='object')||
(typeof element=='function'))&&
(element.length))
elements=element;
else
elements=$(element).childNodes;
var options=Object.extend({
speed:0.1,
delay:0.0
},arguments[2]||{});
var speed=options.speed;
var delay=options.delay;
$A(elements).each(function(element,index){
new effect(element,Object.extend(options,{delay:delay+index*speed}));
});
}
};
var Effect2=Effect;
Effect.Transitions={}
Effect.Transitions.linear=function(pos){
return pos;
}
Effect.Transitions.sinoidal=function(pos){
return(-Math.cos(pos*Math.PI)/2)+0.5;
}
Effect.Transitions.reverse=function(pos){
return 1-pos;
}
Effect.Transitions.flicker=function(pos){
return((-Math.cos(pos*Math.PI)/4)+0.75)+Math.random()/4;
}
Effect.Transitions.wobble=function(pos){
return(-Math.cos(pos*Math.PI*(9*pos))/2)+0.5;
}
Effect.Transitions.pulse=function(pos){
return(Math.floor(pos*10)%2==0?
(pos*10-Math.floor(pos*10)):1-(pos*10-Math.floor(pos*10)));
}
Effect.Transitions.none=function(pos){
return 0;
}
Effect.Transitions.full=function(pos){
return 1;
}
Effect.Queue={
effects:[],
_each:function(iterator){
this.effects._each(iterator);
},
interval:null,
add:function(effect){
var timestamp=new Date().getTime();
switch(effect.options.queue){
case'front':
this.effects.findAll(function(e){return e.state=='idle'}).each(function(e){
e.startOn+=effect.finishOn;
e.finishOn+=effect.finishOn;
});
break;
case'end':
timestamp=this.effects.pluck('finishOn').max()||timestamp;
break;
}
effect.startOn+=timestamp;
effect.finishOn+=timestamp;
this.effects.push(effect);
if(!this.interval)
this.interval=setInterval(this.loop.bind(this),40);
},
remove:function(effect){
this.effects=this.effects.reject(function(e){return e==effect});
if(this.effects.length==0){
clearInterval(this.interval);
this.interval=null;
}
},
loop:function(){
var timePos=new Date().getTime();
this.effects.invoke('loop',timePos);
}
}
Object.extend(Effect.Queue,Enumerable);
Effect.Base=function(){};
Effect.Base.prototype={
position:null,
setOptions:function(options){
this.options=Object.extend({
transition:Effect.Transitions.sinoidal,
duration:1.0,
fps:25.0,
sync:false,
from:0.0,
to:1.0,
delay:0.0,
queue:'parallel'
},options||{});
},
start:function(options){
this.setOptions(options||{});
this.currentFrame=0;
this.state='idle';
this.startOn=this.options.delay*1000;
this.finishOn=this.startOn+(this.options.duration*1000);
this.event('beforeStart');
var bAdd=true;
if(this.options.stateId){
bAdd=this.startState();
if(typeof this.options.succObj=="object")
this.options.succObj.success=bAdd;
}
if(!this.options.sync&&bAdd)Effect.Queue.add(this);
},
loop:function(timePos){
if(timePos>=this.startOn){
if(timePos>=this.finishOn){
this.render(1.0);
this.cancel();
this.event('beforeFinish');
if(this.finish)this.finish();
this.event('afterFinish');
if(this.options.stateId)this.finishState();
if(this.options.userCallBack)this.options.userCallBack();
return;
}
var pos=(timePos-this.startOn)/(this.finishOn-this.startOn);
var frame=Math.round(pos*this.options.fps*this.options.duration);
if(frame>this.currentFrame){
this.render(pos);
this.currentFrame=frame;
}
}
},
render:function(pos){
if(this.state=='idle'){
this.state='running';
this.event('beforeSetup');
if(this.setup)this.setup();
this.event('afterSetup');
}
if(this.options.transition)pos=this.options.transition(pos);
pos*=(this.options.to-this.options.from);
pos+=this.options.from;
this.position=pos;
this.event('beforeUpdate');
if(this.update)this.update(pos);
this.event('afterUpdate');
},
cancel:function(){
if(!this.options.sync)Effect.Queue.remove(this);
this.state='finished';
},
event:function(eventName){
if(this.options[eventName+'Internal'])this.options[eventName+'Internal'](this);
if(this.options[eventName])this.options[eventName](this);
},
startState:function(){
if(!this.element._state){
this.element._state="running";
return true;
}
else if(this.element._state=="running")
return false;
else{
this.element._state="running";
return true;
}
},
finishState:function(){
this.element._state="finished";
}
}
Effect.Opacity=Class.create();
Object.extend(Object.extend(Effect.Opacity.prototype,Effect.Base.prototype),{
initialize:function(element){
this.element=$(element);
if(/MSIE/.test(navigator.userAgent)&&(!this.element.hasLayout))
this.element.style.zoom=1;
var options=Object.extend({
from:Element.getOpacity(this.element)||0.0,
to:1.0
},arguments[1]||{});
this.start(options);
},
update:function(position){
Element.setOpacity(this.element,position);
}
});
Effect.Move=Class.create();
Object.extend(Object.extend(Effect.Move.prototype,Effect.Base.prototype),{
initialize:function(element){
this.element=$(element);
var options=Object.extend({
x:0,
y:0,
mode:'relative'
},arguments[1]||{});
this.start(options);
},
setup:function(){
Element.makePositioned(this.element);
this.originalLeft=parseFloat(Element.getStyle(this.element,'left')||'0');
this.originalTop=parseFloat(Element.getStyle(this.element,'top')||'0');
if(this.options.mode=='absolute'){
this.options.x=this.options.x-this.originalLeft;
this.options.y=this.options.y-this.originalTop;
}
},
update:function(position){
Element.setStyle(this.element,{
left:this.options.x*position+this.originalLeft+'px',
top:this.options.y*position+this.originalTop+'px'
});
}
});
Effect.MoveBy=Class.create();
Object.extend(Object.extend(Effect.MoveBy.prototype,Effect.Base.prototype),{
initialize:function(element,toTop,toLeft){
this.element=$(element);
this.toTop=toTop;
this.toLeft=toLeft;
this.start(arguments[3]);
},
setup:function(){
Element.makePositioned(this.element);
this.originalTop=parseFloat(Element.getStyle(this.element,'top')||'0');
this.originalLeft=parseFloat(Element.getStyle(this.element,'left')||'0');
},
update:function(position){
var topd=this.toTop*position+this.originalTop;
var leftd=this.toLeft*position+this.originalLeft;
this.setPosition(topd,leftd);
},
setPosition:function(topd,leftd){
this.element.style.top=topd+"px";
this.element.style.left=leftd+"px";
}
});
Effect.Scale=Class.create();
Object.extend(Object.extend(Effect.Scale.prototype,Effect.Base.prototype),{
initialize:function(element,percent){
this.element=$(element)
var options=Object.extend({
scaleX:true,
scaleY:true,
scaleContent:true,
scaleFromCenter:false,
scaleMode:'box',
scaleFrom:100.0,
scaleTo:percent
},arguments[2]||{});
this.start(options);
},
setup:function(){
var effect=this;
this.restoreAfterFinish=this.options.restoreAfterFinish||false;
this.elementPositioning=Element.getStyle(this.element,'position');
effect.originalStyle={};
['top','left','width','height','fontSize'].each(function(k){
effect.originalStyle[k]=effect.element.style[k];
});
this.originalTop=this.element.offsetTop;
this.originalLeft=this.element.offsetLeft;
var fontSize=Element.getStyle(this.element,'font-size')||"100%";
['em','px','%'].each(function(fontSizeType){
if(fontSize.indexOf(fontSizeType)>0){
effect.fontSize=parseFloat(fontSize);
effect.fontSizeType=fontSizeType;
}
});
this.factor=(this.options.scaleTo-this.options.scaleFrom)/100;
this.dims=null;
if(this.options.scaleMode=='box'){
if(/MSIE/.test(navigator.userAgent)){
var width=(this.element.clientWidth=="")?this.element.scrollHeight:this.element.clientWidth;
var height=(this.element.clientHeight=="")?this.element.scrollHeight:this.element.clientHeight;
this.dims=[height,width];
}
else
this.dims=[this.element.clientHeight,this.element.clientWidth];
}
if(/^content/.test(this.options.scaleMode))
this.dims=[this.element.scrollHeight,this.element.scrollWidth];
if(!this.dims)
this.dims=[this.options.scaleMode.originalHeight,
this.options.scaleMode.originalWidth];
},
update:function(position){
var currentScale=(this.options.scaleFrom/100.0)+(this.factor*position);
if(this.options.scaleContent&&this.fontSize)
this.element.style.fontSize=this.fontSize*currentScale+this.fontSizeType;
this.setDimensions(this.dims[0]*currentScale,this.dims[1]*currentScale);
},
finish:function(position){
if(this.restoreAfterFinish){
var effect=this;
['top','left','width','height','fontSize'].each(function(k){
effect.element.style[k]=effect.originalStyle[k];
});
}
},
setDimensions:function(height,width){
var els=this.element.style;
if(this.options.scaleX)els.width=width+'px';
if(this.options.scaleY){
if(/MSIE/.test(navigator.userAgent))
if(height<1)height=1;
els.height=height+'px';
}
if(this.options.scaleFromCenter){
var topd=(height-this.dims[0])/2;
var leftd=(width-this.dims[1])/2;
if(this.elementPositioning=='absolute'){
if(this.options.scaleY)els.top=this.originalTop-topd+"px";
if(this.options.scaleX)els.left=this.originalLeft-leftd+"px";
}else{
if(this.options.scaleY)els.top=-topd+"px";
if(this.options.scaleX)els.left=-leftd+"px";
}
}
}
});
Effect.ScrollTo=Class.create();
Object.extend(Object.extend(Effect.ScrollTo.prototype,Effect.Base.prototype),{
initialize:function(element){
this.element=$(element);
this.start(arguments[1]||{});
},
setup:function(){
Position.prepare();
var offsets=Position.cumulativeOffset(this.element);
var max=window.innerHeight?
window.height-window.innerHeight:
document.body.scrollHeight-
(document.documentElement.clientHeight?
document.documentElement.clientHeight:document.body.clientHeight);
this.scrollStart=Position.deltaY;
this.delta=(offsets[1]>max?max:offsets[1])-this.scrollStart;
},
update:function(position){
UD.window.scrollTop=Position.cumulativeOffset(this.element)[1];
}
});
Effect.Fade=function(element){
var oldOpacity=Element.getInlineOpacity(element);
var options=Object.extend({
from:Element.getOpacity(element)||1.0,
to:0.0,
afterFinishInternal:function(effect)
{if(effect.options.to==0){
Element.hide(effect.element);
Element.setInlineOpacity(effect.element,oldOpacity);
}
}
},arguments[1]||{});
return new Effect.Opacity(element,options);
}
Effect.Appear=function(element){
var options=Object.extend({
from:(Element.getStyle(element,"display")=="none"?0.0:Element.getOpacity(element)||0.0),
to:1.0,
beforeSetup:function(effect){
Element.setOpacity(effect.element,effect.options.from);
Element.show(effect.element);}
},arguments[1]||{});
return new Effect.Opacity(element,options);
}
Effect.BlindUp=function(element){
element=$(element);
Element.makeClipping(element);
return new Effect.Scale(element,0,
Object.extend({scaleContent:false,
scaleX:false,
restoreAfterFinish:true,
afterFinishInternal:function(effect)
{
Element.hide(effect.element);
Element.undoClipping(effect.element);
}
},arguments[1]||{})
);
}
Effect.BlindDown=function(element){
element=$(element);
var oldHeight=element.style.height;
var elementDimensions=Element.getDimensions(element);
return new Effect.Scale(element,100,
Object.extend({scaleContent:false,
scaleX:false,
scaleFrom:0,
scaleMode:{originalHeight:elementDimensions.height,originalWidth:elementDimensions.width},
restoreAfterFinish:true,
afterSetup:function(effect){
Element.makeClipping(effect.element);
if(/MSIE/.test(navigator.userAgent))
effect.element.style.height="1px";
else
effect.element.style.height="0px";
Element.show(effect.element);
},
afterFinishInternal:function(effect){
Element.undoClipping(effect.element);
effect.element.style.height=oldHeight;
}
},arguments[1]||{})
);
}
Effect.SlideDown=function(element){
element=$(element);
Element.cleanWhitespace(element);
var oldInnerBottom=element.firstChild.style.bottom;
var elementDimensions=Element.getDimensions(element);
return new Effect.Scale(element,100,
Object.extend({scaleContent:false,
scaleX:false,
scaleFrom:0,
scaleMode:{originalHeight:elementDimensions.height,originalWidth:elementDimensions.width},
restoreAfterFinish:true,
afterSetup:function(effect){
Element.makePositioned(effect.element.firstChild);
if(window.opera)effect.element.firstChild.style.top="";
Element.makeClipping(effect.element);
element.style.height='0';
Element.show(element);
},
afterFinishInternal:function(effect){
Element.undoClipping(effect.element);
Element.undoPositioned(effect.element.firstChild);
effect.element.firstChild.style.bottom=oldInnerBottom;
}
},arguments[1]||{})
);
}
Effect.SlideUp=function(element){
element=$(element);
Element.cleanWhitespace(element);
var oldInnerBottom=element.firstChild.style.bottom;
return new Effect.Scale(element,0,
Object.extend({scaleContent:false,
scaleX:false,
scaleMode:'box',
scaleFrom:100,
restoreAfterFinish:true,
beforeStartInternal:function(effect){
Element.makePositioned(effect.element.firstChild);
if(window.opera)effect.element.firstChild.style.top="";
Element.makeClipping(effect.element);
Element.show(element);
},
afterFinishInternal:function(effect){
Element.hide(effect.element);
Element.undoClipping(effect.element);
Element.undoPositioned(effect.element.firstChild);
effect.element.firstChild.style.bottom=oldInnerBottom;}
},arguments[1]||{})
);
}
Effect.SlideRight=function(element){
element=$(element);
Element.cleanWhitespace(element);
var oldInnerRight=element.firstChild.style.right;
var elementDimensions=Element.getDimensions(element);
return new Effect.Scale(element,100,
Object.extend({scaleContent:false,
scaleY:false,
scaleFrom:0,
scaleMode:{originalHeight:elementDimensions.height,originalWidth:elementDimensions.width},
restoreAfterFinish:true,
afterSetup:function(effect){
Element.makePositioned(effect.element.firstChild);
if(window.opera)effect.element.firstChild.style.top="";
Element.makeClipping(effect.element);
Element.show(element);
},
afterFinishInternal:function(effect){
Element.undoClipping(effect.element);
Element.undoPositioned(effect.element.firstChild);
effect.element.firstChild.style.right=oldInnerRight;
}
},arguments[1]||{})
);
}
Effect.SlideLeft=function(element){
element=$(element);
Element.cleanWhitespace(element);
var oldInnerRight=element.firstChild.style.right;
return new Effect.Scale(element,0,
Object.extend({scaleContent:false,
scaleY:false,
scaleMode:'box',
scaleFrom:100,
restoreAfterFinish:true,
beforeStartInternal:function(effect){
Element.makePositioned(effect.element.firstChild);
if(window.opera)effect.element.firstChild.style.top="";
Element.makeClipping(effect.element);
Element.show(element);
},
afterFinishInternal:function(effect){
Element.hide(effect.element);
Element.undoClipping(effect.element);
Element.undoPositioned(effect.element.firstChild);
effect.element.firstChild.style.right=oldInnerRight;
}
},arguments[1]||{})
);
}
var Autocompleter={}
Autocompleter.defaultTokens=" !@#$%^&*()-_=+\\|[{]};:'\",<.>/?　！◎＃￥％……※×（）－——＝＋÷§【『】』；：‘“，《。》、？";
Autocompleter.Base=function(){};
Autocompleter.Base.prototype={
baseInitialize:function(element,update,options){
this.element=$(element);
this.update=$(update);
this.hasFocus=false;
this.changed=false;
this.active=false;
this.index=0;
this.entryCount=0;
if(this.setOptions)
this.setOptions(options);
else
this.options=options||{};
this.options.paramName=this.options.paramName||this.element.name;
this.options.tokens=this.options.tokens||Autocompleter.defaultTokens.split('')||[];
this.options.frequency=this.options.frequency||0.15;
this.options.useEffect=this.options.useEffect||false;
this.options.minChars=this.options.minChars||1;
this.options.onShow=this.options.onShow||
function(element,update){
if(!update.style.position||update.style.position=='absolute'){
update.style.position='absolute';
Position.clone(element,update,{setHeight:false,offsetLeft:element.scrollLeft,offsetTop:element.offsetHeight});
}
if(this.options.useEffect)
Effect.Appear(update,{duration:0.1});
else
update.style.display='';
}.bind(this);
this.options.onHide=this.options.onHide||
function(element,update){
if(this.options.useEffect)
new Effect.Fade(update,{duration:0.1})
else
update.style.display='none';
}.bind(this);
if(typeof(this.options.tokens)=='string')
this.options.tokens=new Array(this.options.tokens);
this.observer=null;
this.element.setAttribute('autocomplete','off');
this.hideUpdateElement();
Event.observe(this.element,"blur",this.onBlur.bindAsEventListener(this));
if(navigator.appVersion.indexOf("MSIE")>=0){
Event.observe(this.element,"keydown",this.onKeyPress.bindAsEventListener(this));
}else{
Event.observe(this.element,"keypress",this.onKeyPress.bindAsEventListener(this));
}
this.lastValue=this.element.value;
this.hold=false;
this.keyPressed=false;
setInterval(this.checkInput.bind(this),100);
if(this.options.needFix){
if(!this.iefix&&
(navigator.appVersion.indexOf('MSIE')>0)&&
(navigator.userAgent.indexOf('Opera')<0)){
new Insertion.After(this.update,
'<iframe id="'+this.update.id+'_iefix" '+
'style="display:none;position:absolute;filter:progid:DXImageTransform.Microsoft.Alpha(opacity=0);" '+
'src="javascript:false;" frameborder="0" scrolling="no"></iframe>');
this.iefix=$(this.update.id+'_iefix');
}
}else{
this.update.style.zIndex=2;
}
},
show:function(){
if(this.options.beforeShow){
this.options.beforeShow();
}
if(Element.getStyle(this.update,'display')=='none')this.options.onShow(this.element,this.update);
if(this.options.needFix){
if(this.iefix)setTimeout(this.fixIEOverlapping.bind(this),50);
}
if(this.options.afterShow){
this.options.afterShow();
}
},
fixIEOverlapping:function(){
Position.clone(this.update,this.iefix);
this.iefix.style.zIndex=1;
this.update.style.zIndex=2;
Element.show(this.iefix);
},
hide:function(){
if(this.options.beforeHide){
this.options.beforeHide();
}
this.stopIndicator();
if(Element.getStyle(this.update,'display')!='none')this.options.onHide(this.element,this.update);
if(this.iefix)Element.hide(this.iefix);
if(this.options.afterHide){
this.options.afterHide();
}
},
hideUpdateElement:function(){
if(this.options.beforeHide){
this.options.beforeHide();
}
Element.hide(this.update);
if(this.options.afterHide){
this.options.afterHide();
}
},
startIndicator:function(){
if(this.options.indicator)Element.show(this.options.indicator);
},
stopIndicator:function(){
if(this.options.indicator)Element.hide(this.options.indicator);
},
checkInput:function(){
var v=this.element.value;
if(v!=this.lastValue&&!this.hold&&!this.keyPressed){
this.changed=true;
this.hasFocus=true;
if(this.observer)clearTimeout(this.observer);
this.observer=
setTimeout(this.onObserverEvent.bind(this),this.options.frequency*1000);
}
this.lastValue=v;
this.hold=false;
},
onKeyPress:function(event){
if(this.active)
switch(event.keyCode){
case Event.KEY_TAB:
case Event.KEY_RETURN:
this.selectEntry();
Event.stop(event);
case Event.KEY_ESC:
this.hide();
this.active=false;
Event.stop(event);
return;
case Event.KEY_LEFT:
case Event.KEY_RIGHT:
return;
case Event.KEY_UP:
this.markPrevious();
this.selectEntry();
this.render();
if(navigator.appVersion.indexOf('AppleWebKit')>0)Event.stop(event);
return;
case Event.KEY_DOWN:
this.markNext();
this.selectEntry();
this.render();
if(navigator.appVersion.indexOf('AppleWebKit')>0)Event.stop(event);
return;
}
else
if(event.keyCode==Event.KEY_TAB||event.keyCode==Event.KEY_RETURN)
return;
this.changed=true;
this.hasFocus=true;
if(this.observer)clearTimeout(this.observer);
this.keyPressed=true;
this.observer=
setTimeout(this.onObserverEvent.bind(this),this.options.frequency*1000);
},
onHover:function(event){
var element=Event.findElement(event,'LI');
if(this.index!=element.autocompleteIndex)
{
this.index=element.autocompleteIndex;
this.render();
}
Event.stop(event);
},
onClick:function(event){
var element=Event.findElement(event,'LI');
this.index=element.autocompleteIndex;
this.selectEntry();
this.hide();
},
onBlur:function(event){
setTimeout(this.hide.bind(this),250);
this.hasFocus=false;
this.active=false;
},
render:function(){
if(this.entryCount>0){
for(var i=0;i<this.entryCount;i++)
this.index==i?
Element.addClassName(this.getEntry(i),"selected"):
Element.removeClassName(this.getEntry(i),"selected");
if(this.hasFocus){
this.show();
this.active=true;
}
}else{
this.active=false;
this.hide();
}
},
markPrevious:function(){
if(this.index>0)this.index--
else this.index=this.entryCount-1;
},
markNext:function(){
if(this.index<this.entryCount-1)this.index++
else this.index=0;
},
getEntry:function(index){
if(index>=0&&this.update.firstChild&&this.update.firstChild.childNodes)
return this.update.firstChild.childNodes[index];
return null;
},
getCurrentEntry:function(){
return this.getEntry(this.index);
},
selectEntry:function(){
this.active=false;
this.updateElement(this.getCurrentEntry());
},
updateElement:function(selectedElement){
if(selectedElement==null){
try{this.element.focus()}catch(ex){};
this.hold=true;
return;
}
if(this.options.updateElement){
this.options.updateElement(selectedElement);
return;
}
var value=Element.collectTextNodesIgnoreClass(selectedElement,'informal');
var bakValue=this.elementValueForBak;
var lastTokenPos=this.findLastToken(bakValue);
if(lastTokenPos!=-1){
var newValue=bakValue.substr(0,lastTokenPos+1);
var whitespace=bakValue.substr(lastTokenPos+1).match(/^\s+/);
if(whitespace)
newValue+=whitespace[0];
this.element.value=newValue+value;
}else{
this.element.value=value;
}
try{this.element.focus()}catch(ex){};
this.hold=true;
if(this.options.afterUpdateElement)
this.options.afterUpdateElement(this.element,selectedElement);
},
updateChoices:function(choices){
if(!this.changed&&this.hasFocus){
this.update.innerHTML=choices;
Element.cleanWhitespace(this.update);
Element.cleanWhitespace(this.update.firstChild);
if(this.update.firstChild&&this.update.firstChild.childNodes){
this.entryCount=
this.update.firstChild.childNodes.length;
for(var i=0;i<this.entryCount;i++){
var entry=this.getEntry(i);
entry.autocompleteIndex=i;
this.addObservers(entry);
}
}else{
this.entryCount=0;
}
this.stopIndicator();
this.index=-1;
this.elementValueForBak=this.element.value;
this.render();
}
},
addObservers:function(element){
Event.observe(element,"mouseover",this.onHover.bindAsEventListener(this));
Event.observe(element,"click",this.onClick.bindAsEventListener(this));
},
onObserverEvent:function(){
this.changed=false;
this.keyPressed=false;
if(this.getToken().length>=this.options.minChars){
this.startIndicator();
this.getUpdatedChoices();
}else{
this.active=false;
this.hide();
}
},
getToken:function(){
var tokenPos=this.findLastToken(this.element.value);
if(tokenPos!=-1)
var ret=this.element.value.substr(tokenPos+1).replace(/^\s+/,'').replace(/\s+$/,'');
else
var ret=this.element.value;
return/\n/.test(ret)?'':ret;
},
findLastToken:function(value){
var lastTokenPos=-1;
for(var i=0;i<this.options.tokens.length;i++){
var thisTokenPos=value.lastIndexOf(this.options.tokens[i]);
if(thisTokenPos>lastTokenPos)
lastTokenPos=thisTokenPos;
}
return lastTokenPos;
}
}
Ajax.Autocompleter=Class.create();
Object.extend(Object.extend(Ajax.Autocompleter.prototype,Autocompleter.Base.prototype),{
initialize:function(element,update,url,options){
this.baseInitialize(element,update,options);
this.options.asynchronous=true;
this.options.defaultParams=this.options.parameters||null;
this.url=url;
this.cache={};
},
getUpdatedChoices:function(){
entry=encodeURIComponent(this.options.paramName)+'='+
encodeURIComponent(this.getToken());
if(this.cache[entry]){
return this.updateChoices(this.cache[entry]);
}
this.options.onComplete=this.onComplete.bind(this,entry);
this.options.parameters=this.options.callback?
this.options.callback(this.element,entry):entry;
if(this.options.defaultParams)
this.options.parameters+='&'+this.options.defaultParams;
new NetEase.CrossDomainRequest(this.url,this.options);
},
onComplete:function(entry,request){
this.cache[entry]=request.responseText;
this.updateChoices(request.responseText);
}
});
var TrimPath;
(function(){
if(TrimPath==null)
TrimPath=new Object();
if(TrimPath.evalEx==null)
TrimPath.evalEx=function(src){return eval(src);};
var UNDEFINED;
if(Array.prototype.pop==null)
Array.prototype.pop=function(){
if(this.length===0){return UNDEFINED;}
return this[--this.length];
};
if(Array.prototype.push==null)
Array.prototype.push=function(){
for(var i=0;i<arguments.length;++i){this[this.length]=arguments[i];}
return this.length;
};
TrimPath.parseTemplate=function(tmplContent,optTmplName,optEtc){
if(optEtc==null)
optEtc=TrimPath.parseTemplate_etc;
var funcSrc=parse(tmplContent,optTmplName,optEtc);
var func=TrimPath.evalEx(funcSrc,optTmplName,1);
if(func!=null)
return new optEtc.Template(optTmplName,tmplContent,funcSrc,func,optEtc);
return null;
}
try{
String.prototype.process=function(context,optFlags){
var template=TrimPath.parseTemplate(this,null);
if(template!=null)
return template.process(context,optFlags);
return this;
}
}catch(e){
}
TrimPath.parseTemplate_etc={};
TrimPath.parseTemplate_etc.statementTag="forelse|for|if|elseif|else|var|macro";
TrimPath.parseTemplate_etc.statementDef={
"if":{delta:1,prefix:"if (",suffix:") {",paramMin:1},
"else":{delta:0,prefix:"} else {"},
"elseif":{delta:0,prefix:"} else if (",suffix:") {",paramDefault:"true"},
"/if":{delta:-1,prefix:"}"},
"for":{delta:1,paramMin:3,
prefixFunc:function(stmtParts,state,tmplName,etc){
if(stmtParts[2]!="in")
throw new etc.ParseError(tmplName,state.line,"bad for loop statement: "+stmtParts.join(' '));
var iterVar=stmtParts[1];
var listVar="__LIST__"+iterVar;
return["var ",listVar," = ",stmtParts[3],";",
"var __LENGTH_STACK__;",
"if (typeof(__LENGTH_STACK__) == 'undefined' || !__LENGTH_STACK__.length) __LENGTH_STACK__ = new Array();",
"__LENGTH_STACK__[__LENGTH_STACK__.length] = 0;",
"if ((",listVar,") != null) { ",
"var ",iterVar,"_ct = 0;",
"for (var ",iterVar,"_index in ",listVar,") { ",
iterVar,"_ct++;",
"if (typeof(",listVar,"[",iterVar,"_index]) == 'function') {continue;}",
"__LENGTH_STACK__[__LENGTH_STACK__.length - 1]++;",
"var ",iterVar," = ",listVar,"[",iterVar,"_index];"].join("");
}},
"forelse":{delta:0,prefix:"} } if (__LENGTH_STACK__[__LENGTH_STACK__.length - 1] == 0) { if (",suffix:") {",paramDefault:"true"},
"/for":{delta:-1,prefix:"} }; delete __LENGTH_STACK__[__LENGTH_STACK__.length - 1];"},
"var":{delta:0,prefix:"var ",suffix:";"},
"macro":{delta:1,
prefixFunc:function(stmtParts,state,tmplName,etc){
var macroName=stmtParts[1].split('(')[0];
return["var ",macroName," = function",
stmtParts.slice(1).join(' ').substring(macroName.length),
"{ var _OUT_arr = []; var _OUT = { write: function(m) { if (m) _OUT_arr.push(m); } }; "].join('');
}},
"/macro":{delta:-1,prefix:" return _OUT_arr.join(''); };"}
}
TrimPath.parseTemplate_etc.modifierDef={
"eat":function(v){return"";},
"escape":function(s){return String(s).replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;");},
"capitalize":function(s){return String(s).toUpperCase();},
"default":function(s,d){return s!=null?s:d;}
}
TrimPath.parseTemplate_etc.modifierDef.h=TrimPath.parseTemplate_etc.modifierDef.escape;
TrimPath.parseTemplate_etc.Template=function(tmplName,tmplContent,funcSrc,func,etc){
this.process=function(context,flags){
if(context==null)
context={};
if(context._MODIFIERS==null)
context._MODIFIERS={};
if(context.defined==null)
context.defined=function(str){return(context[str]!=undefined);};
for(var k in etc.modifierDef){
if(context._MODIFIERS[k]==null)
context._MODIFIERS[k]=etc.modifierDef[k];
}
if(flags==null)
flags={};
var resultArr=[];
var resultOut={write:function(m){resultArr.push(m);}};
try{
func(resultOut,context,flags);
}catch(e){
if(flags.throwExceptions==true)
throw e;
var result=new String(resultArr.join("")+"[ERROR: "+e.toString()+(e.message?'; '+e.message:'')+"]");
result["exception"]=e;
return result;
}
return resultArr.join("");
}
this.name=tmplName;
this.source=tmplContent;
this.sourceFunc=funcSrc;
this.toString=function(){return"TrimPath.Template ["+tmplName+"]";}
}
TrimPath.parseTemplate_etc.ParseError=function(name,line,message){
this.name=name;
this.line=line;
this.message=message;
}
TrimPath.parseTemplate_etc.ParseError.prototype.toString=function(){
return("TrimPath template ParseError in "+this.name+": line "+this.line+", "+this.message);
}
var parse=function(body,tmplName,etc){
body=cleanWhiteSpace(body);
var funcText=["var TrimPath_Template_TEMP = function(_OUT, _CONTEXT, _FLAGS) { with (_CONTEXT) {"];
var state={stack:[],line:1};
var endStmtPrev=-1;
while(endStmtPrev+1<body.length){
var begStmt=endStmtPrev;
begStmt=body.indexOf("{",begStmt+1);
while(begStmt>=0){
var endStmt=body.indexOf('}',begStmt+1);
var stmt=body.substring(begStmt,endStmt);
var blockrx=stmt.match(/^\{(cdata|minify|eval)/);
if(blockrx){
var blockType=blockrx[1];
var blockMarkerBeg=begStmt+blockType.length+1;
var blockMarkerEnd=body.indexOf('}',blockMarkerBeg);
if(blockMarkerEnd>=0){
var blockMarker;
if(blockMarkerEnd-blockMarkerBeg<=0){
blockMarker="{/"+blockType+"}";
}else{
blockMarker=body.substring(blockMarkerBeg+1,blockMarkerEnd);
}
var blockEnd=body.indexOf(blockMarker,blockMarkerEnd+1);
if(blockEnd>=0){
emitSectionText(body.substring(endStmtPrev+1,begStmt),funcText);
var blockText=body.substring(blockMarkerEnd+1,blockEnd);
if(blockType=='cdata'){
emitText(blockText,funcText);
}else if(blockType=='minify'){
emitText(scrubWhiteSpace(blockText),funcText);
}else if(blockType=='eval'){
if(blockText!=null&&blockText.length>0)
funcText.push('_OUT.write( (function() { '+blockText+' })() );');
}
begStmt=endStmtPrev=blockEnd+blockMarker.length-1;
}
}
}else if(body.charAt(begStmt-1)!='$'&&
body.charAt(begStmt-1)!='\\'){
var offset=(body.charAt(begStmt+1)=='/'?2:1);
if(body.substring(begStmt+offset,begStmt+10+offset).search(TrimPath.parseTemplate_etc.statementTag)==0)
break;
}
begStmt=body.indexOf("{",begStmt+1);
}
if(begStmt<0)
break;
var endStmt=body.indexOf("}",begStmt+1);
if(endStmt<0)
break;
emitSectionText(body.substring(endStmtPrev+1,begStmt),funcText);
emitStatement(body.substring(begStmt,endStmt+1),state,funcText,tmplName,etc);
endStmtPrev=endStmt;
}
emitSectionText(body.substring(endStmtPrev+1),funcText);
if(state.stack.length!=0)
throw new etc.ParseError(tmplName,state.line,"unclosed, unmatched statement(s): "+state.stack.join(","));
funcText.push("}}; TrimPath_Template_TEMP");
return funcText.join("");
}
var emitStatement=function(stmtStr,state,funcText,tmplName,etc){
var parts=stmtStr.slice(1,-1).split(' ');
var stmt=etc.statementDef[parts[0]];
if(stmt==null){
emitSectionText(stmtStr,funcText);
return;
}
if(stmt.delta<0){
if(state.stack.length<=0)
throw new etc.ParseError(tmplName,state.line,"close tag does not match any previous statement: "+stmtStr);
state.stack.pop();
}
if(stmt.delta>0)
state.stack.push(stmtStr);
if(stmt.paramMin!=null&&
stmt.paramMin>=parts.length)
throw new etc.ParseError(tmplName,state.line,"statement needs more parameters: "+stmtStr);
if(stmt.prefixFunc!=null)
funcText.push(stmt.prefixFunc(parts,state,tmplName,etc));
else
funcText.push(stmt.prefix);
if(stmt.suffix!=null){
if(parts.length<=1){
if(stmt.paramDefault!=null)
funcText.push(stmt.paramDefault);
}else{
for(var i=1;i<parts.length;i++){
if(i>1)
funcText.push(' ');
funcText.push(parts[i]);
}
}
funcText.push(stmt.suffix);
}
}
var emitSectionText=function(text,funcText){
if(text.length<=0)
return;
var nlPrefix=0;
var nlSuffix=text.length-1;
while(nlPrefix<text.length&&(text.charAt(nlPrefix)=='\n'))
nlPrefix++;
while(nlSuffix>=0&&(text.charAt(nlSuffix)==' '||text.charAt(nlSuffix)=='\t'))
nlSuffix--;
if(nlSuffix<nlPrefix)
nlSuffix=nlPrefix;
if(nlPrefix>0){
funcText.push('if (_FLAGS.keepWhitespace == true) _OUT.write("');
var s=text.substring(0,nlPrefix).replace('\n','\\n');
if(s.charAt(s.length-1)=='\n')
s=s.substring(0,s.length-1);
funcText.push(s);
funcText.push('");');
}
var lines=text.substring(nlPrefix,nlSuffix+1).split('\n');
for(var i=0;i<lines.length;i++){
emitSectionTextLine(lines[i],funcText);
if(i<lines.length-1)
funcText.push('_OUT.write("\\n");\n');
}
if(nlSuffix+1<text.length){
funcText.push('if (_FLAGS.keepWhitespace == true) _OUT.write("');
var s=text.substring(nlSuffix+1).replace('\n','\\n');
if(s.charAt(s.length-1)=='\n')
s=s.substring(0,s.length-1);
funcText.push(s);
funcText.push('");');
}
}
var emitSectionTextLine=function(line,funcText){
var endMarkPrev='}';
var endExprPrev=-1;
while(endExprPrev+endMarkPrev.length<line.length){
var begMark="${",endMark="}";
var begExpr=line.indexOf(begMark,endExprPrev+endMarkPrev.length);
if(begExpr<0)
break;
if(line.charAt(begExpr+2)=='%'){
begMark="${%";
endMark="%}";
}
var endExpr=line.indexOf(endMark,begExpr+begMark.length);
if(endExpr<0)
break;
emitText(line.substring(endExprPrev+endMarkPrev.length,begExpr),funcText);
var exprArr=line.substring(begExpr+begMark.length,endExpr).replace(/\|\|/g,"#@@#").split('|');
for(var k in exprArr){
if(exprArr[k].replace)
exprArr[k]=exprArr[k].replace(/#@@#/g,'||');
}
funcText.push('_OUT.write(');
emitExpression(exprArr,exprArr.length-1,funcText);
funcText.push(');');
endExprPrev=endExpr;
endMarkPrev=endMark;
}
emitText(line.substring(endExprPrev+endMarkPrev.length),funcText);
}
var emitText=function(text,funcText){
if(text==null||
text.length<=0)
return;
text=text.replace(/\\/g,'\\\\');
text=text.replace(/\n/g,'\\n');
text=text.replace(/"/g,'\\"');
funcText.push('_OUT.write("');
funcText.push(text);
funcText.push('");');
}
var emitExpression=function(exprArr,index,funcText){
var expr=exprArr[index];
if(index<=0){
funcText.push(expr);
return;
}
var parts=expr.split(':');
funcText.push('_MODIFIERS["');
funcText.push(parts[0]);
funcText.push('"](');
emitExpression(exprArr,index-1,funcText);
if(parts.length>1){
funcText.push(',');
funcText.push(parts[1]);
}
funcText.push(')');
}
var cleanWhiteSpace=function(result){
result=result.replace(/\t/g,"    ");
result=result.replace(/\r\n/g,"\n");
result=result.replace(/\r/g,"\n");
result=result.replace(/^(\s*\S*(\s+\S+)*)\s*$/,'$1');
return result;
}
var scrubWhiteSpace=function(result){
result=result.replace(/^\s+/g,"");
result=result.replace(/\s+$/g,"");
result=result.replace(/\s+/g," ");
result=result.replace(/^(\s*\S*(\s+\S+)*)\s*$/,'$1');
return result;
}
TrimPath.parseDOMTemplate=function(elementId,optDocument,optEtc){
if(optDocument==null)
optDocument=document;
var element=optDocument.getElementById(elementId);
var content=element.value;
if(content==null)
content=element.innerHTML;
content=content.replace(/&lt;/g,"<").replace(/&gt;/g,">");
return TrimPath.parseTemplate(content,elementId,optEtc);
}
TrimPath.processDOMTemplate=function(elementId,context,optFlags,optDocument,optEtc){
return TrimPath.parseDOMTemplate(elementId,optDocument,optEtc).process(context,optFlags);
}
})();
TrimPath.parseTemplate_etc.modifierDef.escape=function(s){
return String(s).replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/"/g,"&quot;");
}
TrimPath.parseTemplate_etc.modifierDef.escapeButAmp=function(s){
return String(s).replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/"/g,"&quot;");
}
TrimPath.parseTemplate_etc.modifierDef.js_string=function(s){
return String(s).replace(/\\/g,"\\\\").replace(/'/g,"\\&#39;").replace(/"/g,"\\&#34;");
}
TrimPath.parseTemplate_etc.modifierDef.substring=function(s,s1,s2){
return String(s).substr(s1,s2);
}
TrimPath.parseTemplate_etc.modifierDef.replace=function(s,s1,s2){
return String(s).replace(s1,s2)
}
TrimPath.parseTemplate_etc.modifierDef.parentDomain=function(s){
return DomainMap.getParentDomain(s);
}
TrimPath.parseTemplate_etc.modifierDef.to_url=function(s){
if(s==null)
return"#";
var url=/^(.+):\/\/(.*)$/;
if(!url.test(s))
s="http://"+s;
return s;
}
TrimPath.parseTemplate_etc.modifierDef.showBr=function(s){
return String(s).replace(/\n/g,"<br>");
}
TrimPath.parseTemplate_etc.modifierDef.erase=function(s,i){
s=s+'';
if(s.length<=i)
return s;
return s.substr(0,i)+'...';
}
TrimPath.parseTemplate_etc.modifierDef.getIPNames=function(s,ip){
if(s!=null&&s!="")
return s;
if(ip!=null&&ip!=""&&ip!=undefined){
var i=ip.lastIndexOf(".");
return"IP: "+ip.substring(0,i)+".*";
}else{
return"未知区域用户";
}
}
TrimPath.parseTemplate_etc.modifierDef.toTimeLength=function(str){
var t=parseInt(str);
if(t<0)return'00:00';
var s=t%60+'';if(s.length==1)s='0'+s;
var m=Math.floor(t/60)+'';if(m.length==1)m='0'+m;
return m+':'+s;
}
TrimPath.parseTemplate_etc.modifierDef.to_img=function(s,url){
if(s==null||s.indexOf('.jpg')<0)
return url;
return s;
}
TrimPath.parseTemplate_etc.modifierDef.profile_img=function(s){
return TrimPath.parseTemplate_etc.modifierDef.to_img(s,Const.STDomain+"/style/common/user_default_small.png");
}
TrimPath.parseTemplate_etc.modifierDef.genYearSelect=function(to,from,value){
var s=[];
for(var i=to;i>=from;i--){
if(i==value){
s.push('<option value="'+i+'" SELECTED>'+i+'</option>');
}else{
s.push('<option value="'+i+'">'+i+'</option>');
}
}
return s.join('');
}
String.prototype.processUseCache=function(context,optFlags){
if(this.__template__==null)
this.__template__=TrimPath.parseTemplate(this,null);
if(this.__template__!=null)
return this.__template__.process(context,optFlags);
return this;
}
DomainMap={
cookieDomain:'.163.com',
serverHostName:'blog.163.com',
serverDomain:'.blog.163.com',
getParentDomain:function(s){
if(s.indexOf('@')!=-1||s.indexOf('_')!=-1||s.indexOf('..')!=-1||s.charAt(s.length-1)=='.'||s.toLowerCase().substr(s.length-4)=='.www')
return this.serverHostName+'/'+s;
return s+this.serverDomain;
},
getPCDomain:function(userName,childId){
return"blog.163.com/"+userName+"/home/#tid=9997&prv=true&uid="+childId;
}
};
if(NetEase==undefined){
var NetEase={};
}
NetEase.DwrLogger=Class.create();
NetEase.DwrLogger.prototype={
initialize:function(){
this.options=Object.extend({
fade:true,
container:null,
style:Const.STDomain+'/style/common',
width:200,
opacity:0.80,
timeout:3000,
delay:1500,
position:'absolute'
},arguments[0]||{});
this._init();
this.loggerIndex=-1;
this.cacheLogger=[];
},
_init:function(){
this.loggerZone=$('$_loggerZone');
if(!this.loggerZone){
this.loggerZone=document.createElement('div');
this.loggerZone.setAttribute('id','$_loggerZone');
this.loggerZone.style.position=this.options.position;
this.loggerZone.style.zIndex="100019";
this.loggerZone.style.right='20px';
this.loggerZone.style.top='20px';
if(this.options.container!=null){
this.options.container.appendChild(this.loggerZone);
}else
UD.layer.appendChild(this.loggerZone);
}
},
appendMsg:function(msg,type){
this.loggerIndex++;
var logger={};
logger.id="$_loggerMsg"+this.loggerIndex;
logger.msg=msg;
logger.type=type;
var messageZone=document.createElement('div');
messageZone.setAttribute('id',logger.id);
if(logger.type=="info"){
messageZone.innerHTML='<img src="'+this.options.style+'/ico_info.gif"/>&nbsp;'+msg;
}else
if(logger.type=="ok"){
messageZone.innerHTML='<img src="'+this.options.style+'/ico_confirm.gif"/>&nbsp;'+msg;
}
else
if(logger.type=="error"){
messageZone.innerHTML='<img src="'+this.options.style+'/ico_error.gif"/>&nbsp;'+msg;
}else
messageZone.innerHTML='<img src="'+this.options.style+'/ico_info.gif"/>&nbsp;'+msg;
messageZone.style.display="block";
messageZone.style.backgroundColor="#ffffff";
messageZone.style.color="#000000";
messageZone.style.fontSize="12px";
messageZone.style.margin="5px";
messageZone.style.padding="2px";
messageZone.style.textAlign="left";
messageZone.style.MozOpacity=this.options.opacity+"";
messageZone.style.filter="alpha(opacity="+this.options.opacity*100+")";
messageZone.style.width=this.options.width+'px';
this.loggerZone.insertBefore(messageZone,this.loggerZone.firstChild);
this.cacheLogger.push(logger);
if(!this.toFadeCheck)
this.toFadeCheck=window.setTimeout(this._clean.bind(this,logger),this.options.timeout);
},
_clean:function(logger){
if(this.options.fade){
if($(logger.id)){
Effect.Fade(logger.id,{duration:0.5,userCallBack:this._remove.bind(this,logger)});
}
}else{
this._remove(logger);
}
},
setMsg:function(msg,type){
this.toFadeCheck=window.clearTimeout(this.toFadeCheck);
if(this.cacheLogger.length)
this._remove(this.cacheLogger[0]);
this.appendMsg(msg,type);
},
_remove:function(logger){
if($(logger.id)){
$(logger.id).innerHTML='';
$(logger.id).style.display='none';
Element.removeChild($(logger.id));
this.cacheLogger.shift(logger);
if(this.cacheLogger.length>0){
logger=this.cacheLogger[0];
this.toFadeCheck=window.setTimeout(this._clean.bind(this,logger),this.options.delay);
}else{
this.toFadeCheck=null;
}
}
}
}
NetEase.DwrLogger.TYPE_INFO="info";
NetEase.DwrLogger.TYPE_OK="ok";
NetEase.DwrLogger.TYPE_ERROR="error";
if(NetEase==undefined){
var NetEase={};
}
NetEase.SimplePageLayer=Class.create();
NetEase.SimplePageLayer.prototype={
initialize:function(observerId){
this.observer=(observerId==null)?document:$(observerId);
this.pageLayerArray=[];
this.observeHandler=this._observeHandler.bind(this);
Event.observe(this.observer,'click',this.observeHandler);
},
destory:function(){
Event.stopObserving(this.observer,'click',this.observeHandler);
this.pageLayerArray=null;
},
addPageLayer:function(id,openId,menuId){
var _layer={};
_layer.id=id;
_layer.options=Object.extend(
{
openHandler:Prototype.emptyFunction,
closeHandler:Prototype.emptyFunction,
delay:false,
forceClose:false,
isOpen:false
},arguments[3]||{}
);
_layer.openHandler=this._openHandler.bind(this,_layer,"open");
if(_layer.options.delay){
_layer.menuHandler=this._delayOpenMenuHandler.bind(this,_layer);
}else{
_layer.menuHandler=this._openHandler.bind(this,_layer,"menu");
}
_layer.closeHandler=this._closeHandler.bind(this,_layer);
_layer.blockCloseHandler=this._blockCloseHandler.bind(this,_layer);
if(openId!=null){
_layer.opener=$(openId);
Event.observe(_layer.opener,'click',_layer.openHandler);
}
if(menuId!=null){
_layer.menuer=$(menuId);
Event.observe(_layer.menuer,'mouseover',_layer.menuHandler);
Event.observe(_layer.menuer,'mouseout',_layer.closeHandler);
Event.observe(_layer.id,'mouseover',_layer.blockCloseHandler);
Event.observe(_layer.id,'mouseout',_layer.closeHandler);
}
this.pageLayerArray.push(_layer);
},
removePageLayer:function(id){
this.pageLayerArray=this.pageLayerArray.reject(
function(e){
if(e.id==id){
if(e.opener!=null)
Event.stopObserving(e.opener,'click',e.openHandler);
if(e.menuer!=null){
Event.stopObserving(e.menuer,'mouseover',e.menuHandler);
Event.stopObserving(e.menuer,'mouseout',e.closeHandler);
Event.stopObserving(e.id,'mouseover',e.blockCloseHandler);
Event.stopObserving(e.id,'mouseout',e.closeHandler);
}
return true;
}
return false;
}.bind(this)
);
},
_delayOpenMenuHandler:function(layer,event){
event=event||window.event;
Event.stop(event);
if(!this.delayTask)
this.delayTask=window.setTimeout(this._openHandler.bind(this,layer,"menu"),layer.options.delay);
},
_openHandler:function(layer,type,event){
event=event||window.event;
this.delayTask=null;
if(type=="menu"){
if(event)
Event.stop(event);
layer.blockClose=true;
this._observeHandler();
if(!layer.options.isOpen){
layer.options.isOpen=true;
layer.options.openHandler(layer);
return;
}
}else{
layer.stopEvent=true;
if(layer.options.isOpen){
layer.options.isOpen=false;
layer.options.closeHandler(layer);
}else{
layer.options.isOpen=true;
layer.options.openHandler(layer);
}
}
},
_blockCloseHandler:function(layer){
layer.blockClose=true;
},
_closeHandler:function(layer){
if(this.delayTask){
window.clearTimeout(this.delayTask);
this.delayTask=null;
}
layer.blockClose=false;
window.setTimeout(this._observeHandler.bind(this),100);
},
_observeHandler:function(){
var _layer;
for(var i=0;i<this.pageLayerArray.length;i++){
_layer=this.pageLayerArray[i];
if(_layer.blockClose)continue;
if(_layer.stopEvent==true&&!_layer.options.forceClose){
_layer.stopEvent=false;
continue;
}
if(_layer.options.isOpen||_layer.options.forceClose){
_layer.options.isOpen=false;
_layer.options.closeHandler(_layer);
}
}
}
}
if(NetEase==undefined){
var NetEase={};
}
NetEase.JSWindowManager=Class.create();
NetEase.JSWindowManager.prototype={
initialize:function(){
this.options=Object.extend(
{
prefix:"$_",
systemBarPostfix:"_system_bar",
panelPostfix:"_panel",
titlePostfix:"_title",
closePostfix:"_close",
allowDrag:true,
useDragOpacity:true,
simpleDrag:false,
delSelect:true,
noUD:false
},arguments[0]||{});
this.jsWindowList=[];
this.baseIndex=10000;
this.indexAdd=0;
this.topIndex=99999;
if(this.options.allowDrag)
this.simpleDragDrop=new NetEase.SimpleDragDrop({useDragOpacity:this.options.useDragOpacity,simpleDrag:this.options.simpleDrag});
this.curWindow=null;
},
existWindow:function(id){
var jsWindow=this.jsWindowList.detect(this._detectIter.bind(this,id));
return jsWindow?true:false;
},
getWindow:function(id){
return this.jsWindowList.detect(this._detectIter.bind(this,id));
},
createWindow:function(id,params){
if($(id)!=null){
alert("该窗口已经存在!");
return;
}
var options=Object.extend(
{
className:false,
left:false,
top:false,
width:600,
height:400,
allowDrag:true,
notKeepPos:true,
onTop:false,
hasSystemBar:true,
systemBarClassName:'titlebar',
handleClass:'$$_handle_class',
titleId:false,
title:'JSWindow',
hasCloseId:true,
closeId:false,
hiddenOnClose:true,
panelClassName:'content',
useShadow:true,
needCover:false,
allowScroll:false,
opacity:0.25,
beforShowFunc:Prototype.emptyFunction,
afterShowFunc:Prototype.emptyFunction,
beforeHiddenFunc:Prototype.emptyFunction,
afterHiddenFunc:Prototype.emptyFunction,
beforeCloseFunc:Prototype.emptyFunction,
afterCloseFunc:Prototype.emptyFunction
},params||{});
if(!this.options.allowDrag)options.allowDrag=false;
this._buildPos(options);
if(options.onTop){
options.zIndex=this.topIndex;
}else{
options.zIndex=this.baseIndex+(this.indexAdd++);
}
if(options.titleId==false)
options.titleId=this.options.prefix+id+this.options.systemBarPostfix+this.options.titlePostfix;
if(options.hasSystemBar&&options.hasCloseId&&options.closeId==false)
options.closeId=this.options.prefix+id+this.options.systemBarPostfix+this.options.closePostfix;
var jsWindow=this._createJSWindow(id,options);
jsWindow.windowHtml=this._createWindowHtml(id,options);
if(options.hasSystemBar)
jsWindow.systemBar=this._createSystemBar(jsWindow.windowHtml,id,options);
jsWindow.panel=this._createPanel(jsWindow.windowHtml,id,options);
this.jsWindowList.push(jsWindow);
return jsWindow;
},
_getContainer:function(options){
if(this.options.noUD||options.noUD)
return document.body;
var conDiv=(options.notKeepPos)?UD.layer:UD.body;
if(conDiv==null)conDiv=document.body;
return conDiv;
},
_getLeft:function(width){
var left=(document.documentElement.clientWidth-width)/2;
if(left<10)left=10;
return left;
},
_getTop:function(height,container){
var top=0;
if(container==UD.layer||container==document.body){
top=document.documentElement.scrollTop+
(document.documentElement.clientHeight-height)/2;
}
else{
top=UD.body.parentNode.scrollTop+
(document.documentElement.clientHeight-height)/2;
}
if(top<10)top=10;
return top;
},
_buildPos:function(options){
if(!options.left){
options.left=this._getLeft(options.width);
options._caluLeft=true;
}
if(!options.top){
options.top=this._getTop(options.height,this._getContainer(options));
options._caluTop=true;
}
},
setPos:function(id,pos){
var jsWindow=this.jsWindowList.detect(this._detectIter.bind(this,id));
this._setPos(jsWindow,pos);
return jsWindow;
},
_setPos:function(jsWindow,pos){
if(jsWindow){
Object.extend(jsWindow.options,pos);
jsWindow.options.notKeepPos=true;
this._buildPos(jsWindow.options);
}
},
showWindow:function(id){
var jsWindow=this.jsWindowList.detect(this._detectIter.bind(this,id));
this._showWindow(jsWindow);
return jsWindow;
},
_showWindow:function(jsWindow){
if(jsWindow){
this._showMode(jsWindow,true);
jsWindow.options.beforShowFunc(jsWindow);
if(jsWindow.options.notKeepPos){
if(jsWindow.options._caluLeft){
jsWindow.options.left=this._getLeft(jsWindow.options.width);;
}
jsWindow.windowHtml.style.left=jsWindow.options.left+"px";
if(jsWindow.options._caluTop){
jsWindow.options.top=this._getTop(jsWindow.options.height,jsWindow.containerDiv);
}
jsWindow.windowHtml.style.top=jsWindow.options.top+"px";
}
jsWindow.windowHtml.style.display="";
if(this.options.delSelect)
this._hideSelect(true,jsWindow.windowHtml);
jsWindow.options.afterShowFunc(jsWindow);
this.curWindow=jsWindow;
}
},
_showMode:function(jsWindow,show){
if(!jsWindow.options.needCover)return;
var conDiv=jsWindow.containerDiv;
if(jsWindow.options.allowScroll)
conDiv=UD.layer;
var gapDiv=$(conDiv.id+"_gap");
if(!gapDiv){
gapDiv=document.createElement('div');
gapDiv.id=conDiv.id+"_gap"
gapDiv.style.position='absolute';
gapDiv.style.display='none';
gapDiv.style.left='0px';
gapDiv.style.top='0px';
gapDiv.style.backgroundColor="#ffffff";
gapDiv.style.MozOpacity=""+jsWindow.options.opacity;
gapDiv.style.opacity=""+jsWindow.options.opacity;
gapDiv.style.filter="alpha(opacity="+jsWindow.options.opacity*100+")";
gapDiv.style.width=conDiv.parentNode.scrollWidth+'px';
gapDiv.style.height=conDiv.parentNode.scrollHeight+'px';
gapDiv.style.zIndex=this.baseIndex-1;
if(conDiv==document.body){
conDiv.appendChild(gapDiv);
}else{
conDiv.parentNode.appendChild(gapDiv);
}
}
gapDiv.style.display=show?'block':'none';
},
updateTitle:function(id,title){
var jsWindow=this.jsWindowList.detect(this._detectIter.bind(this,id));
this._updateTitle(jsWindow,title);
return jsWindow;
},
_updateTitle:function(jsWindow,title){
if(jsWindow){
jsWindow.options.title=title;
$(jsWindow.options.titleId).innerHTML=title;
}
},
hiddenWindow:function(id){
var jsWindow=this.jsWindowList.detect(this._detectIter.bind(this,id));
this._hiddenWindow(jsWindow);
return jsWindow
},
_hiddenWindow:function(jsWindow){
if(jsWindow){
this._showMode(jsWindow,false);
jsWindow.options.beforeHiddenFunc(jsWindow);
jsWindow.windowHtml.style.display="none";
if(this.options.delSelect)
this._hideSelect(false,jsWindow.windowHtml);
jsWindow.options.afterHiddenFunc(jsWindow);
if(this.curWindow==jsWindow)this.curWindow=null;
}
},
closeWindow:function(id){
var jsWindow=this.jsWindowList.detect(this._detectIter.bind(this,id));
return this._closeWindow(jsWindow);
},
_closeWindow:function(jsWindow){
if(jsWindow){
this._showMode(jsWindow,false);
jsWindow.options.beforeCloseFunc(jsWindow);
jsWindow.windowHtml.style.display="none";
if(this.options.delSelect)
this._hideSelect(false,jsWindow.windowHtml);
if(jsWindow.options.allowDrag)
this.simpleDragDrop.removeDraggable(jsWindow.windowHtml.id);
if(jsWindow.options.hasSystemBar){
Element.removeChild(jsWindow.systemBar);
jsWindow.systemBar=null;
}
Element.removeChild(jsWindow.panel);
jsWindow.panel=null;
Element.removeChild(jsWindow.windowHtml);
jsWindow.windowHtml=null;
this.jsWindowList=this.jsWindowList.reject(this._detectIter.bind(this,jsWindow.id));
jsWindow.options.afterCloseFunc();
if(this.curWindow==jsWindow)this.curWindow=null;
}
},
focusWindow:function(jsWindow){
if(jsWindow){
this._focusWindow(jsWindow.id);
}
},
_focusWindow:function(id){
var pos=-1;
this.indexAdd=0;
for(var i=0;i<this.jsWindowList.length;i++){
if(this.jsWindowList[i].id!=id){
if(!this.jsWindowList[i].options.onTop){
this.jsWindowList[i].options.zIndex=this.baseIndex+(this.indexAdd++);
this.jsWindowList[i].windowHtml.style.zIndex=this.jsWindowList[i].options.zIndex;
}
}else{
pos=i;
}
}
if(pos>-1){
this.jsWindowList[pos].options.zIndex=this.baseIndex+(this.indexAdd++);
this.jsWindowList[pos].windowHtml.style.zIndex=this.jsWindowList[pos].options.zIndex;
this.curWindow=this.jsWindowList[pos];
}
},
_detectIter:function(id,element){
if(id==element.id){
return true;
}
return false;
},
_createWindowHtml:function(id,options){
var windowHtml=document.createElement('div');
windowHtml.id=this.options.prefix+id;
windowHtml.className='g_lay_com '+(options.className?options.className:'')+(options.useShadow?' g_f_shw':'');
windowHtml.style.display="none";
windowHtml.style.position="absolute";
windowHtml.style.left=options.left+"px";
windowHtml.style.top=options.top+"px";
windowHtml.style.width=options.width+"px";
if(!options.notKeepPos)
windowHtml.container='in';
if(options.height!="auto"){
if(isIE){
if(IEVer>=7){
windowHtml.style.minHeight=options.height+"px";
windowHtml.style.height="auto";
}else{
windowHtml.style.height=options.height+"px";
}
}else{
windowHtml.style.minHeight=options.height+"px";
windowHtml.style.height="auto";
}
}
windowHtml.style.zIndex=options.zIndex;
this._getContainer(options).appendChild(windowHtml);
Event.observe(windowHtml.id,'click',this._focusWindow.bind(this,id));
return windowHtml;
},
_createSystemBar:function(windowHtml,id,options){
var systemBar=document.createElement('div');
systemBar.id=this.options.prefix+id+this.options.systemBarPostfix;
if(options.systemBarClassName)
systemBar.className=options.systemBarClassName;
var g_c_move=options.allowDrag?'g_c_move':'';
var html='<div>';
if(options.hasCloseId){
html+='<span id ="'+options.closeId+'" class="r" title="关闭">&nbsp;</span>';
}
html+='<span style="display:block;" class="g_t_hide $$_handle_class '+g_c_move+'" id="'+options.titleId+'">'+options.title+'</span></div>';
systemBar.innerHTML=html;
windowHtml.appendChild(systemBar);
if(options.hasCloseId){
if(options.hiddenOnClose){
Event.observe(options.closeId,'click',this.hiddenWindow.bind(this,id));
}else{
Event.observe(options.closeId,'click',this.closeWindow.bind(this,id));
}
}
if(options.allowDrag){
this.simpleDragDrop.removeDraggable(windowHtml.id);
this.simpleDragDrop.addDraggable(windowHtml.id,{handle:'$$_handle_class',zindex:this.topIndex-1});
}
return systemBar;
},
_createPanel:function(windowHtml,id,options){
var panel=document.createElement('div');
panel.id=this.options.prefix+id+this.options.panelPostfix;
if(options.panelClassName)
panel.className=options.panelClassName;
windowHtml.appendChild(panel);
return panel;
},
_hideSelect:function(hide,noHideParent){
if(isIE&&IEVer<7){
var selectArray=document.getElementsByTagName("select");
var noHideSelectArray=noHideParent?noHideParent.getElementsByTagName("select"):null;
noHideSelectArray=$A(noHideSelectArray||[]);
if(selectArray){
for(var i=0;i<selectArray.length;i++){
if(selectArray[i].getAttribute("nohide")!="true"&&!noHideSelectArray.include(selectArray[i])){
selectArray[i].style.visibility=(hide==true)?'hidden':'inherit';
}
}
}
}
},
_createJSWindow:function(id,options){
var jsWindow={};
jsWindow.id=id;
jsWindow.options=options;
jsWindow.containerDiv=this._getContainer(options);
jsWindow.setPos=function(pos){this._setPos(jsWindow,pos);}.bind(this);
jsWindow.showWindow=function(){this._showWindow(jsWindow);}.bind(this);
jsWindow.updateTitle=function(title){this._updateTitle(jsWindow,title);}.bind(this);
jsWindow.hiddenWindow=function(){this._hiddenWindow(jsWindow);}.bind(this);
jsWindow.closeWindow=function(){this._closeWindow(jsWindow);}.bind(this);
jsWindow.focusWindow=function(){this.focusWindow(jsWindow);}.bind(this);
return jsWindow;
}
}
