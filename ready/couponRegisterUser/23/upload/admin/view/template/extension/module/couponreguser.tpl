<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-html" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
		 <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-html" class="form-horizontal">
		  <ul class="nav nav-tabs">
			<li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
			<li><a href="#tab-setting_mail" data-toggle="tab"><?php echo $tab_setting_mail; ?></a></li>
		  </ul>
		  <div class="tab-content">
			<div class="tab-pane active" id="tab-general">
              <div class="form-group col-sm-12">
                <label class="col-sm-2 control-label" for="input-type"><span data-toggle="tooltip" title="<?php echo $help_type; ?>"><?php echo $entry_type; ?></span></label>
                <div class="col-sm-10">
                  <select name="module_couponreguser_type" id="input-type" class="form-control">                    
                    <?php if ($module_couponreguser_type== 'P') { ?>                  
						<option value="P" selected="selected"><?php echo $text_percent; ?></option>                    
                    <?php } else { ?>                  
						<option value="P"><?php echo $text_percent; ?></option>                    
                    <?php } ?>
                    <?php if ($module_couponreguser_type== 'F') { ?>                   
						<option value="F" selected="selected"><?php echo $text_amount; ?></option>                    
                    <?php } else { ?>                   
						<option value="F"><?php echo $text_amount; ?></option>                    
                    <?php } ?>                  
                  </select>
                </div>
              </div>
              <div class="form-group col-sm-12">
                <label class="col-sm-2 control-label" for="input-discount"><?php echo $entry_discount; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="module_couponreguser_discount" value="<?php echo $module_couponreguser_discount; ?>" placeholder="<?php echo $entry_discount; ?>" id="input-discount" class="form-control" />
                </div>
              </div>
              <div class="form-group col-sm-6">
                <label class="col-sm-6 control-label"><span data-toggle="tooltip" title="<?php echo $help_logged; ?>"><?php echo $entry_logged; ?></span></label>
                <div class="col-sm-6">
                  <label class="radio-inline"> <?php if ($module_couponreguser_logged) { ?>  
                    <input type="radio" name="module_couponreguser_logged" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="module_couponreguser_logged" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?> </label>
                  <label class="radio-inline"> <?php if (!$module_couponreguser_logged) {?>
                    <input type="radio" name="module_couponreguser_logged" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="module_couponreguser_logged" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?> </label>
                </div>
              </div>
			  <div class="form-group col-sm-6">
                <label class="col-sm-6 control-label"><?php echo $entry_shipping; ?></label>
                <div class="col-sm-6">
                  <label class="radio-inline"> <?php if ($module_couponreguser_shipping) {?>
                    <input type="radio" name="module_couponreguser_shipping" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="module_couponreguser_shipping" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?> </label>
                  <label class="radio-inline"> <?php if (!$module_couponreguser_shipping) {?>
                    <input type="radio" name="module_couponreguser_shipping" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="module_couponreguser_shipping" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?> </label>
                </div>
              </div>
			  <div class="form-group col-sm-12">
                <label class="col-sm-2 control-label" for="input-total"><span data-toggle="tooltip" title="<?php echo $help_total; ?>"><?php echo $entry_total; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="module_couponreguser_total" value="<?php echo $module_couponreguser_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" class="form-control" />
                </div>
              </div>
			  <div class="form-group col-sm-12">
                <label class="col-sm-2 control-label" for="input-count-day"><?php echo $entry_count_day; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="module_couponreguser_uses_count_day" value="<?php echo $module_couponreguser_uses_count_day; ?>" placeholder="<?php echo $entry_count_day; ?>" id="input-uses-total" class="form-control" />
                </div>
              </div>
              <div class="form-group col-sm-6">
                <label class="col-sm-6 control-label" for="input-uses-total"><span data-toggle="tooltip" title="<?php echo $entry_count_day; ?><?php echo $help_uses_total; ?>"><?php echo $entry_uses_total; ?></span></label>
                <div class="col-sm-6">
                  <input type="text" name="module_couponreguser_uses_total" value="<?php echo $module_couponreguser_uses_total; ?>" placeholder="<?php echo $entry_uses_total; ?>" id="input-uses-total" class="form-control" />
                </div>
              </div>
              <div class="form-group col-sm-6">
                <label class="col-sm-6 control-label" for="input-uses-customer"><span data-toggle="tooltip" title="<?php echo $help_uses_customer; ?>"><?php echo $entry_uses_customer; ?></span></label>
                <div class="col-sm-6">
                  <input type="text" name="module_couponreguser_uses_customer" value="<?php echo $module_couponreguser_uses_customer; ?>" placeholder="<?php echo $entry_uses_customer; ?>" id="input-uses-customer" class="form-control" />
                </div>
              </div>
			  <div class="form-group col-sm-12">
                <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                <div class="col-sm-10">
                  <select name="module_couponreguser_status" id="input-status" class="form-control">                    
                    <?php if ($module_couponreguser_status) {?>                    
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
		    <div class="tab-pane" id="tab-setting_mail">
		      <div class="tab-pane">
				<ul class="nav nav-tabs" id="language">
				  <?php foreach ($languages as $language) { ?>
				  <li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
				  <?php } ?>
				</ul>
				<div class="tab-content">
				  <?php foreach ($languages as $language) { ?>
				  <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
					<div class="form-group">
					  <label class="col-sm-2 control-label" for="input-mail<?php echo $language['language_id']; ?>"><span data-toggle="tooltip" title="<?php echo $help_mail; ?>"><?php echo $entry_mail; ?></span></label>
					  <div class="col-sm-10">
					    <textarea name="module_couponreguser_mail[<?php echo $language['language_id']; ?>][description]" placeholder="<?php echo $entry_mail; ?>" id="input-mail<?php echo $language['language_id']; ?>" data-lang="<?php echo $lang; ?>" class="form-control summernote"><?php echo isset($module_couponreguser_mail[$language['language_id']]['description']) ? $module_couponreguser_mail[$language['language_id']]['description'] : ''; ?></textarea>
					  </div>
					</div>
				  </div>
				  <?php } ?>
				</div>
			  </div>
			</div>
		  </div>
        </form>
      </div>
    </div>
  </div>
</div>
  <script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
<?php if ($ckeditor) { ?>
ckeditorInit('input-mail<?php echo $language['language_id']; ?>', getURLVar('token'));
<?php } ?>
<?php } ?>
//--></script> 
  <script type="text/javascript"><!--
$('#language a:first').tab('show');
//--></script></div>
<?php echo $footer; ?>