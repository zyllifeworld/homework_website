<?php
$ProteinID=$_GET['prot_id'];//获取得到protein_id的值
	 //error_reporting(0);
header("Content-Type:text/html; charset=utf-8");
session_start();
set_time_limit(0);
require "confzyl.php";
$user_id = "zyl";//$_SESSION['username'];
print "<fieldset><legend>Hello, " . $user_id . "!"  . "</legend><br>";
//进行查询与输出
		$mysqli = new mysqli($DB_SERVER, $DB_USER, $DB_PASS, $DB_NAME);
		//判断连接是否成功
		if ($mysqli->connect_error) 
		{
			die("Connection Fail: " . $conn->connect_error."<br>");
		}
		echo "Connection Successed"."<br>";
		//echo $taxon_id."<br>";
		$index = 0;
		//$myquery = "SELECT protein.ID,protein.symbol,protein.name,protein.taxon,sequence.seq  FROM protein INNER JOIN sequence ON protein.ID =sequence.id WHERE taxon = '$taxon_q' LIMIT $Number";
		
		//在本地数据库中查询
		
		$myquery = "SELECT protein.ID,protein.symbol,protein.name,protein.taxon,sequence.seq  FROM protein INNER JOIN sequence ON protein.ID =sequence.id WHERE protein.ID='$ProteinID'";//服务器端要用小写
		$mydatabase_result=$mysqli->query($myquery);
		//判断返回结果是否为空
		if($mydatabase_result->num_rows ==0){
			echo "<font color = red>Sorry,There is no such records in our database</font>";
		}
		else{
		print "<strong style=font-size:40px;color:red>Search Result</strong>";
		$row=$mydatabase_result->fetch_assoc();
		$prot_id = $row['ID'];
		$symbol = $row['symbol'];
		$name = $row['name'];
		$sequence = $row['seq'];
		$sequence = substr($sequence,0,20)."........";
		$taxon_id = $row['taxon'];

		print "<table border=thin><BR>";
		print"<tr><td>prot_id</td><td>$prot_id</td></tr>";
		print"<tr><td>symbol</td><td>$symbol</td></tr>";
		print"<tr><td>taxon_id</td><td>$taxon_id</td></tr>";
		print"<tr><td>name</td><td>$name</td></tr>";
		print"<tr><td>sequence</td><td>$sequence</td></tr>";
		print "</table><BR>";
		}
		
		
		//在表达数据库中进行查询
		$sele=rand(2,18357)-2;
		$myquery = "SELECT * FROM expression LIMIT $sele,1";
		//print "$myquery";
		$result=$mysqli->query($myquery);
		if($result->num_rows ==0){
			echo "<font color = red>Sorry,There is no such records in our database</font>";
		}
		else{
		$row=$result->fetch_assoc();
		$fc = $row['fc'];
		$p = $row['p'];
		$q = $row['q'];
		$idexpe = $row['idexpe'];
		$idorig=$row['idorig'];

		print "<table border=thin><BR>";
		print"<tr><td>GeneBank ID</td><td>$idorig</td></tr>";
		print"<tr><td>Fold Change</td><td>$fc</td></tr>";
		print"<tr><td>p-value</td><td>$p</td></tr>";
		print"<tr><td>q-value</td><td>$q</td></tr>";
		print"<tr><td>Experiment ID</td><td>$idexpe</td></tr>";
		print "</table><BR>";
		}
		print "<a href='plot2.php?v=$fc' target=_plot>Bar Plot...</a>";
		print "<a href='update.php?prot_id=" . $ProteinID. "' target='_blank'>Update...</a>      <a href='delete.php?prot_id=" . $ProteinID. "' target='_blank'>delete...</a>";
?>