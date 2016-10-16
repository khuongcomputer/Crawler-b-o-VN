<?php
session_start();
require_once("../function/function.php");
require_once("../config.php");
$sql = new mysqli(	config('db.servername'),
					config('db.username'),
					config('db.password'),
					config('db.name'));

// Temporary variable, used to store current query
$templine = '';
// Read in entire file
$lines = file("./sql/database.sql");
// Loop through each line
foreach ($lines as $line)
{
// Skip it if it's a comment
if (substr($line, 0, 2) == '--' || $line == '')
    continue;

// Add this line to the current segment
$templine .= $line;
// If it has a semicolon at the end, it's the end of the query
if (substr(trim($line), -1, 1) == ';')
{
    // Perform the query
    $sql->query($templine) or print('Error performing query \'<strong>' . $templine . '\': ' . $sql->error . '<br /><br />');
    // Reset temp variable to empty
    $templine = '';
}
}
 echo "Tables imported successfully<br />";
 
$sql->query(insert('urls',['url'=>'http://vnexpress.net'],'url'));

//create data directory

mkdir('../data');
mkdir('../data/news');
mkdir('../data/comments');