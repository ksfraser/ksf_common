DROP TABLE IF EXISTS `stateworkitem`;
CREATE TABLE `stateworkitem` (
`case_id` int(10) unsigned  NULL  default '' comment '',
`workitem_id` smallint(5) unsigned  NULL  default '' comment '',
`workflow_id` smallint(6) unsigned  NULL  default '' comment '',
`transition_id` smallint(5) unsigned  NULL  default '' comment '',
`transition_trigger` varchar(4)   NULL  default '' comment '',
`task_id` varchar(40)   NULL  default '' comment '',
`context` varchar(255)   NULL  default '' comment '',
`workitem_status` char(2)   NULL  default '' comment '',
`enabled_date` datetime()   NOT NULL  default '' comment '',
`cancelled_date` datetime()   NOT NULL  default '' comment '',
`finished_date` datetime()   NOT NULL  default '' comment '',
`deadline` datetime()   NOT NULL  default '' comment '',
`roles_id` varchar(16)   NOT NULL  default '' comment '',
`user_id` varchar(16)   NOT NULL  default '' comment '',
`case_id` int(10) unsigned  NULL  default '' comment '',
`workitem_id` smallint(5) unsigned  NULL  default '' comment '',
`workflow_id` smallint(6) unsigned  NULL  default '' comment '',
`transition_id` smallint(5) unsigned  NULL  default '' comment '',
`transition_trigger` varchar(4)   NULL  default '' comment '',
`task_id` varchar(40)   NULL  default '' comment '',
`context` varchar(255)   NULL  default '' comment '',
`workitem_status` char(2)   NULL  default '' comment '',
`enabled_date` datetime()   NOT NULL  default '' comment '',
`cancelled_date` datetime()   NOT NULL  default '' comment '',
`finished_date` datetime()   NOT NULL  default '' comment '',
`deadline` datetime()   NOT NULL  default '' comment '',
`roles_id` varchar(16)   NOT NULL  default '' comment '',
`user_id` varchar(16)   NOT NULL  default '' comment '', PRIMARY KEY (`case_id`, 'case_id', 'workitem_id', 'case_id', 'workitem_id')) ENGINE=InnoDB;
