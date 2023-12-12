DROP TABLE IF EXISTS `stateworkflow`;
CREATE TABLE `stateworkflow` (
`workflow_id` smallint(5) unsigned  NULL  default '' comment '',
`workflow_name` varchar(80)   NULL  default '' comment '',
`workflow_desc` text,
()   NOT NULL  default '' comment '',
`start_task_id` varchar(40)   NULL  default '' comment '',
`is_valid` char(1)   NULL  default '' comment '',
`workflow_errors` text,
()   NOT NULL  default '' comment '',
`start_date` date()   NOT NULL  default '' comment '',
`end_date` date()   NOT NULL  default '' comment '',
`created_date` datetime()   NULL  default '' comment '',
`created_user` varchar(16)   NOT NULL  default '' comment '',
`revised_date` datetime()   NOT NULL  default '' comment '',
`revised_user` varchar(16)   NOT NULL  default '' comment '',
`workflow_id` smallint(5) unsigned  NULL  default '' comment '',
`workflow_name` varchar(80)   NULL  default '' comment '',
`workflow_desc` text,
()   NOT NULL  default '' comment '',
`start_task_id` varchar(40)   NULL  default '' comment '',
`is_valid` char(1)   NULL  default '' comment '',
`workflow_errors` text,
()   NOT NULL  default '' comment '',
`start_date` date()   NOT NULL  default '' comment '',
`end_date` date()   NOT NULL  default '' comment '',
`created_date` datetime()   NULL  default '' comment '',
`created_user` varchar(16)   NOT NULL  default '' comment '',
`revised_date` datetime()   NOT NULL  default '' comment '',
`revised_user` varchar(16)   NOT NULL  default '' comment '', PRIMARY KEY (`workflow_id`, 'workflow_id', 'workflow_id')) ENGINE=InnoDB;
