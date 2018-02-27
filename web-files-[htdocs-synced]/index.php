<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html>
<title>Daily Mail or not?</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="stylesheet.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
body {font-family: "Lato", sans-serif}
html, body {height: 100%; min-height:100%;}
</style>
<body>

<!-- Navbar -->
<div class="w3-top">
  <div class="w3-bar w3-deep-orange w3-card">
    <a class="w3-bar-item w3-button w3-padding-large w3-hide-medium w3-hide-large w3-right" href="javascript:void(0)" onclick="myFunction()" title="Toggle Navigation Menu"><i class="fa fa-bars"></i></a>
    <a href="#" class="w3-bar-item w3-button w3-padding-large">HOME</a>
    <a href="#about" class="w3-bar-item w3-button w3-padding-large w3-hide-small">ABOUT</a>
  </div>
</div>

<!-- Navbar on small screens -->
<div id="navDemo" class="w3-bar-block w3-deep-orange w3-hide w3-hide-large w3-hide-medium w3-top" style="margin-top:46px">
  <a href="#" class="w3-bar-item w3-button w3-padding-large">HOME</a>
  <a href="#about" class="w3-bar-item w3-button w3-padding-large w3-hide-small">ABOUT</a>
</div>

<!-- Page content -->
<div class="w3-light-blue" style="margin-top:46px;">


  <!-- Quiz text -->
  <div class="w3-center w3-padding-32" style="max-width:800px; margin:auto; height:auto; min-height:100vh;">
    <h2 class="w3-wide"><b>DAILY MAIL OR NOT?</b></h2>
    <p class="w3-opacity w3-large"><i>How good are you at judging the news source of a headline? Take the quiz.</i></p>
	<p class="w3-justify">
	
	<?php if (!isset($_SESSION['score']))
	{
		$_SESSION['score'] = 0; //initialise the score variable
		$_SESSION['nquestions'] = 0;
	}
	?>
	
	<p class="w3-justify w3-large">
	
	<!--Evaluate previous answer, if there is one-->
	<?php
	if ($_SERVER["REQUEST_METHOD"] == "POST") 
	{	
		//echo var_dump($_POST); //if need to see all variables form produces
		//$newsource_input = $_POST["newssource"];
		if ((!empty($_POST["newssource_guardian_x"]) and $_SESSION['newssource']=="guardian") or 
		    (!empty($_POST["newssource_BBCnews_x"]) and $_SESSION['newssource']=="BBCnews") or 
		    (!empty($_POST["newssource_dailymail_x"])) and $_SESSION['newssource']=="dailymail")
		{
			echo "Correct! <br>";
			$_SESSION['score'] = $_SESSION['score']+1;
		}
		else 
		{
			echo "Wrong! This was actually from ";
			if ($_SESSION['newssource'] == 'guardian')
			{
				echo('the Guardian.');
			}
			elseif ($_SESSION['newssource'] == 'BBCnews')
			{
				echo('BBC News.');
			}
			elseif ($_SESSION['newssource'] == 'dailymail')
			{
				echo('the Daily Mail.');
			}
			echo "<br>";
		}
		echo "Score: ";
		echo $_SESSION['score'];
		echo " out of ";
		echo $_SESSION['nquestions'];
		echo " questions";
		echo "<br>";
		
		unset($_POST["newssource_guardian"], $_POST["newssource_BBCnews"], $_POST["newssource_dailymail"]);
		//show picture of correct thingy
	}
	else
	{
		echo "<br>";
	}
	?>
	
	<!-- <?php
		if ($_SERVER["REQUEST_METHOD"] == "POST") 
		{
			$newsource_input = $_POST["newssource_guardian"];
			if ($newsource_input == $x)
			{
				echo "Correct!";
			}
			else 
			{
				echo "Wrong <br>";
				echo $x;
			}
			//show picture of correct thingy
			//need a next question button
		}
	?> -->
	</p>
	
	<!--Get headline for question -->
	<?php
	//Connect to database
	$dir = 'sqlite:headlines.db';
	$dbh  = new PDO($dir) or die("cannot open the database");

	//How many rows in index table?
	$nPapers = $dbh->query('select count(*) from idx')->fetchColumn(); 
	//echo $nPapers; //print number of rows

	//Choose paper at random
	$paperchosen = rand(1, $nPapers);
	//print($paperchosen);

	//Find corresponding table name
	$result = $dbh->query("SELECT newssource FROM idx WHERE id = $paperchosen");
	foreach($result as $row)
	{
		//print($row['newssource']);
		$_SESSION['newssource'] = $row['newssource'];
		//How many rows in newspaper-specific table?
		$nRows = $dbh->query("SELECT count(*) FROM {$_SESSION['newssource']}")->fetchColumn();
		//echo $nRows;
		$headlineChosen = rand(1, $nRows);
		//echo $headlineChosen;
		
		$headline = $dbh->query("SELECT headline FROM {$_SESSION['newssource']} WHERE id = $headlineChosen")->fetchColumn();
		//print $headline;
		#search by row number
		
	}
	
	//Count how many headlines have been displayed in total
	$_SESSION['nquestions'] += 1;
	
	?>
	
	</p>
	<p style="border-style:solid;" class="w3-center w3-white w3-xlarge w3-border-black"><b><?php echo $headline ?></b></p>
	<p class="w3-justify w3-xlarge">Where is this headline from?</p>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
	<div class="w3-row">
    <span>
		
		<!-- <div class="w3-third"> -->
		<!-- <p class="w3-xlarge">The Guardian</p> -->
		<input type="image" name="newssource_guardian" value="guardian" alt="The Guardian masthead" src="images/guardian.png" class="w3-round w3-margin-bottom" style="width: 32%; float:left;">
		<!-- </div> -->
		
		<!-- <div class="w3-third"> -->
		<!-- <p class="w3-xlarge">BBC News</p> -->
		<input type="image" name="newssource_BBCnews" value="BBCnews" alt="BBC news icon" src="images/bbcnews.png" class="w3-round w3-margin-bottom" style="width: 32%">
		<!-- </div> -->
		
		<!-- <div class="w3-third">
		<!-- <p class="w3-xlarge">Daily Mail</p> -->
		<input type="image" name="newssource_dailymail" value="dailymail" alt="Mail Online icon" src="images/mail.png" class="w3-round w3-margin-bottom" style="width: 32%; float:right;">
		<!-- </div> -->
		
		<!-- <button type="submit" name="someName" value="someValue"><img src="guardian.png" alt="SomeAlternateText"></button> -->
	
      <!-- <div class="w3-third">
        <p><input type="radio" name="newssource" value="guardian">The Guardian</p>
		<img src="guardian.png" class="w3-round w3-margin-bottom" alt="The Guardian masthead" style="width:60%">
      </div>
      <div class="w3-third">
        <p><input type="radio" name="newssource" value="BBCnews">BBC News</p>
        <img src="bbcnews.png" class="w3-round w3-margin-bottom" alt="BBC News icon" style="width:60%">
      </div>
      <div class="w3-third">
        <p><input type="radio" name="newssource" value="dailymail">Daily Mail</p>
        <img src="mail.jpg" class="w3-round" alt="Mail Online icon" style="width:60%">
      </div>
	  <p class="w3-center"><input type="submit" name="submit" value="Submit"></p>  -->
	  
	  
    </div>
	</span>
	</form>
  </div>

	<!--About-->
  <div class="w3-light-grey" id="about">
  <div class="w3-content w3-center w3-light-grey w3-padding-64" style="max-width:800px; margin:auto; height:auto; min-height:100vh;">
  <h2 class="w3-wide"><b>ABOUT</b></h2>
  <p class="w3-large w3-justify">
  I'm interested in perceptions of media bias, and whether it can be measured objectively. This website allows you to test how good you are at matching headlines to their news outlet. If you have questions or feedback, you can contact me on   <i class="fa fa-envelope" style="width:30px"></i>contact [AT] compareheadlines[DOT]online.  
  </p>

	<!--
	<div class="w3-col m6 w3-large w3-margin-bottom">
	<i class="fa fa-map-marker" style="width:30px"></i> Chicago, US<br>
	<i class="fa fa-phone" style="width:30px"></i> Phone: +00 151515<br>
	<i class="fa fa-envelope" style="width:30px"> </i> Email: mail@mail.com<br>
	</div> -->
   
  </div>
  </div>
  
<!-- End Page Content -->
</div>


<!-- Footer -->
<footer class="w3-container w3-padding-8 w3-center w3-opacity w3-light-grey w3-xlarge">
<!--
  <i class="fa fa-facebook-official w3-hover-opacity"></i>
  <i class="fa fa-instagram w3-hover-opacity"></i>
  <i class="fa fa-snapchat w3-hover-opacity"></i>
  <i class="fa fa-pinterest-p w3-hover-opacity"></i>
  <i class="fa fa-twitter w3-hover-opacity"></i>
  <i class="fa fa-linkedin w3-hover-opacity"></i>
  -->
  <p class="w3-small">The HTML template for this wesbite was modified from a template by <a href="https://www.w3schools.com/" target="_blank">W3 schools</a></p>
</footer>

</body>
</html>