<?php
include_once("koneksi.php");

class mahasiswa extends Koneksi {
    public function __construct($server, $user, $pass, $db) {
        parent::__construct($server,$user,$pass,$db);
    }

    public function getMahasiswa($search="%") {
        //$sql = "SELECT * FROM movie";
        //$res = $this->con->query($sql);

        $sql = "SELECT * FROM mahasiswa WHERE nrp LIKE ?";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param('s',$search);
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