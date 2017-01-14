<?php
declare(strict_types=1);
$start = microtime(true);
ini_set('memory_limit','-1');

$appDir = __DIR__ . "/..";
$sourcePath = $appDir . "/catalog/models-JSON";
$errPath = $appDir . "/catalog/log/log.txt";

$directory = new DirectoryIterator($sourcePath);

foreach ($directory as $fileInfo) {
  if($fileInfo->isDot()) continue;

  $normalized = Array();
  $currentValue = "";
  $fileName = $fileInfo->getFilename();
  $filePath = $sourcePath . "/" . $fileInfo->getFilename();
  $destPath = $appDir . "/catalog/models-JSON-normalized/" . $fileInfo->getFilename();

  //echo "reading table $filePath\n";

  if(file_exists($destPath)) continue;
  $file= file_get_contents($filePath);
  /* die($file); */
  $file = mb_convert_encoding($file,"UTF-8");
  $data = json_decode($file,true);

  if(!$data) {
    //echo "Fail to read JSON" . json_last_error() . "\n";
    file_put_contents($errPath, "Fail to read JSON, " . $filePath. "\n", FILE_APPEND);
  }

  /* print_r($data); */
  /* die; */
  foreach($data as $item)
  {
    /* print_r($item); */
    foreach($item as $currentKey => $value)
    {
      /* echo "normalizing $currentKey : $value\n"; */
      if(strpos($currentKey,"Column") !== false) {
        $currentValue = rtrim($value,".");
        /* echo "keep $currentValue as value\n"; */
      }
      else {
        $currentTableKey = rtrim($value,":");
        /* echo "keep $currentTableKey as key\n"; */
      }
    }
    /* print_r([$currentKey,$currentValue]); */
    if($currentTableKey !== "" && $currentValue !== ""){
      $normalized[$currentTableKey] = $currentValue;
      $currentValue = $currentKey = "";
      /* echo "push normalized row\n"; */
      /* print_r($normalized); */
    } else {
      /* echo "empty key or value\n"; */
    }
    /* print_r($item); */
    /* die; */
  }
  /* print_r(json_encode($normalized)); */
  echo "writing table $destPath\n";
  file_put_contents($destPath,json_encode($normalized));
  /* die; */
}
$time_elapsed_secs = microtime(true) - $start;
echo "time: $time_elapsed_secs\n";
