<?php /**
        Author: SpringHack - springhack@live.cn
        Last modified: 2015-12-16 21:35:35
        Filename: getBase64PDF.php
        Description: Created by SpringHack using vim automatically.
**/ ?>
<?php
	require_once('fpdf.php');
	$res = base64_decode(explode('ase64,', $_POST['code'])[1]);
	$pic = tempnam(sys_get_temp_dir(), uniqid().'WTF.png');
	$arr = explode('.', $pic);
	$pic = str_replace($arr[count($arr) - 1], 'png', $pic);
	file_put_contents($pic, $res);
	$pdf=new FPDF();
	$pdf->AddPage();
	$pdf->Image($pic, 0, 0, floatval($_POST['w']), floatval($_POST['h']));
	$pdf->Output('', $_POST['name'].'.pdf');
?>
