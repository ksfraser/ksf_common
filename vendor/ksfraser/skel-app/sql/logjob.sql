DROP TABLE IF EXISTS `logjob`;
CREATE TABLE `logjob` (
`idlogjob` int(10) unsigned  NULL auto_increment comment 'Index',
`ltable` varchar(45)   NULL  default '' comment 'Table',
`roadnumber` int(10) unsigned  NULL  default '' comment 'Source',
`lastindex` int(10) unsigned  NULL  default '' comment 'Last Index', PRIMARY KEY (`idlogjob`)) ENGINE=InnoDB;
