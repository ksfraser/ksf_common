<?php

/**************************************************************************
*
*	CONTROLLER
*
*	20221024 Incorporate some SugarController code
*		I probably just broke things.  Sugar uses view as a string as opposed to a class
*		I reset view and model to VIEW and MODEL so inheriting classes might now be broken :(
*
**************************************************************************/
global $path_to_ksfcommon;
require_once( $path_to_ksfcommon . '/db_base.php' );
require_once( $path_to_ksfcommon . '/class.VIEW.php' );

class controller extends db_base
{
	var $mode;
	var $action;
	var $selected_id;
	var $mode_callbacks = array();
	var $VIEW;
	var $MODEL;
    /**
     * @var array $action_remap
     * remap actions in here
     * e.g. make all detail views go to edit views
     * $action_remap = array('detailview'=>'editview');
     */
    protected $action_remap = array('index' => 'listview');

    /**
     * @var string $module
     * The name of the current module.
     */
    public $module = 'Home';

    /**
     * @var string|null
     * The name of the target module.
     */
    public $target_module = null;
   /**
     * @var string $record
     * The id of the current record.
     */
    public $record = '';

    /**
     * @var string|null $return_module
     * The name of the return module.
     */
    public $return_module = null;

    /**
     * @var string|null $return_action
     * The name of the return action.
     */
    public $return_action = null;

    /**
     * @var string|null $return_id uuid
     * The id of the return record.
     */
    public $return_id = null;

    /**
     * @var string $do_action
     * If the action was remapped it will be set to do_action and then we will just
     * use do_action for the actual action to perform.
     */
    protected $do_action = 'index';

    /**
     * @var SugarBean|null $bean
     * If a bean is present that set it.*
     */
    public $bean = null;

    /**
     * @var string $redirect_url
     * url to redirect to
     */
    public $redirect_url = '';

    /**
     * @var string $view
     * any subcontroller can modify this to change the view
     */
    public $view = 'classic';

    /**
     * @var array $view_object_map
     * this array will hold the mappings between a key and an object for use within the view.
     */
    public $view_object_map = array();

    /**
     * This array holds the methods that handleAction() will invoke, in sequence.
     */
    protected $tasks = array(
        'pre_action',
        'do_action',
        'post_action'
    );
   /**
     * @var array $process_tasks
     * List of options to run through within the process() method.
     * This list is meant to easily allow additions for new functionality as well as
     * the ability to add a controller's own handling.
     */
    public $process_tasks = array(
        'blockFileAccess',
        'handleEntryPoint',
        'callLegacyCode',
        'remapAction',
        'handle_action',
        'handleActionMaps',
    );
    /**
     * Whether or not the action has been handled by $process_tasks
     *
     * @var bool
     */
    protected $_processed = false;
    /**
     * Map an action directly to a file
     */
    /**
     * Map an action directly to a file. This will be loaded from action_file_map.php
     */
    protected $action_file_map = array();
    /**
     * Map an action directly to a view
     */
    /**
     * Map an action directly to a view. This will be loaded from action_view_map.php
     */
    protected $action_view_map = array();

    /**
     * This can be set from the application to tell us whether we have authorization to
     * process the action. If this is set we will default to the noaccess view.
     *@var bool
     */
    public $hasAccess ;

    /**
     * Map case sensitive filenames to action.  This is used for linux/unix systems
     * where filenames are case sensitive
     */
    public static $action_case_file = array(
        'editview' => 'EditView',
        'detailview' => 'DetailView',
        'listview' => 'ListView'
    );

   /**
     * Constructor (Suite). This ie meant to load up the module, action, record as well
     * as the mapping arrays.
     */

	function __construct( $host, $user, $pass, $database, $prefs_tablename )
	{
		parent::__construct( $host, $user, $pass, $database, $prefs_tablename );
		$this->hasAccess = true;

		if( isset( $_POST['Mode'] ) )
			$this->set_var( "mode", $_POST['Mode'] );
		else
			$this->set_var( "mode", "unknown" );
		if( isset( $_POST['action'] ) )
			$this->set_var( "action", $_POST['action'] );
		else
		if( isset( $_GET['action'] ) )
			$this->set_var( "action", $_GET['action'] );

		if( isset( $_POST['selected_id'] ) )
			$this->set_var( "selected_id", $_POST['selected_id'] );
		$this->VIEW = new VIEW();
		$this->MODEL = NULL;
		/*********************************
		*	Need to set mode_callbacks
		*	in inheriting classes
		*********************************/
		$this->mode_callbacks["unknown"] = "config_form";
           
		$this->config_values[] = array( 'pref_name' => 'mode', 'label' => 'Mode' );

                //The forms/actions for this module
                //Hidden tabs are just action handlers, without accompying GUI elements.
                //$this->tabs[] = array( 'title' => '', 'action' => '', 'form' => '', 'hidden' => FALSE );
                $this->tabs[] = array( 'title' => 'Configuration', 'action' => 'config', 'form' => 'config_form', 'hidden' => FALSE );
       
	}
        function loadprefs( $prefarr = NULL )
        {
		if( isset( $prefarr ) )
		{
                	foreach( $prefarr as $row )
                	{
                	        $this->set_var( $row['pref_name'], $this->get_pref( $row['pref_name'] ) );
                	}
		}
		else
		{
                	// Get last oID exported
                	foreach( $this->config_values as $row )
                	{
                	        $this->set_var( $row['pref_name'], $this->get_pref( $row['pref_name'] ) );
                	}
		}
        }
        function updateprefs( $prefarr = NULL )
        {
                foreach( $this->config_values as $row )
                {
                        if( isset( $_POST[$row['pref_name']] ) )
                        {
                                $this->set_var( $row['pref_name'], $_POST[ $row['pref_name'] ] );
                                $this->set_pref( $row['pref_name'], $_POST[ $row['pref_name'] ] );
                        }
			else if( isset( $this->$row['pref_name'] ) )
			{
                                $this->set_pref( $row['pref_name'], $this->$row['pref_name'] );
			}
                }
		if( isset( $prefarr ) )
		{
			echo "updateprefs <br />";
			//var_dump( $prefarr );
                	foreach( $prefarr as $row )
                	{
				echo $row['pref_name'] . "<br />";
                	        if( isset( $_POST[$row['pref_name']] ) )
                	        {
                	                $this->set_var( $row['pref_name'], $_POST[ $row['pref_name'] ] );
                	                $this->set_pref( $row['pref_name'], $_POST[ $row['pref_name'] ] );
					echo "Field " . $row['pref_name'] . " set to " . $_POST[ $row['pref_name'] ];
					echo "<br />";
					//display_notification( "Field " . $row['pref_name'] . " set to " . $_POST[ $row['pref_name'] ] );
                	        }
				else if( isset( $this->$row['pref_name'] ) )
				{
                        	        $this->set_pref( $row['pref_name'], $this->$row['pref_name'] );
					echo "Field " . $row['pref_name'] . " set to " . $this->$row['pref_name'];
					echo "<br />";
				}
				else
				{
					//display_notification( "Post " . $row['pref_name'] . " not set <br />" );
					//echo "Neither var nor Post " . $row['pref_name'] . " not set <br />";
					//var_dump( $this->$row['pref_name'] );
				}
                	}
		}
        }
        function checkprefs()
        {
                $this->updateprefs();
        }
        function install()
        {
                $this->create_prefs_tablename();
                $this->loadprefs();
                $this->updateprefs();
                if( isset( $this->redirect_to ) )
                {
                        header("Location: " . $this->redirect_to );
                }
        }
        function config_form()
        {
                start_form(true);
                start_table(TABLESTYLE2, "width=40%");
                $th = array("Config Variable", "Value");
                table_header($th);
                $k = 0;
                alt_table_row_color($k);
                        /* To show a labeled cell...*/
                        //label_cell("Table Status");
                        //if ($this->found) $table_st = "Found";
                        //else $table_st = "<font color=red>Not Found</font>";
                        //label_cell($table_st);
                        //end_row();
/*
                echo combo_input("order_no2", $this->order_no, $sql, 'supp_name', 'order_no',
                        array(
                                //'format' => '_format_add_curr',
                                'order' => array('order_no'),
                                //'search_box' => $mode!=0,
                                'type' => 1,
                                //'search' => array("order_no","supp_name"),
                                //'spec_option' => $spec_option === true ? _("All Suppliers") : $spec_option,
                                'spec_id' => $all_items,
                                'select_submit'=> $submit_on_change,
                                'async' => false,
                                //'sel_hint' => $mode ? _('Press Space tab to filter by name fragment') :
                                //_('Select supplier'),
                                //'show_inactive'=>$all
                        )
                );
*/
                //This currently only puts text boxes on the config screen!
                foreach( $this->config_values as $row )
                {
                                text_row($row['label'], $row['pref_name'], $this->$row['pref_name'], 20, 40);
                }
                end_table(1);
                if (!$this->found) {
                    hidden('action', 'create');
                    submit_center('create', 'Create Table');
                } else {
                    hidden('action', 'update');
                    submit_center('update', 'Update Configuration');
                }
                end_form();
        }

        function related_tabs()
        {
                $action = $this->action;
                foreach( $this->tabs as $tab )
                {
                        if( $action == $tab['action'] )
                        {
                                echo $tab['title'];
                                echo '&nbsp;|&nbsp;';
                        }
                        else
                        {
                                if( $tab['hidden'] == FALSE )
                                {
                                        hyperlink_params($_SERVER['PHP_SELF'],
                                                _("&" .  $tab['title']),
                                                "action=" . $tab['action'],
                                                false);
                                        echo '&nbsp;|&nbsp;';
                                }
                        }
                }
        }
        function show_form()
        {
                $action = $this->action;
                foreach( $this->tabs as $tab )
                {
                        if( $action == $tab['action'] )
                        {
                                //Call appropriate form
                                $form = $tab['form'];
                                $this->$form();
                        }
                }
        }
	function add_addons()
	{
                $addondir = "./addons/";
                foreach (glob("{$addondir}/config.*.php") as $filename)
                {
                        //echo "opening module config file " . $filename . "<br />\n";
                        include_once( $filename );
                }

                /*
                 * Loop through the $configArray to set loading modules in right order
                 */
                foreach( $configArray as $carray )
                {
                        $modarray[$carray['loadpriority']][] = $carray;
                }
	        /*
                 * locate Module class files to open
                 */
                foreach( $modarray as $priarray )
                {
                        foreach( $priarray as $marray )
                        {

                                $res = include_once( $addondir . "/" . $marray['loadFile'] );
                                if( TRUE == $res )
                                {
                                        $marray['objectName'] = new $marray['className'];
                                        if( isset( $marray['objectName']->observers ) )
                                        {
                                                foreach( $marray['objectName']->observers as $obs )
                                                {
                                                        $this->observers[] = $obs;
                                                }
                                        }
                                }
                                else
                                {
                                        echo "Attempt to open " . $addondir . "/" . $marray['loadFile'] . " FAILED!<br />";
                                }
                        }
                }
	}
	function valuesarray2table( $array )
	{
		foreach( $array as $row )
		{
			if( isset( $row['type'] ) )
			{
				switch( $row['type'] ) {
	
					case "bool":
							//$this->VIEW->bool( $row, $this );
							$this->VIEW->textrow( $row, $this );
							break;
					case "flag":
							break;
					case "addr":
					case "city":
					case "prov":
					case "country":
							$this->VIEW->textrow( $row, $this );
							break;
					case "postal":
							break;
					case "date":
							//$this->VIEW->date( $row, $this );
							$this->VIEW->textrow( $row, $this );
							break;
					case "text":
					case "currency":
							break;
					case "int":
							$this->VIEW->number( $row, $this );
							break;
					default:
							$this->VIEW->textrow( $row, $this );
							break;
				}
			}
			else
			{
				$this->VIEW->textrow( $row, $this );
			}
		}
		$this->VIEW->end_table();
	}
	function run()
	{
                if ($this->found) {
                        $this->loadprefs();
                }
                else
                {
                        $this->install();
                        $this->set_var( 'action', "show" );
                }

		$result = $this->MODEL->get_all_rows();

		$this->VIEW->new_table();
		//These should come from the data dictionary having:
		//	Readable name, database column name, data type
		$this->VIEW->header_row = $this->MODEL->header_row;
		$this->VIEW->col_type = $this->MODEL->col_type;
		$this->VIEW->db_column_name = $this->MODEL->db_column_name;
		$this->VIEW->db_result = $result;
		$this->VIEW->db_result2rows();
		$this->VIEW->end_table();

		if( isset($this->mode) )
		{
			if( is_callable( $this->mode_callbacks[$this->mode], $this ) )
			{
				//echo "CALLABLE::" . $this->mode . "::" . $this->mode_callbacks[$this->mode] . "<br />";
				$fcn = $this->mode_callbacks[$this->mode];
				$this->$fcn();
			}
			else
			{
				$this->VIEW->display_notification( "error in action definition" );
			}
		}
		else
		{
			$this->VIEW->display_notification( "mode not set!" );
		}
                $this->related_tabs();
                $this->show_form();
		$this->VIEW->end_page();
	}
	function screen_mode_unknown()
	{
		if( isset( $this->MODEL->db_pager_sql ) )
		{
			$this->VIEW->db_pager( $this->MODEL );
		}
		echo "screen_unknown";
		$this->config_form();
	}
	function go_install()
	{
/*
*/
	}

    /**
     * Called from SugarApplication and is meant to perform the setup operations
     * on the controller.
     *
	* From SuiteCRM
     */
    public function setup($module = '')
    {
        if (empty($module) && !empty($_REQUEST['module'])) {
            $module = $_REQUEST['module'];
        }
        //set the module
        if (!empty($module)) {
            $this->set( "module", $module );
        }

        if (!empty($_REQUEST['target_module']) && $_REQUEST['target_module'] != 'undefined') {
            $this->target_module = $_REQUEST['target_module'];
        }
        //set properties on the controller from the $_REQUEST
        $this->loadPropertiesFromRequest();
        //load the mapping files
        $this->loadMappings();
        /**
         * @see SugarController::allowAction()
         */
        $this->allowAction($this->action);
    }
    /**
     * Set properties on the Controller from the $_REQUEST
	* From SuiteCRM
     *
     */
    private function loadPropertiesFromRequest()
    {
        if (!empty($_REQUEST['action'])) {
            $this->action = $_REQUEST['action'];
        }
        if (!empty($_REQUEST['record'])) {
            $this->record = $_REQUEST['record'];
        }
        if (!empty($_REQUEST['VIEW'])) {
            $this->VIEW = $_REQUEST['VIEW'];
        }
        if (!empty($_REQUEST['return_module'])) {
            $this->return_module = $_REQUEST['return_module'];
        }
        if (!empty($_REQUEST['return_action'])) {
            $this->return_action = $_REQUEST['return_action'];
        }
        if (!empty($_REQUEST['return_id'])) {
            $this->return_id = $_REQUEST['return_id'];
        }
    }
    /**
     * Load map files for use within the Controller
     *
     */
    private function loadMappings()
    {
        $this->loadMapping('action_view_map');
        $this->loadMapping('action_file_map');
        $this->loadMapping('action_remap', true);
    }

    /**
     * Allows action to be pass XSS protection check provide that the action exists in the SugarController
     *
     * @param string $action
     */
    protected function allowAction($action)
    {
        if ($this->hasFunction($this->getActionMethodName())) {
            $GLOBALS['sugar_config']['http_referer']['actions'][] = $action;
        } else {
        }
    }

    /**
     * Given a record id load the bean. This bean is accessible from any sub controllers.
     */
    public function loadBean()
    {
        if (!empty($GLOBALS['beanList'][$this->module])) {
            $class = $GLOBALS['beanList'][$this->module];
            if (!empty($GLOBALS['beanFiles'][$class])) {
                require_once($GLOBALS['beanFiles'][$class]);
                $this->bean = new $class();
                if (!empty($this->record)) {
                    $this->bean->retrieve($this->record);
                    if ($this->bean) {
                        $GLOBALS['FOCUS'] = $this->bean;
                    }
                }
            }
        }
    }
// ******************************************************************************
    /**
     * Generic load method to load mapping arrays.
     *   /
    private function loadMapping($var, $merge = false)
    {
        $$var = sugar_cache_retrieve("CONTROLLER_" . $var . "_" . $this->module);
        if (!$$var) {
            if ($merge && !empty($this->$var)) {
                $$var = $this->$var;
            } else {
                $$var = array();
            }
            if (file_exists('include/MVC/Controller/' . $var . '.php')) {
                require('include/MVC/Controller/' . $var . '.php');
            }
            if (file_exists('modules/' . $this->module . '/' . $var . '.php')) {
                require('modules/' . $this->module . '/' . $var . '.php');
            }
            if (file_exists('custom/modules/' . $this->module . '/' . $var . '.php')) {
                require('custom/modules/' . $this->module . '/' . $var . '.php');
            }
            if (file_exists('custom/include/MVC/Controller/' . $var . '.php')) {
                require('custom/include/MVC/Controller/' . $var . '.php');
            }

            // entry_point_registry -> EntryPointRegistry

            $varname = str_replace(" ", "", ucwords(str_replace("_", " ", $var)));
            if (file_exists("custom/application/Ext/$varname/$var.ext.php")) {
                require("custom/application/Ext/$varname/$var.ext.php");
            }
            if (file_exists("custom/modules/{$this->module}/Ext/$varname/$var.ext.php")) {
                require("custom/modules/{$this->module}/Ext/$varname/$var.ext.php");
            }

            sugar_cache_put("CONTROLLER_" . $var . "_" . $this->module, $$var);
        }
        $this->$var = $$var;
    }
/ ******************************************************************************//

    /**
     * This method is called from SugarApplication->execute and it will bootstrap the entire controller process
     */
    final public function execute()
    {
        try {
            $this->process();
            if (!empty($this->view)) {
                $this->processView();
            } elseif (!empty($this->redirect_url)) {
                $this->redirect();
            }
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }
    protected function showException(Exception $e)
    {
        if ($prev = $e->getPrevious()) {
            $this->showException($prev);
        }
    }

    /**
     * Handle exception
     * @param Exception $e
     */
    protected function handleException(Exception $e)
    {
        $this->showException($e);
        $logicHook = new LogicHook();

        if (isset($this->bean)) {
            $logicHook->setBean($this->bean);
            $logicHook->call_custom_logic($this->bean->module_dir, "handle_exception", $e);
        } else {
            $logicHook->call_custom_logic('', "handle_exception", $e);
        }
    }
     /**
     * Display the appropriate view.
     */
    private function processView()
    {
        if (!isset($this->view_object_map['remap_action']) && isset($this->action_view_map[strtolower($this->action)])) {
            $this->view_object_map['remap_action'] = $this->action_view_map[strtolower($this->action)];
        }
        $view = ViewFactory::loadView(
            $this->view,
            $this->module,
            $this->bean,
            $this->view_object_map,
            $this->target_module
        );
        $GLOBALS['current_view'] = $view;
        if (!empty($this->bean) && !$this->bean->ACLAccess($view->type) && $view->type != 'list') {
            ACLController::displayNoAccess(true);
            sugar_cleanup(true);
        }
        if (isset($this->errors)) {
            $view->errors = $this->errors;
        }
        $view->process();
    }

    /**
     * Meant to be overridden by a subclass and allows for specific functionality to be
     * injected prior to the process() method being called.
     */
    public function preProcess()
    {
    }

    /**
     * if we have a function to support the action use it otherwise use the default action
     *
     * 1) check for file
     * 2) check for action
     */
    public function process()
    {
        $GLOBALS['action'] = $this->action;
        $GLOBALS['module'] = $this->module;

        //check to ensure we have access to the module.
        if ($this->hasAccess) {
            $this->do_action = $this->action;

            $file = self::getActionFilename($this->do_action);

            $this->loadBean();

            $processed = false;
            if (!$this->_processed) {
                foreach ($this->process_tasks as $process) {
                    $this->$process();
                    if ($this->_processed) {
                        break;
                    }
                }
            }

            $this->redirect();
        } else {
            $this->no_access();
        }
    }
   }

    /**
     * This method is called from the process method. I could also be called within an action_* method.
     * It allows a developer to override any one of these methods contained within,
     * or if the developer so chooses they can override the entire action_* method.
     *
     * @return true if any one of the pre_, do_, or post_ methods have been defined,
     * false otherwise.  This is important b/c if none of these methods exists, then we will run the
     * action_default() method.
     */
    protected function handle_action()
    {
        $processed = false;
        foreach ($this->tasks as $task) {
            $processed = ($this->$task() || $processed);
        }
        $this->_processed = $processed;
    }

    /**
     * Perform an action prior to the specified action.
     * This can be overridde in a sub-class
     */
    private function pre_action()
    {
        $function = $this->getPreActionMethodName();
        if ($this->hasFunction($function)) {
            $this->$function();

            return true;
        }

        return false;
    }

    /**
     * Perform the specified action.
     * This can be overridde in a sub-class
     */
    private function do_action()
    {
        $function = $this->getActionMethodName();
        if ($this->hasFunction($function)) {
            $this->$function();

            return true;
        }

        return false;
    }

    /**
     * Perform an action after to the specified action has occurred.
     * This can be overridde in a sub-class
     */
    private function post_action()
    {
        $function = $this->getPostActionMethodName();
        if ($this->hasFunction($function)) {
            $this->$function();

            return true;
        }

        return false;
    }

    /**
     * If there is no action found then display an error to the user.
     */
    protected function no_action()
    {
        sugar_die(sprintf($GLOBALS['app_strings']['LBL_NO_ACTION'], $this->do_action));
    }

    /**
     * The default action handler for instances where we do not have access to process.
     */
    protected function no_access()
    {
        $this->view = 'noaccess';
    }
    ///////////////////////////////////////////////
    /////// HELPER FUNCTIONS
    ///////////////////////////////////////////////

    /**
     * Determine if a given function exists on the objects
     * @param function - the function to check
     * @return true if the method exists on the object, false otherwise
     */
    protected function hasFunction($function)
    {
        return method_exists($this, $function);
    }

    /**
     * @param $action
     * @return string
     */
    protected function getPreActionMethodName()
    {
        return 'pre_' . strtolower( $this->action );
    }

    /**
     * @param $action
     * @return string
     */
    protected function getActionMethodName()
    {
        return 'action_' . strtolower($this->do_action);
    }

    /**
     * @param $action
     * @return string
     */
    protected function getPostActionMethodName()
    {
        return 'post_' . strtolower($this->action);
    }

    /**
     * Set the url to which we will want to redirect
     *
     * @param string url - the url to which we will want to redirect
     */
    protected function set_redirect($url)
    {
        $this->redirect_url = $url;
    }

    /**
     * Perform redirection based on the redirect_url
     *
     */
    protected function redirect()
    {
        if (!empty($this->redirect_url)) {
            SugarApplication::redirect($this->redirect_url);
        }
    }

    ////////////////////////////////////////////////////////
    ////// DEFAULT ACTIONS
    ///////////////////////////////////////////////////////

    /*
     * Save a bean
     */

    /**
     * Do some processing before saving the bean to the database.
     */
    public function pre_save()
    {
        if (!empty($_POST['assigned_user_id']) && $_POST['assigned_user_id'] != $this->bean->assigned_user_id && $_POST['assigned_user_id'] != $GLOBALS['current_user']->id && empty($GLOBALS['sugar_config']['exclude_notifications'][$this->bean->module_dir])) {
            $this->bean->notify_on_save = true;
        }
        require_once('include/SugarFields/SugarFieldHandler.php');
        $sfh = new SugarFieldHandler();
        foreach ($this->bean->field_defs as $field => $properties) {
            $type = !empty($properties['custom_type']) ? $properties['custom_type'] : $properties['type'];
            $sf = $sfh::getSugarField(ucfirst($type), true);
            if (isset($_POST[$field])) {
                if (is_array($_POST[$field]) && !empty($properties['isMultiSelect'])) {
                    if (empty($_POST[$field][0])) {
                        unset($_POST[$field][0]);
                    }
                    $_POST[$field] = encodeMultienumValue($_POST[$field]);
                }
                $this->bean->$field = $_POST[$field];
            } else {
                if (!empty($properties['isMultiSelect']) && !isset($_POST[$field]) && isset($_POST[$field . '_multiselect'])) {
                    $this->bean->$field = '';
                }
            }
            if ($sf != null) {
                $sf->save($this->bean, isset($_POST) ? $_POST : null, $field, $properties);
            }
        }

        foreach ($this->bean->relationship_fields as $field => $link) {
            if (!empty($_POST[$field])) {
                $this->bean->$field = $_POST[$field];
            }
        }
        if (!$this->bean->ACLAccess('save')) {
            ACLController::displayNoAccess(true);
            sugar_cleanup(true);
        }
    }

    /**
     * Perform the actual save
     */
    public function action_save()
    {
        $this->bean->save(!empty($this->bean->notify_on_save));
    }
    public function action_spot()
    {
        $this->view = 'spot';
    }


    /**
     * Specify what happens after the save has occurred.
     */
    protected function post_save()
    {
        $module = (!empty($this->return_module) ? $this->return_module : $this->module);
        $action = (!empty($this->return_action) ? $this->return_action : 'DetailView');
        $id = (!empty($this->return_id) ? $this->return_id : $this->bean->id);

        $url = "index.php?module=" . $module . "&action=" . $action . "&record=" . $id;
        $this->set_redirect($url);
    }

    /*
     * Delete a bean
     */

    /**
     * Perform the actual deletion.
     */
    protected function action_delete()
    {
        //do any pre delete processing
        //if there is some custom logic for deletion.
        if (!empty($_REQUEST['record'])) {
            if (!$this->bean->ACLAccess('Delete')) {
                ACLController::displayNoAccess(true);
                sugar_cleanup(true);
            }
            $this->bean->mark_deleted($_REQUEST['record']);
        } else {
            sugar_die("A record number must be specified to delete");
        }
    }

    /**
     * Specify what happens after the deletion has occurred.
     */
    protected function post_delete()
    {
        if (empty($_REQUEST['return_url'])) {
            $return_module = isset($_REQUEST['return_module']) ?
                $_REQUEST['return_module'] :
                $GLOBALS['sugar_config']['default_module'];
            $return_action = isset($_REQUEST['return_action']) ?
                $_REQUEST['return_action'] :
                $GLOBALS['sugar_config']['default_action'];
            $return_id = isset($_REQUEST['return_id']) ?
                $_REQUEST['return_id'] :
                '';
            $url = "index.php?module=" . $return_module . "&action=" . $return_action . "&record=" . $return_id;
        } else {
            $url = $_REQUEST['return_url'];
        }

        //eggsurplus Bug 23816: maintain VCR after an edit/save. If it is a duplicate then don't worry about it. The offset is now worthless.
        if (isset($_REQUEST['offset']) && empty($_REQUEST['duplicateSave'])) {
            $url .= "&offset=" . $_REQUEST['offset'];
        }

        $this->set_redirect($url);
    }
    /**
     * Perform the actual massupdate.
     */
    protected function action_massupdate()
    {
        if (!empty($_REQUEST['massupdate']) && $_REQUEST['massupdate'] == 'true' && (!empty($_REQUEST['uid']) || !empty($_REQUEST['entire']))) {
            if (!empty($_REQUEST['Delete']) && $_REQUEST['Delete'] == 'true' && !$this->bean->ACLAccess('delete')
                || (empty($_REQUEST['Delete']) || $_REQUEST['Delete'] != 'true') && !$this->bean->ACLAccess('save')
            ) {
                ACLController::displayNoAccess(true);
                sugar_cleanup(true);
            }

            set_time_limit(0);//I'm wondering if we will set it never goes timeout here.
            // until we have more efficient way of handling MU, we have to disable the limit
            DBManagerFactory::getInstance()->setQueryLimit(0);
            require_once("include/MassUpdate.php");
            require_once('modules/MySettings/StoreQuery.php');
            $seed = loadBean($_REQUEST['module']);
            $mass = new MassUpdate();
            $mass->setSugarBean($seed);
            if (isset($_REQUEST['entire']) && empty($_POST['mass'])) {
                $mass->generateSearchWhere($_REQUEST['module'], $_REQUEST['current_query_by_page']);
            }
            $mass->handleMassUpdate();
            $storeQuery = new StoreQuery();//restore the current search. to solve bug 24722 for multi tabs massupdate.
            $temp_req = array(
                'current_query_by_page' => $_REQUEST['current_query_by_page'],
                'return_module' => $_REQUEST['return_module'],
                'return_action' => $_REQUEST['return_action']
            );
            if ($_REQUEST['return_module'] == 'Emails') {
                if (!empty($_REQUEST['type']) && !empty($_REQUEST['ie_assigned_user_id'])) {
                    $this->req_for_email = array(
                        'type' => $_REQUEST['type'],
                        'ie_assigned_user_id' => $_REQUEST['ie_assigned_user_id']
                    ); // Specifically for My Achieves
                }
            }
            $_REQUEST = array();
            $_REQUEST = json_decode(html_entity_decode($temp_req['current_query_by_page']), true);
            unset($_REQUEST[$seed->module_dir . '2_' . strtoupper($seed->object_name) . '_offset']);//after massupdate, the page should redirect to no offset page
            $storeQuery->saveFromRequest($_REQUEST['module']);
            $_REQUEST = array(
                'return_module' => $temp_req['return_module'],
                'return_action' => $temp_req['return_action']
            );//for post_massupdate, to go back to original page.
        } else {
            sugar_die("You must massupdate at least one record");
        }
    }

    /**
     * Specify what happens after the massupdate has occurred.
     */
    protected function post_massupdate()
    {
        $return_module = isset($_REQUEST['return_module']) ?
            $_REQUEST['return_module'] :
            $GLOBALS['sugar_config']['default_module'];
        $return_action = isset($_REQUEST['return_action']) ?
            $_REQUEST['return_action'] :
            $GLOBALS['sugar_config']['default_action'];
        $url = "index.php?module=" . $return_module . "&action=" . $return_action;
        if ($return_module == 'Emails') {//specificly for My Achieves
            if (!empty($this->req_for_email['type']) && !empty($this->req_for_email['ie_assigned_user_id'])) {
                $url = $url . "&type=" . $this->req_for_email['type'] . "&assigned_user_id=" . $this->req_for_email['ie_assigned_user_id'];
            }
        }
        $this->set_redirect($url);
    }

    /**
     * Perform the listview action
     */
    protected function action_listview()
    {
        $this->view_object_map['bean'] = $this->bean;
        $this->view = 'list';
    }

    /*

        //THIS IS HANDLED IN ACTION_REMAP WHERE INDEX IS SET TO LISTVIEW
        function action_index(){
        }
    */

    /**
     * Action to handle when using a file as was done in previous versions of Sugar.
     */
    protected function action_default()
    {
        $this->view = 'classic';
    }
    /**
     * this method id used within a Dashlet when performing an ajax call
     */
    protected function action_callmethoddashlet()
    {
        if (!empty($_REQUEST['id'])) {
            $id = $_REQUEST['id'];
            $requestedMethod = $_REQUEST['method'];
            $dashletDefs = $GLOBALS['current_user']->getPreference('dashlets', 'Home'); // load user's dashlets config
            if (!empty($dashletDefs[$id])) {
                require_once($dashletDefs[$id]['fileLocation']);

                $dashlet = new $dashletDefs[$id]['className'](
                    $id,
                    (isset($dashletDefs[$id]['options']) ? $dashletDefs[$id]['options'] : array())
                );

                if (method_exists($dashlet, $requestedMethod) || method_exists($dashlet, '__call')) {
                    echo $dashlet->$requestedMethod();
                } else {
                    echo 'no method';
                }
            }
        }
    }

    /**
     * this method is used within a Dashlet when the options configuration is posted
     */
    protected function action_configuredashlet()
    {
        global $current_user, $mod_strings;

        if (!empty($_REQUEST['id'])) {
            $id = $_REQUEST['id'];
            $dashletDefs = $current_user->getPreference('dashlets', $_REQUEST['module']); // load user's dashlets config
            require_once($dashletDefs[$id]['fileLocation']);

            $dashlet = new $dashletDefs[$id]['className'](
                $id,
                (isset($dashletDefs[$id]['options']) ? $dashletDefs[$id]['options'] : array())
            );
            if (!empty($_REQUEST['configure']) && $_REQUEST['configure']) { // save settings
                $dashletDefs[$id]['options'] = $dashlet->saveOptions($_REQUEST);
                $current_user->setPreference('dashlets', $dashletDefs, 0, $_REQUEST['module']);
            } else { // display options
                $json = getJSONobj();

                return 'result = ' . $json->encode((array(
                        'header' => $dashlet->title . ' : ' . $mod_strings['LBL_OPTIONS'],
                        'body' => $dashlet->displayOptions()
                    )));
            }
        } else {
            return '0';
        }
    }

    /**
     * Global method to delete an attachment
     *
     * If the bean does not have a deleteAttachment method it will return 'false' as a string
     *
     * @return void
     */
    protected function action_deleteattachment()
    {
        $this->view = 'edit';
        $GLOBALS['view'] = $this->view;
        ob_clean();
        $retval = false;

        if (method_exists($this->bean, 'deleteAttachment')) {
            $duplicate = "false";
            if (isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == "true") {
                $duplicate = "true";
            }
            if (isset($_REQUEST['duplicateSave']) && $_REQUEST['duplicateSave'] == "true") {
                $duplicate = "true";
            }
            $retval = $this->bean->deleteAttachment($duplicate);
        }
        echo json_encode($retval);
        sugar_cleanup(true);
    }
    /**
     * getActionFilename
     */
    public static function getActionFilename($action)
    {
        if (isset(self::$action_case_file[$action])) {
            return self::$action_case_file[$action];
        }

        return $action;
    }

    /********************************************************************/
    //                          PROCESS TASKS
    /********************************************************************/

    /**
     * Given the module and action, determine whether the super/admin has prevented access
     * to this url. In addition if any links specified for this module, load the links into
     * GLOBALS
     *
     * @return true if we want to stop processing, false if processing should continue
     */
    private function blockFileAccess()
    {
        //check if the we have enabled file_access_control and if so then check the mappings on the request;
        if (!empty($GLOBALS['sugar_config']['admin_access_control']) && $GLOBALS['sugar_config']['admin_access_control']) {
            $this->loadMapping('file_access_control_map');
            //since we have this turned on, check the mapping file
            $module = strtolower($this->module);
            $action = strtolower($this->do_action);
            if (!empty($this->file_access_control_map['modules'][$module]['links'])) {
                $GLOBALS['admin_access_control_links'] = $this->file_access_control_map['modules'][$module]['links'];
            }

            if (!empty($this->file_access_control_map['modules'][$module]['actions']) && (in_array(
                $action,
                $this->file_access_control_map['modules'][$module]['actions']
            ) || !empty($this->file_access_control_map['modules'][$module]['actions'][$action]))
            ) {
                //check params
                if (!empty($this->file_access_control_map['modules'][$module]['actions'][$action]['params'])) {
                    $block = true;
                    $params = $this->file_access_control_map['modules'][$module]['actions'][$action]['params'];
                    foreach ($params as $param => $paramVals) {
                        if (!empty($_REQUEST[$param])) {
                            if (!in_array($_REQUEST[$param], $paramVals)) {
                                $block = false;
                                break;
                            }
                        }
                    }
                    if ($block) {
                        $this->_processed = true;
                        $this->no_access();
                    }
                } else {
                    $this->_processed = true;
                    $this->no_access();
                }
            }
        } else {
            $this->_processed = false;
        }
    }

    /**
     * This code is part of the entry points reworking. We have consolidated all
     * entry points to go through index.php. Now in order to bring up an entry point
     * it will follow the format:
     * 'index.php?entryPoint=download'
     * the download entry point is mapped in the following file: entry_point_registry.php
     *
     */
    private function handleEntryPoint()
    {
        if (!empty($_REQUEST['entryPoint'])) {
            $this->loadMapping('entry_point_registry');
            $entryPoint = $_REQUEST['entryPoint'];

            if (!empty($this->entry_point_registry[$entryPoint])) {
                require_once($this->entry_point_registry[$entryPoint]['file']);
                $this->_processed = true;
                $this->view = '';
            }
        }
    }

    /**
     * Checks to see if the requested entry point requires auth
     *
     * @param  $entrypoint string name of the entrypoint
     * @return bool true if auth is required, false if not
     */
    public function checkEntryPointRequiresAuth($entryPoint)
    {
        $this->loadMapping('entry_point_registry');

        if (isset($this->entry_point_registry[$entryPoint]['auth'])
            && !$this->entry_point_registry[$entryPoint]['auth']
        ) {
            return false;
        }

        return true;
    }

    /**
     * Meant to handle old views e.g. DetailView.php.
     *
     */
    protected function callLegacyCode()
    {
        $file = self::getActionFilename($this->do_action);
        if (isset($this->action_view_map[strtolower($this->do_action)])) {
            $action = $this->action_view_map[strtolower($this->do_action)];
        } else {
            $action = $this->do_action;
        }
        // index actions actually maps to the view.list.php view
        if ($action == 'index') {
            $action = 'list';
        }

        if ((file_exists('modules/' . $this->module . '/' . $file . '.php')
                && !file_exists('modules/' . $this->module . '/views/view.' . $action . '.php'))
            || (file_exists('custom/modules/' . $this->module . '/' . $file . '.php')
                && !file_exists('custom/modules/' . $this->module . '/views/view.' . $action . '.php'))
        ) {
            // A 'classic' module, using the old pre-MVC display files
            // We should now discard the bean we just obtained for tracking as the pre-MVC module will instantiate its own
            unset($GLOBALS['FOCUS']);
            $this->action_default();
            $this->_processed = true;
        }
    }

    /**
     * If the action has been remapped to a different action as defined in
     * action_file_map.php or action_view_map.php load those maps here.
     *
     */
    private function handleActionMaps()
    {
        if (!empty($this->action_file_map[strtolower($this->do_action)])) {
            $this->view = '';
            require_once($this->action_file_map[strtolower($this->do_action)]);
            $this->_processed = true;
        } elseif (!empty($this->action_view_map[strtolower($this->do_action)])) {
            $this->view = $this->action_view_map[strtolower($this->do_action)];
            $this->_processed = true;
        } else {
            $this->no_action();
        }
    }
    /**
     * Actually remap the action if required.
     *
     */
    protected function remapAction()
    {
        if (!empty($this->action_remap[$this->do_action])) {
            $this->action = $this->action_remap[$this->do_action];
            $this->do_action = $this->action;
        }
    }


    /**
     * action: Send Confirm Opt In Email to Contact/Lead/Account/Prospect
     *
     * @global array $app_strings using for user messages about error/success status of action
     */
    public function action_sendConfirmOptInEmail()
    {
        global $app_strings;

        if (!($this->bean instanceof Company || $this->bean instanceof Person)) {
            $msg = $app_strings['LBL_CONFIRM_OPT_IN_ONLY_FOR_PERSON'];
            SugarApplication::appendErrorMessage($msg);
        } else {
            $configurator = new Configurator();
            $confirmOptInEnabled = $configurator->isConfirmOptInEnabled();
            if (!$confirmOptInEnabled) {
                $msg = $app_strings['LBL_CONFIRM_OPT_IN_IS_DISABLED'];
                SugarApplication::appendErrorMessage($msg);
            } else {
                $emailAddressStringCaps = strtoupper($this->bean->email1);
                if ($emailAddressStringCaps) {
                    $emailAddress = BeanFactory::newBean('EmailAddresses');
                    $emailAddress->retrieve_by_string_fields(array(
                        'email_address_caps' => $emailAddressStringCaps,
                    ));

                    $emailMan = BeanFactory::newBean('EmailMan');

                    $success = $emailMan->sendOptInEmail($emailAddress, $this->bean->module_name, $this->bean->id);

                    if (!$success) {
                        $msg = $app_strings['LBL_CONFIRM_EMAIL_SENDING_FAILED'];
                        SugarApplication::appendErrorMessage($msg);
                    } else {
                        $msg = $app_strings['LBL_CONFIRM_EMAIL_SENT'];
                        SugarApplication::appendSuccessMessage($msg);
                    }
                } else {
                    $msg = $app_strings['LBL_CONTACT_HAS_NO_PRIMARY_EMAIL'];
                    SugarApplication::appendErrorMessage($msg);
                }
            }
        }
        $this->view = 'detail';
    }

}
?>
