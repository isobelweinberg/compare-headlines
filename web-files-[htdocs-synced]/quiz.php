##Create question

#Count how many papers
<?php
//Connect to database
$dir = 'sqlite:C:\Users\Isobel\Dropbox\Coding projects\compare-editorial-line\headlines.db';
$dbh  = new PDO($dir) or die("cannot open the database");

//How many rows in index table?
$nPapers = $dbh->query('select count(*) from idx')->fetchColumn(); 
//echo $nPapers; //print number of rows

//Choose paper at random
$paperchosen = rand(1, $nPapers);
print($paperchosen);

//Find corresponding table name
$result = $dbh->query("SELECT newssource FROM idx WHERE id = $paperchosen");
foreach($result as $row)
{
	print($row['newssource']);
	$x = $row['newssource'];
	//How many rows in newspaper-specific table?
	$nRows = $dbh->query("SELECT count(*) FROM $x")->fetchColumn();
	echo $nRows;
	$headlineChosen = rand(1, $nRows);
	echo $headlineChosen;
	
	$headline = $dbh->query("SELECT headline FROM $x WHERE id = $headlineChosen")->fetchColumn();
	print $headline;
	#search by row number
	
}
?>

