<?php
require_once 'GeodropResponse.php';

/**
 * Response to a <CODE>PortTrigger</CODE> request
 *
 * @author A-Tono s.r.l.
 * @since 1.0
 *
 *
 */
class PortTrigger_Response extends GeodropResponse
{
  /**
   * Customer phone number in E.164 format (without +)
   * @var string
   */
  //private $msisdn;
  /**
   * DropPay port id
   * @var int
   */
  //private $mno;
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
    //unset($this->msisdn);
    //unset($this->mno);
    unset($this->id);
    unset($this->code);
    unset($this->description);
  }
    
  public function toString()
  {
    //$result = "msisdn: ";
    //$result.= $this->msisdn."\n";
    //$result.= "mno: ";
    //$result.= $this->mno."\n";
    $result = "id: ";
    $result.= $this->id."\n";
    $result.= "code: ";
    $result.= $this->code."\n";
    $result.= "description: ";
    $result.= $this->description."\n";
    
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
    //$this->msisdn     = trim($xml->pay->{'trigger-rsp'}['msisdn']);//TODO
    //$this->mno        = trim($xml->pay->{'trigger-rsp'}['msisdn']);//TODO
    $this->id           = trim($xml->pay->{'trigger-rsp'}->trigger['id']);
    $this->code         = (int)trim($xml->pay->{'trigger-rsp'}['code']);
    $this->description  = trim($xml->pay->{'trigger-rsp'}['description']);
    
    unset($xml);
    return true;
  }
  
  //getters
  //public function get_msisdn()
  //{
  //  return $this->msisdn;
  //}
  //public function get_mno()
  //{
  //  return $this->mno;
  //}
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
}

?>
