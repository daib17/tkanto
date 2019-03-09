--
-- Ensure UTF8 as character encoding within connection.
--
SET NAMES utf8;
SET time_zone = "+01:00";

--
-- Table structure for table `calendar`
--
DROP TABLE IF EXISTS `calendar`;
CREATE TABLE `calendar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student` varchar(12) COLLATE latin1_spanish_ci NOT NULL,
  `date` date NOT NULL,
  `time` int(11) NOT NULL,
  `duration` int(11) NOT NULL,
  `bookdate` datetime DEFAULT NULL,
  `canceldate` datetime DEFAULT NULL,
  `cancelby` varchar(12) COLLATE latin1_spanish_ci DEFAULT NULL,
  `flag` int(11) NOT NULL DEFAULT '0',
  `updated` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=328 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;



--
-- Table structure for table `student`
--
DROP TABLE IF EXISTS `student`;
CREATE TABLE `student` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
  `lastname` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
  `username` varchar(25) COLLATE latin1_spanish_ci NOT NULL,
  `email` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
  `phone` int(12) NOT NULL,
  `password` varchar(255) COLLATE latin1_spanish_ci NOT NULL,
  `status` int(11) NOT NULL,
  `updated` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;



--
-- Table structure for table `note`
--
DROP TABLE IF EXISTS `note`;
CREATE TABLE `note` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `text` text COLLATE latin1_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;



--
-- Insert admin account.
--
-- INSERT INTO `student` (`firstname`, `lastname`, `username`, `email`, `phone`, `password`, `status`) VALUES("niko", "kanto", "admin", "niko@kanto.com", "654987345", ENCRYPT("admin"), 2);


--
-- Insert dummy students
--
INSERT INTO `student` (`id`, `firstname`, `lastname`, `username`, `email`, `phone`, `password`, `status`, `updated`) VALUES
(1, 'niko', 'kanto', 'admin', 'niko@kanto.com', 654987345, 'wRmFwpc.GvGsk', 2, '2018-10-28 10:47:57'),
(53, 'Javier', 'Aranda', 'javi1', 'javier@aranda.com', 650456786, '$2y$10$O95LT7aKB0R5vxkfYcyvyORELDZDpgegD.RwgTA3NRQTc22NJjJxS', 2, '2018-10-28 10:32:06'),
(54, 'Manuel', 'Romero', 'manu1', 'manuel@romero.com', 645678654, '$2y$10$res/eP98CsQp3JOuhTbMDOr2yF2S4XlaOCWLi6.VIrLaOkvfE.WQm', 2, '2018-10-28 10:32:13'),
(55, 'Marta', 'Delgado', 'mart1', 'marta@delgado.com', 657879345, '$2y$10$ZfXCMEmq9oO8OMBL6A6nsOHPncKl.IRKWyKCLLW0fy32mYZMRS/Wq', 2, '2018-10-28 10:32:21'),
(56, 'Gorka', 'Lorenzo', 'gork1', 'gorka@lorenzo.com', 643455668, '$2y$10$MYxMJbq/EvtGYLSC1viiAupHCM/Ui81CsXn4En6y8/B27NjX0mGuO', 2, '2018-10-28 10:32:29'),
(57, 'Sara', 'Cobos', 'sara1', 'sara@cobos.com', 657765678, '$2y$10$Rnm2NJ2eTVAFhlL6s2/XquR0PR4xbCdxbB/wjutvT15S2bpW.r6jG', 2, '2018-10-28 10:32:38'),
(58, 'Noelia', 'Blanes', 'noel1', 'noelia@blanes.com', 656789234, '$2y$10$44hVdv1gUI6A8Xqj3vvyc.C4Ax8JkSRCHzq2NSu9o5F7HLY0h6TMO', 2, '2018-10-28 10:32:47');
