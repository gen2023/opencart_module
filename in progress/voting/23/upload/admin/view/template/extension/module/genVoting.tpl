<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
		<?php if ($module_id) {?>
		<button type="submit" form="form-genVoting" name="apply" data-toggle="tooltip" title="<?php echo $button_apply; ?>" class="btn btn-success"><i class="fa fa-save"></i></button>
		<?php } ?>
        <button type="submit" form="form-genVoting" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
	<?php if ($text_success) {?>
	    <div id="message_success" class="alert alert-success alert-dismissible"><?php echo $text_success; ?></div>
	<? } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-genVoting" class="form-horizontal">
		  <ul class="nav nav-tabs">
			<li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
			<li><a href="#tab-data" data-toggle="tab"><?php echo $tab_data; ?></a></li>
			<li style="<?php echo $viewStatistic;?>"><a href="#tab-statistic" data-toggle="tab"><?php echo $tab_statistic; ?></a></li>
		  </ul>
		  <div class="tab-content">
			<div class="tab-pane active" id="tab-general">
			  <input type="hidden" name="module_id" value="<?php echo $module_id; ?>" id="input-module_id" />
			  <div class="form-group required">
				<label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
				<div class="col-sm-10">
				  <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
				  <?php if ($error_name) { ?>
				  <div class="text-danger"><?php echo $error_name; ?></div>
				  <?php } ?>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label"><?php echo $entry_title_module; ?></label>
				<div class="col-sm-10">
				  <?php foreach ($languages as $language) { ?>
				  <div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>
					<input type="text" name="title_module[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($title_module[$language['language_id']]) ? $title_module[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_title_module; ?>" class="form-control" />
				  </div>
				  <?php } ?>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-viewResult"><?php echo $entry_viewResult; ?></label>
				<div class="col-sm-10">
				  <select name="viewResult" id="input-viewResult" class="form-control">
					<?php if ($viewResult) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0"><?php echo $text_disabled; ?></option>
					<?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
					<?php } ?>
				  </select>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-type_module"><?php echo $entry_type; ?></label>
				<div class="col-sm-10">
				  <select name="type_module" id="input-type_module" class="form-control">
					<?php if ($type_module) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0"><?php echo $text_disabled; ?></option>
					<?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
					<?php } ?>
				  </select>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
				<div class="col-sm-10">
				  <select name="status" id="input-status" class="form-control">
					<?php if ($status) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0"><?php echo $text_disabled; ?></option>
					<?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
					<?php } ?>
				  </select>
				</div>
			  </div>
			</div>
			<div class="tab-pane" id="tab-data">
			  <div class="table-responsive">
                <table id="attribute" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo $entry_attribute; ?></td>
                      <td></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $attribute_row = 0; ?>
                    <?php foreach ($voting_attributes as $voting_attribute) { ?>
                    <tr id="attribute-row<?php echo $attribute_row; ?>">
                      <td class="text-left"><?php foreach ($languages as $language) { ?>
                        <div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>
                          <input name="voting_attributes[<?php echo $attribute_row; ?>][<?php echo $language['language_id']; ?>][text]" value="<?php echo isset($voting_attribute[$language['language_id']]) ? $voting_attribute[$language['language_id']]['text'] : ''; ?>" placeholder="<?php echo $entry_attribute; ?>" class="form-control">
                        </div>
                        <?php } ?>
						<input type="hidden" name="voting_attributes[<?php echo $attribute_row; ?>][value]" value="<?php echo isset($voting_attribute) ? $voting_attribute['value'] : '0'; ?>" /></td>
                      <td class="text-left"><button type="button" onclick="$('#attribute-row<?php echo $attribute_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                    </tr>
                    <?php $attribute_row++; ?>
                    <?php } ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td></td>
                      <td class="text-left"><button type="button" onclick="addAttribute();" data-toggle="tooltip" title="<?php echo $button_attribute_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
			</div>  
			<div class="tab-pane" id="tab-statistic">
				<table id="statistic" class="table table-striped table-bordered table-hover">
				  <thead>
                    <tr>
                      <td class="text-left"><?php echo $entry_attribute; ?></td>
                      <td><?php echo $text_statistic; ?></td>
                    </tr>
                  </thead>
				  <tbody>

                    <?php foreach ($voting_attributes as $voting_attribute) { ?>
                    <tr>
                      <td class="text-left"><?php foreach ($languages as $language) { ?>
                        <div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>
                          <label class="col-sm-10 control-label" for="input-name"><?php echo isset($voting_attribute[$language['language_id']]) ? $voting_attribute[$language['language_id']]['text'] : ''; ?></label>
                        </div>
                        <?php } ?>
					  </td>
                      <td class="text-left">
						<label class="col-sm-2 control-label" for="input-name"><?php echo isset($voting_attribute) ? $voting_attribute['value'] : '0'; ?></label>
					  </td>
                    </tr>
                    <?php } ?>
                  </tbody>
				</table>			  
			</div>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
	setTimeout(function() { 
		$('#message_success').css("display", "none");
		$('#message_success')[0].textContent='';
	}, 10000);
</script>
  <script type="text/javascript"><!--
var attribute_row = <?php echo $attribute_row; ?>;

function addAttribute() {
    html  = '<tr id="attribute-row' + attribute_row + '">';
	html += '  <td class="text-left">';
	<?php foreach ($languages as $language) { ?>
	html += '<div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span><input value="" name="voting_attributes[' + attribute_row + '][<?php echo $language['language_id']; ?>][text]" placeholder="<?php echo $entry_attribute; ?>" class="form-control"></div>';
    <?php } ?>
	html += '  <input type="hidden" name="voting_attributes[' + attribute_row + '][value]" value="0" /></td>';
	html += '  <td class="text-left"><button type="button" onclick="$(\'#attribute-row' + attribute_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
    html += '</tr>';

	$('#attribute tbody').append(html);
	attribute_row++;
}
//--></script>
<?php echo $footer; ?>