<?php
ini_set('session.gc_maxlifetime', 3600);
session_set_cookie_params(3600);
session_start();

echo '<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/3/w3.css">
<nav class="w3-bar w3-black">
  <a href="Poll_home.html" class="w3-button w3-bar-item">Home</a>
  <a a href="logout.php" class="w3-button w3-bar-item">Logout</a>
  <a href="createPoll.html" class="w3-button w3-bar-item">Create a Poll</a>
  <a href="vote.html" class="w3-button w3-bar-item">Vote</a>
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
		//echo $username;
		$query = "select `data`, `poll_id`, `open_time`, `input_date_time` from poll_info where username='$username'";
		$result = $conn->query($query) or die($conn->error);
		//$result = $conn->query("select `input_date_time`, `open_time` from poll_info where poll_id='$poll_id'") or die($conn->error);
			//$row = $result->fetch_assoc();
      $f=0;
      $dataHolder = array();
		while($row = $result->fetch_assoc()){
      $dataHolder[$f][0]=$row;
			$ot = $row['open_time'];
			$time = strtotime($row['input_date_time']." + $ot hour");
			$mysqlDate = date("Y-m-d H:i:s", $time);
			$str=$row['data']."<br />";
			$arr=explode('|',$str);
    //  var_dump($arr);
    //  $serialized=htmlspecialchars(serialize($arr));
			echo "<a href='poll_results.php?index=$f'>Poll Name: ".$arr[0].'</a><br />';
      $_SESSION['arr']=$dataHolder;
    //  $_SESSION['index']=$f;
    //  var_dump($dataHolder[0][0]);
      //echo "<form><input type='hidden' name='arr' value='$serialized'>";
      //echo'<input type="submit" value="Submit"></form>';
			echo "Poll Type: ".$arr[1]."<br />";
			echo "Poll ID: ".$row['poll_id']."<br />";
			for($i=2; $i < count($arr); $i++){
				$o=$i-1;

				if($i+1 == count($arr))
					echo "Option $o: ".$arr[$i];
				else
					echo "Option $o: ".$arr[$i]."</br>";
			}

			/////////---------------------------everything below is about the votes;
			///if straepoll then this below

			$poll_id = $row['poll_id'];
			$q = "select value from votes where poll_id='$poll_id'";
			$r = $conn->query($q) or die($conn->error);
			$ensureArrGets0FilledOnlyOnce = 0;
		//	if(mysqli_num_rows($r) !=0){

				while($row2 = $r->fetch_assoc()){

					$str2 = $row2['value'];
					$arr2 = explode('|', $str2); //optional: 10
					if($ensureArrGets0FilledOnlyOnce ==0){

						$numVotes= array_fill(0,count($arr2),0);
						$ensureArrGets0FilledOnlyOnce--;
					}
					//print_r($arr2);
					if($arr[1]=="strawPoll"){
						for($i=0; $i<count($arr2); $i++){
							$t = ((int)(substr($arr2[$i], strpos($arr2[$i], " ")+1)));
							$numVotes[$i] += $t; // nv[0]=10
						}
					}
				}
			if($arr[1]=="strawPoll"){
				$maxIndex=array_search(max($numVotes), $numVotes);
				$winner = $arr2[$maxIndex]; //optiona1: 10
				$category=substr($winner, 0, strpos($winner, ":"));
				$votes = $numVotes[$maxIndex];

				//echo "VOTES:<br />";
				for($j=0; $j<count($arr2); $j++)
				{
					$wi=$arr2[$j];
					$cat = substr($wi, 0, strpos($wi, ":"));
					$vo = $numVotes[$j];
				//	echo "-- $cat: $vo<br />";
				}
        $dataHolder[$f][1]=$arr2;
      //  $_SESSION['votesArr']=$arr2;
        $dataHolder[$f][2]=$mysqlDate;
      //  $_SESSION['date']=$mysqlDate;
      $_SESSION['numVotesArr']=$numVotes;
      $dataHolder[$f][3]=$numVotes;
				/*if(date('Y-m-d H:i:s') > $mysqlDate)
				{
					echo "-- Winner: ".$category." -> Votes: ".$votes;
					echo "<br><br>";
				}
				else{
					echo "--Winner: Poll is still ongoing<br /><br />";
				}
        */
			}
			else{ //condorchet and other stuff

			}
		//}
		//else
			//echo "<br>";
      $f++;
		}
	}
	$conn->close();
?>
