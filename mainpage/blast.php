<?php
 if ( sizeof($_POST) ) {
    $Name = 'search.fa';
    $Cont = $_POST['blast'] or die('未输入要查询的内容');
    @file_put_contents( $Name, $Cont ) or die('文件写入失败，请检查目录权限');
 }
 system('blastp -query  E:/xampp/htdocs/main_page/search.fa -db E:/xampp/htdocs/main_page/homework/homeworkdb -html -out output.html',$return);
 $test=system('ls');
    $SearchReault = file_get_contents("output.html");
    echo $SearchReault;
	//system('rm -f output.html')
 ?>