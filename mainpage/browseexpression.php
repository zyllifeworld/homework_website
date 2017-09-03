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
echo "<b>Browse For".$_POST['Number']."Records</b><br>";
if(isset($_POST)){
	$Number = intval(trim($_POST['Number']));	
	if($Number == ''){
		print "<font color = red>You have not input all required data!</font><BR><BR>";
	}else{
		$mysqli = new mysqli($DB_SERVER, $DB_USER, $DB_PASS, $DB_NAME);
		//判断连接是否成功
		if ($mysqli->connect_error) 
		{
			die("Connection Fail: " . $conn->connect_error."<br>");
		}
		echo "Connection Successed"."<br>";
		//echo $taxon_id."<br>";
		$index = 0;
		$myquery = "SELECT * FROM expression LIMIT $Number";
		$result=$mysqli->query($myquery);
		//判断返回结果是否为空
		if($result->num_rows ==0){
			echo "<font color = red>Sorry,There is no such records in our database</font>";
		}
		else{
		print "<strong style=font-size:40px;color:red>Search Result</strong>";
		print "<table border=thin><BR>";
		print "<tr><td>idorig</td><td>fc</td><td>P-value</td><td>Q-value</td><td>idexpe</td><td>idprot</td></tr><BR>";
		while($row=$result->fetch_assoc()){
			$idorig = $row['idorig'];
			$fc = $row['fc'];
			$p = $row['p'];
			$q = $row['q'];
			$idexpe = $row['idexpe'];
			$idprot = $row['idprot'];
			print "<tr>";
			print"<td>". "<a href='https://www.ncbi.nlm.nih.gov/nuccore/" . $idorig ."'>". $idorig . "</a></td><td>$fc</td><td>$p</td><td>$q</td><td>$idexpe</td><td>$idprot</td>";
			print "</tr>";	
		}
		print "</table>";
		}
	}
}
?>
</fieldset>
</body>
</html>