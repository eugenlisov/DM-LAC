<?php

$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once( $parse_uri[0] . 'wp-blog-header.php' );
error_reporting(0);
// This one
ob_start();



$college_1 = 3599;
$college_2 = 479;

$college_1 = htmlspecialchars( $_POST["college_1"] );
$college_2 = htmlspecialchars( $_POST["college_2"] );
$custom_title = stripslashes( htmlspecialchars( $_POST["custom_title"] ) );

$college_1_title = get_the_title( $college_1 );
$college_2_title = get_the_title( $college_2 );

// echo '<pre>';
// print_r ( $_POST );
// echo '</pre>';
//
// exit;

$pdf_title = 'Compare ' . get_the_title( $college_1 ) . ' with ' . get_the_title( $college_2 ) . '';
$output_file_name = 'LAC_' . str_replace(" ","", $college_1_title) . '_' . str_replace(" ","", $college_2_title) . '.pdf';


// Include the main TCPDF library (search for installation path).
require_once('tcpdf.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Liberal Arts Colleges');
$pdf->SetTitle( $pdf_title );
// $pdf->SetSubject('TCPDF Tutorial');
// $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
// $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 048', PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', 'B', 25);

// add a page
$pdf->AddPage();

$pdf->Write(0, $custom_title, '', 0, 'C', true, 0, false, false, 0);

$pdf->Write(0, '', '', 0, 'C', true, 0, false, false, 0);

$pdf->SetFont('helvetica', '', 8);

// -----------------------------------------------------------------------------

$dm_comparatorPDF = new DM_ComparatorPDF;
$return .= $dm_comparatorPDF -> style();
$return .= $dm_comparatorPDF -> college_comparison_block( $college_1, $college_2 );


$pdf->writeHTML($return, true, false, false, false, '');

// -----------------------------------------------------------------------------

// set font
$pdf->SetFont('helvetica', 'B', 13);

$pdf->Write(0, '', '', 0, 'C', true, 0, false, false, 0);
$pdf->Write(0, 'source: LiberalArtsColleges.com', '', 0, 'C', true, 0, false, false, 0);


//Close and output PDF document
$pdf->Output( $output_file_name, 'D');

//============================================================+
// END OF FILE
//============================================================+
