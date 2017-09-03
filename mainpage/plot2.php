<?php // content="text/plain; charset=utf-8"
require_once ('jpgraph/src/jpgraph.php');
require_once ('jpgraph/src/jpgraph_line.php');
require_once ('jpgraph/src/jpgraph_bar.php');
$fc=array($_GET['v']);
//$datay=array(12,8,19,3,10,5);
 // Width and height of the graph*/
$width = 300; $height = 200;
 
// Create a graph instance
$graph = new Graph($width,$height);
 
// Specify what scale we want to use,
// int = integer scale for the X-axis
// int = integer scale for the Y-axis
$graph->SetScale('intlin');
 
// Setup a title for the graph
$graph->title->Set('Plot for FoldChange');
 
// Setup titles and X-axis labels
$graph->xaxis->title->Set('(experiment)');
 
// Setup Y-axis title
$graph->yaxis->title->Set('(Fold Change)');
 
// Create the linear plot
//$lineplot=new LinePlot($ydata);
//$barplot=new BarPlot($fc);
$barplot=new BarPlot($fc);
//$lineplot->SetFillColor('orange@0.5');
 
// Add the plot to the graph
$graph->Add($barplot);
 
// Display the graph
$graph->Stroke();
 
?>