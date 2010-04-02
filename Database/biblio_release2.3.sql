ALTER TABLE `author_conference_paper`  ADD COLUMN `id` INT(11) NOT NULL AUTO_INCREMENT FIRST,  ADD PRIMARY KEY (`id`),  ADD UNIQUE INDEX `id` (`id`);
ALTER TABLE `author_journal_paper`  ADD COLUMN `id` INT(11) NOT NULL AUTO_INCREMENT FIRST,  ADD PRIMARY KEY (`id`),  ADD UNIQUE INDEX `id` (`id`);
ALTER TABLE `conference_paper`  ADD COLUMN `conference_meeting_id` INT(11) NOT NULL AFTER `id`,  CHANGE COLUMN `conference_session_id` `conference_session_id` INT(11) NULL AFTER `conference_meeting_id`;
ALTER TABLE `conference_session` CHANGE `name` `name` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `author_conference_paper` CHANGE `author_id` `author_id` INT( 11 ) NOT NULL ,
CHANGE `conference_paper_id` `conference_paper_id` INT( 11 ) NOT NULL ;
