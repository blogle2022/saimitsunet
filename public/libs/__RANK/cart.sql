
DROP TABLE IF EXISTS `Cart`;
CREATE TABLE `Cart` (
  `seq` bigint(20) NOT NULL auto_increment,
  `id` varchar(32) NOT NULL default '',
  `base` int(11) default NULL,
  `tr_status` tinyint(4) default '0',
  `tr_name` varchar(255) default NULL,
  `tr_zip` varchar(16) default NULL,
  `tr_adr` varchar(255) default NULL,
  `tr_tel` varchar(16) default NULL,
  `tr_mail` varchar(255) default NULL,
  `rc_name` varchar(255) default NULL,
  `rc_zip` varchar(255) default NULL,
  `rc_adr` varchar(255) default NULL,
  `rc_tel` varchar(255) default NULL,
  `delivery` int(10) unsigned default NULL,
  `payment` varchar(16) default NULL,
  `detectnumber` varchar(16) default NULL,
  `cr_unixtime` int(10) unsigned default NULL,
  `sm_unixtime` int(10) unsigned default NULL,
  PRIMARY KEY  (`seq`),
  UNIQUE KEY `id` (`id`),
  KEY `base` (`base`)
) ENGINE=MyISAM DEFAULT CHARSET=ujis;


DROP TABLE IF EXISTS `cartItem`;
CREATE TABLE `cartItem` (
  `seq` bigint(20) NOT NULL auto_increment,
  `id` varchar(32) NOT NULL default '',
  `base` int(11) default NULL,
  `transaction_id` varchar(32) NOT NULL default '',
  `item_name` varchar(255) default NULL,
  `item_price` int(10) unsigned default NULL,
  `item_count` int(10) unsigned default NULL,
  PRIMARY KEY  (`seq`),
  UNIQUE KEY `id` (`id`),
  KEY `base` (`base`),
  KEY `transaction_id` (`transaction_id`)
) ENGINE=MyISAM DEFAULT CHARSET=ujis;

