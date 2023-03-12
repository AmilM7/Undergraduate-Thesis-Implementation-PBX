<!DOCTYPE html>
<html>
  <head> 
    <title>Semantic UI</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.13/semantic.min.css">
    <link rel="stylesheet" type="text/css" href="./css/UsersPAMI.css">
  </head>
  <body>

    <header>
      <i class="phone volume icon"></i>
      AMI APPLICATION
      <i class="phone volume icon"></i>
    </header>
 
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
      
      $pamiClient = new PamiClient($pamiClientOptions);
      $pamiClient->open();
      $action = new \PAMI\Message\Action\numberOfUsers();
      $result = $pamiClient->send($action);

      $result = (array) $result->getevents();
      $dir    = '/var/spool/asterisk/monitor/PJSIP/';
      $files  = scandir($dir);
      echo ' <div class="primary">
        <aside class="ui compact labeled icon menu">
          <a href="AmiUI.php" class="item">
            <i class="chart pie icon"></i>
            Board
          </a>
          <a href="Users.php" class="item">
            <i class="users icon"></i>
            Users
          </a>
          <a href="ConfigExtensions.php" class="item">
            <i class="microchip icon"></i>
            Conf file
          </a>
        </aside>
        <div class="main_content">';
      foreach ($result as $key => $value) {
        $breakdown = $value->getKeys();
        if (!empty($breakdown['objectname'])) {
            echo ' <div class="ui cards displayedUsers">
            <div class="card">
              <div class="content">
                <div class="header">'.$breakdown['objectname'].'</div>
                <div class="meta">Recordings</div>
                <div class="description">
                 ';
              $exist = 0;
              foreach($files as $audio ){
                  if(preg_match("/{$breakdown['objectname']}/i", $audio)) {
                      $exist = 1;
                      echo '<audio controls class="audio"> 
                          <source src="$audio" type="audio/wav">
                          Your browser does not support the audio element.
                          </audio><br>';
                  }
              }
              if ($exist == 0) {
                  echo ' <div class="ui message">
                    <div class="header">
                      No data found
                    </div>
                  </div>';
            }
            echo '         </div>
                      </div>
                  </div>
               </div>';
               
        }    
      }
      echo ' 
      </div>
      </div>';
     
?>

   <footer>
     <p>Amil Murselovic</p>
     <p>Sarajevo School of Science and Technology</p>
   </footer>

</body>
</html>
