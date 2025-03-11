<?php	

	// $servername = "localhost";
	// $username = "root";
	// $password = "";
	// $dbname = "harmony_db";

	$servername = "103.230.120.52";
	$username = "together_user2";
	$password = "v[86I8iy[22";
	$dbname = "harmonydb";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);

	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	// สร้าง URL เต็มจากพารามิเตอร์ที่ส่งมา
	$full_url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

	// แยก URL ที่มีพารามิเตอร์ออกจากกัน
	$exp = explode('?', $full_url);
	$exp2 = explode('&', $exp[1]);  // แยกแต่ละพารามิเตอร์ด้วย &

	// ตัดสัญลักษณ์ = และ & ออกจากแต่ละพารามิเตอร์
	$params = [];
	foreach ($exp2 as $param) {
		list($key, $value) = explode('=', $param); // แยก key และ value
		$params[$key] = $value; // เก็บค่าลงใน array
	}

	if(isset($_GET["phone"])){
		echo "Phone : ".$_GET["phone"];
	} else {
		echo "Phone : ";
	}

	$phone = "N/A";
	if(isset($_POST["phone"])){
		$phone =  $_POST["phone"];
	}else if(isset($_GET["phone"])){
		$phone =  $_GET["phone"];
	} elseif (isset($params['phone'])) {
		$phone =  $params["phone"];
	}

	$text = "N/A";
	if(isset($_POST["text"])){
		$text =  $_POST["text"];
	}else if(isset($_GET["text"])){
		$text =  $_GET["text"];
	} elseif (isset($params['text'])) {
		$text =  urldecode($params["text"]);
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
	} elseif (isset($params['sim'])) {
		$sim =  $params["sim"];
	}

	// $myfile = fopen("testfile.txt", "w");
	// fwrite($myfile, pack("CCC",0xef,0xbb,0xbf));  // convert to utf8
	// fwrite($myfile, "phone=$phone\n");
	// fwrite($myfile, "text=$text\n");
	// fwrite($myfile, "sim=$sim\n");
	// if($device!=""){
	// 	fwrite($myfile, "device=$device\n");
	// }
	// fclose($myfile);

	// $phone = "027777777";
	// $text = "21/02/68 15:36 บช X-0999 รับโอนจาก X-6163 13,065.00 คงเหลือ 37,856.06 บ.";
	// $exp_form = explode(" ", $text);
	// dd($exp_form);

	if ($phone == "027777777" || $phone == "SCBQRAlert" || $phone == "KBank" || $phone == "BANGKOKBANK") {
		$exp_form = explode(" ", $text);

		if (!empty($exp_form[0]) ) {
			if (isset($exp_form[0]) && $exp_form[0] == "เงินโอนจาก" 
			|| isset($exp_form[0]) && $exp_form[0] == "เงินโอนยอด" 
			|| isset($exp_form[3]) && $exp_form[3] == "X-0999" && isset($exp_form[4]) && $exp_form[4] == "เงินเข้า"
			|| isset($exp_form[3]) && $exp_form[3] == "X-0999" && isset($exp_form[4]) && $exp_form[4] == "รับโอนจาก"
			|| isset($exp_form[2]) && preg_match('~เข้าบ/ชx755111.*$~', $exp_form[2], $matches) == 1 
			|| isset($exp_form[0]) && $exp_form[0] == "ฝาก/โอนเงินเข้าบ/ชX9911ผ่านMB" 
			|| isset($exp_form[0]) && $exp_form[0] == "เงินเข้าบ/ช" 
			|| isset($exp_form[0]) && $exp_form[0] == "เช็คเข้าบ/ช"
			|| isset($exp_form[4]) && $exp_form[4] == "เข้า076355400050101") {

				$date = date('Y-m-d H:i:s');
				$sql = "INSERT INTO sms_forward (messages, sender, chanel, is_status, created_at) VALUES ('$text', '$phone', 'SMS', 0, '$date')";

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