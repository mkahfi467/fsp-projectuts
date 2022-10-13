<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style type="text/css">
		table, th, td {
			border: 1px solid black;
            min-width: 50px;
		}
	</style>
</head>
<body>
    <?php
    include_once("mahasiswa.php");
    //require("koneksi.php");
    //$koneksi = new koneksi("localhost", "root", "", "fsp-project");

    //$mahasiswa = new mahasiswa("localhost", "id19689568_user", "M.kahfi.12345", "id19689568_db_fsp");
    $mahasiswa = new mahasiswa("localhost", "root", "", "fsp-project");
    $result = $mahasiswa->getMahasiswa();
    //$mahasiswa = new mahasiswa();
    //$result = $mahasiswa->getMahasiswa();
   

    echo "<form method='POST' action=''>";
    echo "Mahasiswa : ";
	echo "<select name='nrp'>";
    echo "<option value=''>-- Pilih Mahasiswa --</option>";
	while ($row = $result->fetch_assoc()) {
        echo "<option value='". $row['nrp'] . "-". $row['nama'] ."'>". $row['nama'] ."</option>";
    }
	echo "</select>";
    //echo "Keyword: <input type='text' name='keyword' value='$keyword'><br>";
    echo "<input type='submit' id='btnPilih' name='submit' value='Pilih'>";
    echo "</form>";

    if (isset($_POST['submit'])){
        $splitNrpNama = explode("-", $_POST['nrp']);
        $nrp = $splitNrpNama[0];
        $nama = $splitNrpNama[1];
        $search = "$nrp";
        //echo "Hai";
    } else {
        $keyword = "";
        $search = "%";
        //echo "Hello";
    }

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
    
    include_once("hari.php");
    $hari = new hari("localhost", "root", "", "fsp-project");
    $resulthari = $hari->getHari(); 

    $my_array_hari = array();
    while($row = $resulthari->fetch_assoc()) {
        array_push( $my_array_hari, $row['nama'] );
    }

    $array_hari_jam = array();

    include_once("jamkuliah.php");
    $jamkuliah = new jamkuliah("localhost", "root", "", "fsp-project");
    $resultjamkuliah = $jamkuliah->getJamKuliah();

    while ($row1 = $resultjamkuliah->fetch_assoc()) {
        foreach ($my_array_hari as $value) {
            array_push($array_hari_jam, $value.$row1['jam_mulai']);
        }
    }

    include_once("jadwal.php");
    $jadwal = new jadwal("localhost", "root", "", "fsp-project");
    $resultjadwal = $jadwal->getJadwal($search);

    $array_jadwal = array();

    while ($row = $resultjadwal->fetch_assoc()) {
        array_push($array_jadwal, $row['nama'].$row['jam_mulai']);
    }



    include_once("jamkuliah.php");
    $jamkuliah = new jamkuliah("localhost", "root", "", "fsp-project");
    $resultjamkuliah = $jamkuliah->getJamKuliah();

    $array_jam_kuliah = array();

    while ($row1 = $resultjamkuliah->fetch_assoc()) {
        array_push($array_jam_kuliah, $row1['jam_mulai']." - ".$row1['jam_selesai']);
    }

    for ($x = 0; $x < count($array_jam_kuliah); $x++) {
        echo "<tr>";
        echo "<td>". $array_jam_kuliah[$x] ."</td>";
        for ($y = 0; $y < 7; $y++) {
            if (in_array($array_hari_jam[($y+($x*7))], $array_jadwal)) {
                echo "<td>&#10004</td>";
            } else {
                echo "<td></td>";
            }

            // echo "<td>". ($y+($x*7)) ."</td>";
        }
        echo "</tr>";
    }

    echo "</table>";
    //echo "<form method='POST' action='ubahjadwal.php'>";
    //echo "<input type='submit' name='submit' value='Ubah Jadwal'>";
    //echo "</form>";
    ?>
    <form action="ubahjadwal.php" method="POST">
        <button type='submit' name='ubah' <?php if (!isset($_POST['submit'])) {echo ' disabled=disabled ';} else {echo 'value = "'. $nrp. '-'. $nama. '"';} ?>>Ubah Jadwal</button>
    </form>
    
    <script type="text/javascript" src="js/jquery-3.6.1.min.js"></script>
    <script type="text/javascript" src="js/fsp.js"></script>
</body>
</html>