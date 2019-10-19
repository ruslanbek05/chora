CREATE TABLE IF NOT EXISTS `#__chora` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL ,
`created_by` INT(11)  NOT NULL ,
`modified_by` INT(11)  NOT NULL ,
`mazmuni` TEXT NOT NULL ,
`vazifa` TEXT NOT NULL ,
`muddati` DATETIME NOT NULL ,
`masul` INT NOT NULL ,
`nazorat` VARCHAR(255)  NOT NULL ,
`tugrilandi_filial` VARCHAR(255)  NOT NULL ,
`tugrilandi_bosh_bank` VARCHAR(255)  NOT NULL ,
`tugrilandi_ichki_nazorat` VARCHAR(255)  NOT NULL ,
`tugrilandi_ichki_audit` VARCHAR(255)  NOT NULL ,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT COLLATE=utf8_unicode_ci;

