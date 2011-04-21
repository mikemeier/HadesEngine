SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

CREATE TABLE IF NOT EXISTS `he1_lang_packs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `isocode` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `direction` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `locale` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_format` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `date_format_long` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

INSERT INTO `he1_lang_packs` (`id`, `isocode`, `name`, `direction`, `locale`, `date_format`, `date_format_long`) VALUES
(1, 'en', 'English', 'ltr', 'en_US.UTF-8,en_US,eng,English', 'Y-m-d', 'D, d M Y');

CREATE TABLE IF NOT EXISTS `he1_lang_strings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pack` int(10) unsigned NOT NULL,
  `string` text COLLATE utf8_unicode_ci NOT NULL,
  `translated` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pack` (`pack`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
