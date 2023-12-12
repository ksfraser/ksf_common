DROP TABLE IF EXISTS `log`;
CREATE TABLE `log` (
`date` timestamp(16)   NOT NULL  default 'CURRENT_TIMESTAMP' comment 'date',
`query` varchar(2550)   NOT NULL  default '' comment 'query',
`user` varchar(45)   NOT NULL  default '' comment 'user',
`log_id` int(8)   NOT NULL auto_increment comment 'row', PRIMARY KEY (`log_id`)) ENGINE=InnoDB;
