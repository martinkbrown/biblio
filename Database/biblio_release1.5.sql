ALTER TABLE `conference_meeting` ADD `name` VARCHAR( 255 ) NOT NULL AFTER `id` ;
ALTER TABLE `conference_meeting` ADD `email` VARCHAR( 255 ) NOT NULL AFTER `end_date`;