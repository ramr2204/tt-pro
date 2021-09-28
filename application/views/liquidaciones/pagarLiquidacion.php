<div class="row"> 
    <div rol="usuario_rotulo" style="display:none;">
        <?php echo $this->ion_auth->user()->row()->id; ?></div>
        <div class="col-sm-12">
            <div class="col-xs-12">
                <h1>Liquidación Web</h1>
            </div>
            <div class="col-xs-12">
                <form action="<?php echo base_url();?>index.php/liquidaciones/pagarContacto" method="POST">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Razón social</label>
                            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Nombres</label>
                            <input type="text" class="form-control" id="exampleInputPassword1">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputPassword1">Número de documento</label>
                            <input type="text" class="form-control" id="exampleInputPassword1">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Apellidos</label>
                            <input type="text" class="form-control" id="exampleInputPassword1">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="exampleInputPassword1">Correo Electrónico</label>
                            <input type="text" class="form-control" id="exampleInputPassword1">
                        </div>
                    </div>
                    <div style="text-align: center;">
                        <button class="btn btn-success">Pagar Web</button>
                    </div>
                    <br>
                </form>   
           </div>
        </div>
    </div>
</div> 
