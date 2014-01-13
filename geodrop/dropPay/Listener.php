<?php
  /**
   * Gives information about an event posted by DropPay
   *
   * @author A-Tono s.r.l.
   * @since 1.0
   *
   */
  class Listener
  {
    /**
     * Event class
     * @var string
     */
    private $lclass;
    
    /**
     * Event type
     * @var string
     */
    private $type;
    
    /**
     * Listener URI
     * @var string
     */
    private $uri;
    
    public function __destruct()
    {
      unset($this->lclass);
      unset($this->type);
      unset($this->uri);
    }
    
    /**
     * Returns a string representation of the object;
     * in general, the toString method returns a string that "textually represents" this object;
     * the result should be a concise but informative representation that is easy for a person to read
     * 
     * @return string A string representation of the object
     */
    public function toString()
    {
      $result = " - class: ";
      $result.= $this->lclass;
      $result.= ", type: ";
      $result.= $this->type;
      $result.= ", uri: ";
      $result.= $this->uri;

      return $result;
    }
    
    //setters
    /**
     * Set the event class
     *
     * @param string lclass Event class
     * @return void
     */
    public function set_lclass($lclass)
    {
      $this->lclass=trim($lclass);
    }
  
    /**
     * Set the event type
     *
     * @param string lclass Event type
     * @return void
     */
    public function set_type($type)
    {
      $this->type=trim($type);
    }
    
    /**
     * Set the URI
     *
     * @param string uri URI
     * @return void
     */
    public function set_uri($uri)
    {
      $this->uri=trim($uri);
    }
    
    //getters
    /**
     * Returns the event class
     * 
     * @return string Event class
     */
    public function get_lclass()
    {
      return $this->lclass;
    }
    
    /**
     * Returns the event type
     * 
     * @return string Event type
     */
    public function get_type()
    {
      return $this->type;
    }
    
    /**
     * Returns the Listener URI
     * 
     * @return string URI
     */
    public function get_uri()
    {
      return $this->uri;
    }
  }
?>
