<section id="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-md-6 col-md-offset-3">
                <h1 class="text-center">Ingrese el código de barras de la firma</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-6 col-md-offset-3">
                <div class="form-group">
                    <input type="text" id="searchCodeBar" class="form-control">
                </div>
            </div>
        </div>
        <div id="zoneResponse"></div>
    </div>
</section>

<script type="text/javascript" language="javascript" charset="utf-8">

    $(function () {
        $(document).on("keypress", "#searchCodeBar", function (e) {
            if (e.keyCode == 13) {
                var valor = e.target.value;
                buscarFirmaCodeBar(valor);
            }
        });
    });

    function buscarFirmaCodeBar(value) {
        $.ajax({
            url: base_url + 'index.php/firma/searchSign',
            type: 'POST',
            data: { value: value },
            beforeSend: function (jqXHR, stt) {
                $('#zoneResponse').html(
                    '<div class="text-center"><span class="fa fa-spinner spinning" style="font-size: 20px;"></span> Procesando la firma...</div>'
                );
            },
            success: function (response) {
                $('#zoneResponse').html(response);
                $('#searchCodeBar').val('');
                $('#searchCodeBar').focus();
            },
        });
    }

</script>