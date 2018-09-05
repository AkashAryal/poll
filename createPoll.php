<?php
ini_set('session.gc_maxlifetime', 3600);
session_set_cookie_params(3600);
session_start();

if(!isset($_SESSION['user_id'])){
	echo "<script>alert('Login First!');window.location.href='createPoll.html';</script>";
	die();
}
if(trim($_POST['pollName'])=="" || trim($_POST['numOfOptions'])=="" || !isset($_POST['clicked'])){
	echo "<script>alert('Empty Fields!')</script>";
	die();
}
$output="";
$pollName=$_POST['pollName'];
$output .= $pollName."|";
$selectOption=$_POST['typeOfPoll'];
$output .= $selectOption."|";
$i=0;

while(isset($_POST['option'.$i])){
	if(!empty($_POST['option'.$i])){
	$temp=$_POST['option'.$i];
	//echo "$_POST['option0']";
	$output .= $temp."|";
	$i++;
	}else		//Make it so that info does not get reset
		echo "<script>alert('Empty Fields!');window.location.href='createPoll.html';</script>";
}

$trimmed=substr("$output", 0, strlen("$output")-1);
$poll_id=random_int(0,1000000);
$username=$_SESSION['user_id'];
$input_date_time=date('Y-m-d H:i:s');
$open_time=$_POST['open'];
$data= $trimmed;

$host="localhost";
$dbusername = "root";
$dbpassword = "ILMSIWLTMCD24/7";
$dbname="poll";

$conn = new mysqli ($host, $dbusername, $dbpassword, $dbname);
	if(mysqli_connect_error()){
	die("Connection Error (" .mysqli_connect_errno(). ") " . mysqli_connect_error());
	sleep(5);
	header('Location: user.php'); 
	}
	else{
		$sql="select poll_id,count(*) as count from poll_info where poll_id='$poll_id'";
		//$query = "select username,count(*) as count from users where username='$username'";
		$result =$conn->query($sql) or die($conn->error);
		$c=mysqli_fetch_assoc($result);
		//echo $c['count']
		
		while($c['count'] >=1){
			$poll_id= random_int(0,1000000);
			$sql="select poll_id,count(*) as count from poll_info where poll_id='$poll_id';";
		$result =$conn->query($sql) or die($conn->error);
		$c=mysqli_fetch_assoc($result);
		}
		
		$sql= "INSERT INTO poll_info (poll_id, username, input_date_time, open_time, data) values('$poll_id','$username','$input_date_time','$open_time','$data')"; 
		
		if($conn->query($sql)){
			echo "<script>alert('success');window.location.href='user.php';</script>";
			//header("Location: user.php");
		}
		else{
			echo"Error: ". $sql ."<br>". $conn->error;
			sleep(5);
		}
		
	}
	$conn->close();
	//echo $input_date_time;
?>