<?php
require_once 'GeodropRequest.php';
require_once 'SMSOut/SMSCheckBalance_Response.php';

/**
 * Request used to get the balance of the user
 *
 * @author A-Tono s.r.l.
 * @since 1.0
 *
 *
 */
class SMSCheckBalance extends GeodropRequest
{
  /**
   * The text of the message to send
   * @var string
   */
  private $message_text;
  /**
   * Contains the msisdn of the recipient or an array of msisdns if there are many recipients;
   * each msisdn is in E.164 format with '+'
   * @var string|string[]
   */
  private $dest_msisdns;
  
  /**
   * Creates a new <CODE>SMSCheckBalance</CODE> instance
   *
   * @throws Exception If parameters are not valid
   */
  public function __construct() 
  {
    //set parameters
    $this->uri = Uri::OUT_SMS_CHECKBALANCE;
    $this->httpMethod = HttpMethod::POST;
    $this->contentType = ContentType::XML;
    $dest_msisdns = '+390123456789';
    $message_text = "fake";
    $this->set_dest_msisdns($dest_msisdns);
    $this->set_message_text(utf8_encode($message_text));
  } 
  
  public function __destruct()
  {
    parent::__destruct();
    unset($this->message_text);
    unset($this->dest_msisdns);
  }
  
  public function createParams()
  {      
    //set body
    $this->body = '<?xml version="1.0" encoding="UTF-8"?>';
    $this->body.= '<GEOSMS>';
    $this->body.= '<MESSAGE content="text"><TEXT><![CDATA['.$this->message_text.']]></TEXT></MESSAGE>';
    $this->body.= '<LIST>';
    //decode $dest_msisdns, that is an array if there are more msisdns
    if(is_string($this->dest_msisdns))
    {
      $this->body.= '<DEST msisdn="'.$this->dest_msisdns.'" />';
    }
    elseif(is_array($this->dest_msisdns))
    {
      foreach($this->dest_msisdns as $msisdn)
      {
	$this->body.= '<DEST msisdn="'.$msisdn.'" />';
      }
    }
    $this->body.= '</LIST>';
    $this->body.= '</GEOSMS>';
  }

  public function decodeResponse( $http_response )
  {
    $this->response = new SMSCheckBalance_Response(); 
    return $this->response->fillParameters($http_response);
  }
  
  
  //setters
  /**
   * Sets the text of the message to send
   * 
   * @param string message_text The text of the message to send
   * @return void
   */
  private function set_message_text($message_text)
  {
    $this->message_text = $message_text;
  }
  /**
   * Sets the msisdn of the recipient or an array of msisdns if there are many recipients;
   * each msisdn is in E.164 format with '+'
   * 
   * @param string|string[] The msisdn of the recipient or an array of msisdns
   * @return void
   * @throws Exception If parameters are not valid
   */
  private function set_dest_msisdns($dest_msisdns)
  {
    //check dest_msisdns
    if(!$this->checkIfMsisdnsIsArrayOrString($dest_msisdns,true))
    {
      throw new Exception(ErrorType::MALFORMED_MSISDNS);
    }
    $this->dest_msisdns = $dest_msisdns;
  }
}

?>