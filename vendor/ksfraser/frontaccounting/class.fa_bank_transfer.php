<?php
//$page_security = 'SA_BANKTRANSFER';
$path_to_root = "../..";

//include_once($path_to_root . "/includes/session.inc");

include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/data_checks.inc");

include_once($path_to_root . "/gl/includes/gl_db.inc");
include_once($path_to_root . "/gl/includes/gl_ui.inc");

require_once( '../ksf_modules_common/class.origin.php' );	//This needs to be replaced with composer namespace ksfraser/origin/...

/*
*	$js = "";
*	if ($SysPrefs->use_popup_windows)
*	        $js .= get_js_open_window(800, 500);
*	if (user_use_date_picker())
*	        $js .= get_js_date_picker();
*	
*	if (isset($_GET['ModifyTransfer'])) {
*	        $_SESSION['page_title'] = _($help_context = "Modify Bank Account Transfer");
*	} else {
*	        $_SESSION['page_title'] = _($help_context = "Bank Account Transfer Entry");
*	}
*	
*	page($_SESSION['page_title'], false, false, "", $js);
*	
*	check_db_has_bank_accounts(_("There are no bank accounts defined in the system."));
*/

class fa_bank_transfer extends origin
{
	protected $trans_no;
	protected $transfer_type;
	protected $ref;
	protected $memo_;
	protected $FromBankAccount;
	protected $ToBankAccount;
	protected $charge;
	protected $amount;
	protected $target_amount;
	protected $DatePaid;

	function __construct()
	{
		$this->set( "memo_", '' );
		$this->set( "FromBankAccount", 0 );
		$this->set( "ToBankAccount", 0 );
		$this->set( "amount", 0 );
	}

	function set( $field, $value = NULL, $enforce_only_native_vars = true )
	{
		switch( $field )
		{
			case "charge":
			case "amount":
			case "target_amount":
				$value = price_format( $value );
			break;
			case "DatePaid":
				$value = sql2date( $value );
				if ( !is_date_in_fiscalyear( $value ) )
				{
                        		$value = end_fiscalyear();
				}
			break;
		}
		parent::set( $field, $value, $enforce_only_native_vars );
	}
	function add_bank_transfer()
	{
		$trans_no = add_bank_transfer(	
						$this->FromBankAccount,
						$this->ToBankAccount, 
						$this->DatePaid,
						$this->amount, 
						$this->ref, 
						$this->memo_, 
						$this->charge, 
						$this->target_amount
			);
		$this->set( "trans_no", $trans_no );
		return $trans_no;
	}
	function update_bank_transfer()
	{
		$trans_no = update_bank_transfer(	$this->trans_no, 
						$this->FromBankAccount,
						$this->ToBankAccount, 
						$this->DatePaid,
						$this->amount, 
						$this->ref, 
						$this->memo_, 
						$this->charge, 
						$this->target_amount
			);
		$this->set( "trans_no", $trans_no );
		return $trans_no;
	}
}

class fa_bank_accounts_MODEL
{
	protected $bank_account_name;
	protected $bank_curr_code;
	protected $inactive;

	function __construct()
	{
	}
	/**//***************************************************************
	*
	*
	*	Replacing from includes/ui/ui_lists.inc
	*
	* @param
	* @return
	*******************************************************************/
	function  bank_accounts_list_sql()
	{
		return "SELECT id, bank_account_name, bank_curr_code, inactive FROM ".TB_PREF."bank_accounts";
	}
	/**//***************************************************************
	*
	*
	*	Replacing from includes/ui/ui_lists.inc
	*
	* @param
	* @return
	*******************************************************************/
	function  cash_accounts_list_sql()
	{
		return "SELECT id, bank_account_name, bank_curr_code, inactive FROM ".TB_PREF."bank_accounts WHERE account_type=".BT_CASH;
	}
}


class fa_bank_accounts_VIEW
{
	protected $name;
	protected $label;
	protected $selected_id;
	protected $submit_on_change;
	protected $spec_option;
	protected $spec_id;
	protected $MODEL;	//!< fa_bank_accounts_MODEL
	protected $all_option;	//!<bool
	protected $sql;
	protected $format;
	protected $async; 	//!<bool
	protected $combo_valfield;
	protected $combo_namefield;

	function __construct( $MODEL )
	{
		$this->set( "MODEL", $MODEL );
	}
	/**//***************************************************************
	*
	*
	*	Replacing from includes/ui/ui_lists.inc
	*
	* @param
	* @return
	*******************************************************************/
	function bank_accounts_list($name, $selected_id=null, $submit_on_change=false, $spec_option=false)
	{
		$sql = $this->MODEL->bank_accounts_list_sql(); 
		$this->set( "name", $name );
		$this->set( "selected_id", $selected_id );
		$this->set( "submit_on_change", $submit_on_change );
		$this->set( "spec_option", $spec_option );

		$this->set( "spec_id", '' );
		$this->set( 'format', '_format_add_curr' );
		$this->set( "async", false );
		$this->set( "combo_valfield", 'id' );
		$this->set( "combo_namefield", 'bank_account_name' );
		$sql = $this->MODEL->cash_accounts_list_sql(); 
		$this->set( "sql", $sql );
		$this->combo_input();
	}
	/**//***************************************************************
	*
	*
	*	Replacing from includes/ui/ui_lists.inc
	*
	* @param NONE expect fields to be set.  Label, Name, selected_id, submit_on_change.
	* @return
	*******************************************************************/

	function bank_accounts_list_cells()
	{
		if( null !== $this->label )
		{
			$this->label_cell();
		}
		$this->td();
        	echo bank_accounts_list($name, $selected_id, $submit_on_change);
		$this->close_td();
		$this->newline();
	}
	/**//***************************************************************
	*
	*
	*	Replacing from includes/ui/ui_lists.inc
	*
	* @param
	* @return
	*******************************************************************/

	function bank_accounts_list_row($label, $name, $selected_id=null, $submit_on_change=false)
	{
		$this->set( "label", $label );
		$this->set( "name", $name );
		$this->set( "selected_id", $selected_id );
		$this->set( "submit_on_change", $submit_on_change );
		$this->row_label();
		$this->set( "label", null );
        	$this->bank_accounts_list_cells();
		$this->close_tr();
		$this->newline();
	}
	/**//***************************************************************
	*
	*
	* @param NONE
	* @return echo'd string
	*******************************************************************/
	function combo_input()
	{
        	echo combo_input(	$this->name, 
					$this->selected_id, 
					$this->sql, 
					$this->combo_valfield,
					$this->combo_namefield,
                			array(
                        			'spec_option' => $this->spec_option,
                        			'spec_id' => $this->spec_id,
                        			'format' => $tihs->format,
                        			'select_submit'=> $this->submit_on_change,
                        			'async' => $this->async
                			) 
		);
	}
	/**//***************************************************************
	*
	*
	*	Replacing from includes/ui/ui_lists.inc
	*
	* @param
	* @return
	*******************************************************************/
	function cash_accounts_list_row($label, $name, $selected_id=null, $submit_on_change=false, $all_option=false)
	{
		$this->set( "label", $label );
		$this->set( "name", $name );
		$this->set( "selected_id", $selected_id );
		$this->set( "submit_on_change", $submit_on_change );
		$this->set( "spec_option", $all_option );
		$this->set( "spec_id", ALL_TEXT );
		$this->set( 'format', '_format_add_curr' );
		$this->set( "async", true );
		$this->set( "combo_valfield", 'id' );
		$this->set( "combo_namefield", 'bank_account_name' );

		$this->row_label();
		$this->td();
		$sql = $this->MODEL->cash_accounts_list_sql(); 
		$this->set( "sql", $sql );

		$this->combo_input();

		$this->close_td();
		$this->close_tr();
		$this->newline();
	}
	/**//***************************************************************
	*
	*
	* @param
	* @return
	*******************************************************************/
	function row_label()
	{
        	$this->tr();
		$this->label_cell();
	}
	/**//***************************************************************
	*
	*
	* @param
	* @return
	*******************************************************************/
	function label_cell()
	{
        	echo "<td class='label'>$this->label</td>";
	}
	/**//***************************************************************
	*
	*
	* @param
	* @return
	*******************************************************************/
	function tr()
	{
        	echo "<td>";
	}
	/**//***************************************************************
	*
	*
	* @param
	* @return
	*******************************************************************/
	function td()
	{
        	echo "<td>";
	}
	/**//***************************************************************
	*
	*
	* @param
	* @return
	*******************************************************************/
	function close_td()
	{
        	echo "</td>";
	}
	/**//***************************************************************
	*
	*
	* @param
	* @return
	*******************************************************************/
	function close_tr()
	{
        	echo "</tr>";
	}
	/**//***************************************************************
	*
	*
	* @param
	* @return
	*******************************************************************/
	function newline()
	{
        	echo "\n";
	}

}


class view_bank_transfer
{
	protected $MODEL;

	function __construct( $MODEL )
	{
		$this->set( "MODEL", $MODEL );
	}
	function new_transfer_form()
	{
		start_form(); 
		start_outer_table(TABLESTYLE2); 
		table_section(1);
		//bank_accounts_list_row($label, $name, $selected_id=null, $submit_on_change=false)
		bank_accounts_list_row(_("From Account:"), 'FromBankAccount', null, true); 
		bank_balance_row( $this-MODEL-get( 'FromBankAccount') ); 
		bank_accounts_list_row(_("To Account:"), 'ToBankAccount', null, true); 

		$DatePaid = $this->MODEL->get( 'DatePaid');
		if ( !isset( $DatePaid ) OR ("" == $DatePaid ) )
		{ // init page
                	$this->MODEL->set( 'DatePaid', new_doc_date() );
			$DatePaid = $this->MODEL->get( 'DatePaid');
        	}
		$TransferType = ST_BANKTRANSFER;
		//$TransferType = $this->MODEL->get( 'transfer_type' );
		date_row(_("Transfer Date:"), 'DatePaid', '', true, 0, 0, 0, null, true);
		ref_row(	_("Reference:"), 
				'ref', 
				'', 
				$Refs->get_next(
					$TransferType, 
					null, 
					$DatePaid
				), 
				false, 
				$TransferType, 
				array(	'date' => $DatePaid )
		); 
		table_section(2); 
		$from_currency = get_bank_account_currency($this->MODEL->get( 'FromBankAccount' ) ); 
		$to_currency = get_bank_account_currency($this->MODEL->get( 'ToBankAccount' ) );
		//Is there a reason to NOT show currency indicators on amount/charge?  
		if ($from_currency != "" && $to_currency != "" && $from_currency != $to_currency )
		{
                	amount_row(_("Amount:"), 'amount', null, null, $from_currency);
                	amount_row(_("Bank Charge:"), 'charge', null, null, $from_currency);
                	amount_row(_("Incoming Amount:"), 'target_amount', null, '', $to_currency, 2); 
		} else 
		{
                	amount_row(_("Amount:"), 'amount');
                	amount_row(_("Bank Charge:"), 'charge'); 
		} 
		textarea_row(_("Memo:"), 'memo_', null, 40,4); 
		end_outer_table(1); // outer table 
		if ($trans_no) 
		{ 
			hidden('_trans_no', $trans_no); 
			submit_center('submit', _("Modify Transfer"), true, '', 'default'); 
		} else {
                	submit_center('submit', _("Enter Transfer"), true, '', 'default'); 
		} 
		end_form();
	}
}


?>
