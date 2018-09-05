<?php
ini_set('session.gc_maxlifetime', 3600);
session_set_cookie_params(3600);
session_start();
 
if(trim($_POST['poll_id'])==""){
	echo "<script>alert('Empty Fields!')</script>";
	header('Location: vote.html');
	die();
}

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
		$poll_id=$_POST['poll_id'];
		$user=$_SESSION['user_id'];
		$sql="select poll_id,count(*) as count from votes where poll_id='$poll_id' and username='$user'";
		$result =$conn->query($sql) or die($conn->error);
		$c=mysqli_fetch_assoc($result);
		if($c['count'] >0)
		{
			echo "<script>alert('Already Voted!');window.location.href='vote.html';</script>";
		}
		else{
		$res = $conn->query("select input_date_time from poll_info where poll_id='$poll_id'");
		
		//echo "$res->num_rows";
		
		if($res->num_rows !=0)
		{
			$result = $conn->query("select `input_date_time`, `open_time`,`data` from poll_info where poll_id='$poll_id'") or die($conn->error);
			$row = $result->fetch_assoc();
			echo '<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/3/w3.css">
			<nav class="w3-bar w3-black">
  <a href="Poll_home.html" class="w3-button w3-bar-item">Home</a>
  <a a href="logout.php" class="w3-button w3-bar-item">Logout</a> 
  <a href="createPoll.html" class="w3-button w3-bar-item">Create a Poll</a>
  <a href="my_polls.php" class="w3-button w3-bar-item">My Polls</a>
 </nav>';
			$ot = $row['open_time'];
			$t = strtotime($row['input_date_time']." + $ot hour");
			$mysqlDate = date("Y-m-d H:i:s", $t);
			
			if(date('Y-m-d H:i:s') > $mysqlDate)
				echo "<script>alert('Poll Closed or Already Voted');window.location.href='vote.html';</script>";
			else{
				$_SESSION['poll_id']=$_POST['poll_id'];
				$str=$row['data'];
				$arr=explode('|',$str);
				$n="";
				echo "<h3 style='text-align:center'>Poll Name: $arr[0]</h3>";
				echo "<h3 style='text-align:center'>Poll Type: $arr[1]</h3>";
				echo '<form action="voting.php" method="POST">';
				for($i=2; $i < count($arr); $i++){
					$o=$i-1;
					//echo "<h4>Option $o: $arr[$i]</h4>";
					echo"$arr[$i]<input type='text' name='option$o'>";
					echo"<br>";
					$n .=$arr[$i]."|";
				}
				$names = substr("$n", 0, strlen("$n")-1);
				$options = count($arr) - 2;
				echo "<input type='hidden' name='options' value='$options'>";
				echo "<input type='hidden' name='names' value='$names'>";
				echo "<input type='hidden' name='poll_id' value='$poll_id'>";
				echo'<input type="submit" value="Submit"></form>';
				//header('Location: votingPage.html');
				//echo"
				
				
				//"
			}
		//print_r($res);
		}
		else{
			echo "<script>alert('Invalid ID');window.location.href='vote.html';</script>";
		}
		//echo "select poll_id ";
		//$result = $conn->query($query) or die($conn->error);
		}
		$conn->close();
		
	}
?>