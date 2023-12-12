DROP TABLE IF EXISTS `statecase`;
CREATE TABLE `statecase` (
`case_id` int(10) unsigned  NULL  default '' comment '',
`workflow_id` smallint(5) unsigned  NULL  default '' comment '',
`context` varchar(255)   NULL  default '' comment '',
`case_status` char(2)   NULL  default '' comment '',
`start_date` datetime()   NULL  default '' comment '',
`end_date` datetime()   NOT NULL  default '' comment '',
`created_date` datetime()   NULL  default '' comment '',
`created_user` varchar(16)   NOT NULL  default '' comment '',
`revised_date` datetime()   NOT NULL  default '' comment '',
`revised_user` varchar(16)   NOT NULL  default '' comment '',
`case_id` int(10) unsigned  NULL  default '' comment '',
`workflow_id` smallint(5) unsigned  NULL  default '' comment '',
`context` varchar(255)   NULL  default '' comment '',
`case_status` char(2)   NULL  default '' comment '',
`start_date` datetime()   NULL  default '' comment '',
`end_date` datetime()   NOT NULL  default '' comment '',
`created_date` datetime()   NULL  default '' comment '',
`created_user` varchar(16)   NOT NULL  default '' comment '',
`revised_date` datetime()   NOT NULL  default '' comment '',
`revised_user` varchar(16)   NOT NULL  default '' comment '', PRIMARY KEY (`case_id`, 'case_id', 'case_id')) ENGINE=InnoDB;
