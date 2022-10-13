<?php

class Koneksi {
    protected $con;

    public function __construct($server, $user, $pass, $db) {
        $this->con = new mysqli($server, $user, $pass, $db);
    }

    public function __destruct() {
        $this->con->close();
    }
}

?>