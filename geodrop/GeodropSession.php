<?php
require_once 'SessionFactory.php';
require_once 'GeodropSession.php';
require_once 'GeodropRequest.php';
require_once 'ErrorType.php';
require_once 'HttpMethod.php';
require_once 'ContentType.php';
require_once 'Uri.php';

/**
 * GeodropSession
 *
 * @author A-Tono s.r.l.
 * @since 1.0
 *
 */
class GeodropSession
{
  /**
   * Access token
   * @var string
   */
  private $access_token;
  /**
   * Refresh token
   * @var string
   */
  private $refresh_token;
  /**
   * Refresh time that is the time of creation of the token
   * @var string
   */
  private $refresh_time;
  /**
   * Token type
   * @var string
   */
  private $token_type;
  
  /**
   * Client id
   * @var string
   */
  private $client_id;
  
  /**
   * Client secret
   * @var string
   */
  private $client_secret;
  
  /**
   * Creates a new <CODE>GeodropSession</CODE> instance
   * 
   * @param string tokenResponse The http response to the token request
   * @param string client_id The client id
   * @param string client_secret The client secret
   *
   * @throws Exception If parameters are not valid
   */
  public function __construct( $tokenResponse,$client_id,$client_secret ) 
  {
    if(!isset($tokenResponse,$client_id,$client_secret))
    {
      throw new Exception(ErrorType::TOKEN_NOT_VALID);
    }
    //parse the json response
    $this->parseTokenResponse($tokenResponse);
    $this->client_id = $client_id;
    $this->client_secret = $client_secret;
  }
  
  public function __destruct()
  {
    unset($this->access_token);
    unset($this->refresh_token);
    unset($this->refresh_time);
    unset($this->token_type);
    unset($this->client_id);
    unset($this->client_secret);
  }
  
  /**
   * Returns a string representation of the object;
   * in general, the toString method returns a string that "textually represents" this object;
   * the result should be a concise but informative representation that is easy for a person to read
   * 
   * @return string a string representation of the object
   */
  public function toString() 
  {
    $result = "GeodropSession:\n";
    if (isset($this->access_token))
	    $result.="\taccess_token: ".$this->access_token."\n";
    if (isset($this->token_type))
	    $result.="\ttoken_type: ".$this->token_type."\n";
    if (isset($this->refresh_time))
	    $result.="\trefresh_time: ".date("Y-m-d H:i:s", $this->refresh_time)."\n";
    if (isset($this->refresh_token))
	    $result.="\trefresh_token: ".$this->refresh_token."\n";
    if (isset($this->client_id))
	    $result.="\tclient_id: ".$this->client_id."\n";
    if (isset($this->client_secret))
	    $result.="\tclient_secret: ".$this->client_secret."\n";
    return $result;
  }
  
  /**
   * Run a <CODE>GeodropRequest</CODE>
   *
   * @param GeodropRequest request The <CODE>GeodropRequest</CODE>
   * 
   * @return boolean Returns <CODE>true</CODE> on success, <CODE>false</CODE> otherwise
   */
  public function runMethod( $request ) 
  {
    if(!isset($request))
    {
      error_log(ErrorType::MISSING_REQUEST);
      return false;
    }
    
    //build http request
    //body or URI parameters
    //print_r ($request->getParams());
    $request->createParams();
    $body = $request->getBody();
    if(!isset($body))
    {
      $body = $this->preparePostFields($request->getParams());
    }
    
    $attempt = 0; //number of connection attempts, MAX 3
    $failed = true; //set to false if the token is valid
    $result = null;
    do
    {
      if($attempt > 0)
      {
	if(!$this->tokenRefresh())
	{
	  error_log(ErrorType::TOKEN_NOT_VALID);
	  return false;
	}
      }
      
      //header
      $header_array = array(
	'Authorization: '.$this->token_type.' '.$this->access_token,
	'Content-type: '.$request->getContentType(),
      );
      
      //open connection
      $ch = curl_init();
      //set options
      curl_setopt($ch,CURLOPT_HTTPHEADER,$header_array);
      curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch,CURLOPT_CUSTOMREQUEST, $request->getHttpMethod());
      switch($request->getHttpMethod())
      {
	case HttpMethod::POST:
	case HttpMethod::PUT:
	  curl_setopt($ch,CURLOPT_POSTFIELDS,$body);
	  curl_setopt($ch,CURLOPT_URL,$request->getUri());
	  curl_setopt($ch,CURLOPT_HTTPGET,false);
	  break;
	case HttpMethod::GET:
	  curl_setopt($ch,CURLOPT_URL,$request->getUri()."?".$body);
	  curl_setopt($ch,CURLOPT_HTTPGET,true);
	  break;
	case HttpMethod::DELETE:
	  break;
      }
      //execute post
      $result = curl_exec($ch);
      //print_r (curl_getinfo($ch));
      //close connection
      curl_close($ch);
      
      //echo "result = ".$result."\n";
      
      $attempt++;
      if(isset($result))
      { 
	if(strstr($result,"<title>401 Authorization Required</title>") == false)
	{
	  $failed = false;
	}
      }
    }
    while($attempt < 3 && $failed);

    if($failed)
    {
      error_log(ErrorType::TOKEN_NOT_VALID);
      return false;
    }
    if(!isset($result))
    {
      error_log(ErrorType::INVALID_HTTP_RESPONSE);
      return false;
    }
    return $request->decodeResponse($result);
  }
  /**
   * Builds the post field using an array of parameters
   * 
   * @param array $array Array of parameters (key => value)
   * @return boolean Returns <CODE>true</CODE> on success, <CODE>false</CODE> otherwise
   */
  private final function preparePostFields($array)
  {
    $params = array();
    foreach ($array as $key => $value) {
      $params[] = $key . '=' . urlencode($value);
    }
    return implode('&', $params);
  }
  
  /**
   * Refreshes the token
   * 
   * @return boolean Returns <CODE>true</CODE> on success, <CODE>false</CODE> otherwise
   */
  private function tokenRefresh() 
  {
    //echo "YYY access token = ".$this->access_token."\n";
    //String grant_type = "refresh_token";
    if(!isset($this->refresh_token))
    {
      error_log(ErrorType::TOKEN_NOT_VALID);
      return false;
    }
    //build http request
    $uri = Uri::TOKEN_REQUEST;
    //header
    $auth = base64_encode($this->client_id . ":" . $this->client_secret);
    //set header array
    $header_array = array(
          'Authorization: Basic '.$auth,
          'Content-type: '.ContentType::RAW,
    );
    $fields_string = 'grant_type=refresh_token&refresh_token=' . urlencode($this->refresh_token);
    
    //open connection
    $ch = curl_init();
    
    //set the url, number of POST vars, POST data
    curl_setopt($ch,CURLOPT_URL,$uri);
    curl_setopt($ch,CURLOPT_POST,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
    curl_setopt($ch,CURLOPT_HTTPHEADER,$header_array);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

    //execute POST
    $result = curl_exec($ch);
    
    //close connection
    curl_close($ch);
    
    if(!isset($result))
    {
      error_log(ErrorType::TOKEN_NOT_VALID);
      return false;
    }
    
    $this->parseTokenResponse($result);
    
    if(!isset($this->access_token))
    {
      error_log(ErrorType::TOKEN_NOT_VALID);
      return false;
    }
    return true;
  }
  
  /**
   * Performs the parsing of the http response
   * and fills the field of the <CODE>GeodropSession</CODE>
   *
   * @param string tokenResponse Response to the http request
   * 
   * @return void
   */
  private function parseTokenResponse($tokenResponse)
  {
    //parse the json response
    //echo "\n".$tokenResponse."\n";
    $result_parsed = json_decode($tokenResponse, true);
    if(!isset($result_parsed['error']))
    {
      $this->access_token = $result_parsed['access_token'];
      $this->token_type = $result_parsed['token_type'];
      $this->refresh_time = $result_parsed['expire_in'];
      $this->refresh_token = $result_parsed['refresh_token'];
    }
    else
    {
      throw new Exception(ErrorType::AUTHENTICATION_FAILED);
    }
  }
}

?>