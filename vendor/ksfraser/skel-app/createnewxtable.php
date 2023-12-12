<?php

$tablename = $argv[1];
$table2name = $argv[2];
$table3name = $argv[3];
$table4name = $argv[4];
$filename = $tablename . "_x_" . $table2name;
if( strlen( $table3name ) > 2 )
{
	$filename .= "_x_" . $table3name;
}
if( strlen( $table4name ) > 2 )
{
	$filename .= "_x_" . $table4name;
}
$tblname = $filename;
$filename = "new_" . $filename . ".sql";


$fp = fopen( $filename, "w" );

fwrite( $fp, "CREATE TABLE `" . $tblname  . "` (" );
fwrite( $fp, "  `id" . $tablename . "_x_" . $table2name . "` int(11) NOT NULL auto_increment COMMENT 'Index'," );
fwrite( $fp, "  `" . $tablename . "_x_" . $table2name . "` int(11) COMMENT 'HIDE'," );
fwrite( $fp, "  `id$tablename` int(11) NOT NULL COMMENT 'Index $tablename'," );
fwrite( $fp, "  `id$table2name` int(11) NOT NULL COMMENT 'Index $table2name'," );
if( strlen( $table3name ) > 2 )
{
fwrite( $fp, "  `id$table3name` int(11) NOT NULL COMMENT 'Index $table3name'," );
}
if( strlen( $table4name ) > 2 )
{
fwrite( $fp, "  `id$table4name` int(11) NOT NULL COMMENT 'Index $table4name'," );
}
fwrite( $fp, "  `fromdate` date NOT NULL COMMENT 'As of date'," );
fwrite( $fp, "  `todate` date COMMENT 'Until date'," );
fwrite( $fp, "  `createdby` varchar(32) NOT NULL COMMENT 'Created By'," );
fwrite( $fp, "  `createddate` date NOT NULL COMMENT 'Created On'," );
fwrite( $fp, "  `updatedby` varchar(32) NOT NULL COMMENT 'Updated By'," );
fwrite( $fp, "  `updateddate` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP COMMENT 'Updated On'," );
fwrite( $fp, "  `comments` varchar(255) NOT NULL COMMENT 'Comments'," );
fwrite( $fp, "  PRIMARY KEY  (`id" . $tablename . "_x_" . $table2name . "`)" );
fwrite( $fp, ") ENGINE=MyISAM DEFAULT CHARSET=latin1;" );

fclose ($fp);

//$cmd = "mysql -p < $filename";
?>
