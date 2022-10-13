<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document2</title>
    <style type="text/css">
		table, th, td {
			border: 1px solid black;
            min-width: 50px;
		}
	</style>
</head>
<body>
    <?php
    echo "<form method='POST' action=''>";

    if (isset($_POST['ubah'])){
        $splitNrpNama = explode("-", $_POST['ubah']);
        $nrp = $splitNrpNama[0];
        $nama = $splitNrpNama[1];
        $search = "$nrp";
    } else {
        $keyword = "";
        $nrp = "";
        $nama = "";
        $search = "%";
        //echo "Hello";
    }
    // =======================
    
    include_once("hari.php");
    $hari = new hari("localhost", "id19689568_user", "M.kahfi.12345", "id19689568_db_fsp");
    $resulthari = $hari->getHari(); 

    $my_array_hari = array();
    while($row = $resulthari->fetch_assoc()) {
        array_push( $my_array_hari, $row['nama'] );
    }

    include_once("jadwal.php");
    $jadwal = new jadwal("localhost", "id19689568_user", "M.kahfi.12345", "id19689568_db_fsp");
    $resultjadwal = $jadwal->getJadwal($search);

    $array_jadwal = array();

    while ($row = $resultjadwal->fetch_assoc()) {
        array_push($array_jadwal, $row['nama']. "-" .$row['jam_mulai']);
    }

    $array_hari_jam = array();

    include_once("jamkuliah.php");
    $jamkuliah = new jamkuliah("localhost", "id19689568_user", "M.kahfi.12345", "id19689568_db_fsp");
    $resultjamkuliah = $jamkuliah->getJamKuliah();

    while ($row1 = $resultjamkuliah->fetch_assoc()) {
        foreach ($my_array_hari as $value) {
            array_push($array_hari_jam, $value. "-" .$row1['jam_mulai']);
        }
    }

    include_once("jamkuliah.php");
    $jamkuliah = new jamkuliah("localhost", "id19689568_user", "M.kahfi.12345", "id19689568_db_fsp");
    $resultjamkuliah = $jamkuliah->getJamKuliah();

    $array_jam_kuliah = array();

    while ($row1 = $resultjamkuliah->fetch_assoc()) {
        array_push($array_jam_kuliah, $row1['jam_mulai']." - ".$row1['jam_selesai']);
    }

    // =======================

    if (isset($_POST['submit']))
    {
        $index = $_POST['check'];
        $splitNrpNama = explode("-", $_POST['submit']);
        $nrp = $splitNrpNama[0];
        $nama = $splitNrpNama[1];
        $search = "$nrp";


        // HAPUS DATA
        $con = new mysqli("localhost", "id19689568_user", "M.kahfi.12345", "id19689568_db_fsp");

	    if ($con->connect_errno)
	    {
	    	die("Failed to Connect");
	    }

	    $sql = "DELETE FROM jadwal WHERE nrp = ?";
	    $stmt = $con->prepare($sql);
	    $stmt->bind_param("i",$nrp);
    
	    $stmt->execute();
        /*
	    if (!$stmt->error)
	    	echo "insert Sukses";
	    else
	    	echo "insert Gagal";*/
	    $stmt->close();

        $newIndexJadwalHari = array();
        $newIndexJadwalJam = array();

        // TAMBAH DATA
        foreach ($index as $value) {
            $splitHariJam = explode("-", $array_hari_jam[$value]);
            $hari = $splitHariJam[0];
            $jam = $splitHariJam[1];
            for ($x = 0; $x < count($my_array_hari); $x++) {
                if ($my_array_hari[$x] == $hari) {
                    array_push($newIndexJadwalHari, $x+1);
                }
            }
            for ($y = 0; $y < count($array_jam_kuliah); $y++) {
                $splitingJamMulai = explode(" - ", $array_jam_kuliah[$y]);
                $jamMulai = $splitingJamMulai[0];
                if ($jamMulai == $jam) {
                    array_push($newIndexJadwalJam, $y+1);
                }
            }
        }

        for ($x = 0; $x < count($newIndexJadwalHari); $x++) {
            /*echo $nrp;
            echo $newIndexJadwalHari[$x];
            echo $newIndexJadwalJam[$x];*/
            
            $sql = "INSERT INTO jadwal (nrp, idhari, idjam_kuliah) VALUES (?,?,?)";
	        $stmt = $con->prepare($sql);
	        $stmt->bind_param("iii",$nrp,$newIndexJadwalHari[$x],$newIndexJadwalJam[$x]);
	        $stmt->execute();
            $stmt->close();
        }
	    $con->close();
    }

    echo "Mahasiswa : ";
	echo "$nrp -";
    echo "$nama";
    //echo "Keyword: <input type='text' name='keyword' value='$keyword'><br>";
    //echo "</form>";

    echo "<table>";
	echo "<tr>";
	echo "<th></th>";
	echo "<th>Minggu</th>";
    echo "<th>Senin</th>";
	echo "<th>Selasa</th>";
	echo "<th>Rabu</th>";
	echo "<th>Kamis</th>";
	echo "<th>Jumat</th>";
	echo "<th>Sabtu</th>";
	echo "</tr>";

    include_once("jadwal.php");
    $jadwal = new jadwal("localhost", "id19689568_user", "M.kahfi.12345", "id19689568_db_fsp");
    $resultjadwal = $jadwal->getJadwal($search);

    $array_jadwal = array();

    while ($row = $resultjadwal->fetch_assoc()) {
        array_push($array_jadwal, $row['nama']. "-" .$row['jam_mulai']);
    }
    
    for ($x = 0; $x < count($array_jam_kuliah); $x++) {
        echo "<tr>";
        echo "<td>". $array_jam_kuliah[$x] ."</td>";
        for ($y = 0; $y < 7; $y++) {
            if (in_array($array_hari_jam[($y+($x*7))], $array_jadwal)) {
                $indexKotak = $y+($x*7);
                echo "<td><input type='checkbox' checked name='check[]' value='". $indexKotak ."'</td>";
            } else {
                $indexKotak = $y+($x*7);
                echo "<td><input type='checkbox' name='check[]' value='". $indexKotak ."'></td>";
            }
        }
        echo "</tr>";
    }

    echo "</table>";
    echo "<button type='submit' name='submit' value='". $nrp. "-". $nama ."'>Simpan</button>";
    echo "</form>";
    ?>
</body>
</html>