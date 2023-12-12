DROP TABLE IF EXISTS `statetransition`;
CREATE TABLE `statetransition` (
`workflow_id` smallint(5) unsigned  NULL  default '' comment '',
`transition_id` smallint(5) unsigned  NULL  default '' comment '',
`transition_name` varchar(80)   NULL  default '' comment '',
`transition_desc` text,
()   NOT NULL  default '' comment '',
`transition_trigger` varchar(4)   NULL  default '' comment '',
`time_limit` smallint(5) unsigned  NOT NULL  default '' comment '',
`task_id` varchar(40)   NULL  default '' comment '',
`roles_id` varchar(16)   NOT NULL  default '' comment '',
`created_date` datetime()   NULL  default '' comment '',
`created_user` varchar(16)   NOT NULL  default '' comment '',
`revised_date` datetime()   NOT NULL  default '' comment '',
`revised_user` varchar(16)   NOT NULL  default '' comment '',
`workflow_id` smallint(5) unsigned  NULL  default '' comment '',
`transition_id` smallint(5) unsigned  NULL  default '' comment '',
`transition_name` varchar(80)   NULL  default '' comment '',
`transition_desc` text,
()   NOT NULL  default '' comment '',
`transition_trigger` varchar(4)   NULL  default '' comment '',
`time_limit` smallint(5) unsigned  NOT NULL  default '' comment '',
`task_id` varchar(40)   NULL  default '' comment '',
`roles_id` varchar(16)   NOT NULL  default '' comment '',
`created_date` datetime()   NULL  default '' comment '',
`created_user` varchar(16)   NOT NULL  default '' comment '',
`revised_date` datetime()   NOT NULL  default '' comment '',
`revised_user` varchar(16)   NOT NULL  default '' comment '', PRIMARY KEY (`workflow_id`, 'workflow_id', 'transition_id', 'workflow_id', 'transition_id')) ENGINE=InnoDB;
