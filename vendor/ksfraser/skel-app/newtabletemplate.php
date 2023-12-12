<?php

/*

Create a new table from the command line.  Some fields are auto genned.


DROP TABLE IF EXISTS `accountants`;
CREATE TABLE `accountants` (
  `idaccountants` int(11) NOT NULL auto_increment COMMENT 'Index',
  `createddate` datetime NOT NULL COMMENT 'Created Timestamp',
  `createdby` varchar(32) NOT NULL COMMENT 'Created By',
  `updateddate` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP COMMENT 'Updated Timestamp',
  `updatedby` varchar(32) NOT NULL COMMENT 'Updated By',
  `name` varchar(255) NOT NULL COMMENT 'Name',
  `description` varchar(255) NOT NULL COMMENT 'Description',
  `address` varchar(255) NOT NULL COMMENT 'Address',
  `idcity` int(11) NOT NULL COMMENT 'City',
  `province` varchar(255) NOT NULL COMMENT 'Province',
  `country` varchar(255) NOT NULL COMMENT 'Country',
  `phone` varchar(32) NOT NULL COMMENT 'Phone Number',
  `contact` varchar(32) NOT NULL COMMENT 'Contact',
  `comment` varchar(255) NOT NULL COMMENT 'Comments',
  `emailaddress` varchar(255) NOT NULL COMMENT 'Email Address',
  PRIMARY KEY  (`idaccountants`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC COMMENT='Accountants';

*/


$tablename = $argv[1];
$indexname = "id" . $tablename;
$dropstatement = "DROP TABLE IF EXISTS `" . $tablename . "`;\n";
$createhdr = "CREATE TABLE `" . $tablename . "` (\n";
$createftr = ") ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC COMMENT='" . $tablename . "';\n";
$createrow = "     `" . $indexname . "` int(11) NOT NULL auto_increment COMMENT 'Index',\n";
$createrow .= "      `createddate` datetime NOT NULL COMMENT 'Created Timestamp',\n      `createdby` varchar(32) NOT NULL COMMENT 'Created By',\n      `updateddate` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP COMMENT 'Updated Timestamp',\n      `updatedby` varchar(32) NOT NULL COMMENT 'Updated By',\n";
$count = 2;
while( $count < $argc )
{
	if( strcmp( $argv[$count], "comment" ) == 0 )
	{
		$createrow .= "     " . "`comment` varchar(255) NOT NULL COMMENT 'Comments'," . "\n";
	}
	else
	if( strcmp( $argv[$count], "description" ) == 0 )
	{
		$createrow .= "     " . "`description` varchar(255) NOT NULL COMMENT 'Description'," . "\n";
	}
	else
	if( strcmp( $argv[$count], "date" ) == 0 )
	{
		$createrow .= "     " . "`date` datetime NOT NULL COMMENT 'Created Timestamp'," . "\n";
	}
	else
	if( strcmp( $argv[$count], "emailaddress" ) == 0 )
	{
		$createrow .= "     " . "`emailaddress` varchar(255) NOT NULL COMMENT 'Email Address'," .  "\n";
	}
	else
	if( strncmp( $argv[$count], "id", 2 ) == 0 )
	{
		$createrow .= "     " . "`" . $argv[$count] . "` int(11) NOT NULL COMMENT '" . $argv[$count] . "'," . "\n";
	}
	else
	{
		//ASSUMING string
		$createrow .= "     `" . $argv[$count] . "` varchar(32) NOT NULL COMMENT '" . $argv[$count] . "'," . "\n";
	}
	$count++;
}
$primarykey = "     PRIMARY KEY  (`id" . $tablename . "`)\n";

echo $dropstatement;
echo $createhdr;
echo $createrow;
echo $primarykey;
echo $createftr;

?>


