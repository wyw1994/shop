<?php
//day1 brand 表
create table brand(
  id int not null auto_increment COMMENT '主键',
  `name` varchar(50) not null default '' COMMENT '品牌名',
  intro text not null default '' COMMENT '简介',
  logo varchar(255) not null default '' COMMENT 'LOGO图片',
  sort int(11) not NULL  DEFAULT  '0' COMMENT '排序',
  status int(2) not null DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (id)
)engine=innodb DEFAULT charset=utf8;

//day 1 文章分类表
create TABLE article_category(
  id int NOT NULL AUTO_INCREMENT COMMENT '主键',
  name VARCHAR(50) NOT NULL DEFAULT '' COMMENT '分类名',
  intro TEXT not null DEFAULT '' COMMENT '简介',
  sort int(11) not null DEFAULT 0 COMMENT '排序',
  status int(2) NOT NULL DEFAULT  1 COMMENT '状态',
  is_help int(1) NOT NULL DEFAULT 0 COMMENT '是否帮助类书籍',
  PRIMARY KEY (id)
)ENGINE =innodb DEFAULT CHARSET utf8;

//day 1 文章表 article
create TABLE article(
  id int NOT NULL AUTO_INCREMENT COMMENT '主键',
  name VARCHAR(50) NOT NULL DEFAULT '' COMMENT '文章名',
  intro TEXT not null DEFAULT '' COMMENT '简介',
  article_category_id int not null DEFAULT 0 COMMENT '文章分类ID',
  sort int(11) not null DEFAULT 0 COMMENT '排序',
  status int(2) NOT NULL DEFAULT  1 COMMENT '状态',
  creat_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (id),
  KEY (article_category_id)
)ENGINE =innodb DEFAULT CHARSET utf8;

//day 1 文章详情表 article_detail
CREATE table article_detail(
  article_id int not NULL AUTO_INCREMENT COMMENT '文章id',
  content text not null DEFAULT '' COMMENT '文章详细内容',
  PRIMARY KEY (article_id)
)ENGINE =innodb DEFAULT CHARSET utf8;

//管理员模块
DROP TABLE IF EXISTS `shop_admin`;
CREATE TABLE IF NOT EXISTS `shop_admin`(
    `adminid` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
    `adminuser` VARCHAR(32) NOT NULL DEFAULT '' COMMENT '管理员账号',
    `adminpass` CHAR(32) NOT NULL DEFAULT '' COMMENT '管理员密码',
    `adminemail` VARCHAR(50) NOT NULL DEFAULT '' COMMENT '管理员电子邮箱',
    `logintime` INT UNSIGNED NOT NULL DEFAULT '0' COMMENT '登录时间',
    `loginip` BIGINT NOT NULL DEFAULT '0' COMMENT '登录IP',
    `createtime` INT UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
    PRIMARY KEY(`adminid`),
    UNIQUE shop_admin_adminuser_adminpass(`adminuser`, `adminpass`),
    UNIQUE shop_admin_adminuser_adminemail(`adminuser`, `adminemail`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `shop_admin`(adminuser,adminpass,adminemail,createtime) VALUES('admin', md5('123'), '2251586313@qq.com', UNIX_TIMESTAMP());

//
DROP TABLE IF EXISTS `shop_profile`;
CREATE TABLE IF NOT EXISTS `shop_profile`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
    `truename` VARCHAR(32) NOT NULL DEFAULT '' COMMENT '真实姓名',
    `age` TINYINT UNSIGNED NOT NULL DEFAULT '0' COMMENT '年龄',
    `sex` ENUM('0','1','2') NOT NULL DEFAULT '0' COMMENT '性别',
    `birthday` date NOT NULL DEFAULT '2017-01-01' COMMENT '生日',
    `nickname` VARCHAR(32) NOT NULL DEFAULT '' COMMENT '昵称',
    `company` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '公司',
    `userid` BIGINT UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户的ID',
    `createtime` INT UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
    PRIMARY KEY(`id`),
    UNIQUE shop_profile_userid(`userid`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;
//购物车
create table cart(
	id int PRIMARY key auto_increment,
  goods_id int not null,
  amount int not null,
  member_id int not NULL,
  key (`goods_id`),
  key (`member_id`)
)engine INNODB default CHARSET  utf8;
//订单
CREATE  table `order`(
	`id` int unsigned not null auto_increment,
  `member_id` int not null,
  `name` VARCHAR (50) not null default '',
  `province` varchar(20) not null DEFAULT '',
  `city` VARCHAR (20) not null default '',
  `area` VARCHAR (20) not null default '',
  `address` VARCHAR (255) not null default '',
  `tel` CHAR (11) not null,
  `delivery_id` int not null,
  `delivery_name` varchar(100) not null default '',
  `delivery_price` decimal(10,2) default '0.00',
  `payment_id` int,
  `payment_name` varchar(50),
  `total` decimal(10,2) default '0.00',
  `status` tinyint,
  `trade_no` varchar(100),
  `create_time` int not null default '0',
  PRIMARY KEY (`id`),
  key (`member_id`)
)engine=innodb DEFAULT charset utf8;

//订单商品详情表order_goods
create table order_goods(
  `id` int not null auto_increment,
  `order_id` int not null,
  `goods_id` int not null,
  `goods_name` varchar(255) not null default '',
  `logo` varchar(255) not null default '',
  `price` decimal(10,2) not null default '0.00',
  `amount` int not null,
  `total` decimal(10,2) not null default '0.00',
  PRIMARY KEY (`id`),
  KEY (`order_id`),
  KEY (`goods_id`)
)engine=innodb default charset utf8;