<html>
<head>
<!--
//Ben	20170507
// This script does update for a entry.
-->
	<title>Search By Name</title>
</head>
<body>
<?php
$ProteinID=$_GET['prot_id'];//获取得到protein_id的值
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
		
		$myquery = "SELECT protein.ID,protein.symbol,protein.name,protein.taxon,sequence.seq  FROM protein INNER JOIN sequence ON protein.ID =sequence.id WHERE protein.ID='$ProteinID'";
		$mydatabase_result=$mysqli->query($myquery);
		//判断返回结果是否为空
		if($mydatabase_result->num_rows ==0){
			echo "<font color = red>Sorry,There is no such records in our database</font>";
		}
		else{
		print"<form action='' method='post'><table border=thin><BR>";
		$row=$mydatabase_result->fetch_assoc();
		$prot_id = $row['ID'];
		$symbol = $row['symbol'];
		$name = $row['name'];
		$sequence = $row['seq'];
		$sequence = substr($sequence,0,20)."........";
		$taxon_id = $row['taxon'];
		print"<tr><td>prot_id</td><td>" . $prot_id. "</td></tr>";
		print"<tr><td>symbol</td><td><input name='symbol_u' value='" . $symbol . "'></td></tr>";
		print"<tr><td>taxon_id</td><td><input name='taxon_id_u' value='" . $taxon_id ."'></td></tr>";
		print"<tr><td>name</td><td><input name='name_u' value='" . $name ."'></td></tr>";
		print"<tr><td>sequence</td><td><input name='sequence_u' value='" . $sequence . "'></td></tr></table>";
		print"<BR><input type=submit name=update value='Update...'>";
		print"</form><BR>";
		}
		
		//进行更新的语句
		if (isset($_POST["update"])) { //点击按钮后进行操作
			$symbol_u=$_POST["symbol_u"];
			$name_u=$_POST["name_u"];
			$taxon_id_u=$_POST["taxon_id_u"];
			$sequence_u=$_POST["sequence_u"];
		$con=mysqli_connect($DB_SERVER, $DB_USER, $DB_PASS, $DB_NAME);
		if (mysqli_connect_errno())
		{
			echo "连接失败: " . mysqli_connect_error();
		}
		else {
		$protein_u="UPDATE protein SET symbol = '$symbol_u' , name = '$name_u' , taxon = '$taxon_id_u'  WHERE ID='$ProteinID'";
		$sequence_u="UPDATE sequence SET symbol='$symbol_u',  name='$name_u' , seq='$sequence_u'  WHERE id='$ProteinID'";
		//更新protein数据表
		if (mysqli_query($con,$protein_u))
		{
		echo "DataBase protein updated"."<BR>";
		}
		else
		{
		echo "Error updating database: " . mysql_error();
		//echo mysql_error();
		}
		//更新sequence数据表
		if (mysqli_query($con,$sequence_u))
		{
		echo "DataBase sequence updated"."<BR>";
		}
		else
		{
		echo "Error updating database: " . mysql_error()."<BR>";
		}
		mysqli_close($con);
		}//连接不成功的else
		}
?>
</body>
</html>