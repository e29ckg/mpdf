<?php

require_once __DIR__ . '/vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf();

$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

// var_dump($fontData);

$mpdf = new \Mpdf\Mpdf([
    'fontDir' => array_merge($fontDirs, [
        __DIR__ . '/fonts',
    ]),
    'fontdata' => $fontData + [
        'thsarabun' => [
            'R' => 'Sarabun-Regular.ttf',
            //'I' => 'THSarabunNew Italic.ttf',
            //'B' => 'THSarabunNew Bold.ttf',
            'useOTL' => 0xFF,
			// 'useKashida' => 75
        ]
    ],
    'default_font' => 'thsarabun',
    'default_font_size' => 8,
    'mode' => 'utf-8', 
    'format' => [235, 108]
]);

$mpdf->SetTitle($name);
$mpdf->useDictionaryLBR = false;

// $mpdf->AddPage('L');
$mpdf->SetXY(100, 50);
// $mpdf = new \Mpdf\Mpdf();


$mpdf->WriteHTML('ศาลเยาวชนและครอบครัวจังหวัดประจวบคีรีขันธ์ ');
$mpdf->WriteHTML('ศาลเยาวชนและครอบครัวจังหวัดประจวบคีรีขันธ์ ถำ้ เปลี่ยน');
$mpdf->Output();

?>