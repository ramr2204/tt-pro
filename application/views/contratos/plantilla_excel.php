<?php
$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0);
$rowCount = 1;

$objPHPExcel->getActiveSheet()
    ->SetCellValue('A1', 'NIT del contratista (Si se digita uno que exista obvie los demás datos del contratista)')
    ->SetCellValue('B1', 'Tipo del contratista (Alguna de las opciones numéricas de abajo)')
    ->SetCellValue('C1', 'Nombre del contratista')
    ->SetCellValue('D1', 'Dirección del contratista')
    ->SetCellValue('E1', 'Teléfono del contratista')
    ->SetCellValue('F1', 'Email del contratista')
    ->SetCellValue('G1', 'Municipio del contratista (Código DANE)')
    ->SetCellValue('H1', 'Tipo de contrato (Alguna de las opciones numéricas de abajo)')
    ->SetCellValue('I1', 'Fecha de la firma del contrato (Formato YYYY-MM-DD)')
    ->SetCellValue('J1', 'Número de contrato')
    ->SetCellValue('K1', 'Valor antes de IVA (Con separador decimal ,)')
    ->SetCellValue('L1', 'Municipio Origen del Contrato (Código DANE de un municipio de Tunja)')
    ->SetCellValue('M1', 'Clasificación del contrato (Alguna de las opciones numéricas de abajo)')
    ->SetCellValue('N1', 'Objeto')
    ->SetCellValue('O1', 'Número de contrato relacionado (En caso de ser requerido)');

$objPHPExcel->getActiveSheet()
    ->SetCellValue('A2', '123456789')
    ->SetCellValue('C2', 'Juan Gonzales')
    ->SetCellValue('D2', 'Calle 19 # 20-23')
    ->SetCellValue('E2', '3000000001')
    ->SetCellValue('F2', 'juan@mail.com')
    ->SetCellValue('G2', '15368')
    ->SetCellValue('I2', '2021-10-20')
    ->SetCellValue('J2', '1000001')
    ->SetCellValue('K2', '1230000')
    ->SetCellValue('L2', '15001')
    ->SetCellValue('N2', 'Compra y venta de materiales')
    ->SetCellValue('O2', '');

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(17);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(40);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(21);
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);


foreach($tipos_contratistas AS $indice => $tipo)
{
    $objPHPExcel->getActiveSheet()
        ->SetCellValue('B'.($indice+2), ($tipo->id .'='. $tipo->nombre));
}

foreach($tipos_contratos AS $indice => $tipo)
{
    $objPHPExcel->getActiveSheet()
        ->SetCellValue('H'.($indice+2), ($tipo->id .'='. $tipo->nombre));
}

$indice = 0;
foreach($clasificacion_contrato AS $id => $nombre)
{
    $objPHPExcel->getActiveSheet()
        ->SetCellValue('M'.($indice+2), ($id .'='. $nombre));
        $indice++;
}


$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

// We'll be outputting an excel file
header('Content-type: application/vnd.ms-excel');
// It will be called file.xls
header('Content-Disposition: attachment; filename="Plantilla_cargue_contratos.xlsx"');
// Write file to the browser
ob_end_clean();

$objWriter->save('php://output');
exit();