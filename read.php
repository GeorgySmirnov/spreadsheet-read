<?php

require __DIR__ . '/vendor/autoload.php';

$input = $argv[1];
$reader = new PhpOffice\PhpSpreadsheet\Reader\Xlsx();
$spreadsheet = $reader->load($input);

for ($i = 0; $i < $spreadsheet->getSheetCount(); $i++){
    $data = $spreadsheet->getSheet($i)->toArray();
    var_dump($data);
}
?>
