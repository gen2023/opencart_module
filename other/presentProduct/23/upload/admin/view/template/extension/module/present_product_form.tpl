<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-events" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
					<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
				</div>
				<div class="panel-body">
					<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-events" class="form-horizontal">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
							<li><a href="#tab-data" data-toggle="tab"><?php echo $tab_data; ?></a></li>
							<li><a href="#tab-license" data-toggle="tab"><?php echo $tab_license; ?></a></li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="tab-general">
								<ul class="nav nav-tabs" id="language">
									<?php foreach ($languages as $language) { ?>
									<li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
									<?php } ?>
								</ul>
								<div class="tab-content">
									<?php foreach ($languages as $language) { ?>
									<div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
										<div class="form-group required">
											<label class="col-sm-2 control-label" for="input-title<?php echo $language['language_id']; ?>"><?php echo $entry_title; ?></label>
											<div class="col-sm-10">
												<input type="text" name="events_description[<?php echo $language['language_id']; ?>][title]" value="<?php echo isset($events_description[$language['language_id']]) ? $events_description[$language['language_id']]['title'] : ''; ?>" placeholder="<?php echo $entry_title; ?>" id="input-title<?php echo $language['language_id']; ?>" class="form-control" />
												<?php if (isset($error_title[$language['language_id']])) { ?>
												<div class="text-danger"><?php echo $error_title[$language['language_id']]; ?></div>
												<?php } ?>
											</div>
									</div>
										<div class="form-group required">
											<label class="col-sm-2 control-label" for="input-mindescription<?php echo $language['language_id']; ?>"><?php echo $entry_mindescription; ?></label>
											<div class="col-sm-10">
												<input type="text" name="events_description[<?php echo $language['language_id']; ?>][mindescription]" value="<?php echo isset($events_description[$language['language_id']]) ? $events_description[$language['language_id']]['mindescription'] : ''; ?>" placeholder="<?php echo $entry_mindescription; ?>" id="input-mindescription<?php echo $language['language_id']; ?>" class="form-control" />
												<?php if (isset($error_mindescription[$language['language_id']])) { ?>
												<div class="text-danger"><?php echo $error_title[$language['language_id']]; ?></div>
												<?php } ?>
											</div>
									</div>
										<div class="form-group required">
											<label class="col-sm-2 control-label" for="input-description<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>
											<div class="col-sm-10">
												<textarea name="events_description[<?php echo $language['language_id']; ?>][description]" placeholder="<?php echo $entry_description; ?>" id="input-description<?php echo $language['language_id']; ?>" class="form-control summernote"><?php echo isset($events_description[$language['language_id']]) ? $events_description[$language['language_id']]['description'] : ''; ?></textarea>
												<?php if (isset($error_description[$language['language_id']])) { ?>
												<div class="text-danger"><?php echo $error_description[$language['language_id']]; ?></div>
												<?php } ?>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label" for="input-meta-title<?php echo $language['language_id']; ?>"><?php echo $entry_meta_title; ?></label>
											<div class="col-sm-10">
												<input type="text" name="events_description[<?php echo $language['language_id']; ?>][meta_title]" value="<?php echo isset($events_description[$language['language_id']]) ? $events_description[$language['language_id']]['meta_title'] : ''; ?>" placeholder="<?php echo $entry_meta_title; ?>" id="input-meta-title<?php echo $language['language_id']; ?>" class="form-control" />
												<?php if (isset($error_meta_title[$language['language_id']])) { ?>
												<div class="text-danger"><?php echo $error_meta_title[$language['language_id']]; ?></div>
												<?php } ?>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label" for="input-meta-h1<?php echo $language['language_id']; ?>"><?php echo $entry_meta_h1; ?></label>
											<div class="col-sm-10">
												<input type="text" name="events_description[<?php echo $language['language_id']; ?>][meta_h1]" value="<?php echo isset($events_description[$language['language_id']]) ? $events_description[$language['language_id']]['meta_h1'] : ''; ?>" placeholder="<?php echo $entry_meta_h1; ?>" id="input-meta-h1<?php echo $language['language_id']; ?>" class="form-control" />
												<?php if (isset($error_meta_title[$language['language_id']])) { ?>
												<div class="text-danger"><?php echo $error_meta_title[$language['language_id']]; ?></div>
												<?php } ?>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label" for="input-meta-description<?php echo $language['language_id']; ?>"><?php echo $entry_meta_description; ?></label>
											<div class="col-sm-10">
												<textarea name="events_description[<?php echo $language['language_id']; ?>][meta_description]" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="input-meta-description<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($events_description[$language['language_id']]) ? $events_description[$language['language_id']]['meta_description'] : ''; ?></textarea>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label" for="input-meta-keyword<?php echo $language['language_id']; ?>"><?php echo $entry_meta_keyword; ?></label>
											<div class="col-sm-10">
												<textarea name="events_description[<?php echo $language['language_id']; ?>][meta_keyword]" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" id="input-meta-keyword<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($events_description[$language['language_id']]) ? $events_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea>
											</div>
										</div>
									</div>
									<?php } ?>
								</div>
							</div>
							<div class="tab-pane" id="tab-data">
								<div class="form-group">
									<label class="col-sm-2 control-label"><?php echo $entry_store; ?></label>
									<div class="col-sm-10">
										<div class="well well-sm" style="height: 150px; overflow: auto;">
											<div class="checkbox">
												<label>
													<?php if (in_array(0, $events_store)) { ?>
													<input type="checkbox" name="events_store[]" value="0" checked="checked" />
													<?php echo $text_default; ?>
													<?php } else { ?>
													<input type="checkbox" name="events_store[]" value="0" />
													<?php echo $text_default; ?>
													<?php } ?>
												</label>
											</div>
											<?php foreach ($stores as $store) { ?>
											<div class="checkbox">
												<label>
													<?php if (in_array($store['store_id'], $events_store)) { ?>
													<input type="checkbox" name="events_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
													<?php echo $store['name']; ?>
													<?php } else { ?>
													<input type="checkbox" name="events_store[]" value="<?php echo $store['store_id']; ?>" />
													<?php echo $store['name']; ?>
													<?php } ?>
												</label>
											</div>
											<?php } ?>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label"><?php echo $entry_image; ?></label>
									<div class="col-sm-10"><a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
									<input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-date-from"><?php echo $entry_date_from; ?></label>
								<div class="col-sm-6">
									<div class="input-group date">
										<input type="text" name="date_from" value="<?php echo $date_from; ?>" placeholder="<?php echo $entry_date_from; ?>" data-date-format="YYYY-MM-DD" id="input-date-available" class="form-control" />
										<span class="input-group-btn">
											<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
										</span></div>
									</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-date-to"><?php echo $entry_date_to; ?></label>
								<div class="col-sm-6">
									<div class="input-group date">
										<input type="text" name="date_to" value="<?php echo $date_to; ?>" placeholder="<?php echo $entry_date_to; ?>" data-date-format="YYYY-MM-DD" id="input-date-available" class="form-control" />
										<span class="input-group-btn">
											<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
										</span></div>
									</div>
							</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input-keyword"><span data-toggle="tooltip" title="<?php echo $help_keyword; ?>"><?php echo $entry_keyword; ?></span></label>
									<div class="col-sm-10">
										<input type="text" name="keyword" value="<?php echo $keyword; ?>" placeholder="<?php echo $entry_keyword; ?>" id="input-keyword" class="form-control" />
										<?php if ($error_keyword) { ?>
										<div class="text-danger"><?php echo $error_keyword; ?></div>
										<?php } ?>
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
							<div  class="tab-pane" id="tab-license">
							<div><?php echo $text_license?></div>
								<div class="form-group">								
                    				<label class="col-sm-2 control-label" for="input-license"><?php echo $entry_license; ?></label>									
                    				<div class="col-sm-10">
										
                        				<input type="text" name="license" value="<?php echo $value2;?>" placeholder="<?php echo $entry_license; ?>" id="input-license" class="form-control" />
										<?php if (isset($error_license[$language['language_id']])) { ?>
										<div class="text-danger"><?php echo $error_license[$language['language_id']]; ?></div>
										<?php } ?>
                    				</div>
                  				</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
  <script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
  <link href="view/javascript/summernote/summernote.css" rel="stylesheet" />
  <script type="text/javascript" src="view/javascript/summernote/opencart.js"></script>  
		<script type="text/javascript"><!--
		$('.date').datetimepicker({
			pickTime: false
		});
		$('#language a:first').tab('show');
	//--></script></div>
	<?php echo $footer; ?>