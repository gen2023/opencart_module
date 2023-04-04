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
						id="form-events-setting" class="form-horizontal">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
							<li><a href="#tab-calendar" data-toggle="tab"><?php echo $tab_calendar; ?></a></li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="tab-general">
								<div class="form-group">
									<label class="col-sm-4 control-label"><?php echo $entry_events_url; ?></label>
									<div class="col-sm-8">
										<div class="row">
											<div class="col-sm-4">
												<input name="events_url" type="text" id="input-events-url" class="form-control" value="<?php echo $events_url; ?>" />
											</div>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label"><?php echo $entry_eventslist_url; ?></label>
									<div class="col-sm-8">
										<div class="row">
											<div class="col-sm-4">
												<input name="eventslist_url" type="text" id="input-eventslist-url" class="form-control" value="<?php echo $eventslist_url; ?>" />
											</div>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label"><?php echo $entry_eventsDetail_url; ?></label>
									<div class="col-sm-8">
										<div class="row">
											<div class="col-sm-4">
												<input name="eventsDetail_url" type="text" id="input-eventsDetail-url" class="form-control" value="<?php echo $eventsDetail_url; ?>" />
											</div>
										</div>
									</div>
								</div>
								<div class="form-group">
								<label class="col-sm-4 control-label" for="input-event-share"><?php echo $entry_share; ?></label>
								<div class="col-sm-4">
									<select name="event_share" id="input-event-share" class="form-control">
										<?php if ($event_share) { ?>
										<option value="1" selected="selected"><?php echo $text_yes; ?></option>
										<option value="0"><?php echo $text_no; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $text_yes; ?></option>
										<option value="0" selected="selected"><?php echo $text_no; ?></option>
										<?php } ?>
									</select>
								</div>
								</div>
							</div>
							<div class="tab-pane" id="tab-calendar">
								<div class="form-group">
									<label class="col-sm-4 control-label" for="input-event-firstDay"><?php echo $entry_firstDay; ?></label>
									<div class="col-sm-4">
										<select name="event_firstDay" id="input-event-firstDay" class="form-control">
											<option value=""></option>
												<?php foreach ($days as $key => $day) { ?>												
												  <?php if ($key===(int)$event_firstDays['firstDay']) { ?>								  
													  <option value="<?php echo $key; ?>" selected="selected"><?php echo $day; ?></option>
												  <?php } else { ?>
													  <option value="<?php echo $key; ?>"><?php echo $day; ?></option>
												  <?php } ?>
												<?php } ?>										
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label"><span data-toggle="tooltip" title="<?php echo $help_dayMaxEvents; ?>"><?php echo $entry_dayMaxEvents; ?></span></label>
									<div class="col-sm-8">
										<div class="row">
											<div class="col-sm-4">
												<input name="dayMaxEvents" type="text" id="input-dayMaxEvents" class="form-control" value="<?php echo $dayMaxEvents; ?>" />
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<?php echo $footer; ?>