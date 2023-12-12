DROP TABLE IF EXISTS `statearc`;
CREATE TABLE `statearc` (
`workflow_id` smallint(5) unsigned  NULL  default '' comment '',
`transition_id` smallint(5) unsigned  NULL  default '' comment '',
`place_id` smallint(5) unsigned  NULL  default '' comment '',
`direction` char(3)   NULL  default '' comment '',
`arc_type` varchar(10)   NULL  default '' comment '',
`pre_condition` text,
()   NOT NULL  default '' comment '',
`created_date` datetime()   NULL  default '' comment '',
`created_user` varchar(16)   NOT NULL  default '' comment '',
`revised_date` datetime()   NOT NULL  default '' comment '',
`revised_user` varchar(16)   NOT NULL  default '' comment '',
`workflow_id` smallint(5) unsigned  NULL  default '' comment '',
`transition_id` smallint(5) unsigned  NULL  default '' comment '',
`place_id` smallint(5) unsigned  NULL  default '' comment '',
`direction` char(3)   NULL  default '' comment '',
`arc_type` varchar(10)   NULL  default '' comment '',
`pre_condition` text,
()   NOT NULL  default '' comment '',
`created_date` datetime()   NULL  default '' comment '',
`created_user` varchar(16)   NOT NULL  default '' comment '',
`revised_date` datetime()   NOT NULL  default '' comment '',
`revised_user` varchar(16)   NOT NULL  default '' comment '', PRIMARY KEY (`workflow_id`, 'workflow_id', 'transition_id', 'place_id', 'direction', 'workflow_id', 'transition_id', 'place_id', 'direction')) ENGINE=InnoDB;
