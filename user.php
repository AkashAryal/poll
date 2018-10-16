<!DOCTYPE html>
<!-main content= updates in website, About User Mabey->
<head>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/3/w3.css">

<?php
session_start();
if(isset($_SESSION['user_id'])){}
else{
	session_destroy();
setcookie("PHPSESSID","",time()-3600,"/");
echo "<script>alert('Log back in!');window.location.href='Poll_home.html';</script>";
}
?>
</head>
<body>
<nav class="w3-bar w3-black">
  <a href="Poll_home.html" class="w3-button w3-bar-item">Home</a>
  <a a href="logout.php" class="w3-button w3-bar-item">Logout</a>
  <a href="createPoll.html" class="w3-button w3-bar-item">Create a Poll</a>
	<a href="vote.html" class="w3-button w3-bar-item">Vote</a>
  <a href="my_polls.php" class="w3-button w3-bar-item">My Polls</a>
  <a href="my_votes.php" class="w3-button w3-bar-item">My Votes</a>
</nav>

<?php
echo "<h1>".$_SESSION['user_id']."</h1>";
?>


</body>

</html>
