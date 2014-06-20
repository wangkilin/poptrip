ALTER TABLE `city` ADD `area_code` INT( 3 ) NOT NULL AFTER `zip_code` ;

ALTER TABLE `district` ADD `area_code` INT( 3 ) NOT NULL AFTER `zip_code` ;


CREATE TABLE IF NOT EXISTS `dianping_city` (
  `city_id` int(11) NOT NULL AUTO_INCREMENT,
  `sys_city_id` int(8) NOT NULL COMMENT 'link to city->city_id',
  `city_name` varchar(32) NOT NULL,
  `has_retail` int(11) NOT NULL COMMENT '是否有商家',
  `has_groupon` int(11) NOT NULL COMMENT '是否有团购',
  `has_coupon` int(11) NOT NULL COMMENT '是否有优惠券',
  `has_booking` int(11) NOT NULL COMMENT '是否有预订',
  PRIMARY KEY (`city_id`),
  KEY `city_name` (`city_name`),
  KEY `sys_city_id` (`sys_city_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `dianping_district` (
  `district_id` int(11) NOT NULL AUTO_INCREMENT,
  `city_id` int(11) NOT NULL ,
  `district_name` varchar(64) NOT NULL,
  `has_retail` int(11) NOT NULL COMMENT '是否有商家',
  `has_groupon` int(11) NOT NULL COMMENT '是否有团购',
  `has_coupon` int(11) NOT NULL COMMENT '是否有优惠券',
  `has_booking` int(11) NOT NULL COMMENT '是否有预订',
  PRIMARY KEY (`district_id`),
  KEY `district_name` (`district_name`),
  KEY `city_id` (`city_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `dianping_zone` (
  `zone_id` int(11) NOT NULL AUTO_INCREMENT,
  `district_id` int(11) NOT NULL ,
  `zone_name` varchar(64) NOT NULL,
  `has_retail` int(11) NOT NULL COMMENT '是否有商家',
  `has_groupon` int(11) NOT NULL COMMENT '是否有团购',
  `has_coupon` int(11) NOT NULL COMMENT '是否有优惠券',
  `has_booking` int(11) NOT NULL COMMENT '是否有预订',
  PRIMARY KEY (`zone_id`),
  KEY `zone_name` (`zone_name`),
  KEY `district_id` (`district_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `dianping_category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL ,
  `category_name` varchar(64) NOT NULL,
  `has_retail` int(11) NOT NULL COMMENT '是否有商家',
  `has_groupon` int(11) NOT NULL COMMENT '是否有团购',
  `has_coupon` int(11) NOT NULL COMMENT '是否有优惠券',
  `has_booking` int(11) NOT NULL COMMENT '是否有预订',
  PRIMARY KEY (`category_id`),
  KEY `category_name` (`category_name`),
  KEY `category_id` (`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
