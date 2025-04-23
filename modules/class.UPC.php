<?php

require_once( 'class.origin.php' );
require_once('../src/class.data_validate.php');

class UPC extends origin
{
	protected $UPC;
	/******************************************************//**
	 * Handle the following:
        * data = UPC
        * data = array( 'upc/isbn/label' => UPC )
        * data = array( UPC, UPC, UPC, ...)
        * data = array( array( 'label' => UPC ), array...)
        * WON'T Handle array( UPC, array()... )
        *
        * TODO
        *       sanity check that data is valid UPC
	*
	* @param caller object
	* @param data array or string
	* @return bool did we set UPC
	***********************************************************/
	function setUPC( $caller, $data )
	{
                $this->tell_eventloop( $this, 'NOTIFY_LOG_DEBUG', get_class( $this ) . "::" . __FUNCTION__ . "::" . __LINE__ );

                if (is_string($data) && DataValidator::validateLength($data, MIN_UPC_LEN, MAX_UPC_LEN)) {
                    $this->set('UPC', $data);
                    return true;
                } elseif (is_array($data)) {
                    foreach ($data as $row) {
                        if (is_string($row) && DataValidator::validateLength($row, MIN_UPC_LEN, MAX_UPC_LEN)) {
                            $this->set('UPC', $row);
                        }
                    }
                    return true;
                } elseif (is_object($data) && isset($data->UPC) && DataValidator::validateLength($data->UPC, MIN_UPC_LEN, MAX_UPC_LEN)) {
                    $this->set('UPC', $data->UPC);
                    return true;
                }

                return false;
	}
}
