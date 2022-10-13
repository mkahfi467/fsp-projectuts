<?php
include_once("koneksi.php");

class hari extends Koneksi {
    public function __construct($server, $user, $pass, $db) {
        parent::__construct($server,$user,$pass,$db);
    }

    public function getHari($search="%") {
        //$sql = "SELECT * FROM movie";
        //$res = $this->con->query($sql);

        $sql = "SELECT * FROM hari";
        $stmt = $this->con->prepare($sql);
        //$stmt->bind_param('s',$search);
        $stmt->execute();
        $res = $stmt->get_result();

        /*while ($row = $res->fetch_assoc()) {
            echo $row['judul']."<br>";
        }*/

        return $res;
    }

    public function __destruct() {
        parent::__destruct();
    }
}

?>