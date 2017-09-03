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
		print"<form action='' method='post'><table border=thin><BR>";
		print"<tr><td>The reason you deleted this record</td><td><input name='reason' value='no-reason'></td></tr>";
		print"<BR><input type=submit name=delete value='Delete'>";
		print"</form><BR>";
		
		//进行更新的语句
		if (isset($_POST["delete"])) { //点击按钮后进行操作
			$reason=$_POST["reason"];
		$con=mysqli_connect($DB_SERVER, $DB_USER, $DB_PASS, $DB_NAME);
		if (mysqli_connect_errno())
		{
			echo "连接失败: " . mysqli_connect_error();
		}
		else{
		$protein_d="DELETE FROM protein WHERE ID='$ProteinID'";
		$sequence_d="DELETE FROM sequence WHERE id='$ProteinID'";
		//判断是否输入了原因
		if($reason == "no-reason") {
			echo "sorry,we cann't let you delete our record for no reason";
		}
		else {
		if (mysqli_query($con,$sequence_d))
		{
		echo "DataBase sequence'record ". $ProteinID ." deleted."."The reason is:  ". $reason ."<BR>";
		}
		else
		{
		echo "Error deleting database: " . mysql_error()."<BR>";
		}
				//删除protein数据表,不知道为什么掉了一下位置就可以了
		
		if (mysqli_query($con,$protein_d))
		{
		echo "DataBase protein's record ". $ProteinID ." deleted."."The reason is:  ". $reason ."<BR>";
		}
		else
		{
		echo "Error deleting database: " . mysql_error()."<BR>";
		//echo mysql_error();
		}
		//删除sequence数据表
		}//判断原因是否存在的else
			mysqli_close($con);
		}//连接不成功的else
		}
?>
</body>
</html>