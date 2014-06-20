/**
 * @author xuanzhang
 * Genearate city list 
 */
 (function(Ctrip){
 	$.ready(function(){
 		Ctrip.hotCities = [{ display: "北京", data: "|北京|1" }, { display: "上海", data: "|上海|2" }, { display: "广州", data: "|广州|32" }, { display: "深圳", data: "|深圳|30" },{ display: "西安", data: "|西安|10" }, { display: "南京", data: "|南京|12" },  { display: "成都", data: "|成都|28" },  { display: "武汉", data: "|武汉|477"}];
		Ctrip.staicData = "@Beijing|北京|1|bj|@Xian|西安|10|xa|@Lanzhou|兰州|100|lz|@Jiangshan|江山|1000|js|@Fengcheng|丰城|1003|fc|@Ningguo|宁国|1005|ng|@Xuancheng|宣城|1006|xc|@Ningxiang|宁乡|1011|nx|@Lingbao|灵宝|1023|lb|@Ma'anshan|马鞍山|1024|mas|@Anning|安宁|10254||@Yangling|杨凌|10270|YL|@Luoning|洛宁|10271|LN|@Danba|丹巴|10272|DB|@Juxian|莒县|10273|JX|@Hongya|洪雅|10296|HY|@Changle|昌乐|10297|CL|@Maoxian|茂县|10298|MX|@Huhehaote|呼和浩特|103|hhht|@Weinan|渭南|1030|wn|@Zhongning|中宁|1035|zn|@Haiyang|海阳|1037|hy|@Laiyang|莱阳|1038|ly|@Pingyao|平遥|104|py|@Gaomi|高密|1040|gm|@Jiaozhou|胶州|1043|jz|@Qingzhou|青州|1044|qz|@Tieling|铁岭|1048|tl|@Taiyuan|太原|105|ty|@Huludao|葫芦岛|1050|hld|@Xingcheng|兴城|1051|xc|@Jiangyou|江油|1054|jy|@Wutaishan|五台山|106|wts|@Rruzhou|汝州|1060|rz|@Bazhou|霸州|1068|bz|@Renqiu|任丘|1069|rq|@Suning|肃宁|1070|sn|@Liaocheng|聊城|1071|lc|@Heze|菏泽|1074|hz|@Bozhou|亳州|1078|bz|@Macheng|麻城|1079|mc|@Linzhi|林芝|108|lz|@Manzhouli|满洲里|1083|mzl|@Luohe|漯河|1088|lh|@Leping|乐平|1089|lp|@Kashi|喀什|109|ks|@Dingzhou|定州|1090|dz|@Jincheng|晋城|1092|jc|@Jiaozuo|焦作|1093|jz|@Xuchang|许昌|1094|xc|@Houma|侯马|1095|hm|@Panzhihua|攀枝花|1097|pzh|@Dunhuang|敦煌|11|dh|@Yan'an|延安|110|ya|@Guang'an|广安|1100|ga|@Maoming|茂名|1105|mm|@Rizhao|日照|1106|rz|@Changxing|长兴|1107|cx|@Xianyang|咸阳|111|xy|@Jishou|吉首|1110|js|@Shaoyang|邵阳|1111|sy|@Yulin|玉林|1113|yl|@Baicheng|白城|1116|bc|@Suizhou|随州|1117|sz|@Baoji|宝鸡|112|bj|@Jingmen|荆门|1121|jm|@Yiyang|益阳|1125|yy|@Suihua|绥化|1128|sh|@Wuhai|乌海|1133|wh|@Xingyi|兴义|1139|xy|@Baise|百色|1140|bs|@Jiagedaqi|加格达奇|1143|jgdq|@Meishan|眉山|1148|ms|@Benxi|本溪|1155|bx|@Jinchang|金昌|1158|jc|@Tongchuan|铜川|118|tc|@Huayin|华阴|119|hy|@Nanjing|南京|12|nj|@Yancheng|盐城|1200|yc|@Ninghai|宁海|1201|nh|@Tongli|同里|1205|tl|@Pinghu|平湖|1206|ph|@Cixi|慈溪|1208|cx|@Linhai|临海|1209|lh|@Shengzhou|嵊州|1212|sz|@Wuxue|武穴|1219|wx|@Daocheng|稻城|1222|dc|@Jiangdu|江都|1223|jd|@Yizheng|仪征|1224|yz|@Nandaihe|南戴河|1226|ndh|@Tongren|铜仁|1227|tr|@Puyang|濮阳|1232|py|@Dazhou|达州|1233|dz|@Xining|西宁|124|xn|@Hancheng|韩城|128|hc|@Hanzhong|汉中|129|hz|@Wuxi|无锡|13|wx|@Yingkou|营口|1300|yk|@Songyuan|松原|1303|sy|@Yongji|永济|1315|yj|@Shuozhou|朔州|1317|sz|@Ge'ermu|格尔木|132|gem|@Dongmingxian|东明县|1322|dmx|@Kuitun|奎屯|1325|kt|@Qiandaohu|千岛湖|1332|qdh|@Honghezhou|红河州|1341|hhz|@Wenshan|文山|1342|ws|@Jiexiu|介休|135|jx|@Liyang|溧阳|1358|ly|@Datong|大同|136|dt|@Deqing|德清|1367|dq|@Changzhi|长治|137|cz|@Dezhou|德州|1370|dz|@Suining|遂宁|1371|sn|@Liancheng|连城|1373|lc|@Linfen|临汾|139|lf|@Suzhou|苏州|14|sz|@Yuncheng|运城|140|yc|@Baotou|包头|141|bt|@Conghua|从化|1421|ch|@Qingyuan|清远|1422|qy|@Enping|恩平|1428|ep|@Qufu|曲阜|143|qf|@Shanwei|汕尾|1436|sw|@Jinan|济南|144|jn|@Taishun|泰顺|1443|ts|@Laiwu|莱芜|1452|lw|@Jinzhong|晋中|1453|jz|@Jiyuan|济源|1454|jy|@Antu|安图|1466|at|@Qinhuangdao|秦皇岛|147|qhd|@Suqian|宿迁|1472|sq|@Qiqihaer|齐齐哈尔|149|qqhe|@Xiaogan|孝感|1490|xg|@Yangzhou|扬州|15|yz|@Mudanjiang|牡丹江|150|mdj|@Gaobeidian|高碑店|1501|gbd|@Guigang|贵港|1518|gg|@Chibi|赤壁|1521|cb|@Laiyuan|涞源|1522|ly|@Baiyin|白银|1541|by|@Mohe|漠河|155|mh|@Zhijiang|枝江|1557|zj|@Ziyang|资阳|1560|zy|@Suizhong|绥中|1564|sz|@Jixi|鸡西|157|jx|@Changchun|长春|158|cc|@Jilin|吉林|159|jl|@Neijiang|内江|1597|nj|@Qitaihe|七台河|1599|qth|@Zhenjiang|镇江|16|zj|@Mishan|密山|1609|ms|@Hegang|鹤岗|1611|hg|@Shuangyashan|双鸭山|1617|sys|@Tahe|塔河|1628|th|@Kelamayi|克拉玛依|166|klmy|@Yabuli|亚布力|1664|ybl|@Hailin|海林|1666|hl|@Fangchenggang|防城港|1677|fcg|@Caoxian|曹县|1696|cx|@Hangzhou|杭州|17|hz|@Ruichang|瑞昌|1700|rc|@Liu'an|六安|1705|la|@hailuogou|海螺沟|1706|hlg|@libo|荔波|1708|lb|@Ankang|安康|171|ak|@Akesu|阿克苏|173|aks|@Aletai|阿勒泰|175|alt|@Anqing|安庆|177|aq|@Anshan|鞍山|178|as|@Anshun|安顺|179|as|@Jinjiang|晋江|1803|jj|@laixi|莱西|1804|lx|@Anyang|安阳|181|ay|@tengchong|腾冲|1819|tc|@Bengbu|蚌埠|182|bb|@binzhou|滨州|1820|bz|@xingan|兴安|1822|xa|@Xiangshan|象山|1823|xs|@Jintan|金坛|1839|jt|@Pingxiang|萍乡|1840|px|@Baoding|保定|185|bd|@Yuxi|玉溪|186|yx|@Beidaihe|北戴河|187|bdh|@Xiantao|仙桃|1882|xt|@feicheng|肥城|1884|fc|@Beihai|北海|189|bh|@Laibin|来宾|1892|lb|@Qinzhou|钦州|1899|qz|@Zhoushan|舟山|19|zs|@Mizhixian|米脂县|1937|mzx|@Dingxing|定兴|1980|dx|@Xushui|徐水|1983|xs|@Pingyuanxian|平原县|19953|PYX|@Qingyunxian|庆云县|19954|QYX|@Qingyuan|庆元|19955|QY|@Gaotangxian|高唐县|19956|GTX|@Guide|贵德|19958|GD|@Mengyin|蒙阴|19959|MY|@Wuzhi|武陟 |19960|WZ|@Shanghai|上海|2|sh|@Changde|常德|201|cd|@Chifeng|赤峰|202|cf|@Wuan|武安|2033|wa|@Changsha|长沙|206|cs|@Pingyin|平阴|20756|PY|@Chaoyang|朝阳|211|zy|@Lushan|鲁山|2122|ls|@Changzhou|常州|213|cz|@Chuzhou|滁州|214|cz|@Chaozhou|潮州|215|cz|@Cangzhou|沧州|216|cz|@Chizhou|池州|218|cz|@Shaoxing|绍兴|22|sx|@Dandong|丹东|221|dd|@Dengfeng|登封|222|df|@Dongguan|东莞|223|dg|@Qianan|迁安|2230|qa|@Huangshan|黄山|23|hs|@Daqing|大庆|231|dq|@Shaodong|邵东|2339|sd|@Dongying|东营|236|dy|@Deyang|德阳|237|dy|@Danyang|丹阳|238|dy|@Jiujiang|九江|24|jj|@Meng|蒙自|2431|mz|@Jianshui|建水|2442|js|@Enshi|恩施|245|es|@Fuding|福鼎|246|fd|@Xiamen|厦门|25|xm|@Foshan|佛山|251|fs|@Fushun|抚顺|252|fs|@Fuxin|阜新|254|fx|@Delingha|德令哈|2542|dlh|@Bole|博乐|2548|bl|@Luntai|轮台|2549|lt|@Daying|大英|2552|dy|@Fuyang|富阳|256|fy|@Fuyang|阜阳|257|fy|@Fuzhou|福州|258|fz|@Wuyishan|武夷山|26|wys|@Mian|绵竹|2625|mz|@Guangyuan|广元|267|gy|@Ganzhou|赣州|268|gz|@Zhangjiajie|张家界|27|zjj|@Huaibei|淮北|272|hb|@Handan|邯郸|275|hd|@Hefei|合肥|278|hf|@Chengdu|成都|28|cd|@Heihe|黑河|281|hh|@Huaihua|怀化|282|hh|@Hami|哈密|285|hm|@Huainan|淮南|287|hn|@Huashan|华山|288|hs|@Wenxi|闻喜|2886|wx|@Hengshui|衡水|290|hs|@Huangshi|黄石|292|hs|@Hetian|和田|294|ht|@Shangzhi|尚志|2966|sz|@Hengyang|衡阳|297|hy|@Huizhou|惠州|299|hz|@Tianjin|天津|3|tj|@Shenzhen|深圳|30|sz|@Jingdezhen|景德镇|305|jdz|@Meizhou|梅州|3053|mz|@Longquan|龙泉|3055|lq|@Jinggangshan|井冈山|307|jgs|@Jinhua|金华|308|jh|@Zhuhai|珠海|31|zh|@Penglai|蓬莱|310|pl|@Jiangmen|江门|316|jm|@Jiamusi|佳木斯|317|jms|@Jining|济宁|318|jn|@Guangzhou|广州|32|gz|@Guyuan|固原|321|gy|@zhoukou|周口|3221|zk|@pingdingshan|平顶山|3222|pds|@bijie|毕节|3225|bj|@Jurong|句容|3230|jr|@dongtai|东台|3233|dt|@Jiangyin|江阴|325|jy|@Jiayuguan|嘉峪关|326|jyg|@Jinzhou|锦州|327|jz|@langzhong|阆中|3275|lz|@huashuiwan|花水湾|3276|hsw|@yaan|雅安|3277|ya|@Jingzhou|荆州|328|jz|@Kuche|库车|329|kc|@Guilin|桂林|33|gl|@Kuerle|库尔勒|330|kel|@hengdian|横店|3309|hd|@Kaifeng|开封|331|kf|@kanasi|喀纳斯|3326|kns|@Kaili|凯里|333|kl|@Kaiping|开平|335|kp|@Kunming|昆明|34|km|@Langfang|廊坊|340|lf|@Longhai|龙海|341|lh|@Lushan|庐山|344|ls|@Leshan|乐山|345|ls|@Lishui|丽水|346|ls|@Longyan|龙岩|348|ly|@Xishuangbanna|西双版纳|35|xsbn|@Luoyang|洛阳|350|ly|@Liaoyang|辽阳|351|ly|@Liaoyuan|辽源|352|ly|@Lianyungang|连云港|353|lyg|@Liuzhou|柳州|354|lz|@Luzhou|泸州|355|lz|@Dali|大理|36|dl|@Dehong|德宏|365|dh|@Lijiang|丽江|37|lj|@Mianyang|绵阳|370|my|@Nan'an|南安|374|na|@Ningbo|宁波|375|nb|@Nanchang|南昌|376|nc|@Nanchong|南充|377|nc|@Ningde|宁德|378|nd|@Guiyang|贵阳|38|gy|@Nanning|南宁|380|nn|@Hsinchu|新竹|3845|xz|@Tainan|台南|3847|tn|@Taitung|台东|3848|td|@Taichung|台中|3849|tz|@Nanyang|南阳|385|ny|@shouguang|寿光|3863|sg|@Panjin|盘锦|387|pj|@Pingliang|平凉|388|pl|@siyang|泗阳|3881|sy|@Fuzhou|抚州|3884|fz|@huang gang|黄冈|3885|hg|@baishan|白山|3886|bs|@Bayannaoer|巴彦淖尔|3887|byne|@Puning|普宁|389|pn|@Wulumuqi|乌鲁木齐|39|wlmq|@jimo|即墨|3906|jm|@wendeng|文登|3908|wd|@jiaonan|胶南|3909|jn|@yiyuan|沂源|3913|yy|@daye|大冶|3914|dy|@Laizhou|莱州|3915|lz|@Fuqing|福清|3917|fq|@Tianmen|天门|3920|tm|@Chuxiong|楚雄|3921|cx|@Hai'an|海安|3923|ha|@yuhuan|玉环|3925|yh|@jingjiang|靖江|3926|jj|@dexing|德兴|3927|dx|@deqin|德钦|3928|dq|@pizhou|邳州|3929|pz|@lianzhou|连州|3931|lz|@yunfu|云浮|3933|yf|@yingcheng|应城|3935|yc|@yangzhong|扬中|3937|yz|@zhongxiang|钟祥|3938|zx|@pingdu|平度|3943|pd|@longkou|龙口|3946|lk|@Pingxiang|凭祥|396|px|@bazhong|巴中|3966|bz|@dongxing|东兴|3967|dx|@guiping|桂平|3968|gp|@hechi|河池|3969|hc|@gaoan|高安|3970|ga|@E'erduosi|鄂尔多斯|3976|eeds|@Taixing|泰兴|3980|tx|@jiyang|济阳|3989|jy|@Puer|普洱|3996|pe|@Chongqing|重庆|4|cq|@Tulufan|吐鲁番|40|tlf|@xiajin|夏津|4013|xj|@YongCheng|永城|4020|yc|@jiangyan|姜堰|4026|jy|@dafeng|大丰|4029|df|@Qingyang|庆阳|404|qy|@Quanzhou|泉州|406|qz|@Quzhou|衢州|407|qz|@Ruian|瑞安|408|ra|@Lasa|拉萨|41|ls|@Shangrao|上饶|411|sr|@Ruili|瑞丽|412|rl|@gaoyou|高邮|4125|gy|@Kangding|康定|4130|kd|@yangchengxian|阳城县|4131|ycx|@xinmi|新密|4136|xm|@hunchun|珲春|4137|hc|@rugao|如皋|4139|rg|@BOXING|博兴|4141|bx|@ZHUCHENG|诸城|4144|zc|@hezhou|贺州|4146|hz|@qianjiang|潜江|4154|qj|@boao|博鳌|4159|ba|@Liuyang|浏阳|4185|ly|@Haikou|海口|42|hk|@Suifenhe|绥芬河|421|sfh|@shizuishan|石嘴山|4216|szs|@Shaoguan|韶关|422|sg|@zhaoyuan|招远|4251|zy|@Hulunbeier|呼伦贝尔|4255|hlbe|@Shijiazhuang|石家庄|428|sjz|@Sanya|三亚|43|sy|@Sanmenxia|三门峡|436|smx|@Sanming|三明|437|sm|@Shannan|山南|439|sn|@Wenchang|文昌|44|wc|@Siping|四平|440|sp|@Shangqiu|商丘|441|sq|@Sihui|泗水|443|ss|@Shishi|石狮|444|ss|@Shaoshan|韶山|446|ss|@Shantou|汕头|447|st|@Shaowu|邵武|448|sw|@Wanning|万宁|45|wn|@Shenyang|沈阳|451|sy|@Shiyan|十堰|452|sy|@Tai'an|泰安|454|ta|@Tonghua|通化|456|th|@Tongliao|通辽|458|tl|@Tongling|铜陵|459|tl|@Wuzhishan|五指山|46|wzs|@Tonglu|桐庐|460|tl|@Tianshui|天水|464|ts|@Tangshan|唐山|468|ts|@Tiantai|天台|470|tt|@Wudangshan|武当山|474|wds|@Weifang|潍坊|475|wf|@Wuhan|武汉|477|wh|@Wuhu|芜湖|478|wh|@Weihai|威海|479|wh|@Dongfang|东方|48|df|@Wujiang|吴江|481|wj|@Yong'an|永安|485|ya|@Wuyuan|婺源|489|wy|@Wenzhou|温州|491|wz|@Wuzhou|梧州|492|wz|@Xichang|西昌|494|xc|@Xiangyang|襄阳|496|xy|@Xiahe|夏河|497|xh|@Haerbin|哈尔滨|5|heb|@Ding'an|定安|50|da|@Xilinhaote|锡林浩特|500|xlht|@Xinxiang|新乡|507|xx|@Xinyang|信阳|510|xy|@Xuzhou|徐州|512|xz|@Yibin|宜宾|514|yb|@Yichang|宜昌|515|yc|@CHIAYI|嘉义|5152|jy|@Yichun|伊春|517|yc|@Yichun|宜春|518|yc|@Qionghai|琼海|52|qh|@Suzhou|宿州|521|sz|@Yanji|延吉|523|yj|@Yulin|榆林|527|yl|@Yining|伊宁|529|yn|@Yantai|烟台|533|yt|@Yingtan|鹰潭|534|yt|@Yiwu|义乌|536|yw|@Yixing|宜兴|537|yx|@Yueyang|岳阳|539|yy|@Baoting|保亭|54|bt|@Yuyao|余姚|540|yy|@Yanzhou|兖州|541|yz|@Zibo|淄博|542|zb|@Zigong|自贡|544|zg|@Zunhua|遵化|545|zh|@Zhanjiang|湛江|547|zj|@Zhuji|诸暨|548|zj|@Lingshui|陵水|55|ls|@Zhangjiakou|张家口|550|zjk|@Zhumadian|驻马店|551|zmd|@Zhaoqing|肇庆|552|zq|@Zhongshan|中山|553|zs|@Zhaotong|昭通|555|zt|@Zhongwei|中卫|556|zw|@Zunyi|遵义|558|zy|@PINGTUNG|屏东|5589|pd|@Zhengzhou|郑州|559|zz|@Zhangzhou|漳州|560|zz|@Zhouzhuang|周庄|561|zz|@Chengde|承德|562|cd|@Linyi|临沂|569|ly|@Danzhou|儋州|57|dz|@Jiaxing|嘉兴|571|jx|@Changdu|昌都|575|cd|@Huai'an|淮安|577|ha|@Taizhou|台州|578|tz|@Taizhou|泰州|579|tz|@Hong Kong|香港|58|xg|HongKong|XiangGang@Tongxiang|桐乡|580|tx|@Haiyan|海盐|582|hy|@Jiuhuashan|九华山|583|jhs|@Chaohu|巢湖|589|ch|@Macau|澳门|59|am|aoMen@Shangyu|上虞|595|sy|@Jiashan|嘉善|596|js|@Lanxi|兰溪|597|lx|@Xiangtan|湘潭|598|xt|@Dalian|大连|6|dl|@Zhuzhou|株洲|601|zz|@Xinyu|新余|603|xy|@Liupanshui|六盘水|605|lps|@Chenzhou|郴州|612|cz|@Zaozhuang|枣庄|614|zz|@Taipei|台北|617|tb|taibei@Wenling|温岭|619|wl|@Yandangshan|雁荡山|620|yds|@Zhangjiagang|张家港|621|zjg|@Jinyun|缙云|652|jy|@Taicang|太仓|654|tc|@Shennongjia|神农架|657|snj|@Jiande|建德|658|jd|@Anji|安吉|659|aj|@Xianggelila|香格里拉|660|xgll|@Jiuquan|酒泉|662|jq|@Zhangye|张掖|663|zy|@Wuwei|武威|664|ww|@Putian|莆田|667|pt|@Yangjiang|阳江|692|yj|@Heyuan|河源|693|hy|@HUALIEN|花莲|6954|hl|@Xuyi|盱眙|696|xy|@Qidong|启东|697|qd|@Qingdao|青岛|7|qd|@Kaohsiung|高雄|720|gx|@KINMEN|金门|7203|jm|@taishan|台山|729|ts|@Yueqing|乐清|732|lq|@Guanghan|广汉|750|gh|@wulanchabu|乌兰察布|7518|wlcb|@zoucheng|邹城|7519|zc|@Longsheng|龙胜|7521|ls|@nantou|南投|7524|nt|@Beichuanxian|北川县|7525|bcx|@Tanghai|唐海|7530|th|@Daxinxian|大新县|7531|dxx|@pingyang|平阳|7533|py|@changji|昌吉|7534|cj|@pingyi|平邑|7536|py|@liangshanzhou|凉山州|7537|lsz|@Maerkang|马尔康|7540|mek|@ziyuan|资源|7541|zy|@chishui|赤水|7544|cs|@Linzhou|林州|7545|lz|@Alashan|阿拉善|7548|als|@Luding|泸定|7549|ld|@Dongyang|东阳|755|dy|@Shangluo|商洛|7551|sl|@Pingdingxian|平定县|7552|pdx|@qionglai|邛崃|7553|ql|@rushan|乳山|7554|rs|@Rudong|如东|7557|rd|@sanmen|三门|7558|sm|@Haimen|海门|7559|hm|@Fopingxian|佛坪县|7568|fpx|@Taoyuan(TW)|桃园|7570|tyx|@Panan|磐安|7571|pa|@shehong|射洪|7575|sh|@Tianchang|天长|7577|tc|@Xinghua|兴化|7578|xh|@Donggang|东港|7579|dg|@kaihua|开化|7586|kh|@wuzhong|吴忠|7587|wz|@Changshan|常山|7590|cs|@Zhangqiu|章丘|7593|zq|@Yuanyang|元阳|7594|yy|@tianquan|天全|7599|tq|@Zhuozhou|涿州|7605|zz|@Lipu|荔浦|7607|lp|@Yilan|宜兰|7614|yl|@Honghu|洪湖|7618|hh|@Renhuai|仁怀|7619|rh|@xilingxueshan|西岭雪山|7622|xlxs|@Guangrao|广饶|7625|gr|@Botou|泊头|7629|bt|@yishui|沂水|7630|ys|@Lvliang|吕梁|7631|ll|@Luanchuan|栾川|7637|lc|@Eerguna|额尔古纳|7638|eegn|@Huanghua|黄骅|7644|hh|@Changge|长葛|7650|cg|@Ningcheng|宁城|7651|nc|@Xinbeishi|新北市|7662|tbx|@Suichang|遂昌|7665|sc|@Cangnan|苍南|7666|cn|@Luotianxian|罗田县|7667|ltx|@Qixia|栖霞|7669|qx|@Longhushan|龙虎山|7670|lhs|@Fengcheng|凤城|7671|fc|@Yunchengxian|郓城县|7673|ycx|@Changdao|长岛|7674|cd|@yinanxian|沂南县|7675|ynx|@Jingning|景宁|7679|jn|@Xuexiang|雪乡|7681|xx|@Luxi|泸西|7682|lx|@Danjiangkou|丹江口|7685|djk|@Gaocheng|藁城|7687|gc|@Beizhen|北镇|7698|bz|@Wulianxian|五莲县|7700|wlx|@Wuyang|舞阳|7703|wy|@Longnan|陇南|7707|ln|@Guangshan|光山|7710|gs|@Pingluoxian|平罗县|7712|plx|@Huangzhongxian|湟中县|7713|hzx|@JuNanXian|莒南县|7714|jnx|@Dayixian|大邑县|7716|dyx|@Wugongshan|武功山|7724|wgs|@Helan|贺兰|7727|hl|@Dongping|东平县|7728|dpx|@Yanshan|盐山|7733|ys|@Anqiu|安丘|7736|aq|@Jianyang|简阳|7744|jy|@Jinxiang|金乡|7745|jx|@Yijinhuoluoqi|伊金霍洛旗|7748|yjhlq|@Dalateqi|达拉特旗|7749|dltq|@Zouping|邹平|7758|zp|@Hejian|河间|7759|hj|@Yuzhou|禹州|7766|yz|@Xintai|新泰|7771|xt|@Lichuan|利川|7779|lc|@Jimunai|吉木乃|7782|jmn|@Yunhe|云和|7789|yh|@Etuokeqi|鄂托克旗|7793|etkq|@Baigou|白沟|7799|bg|@Huangnanzangzuzizhizhou|黄南藏族自治州|7802|hnzzzzz|@HongJiangShi|洪江市|7803|hjs|@Penghu|澎湖|7805|ph|@Ningyangxian|宁阳县|7806|nyx|@Haibei|海北|7807|hb|@Mazu|马祖|7808|mz|@Miaoli|苗栗|7809|ml|@Jilong|基隆|7810|jl|@Zhanghua|彰化|7811|zh|@Fakuxian|法库县|7823|fkx|@Chengxian|成县|7829|cx|@Hongyuanxian|红原县|7835|hyx|@Wencheng|文成|7836|wc|@Qihexian|齐河县|7839|qhx|@Dongtou|洞头|7841||@Chipingxian|茌平县|7842||@Eryuan|洱源|7843||@Nantong|南通|82|nt|@Kunshan|昆山|83|ks|@Rongcheng|荣成|833|rc|@Haining|海宁|84|hn|@Yongjia|永嘉|85|yj|@Huzhou|湖州|86|hz|@Fenghuang|凤凰|866|fh|@Xianju|仙居|868|xj|@Fenghua|奉化|87|fh|@Sanqingshan|三清山|870|sqs|@Yangshuo|阳朔|871|ys|@Xinchang|新昌|872|xc|@Xinyi|新沂|895|xy|@Linan|临安|90|la|@Yangquan|阳泉|907|yq|@Tengzhou|滕州|909|tz|@Jiuzhaigou|九寨沟|91|jzg|@Xiangxiang|湘乡|917|xx|@Loudi|娄底|918|ld|@Rikaze|日喀则|92|rkz|@Lengshuijiang|冷水江|920|lsj|@Pujiang|浦江|929|pj|@Jian|吉安|933|ja|@Xianning|咸宁|937|xn|@Dujiangyan|都江堰|94|djy|@Leiyang|耒阳|940|ly|@Yongxiu|永修|943|YX|@Tongcheng|桐城|944|tc|@Tianzhushan|天柱山|945|tzs|@Xingtai|邢台|947|xt|@Emeishan|峨眉山|95|ems|@Hebi|鹤壁|951|hb|@Jieyang|揭阳|956|jy|@Yanshi|偃师|957|ys|@Gongyi|巩义|958|gy|@Wuyi|武义|959|wy|@Changshu|常熟|96|cs|@Yongkang|永康|960|yk|@Qingtian|青田|961|qt|@Haicheng|海城|963|hc|@Wafang|瓦房店|966|wfd|@Dashiqiao|大石桥|967|dsq|@Xingyang|荥阳|969|yy|@Ali|阿里|97|al|@Yongzhou|永州|970|yz|@Longyou|龙游|973|ly|@Duyun|都匀|975|dy|@Liling|醴陵|981|ll|@Qujing|曲靖|985|qj|@Zhenyuan|镇远|986|zy|@Yinchuan|银川|99|yc|@Ezhou|鄂州|992|ez|@Luguhu|泸沽湖景区(丽江)|D105_37||@tianmushan|天目山景区(临安)|D1435_90||@tianmuhu|天目湖景区(溧阳)|D1437_1358||@Xitang|西塘景区(嘉善)|D15_596||@Putuoshan|普陀山景区(舟山)|D16_19||@fuxianhu|抚仙湖景区(玉溪)|D2080_186||@Changbaishan|长白山景区(安图)|D268_1466||@Changbaishan|长白山景区(白山)|D268_3886||@wuzhen|乌镇景区(桐乡)|D508_580||@Nanxun|南浔景区(湖州)|D80_86||@Moganshan|莫干山景区(德清)|D87_1367||@";
	 	Ctrip.index = function(one,list){
			var index = 0;
			for(var i=0;i<list.length;i++){
				if(list[i] == one){
					index = i;
				}
			}
			return index;
		}		
		//append hotcities
		var tempHtml = [];
		for(var i =0; i<Ctrip.hotCities.length; i++){
			var item = '<a class="cityname" href="#" rel="'+Ctrip.hotCities[i].data.substr(1)+'">'+Ctrip.hotCities[i].display+'</a>';
			tempHtml.push(item);
		}
		$('#hot_city_list').html(tempHtml.join(' '));
	 	Ctrip.citylist = (function(){
	 		var options = [{name:"ABCD",value:"ABCD",reg:/^[abcd]/g},
			{name:"EFGHJ",value:"EFGHIJ",reg:/^[efghij]/g},
			{name:"KLMN",value:"KLMN",reg:/^[klmn]/g},
			{name:"PQRSTW",value:"PQRSTW",reg:/^[pqrstw]/g},
			{name:"XYZ",value:"XYZ",reg:/^[xyz]/g}
			];
			Ctrip.finalData = [{name:"ABCD",datalist:[],className:"show"},{name:"EFGHIJ",datalist:[],className:"hide"},
			{name:"KLMN",datalist:[],className:"hide"},{name:"PQRSTW",datalist:[],className:"hide"},{name:"XYZ",datalist:[],className:"hide"}];
			
			//render current list
			var _renderList = function(data,index){
				alphaList = options[index].name.split('');
				var par = document.getElementById('sub_city_list');
				par.innerHTML = '';
				for(var i=0;i<alphaList.length;i++){
					var newEle = document.createElement('div');
					newEle.id = 'flag'+alphaList[i];
					newEle.innerHTML = '<li class="basefix"><strong>'+alphaList[i]+'</strong><p></p></li>';
					par.appendChild(newEle);
				}
				for(var i =0;i<data.length;i++){
					var which = data[i];
					var ele = document.getElementById('flag'+which.flag);
					ele.getElementsByTagName('p')[0].innerHTML += '<a class="cityname" href="#" rel="'+which.cname+'|'+which.id+'">'+which.cname+'</a>';
				}
				Ctrip.finalData[index].cachedHTML = par.innerHTML;
			}
			return{
				init:function(){
					_renderList(Ctrip.finalData[0].datalist,0);
				},
				prepareData:function(){
					var tempArr = Ctrip.staicData.split('@');
					tempArr.pop();
					tempArr.shift();
					for(var i = 0; i<tempArr.length; i++){
						var tempPro = tempArr[i].split('|');
						tempPro.pop();
						var scity = {};
						scity.cname = tempPro[1];
						scity.ename = tempPro[0];
						scity.id = tempPro[2];
						scity.flag = tempPro[3].substr(0,1).toUpperCase();
						for(var j= 0; j<options.length; j++){
							if(options[j].reg.test(tempPro[3])){
								Ctrip.finalData[j].datalist.push(scity);
							}
						}
					}
				},
				renderList : _renderList
			}
			//console.log(tempArr);
	 	})()


	 	//start from here
	 	Ctrip.citylist.prepareData();

	 	Ctrip.citylist.init();

	 	$('.city_box_gray a').bind('click',function(e){
	 		var self = this;
	 		var index = Ctrip.index(self,$('.city_box_gray a'));
	 		if(Ctrip.finalData[index].cachedHTML && Ctrip.finalData[index].cachedHTML != ''){
	 			$('#sub_city_list').html(Ctrip.finalData[index].cachedHTML);
	 		}else{
	 			Ctrip.citylist.renderList(Ctrip.finalData[index].datalist,index);
	 		}
	 		
	 		e.preventDefault();
	 	})
	 	$(document).bind('click',function(e){
	 		var tar;
			if(e.target){
				tar = e.target;
			}else if(e.srcElement){
				tar = e.srcElement;
			}
			if(tar.className === 'cityname'){
				var tempArr = tar.rel.split('|');
		 		var cityid = tempArr[1];
		 		var cityname = tempArr[0];
		 		$('#main_Search_CityName').value(cityname);
		 		$('#main_Search_CityID').value(cityid);
		 		$('#repostSearch').trigger('click');
		 		e.preventDefault();
			}
	 	})

 	})
 	
 })(window.Ctrip = window.Ctrip || {})

