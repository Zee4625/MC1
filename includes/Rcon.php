<?php

namespace Thedudeguy;

class Rcon {
    private $host;
    private $port;
    private $password;
    private $timeout;
    private $socket;

    public function __construct($host, $port, $password, $timeout = 3) {
        $this->host = $host;
        $this->port = $port;
        $this->password = $password;
        $this->timeout = $timeout;
    }

    public function connect() {
        $this->socket = @fsockopen($this->host, $this->port, $errno, $errstr, $this->timeout);
        if (!$this->socket) return false;

        stream_set_timeout($this->socket, $this->timeout);
        return $this->auth();
    }

    private function auth() {
        return true;
    }

    public function sendCommand($command) {
        fwrite($this->socket, $command . "\n");
        $response = '';
        while (!feof($this->socket)) {
            $response .= fgets($this->socket, 128);
        }
        return trim($response);
    }

    public function __destruct() {
        if (is_resource($this->socket)) {
            fclose($this->socket);
        }
    }
}
