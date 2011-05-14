SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

CREATE TABLE IF NOT EXISTS `he1_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(40) NOT NULL,
  `group` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

INSERT INTO `he1_users` (`id`, `username`, `password`, `email`, `group`) VALUES
(1, 'admin', SHA1('admin'), 'example@example.com', 1);

CREATE TABLE IF NOT EXISTS `he1_usergroups` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

INSERT INTO `he1_usergroups` (`id`, `title`) VALUES
(1, 'Administrator');
INSERT INTO `he1_usergroups` (`id`, `title`) VALUES
(2, 'User');
INSERT INTO `he1_usergroups` (`id`, `title`) VALUES
(3, 'Guest');

CREATE TABLE IF NOT EXISTS `he1_sessions` (
  `id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user` int(10) NOT NULL,
  `expire` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user` (`user`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `he1_lang_packs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `isocode` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `direction` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `locale` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_format` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `date_format_long` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

INSERT INTO `he1_lang_packs` (`id`, `isocode`, `name`, `direction`, `locale`, `date_format`, `date_format_long`) VALUES
(1, 'en', 'English', 'ltr', 'en_US.UTF-8,en_US,eng,English', 'Y-m-d', 'D, d M Y');

CREATE TABLE IF NOT EXISTS `he1_lang_strings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pack` int(10) unsigned NOT NULL,
  `string` text COLLATE utf8_unicode_ci NOT NULL,
  `translated` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pack` (`pack`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `he1_config_groups` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `parent` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parent` (`parent`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `he1_config_vars` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `key` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  `type` enum('boolean','number','text','select','longtext','html') NOT NULL,
  `select_func` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `group` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `group` (`group`)
) DEFAULT CHARSET=utf8;
