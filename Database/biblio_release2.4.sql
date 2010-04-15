ALTER TABLE `conference`  DROP INDEX `name`;

ALTER TABLE `conference_paper`  ADD UNIQUE INDEX `title` (`title`);