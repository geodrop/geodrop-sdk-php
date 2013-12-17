<?php
require_once 'GeodropResponse.php';
require_once 'HttpMethod.php';
require_once 'ErrorType.php';
require_once 'HttpMethod.php';
require_once 'ContentType.php';
require_once 'Uri.php';

/**
 * Generic Geodrop request
 *
 * @author A-Tono s.r.l.
 * @since 1.0
 *
 * @abstract
 * @access public
 */
abstract class GeodropRequest
{
  /**
   * The request URI
   * @var Uri
   * @access protected
   */
  protected $uri;
  /**
   * The request method
   * @var HttpMethod
   * @access protected
   */
  protected $httpMethod;
  /**
   * The request content type
   * @var ContentType
   * @access protected
   */
  protected $contentType;
  /**
   * The <CODE>GeodropResponse</CODE> to the request
   * @var GeodropResponse
   * @access protected
   */
  protected $response;
  /**
   * The parameters of the request
   * @var array
   * @access protected
   */
  protected $params;
  /**
   * The body of the request
   * @var string
   * @access protected
   */
  protected $body;  
    
  /**
   * Decodes the response
   * @abstract
   * @access public
   * @param string $http_response Response to the http request
   * @return boolean <CODE>true</CODE> on success, <CODE>false</CODE> otherwise.
   */
  abstract public function decodeResponse($http_response);
  
  /**
   * Creates the parameters for the http request
   * @abstract
   * @access protected
   * @return void
   */
  abstract protected function createParams();
  
  
  public function __destruct()
  {
    unset($this->uri);
    unset($this->httpMethod);
    unset($this->contentType);
    unset($this->response);
    unset($this->params);
    unset($this->body);
  }
  
  /**
   * Checks the limit format; limit is used to paginate the result,
   * it consists of two integers separated by a comma,
   * the first one indicates the position of the first required result
   * and the second the total number of result to return
   * 
   * @access public
   * @final
   * @param string $limit Limit to check
   * @return boolean <CODE>true</CODE> on success, <CODE>false</CODE> otherwise
   */
  public final function checkLimitFormat($limit)
  {
    //int,int
    $limit_splitted = split(",",$limit);
    if(count($limit_splitted) != 2)
    {
      return false;
    }
    if(ctype_digit($limit_splitted[0]) && ctype_digit($limit_splitted[1]))
    {
      return true;
    }
    return false;
  }
  
  /**
   * Checks if the format of the msisdn is E.164;
   * plan-conforming numbers are limited to a maximum of 15 digits
   * usually prefixed with the character +
   *
   * @access public
   * @param string msisdn Msisdn to check
   * @param boolean $plus <CODE>true</CODE> if the msisdn must be preceded by "+",
   * <CODE>false</CODE> otherwise
   * @return boolean <CODE>true</CODE> on success, <CODE>false</CODE> otherwise.
   */
  public final function checkMsisdnE164Format($msisdn,$plus)
  {
    if($plus)
    {
      if($msisdn[0]!="+")
      {
	return false;
      }
      $msisdn_digit = substr($msisdn,1);
    }
    else
    {
      $msisdn_digit = $msisdn;
    }
    if(ctype_digit($msisdn_digit) && strlen($msisdn_digit) <= 15)
    {
      return true;
    }
    return false;
  }
  
  /**
   * Checks if the format is an array of msisdns (in E.164)
   * or a single msisdn (in E.164)
   *
   * @access public
   * @final
   * @param string|array $msisdns string that contains the msisdn or the array of msisdns to check
   * @param boolean plus <CODE>true</CODE> if the msisdn must be preceded by "+",
   * <CODE>false</CODE> otherwise
   * @return boolean <CODE>true</CODE> on success, <CODE>false</CODE> otherwise.
   */
  public final function checkIfMsisdnsIsArrayOrString($msisdns,$plus)
  {
    if($this->checkMsisdnsArray($msisdns,$plus))
    {
      return true;
    }
    if($this->checkMsisdnE164Format($msisdns,$plus))
    {
      return true;
    }
    return false;
  }
  
  /**
   * Checks if the format is an array of valid msisdns (in E.164)
   *
   * @access public
   * @final
   * @param array $msisdns The array of msisdns to check
   * @param boolean $plus <CODE>true</CODE> if the msisdn must be preceded by "+",
   * <CODE>false</CODE> otherwise
   * @return boolean <CODE>true</CODE> on success, <CODE>false</CODE> otherwise.
   */
  public final function checkMsisdnsArray($msisdns,$plus)
  {
    if(is_array($msisdns))
    {
      foreach($msisdns as $msisdn)
      {
	if(!$this->checkMsisdnE164Format($msisdn,$plus))
	{
	  return false;
	}
      }
    }
    else
    {
      return false;
    }
    return true;
  }
  
  /**
   * Checks if the date is valid
   * and if it is in the furure
   *
   * @access public
   * @final
   * @param date $datetime Date to check
   * @return boolean <CODE>true</CODE> on success, <CODE>false</CODE> otherwise.
   */
  public final function checkIfFutureDatetime($datetime)
  {
    //check the format
    if(!$this->checkDatetimeFormat($datetime))
    {
      return false;
    }
    //The date must be in the future
    if($datetime < date("Y-m-d H:i:s"))
    {
      return false;
    }
    return true;
  }
  
  /**
   * Checks if the date valid
   *
   * @access public
   * @final
   * @param date $datetime Date to check
   * @return boolean <CODE>true</CODE> on success, <CODE>false</CODE> otherwise.
   */
  public final function checkDatetimeFormat($datetime)
  {
    if(date_parse($datetime)['error_count'] != 0)
    {
      return false;
    }
    return true;
  }
  
  /**
   * Checks the first parameter is an array that contains pairs of
   * "order id, msisdn" that uniquely identifies a single SMS message in the Geodrop archive;
   * the msisdn must be in the E.164 format
   *
   * @access public
   * @final
   * @param array $arrayToCheck Array to check
   * @param boolean $plus <CODE>true</CODE> if the msisdn must be preceded by "+",
   * <CODE>false</CODE> otherwise
   * @return boolean <CODE>true</CODE> on success, <CODE>false</CODE> otherwise.
   */
  public final function checkIfArrayOfPairOfOrderIdMsisdn($arrayToCheck,$plus)
  {
    if(!is_array($arrayToCheck))
    {
      return false;
    }
    else
    {
      foreach($arrayToCheck as $tuple)
      {
	if(!is_array($tuple))
	{
	  return false;
	}
	else
	{
	  if(count($tuple) != 2)
	  {
	    return false;
	  }
	  else
	  {
	    if(!$this->checkMsisdnE164Format($tuple[1],$plus))
	    {
	      return false;
	    }
	  }
	}
      }
    }
    return true;
  }
  
  /**
   * Checks if the parameter is an integer or a string where all characters are numerical
   *
   * @access public
   * @final
   * @param string|int $var The var to check
   * @return boolean <CODE>true</CODE> on success, <CODE>false</CODE> otherwise.
   */
  public final function checkIfIntegerOrDigitString($var)
  {
    if(is_int($var) || ctype_digit($var))
    {
      return true;
    }
    return false;
  }
  
  /**
   * Returns the parameters of the <CODE>GeodropRequest</CODE>
   *
   * @access public
   * @final
   * @return array
   */
  public final function getParams()
  {
    return $this->params;
  }

  /**
   * Returns the body of the <CODE>GeodropRequest</CODE>
   *
   * @access public
   * @final
   * @return string
   */
  public final function getBody()
  {
    return $this->body;
  }
  
  /**
   * Returns the <CODE>GeodropResponse</CODE>
   *
   * @access public
   * @final
   * @return GeodropResponse
   */
  public final function getResponse()
  {
    return $this->response;
  }
  
  /**
   * Returns the request URI
   *
   * @access public
   * @final
   * @return Uri
   */
  public final function getUri()
  {
    return $this->uri;
  }
  
  /**
   * Returns the request method
   *
   * @access public
   * @final
   * @return HttpMethod
   */
  public final function getHttpMethod()
  {
    return $this->httpMethod;
  }
  
  /**
   * Returns the request content type
   *
   * @access public
   * @final
   * @return ContentType
   */
  public final function getContentType()
  {
    return $this->contentType;
  }
}

?>
