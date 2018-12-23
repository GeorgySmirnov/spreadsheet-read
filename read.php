<?php

require __DIR__ . '/vendor/autoload.php';

$header = [
    "name" => "Название",
    "address" => "Адрес",
    "phone" => "Телефон",
];

function getData(PhpOffice\PhpSpreadsheet\Spreadsheet $document): array {
    $data = [];

    for ($i = 0; $i < $document->getSheetCount(); $i++){
        $sheet = $document->getSheet($i)->toArray();
        $data = array_merge($data, $sheet);
    }
    
    return $data;
}

$input = $argv[1];
$reader = new PhpOffice\PhpSpreadsheet\Reader\Xlsx();
$document = $reader->load($input);

var_dump(getData($document));

?>
