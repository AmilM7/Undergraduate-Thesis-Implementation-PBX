<?php
require '../vendor/autoload.php';
use PAMI\Client\Impl\ClientImpl as PamiClient;
$channel = $_POST['channel'];

recordCall($channel);

function recordCall($channel){
    $pamiClientOptions = array(
      'host' => '127.0.0.1',
      'scheme' => 'tcp://',
      'port' => 5038,
      'username' => 'admin',
      'secret' => 'mysecret',
      'connect_timeout' => 10000,
      'read_timeout' => 10000
  );
      $pamiClient = new PamiClient($pamiClientOptions);
      $pamiClient->open();
      $path= '/var/spool/asterisk/monitor/';
      $action = new \PAMI\Message\Action\MonitorAction($channel,$path.$channel);
      $result = $pamiClient->send($action);
  
      $myObj = (object) ['event' => 'Recording'];
      $myJSON = json_encode($myObj);
      echo $myJSON;
    }
?>
