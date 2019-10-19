CREATE TABLE IF NOT EXISTS `#__foydalanuvchilar_` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL ,
`created_by` INT(11)  NOT NULL ,
`modified_by` INT(11)  NOT NULL ,
`user_id` INT NOT NULL ,
`mfo_filial` VARCHAR(255)  NOT NULL ,
`departament` VARCHAR(255)  NOT NULL ,
`mintaqaviy_filial` VARCHAR(255)  NOT NULL ,
`ichki_nazorat` VARCHAR(255)  NOT NULL ,
`ichki_audit` VARCHAR(255)  NOT NULL ,
`filial` VARCHAR(255)  NOT NULL ,
`barcha_soha` VARCHAR(255)  NOT NULL ,
`bosh_bank` VARCHAR(255)  NOT NULL ,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT COLLATE=utf8_unicode_ci;

