<?php	

	// $servername = "localhost";
	// $username = "root";
	// $password = "";
	// $dbname = "together_db";

	// $servername = "localhost";
	// $username = "zkdpszwh_together_db";
	// $password = "together_123";
	// $dbname = "zkdpszwh_together_db";

	$servername = "103.230.120.52";
	$username = "together_user2";
	$password = "v[86I8iy[22";
	$dbname = "togetherdb2";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);

	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

    // echo "Phone : ".$_GET["phone"];
    // print_r($_GET);

	$phone = "N/A";
	if(isset($_POST["phone"])){
		$phone =  $_POST["phone"];
	}else if(isset($_GET["phone"])){
		$phone =  $_GET["phone"];
	}

	$text = "N/A";
	if(isset($_POST["text"])){
		$text =  $_POST["text"];
	}else if(isset($_GET["text"])){
		$text =  $_GET["text"];
	}

	$device = "";
	if(isset($_POST["device"])){
		$device =  $_POST["device"];
	}

	$sim = "N/A";
	if(isset($_POST["sim"])){
		$sim =  $_POST["sim"];
	}else if(isset($_GET["sim"])){
		$sim =  $_GET["sim"];
	}

	$myfile = fopen("testfile.txt", "w");
	fwrite($myfile, pack("CCC",0xef,0xbb,0xbf));  // convert to utf8
	fwrite($myfile, "phone=$phone\n");
	fwrite($myfile, "text=$text\n");
	fwrite($myfile, "sim=$sim\n");
	if($device!=""){
		fwrite($myfile, "device=$device\n");
	}
	fclose($myfile);

	// $phone = "027777777";
	// $text = "105.00 บ.จากxBorder ชำระผ่านQR เข้า076355900016902 06/10@11:09";
	// $exp_form = explode(" ", $text);
	// dd($exp_form);

	if ($phone == "027777777" || $phone == "SCBQRAlert") {
		$exp_form = explode(" ", $text);

		if (!empty($exp_form[0]) ) {
			if ($exp_form[0] == "เงินโอนจาก" || $exp_form[0] == "เงินโอนยอด" || $exp_form[3] == "เข้า076355900016902" || $exp_form[4] == "เข้า076355900016902" || $exp_form[4] == "เข้า076355900016901" || $exp_form[4] == "เข้า076355900016911" || $exp_form[0] == "เงินเข้าบ/ช" || $exp_form[0] == "เช็คเข้าบ/ช") {
				$date = date('Y-m-d H:i:s');
				$sql = "INSERT INTO sms_forward (messages, is_status, created_at) VALUES ('$text', 0, '$date')";

				if ($conn->query($sql) === TRUE) {
					//must return "OK" or APP will consider message as failed
					echo "OK";
				  } else {
					echo "Error: " . $sql . "<br>" . $conn->error;
				  }
			}
		}
		  $conn->close();
	}

?>