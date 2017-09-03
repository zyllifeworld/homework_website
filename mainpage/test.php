<?php
 system('blastp -query search.fa -db /home/201618012115047/project/homework_2/homeworkdb -html -out output.html',$return);
 $test=system('ls');
 echo $test;
    $SearchReault = file_get_contents("output.html");
    echo $SearchReault;
	system('rm -f output.html')
 ?>