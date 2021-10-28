<div class="panel panel-success">
    <div class="panel-heading text-center"><b>FIRMAS DE LA DECLARACIÓN</b></div>

    <table class="table">
        <thead>
            <tr>
                <th>Documento</th>
                <th>Tipo</th>
                <th>Nombre</th>
                <th>Fecha Firma</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php
                if( isset($info) && count($info) > 0 ){   
                    foreach($info AS $firma)
                    {
                        ?>
                        <tr>
                            <td><?= $firma['id_usuario'] ?></td>
                            <td><?= $firma['label'] ?></td> 
                            <td><?= $firma['first_name'].' '.$firma['last_name'] ?></td>
                            <td><?= $firma['fecha_firma'] ?></td>
                            <td>
                                <?
                                    if( $firma['estado'] == 1 ){
                                        echo '<button
                                            class="btn btn-xs btn-success free-sign"
                                            data-codigo="'.$firma['id'].'"
                                            data-declaracion="'.$id_declaracion.'"
                                            title="Liberar Firma"
                                        >
                                            <i class="glyphicon glyphicon-ok"></i> Liberar Firma
                                        </button>';
                                    }else{
                                        echo '<button
                                            class="btn btn-default btn-xs"
                                            title="No se puede realizar cambios sobre esta firma"
                                        >
                                            <i class="glyphicon glyphicon-remove"></i>Firma Liberada
                                        </button>';
                                    }
                                ?>
                            </td>
                        </tr>
                        <?
                    }
                }else{
                    echo "<tr>
                            <td colspan=\"5\" align=\"center\">No  hay firmas registradas.</td>
                        </tr>";
                }
            ?>
        </tbody>
    </table>
</div>