<?php
require_once 'GeodropResponse.php';

/**
 * Response to a <CODE>PortChallenge</CODE> request
 *
 * @author A-Tono s.r.l.
 * @since 1.0
 *
 *
 */
class PortChallenge_Response extends GeodropResponse
{
  /**
   * Customer phone number in E.164 format (without +)
   * @var string
   */
  private $msisdn;
  /**
   * DropPay port id
   * @var int
   */
  private $port;
  /**
   * Unique CP request id
   * @var string
   */
  private $custom;
  /**
   * Error code
   * @var int
   */
  private $code;
  /**
   * Verbose description of the error
   * @var string
   */
  private $description;
  /**
   * Order id appended by Geodrop
   * @var int
   */
  private $order;
  
  public function __destruct()
  {
    parent::__destruct();    
    unset($this->msisdn);
    unset($this->port);
    unset($this->custom);
    unset($this->code);
    unset($this->description);
    unset($this->order);
  }
  
  public function toString()
  {
    $result = "msisdn: ";
    $result.= $this->msisdn."\n";
    $result.= "port: ";
    $result.= $this->port."\n";
    $result.= "custom: ";
    $result.= $this->custom."\n";
    $result.= "code: ";
    $result.= $this->code."\n";
    $result.= "description: ";
    $result.= $this->description."\n";
    $result.= "order: ";
    $result.= $this->order;
    
    return $result;
  }
  
  public function fillParameters($http_response)
  {
    try
    {
      $xml = new SimpleXMLElement($http_response);
    }
    catch (Exception $e)
    {
      error_log(ErrorType::RESPONSE_NOT_XML);
      return false;
    }
    $this->msisdn       = trim($xml->pay->{'challenge-rsp'}['msisdn']);
    $this->port         = (int)trim($xml->pay->{'challenge-rsp'}['port']);
    $this->custom       = trim($xml->pay->{'challenge-rsp'}['custom']);
    $this->code         = (int)trim($xml->pay->{'challenge-rsp'}->status['code']);
    $this->description  = trim($xml->pay->{'challenge-rsp'}->status['description']);
    $this->order        = (int)trim($xml->pay->{'challenge-rsp'}->order);
    unset($xml);
    return true;
  }
  
  //getters
  /**
   * Returns the customer phone number
   * 
   * @return string Customer phone number
   */
  public function get_msisdn()
  {
    return $this->msisdn;
  }
  /**
   * Returns the DropPay port id
   * 
   * @return int DropPay port id
   */
  public function get_port()
  {
    return $this->port;
  }
  /**
   * Returns the unique CP request id
   * 
   * @return string Unique CP request id
   */
  public function get_custom()
  {
    return $this->custom;
  }
  /**
   * Returns the error code
   * 
   * @return int Error code
   */
  public function get_code()
  {
    return $this->code;
  }
  /**
   * Returns the description of the error
   * 
   * @return string Description of the error
   */
  public function get_description()
  {
    return $this->description;
  }
  /**
   * Returns the order id
   * 
   * @return int Order id
   */
  public function get_order()
  {
    return $this->order;
  }
}

?>
