<?php

require __DIR__ . '/vendor/autoload.php';

define("HEADER", [
    "name" => "Название",
    "phone" => "Телефон",
    "address" => "Адрес",
]);

function checkHeader(array $sheetRow, int $col): ?array {
    $result = array_fill_keys(array_keys(HEADER), null);

    for ($i = 0; $i < count(HEADER); $i++) {
        foreach (HEADER as $key => $value) {
            if ($sheetRow[$col + $i] === $value) {
                $result[$key] = $col + $i;
            }
        }
    }

    if (in_array(null, $result, true)) {
        return null;
    } else {
        return $result;
    }
};

// Return array with indexes of coresponding columns if valid hearer is found
// Return null otherwise
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
    $result = [];
    if ($header = findHeader($sheet)) {
        for ($i = $header["row"] + 1; $i < count($sheet); $i++) {
            $newRow = [];
            foreach(HEADER as $key => $value) {
                $newRow[$key] = $sheet[$i][$header[$key]];
            }

            if (in_array(null, $newRow, true)) {
                break;
            } else {
                $result[] = $newRow;
            }
        }
    }
    
    return $result;
};

function getData(PhpOffice\PhpSpreadsheet\Spreadsheet $document): array {
    $data = [];

    for ($i = 0; $i < $document->getSheetCount(); $i++){
        $sheet = $document->getSheet($i)->toArray();
        $data = array_merge($data, parseSheet($sheet));
    }
    
    return $data;
};

$input = $argv[1];
$reader = new PhpOffice\PhpSpreadsheet\Reader\Xlsx();
$document = $reader->load($input);

$data = getData($document);

foreach (HEADER as $key => $value) {
    echo $value, "\t";
}
echo "\n";

foreach($data as $dataRow) {
    foreach (HEADER as $key => $value) {
        echo $dataRow[$key], "\t";
    }
    echo "\n";
}

?>
