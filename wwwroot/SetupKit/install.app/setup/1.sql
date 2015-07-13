CREATE TABLE IF NOT EXISTS `ewguser` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `group` int(11) NOT NULL,
  `rnd` varchar(20) NOT NULL,
  `pass` varchar(40) NOT NULL,
  `mail` varchar(80) NOT NULL,
  `can` text NOT NULL,
  `data` text NOT NULL,
  `session` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;
