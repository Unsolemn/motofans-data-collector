<?php

include_once 'HTMLTable2JSON.php';
$appDir = __DIR__ . "/../..";
$sourceDir = $appDir . "/catalog/models-HTML-stripped";
$helper = new HTMLTable2JSON();
// output all files and directories except for '.' and '..'
$directory = new DirectoryIterator($sourceDir);
$errorDir = $appDir . "/catalog/last-errors";
/* echo count($directory);die; */
/* print_r(get_class($directory));die; */
/* print_r(get_class_methods($directory));die; */
foreach ($directory as $fileInfo) {
  if($fileInfo->isDot()) continue;
  $fileName = $fileInfo->getFilename();
  $filePath = $sourceDir . "/" . $fileInfo->getFilename();
  echo "reading $filePath\n";
  $table = file_get_contents($filePath);
  $output = $helper->tableToJSON('', false, null, null, null, null, null, true, null, null, $table, 5);
  if(!$output) {
    $moveRes = rename ($filePath, $errorDir . "/" . $fileName);
    if ($moveRes) continue;
    else die("cannot rename file $filePath to " . $errorDir . "/" . $fileName);
  }
  file_put_contents($appDir . "/catalog/models-JSON/$fileName",$output);
  echo "writing" .  $appDir . "/catalog/models-JSON/$fileName\n";
}
