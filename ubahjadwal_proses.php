<?php

if (isset($_POST['submit'])) {
    $splitNrpNama = explode("-", $_POST['submit']);
    $nrp = $splitNrpNama[0];
    $index = $_POST['check'];

    $con = new mysqli("localhost", "root", "", "fsp-project");

	if ($con->connect_errno)
	{
		die("Failed to Connect");
	}

	$sql = "DELETE FROM JADWAL WHERE nrp = ?";
	$stmt = $con->prepare($sql);
	$stmt->bind_param("i",$nrp);
	
	$stmt->execute();
	if (!$stmt->error)
		echo "insert Sukses";
	else
		echo "insert Gagal";
	$stmt->close();
	$con->close();
}

?>