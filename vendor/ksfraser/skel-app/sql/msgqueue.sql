DROP TABLE IF EXISTS `msgqueue`;
CREATE TABLE `msgqueue` (
`idmsgqueue` int(10) unsigned  NULL auto_increment comment 'Index',
`msgid` varchar(45) unsigned  NULL  default '' comment 'Message ID',
`mfrom` varchar(45)   NULL  default '' comment 'Message From',
`mto` varchar(45)   NULL  default '' comment 'Message To',
`message` text()   NULL  default '' comment 'Message Body',
`date` timestamp()   NULL  default 'NULL' comment 'Date',
`mstatus` varchar(45) unsigned  NULL  default 'new' comment 'Message Status', PRIMARY KEY (`idmsgqueue`)) ENGINE=InnoDB;
