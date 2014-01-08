<?php
require_once 'GeodropRequest.php';
require_once 'dropOut/SMSSend_Response.php';
require_once 'dropOut/Report.php';

/**
 * Request to send SMS messages
 * specifying a text for the message,
 * a list of recipients and a personalized sender
 *
 * @author A-Tono s.r.l.
 * @since 1.0
 *
 *
 */
class SMSSend extends GeodropRequest
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
   * Used to specify the personalized sender
   * @var string
   */
  private $tpoa;
  /**
   * Date and time in the format "Y-m-d H:i:s",
   * used to send the message to a certain date,
   * if not specified the message is sent immediately
   * @var date
   */
  private $deferred;
  
  /**
   * Creates a new <CODE>SMSSend</CODE> instance
   * 
   * @param string/array dest_msisdns Msisdn of the recipient or array of msisdns
   * @param string message_text Text of the message
   * @param string tpoa Used to specify the personalized sender
   * @param string deferred (optional) Date and time in the format "Y-m-d H:i:s",
   * used to send the message to a certain date,
   * if not specified the message is sent immediately
   *
   * @throws Exception If parameters are not valid
   */
  public function __construct($dest_msisdns,$message_text,$tpoa,$deferred=null) 
  {
    if(!isset($dest_msisdns,$message_text,$tpoa))
    {
      throw new Exception(ErrorType::MISSING_PARAMETERS);
    }
    
    //check dest_msisdns
    if(!$this->checkIfMsisdnsIsArrayOrString($dest_msisdns,true))
    {
      throw new Exception(ErrorType::MALFORMED_MSISDNS);
    }
    
    //check deferred
    if(isset($deferred))
    {
      if(!$this->checkIfFutureDatetime($deferred))
      {
	throw new Exception(ErrorType::MALFORMED_OR_PAST_TIME);
      }
    }

    //set parameters
    $this->uri = Uri::OUT_SMS_SEND;
    $this->httpMethod = HttpMethod::POST;
    $this->contentType = ContentType::XML;
    $this->dest_msisdns = $dest_msisdns;
    $this->message_text = utf8_encode($message_text);
    $this->tpoa = $tpoa;
    if(isset($deferred))
      $this->deferred = date('Y-m-d H:i:s',strtotime(trim($deferred)));
    $this->createParams();
  }
  
  public function __destruct()
  {
    parent::__destruct();
    unset($this->message_text);
    unset($this->dest_msisdns);
    unset($this->tpoa);
    unset($this->deferred);
  }

  public function decodeResponse( $http_response )
  {
    //echo "SMSSend::decodeResponse: ".$http_response."\n";    
    $this->response = new SMSSend_Response();
    return $this->response->fillParameters($http_response);
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
      foreach($this->dest_msisdns as &$msisdn)
      {
	$this->body.= '<DEST msisdn="'.$msisdn.'" />';
      }
    }
    $this->body.= '</LIST>';
    $this->body.= '<TPOA>'.$this->tpoa.'</TPOA>'; 
    if(isset($this->deferred))
      $this->body.= '<DEFERRED>'.$this->deferred.'</DEFERRED>';
    $this->body.= '</GEOSMS>';
  }

  //getters
  /**
   * Returns the text of the message to send
   * @access public
   * @return string
   */
  public function get_message_text()
  {
    return $this->message_text;
  }
  /**
   * Returns the msisdn of the recipient or an array of msisdns if there are many recipients;
   * each msisdn is in E.164 format with '+'
   * @access public
   * @return string|string[]
   */
  public function get_dest_msisdns()
  {
    return $this->dest_msisdns;
  }
  /**
   * Returns the personalized sender
   * @access public
   * @return string
   */
  public function get_tpoa()
  {
    return $this->tpoa;
  }
  /**
   * Returns date and time in the format "Y-m-d H:i:s",
   * used to send the message to a certain date,
   * if not specified the message is sent immediately
   * @access public
   * @return date
   */
  public function get_deferred()
  {
    return $this->deferred;
  }
}

?>