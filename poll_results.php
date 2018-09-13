<?php
require_once 'Condorcet/__CondorcetAutoload.php' ;
use Condorcet\Condorcet;
  use Condorcet\Election;
  use Condorcet\Candidate;
  use Condorcet\CondorcetUtil;
  use Condorcet\Vote;

ini_set('session.gc_maxlifetime', 3600);
session_set_cookie_params(3600);
session_start();

if(!isset($_SESSION['user_id'])){
  session_destroy();
setcookie("PHPSESSID","",time()-3600,"/");
echo "<script>alert('Log back in!');window.location.href='login.html';</script>";
  die();
}

  $election = new Election () ;

  // Create your own candidate object
  $candidate1 = new Candidate ('John');
  $candidate2 = new Candidate ('Greg');
  $candidate3 = new Candidate ('Jack');

  // Register your candidates
  $election->addCandidate($candidate1);
  $election->addCandidate($candidate2);
  $election->addCandidate($candidate3);
//  $candidate4 = $election->addCandidate('Candidate 4'); //stores everything
  //in var?

  $election->addVote( array(
                             $candidate3, // 1
                             [$candidate1] // 2 - Tie
                             // Last rank is optionnal. Here it's : $candidate3
 ));
//Session Var for poll Name
$array= $_SESSION['arr'];
$index = $_GET['index'];
$row = $array[$index][0];
//$date=$_SESSION['date'];
$str=$row['data']."<br />";
$arr=explode('|',$str);

//echo "<pre>"; var_dump($array);"</pre>";
$PollName=" ".$arr[0];
// View :
?><!doctype html>
 <html>
 <head>
 	<meta charset="UTF-8">
 	<title>Poll Results</title>

 	<style>
		.votant {
		  display: inline-block;
		  margin-right: 2cm;
		}
 	</style>
 </head>
 <body>

	<h1><?php echo "Poll Results: $PollName";?></h1>

	<em>
          <!-Update num of options, votes. Input Votes.->
		<?php echo "Poll Type: ".$arr[1]." | Poll ID: ".$row['poll_id']."<br />";echo"Number of Options: ".$election->countCandidates() ;?>
		|
		Number of votes :
		<?php echo $election->countVotes() ;?>
	</em>

	<h2>Candidates list :</h2>

	<ul>
	<?php
  //var_dump($arr);
  //echo"<pre>";
  //var_dump($array);
  //echo"</pre>";
  $arr2=$array[$index][1];
  $numVotes=$_SESSION['numVotesArr'];
    for($j=0; $j<count($arr2); $j++)
    {
      $wi=$arr2[$j];
      $cat = substr($wi, 0, strpos($wi, ":"));
      $tempArr=$array[$index][3];
      $vo = $tempArr[$j];
      echo "<li>$cat - $vo Vote(s)</;i>";
    }
  //  echo $election->getCandidateObjectByName("$candidatName");
	//	echo '<li>'.$candidatName." - ".'</li>' ;

	?>
	</ul>

  <!//originally Resgistered votes details ->


<?php
//echo votes h2
/*
	foreach ($election->getVotesList() as $vote)
	{
	//	echo '<div class="votant">';
		echo '<strong style="color:green;">'.implode(' / ',$vote->getTags()).'</strong>';
		echo "<ul>";
		foreach ($vote as $rank => $value)
		{
			if ($rank == 'tag') {continue ;}
		?>

			<li><?php echo implode(',',$value);?></li>

		<?php
		}
		echo '</ul>' ;
	}
  */
?>
<?php
  $date=$array[$index][2];
  if(date('Y-m-d H:i:s') > $date)
  {

  }
  else {
    die();
  }
 ?>
<hr style="clear:both;">

	<h2>Winner by <a target="blank" href="http://en.wikipedia.org/wiki/Condorcet_method">natural Condorcet</a> :</h2>

	<strong style="color:green;">
		<?php
		if ( !is_null($election->getWinner()) )
			{ echo $election->getWinner() ;}
		else
			{ echo '<span style="color:red;">The votes of this group do not allow natural Condorcet winner because of <a href="http://fr.wikipedia.org/wiki/Paradoxe_de_Condorcet" target="_blank">Condorcet paradox</a>.</span>'; }
		?>
		<br>
		<em style="color:green;">computed in <?php echo $election->getLastTimer() ; ?> second(s).</em>	</strong>

	<h2>Loser by <a target="blank" href="http://en.wikipedia.org/wiki/Condorcet_method">natural Condorcet</a> :</h2>

	<strong style="color:green;">
		<?php
		if ( !is_null($election->getLoser()) )
			{ echo $election->getLoser() ;}
		else
			{ echo '<span style="color:red;">The votes of this group do not allow natural Condorcet loser because of <a href="http://fr.wikipedia.org/wiki/Paradoxe_de_Condorcet" target="_blank">Condorcet paradox</a>.</span>'; }
		?>
		<br>
		<em style="color:green;">computed in <?php echo $election->getLastTimer() ; ?> second(s).</em>	</strong>
	</strong>

<br><br><hr>

<?php
	foreach (Condorcet::getAuthMethods() as $method)
	{ ?>

		<h2>Ranking by <?php echo $method ?>:</h2>

		<?php
			$result = $election->getResult($method) ;
			$lastTimer = $election->getLastTimer() ;
			if ( $method === 'Kemeny–Young' && !empty($result->getWarning(\Condorcet\Algo\Methods\KemenyYoung::CONFLICT_WARNING_CODE)) )
			{
				$kemeny_conflicts = explode( ';', $result->getWarning(\Condorcet\Algo\Methods\KemenyYoung::CONFLICT_WARNING_CODE)[0]['msg'] ) ;
				echo '<strong style="color:red;">Arbitrary results: Kemeny-Young has '.$kemeny_conflicts[0].' possible solutions at score '.$kemeny_conflicts[1].'</strong>' ;
			}
		 ?>

		<pre>
		<?php
    $temp = print_r( CondorcetUtil::format($result), true );
    $print = substr(substr($temp,0,-3),7);
    echo "$print";
    ?>
		</pre>

		<em style="color:green;">computed in <?php echo $lastTimer ; ?> second(s).</em>

	<?php }
?>
<br><br><hr><br>
<strong style="color:green;">Total computed in <?php echo $election->getGlobalTimer() ; ?> second(s).</strong>
<br>
<?php var_dump($election->getTimerManager()->getHistory()); ?>
<br><br><hr>

<h2>Computing statistics :</h2>

	<h3>Pairwise :</h3>

	<pre>
	<?php var_dump( CondorcetUtil::format($election->getPairwise()) ); ?>
	</pre>

	<?php
	foreach (Condorcet::getAuthMethods() as $method)
	{ ?>
		<h3>Stats for <?php echo $method ?>:</h3>

		<pre>
		<?php var_dump( CondorcetUtil::format($election->getResult($method)->getStats()) ); ?>
		</pre>

	<?php } ?>

 <br><br><hr>

<h2>Debug Data :</h2>

 <h4>Defaut method (not used explicitly before) :</h4>

 <pre>
<?php var_dump( CondorcetUtil::format(Condorcet::getDefaultMethod()) ); ?>
 </pre>

<!-- <h4>CondorcetUtil::format (for debug only) :</h4>
 <pre>
<?php // CondorcetUtil::format($election); ?>
 </pre> -->

 </body>
 </html>
