<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sms_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }

    //COMMON FUNCTION FOR SENDING SMS
	
    function send_sms($message, $reciever, $time, $msgTittle)
    {
        $active_sms_service = $this->db->get_where('settings' , array(
            'type' => 'active_sms_service'
        ))->row()->description;
        if ($active_sms_service == '' || $active_sms_service == 'disabled')
            return;
        if ($active_sms_service == 'clickatell') {
            $this->send_sms_via_clickatell($message , $reciever, $time, $msgTittle);
        }
        if ($active_sms_service == 'twilio') {
            $this->send_sms_via_twilio($message , $reciever );
        }
    }
    
    // SEND SMS VIA CLICKATELL API
    function send_sms_via_clickatell($message, $reciever, $time, $msgTittle) {
        //$time = strtotime($time);

        
        $clickatell_user       = $this->db->get_where('settings', array('type' => 'clickatell_user'))->row()->description;
        $clickatell_password   = $this->db->get_where('settings', array('type' => 'clickatell_password'))->row()->description;
        $clickatell_api_id     = $this->db->get_where('settings', array('type' => 'clickatell_api_id'))->row()->description;
        
		$from 	= '8801847050009';
		foreach ($reciever as $value)
			{
			$to = $value;
			$url = "https://bmpws.robi.com.bd/ApacheGearWS/SendTextMessage?Username=".$clickatell_user."&Password=".$clickatell_password."&From=".$from."&To=".$to."&Message=".urlencode($message);
			$ret = file_get_contents($url);
            $not_data['recipient'] = $to;
            $not_data['text']      = $msgTittle;
            $not_data['noticedate'] = $time;

            $this->db->insert('notification', $not_data);
			}	          
    }
    
    
    // SEND SMS VIA TWILIO API
    function send_sms_via_twilio($message = '' , $reciever_phone = '') {
        
        // LOAD TWILIO LIBRARY
        require_once(APPPATH . 'libraries/twilio_library/Twilio.php');


        $account_sid    = $this->db->get_where('settings', array('type' => 'twilio_account_sid'))->row()->description;
        $auth_token     = $this->db->get_where('settings', array('type' => 'twilio_auth_token'))->row()->description;
        $client         = new Services_Twilio($account_sid, $auth_token); 

        $client->account->messages->create(array( 
            'To'        => $reciever_phone, 
            'From'      => $this->db->get_where('settings', array('type' => 'twilio_sender_phone_number'))->row()->description,
            'Body'      => $message   
        ));

    }
}