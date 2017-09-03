<html>
<head>
<!--
// This script adds data to a database table.
// Created on: 20170315
// Created by: Ben Han
// Modified on: 20170329
// Modified by: Ben Han
-->
	<title>SearchResult</title>
	<a href="http://159.226.67.97/student2017/201618012115047/main_page/index.html">Back To Mainpage</a>
</head>
<body>

<?php
//error_reporting(0);
header("Content-Type:text/html; charset=utf-8");
session_start();
set_time_limit(0);
require "confzyl.php";
$user_id = "zyl";//$_SESSION['username'];
print "<fieldset><legend>Hello, " . $user_id . "!"  . "</legend><br>";
$transform=array("9606"=>"human","10090"=>"mouse");
//echo "<b>Search For ID:</b>".$_POST['ProteinID']."<br>";
echo "<b>Browse For Taxon:</b>".$_POST['taxon']."For".$_POST['Number']."<br>";
if(isset($_POST)){
	$Number = intval(trim($_POST['Number']));
	$taxon = $_POST['taxon'];
	
	if(($Number == '')||($taxon == '')){
		print "<font color = red>You have not input all required data!</font><BR><BR>";
	}else{
		if(strcmp($taxon,"human"))
		{
			$taxon_q='10090';
		}
		else if(strcmp($taxon,"mouse"))
		{
			$taxon_q='9606';
		}
		$mysqli = new mysqli($DB_SERVER, $DB_USER, $DB_PASS, $DB_NAME);
		//判断连接是否成功
		if ($mysqli->connect_error) 
		{
			die("Connection Fail: " . $conn->connect_error."<br>");
		}
		echo "Connection Successed"."<br>";
		//echo $taxon_id."<br>";
		$index = 0;
		$myquery = "SELECT protein.ID,protein.symbol,protein.name,protein.taxon,sequence.seq  FROM protein INNER JOIN sequence ON protein.ID =sequence.id WHERE taxon = '$taxon_q' LIMIT $Number";
		$result=$mysqli->query($myquery);
		//判断返回结果是否为空
		if($result->num_rows ==0){
			echo "<font color = red>Sorry,There is no such records in our database</font>";
		}
		else{
		print "<strong style=font-size:40px;color:red>Search Result</strong>";
		print "<table border=thin><BR>";
		print "<tr><td>Gene_Symbol</td><td>Protein_id</td><td>Protein_Name</td><td>Taxon</td><td>Protein_sequence</td><td>More</td></tr><BR>";
		while($row=$result->fetch_assoc()){
			$prot_id = $row['ID'];
			$symbol = $row['symbol'];
			$name = $row['name'];
			$sequence = $row['seq'];
			$sequence = substr($sequence,0,20)."........";
			$taxon_id = $row['taxon'];
			print "<tr>";
			print"<td>$symbol</td><td>". "<a href='http://www.ebi.ac.uk/QuickGO/GSearch?q=" . $prot_id ."#1=2'>". $prot_id . "</a></td><td>$name</td><td>$taxon</td><td>$sequence</td><td><a href='more.php?prot_id=" . $prot_id . "'>more</a></td>";
			print "</tr>";	
		}
		print "</table>";
		}
		system('head -'. $Number*2 . '/opt/lampp/htdocs/student2017/201618012115047/main_page/proteins.fasta > download.fa',$return);
		echo "<a href='download.fa' download='dbdownload.fa'>download_as_fasta</a>";
	}
}
?>
</fieldset>
</body>
</html>