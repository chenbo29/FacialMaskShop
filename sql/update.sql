#2019-03-08 
#tp_team_activity增加和修改字段
ALTER TABLE `tp_team_activity` ADD `cluster_type` TINYINT(2) DEFAULT 0 COMMENT '团购类型：1小团，2大团，3 阶梯团' AFTER `team_type`;
ALTER TABLE `tp_team_activity` MODIFY `status` TINYINT(1) DEFAULT 0 COMMENT '是否开启拼团：0否，1是';
ALTER TABLE `tp_team_activity` ADD `group_price` int(11) DEFAULT 0 COMMENT '团购价';
ALTER TABLE `tp_team_activity` ADD `group_conditions` TINYINT(1) DEFAULT 0 COMMENT '成团条件：1按拼团数量，2按购买数量';
ALTER TABLE `tp_team_activity` ADD `group_number` int(11) DEFAULT 0 COMMENT '拼团人数：0默认不限制';
ALTER TABLE `tp_team_activity` ADD `purchase_qty` int(11) DEFAULT 0 COMMENT '购买数量：0默认不限制';
ALTER TABLE `tp_team_activity` ADD `max_open_num` int(11) DEFAULT 0 COMMENT '最大开团数：0默认不限制';
ALTER TABLE `tp_team_activity` ADD `is_free` TINYINT(1) DEFAULT 0 COMMENT '是否免单：0否，1是';
ALTER TABLE `tp_team_activity` ADD `return_commission` TINYINT(1) DEFAULT 0 COMMENT '是否返佣：0否，1是';
ALTER TABLE `tp_team_activity` MODIFY `buy_limit` smallint(4) NOT NULL DEFAULT '0' COMMENT '限购数量：0不限制';
ALTER TABLE `tp_team_activity` ADD `activity_rule` TEXT COMMENT '活动规则';
ALTER TABLE `tp_team_activity` ADD `goods_intro` TEXT COMMENT '商品简介';
ALTER TABLE `tp_team_activity` ADD `create_time` datetime NOT NULL COMMENT '创建时间';
ALTER TABLE `tp_team_activity` ADD `update_time` datetime DEFAULT NULL COMMENT '更新时间';
#tp_team_goods_item表增加和修改字段
ALTER TABLE `tp_team_goods_item` ADD `sku` varchar(60) DEFAULT '' COMMENT 'SKU' AFTER `item_id`;
ALTER TABLE `tp_team_goods_item` ADD `goods_price` decimal(10,2) DEFAULT '0.00' COMMENT '商品原价' AFTER `sku`;
ALTER TABLE `tp_team_goods_item` ADD `current_price` decimal(10,2) DEFAULT '0.00' COMMENT '现价' AFTER goods_price;

CREATE TABLE `tp_team_ladder` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `team_id` int(10) DEFAULT '0' COMMENT '拼团活动id',
  `level` int(11) DEFAULT '0' COMMENT '层级',
  `group_number` int(11) DEFAULT 0 COMMENT '拼团人数：0默认不限制',
  `price` decimal(10,2) DEFAULT '0.00' COMMENT '拼团价格',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态：0存在，1已删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=111 DEFAULT CHARSET=utf8 COMMENT='阶梯团表';







