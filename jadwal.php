<?php
include_once("koneksi.php");

class jadwal extends Koneksi {
    public function __construct($server, $user, $pass, $db) {
        parent::__construct($server,$user,$pass,$db);
    }

    public function getJadwal($search="%") {
        //$sql = "SELECT * FROM movie";
        //$res = $this->con->query($sql);

        $sql = "SELECT h.nama, jk.jam_mulai
        FROM mahasiswa mhs INNER JOIN jadwal j ON mhs.nrp = j.nrp
        INNER JOIN hari h ON h.idhari = j.idhari
        INNER JOIN jam_kuliah jk ON j.idjam_kuliah = jk.idjam_kuliah
        WHERE mhs.nrp = ?;";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param('s',$search);
        $stmt->execute();
        $res = $stmt->get_result();

        /*while ($row = $res->fetch_assoc()) {
            echo $row['judul']."<br>";
        }*/

        return $res;
    }
}

?>