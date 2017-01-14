<?php
$appDir = __DIR__ . '/..';
foreach (new DirectoryIterator($appDir . "/catalog/models-by-year-tables") as $fileInfo) {
  $links = "";
  if($fileInfo->isDot()) continue;
  $fileName = $fileInfo->getFilename();
  $filePath = $appDir . "/catalog/models-by-year-tables/" . $fileInfo->getFilename();
  $html = file_get_contents($filePath);
  echo "reading $filePath\n";
  $doc = new DOMDocument();
  $doc->loadHTML($html); 

  $xpath = new DOMXPath($doc);

  foreach ($xpath->query('//a') as $link) {
     $links.= $link->getAttribute('href')."\n";
  }

  file_put_contents($appDir . "/catalog/models-by-year-links/$fileName",$links);
  echo "writing" .  $appDir . "/catalog/models-by-year-links/$fileName\n";
}
