<?php
ini_set('session.gc_maxlifetime', 3600);
session_set_cookie_params(3600);
session_start();

echo "<center><h1>My Votes</h1></center>";
echo '<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/3/w3.css">
<nav class="w3-bar w3-black">
  <a href="Poll_home.html" class="w3-button w3-bar-item">Home</a>
  <a a href="logout.php" class="w3-button w3-bar-item">Logout</a>
  <a href="createPoll.html" class="w3-button w3-bar-item">Create a Poll</a>
    <a href="vote.html" class="w3-button w3-bar-item">Vote</a>
  <a href="my_polls.php" class="w3-button w3-bar-item">My Polls</a>
  <a href="test.php" class="w3-button w3-bar-item">User</a>
  </nav>';

	$host="localhost";
	$dbusername = "root";
	$dbpassword = "ILMSIWLTMCD24/7";
	$dbname="poll";

	//create connection
	$conn = new mysqli ($host, $dbusername, $dbpassword, $dbname);

	if(mysqli_connect_error()){
	die("Connection Error (" .mysql_connect_errno(). ") " . mysql_connect_error());
	sleep(5);
	header('Location: user.php');
	}
	else{
		$username=$_SESSION['user_id'];
		$query = "select `poll_id`, `value` from votes where username='$username'";
		$result = $conn->query($query) or die($conn->error);


		while($row = $result->fetch_assoc()){
			$id=$row['poll_id'];
			$v = $row['value'];

			$query2 = "select `data` from poll_info where poll_id='$id'";
			$result2 = $conn->query($query2) or die($conn->error);
			$row2 = $result2->fetch_assoc();
			$data = $row2['data'];
			$arr=explode('|',$data);
			echo "Poll Name: ".$arr[0]."<br />";
			echo "Poll Type: ".$arr[1]."<br />";
			echo "Poll ID: ".$row['poll_id']."<br />";
			for($i=2; $i < count($arr); $i++){
				$o=$i-1;
				echo "Option $o: ".$arr[$i]."</br>";
			}
			echo "Vote: ".str_replace("|", ", ",$v)."<br />";
			echo "<br/>";
		}
		$conn->close();
	}

?>
