<?php

/**
 * Generic Geodrop response
 *
 * @author A-Tono s.r.l.
 * @since 1.0
 * 
 */
abstract class GeodropResponse
{
  public function __destruct()
  {}
  
  /**
   * Returns a string representation of the object.
   * In general, the toString method returns a string that "textually represents" this object.
   * The result should be a concise but informative representation that is easy for a person to read.
   * 
   * @return string A string representation of the object
   */
  abstract public function toString();
  
  /**
   * Performs the parsing of the http response
   * and fills the field of the <CODE>GeodropResponse</CODE>
   *
   * @param string http_response Response to the http request
   * 
   * @return boolean <CODE>true</CODE> on success, <CODE>false</CODE> otherwise
   */
  abstract public function fillParameters($http_response);
  
}
?>
