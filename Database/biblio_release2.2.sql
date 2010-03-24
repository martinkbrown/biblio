CREATE TABLE `biblio`.`author` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`firstname` VARCHAR( 255 ) NOT NULL
) ENGINE = InnoDB;

ALTER TABLE `author` ADD `initial` CHAR( 1 ) NULL AFTER `firstname` ,
ADD `lastname` VARCHAR( 255 ) NOT NULL AFTER `initial` 

CREATE TABLE `biblio`.`conference_paper` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`conference_session_id` INT( 11 ) NOT NULL ,
`title` VARCHAR( 255 ) NOT NULL ,
`start_page` INT( 11 ) NOT NULL ,
`end_page` INT( 11 ) NOT NULL
) ENGINE = InnoDB;


CREATE TABLE `biblio`.`conference_session` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`conference_meeting_id` INT( 11 ) NOT NULL ,
`name` INT( 255 ) NOT NULL
) ENGINE = InnoDB;

CREATE TABLE `biblio`.`author_conference_paper` (
`author_id` INT( 255 ) NOT NULL ,
`conference_paper_id` INT( 255 ) NOT NULL
) ENGINE = InnoDB;

CREATE TABLE `biblio`.`journal_paper` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`journal_id` INT( 11 ) NOT NULL ,
`title` VARCHAR( 255 ) NOT NULL ,
`start_page` INT( 11 ) NOT NULL ,
`end_page` INT( 11 ) NOT NULL ,
`volume` INT( 11 ) NOT NULL ,
`number` INT( 11 ) NULL
) ENGINE = InnoDB;

CREATE TABLE `biblio`.`journal_volume_number` (
`number` INT( 11 ) NOT NULL ,
`volume` INT( 11 ) NOT NULL ,
`journal_id` INT( 11 ) NOT NULL ,
`date` INT( 11 ) NOT NULL ,
PRIMARY KEY ( `number` )
) ENGINE = InnoDB;

CREATE TABLE `biblio`.`author_journal_paper` (
`author_id` INT( 11 ) NOT NULL ,
`journal_paper_id` INT( 11 ) NOT NULL
) ENGINE = InnoDB;

ALTER TABLE `journal_paper` ADD `create_date` INT( 11 ) NOT NULL AFTER `number` ,
ADD `email` VARCHAR( 255 ) NOT NULL AFTER `create_date` ,
ADD `approved` BOOL NOT NULL AFTER `email` 

ALTER TABLE `author_conference_paper` ADD `main_author` BOOL NOT NULL AFTER `conference_paper_id` 

ALTER TABLE `author_journal_paper` ADD `main_author` BOOL NOT NULL 

ALTER TABLE `conference_paper` ADD `create_date` INT( 11 ) NOT NULL ,
ADD `email` VARCHAR( 255 ) NOT NULL ,
ADD `approved` BOOL NOT NULL 

ALTER TABLE `journal`  ADD UNIQUE INDEX `name` (`name`);

ALTER TABLE `conference_meeting`  ADD UNIQUE INDEX `name` (`name`);