<?php
declare(strict_types=1);
ini_set('memory_limit','-1');

$appDir = __DIR__ . "/..";

$directory = new DirectoryIterator($appDir . "/catalog/models-JSON");


foreach ($directory as $fileInfo) {
  if($fileInfo->isDot()) continue;
  $stripped = Array();
  $fileName = $fileInfo->getFilename();
  $filePath = $appDir . "/catalog/models-JSON/" . $fileInfo->getFilename();
  $destPath = $appDir . "/catalog/models-JSON-stripped/" . $fileInfo->getFilename();
  $errPath = $appDir . "/catalog/log/log.txt";
  echo "reading table $filePath\n";
  if(file_exists($destPath)) continue;
  $file= file_get_contents($filePath);
  /* die($file); */
  $file = mb_convert_encoding($file,"UTF-8");
  $data = json_decode($file,true);

  if(!$data) {
    echo "Fail to read JSON" . json_last_error() . "\n";
    file_put_contents($errPath, "Fail to read JSON, " . $filePath. "\n", FILE_APPEND);
  }

  /* print_r($data); */
  foreach($data as $item)
  {
    foreach($item as $key => $value)
    {
      $strippedKey = strip_tags($key);
      $strippedValue = strip_tags($value);
      /* print_r($strippedKey); */
      /* echo " : "; */
      /* print_r($strippedValue); */
      /* echo "\n"; */

      array_push($stripped, [$strippedKey => $strippedValue]);

    }
  }
  /* print_r(json_encode($stripped)); */
  file_put_contents($destPath,json_encode($stripped));
  /* die; */
}
