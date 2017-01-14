<?php
$html = file_get_contents("byyear.html");
$doc = new DOMDocument();
$doc->loadHTML($html); 

$xpath = new DOMXPath($doc);

foreach ($xpath->query('//a') as $link) {
     $links.= $link->getAttribute('href')."\n";
}
$file = 'links-by-year.txt';
// Open the file to get existing content
 $current = file_get_contents($file);
//  Append a new person to the file
//  Write the contents back to the file
 file_put_contents($file, $links);
print_r($links);
