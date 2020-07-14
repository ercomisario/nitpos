<!-- Page Content -->
<link rel="stylesheet" href="<?=base_url()?>assets/css/buttons.dataTables.min.css" type='text/css'>

<style>
  .tablecash{
    text-align: end;
    width: 100%;
  }
  .titulo{
    text-align: left;
    font-weight: bold;
   }
  .totales{
    
    font-weight: bold;
    
  }
  .border{
    border-top:1px dashed #000;
  }

</style>

 
  <?php
  if(!$dateBegin)$dateBegin=date('Y-m-d');
  if(!$dateEnd)$dateEnd=date('Y-m-d');
  ?>
  <div class="container">

    <h3><?=label('CashMovements');?></h3>
    <br />
    <br />
  <div style="display: flex;">
    <div class="col-sm-5 well">
      <form action="cashmovements" method="post" >
        <div class="form-group controls col-sm-12 ">
          <label for="type"><?=label("TypeReport");?></label>
          <select class="form-control" name="type" id="type">
            <option value="1" <?php if($type==1) echo "selected ";?> >Reporte Z</option>
            <option value="2" <?php if($type==2) echo "selected ";?> >Informe Detallado Diario</option>
            <option value="4" <?php if($type==4) echo "selected ";?> >Informe Fiscal Diario</option>
            <option value="5" <?php if($type==5) echo "selected ";?> >Informe de Retenciones</option>
            <option value="3" <?php if($type==3) echo "selected ";?> >Reporte X</option>
            
          </select>     
        </div>  
        <div class="form-group controls col-sm-12 ">
          <label for="store_id"><?=label("Store");?></label>
          <select class="form-control" name="store_id" id="store_id">
            <option value=""><?=label("TODAS");?></option>
            <?php foreach ($stores as $row):?>
            <option value="<?=$row->id;?>" <?php if($store_id==$row->id) echo "selected ";?> ><?=$row->name;?></option>
            <?php endforeach;?>
          </select>      
        </div>
        <div class="form-group controls col-sm-12 ">
          <label for="register_id"><?=label("Registro");?></label>
          <select class="form-control" id="register_id" name="register_id">
            <option value=''><?=label("TODOS");?></option>
          <?php foreach ($registers as $row):
            $status='Cerrada';
            if($row->status==1)$status='Abierta';?>
            <option value="<?=$row->id;?>" <?php if($register_id==$row->id) echo "selected ";?> ><?=$row->id;?> - Fecha: <?=$row->date->format('d/m/Y');?> Caja: <?=$row->username;?> <?=$row->firstname;?> - <?=$status;?></option>
          <?php endforeach;?>
          </select>
        </div>
        <div class="form-group controls col-sm-6 ">
          <label for="date"><?=label("DateBegin");?></label>
          <input type="text" name="dateBegin" class="form-control" id="dateBegin" placeholder="<?=label("Date");?>" value="<?=$dateBegin?>">
        </div>
        <div class="form-group controls col-sm-6 ">
          <label for="date"><?=label("DateEnd");?></label>
          <input type="text" name="dateEnd" class="form-control col-sm-6" id="dateEnd" placeholder="<?=label("DateEnd");?>" value="<?=$dateEnd?>">
        </div>
        <div class="form-group controls col-sm-12 ">
          <label for="user_id"><?=label("Caja/Usuario");?></label>
          <select class="form-control" name="user_id" id="user_id">
            <option value=""><?=label("TODOS");?></option>
            <?php foreach ($users as $row):?>
            <option value="<?=$row->id;?>" <?php if($user_id==$row->id) echo "selected ";?> ><?=$row->username;?> <?=$row->firstname;?> <?=$row->lastname;?></option>
            <?php endforeach;?>
          </select>        
        </div>
        <div class="col-sm-12 ">
           <button type="submit" class="btn btn-primary col-sm-4"><?=label("Search");?></button>
           <button type="button" class="btn btn-success col-sm-4" onclick="ticket();"><?=label("Ticket");?></button>
           <button type="button" class="btn btn-danger col-sm-4" onclick="gotoCashmovement();"><?=label("Cancel");?></button>
        </div>
                  

     </form>
  </div>
  <div class="col-sm-7 well" id="resultReport" style="margin-left: 10px;">
    <table id="encabezado" width="100%"><tr class="text-center" style="font-size: small;"><td><?=$this->setting->receiptheader;?></td></tr></table>

<?php if($type==1 || $type==3){
     $reporte='REPORTE Z';
     if($type==3)
        $reporte='REPORTE X';

?>
<table width="100%">
   <tr class="text-center" style="font-size: small;"><td id="tituloZ"><?=$reporte;?></td></tr>
   <tr class="text-center" style="font-size: small;"><td id="tituloZ2"><?=label("Creado por ").' '.$createdBy;?></td></tr>
</table>
<table id="tableReporteZ" class="table-pdfZ" style="width:100%">
  <thead class="thead-inverse">
    <tr style="font-size: smaller;">
      <th ><?='Desde: '?></th>
      <th ><?=$dateBegin;?></th>
      <th ><?='Hasta: '?></th>
      <th ><?=$dateEnd;?></th>
    </tr>
  </thead>
  <tbody>     
  <?php if(isset($totales)){?>
    <?php  foreach ($totales as $row):    
            $totalFacturado=$row->total;        
           endforeach;
    ?>         
  <tr style="font-size: smaller;">
    <td class="border"><?=label('Total Facturado')?></td>
    <td class="border"></td>
    <td class="border"><?=number_format((float)$row->total, $this->setting->decimals, '.', ',');?></td>
    <td class="border"></td>
  </tr>
  <?php }?>         
  <?php if(isset($invoices)){?>
      <?php foreach ($invoices as $row):?>                  
  <tr style="font-size: smaller;" class="cell-border">
    <td ><?=label('DateBegin')?></td>
    <td ></td>
    <td ><?=$row->datebegin;?></td>
    <td ></td>
  </tr>
  <tr style="font-size: smaller;">
    <td ><?=label('DateEnd')?></td>
    <td ></td>
    <td ><?=$row->dateend;?></td>
    <td ></td>
  </tr>
  <?php endforeach; }?>           
  <?php if(isset($invoices)){?>
  <?php  foreach ($invoices as $row):?>
  <tr style="font-size: smaller;">
    <td class="border"><?=label('Documents')?></td>
    <td class="border"></td>
    <td class="border"></td>
    <td class="border"></td>
  </tr>  
  <tr style="font-size: smaller;">
    <td ><?=label('InvoiceBegin');?></td>
    <td ></td>
    <td ><?=sprintf("%08d", $row->invoicebegin);?></td>
    <td ></td>
  </tr>      
  <tr style="font-size: smaller;">
    <td ><?=label('InvoiceEnd');?></td>
    <td ></td>
    <td ><?=sprintf("%08d", $row->invoiceend);?></td>
    <td ></td>
  </tr>      
  <?php endforeach;?>
  <?php }?>         
  <?php if(isset($paidmethods)){?>
  <tr style="font-size: smaller;">
    <td class="border"><?=label('PaidMethods')?></td>
    <td class="border"></td>
    <td class="border"></td>
    <td class="border"></td>
  </tr>  
  <?php  $total=0;
    foreach ($paidmethods as $row):
        $paidmethod="Efectivo";
        $PayMethode = explode('~', $row->paidmethod);
        if($PayMethode[0]==1)
            $paidmethod="Tarjeta de Crédito";
        if($PayMethode[0]==2)
            $paidmethod="Cheque";
        if($PayMethode[0]==3)
            $paidmethod="A Crédito";
        $total+=$row->amount;?>
  <tr style="font-size: smaller;">
    <td ><?=$paidmethod;?></td>
    <td ></td>
    <td ><?=number_format((float)$row->amount, $this->setting->decimals, '.', ',');?></td>
    <td ></td>
  </tr>      
  <?php endforeach;?>
  <?php }?>         
 <?php if(isset($engravins)){?>
  <?php  foreach ($engravins as $row):?>
  <tr style="font-size: smaller;">
    <td class="border"><?=label('Engravins')?></td>
    <td class="border"></td>
    <td class="border"></td>
    <td class="border"></td>
  </tr>  
  <tr style="font-size: smaller;">
    <td ><?=label('Base');?></td>
    <td ></td>
    <td ><?=number_format((float)$row->base, $this->setting->decimals, '.', ',');?></td>
    <td ></td>
  </tr>      
  <tr style="font-size: smaller;">
    <td ><?=label('Discount');?></td>
    <td ></td>
    <td ><?=number_format((float)$row->discountamount, $this->setting->decimals, '.', ',');?></td>
    <td ></td>
  </tr>      
  <tr style="font-size: smaller;">
    <td ><?=label('Tax');?></td>
    <td ></td>
    <td ><?=number_format((float)$row->taxamount, $this->setting->decimals, '.', ',');?></td>
    <td ></td>
  </tr>      
  <tr style="font-size: smaller;">
    <td ><?=label('Total');?></td>
    <td ></td>
    <td ><?=number_format((float)$row->total, $this->setting->decimals, '.', ',');?></td>
    <td ></td>
  </tr>      
  <?php endforeach;?>
  <?php }?>         
  <tr style="font-size: smaller;">
    <td class="border"><?=label('Ingresos Venta Crédito');?></td>
    <td class="border"></td>
    <td class="border"></td>
    <td class="border"></td>
  </tr>  
  <tr style="font-size: smaller;">
    <td ><?=label('invoice');?></td>
    <td ></td>
    <td ><?=label('Fecha de Factura');?></td>
    <td ><?=label("Paid")?></td>
  </tr> 
  <?php
      $totalPaid=0;
      foreach ($payaments as $row) {
      $totalPaid+=$row->paid;
  ?>
  <tr style="font-size: smaller;">
    <td ><?=sprintf("%08d", $row->invoice);?></td>
    <td ></td>
    <td ><?=$row->date;?></td>
    <td ><?=number_format((float)$row->paid, $this->setting->decimals, '.', ',')?></td>
  </tr>  
 <?php }?>
  <tr style="font-size: smaller;">
    <td ><?=label("Total")?></td>
    <td ></td>
    <td ></td>
    <td ><?=number_format((float)$totalPaid, $this->setting->decimals, '.', ',')?></td>
  </tr>  

 <?php 
 $totalExpenses=0;
 $totalIngresosExtras=0;
 foreach ($expences as $row):
      if($row->category_name!='Ingreso Extraordinario')
          $totalExpenses+=$row->amount;
      else
          $totalIngresosExtras+=$row->amount;
 endforeach;

 if($type==1){?>
  <tr style="font-size: smaller;">
    <td class="border"><?=label('Expenses');?></td>
    <td class="border"></td>
    <td class="border"></td>
    <td class="border"></td>
  </tr> 
  <tr style="font-size: smaller;">
    <td ><?=label('Category');?></td>
    <td ><?=label('Descripción/Motivo');?></td>
    <td ></td>
    <td ><?=label("Amount")?></td>
  </tr> 
   <?php
      foreach ($expences as $row) {
       if($row->category_name!='Ingreso Extraordinario'){
 
      ?>
      <tr style="font-size: smaller;">
        <td ><?=$row->category_name;?></td>
        <td ><?=$row->note;?></td>
        <td ></td>
        <td ><?=number_format((float)$row->amount, $this->setting->decimals, '.', ',')?></td>
      </tr>  
     <?php }?>
   <?php }?>
  <tr style="font-size: smaller;">
    <td ><?=label("Total")?></td>
    <td ></td>
    <td ></td>
    <td ><?=number_format((float)$totalExpenses, $this->setting->decimals, '.', ',')?></td>
  </tr>  
  <tr style="font-size: smaller;">
    <td class="border"><?=label('Ingresos Extraordinarios');?></td>
    <td class="border"></td>
    <td class="border"></td>
    <td class="border"></td>
  </tr> 
  <tr style="font-size: smaller;">
    <td ><?=label('Referencia');?></td>
    <td ></td>
    <td ><?=label('Descripción/Motivo');?></td>
    <td ><?=label("Amount")?></td>
  </tr> 
   <?php
      foreach ($expences as $row) {
      if($row->category_name=='Ingreso Extraordinario'){

      ?>
      <tr style="font-size: smaller;">
        <td ><?=$row->reference;?></td>
        <td ></td>
        <td ><?=$row->note;?></td>
        <td ><?=number_format((float)$row->amount, $this->setting->decimals, '.', ',')?></td>
      </tr>  
     <?php }?>
   <?php }?>
  <tr style="font-size: smaller;">
    <td ><?=label("Total")?></td>
    <td ></td>
    <td ></td>
    <td ><?=number_format((float)$totalIngresosExtras, $this->setting->decimals, '.', ',')?></td>
  </tr>  
<?php }?>
  <tr style="font-size: smaller;">
    <td class="border"><?=label('Devoluciones');?></td>
    <td class="border"></td>
    <td class="border"></td>
    <td class="border"></td>
  </tr> 
  <tr style="font-size: smaller;">
    <td ><?=label('Cantidad');?></td>
    <td ></td>
    <td ></td>
    <td ><?=label("Amount")?></td>
  </tr> 
  <?php
      $totalDevoluciones=0;
      foreach ($devoluciones as $row) {
          $totalDevoluciones=$row->total;
      ?>
      <tr style="font-size: smaller;">
        <td ><?=$row->devoluciones;?></td>
        <td ></td>
        <td ></td>
        <td ><?=number_format((float)$row->total, $this->setting->decimals, '.', ',')?></td>
      </tr>  
  <?php }?>
  <tr style="font-size: smaller;">
    <td class="border"><?=label("Facturado + Ingresos por ")?></td>
    <td class="border"><?=label("Créditos + Ingresos Extraordinarios ")?></td>
    <td class="border"><?=label(" - Egresos- Devoluciones")?></td>
    <td class="border"><?=number_format((float)$totalFacturado+$totalIngresosExtras+$totalPaid-$totalExpenses-$totalDevoluciones, $this->setting->decimals, ',', '.')?></td>
  </tr>  
  <tr style="font-size: smaller;">
    <td class="border"><?=label('TaxResume');?></td>
    <td class="border"></td>
    <td class="border"></td>
    <td class="border"></td>
  </tr> 
  <?php
      foreach ($sales as $row) {
      ?>
      <tr style="font-size: smaller;">
        <td ><?=$row->name;?></td>
        <td ><?=number_format((float)$row->base, $this->setting->decimals, '.', ',');?></td>
        <td ><?=number_format((float)$row->taxamount, $this->setting->decimals, '.', ',');?></td>
        <td ><?=number_format((float)$row->total, $this->setting->decimals, '.', ',')?></td>
      </tr>  
  <?php }?>
  <tr style="font-size: smaller;">
    <td class="border"><?=label('Resumen de IVA');?></td>
    <td class="border"><?=label('por Devoluciones');?></td>
    <td class="border"></td>
    <td class="border"></td>
  </tr> 
  <?php
      foreach ($taxDevoluciones as $row) {
      ?>
      <tr style="font-size: smaller;">
        <td ><?=$row->name;?></td>
        <td ><?=number_format((float)$row->base, $this->setting->decimals, '.', ',');?></td>
        <td ><?=number_format((float)$row->taxamount, $this->setting->decimals, '.', ',');?></td>
        <td ><?=number_format((float)$row->total, $this->setting->decimals, '.', ',')?></td>
      </tr>  
  <?php }?>
 <?php if($type==1){?>

  <tr style="font-size: smaller;">
    <td class="border"><?=label('Total por Categorías');?></td>
    <td class="border"></td>
    <td class="border"></td>
    <td class="border"></td>
  </tr> 
      <?php
      $totalArea=0;

      foreach ($areas as $row) {
      $totalArea+=$row->total;?>
      <tr style="font-size: smaller;">
        <td ><?=$row->name;?></td>
        <td ></td>
        <td ></td>
        <td ><?=number_format((float)$row->total, $this->setting->decimals, '.', ',')?></td>
      </tr>  
      <?php }?>
  <tr style="font-size: smaller;">
    <td ><?=label("Total")?></td>
    <td ></td>
    <td ></td>
    <td ><?=number_format((float)$totalArea, $this->setting->decimals, '.', ',')?></td>
  </tr>  
  <?php }?>
  </tbody>
</table>
   
               
   <?php }?>
   <?php if($type==2){?>
     <table width="100%">
          <tr class="text-center" style="font-size: small;"><td id="titulo1">Informe Detallado Diario</td></tr>
          <tr class="text-center" style="font-size: small;"><td id="titulo2">Desde:<?=$dateBegin;?> Hasta: <?=$dateEnd;?></td></tr>
      </table>

      <table id="tableDiario" class="table display nowrap" style="width:100%">
      <?php if(isset($sales)){
        ?>
      <thead class="thead-inverse">
        <tr style="font-size: smaller;">
          <th ><?=label('invoice');?></th>
          <th ><?=label('Método');?></th>
          <th ><?=label('Date');?></th>          
          <th ><?=label('Base');?></th>
          <th ><?=label('IVA');?></th>
          <th ><?=label('Status');?></th>
          <th ><?=label('Total');?></th>
        </tr>
      </thead>
      <tbody>     
          <?php

            $totalBbase = 0;
            $totalTaxamount = 0;
            $sumaTotal = 0;
            $i=0;
            $j=0;
          foreach ($sales as $row):
            $i++;
            $status="Valida";
            $color="";
            if($row->in_activo==2)
              {
                $status="Anulada";
                $color="color:red";
                $j++;
              }
              else
              {
                $totalBbase+=$row->base;
                $totalTaxamount+=$row->taxamount;
                $sumaTotal+=$row->subtotal;
              }
                $paidmethod="Efectivo";
                $PayMethode = explode('~', $row->paidmethod);
                if($PayMethode[0]==1)
                    $paidmethod="Tarjeta de Crédito";
                if($PayMethode[0]==2)
                    $paidmethod="Cheque";
                if($PayMethode[0]==3)
                    $paidmethod="A Crédito";
                $created_at=$row->date->format('d/m/Y H:i');
                //$date=$row->date;
                 
            ?>
              <tr style="font-size: smaller; <?=$color?> ">
                 <td ><?=sprintf("%08d", $row->invoice);?></td>
                 <td ><?=$paidmethod;?></td>
                 <td ><?=$created_at?></td>
                 <td ><?=number_format((float)$row->base, $this->setting->decimals, ',', '.');?></td>
                 <td ><?=number_format((float)$row->taxamount, $this->setting->decimals, ',', '.');?></td>
                 <td ><?=$status;?></td>
                 <td ><?=number_format((float)$row->subtotal, $this->setting->decimals, ',', '.');?></td>
              </tr>
           
           <?php endforeach;?>
           <tr style="font-size: smaller;">
              <td >Totales&nbsp;:</td>
              <td ><?=($i-$j);?>&nbsp;&nbsp;Valido</td>
              <td ><?=$j;?>&nbsp;&nbsp;&nbsp;Anulado</td>
              <td ><?=number_format((float)$totalBbase, $this->setting->decimals, ',', '.');?></td>
              <td ><?=number_format((float)$totalTaxamount, $this->setting->decimals, ',', '.');?></td>
              <td ></td>              
              <td ><?=number_format($sumaTotal, $this->setting->decimals, ',', '.');?></td>
           </tr>
           
          </tbody>
         <?php }?>    
     </table>


   <?php }?>
  

 <?php if($type==4){?>
   <?php $total=0;
        foreach ($sales as $row):
         // $taxamount=$row->value*$row->base/100;
          //$dateBegin=$row->datebegin;
          //$dateEnd=$row->dateend;
          $total+=$row->base;
         endforeach;?>
 
          <?php 
              $invoiceEnd=0;
              $invoiceBegin=10000000;
               foreach ($sales as $row):
                if($row->invoiceend>$invoiceEnd)
                  $invoiceEnd=$row->invoiceend;
                if($row->invoicebegin<$invoiceBegin)
                  $invoiceBegin=$row->invoicebegin;
               endforeach;?>
  
  <table width="100%">
      <tr class="text-center" style="font-size: small;"><td id="tituloF1">INFORME FISCAL DE VENTAS DIARIA</td></tr>
      <tr class="text-center" style="font-size: small;"><td id="tituloF2"><?=label('InvoiceBegin').' '.sprintf("%08d", $invoiceBegin).' - '.label('InvoiceEnd').' '.sprintf("%08d", $invoiceEnd);?></td></tr>
  </table>

  
 
 <table id="tableFiscal" class="display" style="width:100%">
  <thead class="thead-inverse">
    <tr style="font-size: smaller;">
      <th ><?='Desde: '?></th>
      <th ><?=$dateBegin;?></th>
      <th ><?='Hasta: '?></th>
      <th ><?=$dateEnd;?></th>
    </tr>
  </thead>
  <tbody>     
    <tr style="font-size: smaller;">
      <td class="border"><?=label('Cuenta')?></td>
      <td class="border"></td>
      <td class="border"><?=label('Débito')?></td>
      <td class="border"><?=label('Crédito')?></td>
    </tr> 
    <tr style="font-size: smaller;">
      <td ><?=label('Caja')?></td>
      <td ></td>
      <td ><?=number_format((float)$total, $this->setting->decimals, ',', '.');?></td>
      <td ></td>
    </tr>
    <?php foreach ($sales as $row):?>
    <tr style="font-size: smaller;">
      <td >Ventas al <?=$row->name?></td>
      <td ></td>
      <td ></td>
      <td ><?=number_format((float)$row->base, $this->setting->decimals, ',', '.');?></td>
    </tr>
    <?php endforeach;?>
    <tr style="font-size: smaller;">
      <td>Sumas iguales</td>
      <td ></td>
      <td><?=number_format((float)$total, $this->setting->decimals, ',', '.');?></td>
      <td><?=number_format((float)$total, $this->setting->decimals, ',', '.');?></td>       
    </tr>      
    <tr style="font-size: smaller;">
      <td class="border"><?=label('Documents')?></td>
      <td class="border"></td>
      <td class="border"></td>
      <td class="border"></td>
    </tr> 
    <?php foreach ($invoices as $row):?>
    <tr style="font-size: smaller;">
      <td ><?=label('InvoiceBegin');?></td>
      <td ></td>
      <td ><?=sprintf("%08d", $row->invoicebegin);?></td>
      <td ></td>
    </tr> 
    <tr style="font-size: smaller;">
      <td ><?=label('InvoiceEnd');?></td>
      <td ></td>
      <td ><?=sprintf("%08d", $row->invoiceend);?></td>
      <td ></td>
    </tr>
     <tr style="font-size: smaller;">
      <td ><?=label('Clientes atendidos');?></td>
      <td ></td>
      <td ><?=$row->qt;?></td>
      <td ></td>
    </tr>
    <?php endforeach;?>
    <tr style="font-size: smaller;">
      <td class="border"><?=label('TaxResume')?></td>
      <td class="border"></td>
      <td class="border"></td>
      <td class="border"></td>
    </tr> 
    <?php foreach ($salesTax as $row):?>
    <tr style="font-size: smaller;">
      <td><?=$row->name;?></td>
      <td><?=number_format((float)$row->base, $this->setting->decimals, '', '.');?></td>
      <td><?=number_format((float)$row->taxamount, $this->setting->decimals, '', '.');?></td>
      <td><?=number_format((float)$row->total, $this->setting->decimals, '', '.');?></td>
    </tr>           
    <?php endforeach;?>
     <tr style="font-size: smaller;">
      <td class="border"></td>
      <td class="border"></td>
      <td class="border"></td>
      <td class="border"></td>
    </tr> 
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr> 
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;:</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr> 
    <tr>
      <td>Impreso por:</td>
      <td>NITPOS</td>
      <td>Aprobado por:</td>
      <td>_________________</td>
    </tr> 
    
  </tbody>     
  </table>      


   <?php }?>
     <?php if($type==5){?>
      <table width="100%">
          <tr class="text-center" style="font-size: small;"><td id="titulo1">Informe de Retenciones</td></tr>
          <tr class="text-center" style="font-size: small;"><td id="titulo2">Desde:<?=$dateBegin;?> Hasta; <?=$dateEnd;?></td></tr>
      </table>
      <table id="tableRetention" class="table display nowrap" style="width:100%">
      <?php if(isset($purchases)){
        ?>
      <thead class="thead-inverse">
        <tr style="font-size: smaller;">
          <th ><?=label('Orden de Compra');?></th>
          <th ><?=label('Supplier');?></th>
          <th ><?=label('Date');?></th>
          <th ><?=label('Total Compra');?></th>          
          <th ><?=label('Retención');?></th>
        </tr>
      </thead>
      <tbody>     
          <?php

            $totalRetention = 0;
            $sumaTotal = 0;
            $i=0;
            $j=0;
          foreach ($purchases as $row):
            $i++;
            $totalRetention+=$row->retention;
            $sumaTotal+=$row->total;
            ?>
              <tr style="font-size: smaller; ">
                 <td ><?=sprintf("%08d", $row->purchase_order);?></td>
                 <td ><?=$row->supplier;?></td>
                 <td ><?=$row->date->format('d/m/Y');?></td>
                 <td ><?=number_format((float)$row->total, $this->setting->decimals, ',', '.');?></td>
                 <td ><?=number_format((float)$row->retention, $this->setting->decimals, ',', '.');?></td>
              </tr>
           
           <?php endforeach;?>
           <tr style="font-size: smaller;">
              <td >Totales&nbsp;:</td>
              <td ><?=$i;?>&nbsp;&nbsp;Retención(es)</td>
              <td ></td>
              <td ><?=number_format($sumaTotal, $this->setting->decimals, ',', '.');?></td>
              <td ><?=number_format($totalRetention, $this->setting->decimals, ',', '.');?></td>
           </tr>
           
          </tbody>
         <?php 
          $setting=strip_tags($this->setting->receiptheader,'<br>');
        }?>    
     </table>


   <?php }?>

  </div>
</div>
</div>

      <script src="<?=base_url()?>assets/js/jquery.dataTables.min.js"></script>
      <script src="<?=base_url()?>assets/js/dataTables.buttons.min.js"></script>
      <script src="<?=base_url()?>assets/js/jszip.min.js"></script>
      <script src="<?=base_url()?>assets/js/pdfmake.min.js"></script>
      <script src="<?=base_url()?>assets/js/vfs_fonts.js"></script>
      <script src="<?=base_url()?>assets/js/buttons.html5.min.js"></script>

 <script type="text/javascript">
  

$(document).ready(function() {
      $("#ticket").on("hidden.bs.modal", function () {
        // Aquí va el código a disparar en el evento
         $('.dataTables_filter').show();
         $('.dataTables_length').show();
         $('.dataTables_paginate').show();   
         $('.dt-buttons').show(); 
         $('.dataTables_info').show(); 

      });
      $('#dateBegin').datepicker({
          todayHighlight: true,
          format:'yyyy-mm-dd'
      });
      
      $('#dateEnd').datepicker({
          todayHighlight: true,
          format:'yyyy-mm-dd'
      });
      var titulo1 = $('#titulo1').html();
      var titulo2 = $('#titulo2').html();
      <?php $setting=strip_tags($this->setting->receiptheader,'<br>');?>
      var cabecera ="<?=$setting;?>";
      var setting = '';
      var arreglo=cabecera.split("<br>");
      for(i=0;i<arreglo.length;++i)
      {
            setting=setting+arreglo[i]+"\n";
      }
      settingHeader=setting+'\n'+titulo1+'\n'+titulo2;
      $('#tableRetention').DataTable({
            "pageLength": 50,
             dom: 'Bfrtip',
             buttons: [
                'excelHtml5',
                'csvHtml5',
                 {
                    extend: 'pdfHtml5',
                    title: settingHeader,
                    exportOptions: {
                          columns: [ 0, 1, 2, 3, 4]
                    },
                    customize:function(doc) {
                        //console.log(doc);
                        doc.styles.title = {
                            color: "#18293d",
                            fontSize: "12",
                            alignment: "center"
                        };
                        doc.styles.tableHeader = {
                            fillColor:"#18293d",
                            color:"white"
                        };
                        doc.content[1].table.widths = ["*", "35%","*","*","*"];
                    }
                 }
            ]
      }); 

      $('#tableDiario').DataTable({
            "pageLength": 50,
             dom: 'Bfrtip',
             buttons: [
                'excelHtml5',
                'csvHtml5',
                 {
                    extend: 'pdfHtml5',
                    title: settingHeader,
                    exportOptions: {
                          columns: [ 0, 1, 2, 3, 4, 5, 6]
                    },
                    customize:function(doc) {
                        //console.log(doc);
                        doc.styles.title = {
                            color: "#18293d",
                            fontSize: "12",
                            alignment: "center"
                        };
                        doc.styles.tableHeader = {
                            fillColor:"#18293d",
                            color:"white"
                        };
                        doc.content[1].table.widths = ["*", "*","*","*","*","*","*"];
                    }
                 }
            ]
      });
      titulo1 = $('#tituloZ').html();
      titulo2 = $('#tituloZ2').html();
   
      settingHeader=setting+'\n'+titulo1+'\n'+titulo2;
      $('#tableReporteZ').DataTable({
              "order": [], //Initial no order.
              "aaSorting": [],
              "aoColumnDefs": [
                 { 
                    'bSortable': false, 
                    'aTargets': [0, 1, 2, 3] 
                 }
              ],
              "bPaginate": false,
              "info":  false,
              dom: 'Bfrtip',
              buttons: [
                'excelHtml5',
                'csvHtml5',
                 {
                    extend: 'pdfHtml5',
                    title: settingHeader,
                    exportOptions: {
                          columns: [ 0, 1, 2, 3]
                    },
                    customize:function(doc) {
                        //console.log(doc);
                        doc.styles.title = {
                            color: "#18293d",
                            fontSize: "12",
                            alignment: "center"
                        };
                        doc.styles.tableHeader = {
                            fillColor:"#18293d",
                            color:"white"
                        };
                        doc.content[1].table.widths = ["25%", "33%", "25%", "*"];
                    }
                 }
            ]
      }); 
      titulo1 = $('#tituloF1').html();
      titulo2 = $('#tituloF2').html();
   
      settingHeader=setting+'\n'+titulo1+'\n'+titulo2;
      $('#tableFiscal').DataTable({
              "order": [], //Initial no order.
              "aaSorting": [],
              "aoColumnDefs": [
                 { 
                    'bSortable': false, 
                    'aTargets': [0, 1, 2, 3] 
                 }
              ],
              "pageLength": 50,
              dom: 'Bfrtip',
              buttons: [
                'excelHtml5',
                'csvHtml5',
                 {
                    extend: 'pdfHtml5',
                    title: settingHeader,
                    exportOptions: {
                          columns: [ 0, 1, 2, 3]
                    },
                    customize:function(doc) {
                        //console.log(doc);
                        doc.styles.title = {
                            color: "#18293d",
                            fontSize: "12",
                            alignment: "center"
                        };
                        doc.styles.tableHeader = {
                            fillColor:"#18293d",
                            color:"white"
                        };
                        doc.content[1].table.widths = ["25%", "25%", "25%", "*"];
                    }
                 }
            ]
      });

  });

function gotoCashmovement()
{
  location.href = "<?php echo site_url('cashmovements')?>";
      

}
function PrintTicket()
{

   $('.tabla-pdfZ').removeAttr('id');
   $('.modal-body').removeAttr('id');
   window.print();
   $('.modal-body').attr('id', 'modal-body');
   $('.tabla-pdfZ').attr('id', 'tableReporteZ');

   $("#ticket").modal('hide');


}

function ticket(){

   var content = $('#resultReport').html();
   $('#printSection').html(content);
   $('.dataTables_filter').hide();
   $('.dataTables_length').hide();   
   $('.dataTables_paginate').hide(); 
   $('.dt-buttons').hide(); 
   $('.dataTables_info').hide();
   $('#ticket').modal('show');



}
function pdfreceipt(){

   //$('.dataTables_filter').hide();
   $('.dataTables_filter').css('display','none');
   $('.dataTables_length').css('display','none');   
   $('.dataTables_paginate').css('display','none');   

   var content = $('#printSection').html();
   $.redirect('<?php echo site_url('pos/pdfreceipt')?>/', { content: content });
   
   $('.dataTables_filter').show();
   $('.dataTables_length').show();   
   $('.dataTables_paginate').show();   
      

}


  </script>

<!-- Modal ticket -->
<div class="modal fade" id="ticket" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 <div class="modal-dialog" role="document" id="ticketModal">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="ticket"><?=label("Receipt");?></h4>
      </div>
      <div class="modal-body" id="modal-body">
         <div id="printSection">
            <!-- Ticket goes here -->
            <center><h1 style="color:#34495E"><?=label("empty");?></h1></center>
         </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default hiddenpr" data-dismiss="modal"><?=label("Close");?></button>
        <!--<button type="button" class="btn btn-add hiddenpr" href="javascript:void(0)" onClick="pdfreceipt()">PDF</button>-->
        <!--<button type="button" class="btn btn-add hiddenpr" onclick="email()"><?=label("email");?></button>-->
        <button type="button" id="btnTicket" class="btn btn-add hiddenpr" onclick="PrintTicket()"><?=label("print");?></button>
      </div>
    </div>
 </div>
</div>
<!-- /.Modal -->

