<?php
$configuraPagina = array();
foreach ($configuracao as $cf) {
    $configuraPagina = $cf;
    break;
}
?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

    <h4 class="modal-title">Município</h4>
</div>
<div class="modal-body">
    <div class="row">
        <span class="col-md-12"><strong> Nome :</strong></span>
        <div class="col-md-12"><?php echo $configuraPagina->nomeMunicipio ?></div>
        <br/>
        <br/>
        <span class="col-md-12"><strong>CNPJ :</strong></span>
        <div class="col-md-12"><?php echo $configuraPagina->cnpjMunicipio ?></div>
        <br/>
        <br/>
        <span class="col-md-12"><strong>Endereço :</strong></span>
        <div class="col-md-12"><?php echo $configuraPagina->cepMunicipio ?></div> 
        <br/>
        <br/>
        <span class="col-md-12"><strong>Telefone :</strong></span>
        <div class="col-md-12"><?php echo $configuraPagina->telefoneMunicipio ?></div> 
        <br/>
        <br/>
        <span class="col-md-12"><strong>E-mail :</strong></span>
        <div class="col-md-12"><?php echo $configuraPagina->emailMunicipio ?></div> 
        <br/>
        <br/>

        <?php ?>
        <?php if (trim($configuraPagina->siteMunicipio) != "") { ?>
            <span class="col-md-12"><strong>Web Site :</strong></span>
            <div class="col-md-12">
                <a href="<?php echo $configuraPagina->siteMunicipio ?>" target='_blank'><?php echo $configuraPagina->siteMunicipio ?></a> 
            </div> 
        <?php } ?>  
    </div>
</div>