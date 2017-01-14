<?php
declare(strict_types=1);
$start = microtime(true);
ini_set('memory_limit','-1');

$appDir = __DIR__ . "/..";
$errPath = $appDir . "/catalog/log/log.txt";
$sourcePath = "/catalog/models-JSON-separated-metric/";
$destPath = "/catalog/models-JSON-for-hugo/";
$frontMatter = [
  "date" => "2017-01-12T22:46:50Z",
  "tags" => [
    "motorbike",
    "spec"
  ],
  "title" => "motorbike"
];

$directory = new DirectoryIterator($appDir . $sourcePath);

foreach ($directory as $fileInfo) {
  if($fileInfo->isDot()) continue;

  $filePath = $appDir . $sourcePath . $fileInfo->getFilename();
  $fileDest = $appDir . $destPath . $fileInfo->getBasename(".php") . ".md";

  //echo "reading table $filePath\n";

  $file= file_get_contents($filePath);
  /* die($file); */
  $file = mb_convert_encoding($file,"UTF-8");
  $data = json_decode($file,true);

  if(!$data) {
    //echo "Fail to read JSON" . json_last_error() . "\n";
    file_put_contents($errPath, "Fail to read JSON, " . $filePath. "\n", FILE_APPEND);
    continue;
  }
  $frontMatter["spec"] = [$data];
  file_put_contents($fileDest,json_encode($frontMatter,JSON_PRETTY_PRINT));
}
$time_elapsed_secs = microtime(true) - $start;
echo "time: $time_elapsed_secs\n";
