<!-- Page Content -->
<div class="container">
   <h3><?=label("Brands");?></h3>
    <div class="float-right hidden-xs hidden-sm" style="margin-bottom:-30px;">
         <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#AddBrand">
            <?=label("AddBrand");?>
         </button>         
         <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#ImportCategory"><?=label("UploadCSVfile");?></button>
     </div>
      <table id="Table" class="table table-striped table-bordered" cellspacing="0" width="100%">
          <thead>
              <tr>
                  <th><?=label("BrandName");?></th>
                  <th><?=label("CreatedAt");?></th>
                  <th><?=label("Action");?></th>
              </tr>
          </thead>

          <tbody>
             <?php foreach ($brands as $row):?>
              <tr>
                 <td><?=$row->name;?></td>
                 <td><?=$row->date->format("d/m/Y");?></td>
                 <td><div class="btn-group">
                          <a class="btn btn-primary dropdown-toggle" href="javascript:void(0)" data-toggle="dropdown" >
                          <i class="fa fa-cog fa-fw"></i> <?=label("Action")?></a>
                          <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
                             <span class="fa fa-caret-down" title="Toggle dropdown menu"></span></a>
                          <ul class="dropdown-menu">
                             <li><a href="brands/edit/<?=$row->id;?>">
                                 <i class="fa fa-pencil fa-fw" aria-hidden="true"></i> <?=label("Edit")?></a></li>
                             <li><a href="javascript:void(0)" onclick="deleteBrand(<?=$row->id;?>)">
                                 <i class="fa fa-trash-o fa-fw" aria-hidden="true"></i><?=label("Delete")?></a></li>
                          </ul>
                      </div>

                  </td>
              </tr>
           <?php endforeach;?>
          </tbody>
      </table>

</div>
<!-- /.container -->
<!-- Modal -->
<div class="modal fade" id="AddBrand" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?=label("AddBrand");?></h4>
      </div>
      <?php echo form_open_multipart('brands/add'); ?>
      <div class="modal-body">
           <div class="form-group">
             <label for="name"><?=label("BrandName");?></label>
             <input type="text" maxlength="50" name="name" class="form-control" id="name" placeholder="<?=label("BrandName");?>" required>
           </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?=label("Close");?></button>
        <button type="submit" class="btn btn-add"><?=label("Submit");?></button>
      </div>
   <?php echo form_close(); ?>
    </div>
 </div>
</div>
<!-- /.Modal -->
<script type="text/javascript">

$(document).ready(function() {

<?php if($band==-1){ ?>
          swal({   
              title: '<?=label("Aviso");?>',
              text: 'Importación de marcas realizado con éxito',
              type: "info",
              showCancelButton: false,
              confirmButtonColor: "#DD6B55",
              confirmButtonText: '<?=label("Aceptar");?>',
              closeOnConfirm: true },
              function(){
                   location.href="<?=base_url()?>brands";
                }           
             );

<?php } ?>
});

</script>

<script type="text/javascript">
  
function deleteBrand(id)
{
  swal({   title: '<?=label("Areyousure");?>',
      text: '<?=label("Deletemessage");?>',
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: '<?=label("yesiam");?>',
      closeOnConfirm: false },
      function(){
         // ajax delete data to database
        $.ajax({
             url : "<?php echo site_url('brands/delete')?>/"+id,
             type: "POST",         
             success: function(data)
             {   

                  location.reload();
             },
             error: function (jqXHR, textStatus, errorThrown)
             {
                alert("error");
             }
        });
        
        swal('<?=label("Deleted");?>', '<?=label("Deletedmessage");?>', "success"); 
      });

}
</script>


<!-- Modal -->
<div class="modal fade" id="ImportCategory" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?=label("AddBrand");?></h4>
      </div>
      <?php
      $attributes = array('id' => 'addform');
      echo form_open_multipart('brands/importcsv', $attributes);
      ?>
      <div class="modal-body">
         <div class="form-group">
           <label for="user_file_import"><?=label("UploadCSVfile");?></label>
           <input type="file" name="user_file_import" id="user_file_import">
           <p class="help-block"><a href="<?=site_url('brands/csv/2');?>"><?=label('DownloadSample');?></a></p>
         </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?=label("Close");?></button>
        <button type="submit" class="btn btn-add"><?=label("Submit");?></button>
      </div>
   <?php echo form_close(); ?>
    </div>
 </div>
</div>
<!-- /.Modal -->