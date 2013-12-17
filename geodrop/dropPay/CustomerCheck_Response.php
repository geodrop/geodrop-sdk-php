<?php
require_once 'GeodropResponse.php';

/**
 * Response to a <CODE>CustomerCheck</CODE> request
 *
 * @author A-Tono s.r.l.
 * @since 1.0
 *
 *
 */
class CustomerCheck_Response extends GeodropResponse
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
   * Short identifier label of network operator
   * @var string
   */
  private $mno;
  /**
   * Status of the port (ACTIVE, NOTACTIVE, SUSPENDED)
   * @var string
   */
  private $status;
  /**
   * Subscriber id
   * @var int
   */
  private $subscriber;
  /**
   * Subscription date
   * @var date
   */
  private $date;
  /**
   * Status change date
   * @var date
   */
  private $updated;
  /**
   * Subscription category
   * @var string
   */
  private $category;
  
  public function __destruct()
  {
    parent::__destruct();    
    unset($this->msisdn);
    unset($this->port);
    unset($this->code);
    unset($this->description);
    unset($this->mno);
    unset($this->status);
    unset($this->subscriber);
    unset($this->date);
    unset($this->updated);
    unset($this->category);
  }
  
  public function toString()
  {
    $result = "msisdn: ";
    $result.= $this->msisdn."\n";
    $result.= "port: ";
    $result.= $this->port."\n";
    $result.= "code: ";
    $result.= $this->code."\n";
    $result.= "description: ";
    $result.= $this->description."\n";
    $result.= "mno: ";
    $result.= $this->mno."\n";
    $result.= "status: ";
    $result.= $this->status."\n";
    $result.= "subscriber: ";
    $result.= $this->subscriber."\n";
    $result.= "date: ";
    $result.= $this->date."\n";
    $result.= "updated: ";
    $result.= $this->updated."\n";
    $result.= "category: ";
    $result.= $this->category."\n"; 
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
    
    $this->msisdn       = trim($xml->pay->{'check-rsp'}['msisdn']);
    $this->port         = (int)trim($xml->pay->{'check-rsp'}['port']);
    $this->code         = (int)trim($xml->pay->{'check-rsp'}['code']);
    $this->description  = trim($xml->pay->{'check-rsp'}['description']);
    $this->mno          = trim($xml->pay->{'check-rsp'}['mno']);
    $this->status       = trim($xml->pay->{'check-rsp'}->subscription->status);
    $this->subscriber   = (int)trim($xml->pay->{'check-rsp'}->subscription->subscriber);
    $this->date         = date('Y-m-d H:i:s',strtotime(trim($xml->pay->{'check-rsp'}->subscription->date)));
    $this->updated      = date('Y-m-d H:i:s',strtotime(trim($xml->pay->{'check-rsp'}->subscription->updated)));
    $this->category     = trim($xml->pay->{'check-rsp'}->subscription->category);
    
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
   * Returns the mno
   * 
   * @return string Mno
   */
  public function get_mno()
  {
    return $this->mno;
  }
  /**
   * Returns the status
   * 
   * @return string Status
   */
  public function get_status()
  {
    return $this->status;
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
  /**
   * Returns the subscription date
   * 
   * @return date Subscription date
   */
  public function get_date()
  {
    return $this->date;
  }
  /**
   * Returns the status change date
   * 
   * @return date Status change date
   */
  public function get_updated()
  {
    return $this->updated;
  }
  /**
   * Returns the subscription category
   * 
   * @return string Subscription category
   */
  public function get_category()
  {
    return $this->category;
  }
}

?>
