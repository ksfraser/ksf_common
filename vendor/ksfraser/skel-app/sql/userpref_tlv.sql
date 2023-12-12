CREATE TABLE `userpref_tlv` (
  `iduserpref_tlv` int(11) NOT NULL auto_increment,
  `pref` varchar(32) NOT NULL default '',
  `type` varchar(32) NOT NULL default '',
  `length` int(11) NOT NULL default '0',
  `defaultvalue` varchar(32) NOT NULL default '',
  `minvalue` varchar(32) NOT NULL default '',
  `maxvalue` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`iduserpref_tlv`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

