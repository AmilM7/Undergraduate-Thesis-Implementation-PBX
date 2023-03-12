<!DOCTYPE html>

<html>
	<body>
		<?php
        require '../../vendor/autoload.php';
        use WebSocket\Client;
		use PAMI\Client\Impl\ClientImpl as PamiClient;
		use PAMI\Message\Event\EventMessage;
		use PAMI\Listener\IEventListener;

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
				
        $clientWeb = new Client("ws://127.0.0.1:8080");
			
		$pamiClient->registerEventListener(function (EventMessage $event) {
            global $clientWeb;
			if ($event->getKeys()['event']=='Newchannel' || $event->getKeys()['event']=='Hangup' || $event->getKeys()['event']=='ContactStatus')  {
                $clientWeb->send('{"event": "' . $event->getKeys()['event'].'", "channel": "'.$event->getKeys()['channel'] . '" }');
			}
		});

		$running = true;
		while($running) {
			$pamiClient->process();
			usleep(1000);
		}
		$pamiClient->close();

		?>
	 </body>
</html>