<?php

$tablename = $argv[1];
$filename = "new_" . $tablename . ".sql";

$fp = fopen( $filename, "w" );

fwrite( $fp, "CREATE TABLE `$tablename` (" );
fwrite( $fp, "  `id$tablename` int(11) NOT NULL auto_increment COMMENT 'Index'," );
fwrite( $fp, "  `createdby` varchar(32) NOT NULL COMMENT 'Created By'," );
fwrite( $fp, "  `createddate` date NOT NULL COMMENT 'Created On'," );
fwrite( $fp, "  `updatedby` varchar(32) NOT NULL COMMENT 'Updated By'," );
fwrite( $fp, "  `updateddate` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP COMMENT 'Updated On'," );
fwrite( $fp, "  `$tablename` int(11) NOT NULL COMMENT 'Focus of this table.  SET ME'," );
fwrite( $fp, "  `comments` varchar(255) NOT NULL COMMENT 'Comments'," );
fwrite( $fp, "  PRIMARY KEY  (`id$tablename`)" );
fwrite( $fp, ") ENGINE=MyISAM DEFAULT CHARSET=latin1;" );

fclose ($fp);

//$cmd = "mysql -p < $filename";
?>
