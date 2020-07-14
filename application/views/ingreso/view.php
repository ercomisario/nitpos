<!-- Page Content -->
<link rel="stylesheet" href="<?=base_url()?>assets/css/buttons.dataTables.min.css" type='text/css'>

<div class="container" style="width: 95%;">
   <h3><?=label("Ingresos ventas crédito");?></h3>
      <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
          <thead>
              <tr>
                  <th><?=label("Customer");?></th>
                  <th><?=label("invoice");?></th>
                  <th><?=label("Fecha venta");?></th>
                  <th><?=label("Fecha últ pago");?></th>
                  <th><?=label("Dias");?></th>
                  <th><?=label("Cajero");?></th>
                  <th><?=label("Monto");?></th>
                  <th><?=label("Ult. abono");?></th>
                  <th><?=label("Pendiente");?></th>
                  <th><?=label("Status");?></th>
                  <th><?=label("Action");?></th>
              </tr>
          </thead>

          <tbody>
             <?php 
              $pendiente=0;
              $invoiceAnt=0;
              $fecha_hoy=date('Y-m-d');

            foreach ($ingresos as $row){
            
              $pendiente=$row->total-$row->pagado;
              if($row->pagado>=$row->total)
              {
                $classstatus = 'paid';
                $pendiente=0;
              }
              else
                $classstatus = 'Partiallypaid';

              if(!$row->pagado)
                $classstatus = 'unpaid';

            $fecha_i=$row->date->format('Y-m-d');
            $fecha_f=$row->fecha_abono;
            if(!$fecha_f)$fecha_f=$fecha_hoy;
              $dias = (strtotime($fecha_i)-strtotime($fecha_f))/86400;
              $dias   = abs($dias); $dias = floor($dias);
            
            ?>
              <tr>
                 <td><?=$row->clientname;?></td>
                 <td><?=sprintf("%08d", $row->invoice)?></td>
                 <td><?=$fecha_i;?></td>
                 <td><?=$row->fecha_abono;?></td>
                 <td><?=$dias;?></td>
                 <td><?=$row->username;?></td>
                 <td><?=number_format((float)$row->total, $this->setting->decimals, ',', '.');?></td>
                 <td><?=number_format((float)$row->pago, $this->setting->decimals, ',', '.');?></td>
                 <td><?=number_format((float)$pendiente, $this->setting->decimals, ',', '.');?></td>
                 <td><span class="<?=$classstatus?>"><?=label($classstatus)?><span></td>
                 <td><div class="btn-group">
                          <a class="btn btn-primary dropdown-toggle" href="javascript:void(0)" data-toggle="dropdown" >
                          <i class="fa fa-cog fa-fw"></i> <?=label("Action")?></a>
                          <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
                             <span class="fa fa-caret-down" title="Toggle dropdown menu"></span></a>
                          <ul class="dropdown-menu">
                             <li><a href="javascript:void(0)" onclick="payaments(<?=$row->id;?>);">
                               <i class="fa fa-sticky-note" aria-hidden="true"></i> <?=label("invoice")?></a></li>
                          </ul>
                      </div>

                  </td>
              </tr>
           <?php };?>
          </tbody>
      </table>

</div>
<!-- /.container -->
      <script src="<?=base_url()?>assets/js/jquery.dataTables.min.js"></script>
      <script src="<?=base_url()?>assets/js/dataTables.buttons.min.js"></script>
      <script src="<?=base_url()?>assets/js/jszip.min.js"></script>
      <script src="<?=base_url()?>assets/js/pdfmake.min.js"></script>
      <script src="<?=base_url()?>assets/js/vfs_fonts.js"></script>
      <script src="<?=base_url()?>assets/js/buttons.html5.min.js"></script>

<script type="text/javascript">

   var table;
    $(document).ready(function() {

      var setting='<?=$this->setting->companyname?>';

      table = $('#table').DataTable({

        "order": [], //Initial no order.
        "columnDefs": [
        {
          "targets": [ -1 ], //last column
          "orderable": false, //set not orderable
        },
        ],
         "bInfo": false,
          dom: 'Bfrtip',
         "pageLength": 50,                  //Set column definition initialisation properties.
          buttons: [
              "excelHtml5",
              "csvHtml5",
              {
                  extend: "pdfHtml5",
                  title: setting,
                  exportOptions: {
                      columns: [ 0, 1, 2, 4, 5, 6, 8, 9]
                  },
                  customize:function(doc) 
                  {
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
                      doc.content[1].table.widths = ["*","*","*","*","*","*","*","*"];
                      doc["footer"]=(function(page, pages) {
                          return {
                              columns: [
                              {
                                  alignment: "center",
                                  text: 
                                      [
                                         "Ingresos por Ventas a Créditos" 
                                      ]                                                        
                              },
                              {
                                  alignment: "center",
                                  text: 
                                      [
                                          "Página ", 
                                          { 
                                              text: 
                                              page.toString()
                                          },  
                                          " de ", 
                                          { 
                                              text: 
                                              pages.toString()
                                          },  
                                          " "
                                      ]
                              }
                              ]
                            }
                      });
                  }

              }
              ]
      });
    });







    function PrintTicket() {
       $('.modal-body').removeAttr('id');
       window.print();
       $('.modal-body').attr('id', 'modal-body');
    }

    function payaments(id){
   
      $.ajax({
          url : "<?php echo site_url('invoices/payaments')?>/"+id,
          type: "POST",
          success: function(data)
          {
              $('#printSection').html(data);
              $('#ticket').modal('show');
              $('.btnDeletePaid').hide();
              $('#btnAddPaid').hide();
          },
          error: function (jqXHR, textStatus, errorThrown)
          {
             alert("error");
          }
     });
    };
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
          <button type="button" class="btn btn-add hiddenpr" href="javascript:void(0)" onClick="pdfreceipt()">PDF</button>
          <button type="button" class="btn btn-add hiddenpr" onclick="PrintTicket()"><?=label("print");?></button>
        </div>
      </div>
   </div>
  </div>