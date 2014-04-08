
-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE IF NOT EXISTS `employee` (
  `eid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `department` varchar(250) NOT NULL,
  `fullname` varchar(250) NOT NULL,
  `job` varchar(250) NOT NULL,
  `rate` int(11) NOT NULL,
  `u_location` int(11) NOT NULL,
  `view` int(11) NOT NULL,
  `onleave` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `login` tinyint(4) NOT NULL,
  `assign` int(11) NOT NULL,
  `vacation` int(11) NOT NULL,
  `u_email` varchar(200) NOT NULL,
  `benefit` int(11) NOT NULL,
  `upload` int(11) NOT NULL,
  `post` int(11) NOT NULL,
  `bonus` varchar(11) NOT NULL,
  `bonusfrom` varchar(100) NOT NULL,
  `deposit` int(11) NOT NULL,
  `special` tinyint(4) NOT NULL,
  `email_assigned` tinyint(3) unsigned NOT NULL COMMENT 'send email when assigned or not',
  `callcentre` tinyint(3) unsigned NOT NULL,
  `orderdesk` tinyint(3) unsigned NOT NULL,
  `goal` varchar(10) NOT NULL,
  `secretquestion` tinyint(4) NOT NULL,
  `agree` tinyint(4) NOT NULL,
  `locked` tinyint(4) NOT NULL,
  `kick` bigint(20) NOT NULL,
  `create_email` tinyint(4) NOT NULL,
  `newwindow` int(11) NOT NULL,
  `gid` int(5) NOT NULL,
  `mobile_phone` varchar(30) DEFAULT NULL,
  `text_assigned` tinyint(4) NOT NULL,
  `email_notes` int(11) NOT NULL,
  `associate1` int(11) NOT NULL,
  `associate2` int(11) NOT NULL,
  `lastlogin` varchar(100) NOT NULL,
  `birthday` varchar(100) NOT NULL,
  `verify` text NOT NULL,
  `verfied` int(11) NOT NULL,
  PRIMARY KEY (`eid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `episodes`
--

CREATE TABLE IF NOT EXISTS `episodes` (
  `title` varchar(255) NOT NULL,
  `episode` int(11) NOT NULL,
  `season` int(11) NOT NULL,
  `date` varchar(100) NOT NULL,
  `episodeid` int(11) NOT NULL AUTO_INCREMENT,
  `posted` int(11) NOT NULL,
  `showid` int(11) NOT NULL,
  `dateadded` int(20) NOT NULL,
  `timestamp` int(20) NOT NULL,
  `views` int(11) NOT NULL,
  `tunefind` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `magnet` varchar(255) NOT NULL,
  PRIMARY KEY (`episodeid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `links`
--

CREATE TABLE IF NOT EXISTS `links` (
  `linkid` int(11) NOT NULL AUTO_INCREMENT,
  `songid` int(11) NOT NULL,
  `linktext` text NOT NULL,
  `dateadded` int(20) NOT NULL,
  `deleted` int(20) NOT NULL,
  `title` varchar(255) NOT NULL,
  `real` text NOT NULL,
  `clickcount` int(11) NOT NULL,
  PRIMARY KEY (`linkid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `shows`
--

CREATE TABLE IF NOT EXISTS `shows` (
  `showid` int(11) NOT NULL AUTO_INCREMENT,
  `showname` varchar(255) NOT NULL,
  `imdb` int(11) NOT NULL,
  `thetvdb` int(11) NOT NULL,
  `shownamevariation` varchar(255) NOT NULL,
  `posted` int(11) NOT NULL,
  `tobeposted` int(11) NOT NULL,
  `lastseason` int(11) NOT NULL,
  `airday` int(11) NOT NULL,
  `updated` varchar(100) NOT NULL,
  PRIMARY KEY (`showid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `songs`
--

CREATE TABLE IF NOT EXISTS `songs` (
  `songid` int(11) NOT NULL AUTO_INCREMENT,
  `episodeid` int(11) NOT NULL,
  `songtext` text NOT NULL,
  `desc` text NOT NULL,
  `position` int(11) NOT NULL,
  `dateadded` varchar(100) NOT NULL,
  `deleted` int(20) NOT NULL,
  `theme` int(11) NOT NULL,
  `clickcount` int(11) NOT NULL,
  `eid` int(11) NOT NULL,
  PRIMARY KEY (`songid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `subs`
--

CREATE TABLE IF NOT EXISTS `subs` (
  `subid` int(11) NOT NULL AUTO_INCREMENT,
  `showid` int(11) NOT NULL,
  `lastsent` varchar(50) NOT NULL,
  `eid` int(11) NOT NULL,
  `del` int(11) NOT NULL,
  PRIMARY KEY (`subid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
