<?php
ini_set('session.gc_maxlifetime', 3600);
session_set_cookie_params(3600);
session_start();
/*
If i send the arr no need to sned the numOPtions;
*/
$host="localhost";
	$dbusername = "root";
	$dbpassword = "ILMSIWLTMCD24/7";
	$dbname="poll";
	
	//create connection
	$conn = new mysqli ($host, $dbusername, $dbpassword, $dbname);
	
	if(mysqli_connect_error()){
	die("Connection Error (" .mysqli_connect_errno(). ") " . mysqli_connect_error());
	sleep(5);
	header('Location: sign-up.html'); 
	}
	else{
		$numOptions = $_POST['options'];
		$ar = $_POST['names'];
		$arr=explode('|',$ar);
		//echo "$numOptions";
		$output = "";
		for($i=1; $i <= $numOptions; $i++){
			$s = "option$i";
			$o=$i-1;
			if ($_POST["$s"] !="")
			$output .= "$arr[$o]: ".$_POST["$s"]."|";
		else
			$output .= "$arr[$o]: 0|";
		}
		$trimmed=substr("$output", 0, strlen("$output")-1);
		echo "$trimmed";
		
		//send trimmed to database;
		$poll_id=$_POST['poll_id'];
		$user=$_SESSION['user_id'];
		$query = "insert into votes (username, poll_id, value) values('$user', '$poll_id', '$trimmed')";
		if($conn->query($query)){
			echo "<script>alert('success');window.location.href='user.php';</script>";
			//header("Location: user.php");
		}
		else{
			echo"Error: ". $query ."<br>". $conn->error;
			sleep(5);
		}
	}
?>