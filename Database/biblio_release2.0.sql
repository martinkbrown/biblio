                                                                     
                                                                     
                                                                     
                                             
ALTER TABLE `conference_meeting`  ADD COLUMN `create_date` INT(11) NOT NULL AFTER `email`;# 6 row(s) affected.


--
-- Table structure for table `journal`
--

CREATE TABLE IF NOT EXISTS `journal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `acronym` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `create_date` int(11) NOT NULL,
  `approved` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;# MySQL returned an empty result set (i.e. zero rows).


--# MySQL returned an empty result set (i.e. zero rows).
