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
//ADD TAGS FOR 1st 2nd 3rd 4th etc... choice and CATEGORY
  $election = new Election () ;

  // Create your own candidate object
  $candidate1 = new Candidate ('John');
  $candidate2 = new Candidate ('Greg');
  $candidate3 = new Candidate ('Jack');
  $candidate4 = new Candidate ('p');
  // Register your candidates
  $election->addCandidate($candidate1);
  $election->addCandidate($candidate2);
  $election->addCandidate($candidate3);
    $election->addCandidate($candidate4);
//  $candidate4 = $election->addCandidate('Candidate 4'); //stores everything
  //in var?

  /*$myVote3 = new Vote( array(
                             1 => ['John','p'],
                             2 => 'Greg',
                             //3 => 'p',
                             3 => 'Jack'
                             //2 => 'Jack'
 ));
*/
$myVote3 = $election->addVote('John = Greg = p = Jack');
 //$myVote3 = $election->addVote('John > Greg > p > Jack');
 //$election->addVote($myVote3);
$myVote3->addTags('first');



//$votesArray=$_SESSION['votesArray'];
//print_r($votesArray);
//$election1->getResult('Schulze')->getResultAsArray(true);
//$r=$election->getResult('Schulze')->getResultAsArray(true);
 //$r=$election->getCandidatesList();
 //$r=$myVote3->getRanking($election);
//Session Var for poll Name
    //This below (2 lines) is how i will get my num of canidates and num of total votes
    //print_r($election->getCandidatesList(true));
    // echo "<br>".$election->getCandidatesList(true)[1]."<br>".$election->countVotes()."<br>";
$array= $_SESSION['arr'];
$index = $_GET['index'];

$votesArray=$array[$index][5];
echo"Before: ";
print_r($votesArray);
echo "<br>";

for($l=0;$l<count($votesArray); $l++)
{
    array_multisort(array_column($votesArray[$l], 'place'), SORT_ASC,SORT_NUMERIC,$votesArray[$l]);
}
echo "<br>After: ";
print_r($votesArray);


$options=$array[$index][4];
//print_r($array[$index][4]);
echo "optoins Arr:<br>";
print_r($options);
//echo "<br>".$options[1]['option'];

echo"<br><br>A5:";
print_r($votesArray[5]);
$hgf=$votesArray[5];
//echo $hgf[1]['option'];
//echo count($votesArray);
//$out="";
//$firstDone=false;
echo "<br><br>";
//echo "<br>".$out;
          //CREATING Election
$electionREAL= new Election();

//add canidates. can use any index from votesarr
for($ii=0; $ii<count($votesArray[0]);$ii++){
  $electionREAL->addCandidate($votesArray[0][$ii]['option']);
}
for($ii=0; $ii<count($votesArray);$ii++){
  $out="";
  $firstDone=false;
  $hgf=$votesArray[$ii];
  for($i=0; $i<(count($hgf)-1); $i++)           //IMPORTANT: IF ALL VOTES ARE 0 THEN YOU SHOULD NOT PUT AT ALL.
  {                                             //cHECK IF STRING IS NULL WHEN IMPLEMENTING THIS!!!!!
                                              //IF ALL VOTES ARE 3 OR 2 OR 1 OR ANY NON ZERO THEN YOU CAN
  //echo "<br>".$hgf[$i]['option'];           //INPUT THEM. CONDORCET ALLOWS YOU IT.
    if(!$hgf[$i]['place']==0){
      if($firstDone==false){
        if($hgf[$i]['place'] > $hgf[$i+1]['place'])
        $out.=$hgf[$i]['option']." < ".$hgf[$i+1]['option'];
        else if($hgf[$i]['place'] < $hgf[$i+1]['place'])
        $out.=$hgf[$i]['option']." > ".$hgf[$i+1]['option'];
        else {
          $out.=$hgf[$i]['option']." = ".$hgf[$i+1]['option'];
        }
        $firstDone=true;
      }else{
        if($hgf[$i]['place'] > $hgf[$i+1]['place'])
        $out.=" < ".$hgf[$i+1]['option'];
        else if($hgf[$i]['place'] < $hgf[$i+1]['place'])
        $out.=" > ".$hgf[$i+1]['option'];
        else {
          $out.=" = ".$hgf[$i+1]['option'];
        }
      }
    }else if(($i+1)==(count($hgf)-1)){
      $out.=$hgf[$i+1]['option'];
    }
  }
//  echo "<br>".$out;
  $vote = $electionREAL->addVote($out);
  //$vote->addTags();     //TAGS MAY NOT BE THE BEST IDEAer.
}
//echo $electionREAL->countVotes();












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
		<?php echo $electionREAL->countVotes() ;?>
	</em>

	<h2>Candidates list (Stats go here?):</h2>

	<ul>
	<?php
  //var_dump($arr);
  //echo"<pre>";
  //var_dump($array);
  //echo"</pre>";
  //Since condorchet only depends on order, the display should be #people voted first, second etc... Do this through tags
  //test
//  *****************if($_SESSION['anyVotes']==false)
  //die();

  $arr2=$array[$index][1];
  $numVotes=$_SESSION['numVotesArr'];
    for($j=0; $j<count($arr2); $j++)
    {
      $wi=$arr2[$j];
      $cat = substr($wi, 0, strpos($wi, ":"));
      $tempArr=$array[$index][3];
      $vo = $tempArr[$j];
      echo "<li>$cat</;i>";
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
  //if($_SESSION['anyVotes']==true)

  ?>


<?php
  $date=$array[$index][2];  //|| $_SESSION['anyVotes']==true
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
