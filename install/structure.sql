CREATE TABLE IF NOT EXISTS `cat_mod_blackforms_forms` (
  `section_id` int(10) NOT NULL,
  `preset` varchar(50) NOT NULL,
  `config` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `cat_mod_blackforms_presets` (
  `preset_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `preset_name` varchar(50) NOT NULL DEFAULT '0',
  `display_name` varchar(50) DEFAULT NULL,
  `preset_data` text NOT NULL,
  PRIMARY KEY (`preset_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `cat_mod_blackforms_replies` (
  `reply_id` int(11) NOT NULL AUTO_INCREMENT,
  `submission_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL DEFAULT '0',
  `submitted_when` int(11) NOT NULL DEFAULT '0',
  `submitted_by` int(11) NOT NULL DEFAULT '0',
  `data_serialized` longtext,
  PRIMARY KEY (`reply_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `cat_mod_blackforms_settings` (
	`section_id` INT(10) NOT NULL,
	`option_name` VARCHAR(50) NOT NULL,
	`option_value` TINYTEXT NOT NULL,
	UNIQUE INDEX `section_id_option_name` (`section_id`, `option_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `cat_mod_blackforms_submissions` (
  `submission_id` int(11) NOT NULL AUTO_INCREMENT,
  `section_id` int(11) NOT NULL DEFAULT '0',
  `submitted_when` int(11) NOT NULL DEFAULT '0',
  `submitted_by` int(11) NOT NULL DEFAULT '0',
  `data_serialized` longtext,
  PRIMARY KEY (`submission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
