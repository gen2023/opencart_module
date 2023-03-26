<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-theme-config" data-toggle="tooltip"
				title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i>
				</button>
				<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>"
				class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
				<div class="panel-body">
					<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data"
						id="form-gallery-setting" class="form-horizontal">

						<div class="form-group">
							<label class="col-sm-4 control-label"><?php echo $entry_gallery_url; ?></label>
							<div class="col-sm-8">
								<div class="row">
									<div class="col-sm-4">
										<input name="gallery_url" type="text" id="input-gallery-url" class="form-control" value="<?php echo $gallery_url; ?>" />
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label"><?php echo $entry_gallerylist_url; ?></label>
							<div class="col-sm-8">
								<div class="row">
									<div class="col-sm-4">
										<input name="gallerylist_url" type="text" id="input-gallerylist-url" class="form-control" value="<?php echo $gallerylist_url; ?>" />
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="input-gallery-share"><?php echo $entry_share; ?></label>
							<div class="col-sm-4">
								<select name="gallery_share" id="input-gallery-share" class="form-control">
									<?php if ($gallery_share) { ?>
									<option value="1" selected="selected"><?php echo $text_yes; ?></option>
									<option value="0"><?php echo $text_no; ?></option>
									<?php } else { ?>
									<option value="1"><?php echo $text_yes; ?></option>
									<option value="0" selected="selected"><?php echo $text_no; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<?php echo $footer; ?>