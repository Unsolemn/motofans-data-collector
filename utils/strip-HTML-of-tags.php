<?php
declare(strict_types=1);
ini_set('memory_limit','-1');

$appDir = __DIR__ . "/..";
$sourceDir = $appDir . "/catalog/models-tables";
$directory = new DirectoryIterator($sourceDir);


foreach ($directory as $fileInfo) {
  if($fileInfo->isDot()) continue;
  $stripped = Array();
  $fileName = $fileInfo->getFilename();
  $filePath = $sourceDir . "/" . $fileInfo->getFilename();
  $destPath = $appDir . "/catalog/models-HTML-stripped/" . $fileInfo->getFilename();
  $errPath = $appDir . "/catalog/log/log.txt";
  echo "reading table $filePath\n";
  if(file_exists($destPath)) continue;
  $file= file_get_contents($filePath);
  /* die($file); */
  $file = mb_convert_encoding($file,"UTF-8");

  $stripped = strip_tags($file,'<table><tr><td><th><thead><tbody><tfoot><caption><colgroup><col>');
  /* print_r(json_encode($stripped)); */
  file_put_contents($destPath,$stripped);
  /* die; */
}
