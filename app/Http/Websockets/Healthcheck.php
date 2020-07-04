<?php

namespace App\Http\Websockets;

use Exception;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Http\HttpServerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Healthcheck implements HttpServerInterface
{
    public function onMessage(ConnectionInterface $from, $msg)
    {
        //
    }

    public function onOpen(ConnectionInterface $connection, RequestInterface $request = null)
    {
        $response = new Response(200, [
            'Content-Type' => 'application/json',
        ], json_encode([
            'ok' => true,
        ]));

        $connection->send(\GuzzleHttp\Psr7\str($response));

        $connection->close();
    }

    public function onClose(ConnectionInterface $connection)
    {
        //
    }

    public function onError(ConnectionInterface $connection, Exception $exception)
    {
        if (! $exception instanceof HttpException) {
            return;
        }

        $response = new Response($exception->getStatusCode(), [
            'Content-Type' => 'application/json',
        ], json_encode([
            'error' => $exception->getMessage(),
        ]));

        $connection->send(\GuzzleHttp\Psr7\str($response));

        $connection->close();
    }
}
