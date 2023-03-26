<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-services" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-services" class="form-horizontal">
		<div class="form-group">
		<table id="service" class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <td class="text-left required"><?php echo $entry_name; ?></td>
                <td></td>
              </tr>
            </thead>
            <tbody>
              <?php $service_row = 1; ?>			  
                <?php foreach ($services as $service) { ?>			  
				  <tr id="service-row<?php echo $service_row; ?>">
					<td class="text-left" style="width: 70%;"><input type="hidden" name="service[<?php echo $service_row; ?>][service_id]" value="<?php echo $service['service_id']; ?>" />
					  <?php foreach ($languages as $language) { ?>
					  <div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>
						<input type="text" name="service[<?php echo $service_row; ?>][service_name][<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($service['service_name'][$language['language_id']]) ? $service['service_name'][$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_name; ?>" class="form-control" />
					  </div>
					  <?php if (isset($error_service[$service_row][$language['language_id']])) { ?>
					  <div class="text-danger"><?php echo $error_service[$service_row][$language['language_id']]; ?></div>
					  <?php } ?>
					  <?php } ?></td>
					<td class="text-left"><button type="button" onclick="$('#service-row<?php echo $service_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
				  </tr>
                <?php $service_row++; ?>
              <?php } ?>
            </tbody>
            <tfoot>
              <tr>
                <td colspan="2"></td>
                <td class="text-left"><a onclick="addServiceRow();" data-toggle="tooltip" title="<?php echo $button_service_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></a></td>
              </tr>
            </tfoot>
          </table>
		  </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="services_status" id="input-status" class="form-control">
                <?php if ($services_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
  <script type="text/javascript"><!--
var service_row = <?php echo $service_row; ?>;

function addServiceRow() {
	html  = '<tr id="service-row' + service_row + '">';	
    html += '  <td class="text-left" style="width: 70%;"><input type="hidden" name="service[' + service_row + '][service_id]" value="" />';
	<?php foreach ($languages as $language) { ?>
	html += '  <div class="input-group">';
	html += '    <span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span><input type="text" name="service[' + service_row + '][service_name][<?php echo $language['language_id']; ?>][name]" value="" placeholder="<?php echo $entry_name; ?>" class="form-control" />';
    html += '  </div>';
	<?php } ?>
	html += '  </td>';
	html += '  <td class="text-left"><button type="button" onclick="$(\'#service-row' + service_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';	
	
	$('#service tbody').append(html);
	
	service_row++;
}
//--></script>
<?php echo $footer; ?>