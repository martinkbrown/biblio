-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 03, 2010 at 12:35 PM
-- Server version: 5.1.36
-- PHP Version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `biblio`
--

--
-- Dumping data for table `conference`
--


INSERT IGNORE INTO `conference` (`id`, `name`, `acronym`, `approved`) VALUES
(1, 'Supercomputing', 'SC', 1),
(2, 'ACM Southeast Regional Conference', 'ACMSE', 1),
(3, 'National Conference on Artificial Intelligence', 'AAAI', 1),
(4, 'High Assurance Systems', 'HASE', 1),
(5, 'NATO Advanced Study Institute ', 'NATO ASI', 1),
(6, 'Extreme Programming and Agile Processes in Software Engineering', 'DP', 1),
(7, 'IEEE Symposium on Information Visualization ', 'INFOVIS', 0),
(8, 'IFIP Conference on Human-Computer Interaction ', 'INTERACT', 0),
(9, 'International Service Availability Symposium ', 'ISAS', 0),
(10, 'Security in Communication Networks', 'SCN', 1),
(11, 'International Conference on Intelligent Agents, Web Technologies and Internet Commerce', 'IAWTIC', 1),
(12, 'International Conference on Autonomic Computing ', 'ICAC', 1),
(13, 'Martin''s Conference', 'MC', 1);

--
-- Dumping data for table `conference_meeting`
--

INSERT IGNORE INTO `conference_meeting` (`id`, `name`, `conference_id`, `city`, `state_id`, `country_id`, `publisher_id`, `publisher_website`, `conference_website`, `isbn`, `start_date`, `end_date`, `email`, `approved`) VALUES
(1, 'Supercomputing 2008', 1, 'Austin', 44, 256, 1, 'www.acm.com', 'www.com', '234234234', 1226880000, 1227225600, 'martin@gmail.com', 1),
(2, 'Supercomputing 2009', 1, 'Portland', 38, 256, 1, '', '', '2343333', 1258329600, 1258675200, 'sherene@sheepmail.com', 1),
(3, 'The 47th ACM Southeast Conference', 2, 'Clemson', 41, 256, 1, 'www.acm.org', 'http://www.cs.clemson.edu/acmse09/', '62565656', 1237420800, 1237593600, 'someone@clemson.com', 1),
(4, 'The 48th ACM Southeast Conference', 2, 'Oxford', 25, 256, 1, 'www.com', 'http://www.cs.olemiss.edu/acmse2010/Home.htm', '23482934', 1271289600, 1271462400, 'martin@gmail.com', 1),
(5, 'The 46th ACM Southeast Conference', 2, 'Auburn', 1, 256, 1, 'www.com', 'http://www.eng.auburn.edu/acmse/', '2348324', 1269734400, 1269820800, 'someone@auburn.com', 1),
(6, 'Martin''s Conference', 13, 'Tallahassee', 10, 256, 0, 'martin.com', 'martin.com', '123-2343233-43', 1267574400, 1267747200, 'martin@gmail.com', 0);

--
-- Dumping data for table `publisher`
--

INSERT IGNORE INTO `publisher` (`id`, `name`) VALUES
(1, 'ACM'),
(2, 'Martin'),
(3, 'Springer');

