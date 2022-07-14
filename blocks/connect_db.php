<?php

	session_start();

	$conn = mysqli_connect('localhost', 'f0682541_db', 'strelok001', 'f0682541_db');
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}
