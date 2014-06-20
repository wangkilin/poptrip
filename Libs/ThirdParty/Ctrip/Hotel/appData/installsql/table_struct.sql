--
-- 表的结构 `managermentuser`
--
DROP TABLE IF EXISTS `ctrip_managermentuser`;
CREATE TABLE `ctrip_managermentuser` (
  `ID` int(4) NOT NULL AUTO_INCREMENT,
  `UserName` varchar(50)   NOT NULL,
  `Password` varchar(50)  NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8   AUTO_INCREMENT=1 ;
--
-- 表的结构 `keywords`
--
DROP TABLE IF EXISTS `ctrip_keywords`;
CREATE TABLE `ctrip_keywords` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `PageName` varchar(100)  DEFAULT NULL COMMENT '是哪个页面的关键字',
  `Page` varchar(200)  DEFAULT NULL COMMENT '页面的索引名称',
  `Title` varchar(200)  DEFAULT NULL COMMENT '标题的规则',
  `Keywords` varchar(200)  DEFAULT NULL COMMENT '关键字的规则',
  `Description` varchar(500)  DEFAULT NULL COMMENT '页面描述的规则',
  `Times` date DEFAULT NULL COMMENT '更新的日期',
  `Rule` varchar(500)  DEFAULT NULL COMMENT '规则定义',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8   AUTO_INCREMENT=1 ;
--
-- 表的结构 `siteconfig`
--
DROP TABLE IF EXISTS `ctrip_siteconfig`;
CREATE TABLE `ctrip_siteconfig` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ConfigName` varchar(100)  DEFAULT NULL COMMENT '配置项的名称',
  `ConfigValue` varchar(300)  DEFAULT NULL COMMENT '配置项的值',
  `Type` varchar(10)  DEFAULT NULL COMMENT '值的类型-int,string',
  `ConfigInfo` varchar(500)  DEFAULT NULL COMMENT '配置项的用途描述',
  `ConfigClass` varchar(20)  DEFAULT NULL COMMENT '配置项的大类名称-数据库，系统，联盟信息等',
  `Level` int(11) DEFAULT NULL COMMENT '配置项的是由哪个级别的管理员管理',
  `OrderBy` int(11) DEFAULT NULL COMMENT '排序的顺序号',
  `Times` date DEFAULT NULL COMMENT '更新的时间',
  `State` int(11)  DEFAULT NULL COMMENT '状态-0禁止；1启用中',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8  AUTO_INCREMENT=1 ;