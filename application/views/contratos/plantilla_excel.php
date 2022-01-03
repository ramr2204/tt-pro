<?
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: filename=Plantilla_cargue_contratos.xls");
echo "
<html xmlns:o=\"urn:schemas-microsoft-com:office:office\" xmlns:x=\"urn:schemas-microsoft-com:office:excel\" xmlns=\"http://www.w3.org/TR/REC-html40\">
<html>
<head><meta http-equiv=\"Content-type\" content=\"text/html;charset=utf-8\" /></head>
<body>
";
?>
<table border="1" style="text-align:center;">
    <thead>
        <tr>
            <th style="text-align:center; width:26mm;"><strong>NIT del contratista (Si se digita uno que exista obvie los demás datos del contratista)</strong></th>
            <th style="text-align:center; width:20mm;"><strong>Tipo del contratista (Alguna de las opciones numéricas de abajo)</strong></th>
            <th style="text-align:center; width:20mm;"><strong>Nombre del contratista</strong></th>
            <th style="text-align:center; width:40mm;"><strong>Dirección del contratista</strong></th>
            <th style="text-align:center; width:40mm;"><strong>Teléfono del contratista</strong></th>
            <th style="text-align:center; width:40mm;"><strong>Email del contratista</strong></th>
            <th style="text-align:center; width:30mm;"><strong>Municipio del contratista (Código DANE)</strong></th>
            <th style="text-align:center; width:30mm;"><strong>Tipo de contrato (Alguna de las opciones numéricas de abajo)</strong></th>
            <th style="text-align:center; width:30mm;"><strong>Fecha de la firma del contrato (Formato YYYY-MM-DD)</strong></th>
            <th style="text-align:center; width:30mm;"><strong>Número de contrato</strong></th>
            <th style="text-align:center; width:30mm;"><strong>Valor antes de IVA (Con separador decimal ,)</strong></th>
            <th style="text-align:center; width:30mm;"><strong>Municipio Origen del Contrato (Código DANE de un municipio de Tunja)</strong></th>
            <th style="text-align:center; width:30mm;"><strong>Clasificación del contrato (Alguna de las opciones numéricas de abajo)</strong></th>
            <th style="text-align:center; width:30mm;"><strong>Objeto</strong></th>
            <th style="text-align:center; width:30mm;"><strong>Número de contrato relacionado (En caso de ser requerido)</strong></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="text-align:center; width:26mm;">123456789</td>
            <td style="text-align:center; width:20mm;">
                <table>
                    <tbody>
                        <?
                            foreach($tipos_contratistas AS $tipo)
                            {
                                ?>
                                <tr>
                                    <td><?= $tipo->id ?> = <?= $tipo->nombre ?></td>
                                </tr>
                                <?
                            }
                        ?>
                    </tbody>
                </table>
            </td>
            <td style="text-align:center; width:20mm;">Juan Gonzales</td>
            <td style="text-align:center; width:40mm;">Calle 19 # 20-23</td>
            <td style="text-align:center; width:40mm;">3000000001</td>
            <td style="text-align:center; width:40mm;">juan@mail.com</td>
            <td style="text-align:center; width:30mm;">15368</td>
            <td style="text-align:center; width:30mm;">
                <table>
                    <tbody>
                        <?
                            foreach($tipos_contratos AS $tipo)
                            {
                                ?>
                                <tr>
                                    <td><?= $tipo->id ?> = <?= $tipo->nombre ?></td>
                                </tr>
                                <?
                            }
                        ?>
                    </tbody>
                </table>
            </td>
            <td style="text-align:center; width:30mm;mso-number-format:'@';">2021-10-20</td>
            <td style="text-align:center; width:30mm;">1000001</td>
            <td style="text-align:center; width:30mm;">1230000</td>
            <td style="text-align:center; width:30mm;">15001</td>
            <td style="text-align:center; width:30mm;">
                <table>
                    <tbody>
                        <?
                            foreach($clasificacion_contrato AS $id => $nombre)
                            {
                                ?>
                                <tr>
                                    <td><?= $id ?> = <?= $nombre ?></td>
                                </tr>
                                <?
                            }
                        ?>
                    </tbody>
                </table>
            </td>
            <td style="text-align:center; width:30mm;">Compra y venta de materiales</td>
            <td style="text-align:center; width:30mm;"></td>
        </tr>
    </tbody>
</table>