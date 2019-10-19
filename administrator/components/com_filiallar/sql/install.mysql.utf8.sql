CREATE TABLE IF NOT EXISTS `#__filiallar` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL ,
`created_by` INT(11)  NOT NULL ,
`modified_by` INT(11)  NOT NULL ,
`mfo` VARCHAR(5)  NOT NULL ,
`mintaqa_mfo` VARCHAR(5)  NOT NULL ,
`nomi` VARCHAR(100)  NOT NULL ,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT COLLATE=utf8_unicode_ci;

