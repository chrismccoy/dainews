CREATE TABLE `celebrities` (
  `celeb_id` int(20) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `images` int(10) NOT NULL,
  `last_crawl` int(25) NOT NULL,
  PRIMARY KEY  (`celeb_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

CREATE TABLE `images` (
  `image_id` int(20) NOT NULL auto_increment,
  `celeb_id` int(20) NOT NULL,
  `nudity_id` int(10) NOT NULL,
  `quality_id` int(10) NOT NULL,
  `added` datetime NOT NULL,
  `filesize` int(10) NOT NULL,
  `width` int(4) NOT NULL,
  `height` int(4) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `comments` int(10) NOT NULL,
  `reports` int(10) NOT NULL,
  `views` int(10) NOT NULL,
  PRIMARY KEY  (`image_id`),
  KEY `celeb_id` (`celeb_id`,`nudity_id`,`quality_id`,`added`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

CREATE TABLE `nudity_levels` (
  `id` int(10) NOT NULL auto_increment,
  `title` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6;

INSERT INTO nudity_levels VALUES(1, '1');
INSERT INTO nudity_levels VALUES(2, '2');
INSERT INTO nudity_levels VALUES(3, '3');
INSERT INTO nudity_levels VALUES(4, '4');
INSERT INTO nudity_levels VALUES(5, '5');

CREATE TABLE `quality_levels` (
  `id` int(20) NOT NULL auto_increment,
  `title` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6;

INSERT INTO quality_levels VALUES(1, '1');
INSERT INTO quality_levels VALUES(2, '2');
INSERT INTO quality_levels VALUES(3, '3');
INSERT INTO quality_levels VALUES(4, '4');
INSERT INTO quality_levels VALUES(5, '5');