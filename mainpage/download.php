<?php
$Number=20;
$Number_d=$Number*2;
$download="head -$Number_d  proteins.fasta > download.fa";
echo $download;
 system($download,$return);
 ?>