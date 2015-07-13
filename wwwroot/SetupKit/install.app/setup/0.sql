CREATE TABLE IF NOT EXISTS `ewggroup` (
  `id` int(11) NOT NULL,
  `name` varchar(40) NOT NULL,
  `can` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
