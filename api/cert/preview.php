<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Content-Type: application/json; charset=utf-8");

require_once '../../vendor/autoload.php';

$data = json_decode(file_get_contents("php://input"));

$files = glob('../../output/preview/*'); 
 
// Deleting all the files in the list
foreach($files as $file) {   
    if(is_file($file))     
        unlink($file); 
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$data   = $data->data;

// $uname   = $data->uname;
if(isset($data->template)){
    $tm = '../../../pkkjc_cert/template/'.$data->template;
}else{
    $tm = '../../../pkkjc_cert/template/test.pdf';
}
// $tm     = '../../../pkkjc_cert/template/'.$data->template;
$name_file = time().'.pdf';
$output = '../../output/preview/'.$name_file;
$uname = 'นายพเยาว์ เยี่ยม';
$name_font = 'prompt';
$name_font_size = 36;
$name_text_align = 'center';
$name_x = 0;
$name_y = 69;

if(isset($data->uname)){$uname = $data->uname;}
if(isset($data->name_font_size)){$name_font_size = $data->name_font_size;}
if(isset($data->name_font)){$name_font = $data->name_font;}
if(isset($data->name_y)){$name_y = $data->name_y;}


$link_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/";

$mpdf = new \Mpdf\Mpdf();

$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

// var_dump($fontData);

$mpdf = new \Mpdf\Mpdf([
    'fontDir' => array_merge($fontDirs, [
        '../../fonts',
    ]),
    'fontdata' => $fontData + [
        'thsarabun' => [
            'R' => 'Sarabun-Regular.ttf',
            //'I' => 'THSarabunNew Italic.ttf',
            //'B' => 'THSarabunNew Bold.ttf',
            'useOTL' => 0xFF,
			// 'useKashida' => 75
        ],
        'prompt' => [
            'R' => 'Prompt.ttf',
            'B' => 'Prompt-Bold.ttf',
            'useOTL' => 0xFF,
			// 'useKashida' => 75
        ]
    ],
    'mode' => 'utf-8', 
    'format' => 'A4-L',
    'orientation' => 'L',
    // 'default_font' => 'thsarabun',
    // 'default_font_size' => 8,
    // 'format' => [235, 108],    
    // 'default_font' => 'kanit',
    // 'default_font' => 'NotoSerifThai',
    'default_font' => $name_font,
    'default_font_size' => $name_font_size
]);
$mpdf->useDictionaryLBR = false;

$mpdf->SetTitle($uname);
$mpdf->SetAuthor('pkkjc');
$mpdf->SetSubject('pkkjc-cert');
$mpdf->SetCreator('pkkjc.coj');
$mpdf->SetKeywords('pkkjc');

$mpdf->AddPage();

// $pagecount = $mpdf->setSourceFile('tm.pdf');
$pagecount = $mpdf->setSourceFile($tm);
// $pagecount = $mpdf->setSourceFile($tm);
$tplId = $mpdf->importPage($pagecount);

$actualsize = $mpdf->useTemplate($tplId);

$data = '<div style="text-align:'.$name_text_align.';font-weight: bold;">'
        .$uname.
        '</div>';
$mpdf->WriteFixedPosHTML($data, $name_x, $name_y, 297, 210, 'auto');

// $qr_code = '<img id="imgurl" src="https://chart.googleapis.com/chart?cht=qr&amp;chl=http://www.diw.go.th&amp;chs=80x80&amp;choe=UTF-8" border="0" width="80" height="80">';
// $mpdf->WriteFixedPosHTML($qr_code, 15, 175, 297, 210, 'auto');

// Output a PDF file directly to the browser
// $mpdf->Output();



$mpdf->Output($output);

$link_url .= 'mpdf/output/preview/'.$name_file;

http_response_code(200);
echo json_encode(array('status' => true, 'massege' => 'สำเร็จ', 'url' => $link_url));
exit;

}
?>
