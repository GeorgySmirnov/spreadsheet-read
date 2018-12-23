<?php

require __DIR__ . '/vendor/autoload.php';

define("HEADER", [
    "name" => "Название",
    "address" => "Адрес",
    "phone" => "Телефон",
]);

// Return array with indexes of coresponding columns if valid hearer is found
// Return null otherwise
function checkHeader(array $sheetRow, int $col): ?array {
    $result = array_fill_keys(array_keys(HEADER), null);

    for ($i = 0; $i < count(HEADER); $i++) {
        foreach (HEADER as $key => $value) {
            if ($sheetRow[$col + $i] === $value) {
                $result[$key] = $col + $i;
            }
        }
    }

    foreach ($result as $value) {
        if ($value === null) {
            return null;
        }
    }

    return $result;
};

function findHeader(array $sheet): ?array {
    for ($i = 0; $i < (count($sheet) - 1); $i++) {
        for ($j = 0; $j < (count($sheet[$i]) - count(HEADER) + 1); $j++) {
            if ($header = checkHeader($sheet[$i], $j)) {
                $header["row"] = $i;
                return $header;
            }
        }
    }
    return null;
}

function parseSheet(array $sheet): array {
    $result = findHeader($sheet);
    return $result ? $result : [];
};

function getData(PhpOffice\PhpSpreadsheet\Spreadsheet $document): array {
    $data = [];

    for ($i = 0; $i < $document->getSheetCount(); $i++){
        $sheet = $document->getSheet($i)->toArray();
        $data[] = parseSheet($sheet);
    }
    
    return $data;
};

$input = $argv[1];
$reader = new PhpOffice\PhpSpreadsheet\Reader\Xlsx();
$document = $reader->load($input);

var_dump(getData($document));

?>
