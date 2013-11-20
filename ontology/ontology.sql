--
-- テーブルの構造 `concept`
--

CREATE TABLE IF NOT EXISTS `concept` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `name` varchar(1000) DEFAULT NULL,
  `uri` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=38 ;

--
-- テーブルの構造 `ontology`
--

CREATE TABLE IF NOT EXISTS `ontology` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `subject` int(100) NOT NULL,
  `predict` int(100) NOT NULL,
  `object` int(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;
