<?php
/**
 * Gives information about the recipient's phone number
 *
 * @author A-Tono s.r.l.
 * @since 1.0
 *
 */
class Report
{
  /**
   * The telephone number in E.164 format
   * @var string
   */
  private $msisdn;
  /**
   * Status of the request
   * @var int
   */
  private $status;

  /**
   * The job GUID
   * @var string
   */
  private $order_id;
  
  public function __destruct()
  {
    unset($this->msisdn);
    unset($this->status);
  }
  
  /**
   * Returns a string representation of the object
   * 
   * @return string A string representation of the object
   */
  public function toString()
  {
    $result = "- msisdn: ";
    $result.= $this->msisdn;
    $result.= ", status: ";
    $result.= $this->status;
    $result.= ", order id: ";
    $result.= $this->order_id;
    return $result;
  }
  
  //setters
  /**
   * Sets the telephone number
   *
   * @param string msisdn Telephone number
   * @return void
   */
  public function set_msisdn($msisdn)
  {
    $this->msisdn = $msisdn;
  }
  
  /**
   * Sets the status
   *
   * @param int status status
   * @return void
   */
  public function set_status($status)
  {
    $this->status = $status;
  }
  
  /**
   * Sets the job GUID
   * @var string
   */
  public function set_order_id($order_id)
  {
    $this->order_id = $order_id;
  }
  
  
  //getters
  /**
   * Returns the telephone number
   * 
   * @return string Telephone number
   */
  public function get_msisdn()
  {
    return $this->msisdn;
  }
  
  /**
   * Returns the status
   * 
   * @return int Status
   */
  public function get_status()
  {
    return $this->status;
  }
  
  /**
   * Returns the job GUID
   * @return string Job GUID
   */
  public function get_order_id()
  {
    return $this->order_id;
  }
  
}

?>
