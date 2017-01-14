<?php

include_once 'HTMLTable2JSON.php';
$helper = new HTMLTable2JSON();
// output all files and directories except for '.' and '..'
foreach (new DirectoryIterator('models-by-year-html') as $fileInfo) {
  if($fileInfo->isDot()) continue;
  $fileName = $fileInfo->getFilename();
  $filePath = __DIR__ . "/models-by-year-html/" . $fileInfo->getFilename();
  $table = file_get_contents($filePath);
  echo "reading $filePath\n";
  $output = $helper->tableToJSON('', false, null, null, null, null, null, true, null, null, $table);
  file_put_contents(__DIR__ . "/models-by-year-tables/$fileName",$output);
  echo "writing" .  __DIR__ . "/models-by-year-tables/$fileName\n";
}
