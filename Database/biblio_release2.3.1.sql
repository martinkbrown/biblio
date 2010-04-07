DROP TABLE journal_volume_number;

CREATE TABLE `biblio`.`journal_volume_number` (
`number` INT( 11 ) NOT NULL ,
`journal_id` INT( 11 ) NOT NULL ,
`volume` INT( 11 ) NOT NULL ,
`create_date` INT( 11 ) NOT NULL
) ENGINE = InnoDB;

ALTER TABLE `journal_volume_number`  ADD PRIMARY KEY (`number`, `journal_id`, `volume`);

ALTER TABLE `journal_volume_number`  CHANGE COLUMN `create_date` `date` INT(11) NOT NULL AFTER `volume`;

DROP TABLE `journal_volume_number` ;

CREATE TABLE `biblio`.`journal_volume_number` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`number` INT( 11 ) NOT NULL ,
`journal_id` INT( 11 ) NOT NULL ,
`volume` INT( 11 ) NOT NULL
) ENGINE = InnoDB;

ALTER TABLE `journal_volume_number`  ADD UNIQUE INDEX `number_journal_id_volume` (`number`, `journal_id`, `volume`);

ALTER TABLE `journal_volume_number` ADD `date` INT( 11 ) NOT NULL AFTER `volume` ;

ALTER TABLE `journal_volume_number` CHANGE `journal_id` `journal_paper_id` INT( 11 ) NOT NULL ;

ALTER TABLE `journal_volume_number` CHANGE `journal_paper_id` `journal_id` INT( 11 ) NOT NULL ;