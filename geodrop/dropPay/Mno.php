<?php
  /**
   * Indicates settings for which the payment port is enabled (see Mobile Network Code)
   *
   * @author A-Tono s.r.l.
   * @since 1.0
   *
   */
  class Mno
  {
    /**
     * Short identifier label for network operator
     * @var string
     */
    private $brand;
    /**
     * Double value parameter made of two dot separed integers
     * @var string
     */
    private $mcnc;
    /**
     * Long network operator description
     * @var string
     */
    private $operator;
    /**
     * It is enabled for short messaging features;
     * it is used as sender of short messager pertinent to the payment port,
     * and/or recipient for requests coming from customers (reply messages or purchasing actions)
     *
     * @var int
     */
    private $shortcode;
    
    public function __destruct()
    {
      unset($this->brand);
      unset($this->mcnc);
      unset($this->operator);
      unset($this->shortcode);
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
      $result = " - brand: ";
      $result.= $this->brand;
      $result.= ", mcnc: ";
      $result.= $this->mcnc;
      $result.= ", operator: ";
      $result.= $this->operator;
      $result.= ", shortcode: ";
      $result.= $this->shortcode;
      
      return $result;
    }
    
    //setters
    /**
     * Set the brand
     *
     * @param string brand Brand
     * @return void
     */
    public function set_brand($brand)
    {
      $this->brand = trim($brand);
    }
    
    /**
     * Set the mcnc
     *
     * @param string mcnc Mcnc
     * @return void
     */
    public function set_mcnc($mcnc)
    {
      $this->mcnc = trim($mcnc);
    }
    
    /**
     * Set the operator
     *
     * @param string operator Operator
     * @return void
     */
    public function set_operator($operator)
    {
      $this->operator = trim($operator);
    }
    
    /**
     * Set the shortcode
     * 
     * @param int shortcode Shortcode
     * @return void
     */
    public function set_shortcode($shortcode)
    {
      $this->shortcode = trim($shortcode);
    }
    
    //getters
    /**
     * Returns the brand
     * 
     * @return string Brand
     */
    public function get_brand()
    {
      return $this->brand;
    }
    
    /**
     * Returns the mcnc
     * 
     * @return string Mcnc
     */
    public function get_mcnc()
    {
      return $this->mcnc;
    }
    
    /**
     * Returns the operator
     * 
     * @return string Operator
     */
    public function get_operator()
    {
      return $this->operator;
    }
    
    /**
     * Returns the shortcode
     * 
     * @return int Shortcode
     */
    public function get_shortcode()
    {
      return $this->shortcode;
    }
  }
?>
