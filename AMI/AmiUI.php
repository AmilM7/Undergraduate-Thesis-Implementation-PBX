<!DOCTYPE html>
<html>
  <head> 
    <meta charset="utf-8">
    <meta name = "viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0,  minimum-scale=1.0">
    <title>PAMI application</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.css">
    <link rel="stylesheet" type="text/css" href="/css/PAMI.css">
  </head>
  <body>
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

      $action = new \PAMI\Message\Action\CoreStatusAction();
      $result = $pamiClient->send($action);
      $callsOnGoing = $result->getKeys()['corecurrentcalls'];

      $action = new \PAMI\Message\Action\numberOfUsers();
      $result = $pamiClient->send($action);

      $result = (array) $result->getevents();
      $usersCount = 0;
      $onlineUsers = 0;
      $Users = [];

          foreach ($result as $key => $value) 
          {
            $breakdown = $value->getKeys();
            if (!empty($breakdown['objectname'])){
              $Users[$usersCount] = array('objectname' => $breakdown['objectname'], 'contacts' => $breakdown['contacts']);
              $usersCount++;
            }
            if (!empty($breakdown['contacts'])){
              $onlineUsers++;
            }
          }

     
      $ShowChannelsActions =[[]];
      array_splice($ShowChannelsActions,0);
      $action = new \PAMI\Message\Action\CoreShowChannelsAction();
      $result = $pamiClient->send($action);
       if ($result->isSuccess())
         {  
           $events = $result->getevents();
           $events = (array) $events;
           $i = 0;
           foreach ($events as $key => $value) 
           {
             $breakdown = $value->getKeys();
             if (!empty($breakdown['channel']))
             {
             $ShowChannelsActions[$i] = array('duration' => $breakdown['duration'], 'status' => $breakdown['channelstatedesc'], 'channel' => $breakdown['channel'] ,'conected' => $breakdown['connectedlinenum']);
             $i++;
             }
           }
         }

     $pamiClient->close();
    ?>    

    <header>
      <i class="phone volume icon"></i>
       AMI APPLICATION
      <i class="phone volume icon"></i>
    </header>

    <div>
        <h1 class="ui header DashboardHeading">Dashboard</h1>
    </div>

    <div class="middleContent"> 

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

      <div class="MainOne">
        <main>
          <section>
            <h3 class="ui medium header">Number of users</h3>
            <p id="usercount" class="ui large header addMargin"> <?php echo $usersCount ?></p>
          </section>
          <section>
            <h3 class="ui medium header">Number of online users</h3>
            <p id="onlineUsers" class="ui large header addMargin"> <?php echo $onlineUsers ?></p>
          </section>
          <section>
            <h3 class="ui medium header">Ongoing calls</h3>
            <p id="callsOngoing" class="ui large header addMargin"> <?php echo $callsOnGoing ?></p>
          </section>
        </main>
      </div>
    </div>  
      

    <div id="callsCards"  class="ui link cards">
     <?php 
        foreach($ShowChannelsActions as $row => $innerArray) {
          echo '
              <div id="'.$innerArray["channel"].'" class="card">
                    <div class="image">
                      <img src="./pictures/phone-call.png">
                    </div>
                    <div class="content">
                          <div class="header">Status: '.$innerArray["status"].'</div>
                    </div>
                    <div class="extra content">
                        <span>
                        <i class="phone icon"></i>
                          '.$innerArray["channel"].'
                          <div class="ui bottom attached button" id="hangup" onclick="hangup(\'' .$innerArray["channel"]. '\');  " "
                          \' > 
                            <i class="stop icon"></i>
                            Hangup 
                          </div> 
                          <div class="ui bottom attached button" id="record" onclick="record(\'' .$innerArray["channel"]. '\');  " "
                          \' > 
                          <i class="circle icon"></i>
                            Record 
                          </div> 
                        </span>
                    </div>
              </div> 
          '; 
        }
      ?>
 </div>


 <footer>
   <p>Amil Murselovic</p>
   <p>Sarajevo School of Science and Technology</p>
 </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.13/semantic.min.js"></script>

    <script type="text/javascript">
      $(document).ready(function(){
      var conn = new WebSocket('ws://localhost:8080');
      conn.onopen = function(e) {
          console.log("Connection established!");
      };

      conn.onmessage = function(e) {
          var obj = JSON.parse(e.data);
          console.log(obj);
          if (obj.event == 'Newchannel' || obj.event == 'Hangup') {
            calls();
            if (obj.event == 'Hangup') {
              $('#' + $.escapeSelector(obj.channel)).remove();
            } else {
              createCard(obj.channel);
            }
          }

          if (obj.event == 'ContactStatus') {
            onlineUsers();
          }
      };

      conn.onclose = function(e) {
        console.log("Connection Closed!");
      }

      function createCard(channel) {
        var cardDiv = document.createElement("div");  
              cardDiv.className = "card";
              cardDiv.setAttribute("id",channel);
              cardDiv.innerHTML  = '<div class="image"><img src="./pictures/phone-call.png"></div> <div class="extra content"> <span> <i class="phone icon"></i> '+channel+' <div class="ui bottom attached button" id="hangup" onclick="hangup(\''+channel+ '\');  " " \' > <i class="stop icon"></i> Hangup </div> <div class="ui bottom attached button" id="record" onclick="record(\'' +channel+ '\');  " "\' > <i class="circle icon"></i>Record </div> </span></div>';
              document.getElementById("callsCards").appendChild(cardDiv);
      }

      //returns number of ongoing calls
      function calls() {
                $.ajax({
                  type: "POST",
                  url: "currentcalls.php",
                  data: {}
                }).done(function( data ) {        
                $('#callsOngoing').text(data); 
                });
              };
    
      function onlineUsers() {
                $.ajax({
                  type: "POST",
                  url: "onlineusers.php",
                  data: {}
                }).done(function( data ) {        
                $('#onlineUsers').text(data);            
                });
              };
      })
     </script>

    <script>
        function hangup(channel) {
                $.ajax({
                  type: "POST",
                  url: "hangup.php",
                  data: {'channel': channel }
                }).done(function( msg ) {
                  $('#' + $.escapeSelector(channel)).remove();;
                });
               };
    </script>

    <script>
        function record(channel) {
                $.ajax({
                  type: "POST",
                  url: "record.php",
                  data: {'channel': channel }
                }).done(function( msg ) {
                console.log(msg);
                });
               }; 
      </script>
  </body>
</html>
