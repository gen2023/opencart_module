<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
		<?php if ($module_id) { ?>
		<button type="submit" form="form-module" name="apply" data-toggle="tooltip" title="<?php echo $button_apply; ?>" class="btn btn-success"><i class="fa fa-save"></i></button>
		<?php } ?>
        <button type="submit" form="form-module" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
    <div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-module" class="form-horizontal">
		<input type="hidden" name="module_id" value="<?php echo $module_id; ?>" id="input-module_id" />
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              <?php if ($error_name) { ?>
              <div class="text-danger"><?php echo $error_name; ?></div>
              <?php } ?>
            </div>
          </div>
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
                  <label class="col-sm-2 control-label" for="input-description<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>
                  <div class="col-sm-10">
					<textarea name="module_description[<?php echo $language['language_id']; ?>][description]" placeholder="<?php echo $entry_description; ?>" id="input-description<?php echo $language['language_id']; ?>" data-lang="<?php echo $lang; ?>" class="form-control summernote"><?php echo isset($module_description[$language['language_id']]) ? $module_description[$language['language_id']]['description'] : ''; ?></textarea>
                  </div>
                </div>
              </div>
              <?php } ?>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-styleBackground"><?php echo $entry_styleBackground; ?></label>
            <div class="col-sm-10">
              <select name="styleBackground" id="input-styleBackground" class="form-control">
                <?php if ($styleBackground) {?>
                <option value="1" selected="selected"><?php echo $text_image; ?></option>
                <option value="0"><?php echo $text_color; ?></option>
			  <?php }else{ ?>
                <option value="1"><?php echo $text_image; ?></option>
                <option value="0" selected="selected"><?php echo $text_color; ?></option>
				<?php } ?>
              </select>
            </div>
          </div>
		  <div id="inputColor" class="form-group">
            <label class="col-sm-2 control-label" for="input-color"><?php echo $entry_color; ?></label>
            <div class="col-sm-10">
              <input type="text" name="color" value="<?php echo $color; ?>" placeholder="<?php echo $entry_color; ?>" id="input-color" class="form-control" />
            </div>
          </div>
		  <div id="inputBackground" class="form-group"  style="display:none;">
			<label class="col-sm-2 control-label"><?php echo $entry_background; ?></label>
			<div class="col-sm-10"><a href="" id="thumb-background" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
				<input type="hidden" name="background" value="<?php echo $background; ?>" id="input-background" />
			</div>
		  </div>
		  <div class="col-sm-12 form-group">
			  <div class="col-sm-6">
				<label class="col-sm-2 control-label" for="input-width"><?php echo $entry_width; ?></label>
				<div class="col-sm-10">
				  <input type="number" name="width" value="<?php echo $width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-width" class="form-control" />
				  <?php if ($error_width) {?>
				  <div class="text-danger"><?php echo $error_width; ?></div>
				  <?php } ?>
				</div>
			  </div>
			  <div class="col-sm-6">
				<label class="col-sm-2 control-label" for="input-height"><?php echo $entry_height; ?></label>
				<div class="col-sm-10">
				  <input type="number" name="height" value="<?php echo $height; ?>" placeholder="<?php echo $entry_height; ?>" id="input-height" class="form-control" />
				  <?php if ($error_height) {?>
				  <div class="text-danger"><?php echo $error_height; ?></div>
				  <?php } ?>
				</div>
			  </div>
		  </div>
		  <div id="inputColor" class="form-group">
            <label class="col-sm-2 control-label" for="input-radius"><?php echo $entry_radius; ?></label>
            <div class="col-sm-10">
              <input type="number" name="radius" value="<?php echo $radius; ?>" placeholder="<?php echo $entry_radius; ?>" id="input-radius" class="form-control" />
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-cookie"><?php echo $entry_cookie; ?></label>
            <div class="col-sm-10">
              <select name="cookie" id="input-cookie" class="form-control">
                <?php if ($cookie) {?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                <?php }else{ ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
		  <div id="block_countView" class="form-group" style="display:none;">
			<div class="col-sm-6">
              <label class="control-label" for="input-countView"><?php echo $entry_countView; ?></label>
              <div class="col-sm-10">
				<input type="number" name="countView" value="<?php echo $countView; ?>" placeholder="<?php echo $entry_countView; ?>" id="input-countView" class="form-control" />
				<?php if ($error_countView) {?>
				  <div class="text-danger"><?php echo $error_countView; ?></div>
				<?php } ?>
              </div>
			</div>
			<div class="col-sm-6">
              <label class="control-label" for="input-validity"><?php echo $entry_validity; ?></label>
              <div class="col-sm-12">
				<input type="number" name="validity" value="<?php echo $validity; ?>" placeholder="<?php echo $entry_validity; ?>" id="input-validity" class="form-control" />
              </div>
			</div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-viewSecond"><?php echo $entry_viewSecond; ?></label>
            <div class="col-sm-10">
              <input type="number" name="viewSecond" value="<?php echo $viewSecond; ?>" placeholder="<?php echo $entry_viewSecond; ?>" id="input-viewSecond" class="form-control" />
              <?php if ($error_viewSecond) {?>
              <div class="text-danger"><?php echo $error_viewSecond; ?></div>
              <?php } ?>
            </div>
          </div>
		  <div class="col-sm-12 form-group">
			  <div class="col-sm-6">
				<label class="control-label" for="input-closeModal"><?php echo $entry_closeModal; ?></label>
				<div class="col-sm-12">
				  <select name="closeModal" id="input-closeModal" class="form-control">
					<?php if ($closeModal) {?>
					<option value="1" selected="selected"><?php echo $text_btn; ?></option>
					<option value="0"><?php echo $text_second; ?></option>
					<?php }else{ ?>
					<option value="1"><?php echo $text_btn; ?></option>
					<option value="0" selected="selected"><?php echo $text_second; ?></option>
					<?php } ?>
				  </select>
				</div>
			  </div>
			  <div id="inputCloseModalSecond" class="col-sm-6">
				<label class="control-label" for="input-closeModalSecond"><?php echo $entry_closeModalSecond; ?></label>
				<div class="col-sm-12">
				  <input type="number" name="closeModalSecond" value="<?php echo $closeModalSecond; ?>" placeholder="<?php echo $entry_closeModalSecond; ?>" id="input-closeModalSecond" class="form-control" />
				  <?php if ($error_closeModalSecond) {?>
				  <div class="text-danger"><?php echo $error_closeModalSecond; ?></div>
				  <?php } ?>
				</div>
			  </div>
		  </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-modalMobile"><?php echo $entry_modalMobile; ?></label>
            <div class="col-sm-10">
              <select name="modalMobile" id="input-modalMobile" class="form-control">
                <?php if ($modalMobile) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php }else{ ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-viewLogged"><?php echo $entry_viewLogged; ?></label>
            <div class="col-sm-10">
              <select name="viewLogged" id="input-viewLogged" class="form-control">
                <?php if ($viewLogged==1) { ?>
                <option value="1" selected="selected"><?php echo $text_all; ?></option>
                <option value="2"><?php echo $text_logged; ?></option>
				 <option value="3"><?php echo $text_noLogged; ?></option>
                <?php } else if ($viewLogged==2) { ?>
                <option value="1"><?php echo $text_all; ?></option>
                <option value="2" selected="selected"><?php echo $text_logged; ?></option>
				<option value="3"><?php echo $text_noLogged; ?></option>
                <?php } else if ($viewLogged==3) { ?>
                <option value="1"><?php echo $text_all; ?></option>
                <option value="2"><?php echo $text_logged; ?></option>
				<option value="3" selected="selected"><?php echo $text_noLogged; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-ifViewModal"><?php echo $entry_ifViewModal; ?></label>
            <div class="col-sm-10">
              <select name="ifViewModal" id="input-ifViewModal" class="form-control">
                <?php if ($ifViewModal==1) { ?>
                <option value="1" selected="selected"><?php echo $text_allView; ?></option>
                <option value="2"><?php echo $text_even; ?></option>
				<option value="3"><?php echo $text_odd; ?></option>
				<option value="4"><?php echo $text_forTime; ?></option>
                <?php } else if ($ifViewModal==2) { ?>
                <option value="1"><?php echo $text_allView; ?></option>
                <option value="2" selected="selected"><?php echo $text_even; ?></option>
				<option value="3"><?php echo $text_odd; ?></option>
				<option value="4"><?php echo $text_forTime; ?></option>
				<?php } else if ($ifViewModal==3) { ?>
                <option value="1"><?php echo $text_allView; ?></option>
                <option value="2"><?php echo $text_even; ?></option>
				<option value="3" selected="selected"><?php echo $text_odd; ?></option>
				<option value="4"><?php echo $text_forTime; ?></option>
                <?php } else if ($ifViewModal==4) { ?>
                <option value="1"><?php echo $text_allView; ?></option>
                <option value="2"><?php echo $text_even; ?></option>
				<option value="3"><?php echo $text_odd; ?></option>
				<option value="4" selected="selected"><?php echo $text_forTime; ?></option>
			  <?php } ?>
              </select>
            </div>
          </div>
		  <div id="formTime" class="form-group" style="display:none;">
			<div class="col-sm-4">
				<label class="control-label" for="input-timezone"><?php echo $entry_timezone; ?></label>
				<div class="col-sm-12">
					<input type="number" min="-12" max="14" name="timezone" value="<?php echo $timezone; ?>" class="form-control" />
				</div>
			</div>
			<div class="col-sm-4">
				<label class="control-label" for="input-time-to"><?php echo $entry_time_to; ?></label>
				<div class="col-sm-12">
					<div class="input-group time">
						<input type="time" name="time_to" value="<?php echo $time_to; ?>" class="form-control" />
					</div>
				</div>
			</div>	
			<div class="col-sm-4">
				<label class="control-label" for="input-time-from"><?php echo $entry_time_from; ?></label>
				<div class="col-sm-12">
					<div class="input-group time">
						<input type="time" name="time_from" value="<?php echo $time_from; ?>" class="form-control" />
					</div>
				</div>
			</div>
		  </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="status" id="input-status" class="form-control">
                <?php if ($status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php }else{ ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
			<div class="col-sm-4">
				<label class="control-label" for="input-view_user"><?php echo $text_view_user; ?></label>
				<label id="labelView" class="control-label" for="input-view_user"><?php echo $view_user; ?></label>
				<a id="removeViewUser" class="btn btn-danger"><?php echo $text_reset; ?></a>
				<div class="col-sm-8">
					<input id="inputView" type="hidden" name="view_user" value="<?php echo $view_user; ?>" class="form-control" />
				</div>
			</div>
        </form>
      </div>
    </div>
  </div>
  <link href="view/javascript/codemirror/lib/codemirror.css" rel="stylesheet" />
  <link href="view/javascript/codemirror/theme/monokai.css" rel="stylesheet" />
  <script type="text/javascript" src="view/javascript/codemirror/lib/codemirror.js"></script> 
  <script type="text/javascript" src="view/javascript/codemirror/lib/xml.js"></script> 
  <script type="text/javascript" src="view/javascript/codemirror/lib/formatting.js"></script> 
    
  <script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
  <link href="view/javascript/summernote/summernote.css" rel="stylesheet" />
  <script type="text/javascript" src="view/javascript/summernote/summernote-image-attributes.js"></script>
  <script type="text/javascript" src="view/javascript/summernote/opencart.js"></script>
  <script type="text/javascript" src="view/javascript/jquery.form.min.js"></script>
  <script type="text/javascript"><!--
	$('#language a:first').tab('show');
  //--></script>
<script type="text/javascript"><!--
	function closeModal(){
		if ($('#input-closeModal').val()==0){
			$('#inputCloseModalSecond').css("display","block");
		}else{
			$('#inputCloseModalSecond').css("display","none");
		}
	}  
	function ifViewModal(){
		if ($('#input-ifViewModal').val()==4){
			$('#formTime').css("display","block");
		} else{
			$('#formTime').css("display","none");
		}		
	}
	function ifViewModal(){
		if ($('#input-ifViewModal').val()==4){
			$('#formTime').css("display","block");
		} else{
			$('#formTime').css("display","none");
		}		
	}
	function styleBg(){
		if ($('#input-styleBackground').val()==0){
			$('#inputColor').css("display","block");
			$('#inputBackground').css("display","none");
		} else{
			$('#inputColor').css("display","none");
			$('#inputBackground').css("display","block");
		}
	}
	function setCookie(){
		if ($('#input-cookie').val()==1){
			$('#block_countView').css("display","block");
		} else{
			$('#block_countView').css("display","none");
		}
	}
	closeModal();
	ifViewModal();
	styleBg();
	setCookie();
	
	$('#input-closeModal').on('change',function(){
		closeModal();
	});
	
	$('#input-ifViewModal').on('change',function(){
		ifViewModal();	
	});

	$('#input-styleBackground').on('change',function(){
		styleBg();
	});
	
	$('#input-cookie').on('change',function(){
		setCookie();
	});
	
	$('#removeViewUser').on('click',function(event){
		event.preventDefault();
		$('#inputView')[0].value=0;
		$('#labelView')[0].textContent='0';
	});
//--></script>
<script>
	setTimeout(function() { 
		$('#message_success').css("display", "none");
		$('#message_success')[0].textContent='';
	}, 10000);
</script>
</div>
<?php echo $footer; ?>
