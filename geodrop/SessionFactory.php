<?php
require_once 'GeodropSession.php';
require_once 'ContentType.php';
require_once 'Uri.php';

/**
 * Used for the creation of a <CODE>GeodropSession</CODE>
 *
 * @author A-Tono s.r.l.
 * @since 1.0
 *
 */
class SessionFactory
{
  /**
   * Creates a <CODE>GeodropSession</CODE>
   * with the method of Resource Owner Password Credentials
   *
   * @param string client_id Client id
   * @param string client_secret Client secret
   * @param string username Username
   * @param string password Password
   *
   * @throws Exception If parameters are not valid
   * 
   * @return GeodropSession The session
   */
  public static function buildSession_ResourceOwnerPasswordCredentials( $client_id,  $client_secret,  $username,  $password ) 
  {
    if(!isset($client_id,$client_secret,$username,$password))
    {
      throw new Exception(ErrorType::MISSING_PARAMETERS);
    }
    
    $uri = Uri::TOKEN_REQUEST;
    $grant_type = 'password';
    
    //header params
    $auth = base64_encode($client_id . ":" . $client_secret);
    //set header array
    $header_array = array(
          'Authorization: Basic '.$auth,
          'Content-type: '.ContentType::RAW,
    );
    
    //fields
    $fields_string = "grant_type=".$grant_type."&username=".urlencode($username)."&password=".urlencode($password);
    
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
    //print_r (curl_getinfo($ch));
    //close connection
    curl_close($ch);

    //create the GeodropSession object
    $geodropSession = new GeodropSession($result,$client_id,$client_secret);
    
    return $geodropSession;
  }

}
?>