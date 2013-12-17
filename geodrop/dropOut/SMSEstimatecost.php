<?php
require_once 'GeodropRequest.php';
require_once 'dropOut/SMSEstimatecost_Response.php';

/**
 * Request used to determine a higher estimate of the final cost of the message
 *
 * @author A-Tono s.r.l.
 * @since 1.0
 *
 *
 */
class SMSEstimatecost extends GeodropRequest
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
   * Creates a new <CODE>SMSEstimatecost</CODE> instance
   * 
   * @param string|array dest_msisdns Msisdn of the recipient or array of msisdns
   * @param string message_text Text of the message
   *
   * @throws Exception If parameters are not valid
   */
  public function __construct($dest_msisdns,$message_text) 
  {
    if(!isset($dest_msisdns) || !isset($message_text))
    {
      throw new Exception(ErrorType::MISSING_PARAMETERS);
    }
    
    //check dest_msisdns
    if(!$this->checkIfMsisdnsIsArrayOrString($dest_msisdns,true))
    {
      throw new Exception(ErrorType::MALFORMED_MSISDNS);
    }
    
    //set parameters
    $this->uri = Uri::OUT_SMS_ESTIMATECOST;
    $this->httpMethod = HttpMethod::POST;
    $this->contentType = ContentType::XML;
    $this->dest_msisdns = $dest_msisdns;
    $this->message_text = utf8_encode($message_text);
    $this->createParams();
  } 
  
  public function __destruct()
  {
    parent::__destruct();
    unset($this->message_text);
    unset($this->dest_msisdns);
  }
  
  protected function createParams()
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
    $this->response = new SMSEstimatecost_Response(); 
    return $this->response->fillParameters($http_response);
  }
}

?>