<?php
require '../../vendor/autoload.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
/*use MyApp\AmiListner;*/

require('AmiListner.php');

    $server = IoServer::factory(
        new HttpServer(
            new WsServer(
                new AmiListner()
            )
        ),
        8080
    );

    $server->run();