<?php
require_once 'GeodropResponse.php';

/**
 * Response to a <CODE>CustomerGoodbye</CODE> request
 *
 * @author A-Tono s.r.l.
 * @since 1.0
 *
 *
 */
class CustomerGoodbye_Response extends GeodropResponse
{
  /**
   * Customer phone number in E.164 format (without +)
   * @var string
   */
  private $msisdn;
  /**
   * Short identifier label of network operator
   * @var string
   */
  private $mno;
  /**
   * DropPay port id
   * @var int
   */
  private $port;
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
   * Subscriber id
   * @var int
   */
  private $subscriber;
  
  public function __destruct()
  {
    parent::__destruct();    
    unset($this->msisdn);
    unset($this->mno);
    unset($this->port);
    unset($this->code);
    unset($this->description);
    unset($this->subscriber);
  }
  
  public function toString()
  {
    $result = "msisdn: ";
    $result.= $this->msisdn."\n";
    $result.= "mno: ";
    $result.= $this->mno."\n";
    $result.= "port: ";
    $result.= $this->port."\n";
    $result.= "code: ";
    $result.= $this->code."\n";
    $result.= "description: ";
    $result.= $this->description."\n";
    $result.= "subscriber: ";
    $result.= $this->subscriber;

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
    
    $this->msisdn       = trim($xml->pay->{'goodbye-rsp'}['msisdn']);
    $this->mno          = trim($xml->pay->{'goodbye-rsp'}['mno']);
    $this->port         = (int)trim($xml->pay->{'goodbye-rsp'}['port']);
    $this->code         = trim($xml->pay->{'goodbye-rsp'}->status['code']);
    $this->description  = trim($xml->pay->{'goodbye-rsp'}->status['description']);
    $this->subscriber   = (int)trim($xml->pay->{'goodbye-rsp'}->subscriber);
    
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
   * Returns the mno
   * 
   * @return string Mno
   */
  public function get_mno()
  {
    return $this->mno;
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
   * Returns the error code
   * 
   * @return int error code
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
   * Returns the subscriber id
   * 
   * @return int Subscriber id
   */
  public function get_subscriber()
  {
    return $this->subscriber;
  }
}

?>
