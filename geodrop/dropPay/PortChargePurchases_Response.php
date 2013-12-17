<?php
require_once 'GeodropResponse.php';

/**
 * Response to a <CODE>PortChargePurchases</CODE> request
 *
 * @author A-Tono s.r.l.
 * @since 1.0
 *
 *
 */
class PortChargePurchases_Response extends GeodropResponse
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
  private $mno;
  /**
   * To be used to match with notification of listeners
   * @var string
   */
  private $id;
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
  
  public function __destruct()
  {
    parent::__destruct();    
    unset($this->msisdn);
    unset($this->mno);
    unset($this->id);
    unset($this->code);
    unset($this->description);
  }
  
  public function toString()
  {
    $result = "msisdn: ";
    $result.= $this->msisdn."\n";
    $result.= "mno: ";
    $result.= $this->mno."\n";
    $result.= "id: ";
    $result.= $this->id."\n";
    $result.= "code: ";
    $result.= $this->code."\n";
    $result.= "description: ";
    $result.= $this->description;
    
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
    
    $this->msisdn       = trim($xml->pay->{'charge-rsp'}['msisdn']);
    $this->mno          = trim($xml->pay->{'charge-rsp'}['mno']);
    $this->id           = trim($xml->pay->{'charge-rsp'}['id']);
    $this->code         = (int)trim($xml->pay->{'charge-rsp'}['code']);
    $this->description  = trim($xml->pay->{'charge-rsp'}['description']);
    
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
   * Returns the id
   * 
   * @return string Id
   */
  public function get_id()
  {
    return $this->id;
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

}

?>
