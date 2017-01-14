<?php

include_once 'HTMLTable2JSON.php';
$appDir = __DIR__ . "/../..";
$helper = new HTMLTable2JSON();
// output all files and directories except for '.' and '..'
$directory = new DirectoryIterator($appDir . "/catalog/models-html");
$errorDir = $appDir . "/catalog/other-errors";
/* echo count($directory);die; */
/* print_r(get_class($directory));die; */
/* print_r(get_class_methods($directory));die; */
foreach ($directory as $fileInfo) {
  if($fileInfo->isDot()) continue;
  $fileName = $fileInfo->getFilename();
  $filePath = $appDir . "/catalog/models-html/" . $fileInfo->getFilename();
  echo "reading $filePath\n";
  $table = file_get_contents($filePath);
  $output = $helper->tableToJSON('', false, null, null, null, null, null, true, null, null, $table, 5);
  if(!$output) {
    $moveRes = rename ($filePath, $errorDir . "/" . $fileName);
    if ($moveRes) continue;
    else die("cannot rename file $filePath to " . $errorDir . "/" . $fileName);
  }
  file_put_contents($appDir . "/catalog/models-tables/$fileName",$output);
  echo "writing" .  $appDir . "/catalog/models-tables/$fileName\n";
}
