<?
set_include_path('geodrop/');
require_once 'SessionFactory.php';
require_once 'GeodropSession.php';
require_once 'dropOut/SMSSend.php';



$application_id = '';
$application_secret = '';
$username = '';
$password = '';
$msg = "";
$dest = "";
$sender = "SENDER";

//Creation of the GeodropSession
$session = SessionFactory::buildSession_ResourceOwnerPasswordCredentials($application_id,$application_secret,$username,$password);
$requestSend = new SMSSend($dest,$msg,$sender);
$session->runMethod($requestSend);
echo "Response: ".$requestSend->getResponse()->toString()."\n";


?>