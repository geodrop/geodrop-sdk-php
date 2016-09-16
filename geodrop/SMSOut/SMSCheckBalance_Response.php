<?php
require_once 'GeodropResponse.php';

/**
 * Response to a <CODE>SMSCheckBalance</CODE> request
 *
 * @author A-Tono s.r.l.
 * @since 1.0
 *
 *
 */
class SMSCheckBalance_Response extends GeodropResponse
{
  /**
   * balance of the user
   * @var int
   */
  private $balance;
 
  public function __destruct()
  {
    parent::__destruct();
    unset($this->balance);
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
    $this->balance = trim($xml->USER['credit']);
    unset($xml);
    return true;
  }
  
  public function toString()
  {
    return "balance: ". $this->balance;
  }
  
  //getters
  /**
   * Returns the balance of the user
   *
   * @access public
   * @return int
   */
  public function getBalance()
  {
    return $this->balance;
  }
}

?>
