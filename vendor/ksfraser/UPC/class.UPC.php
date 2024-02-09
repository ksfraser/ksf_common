<?php

require_once( 'class.origin.php' );

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
                $ret = "";
                if( is_array( $data ) )
                {
                        if( is_array( $data[0] ) )
                        {
                                foreach( $data as $row )
                                {
                                        $this->tell_eventloop( $this, 'NOTIFY_LOG_DEBUG', get_class( $this ) . "::" . __FUNCTION__ . "::" . __LINE__ );
                                        $this->setUPC( $this, $row );
                                }
                        }
                        else if( isset( $data['upc'] ) and strlen( $data['upc'] ) >= MIN_UPC_LEN AND strlen( $data['upc'] ) <= MAX_UPC_LEN)
                        {
                                $this->tell_eventloop( $this, 'NOTIFY_LOG_DEBUG', get_class( $this ) . "::" . __FUNCTION__ . "::" . __LINE__ );
                                $this->set( 'UPC', $data['upc'] );
                        }
                        else if( isset( $data['UPC'] ) and strlen( $data['upc'] ) >= MIN_UPC_LEN AND strlen( $data['upc'] ) <= MAX_UPC_LEN )
                        {
                                $this->tell_eventloop( $this, 'NOTIFY_LOG_DEBUG', get_class( $this ) . "::" . __FUNCTION__ . "::" . __LINE__ );
                                $this->set( 'UPC', $data['UPC'] );
                        }
                }
                else
                if( is_object( $data ) )
                {
			//If upc/UPC is protected this will FAIL!
                        if( isset( $data->upc ) and strlen( $data->upc ) >= MIN_UPC_LEN AND strlen( $data->upc ) <= MAX_UPC_LEN )
                        {
                                $this->tell_eventloop( $this, 'NOTIFY_LOG_DEBUG', get_class( $this ) . "::" . __FUNCTION__ . "::" . __LINE__ );
                                $this->set( 'UPC', $data->upc );
                        }
                        else
                        if( isset( $data->UPC ) and strlen( $data->UPC ) >= MIN_UPC_LEN AND strlen( $data->UPC ) <= MAX_UPC_LEN )
                        {
                                $this->tell_eventloop( $this, 'NOTIFY_LOG_DEBUG', get_class( $this ) . "::" . __FUNCTION__ . "::" . __LINE__ );
                                $this->set( 'UPC', $data->UPC );
                        }
                }
                else if( is_string( $data ) and strlen( $data ) >= MIN_UPC_LEN AND strlen( $data ) <= MAX_UPC_LEN )
                {
                        //assuming single UPC
                        $this->tell_eventloop( $this, 'NOTIFY_LOG_DEBUG', get_class( $this ) . "::" . __FUNCTION__ . "::" . __LINE__ );
                        $this->set( 'UPC', $data );
                }
                else
                if( is_object( $caller ) )
                {
                        if( isset( $caller->upc ) and strlen( $caller->upc ) >= MIN_UPC_LEN AND strlen( $caller->upc ) < MAX_UPC_LEN )
                        {
                                $this->tell_eventloop( $this, 'NOTIFY_LOG_DEBUG', get_class( $this ) . "::" . __FUNCTION__ . "::" . __LINE__ );
                                $this->set( 'UPC', $caller->upc );
                        }
                        else
                        if( isset( $caller->UPC ) and strlen( $caller->UPC ) >= MIN_UPC_LEN AND strlen( $caller->UPC ) < MAX_UPC_LEN )
                        {
                                $this->tell_eventloop( $this, 'NOTIFY_LOG_DEBUG', get_class( $this ) . "::" . __FUNCTION__ . "::" . __LINE__ );
                                $this->set( 'UPC', $caller->UPC );
                        }
                }
		else
		{
			return FALSE;
		}
		return TRUE;
	}
}
