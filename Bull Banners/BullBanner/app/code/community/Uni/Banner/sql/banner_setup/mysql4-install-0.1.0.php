<?php
/**
 * Unicode Systems
 * @category   Uni
 * @package    Uni_Banner
 * @copyright  Copyright (c) 2010-2011 Unicode Systems. (http://www.unicodesystems.in)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
$installer = $this;

$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS {$this->getTable('uni_banner')};
CREATE TABLE {$this->getTable('uni_banner')} (
  `banner_id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `filename` varchar(255) NOT NULL default '',
  `link` varchar(255) NOT NULL default '',
  `banner_content` text NOT NULL default '',
  `status` smallint(6) NOT NULL default '0',
  `sort_order` int(11) NOT NULL default '0',
  `banner_type` TINYINT( 4 ) NOT NULL DEFAULT '0' COMMENT '0=>Image, 1=>HTML',
  `created_time` DATETIME NULL,
  `update_time` DATETIME NULL,
  PRIMARY KEY (`banner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO {$this->getTable('uni_banner')} (`banner_id`, `title`, `filename`, `link`, `banner_content`, `status`, `sort_order`, `banner_type`, `created_time`, `update_time`) VALUES
(1, 'Banner 1', 'custom/banners/File-1300256573.jpg', '', '', 1, 0, 0, '2011-03-16 06:22:53', '2011-03-16 06:22:53'),
(2, 'Banner 2', 'custom/banners/File-1300256584.jpg', '', '', 1, 0, 0, '2011-03-16 06:23:04', '2011-03-16 06:23:04'),
(3, 'Banner 3', 'custom/banners/File-1300256596.jpg', '', '', 1, 0, 0, '2011-03-16 06:23:16', '2011-03-16 06:23:16'),
(4, 'Banner 4', 'custom/banners/File-1300256608.jpg', '', '', 1, 0, 0, '2011-03-16 06:23:28', '2011-03-16 06:23:28');

");
$installer->endSetup();
