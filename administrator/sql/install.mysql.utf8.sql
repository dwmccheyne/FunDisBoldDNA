CREATE TABLE IF NOT EXISTS `#__dnabold_` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL DEFAULT 1,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT "0000-00-00 00:00:00",
`created_by` INT(11)  NOT NULL ,
`modified_by` INT(11)  NOT NULL ,
`record_id` VARCHAR(255)  NOT NULL ,
`scientific_name` VARCHAR(255)  NOT NULL ,
`bold_id` VARCHAR(255)  NOT NULL ,
`group_id` INT NOT NULL ,
`step` INT NOT NULL DEFAULT 1,
`genbank_id` VARCHAR(255)  NOT NULL ,
`mycomap_id` VARCHAR(255)  NOT NULL ,
`fungarium_url` VARCHAR(255)  NOT NULL ,
`mycoportal_id` VARCHAR(255)  NOT NULL ,
`cover_image` VARCHAR(255)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8mb4_unicode_ci;


INSERT INTO `#__content_types` (`type_title`, `type_alias`, `table`, `content_history_options`)
SELECT * FROM ( SELECT 'dnabold','com_dnabold.dnabold','{"special":{"dbtable":"#__dnabold_","key":"id","type":"Dnabold","prefix":"DnaboldTable"}}', '{"formFile":"administrator\/components\/com_dnabold\/models\/forms\/dnabold.xml", "hideFields":["checked_out","checked_out_time","params","language"], "ignoreChanges":["modified_by", "modified", "checked_out", "checked_out_time"], "convertToInt":["publish_up", "publish_down"], "displayLookup":[{"sourceColumn":"catid","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"group_id","targetTable":"#__usergroups","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"created_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"}]}') AS tmp
WHERE NOT EXISTS (
	SELECT type_alias FROM `#__content_types` WHERE (`type_alias` = 'com_dnabold.dnabold')
) LIMIT 1;
INSERT INTO `#__dnabold_` (`id`, `ordering`, `state`, `checked_out`, `checked_out_time`, `created_by`, `modified_by`, `record_id`, `scientific_name`, `bold_id`, `group_id`, `step`, `genbank_id`, `mycomap_id`, `fungarium_url`, `mycoportal_id`, `cover_image`) VALUES
(1, 1, 1, 0, '0000-00-00 00:00:00', 732, 732, 'MO363747', 'Amanita velosa', 'NAMPA002-19', 1001, 1, 'MT551941', '', '', '', 'images/MO3637471575912162.jpg'),
(2, 2, 1, 0, '2020-07-15 21:53:18', 732, 732, ' iNat35098933', 'Leucocoprinus fragilissimus', 'NAMPA042-19', 1002, 1, 'MT551946', '', '', '', 'images/iNat350989331575912686.jpg');
