DROP TABLE IF EXISTS `stateplace`;
CREATE TABLE `stateplace` (
`workflow_id` smallint(5) unsigned  NULL  default '' comment '',
`place_id` smallint(5) unsigned  NULL  default '' comment '',
`place_type` char(1)   NULL  default '' comment '',
`place_name` varchar(80)   NULL  default '' comment '',
`place_desc` text,
()   NOT NULL  default '' comment '',
`created_date` datetime()   NULL  default '' comment '',
`created_user` varchar(16)   NOT NULL  default '' comment '',
`revised_date` datetime()   NOT NULL  default '' comment '',
`revised_user` varchar(16)   NOT NULL  default '' comment '',
`workflow_id` smallint(5) unsigned  NULL  default '' comment '',
`place_id` smallint(5) unsigned  NULL  default '' comment '',
`place_type` char(1)   NULL  default '' comment '',
`place_name` varchar(80)   NULL  default '' comment '',
`place_desc` text,
()   NOT NULL  default '' comment '',
`created_date` datetime()   NULL  default '' comment '',
`created_user` varchar(16)   NOT NULL  default '' comment '',
`revised_date` datetime()   NOT NULL  default '' comment '',
`revised_user` varchar(16)   NOT NULL  default '' comment '', PRIMARY KEY (`workflow_id`, 'workflow_id', 'place_id', 'workflow_id', 'place_id')) ENGINE=InnoDB;
