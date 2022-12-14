<?php
require('tfpdf/tfpdf.php');

class PDF extends TFPDF
{

// Page header
function Header()
{
    $this->SetFont('LibSerif','',10);
	$this->SetTextColor(150);
    // Title
    $this->Cell(0,10,'PRO SLUŽEBNÍ POTŘEBU',0,0,'C');
    // Line break
    $this->Ln(20);
}

// Page footer
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-20);
    $this->SetFont('LibSerif','',10);
    // Page number
    $this->Cell(0,10,$this->PageNo(),0,0,'C');
	$this->SetY(-15);
	$this->SetTextColor(150);
    $this->SetFont('LibSerif','',10);
    $this->Cell(0,10,'PRO SLUŽEBNÍ POTŘEBU',0,0,'C');
}

function PrintTitle($text, $size = 14)
{
	$this->SetFont('LibSerifB','',$size);
    $this->Cell(0,10,$text,0,1,'C');
}


function Hlavicka()
{
	$this->SetFont('','',10);	
	$this->Cell(80,4,'26. pluk velení,řízení a průzkumu');
	$this->Cell(0,4,'Příloha č. 4 k Ev.č.: 12345/2022-111',0,1,'R');
	$this->Cell(0,4,'Výtisk jediný',0,1,'R');
	$this->Cell(0,4,'Počet listů : ^%',0,1,'R');

}

function Dolozka($data)
{
	$w = array(90,50,50);
	$this->SetY(-85);
	$this->SetFont('','',7);	
	$this->Cell(0,5,'• Technika, písemnosti a inventář předány dle směrnic ev.č. MOCRX00IU2NP, seznamu písemností ev.č. MOCRX00ITW0F a seznamu předmětů a zařízení ev.č. MOCRX00ITX08.');

	$this->SetFont('','',8);	
	$this->Ln();
	$this->Cell($w[0],5,'Funkce',1,0,'C');
	$this->Cell($w[1],5,'Končící směna',1,0,'C');
	$this->Cell($w[2],5,'Nastupující směna',1,0,'C');
	$this->Ln();
	// Data
	foreach($data as $row)
	{
		$this->Cell($w[0],4,$row[0],'LR');
		$this->Cell($w[1],4,$row[1],'LR');
		$this->Cell($w[2],4,$row[2],'LR');
		$this->Ln();
	}
	// Closing line
	$this->Cell(array_sum($w),1,'','T');

	$this->SetFont('','',8);	
	$this->Ln();
	$this->Cell(0,6,'• Provozní způsobilost leteckých pozemních zařízení neměla vliv na rozsah poskytovaných služeb.');
	$this->Ln();
	$this->Ln();
	$this->Ln();
	$this->SetFont('','',10);	
	$this->Cell(40,5,$data[1][1],'T',0,'C');
	$this->Cell(35,5,'','');
	$this->Cell(40,5,$data[1][2],'T',0,'C');
	$this->Cell(35,5,'','');
	$this->Cell(40,5,$data[0][1],'T',0,'C');
	$this->Ln();
}

// Load data
function LoadData($file)
{
	// Read file lines
	$lines = file($file);
	$data = array();
	foreach($lines as $line)
		$data[] = explode(';',trim($line));
	return $data;
}

function ImprovedTable($header, $data)
{
	$this->SetFont('LibSerif','',8);
	// Column widths
	$w = array(45, 18, 45, 18, 45, 18);
	// Header
	for($i=0;$i<count($header);$i++)
		$this->Cell($w[$i],5,$header[$i],1,0,'C');
	$this->Ln();
	// Data
	foreach($data as $row)
	{
		$this->Cell($w[0],6,$row[0],'LR');
		$this->Cell($w[1],6,$row[1],'LR');
		$this->Cell($w[2],6,number_format($row[2]),'LR',0,'R');
		$this->Cell($w[3],6,number_format($row[3]),'LR',0,'R');
		$this->Ln();
	}
	// Closing line
	$this->Cell(array_sum($w),8,'','T');
	$this->Ln();

}

// Colored table
// function FancyTable($header, $data)
// {
//	// Colors, line width and bold font
//	$this->SetFillColor(255,0,0);
//	$this->SetTextColor(255);
//	$this->SetDrawColor(128,0,0);
//	$this->SetLineWidth(.3);
//	$this->SetFont('','');
//	// Header
//	$w = array(40, 35, 40, 45);
//	for($i=0;$i<count($header);$i++)
//		$this->Cell($w[$i],7,$header[$i],1,0,'C',true);
//	$this->Ln();
//	// Color and font restoration
//	$this->SetFillColor(224,235,255);
//	$this->SetTextColor(0);
//	$this->SetFont('');
//	// Data
//	$fill = false;
//	foreach($data as $row)
//	{
//		$this->Cell($w[0],6,$row[0],'LR',0,'L',$fill);
//		$this->Cell($w[1],6,$row[1],'LR',0,'L',$fill);
//		$this->Cell($w[2],6,number_format($row[2]),'LR',0,'R',$fill);
//		$this->Cell($w[3],6,number_format($row[3]),'LR',0,'R',$fill);
//		$this->Ln();
//		$fill = !$fill;
//	}
//	// Closing line
//	$this->Cell(array_sum($w),0,'','T');
//}
}

$pdf = new PDF();
$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
$pdf->AddFont('DejaVuB','','DejaVuSansCondensed-Bold.ttf',true);
$pdf->AddFont('LibSerif','','LiberationSerif-Regular.ttf',true);
$pdf->AddFont('LibSerifB','','LiberationSerif-Bold.ttf',true);
// Column headings
$header = array('Systém', 'Stav', 'Systém', 'Stav', 'Systém', 'Stav');
// Data loading
//$data = $pdf->LoadData('countries.txt');
$pdf->SetFont('LibSerif','',10);
$pdf->AddPage();
$pdf->Hlavicka();

$pdf->PrintTitle("STAV TECHNIKY 14.12.2022",15); 

$pdf->PrintTitle("STAV SYSTÉMŮ"); 
$pdf->ImprovedTable($header,$data);

$pdf->PrintTitle("RADIOSTANICE"); 
$pdf->ImprovedTable($header,$data);

$pdf->PrintTitle("PLÁNOVANÉ VÝPADKY"); 
$pdf->ImprovedTable($header,$data);

$pdf->PrintTitle("PRŮBĚH SLUŽBY"); 
$pdf->ImprovedTable($header,$data);


$data = [['Master Controller (MC)', 'pplk. Stanislav Hebr',''],
		['Výkonný specialista - technická supervize a dohled','kpt. Jan Novák','kpt. Václav Bobek'],
		['Výkonný specialista - technická supervize a dohled','kpt. Stanislav Lesniak','kpt. Jan Cellárik'],
		['1. výkonný specialista LRNS', 'nrtm. Milan Sládek','nrtm. Jaroslav Rozporka'],
		['2. výkonný specialista LRNS', 'nrtm. Lukáš Ježek', 'nrtm. Mireček Kiců']];
$pdf->Dolozka($data);
//$pdf->AddPage();
//$pdf->FancyTable($header,$data);
$pdf->AliasNbPages('^%');
$pdf->Output();
?>
