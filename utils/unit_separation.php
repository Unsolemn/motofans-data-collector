<?php
declare(strict_types=1);
$start = microtime(true);
ini_set('memory_limit','-1');

$appDir = __DIR__ . "/..";
$errPath = $appDir . "/catalog/log/log.txt";
$separatedUnits = [
  "cubic inches",
  "inches",
  "pounds",
  "gallons",
  "mph",
  "quarts",
  "mpg",
  "ft.lbs",
];
$directory = new DirectoryIterator($appDir . "/catalog/models-JSON-normalized");

foreach ($directory as $fileInfo) {
  if($fileInfo->isDot()) continue;

  $separated = Array();
  $fileName = $fileInfo->getFilename();
  $filePath = $appDir . "/catalog/models-JSON-normalized/" . $fileInfo->getFilename();
  $destPath = $appDir . "/catalog/models-JSON-separated/" . $fileInfo->getFilename();

  //echo "reading table $filePath\n";

  /* if(file_exists($destPath)) continue; */
  $file= file_get_contents($filePath);
  /* die($file); */
  $file = mb_convert_encoding($file,"UTF-8");
  $data = json_decode($file,true);

  if(!$data) {
    //echo "Fail to read JSON" . json_last_error() . "\n";
    file_put_contents($errPath, "Fail to read JSON, " . $filePath. "\n", FILE_APPEND);
  }

  /* print_r($data); */
  foreach($data as $key => $value)
  {
    if(strpos($value," or ")) continue;
    $isMixed = false;
    $hasComment = false;
    foreach($separatedUnits as $unit)
    {
      $matches = null;
      $returnValue = preg_match("/\\(.* $unit.*\\)/i", $value, $matches);
      if($returnValue){
        $removedParenth = str_replace(
          array( '(', ')' ),
          '',
          $matches[0]
        );
        $removedText = str_replace($unit,"",$removedParenth);
        /* echo $matches[0]; */
        /* echo "\n"; */
        /* echo $removedText; */
        /* echo "\n"; */
        $separated[$key] = trim(str_replace($matches[0],"",$data[$key]));
        $separated[$key.", ".$unit] = trim($removedText);
        /* echo "found $unit\n"; */
        $isMixed = true;

        break;
      }
    }
    if(!$isMixed) $separated[$key] = $value;
  }
  /* print_r(json_encode($separated)); */
  //echo "writing table $destPath\n";
  file_put_contents($destPath,json_encode($separated));
  /* die; */
}
$time_elapsed_secs = microtime(true) - $start;
echo "time: $time_elapsed_secs\n";
