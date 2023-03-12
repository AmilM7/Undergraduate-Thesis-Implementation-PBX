<?php
      require '../vendor/autoload.php';
      use PAMI\Client\Impl\ClientImpl as PamiClient;
      use PAMI\Message\Event\EventMessage;
      
      $pamiClientOptions = array(
          'host' => '127.0.0.1',
          'scheme' => 'tcp://',
          'port' => 5038,
          'username' => 'admin',
          'secret' => 'mysecret',
          'connect_timeout' => 10000,
          'read_timeout' => 10000
      );
      usleep(10000);
      $pamiClient = new PamiClient($pamiClientOptions);
      $pamiClient->open();
     
      $action = new \PAMI\Message\Action\numberOfUsers();
      $result = $pamiClient->send($action);

      $result = (array) $result->getevents();
      $onlineUsers = 0;

          foreach ($result as $key => $value) 
          {
            $breakdown = $value->getKeys();
            if (!empty($breakdown['contacts'])){
              $onlineUsers++;
            }
          }

      $pamiClient->close();
      echo $onlineUsers;
?>