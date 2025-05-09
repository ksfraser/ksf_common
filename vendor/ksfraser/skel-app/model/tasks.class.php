<?php 
//This file was generated by calling php /var/www/html/skel-app/generator.php acl 

//this script may only be included - so its better to die if called directly.
if ( strpos( $_SERVER["SCRIPT_NAME"], basename(__FILE__) ) !== false ) {
  header( "location: ../index.php" );
  exit;
}

include_once('tasks.extend.php');

class tasks extends generictable 
{
         private $observers;
         var $data; //data passed in by other calls
         var $idtasks;
		 /*Task Index */
         var $tasktype;
		 /*Task Type */
         var $taskdescription;
		 /*Task Description */
         var $tasklink;
		 /*Link for Task/Menu */
         var $taskparent;
		 /*Parent Menu Item */
         function __construct()
         {
         	$this->querytablename = 'tasks';
         	$this->fieldspec['idtasks']['metadata_id'] = '2882';
         	$this->fieldspec['idtasks']['table_name'] = 'tasks';
         	$this->fieldspec['idtasks']['column_name'] = 'idtasks';
         	$this->fieldspec['idtasks']['pretty_name'] = 'Task Index';
         	$this->fieldspec['idtasks']['abstract_data_type'] = 'index';
         	$this->fieldspec['idtasks']['db_data_type'] = 'integer';
         	$this->fieldspec['idtasks']['field_null'] = 'NO';
         	$this->fieldspec['idtasks']['field_key'] = '0';
         	$this->fieldspec['idtasks']['extra_sql'] = ' ';
         	$this->fieldspec['idtasks']['html_form_type'] = 'integer';
         	$this->fieldspec['idtasks']['html_form_options'] = ' ';
         	$this->fieldspec['idtasks']['html_form_explanation'] = ' ';
         	$this->fieldspec['idtasks']['help_text'] = ' ';
         	$this->fieldspec['idtasks']['mandatory_p'] = 'Y';
         	$this->fieldspec['idtasks']['sort_key'] = '0';
         	$this->fieldspec['idtasks']['form_sort_key'] = '0';
         	$this->fieldspec['idtasks']['form_number'] = '1';
         	$this->fieldspec['idtasks']['default_value'] = '0';
         	$this->fieldspec['idtasks']['field_toupper'] = 'NO';
         	$this->fieldspec['idtasks']['validationprocname'] = ' ';
         	$this->fieldspec['idtasks']['c_size'] = '8';
         	$this->fieldspec['idtasks']['prikey'] = 'Y';
         	$this->fieldspec['idtasks']['noedit'] = 'Y';
         	$this->fieldspec['idtasks']['nodisplay'] = 'N';
         	$this->fieldspec['idtasks']['c_unsigned'] = 'N';
         	$this->fieldspec['idtasks']['c_zerofill'] = 'N';
         	$this->fieldspec['idtasks']['c_auto_increment'] = 'N';
         	$this->fieldspec['idtasks']['foreign_table'] = '';
         	$this->fieldspec['idtasks']['foreign_key'] = '';
         	$this->fieldspec['idtasks']['application'] = 'acl';
         	$this->fieldspec['idtasks']['issearchable'] = '1';
         	$this->fieldspec['idtasks']['preinserttrigger'] = '';
         	$this->fieldspec['idtasks']['postinserttrigger'] = '';
         	$this->fieldspec['idtasks']['preupdatetrigger'] = '';
         	$this->fieldspec['idtasks']['postupdatetrigger'] = '';
         	$this->fieldspec['idtasks']['predeletetrigger'] = '';
         	$this->fieldspec['idtasks']['postdeletetrigger'] = '';
         	$this->fieldspec['idtasks']['postinsert'] = 'PostidtasksInsert';
         	$this->fieldspec['idtasks']['postupdate'] = 'PostidtasksUpdate';
         	$this->fieldspec['idtasks']['postdelete'] = 'PostidtasksDelete';
         	$this->fieldspec['idtasks']['preinsert'] = 'PreidtasksInsert';
         	$this->fieldspec['idtasks']['preupdate'] = 'PreidtasksUpdate';
         	$this->fieldspec['idtasks']['predelete'] = 'PreidtasksDelete';
         	$this->fieldspec['tasktype']['metadata_id'] = '2883';
         	$this->fieldspec['tasktype']['table_name'] = 'tasks';
         	$this->fieldspec['tasktype']['column_name'] = 'tasktype';
         	$this->fieldspec['tasktype']['pretty_name'] = 'Task Type';
         	$this->fieldspec['tasktype']['abstract_data_type'] = 'string';
         	$this->fieldspec['tasktype']['db_data_type'] = 'varchar';
         	$this->fieldspec['tasktype']['field_null'] = 'YES';
         	$this->fieldspec['tasktype']['field_key'] = '0';
         	$this->fieldspec['tasktype']['extra_sql'] = ' ';
         	$this->fieldspec['tasktype']['html_form_type'] = 'text';
         	$this->fieldspec['tasktype']['html_form_options'] = ' ';
         	$this->fieldspec['tasktype']['html_form_explanation'] = ' ';
         	$this->fieldspec['tasktype']['help_text'] = ' ';
         	$this->fieldspec['tasktype']['mandatory_p'] = 'Y';
         	$this->fieldspec['tasktype']['sort_key'] = '0';
         	$this->fieldspec['tasktype']['form_sort_key'] = '0';
         	$this->fieldspec['tasktype']['form_number'] = '1';
         	$this->fieldspec['tasktype']['default_value'] = 'TASK';
         	$this->fieldspec['tasktype']['field_toupper'] = 'YES';
         	$this->fieldspec['tasktype']['validationprocname'] = ' ';
         	$this->fieldspec['tasktype']['c_size'] = '45';
         	$this->fieldspec['tasktype']['prikey'] = 'N';
         	$this->fieldspec['tasktype']['noedit'] = 'N';
         	$this->fieldspec['tasktype']['nodisplay'] = 'N';
         	$this->fieldspec['tasktype']['c_unsigned'] = 'N';
         	$this->fieldspec['tasktype']['c_zerofill'] = 'N';
         	$this->fieldspec['tasktype']['c_auto_increment'] = 'N';
         	$this->fieldspec['tasktype']['foreign_table'] = '';
         	$this->fieldspec['tasktype']['foreign_key'] = '';
         	$this->fieldspec['tasktype']['application'] = 'acl';
         	$this->fieldspec['tasktype']['issearchable'] = '1';
         	$this->fieldspec['tasktype']['preinserttrigger'] = '';
         	$this->fieldspec['tasktype']['postinserttrigger'] = '';
         	$this->fieldspec['tasktype']['preupdatetrigger'] = '';
         	$this->fieldspec['tasktype']['postupdatetrigger'] = '';
         	$this->fieldspec['tasktype']['predeletetrigger'] = '';
         	$this->fieldspec['tasktype']['postdeletetrigger'] = '';
         	$this->fieldspec['tasktype']['postinsert'] = 'PosttasktypeInsert';
         	$this->fieldspec['tasktype']['postupdate'] = 'PosttasktypeUpdate';
         	$this->fieldspec['tasktype']['postdelete'] = 'PosttasktypeDelete';
         	$this->fieldspec['tasktype']['preinsert'] = 'PretasktypeInsert';
         	$this->fieldspec['tasktype']['preupdate'] = 'PretasktypeUpdate';
         	$this->fieldspec['tasktype']['predelete'] = 'PretasktypeDelete';
         	$this->fieldspec['taskdescription']['metadata_id'] = '2887';
         	$this->fieldspec['taskdescription']['table_name'] = 'tasks';
         	$this->fieldspec['taskdescription']['column_name'] = 'taskdescription';
         	$this->fieldspec['taskdescription']['pretty_name'] = 'Task Description';
         	$this->fieldspec['taskdescription']['abstract_data_type'] = 'string';
         	$this->fieldspec['taskdescription']['db_data_type'] = 'varchar';
         	$this->fieldspec['taskdescription']['field_null'] = 'NO';
         	$this->fieldspec['taskdescription']['field_key'] = '0';
         	$this->fieldspec['taskdescription']['extra_sql'] = ' ';
         	$this->fieldspec['taskdescription']['html_form_type'] = 'text';
         	$this->fieldspec['taskdescription']['html_form_options'] = ' ';
         	$this->fieldspec['taskdescription']['html_form_explanation'] = ' ';
         	$this->fieldspec['taskdescription']['help_text'] = ' ';
         	$this->fieldspec['taskdescription']['mandatory_p'] = 'Y';
         	$this->fieldspec['taskdescription']['sort_key'] = '0';
         	$this->fieldspec['taskdescription']['form_sort_key'] = '0';
         	$this->fieldspec['taskdescription']['form_number'] = '1';
         	$this->fieldspec['taskdescription']['default_value'] = '';
         	$this->fieldspec['taskdescription']['field_toupper'] = 'YES';
         	$this->fieldspec['taskdescription']['validationprocname'] = ' ';
         	$this->fieldspec['taskdescription']['c_size'] = '45';
         	$this->fieldspec['taskdescription']['prikey'] = 'N';
         	$this->fieldspec['taskdescription']['noedit'] = 'N';
         	$this->fieldspec['taskdescription']['nodisplay'] = 'N';
         	$this->fieldspec['taskdescription']['c_unsigned'] = 'N';
         	$this->fieldspec['taskdescription']['c_zerofill'] = 'N';
         	$this->fieldspec['taskdescription']['c_auto_increment'] = 'N';
         	$this->fieldspec['taskdescription']['foreign_table'] = '';
         	$this->fieldspec['taskdescription']['foreign_key'] = '';
         	$this->fieldspec['taskdescription']['application'] = 'acl';
         	$this->fieldspec['taskdescription']['issearchable'] = '1';
         	$this->fieldspec['taskdescription']['preinserttrigger'] = '';
         	$this->fieldspec['taskdescription']['postinserttrigger'] = '';
         	$this->fieldspec['taskdescription']['preupdatetrigger'] = '';
         	$this->fieldspec['taskdescription']['postupdatetrigger'] = '';
         	$this->fieldspec['taskdescription']['predeletetrigger'] = '';
         	$this->fieldspec['taskdescription']['postdeletetrigger'] = '';
         	$this->fieldspec['taskdescription']['postinsert'] = 'PosttaskdescriptionInsert';
         	$this->fieldspec['taskdescription']['postupdate'] = 'PosttaskdescriptionUpdate';
         	$this->fieldspec['taskdescription']['postdelete'] = 'PosttaskdescriptionDelete';
         	$this->fieldspec['taskdescription']['preinsert'] = 'PretaskdescriptionInsert';
         	$this->fieldspec['taskdescription']['preupdate'] = 'PretaskdescriptionUpdate';
         	$this->fieldspec['taskdescription']['predelete'] = 'PretaskdescriptionDelete';
         	$this->fieldspec['tasklink']['metadata_id'] = '2888';
         	$this->fieldspec['tasklink']['table_name'] = 'tasks';
         	$this->fieldspec['tasklink']['column_name'] = 'tasklink';
         	$this->fieldspec['tasklink']['pretty_name'] = 'Link for Task/Menu';
         	$this->fieldspec['tasklink']['abstract_data_type'] = 'string';
         	$this->fieldspec['tasklink']['db_data_type'] = 'varchar';
         	$this->fieldspec['tasklink']['field_null'] = 'NO';
         	$this->fieldspec['tasklink']['field_key'] = '0';
         	$this->fieldspec['tasklink']['extra_sql'] = ' ';
         	$this->fieldspec['tasklink']['html_form_type'] = 'text';
         	$this->fieldspec['tasklink']['html_form_options'] = ' ';
         	$this->fieldspec['tasklink']['html_form_explanation'] = ' ';
         	$this->fieldspec['tasklink']['help_text'] = ' ';
         	$this->fieldspec['tasklink']['mandatory_p'] = 'Y';
         	$this->fieldspec['tasklink']['sort_key'] = '0';
         	$this->fieldspec['tasklink']['form_sort_key'] = '0';
         	$this->fieldspec['tasklink']['form_number'] = '1';
         	$this->fieldspec['tasklink']['default_value'] = '';
         	$this->fieldspec['tasklink']['field_toupper'] = 'NO';
         	$this->fieldspec['tasklink']['validationprocname'] = ' ';
         	$this->fieldspec['tasklink']['c_size'] = '45';
         	$this->fieldspec['tasklink']['prikey'] = 'N';
         	$this->fieldspec['tasklink']['noedit'] = 'N';
         	$this->fieldspec['tasklink']['nodisplay'] = 'N';
         	$this->fieldspec['tasklink']['c_unsigned'] = 'N';
         	$this->fieldspec['tasklink']['c_zerofill'] = 'N';
         	$this->fieldspec['tasklink']['c_auto_increment'] = 'N';
         	$this->fieldspec['tasklink']['foreign_table'] = '';
         	$this->fieldspec['tasklink']['foreign_key'] = '';
         	$this->fieldspec['tasklink']['application'] = 'acl';
         	$this->fieldspec['tasklink']['issearchable'] = '1';
         	$this->fieldspec['tasklink']['preinserttrigger'] = '';
         	$this->fieldspec['tasklink']['postinserttrigger'] = '';
         	$this->fieldspec['tasklink']['preupdatetrigger'] = '';
         	$this->fieldspec['tasklink']['postupdatetrigger'] = '';
         	$this->fieldspec['tasklink']['predeletetrigger'] = '';
         	$this->fieldspec['tasklink']['postdeletetrigger'] = '';
         	$this->fieldspec['tasklink']['postinsert'] = 'PosttasklinkInsert';
         	$this->fieldspec['tasklink']['postupdate'] = 'PosttasklinkUpdate';
         	$this->fieldspec['tasklink']['postdelete'] = 'PosttasklinkDelete';
         	$this->fieldspec['tasklink']['preinsert'] = 'PretasklinkInsert';
         	$this->fieldspec['tasklink']['preupdate'] = 'PretasklinkUpdate';
         	$this->fieldspec['tasklink']['predelete'] = 'PretasklinkDelete';
         	$this->fieldspec['taskparent']['metadata_id'] = '2889';
         	$this->fieldspec['taskparent']['table_name'] = 'tasks';
         	$this->fieldspec['taskparent']['column_name'] = 'taskparent';
         	$this->fieldspec['taskparent']['pretty_name'] = 'Parent Menu Item';
         	$this->fieldspec['taskparent']['abstract_data_type'] = 'string';
         	$this->fieldspec['taskparent']['db_data_type'] = 'varchar';
         	$this->fieldspec['taskparent']['field_null'] = 'NO';
         	$this->fieldspec['taskparent']['field_key'] = '0';
         	$this->fieldspec['taskparent']['extra_sql'] = ' ';
         	$this->fieldspec['taskparent']['html_form_type'] = 'text';
         	$this->fieldspec['taskparent']['html_form_options'] = ' ';
         	$this->fieldspec['taskparent']['html_form_explanation'] = ' ';
         	$this->fieldspec['taskparent']['help_text'] = ' ';
         	$this->fieldspec['taskparent']['mandatory_p'] = 'Y';
         	$this->fieldspec['taskparent']['sort_key'] = '0';
         	$this->fieldspec['taskparent']['form_sort_key'] = '0';
         	$this->fieldspec['taskparent']['form_number'] = '1';
         	$this->fieldspec['taskparent']['default_value'] = '';
         	$this->fieldspec['taskparent']['field_toupper'] = 'YES';
         	$this->fieldspec['taskparent']['validationprocname'] = ' ';
         	$this->fieldspec['taskparent']['c_size'] = '45';
         	$this->fieldspec['taskparent']['prikey'] = 'N';
         	$this->fieldspec['taskparent']['noedit'] = 'N';
         	$this->fieldspec['taskparent']['nodisplay'] = 'N';
         	$this->fieldspec['taskparent']['c_unsigned'] = 'N';
         	$this->fieldspec['taskparent']['c_zerofill'] = 'N';
         	$this->fieldspec['taskparent']['c_auto_increment'] = 'N';
         	$this->fieldspec['taskparent']['foreign_table'] = '';
         	$this->fieldspec['taskparent']['foreign_key'] = '';
         	$this->fieldspec['taskparent']['application'] = 'acl';
         	$this->fieldspec['taskparent']['issearchable'] = '1';
         	$this->fieldspec['taskparent']['preinserttrigger'] = '';
         	$this->fieldspec['taskparent']['postinserttrigger'] = '';
         	$this->fieldspec['taskparent']['preupdatetrigger'] = '';
         	$this->fieldspec['taskparent']['postupdatetrigger'] = '';
         	$this->fieldspec['taskparent']['predeletetrigger'] = '';
         	$this->fieldspec['taskparent']['postdeletetrigger'] = '';
         	$this->fieldspec['taskparent']['postinsert'] = 'PosttaskparentInsert';
         	$this->fieldspec['taskparent']['postupdate'] = 'PosttaskparentUpdate';
         	$this->fieldspec['taskparent']['postdelete'] = 'PosttaskparentDelete';
         	$this->fieldspec['taskparent']['preinsert'] = 'PretaskparentInsert';
         	$this->fieldspec['taskparent']['preupdate'] = 'PretaskparentUpdate';
         	$this->fieldspec['taskparent']['predelete'] = 'PretaskparentDelete';
         	$this->fieldlist = array('idtasks', 'tasktype', 'taskdescription', 'tasklink', 'taskparent');
         	$this->searchlist = array('idtasks', 'tasktype', 'taskdescription', 'tasklink', 'taskparent', );
	         return SUCCESS;
         }
         function tasks()
         { //For older php which doesn't have constructor
              return $this->__construct();
         }
         function Push()
         {
	         $_SESSION['tasks'] = serialize($this);
	         return SUCCESS;
         }
         function Pop()
         {
                 //Can't do this in self - this is how to do it outside
	       //  $this = unserialize($_SESSION['tasks']);
	         return SUCCESS;
         }
         function ObserverRegister( $observer, $event )
         {
                 $this->observers[$event][] = $observer;
	         return SUCCESS;
         }
         function ObserverDeRegister( $observer )
         {
                 $this->observers[] = array_diff( $this->observers, array( $observer) );
	         return SUCCESS;
         }
         function ObserverNotify( $event, $msg )
         {
                 if ( isset( $this->observers[$event] ) )
                 	foreach ( $this->observers[$event] as $obs ) 
                 	{
                      		$obs->notify( $event, $msg );
                 	}
                 /* '**' being used as 'ALL' */
                 if ( isset( $this->observers['**'] ) )
                 	foreach ( $this->observers['**'] as $obs ) 
                 	{
                      		$obs->notify( $event, $msg );
                 	}
	         return SUCCESS;
         }
         function notify( $object )
         {
                 //Called when another object we are observing sends us a notification
	         return SUCCESS;
         }
         function PreidtasksInsert( $data )
         {
         $this->data = $data;
                 if ( is_callable( PreidtasksInsert ) )
	            return PreidtasksInsert( $this );
                 else return;
         }
         function PostidtasksInsert( $data, $lastinsert = 0 )
         {
         $this->data = $data;
                 if ( is_callable( PostidtasksInsert ) )
	            return PostidtasksInsert( $this );
                 else return;
         }
         function PreidtasksUpdate( $data )
         {
         $this->data = $data;
                 if ( is_callable( PreidtasksUpdate ) )
	            return PreidtasksUpdate( $this );
                 else return;
         }
         function PostidtasksUpdate( $data, $lastinsert = 0 )
         {
         $this->data = $data;
                 if ( is_callable( PostidtasksUpdate ) )
	            return PostidtasksUpdate( $this );
                 else return;
         }
         function PreidtasksDelete( $data )
         {
         $this->data = $data;
                 if ( is_callable( PreidtasksDelete ) )
	            return PreidtasksDelete( $this );
                 else return;
         }
         function PostidtasksDelete( $data, $lastinsert = 0 )
         {
         $this->data = $data;
                 if ( is_callable( PostidtasksDelete ) )
	            return PostidtasksDelete( $this );
                 else return;
         }
         function Setidtasks($value)
         {
                 $this->idtasks = $value;
	          return SUCCESS;
         }
         function Getidtasks()
         {
                    return $this->idtasks;
         }
         function PretasktypeInsert( $data )
         {
         $this->data = $data;
                 if ( is_callable( PretasktypeInsert ) )
	            return PretasktypeInsert( $this );
                 else return;
         }
         function PosttasktypeInsert( $data, $lastinsert = 0 )
         {
         $this->data = $data;
                 if ( is_callable( PosttasktypeInsert ) )
	            return PosttasktypeInsert( $this );
                 else return;
         }
         function PretasktypeUpdate( $data )
         {
         $this->data = $data;
                 if ( is_callable( PretasktypeUpdate ) )
	            return PretasktypeUpdate( $this );
                 else return;
         }
         function PosttasktypeUpdate( $data, $lastinsert = 0 )
         {
         $this->data = $data;
                 if ( is_callable( PosttasktypeUpdate ) )
	            return PosttasktypeUpdate( $this );
                 else return;
         }
         function PretasktypeDelete( $data )
         {
         $this->data = $data;
                 if ( is_callable( PretasktypeDelete ) )
	            return PretasktypeDelete( $this );
                 else return;
         }
         function PosttasktypeDelete( $data, $lastinsert = 0 )
         {
         $this->data = $data;
                 if ( is_callable( PosttasktypeDelete ) )
	            return PosttasktypeDelete( $this );
                 else return;
         }
         function Settasktype($value)
         {
                 $this->tasktype = $value;
	          return SUCCESS;
         }
         function Gettasktype()
         {
                    return $this->tasktype;
         }
         function PretaskdescriptionInsert( $data )
         {
         $this->data = $data;
                 if ( is_callable( PretaskdescriptionInsert ) )
	            return PretaskdescriptionInsert( $this );
                 else return;
         }
         function PosttaskdescriptionInsert( $data, $lastinsert = 0 )
         {
         $this->data = $data;
                 if ( is_callable( PosttaskdescriptionInsert ) )
	            return PosttaskdescriptionInsert( $this );
                 else return;
         }
         function PretaskdescriptionUpdate( $data )
         {
         $this->data = $data;
                 if ( is_callable( PretaskdescriptionUpdate ) )
	            return PretaskdescriptionUpdate( $this );
                 else return;
         }
         function PosttaskdescriptionUpdate( $data, $lastinsert = 0 )
         {
         $this->data = $data;
                 if ( is_callable( PosttaskdescriptionUpdate ) )
	            return PosttaskdescriptionUpdate( $this );
                 else return;
         }
         function PretaskdescriptionDelete( $data )
         {
         $this->data = $data;
                 if ( is_callable( PretaskdescriptionDelete ) )
	            return PretaskdescriptionDelete( $this );
                 else return;
         }
         function PosttaskdescriptionDelete( $data, $lastinsert = 0 )
         {
         $this->data = $data;
                 if ( is_callable( PosttaskdescriptionDelete ) )
	            return PosttaskdescriptionDelete( $this );
                 else return;
         }
         function Settaskdescription($value)
         {
                 $this->taskdescription = $value;
	          return SUCCESS;
         }
         function Gettaskdescription()
         {
                    return $this->taskdescription;
         }
         function PretasklinkInsert( $data )
         {
         $this->data = $data;
                 if ( is_callable( PretasklinkInsert ) )
	            return PretasklinkInsert( $this );
                 else return;
         }
         function PosttasklinkInsert( $data, $lastinsert = 0 )
         {
         $this->data = $data;
                 if ( is_callable( PosttasklinkInsert ) )
	            return PosttasklinkInsert( $this );
                 else return;
         }
         function PretasklinkUpdate( $data )
         {
         $this->data = $data;
                 if ( is_callable( PretasklinkUpdate ) )
	            return PretasklinkUpdate( $this );
                 else return;
         }
         function PosttasklinkUpdate( $data, $lastinsert = 0 )
         {
         $this->data = $data;
                 if ( is_callable( PosttasklinkUpdate ) )
	            return PosttasklinkUpdate( $this );
                 else return;
         }
         function PretasklinkDelete( $data )
         {
         $this->data = $data;
                 if ( is_callable( PretasklinkDelete ) )
	            return PretasklinkDelete( $this );
                 else return;
         }
         function PosttasklinkDelete( $data, $lastinsert = 0 )
         {
         $this->data = $data;
                 if ( is_callable( PosttasklinkDelete ) )
	            return PosttasklinkDelete( $this );
                 else return;
         }
         function Settasklink($value)
         {
                 $this->tasklink = $value;
	          return SUCCESS;
         }
         function Gettasklink()
         {
                    return $this->tasklink;
         }
         function PretaskparentInsert( $data )
         {
         $this->data = $data;
                 if ( is_callable( PretaskparentInsert ) )
	            return PretaskparentInsert( $this );
                 else return;
         }
         function PosttaskparentInsert( $data, $lastinsert = 0 )
         {
         $this->data = $data;
                 if ( is_callable( PosttaskparentInsert ) )
	            return PosttaskparentInsert( $this );
                 else return;
         }
         function PretaskparentUpdate( $data )
         {
         $this->data = $data;
                 if ( is_callable( PretaskparentUpdate ) )
	            return PretaskparentUpdate( $this );
                 else return;
         }
         function PosttaskparentUpdate( $data, $lastinsert = 0 )
         {
         $this->data = $data;
                 if ( is_callable( PosttaskparentUpdate ) )
	            return PosttaskparentUpdate( $this );
                 else return;
         }
         function PretaskparentDelete( $data )
         {
         $this->data = $data;
                 if ( is_callable( PretaskparentDelete ) )
	            return PretaskparentDelete( $this );
                 else return;
         }
         function PosttaskparentDelete( $data, $lastinsert = 0 )
         {
         $this->data = $data;
                 if ( is_callable( PosttaskparentDelete ) )
	            return PosttaskparentDelete( $this );
                 else return;
         }
         function Settaskparent($value)
         {
                 $this->taskparent = $value;
	          return SUCCESS;
         }
         function Gettaskparent()
         {
                    return $this->taskparent;
         }
} /* class tasks */
