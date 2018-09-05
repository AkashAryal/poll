<?php
ini_set('session.gc_maxlifetime', 3600);
session_set_cookie_params(3600);
session_start();

$username = filter_input(INPUT_POST, 'username');
$password = filter_input(INPUT_POST, 'password');
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

if(empty($password)){
echo "<script>alert('Password field is empty!');window.location.href='sign-up.html';</script>";
die();
}
if(!(empty($username)))
{

	$host="localhost";
	$dbusername = "root";
	$dbpassword = "ILMSIWLTMCD24/7";
	$dbname="poll";

	//create connection
	$conn = new mysqli ($host, $dbusername, $dbpassword, $dbname);

	if(mysqli_connect_error()){
	die("Connection Error (" .mysql_connect_errno(). ") " . mysql_connect_error());
	sleep(5);
	header('Location: sign-up.html');
	}
	else{

		$query = "select username,count(*) as count from users where username='$username'";
		$result =$conn->query($query) or die($conn->error);
		$c=mysqli_fetch_assoc($result);
		//echo $c['count'];
		if ($c['count'] !=0)
		{
			//print_r($result);
			echo "<script>alert('Username Taken!');window.location.href='sign-up.html'</script>";
			$conn->close();
			die();
		}
		$sql="INSERT INTO users (username, password) values('$username','$hashed_password')";

		if($conn->query($sql)){
			echo "<script>alert('Success! Login Created');window.location.href='user.php'</script>";
		}
		else{
			echo"Error: ". $sql ."<br>". $conn->error;
			sleep(5);
		}
		$conn->close();
		$_SESSION["user_id"]=$username;
		//header('Location: user.php');
	}

}
else{
echo "<script>alert('Username field is empty!');window.location.href='sign-up.html'</script>";
//header('Location: sign-up.html');
die();
}
?>
