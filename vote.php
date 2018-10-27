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
        echo "<div class='form-style'>";//
        echo "<h1>Vote</h1>";
				echo "<div class='grad'>";
				echo "<h4 style='text-align:center'>Poll Name: $arr[0]</h4>";
				echo "<h4 style='text-align:center'>Poll Type: $arr[1]</h4>";
				echo "</div>";
				echo '<form action="voting.php" method="POST">';
				for($i=2; $i < count($arr); $i++){
					$o=$i-1;
					//echo "<h4>Option $o: $arr[$i]</h4>";
					//echo"$arr[$i]<input type='text' name='option$o'>";
					if($arr[1] == "RankedVoting"){
						echo '<label for="$arr[$i]">'.'<strong>'.$arr[$i].'</strong></label>';
          echo"<input type='number' min='0' step='1' name='option$o' id='$arr[$i]'>";
					echo"<br>";
				}
				else if($arr[1] == "strawPoll"){

echo"<label class='container'>".$arr[$i]."
<input type='hidden' name='option$o' value='0'>
<input type='radio' name='option$o' value='1'>
	  <span class='checkmark'></span>
	</label>";
					echo"<br>";
				}
					$n .=$arr[$i]."|";
				}

				$names = substr("$n", 0, strlen("$n")-1);
			//	echo "$names";
				$options = count($arr) - 2;
				//echo "$options";
				echo "<input type='hidden' name='options' value='$options'>";
				echo "<input type='hidden' name='names' value='$names'>";
				echo "<input type='hidden' name='poll_id' value='$poll_id'>";
				echo'<input type="submit" value="Vote"></form>';
        echo "</div>";
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

<center><div align="center" class='box'>
<h3><strong>How to Vote:</strong></h3>
<p><strong>Straw Poll:</strong> Select the option that you want and click vote</p>
<p><strong>Ranked Voting:</strong> Rank your choices with 1 representing your first choice, 2 representing your second choice and so on. Few things to note:
<ul><li>0 represents last place</li><li>leaving an option empty is equivalent to a value of 0</li><li>
You can have multiple fields with the same value. EX: multiple fields can be left empty and they will tie for last place,
or multiple fields can have a value of 1 and be tied for first place etc...</li></p>
<p><strong>More voting methods to come!</strong></p>
</div></center>
