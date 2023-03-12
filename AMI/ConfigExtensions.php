<!DOCTYPE html>
<html>
  <head> 
    <title>Semantic UI</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.13/semantic.min.css">
    <link rel="stylesheet" type="text/css" href="./css/ConfigExtensions.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

  </head>
  <body>

    <header>
      <i class="phone volume icon"></i>
      AMI APPLICATION
      <i class="phone volume icon"></i>
    </header>

    <div class="main_content">
      
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

      $action = new \PAMI\Message\Action\GetConfigAction('extensions.conf',false);
      $result = $pamiClient->send($action);
          $extensionConfg = "";
          foreach ($result->getKeys() as  $key => $value) {
            if ($key !='response' && $key !='actionid'){
              $extensionConfg .= "$value <br><br>";
            }
          }
      ?>

      <div class="ui message">
        <div class="header">
          Extensions.confg
        </div>
        <p><?php echo $extensionConfg; ?></p>
      </div>

    </div>

    <footer>
     <p>Amil Murselovic</p>
     <p>Sarajevo School of Science and Technology</p>
    </footer>

    <script type="text/javascript">
      $(document).ready(function(){
  		var conn = new WebSocket('ws://localhost:8080');
  		conn.onopen = function(e) {
  		    console.log("Connection established!");
         conn.send("");
  		};

  		conn.onmessage = function(e) {
  		};

  		conn.onclose = function(e) {
  			console.log("Connection Closed!");
  		}

  		$("#send").click(function(){
  			 var userId 	= "ID usera";
  			 var msg 	= $("#testid").val();
  			 var data = {
  			 	userId: userId,
  			 	msg: msg
  			 };
  			conn.send(JSON.stringify(data));
  		});


  	  })
    </script>
  </body>
</html>
