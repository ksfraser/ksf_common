CREATE TABLE `userpref` (
  `iduserpref` int(11) NOT NULL auto_increment,
  `idusers` int(11) NOT NULL default '0',
  `iduserpref_tlv` int(11) NOT NULL default '0',
  `value` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`iduserpref`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

