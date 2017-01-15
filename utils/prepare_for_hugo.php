<?php
declare(strict_types=1);
$start = microtime(true);
ini_set('memory_limit','-1');

require __DIR__ . '/../vendor/autoload.php';
use Cocur\Slugify\Slugify;

$appDir = __DIR__ . "/..";
$errPath = $appDir . "/catalog/log/log.txt";
$sourcePath = "/catalog/models-JSON-separated-metric/";
$destPath = "/catalog/models-JSON-for-hugo/";
$dataRequired = ["Model", "Year", "Category"];
$frontMatter = [
  "date" => "2017-01-12T22:46:50Z",
  "tags" => [
    "motorbike",
    "spec"
  ]
];

$slugify = new Slugify();

$directory = new DirectoryIterator($appDir . $sourcePath);

foreach ($directory as $fileInfo) {
  if($fileInfo->isDot()) continue;
  $err = false;

  $fileBasename = $fileInfo->getBasename(".php");
  $filePath = $appDir . $sourcePath . $fileInfo->getFilename();
  $fileDest = $appDir . $destPath . $fileBasename . ".md";
  list($brandName, $modelName) = explode('_', $fileBasename, 2);

  //echo "reading table $filePath\n";

  $file= file_get_contents($filePath);
  /* die($file); */
  $file = mb_convert_encoding($file,"UTF-8");
  $data = json_decode($file,true);
  if(!$data || count($data) < 1) {
    //echo "Fail to read JSON" . json_last_error() . "\n";
    file_put_contents($errPath, "Fail to read JSON, " . $filePath. "\n", FILE_APPEND);
    continue;
  }

  foreach($dataRequired as $curRequired){
    /* echo "checkind required data $curRequired\n"; */
    if(!isset($data[$curRequired])) {
    /* if(!array_key_exists($curRequired, $data)) { */
      file_put_contents($errPath, "Required data missed: " . $curRequired . " " . $filePath . "\n", FILE_APPEND);
      $err = true;
      break;
    }
  }
  if($err) continue;

  $frontMatter["url"] = "spec/" . $slugify->slugify($brandName) . "/" . $data["Year"] ."/". $slugify->slugify($data["Model"]);
  $frontMatter["title"] = $data["Category"] ." ". $data["Model"];
  $frontMatter["categories"] = str_replace(" / ","-",$data["Category"]);
  $frontMatter["brands"] = $brandName;
  $frontMatter["years"] = $data["Year"];
  $frontMatter["spec"] = [$data];
  file_put_contents($fileDest,json_encode($frontMatter,JSON_PRETTY_PRINT));
}
$time_elapsed_secs = microtime(true) - $start;
echo "time: $time_elapsed_secs\n";
