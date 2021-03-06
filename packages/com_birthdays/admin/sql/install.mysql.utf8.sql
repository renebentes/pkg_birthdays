-- 1.0.0
CREATE TABLE IF NOT EXISTS`#__birthdays` (
    `id` INT(10) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL DEFAULT '',
    `nickname` VARCHAR(20) NOT NULL DEFAULT '',
    `grade` TINYINT(2) NOT NULL DEFAULT 0,
    `birthdate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `picture` VARCHAR(255) NULL DEFAULT '',
    `alias` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_bin' NOT NULL DEFAULT '',
    `hits` INT(10) UNSIGNED NOT NULL DEFAULT 0,
    `access` INT(10) UNSIGNED NOT NULL DEFAULT 0,
    `ordering` INT NOT NULL DEFAULT 0,
    `published` TINYINT(3) NOT NULL DEFAULT 0,
    `publish_up` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `publish_down` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `created_by` INT(10) UNSIGNED NOT NULL DEFAULT 0,
    `created_by_alias` VARCHAR(255) NOT NULL DEFAULT '',
    `modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `modified_by` INT(10) NOT NULL DEFAULT 0,
    `version` INT(10) UNSIGNED NOT NULL DEFAULT 1,
    `checked_out` INT(10) UNSIGNED NOT NULL DEFAULT 0,
    `checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `language` CHAR(7) NOT NULL DEFAULT '',
    PRIMARY KEY (`id`),
    INDEX `idx_published` (`published`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

-- 1.0.1
ALTER TABLE `#__birthdays` CHANGE COLUMN `birthdate` `birthdate` DATE NOT NULL DEFAULT '0000-00-00';

-- 1.0.2
ALTER TABLE `#__birthdays` CHANGE COLUMN `birthdate` `birthdate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00';

-- 2.0.0
ALTER TABLE `#__birthdays` CHANGE COLUMN `published` `state` TINYINT(3) NOT NULL DEFAULT 0;
