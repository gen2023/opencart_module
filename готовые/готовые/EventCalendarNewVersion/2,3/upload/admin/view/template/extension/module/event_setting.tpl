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
							<li><a href="#tab-editNameRightMenu" data-toggle="tab"><?php echo $tab_editNameRightMenu; ?></a></li>
							<li><a href="#tab-license" data-toggle="tab"><?php echo $tab_license;?></a></li>
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
									<label class="col-sm-4 control-label" for="input-event-list_rightColumn"><?php echo $entry_list_rightMenu; ?></label>
									<div class="col-sm-4">
										<?php foreach ($listOptions as $key =>$listOption ) { ?>
										  <div>
											<?php if (in_array($key, $rightColumnMenu)) { ?>
											  <input type="checkbox" id="rightColumn" name="rightColumnMenu[]" value="<?php echo $key; ?>" checked="checked" data-value="<?php echo $listOption; ?>" /> <?php echo $listOption; ?>
											<?php } else { ?>
											  <input type="checkbox" id="rightColumn" name="rightColumnMenu[]" value="<?php echo $key; ?>" data-value="<?php echo $listOption; ?>" /> <?php echo $listOption; ?>
											<?php } ?>
										  </div>
										<?php } ?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" for="input-event-initialView"><?php echo $entry_initialView; ?></label>
									<div class="col-sm-4">
										<select name="event_initialView" id="input-event-initialView" class="form-control">
										<option value=""></option>
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
							<div class="tab-pane" id="tab-editNameRightMenu">

								<ul class="nav nav-tabs" id="language">
									<?php foreach ($languages as $language) { ?>
									<li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
									<?php } ?>
								</ul>
								<div class="tab-content">
								<?php foreach ($languages as $language) { ?>
								  <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
									<div class="form-group">
										<label class="col-sm-2 control-label" for="input-rightMenu<?php echo $language['language_id']; ?>"><?php echo $dayGridMonth; ?></label>
										<div class="col-sm-10">
											<input type="text" name="rightMenu[<?php echo $language['language_id']; ?>][dayGridMonth]" value="<?php echo isset($rightMenu[$language['language_id']]) ? $rightMenu[$language['language_id']]['dayGridMonth'] : ''; ?>" placeholder="<?php echo $dayGridMonth; ?>" id="input-rightMenu<?php echo $language['language_id']; ?>" class="form-control" />
										</div>	
										<label class="col-sm-2 control-label" for="input-rightMenu<?php echo $language['language_id']; ?>"><?php echo $timeGridWeek; ?></label>
										<div class="col-sm-10">
											<input type="text" name="rightMenu[<?php echo $language['language_id']; ?>][timeGridWeek]" value="<?php echo isset($rightMenu[$language['language_id']]) ? $rightMenu[$language['language_id']]['timeGridWeek'] : ''; ?>" placeholder="<?php echo $timeGridWeek; ?>" id="input-rightMenu<?php echo $language['language_id']; ?>" class="form-control" />
										</div>
										<label class="col-sm-2 control-label" for="input-rightMenu<?php echo $language['language_id']; ?>"><?php echo $timeGridDay; ?></label>
										<div class="col-sm-10">
											<input type="text" name="rightMenu[<?php echo $language['language_id']; ?>][timeGridDay]" value="<?php echo isset($rightMenu[$language['language_id']]) ? $rightMenu[$language['language_id']]['timeGridDay'] : ''; ?>" placeholder="<?php echo $timeGridDay; ?>" id="input-rightMenu<?php echo $language['language_id']; ?>" class="form-control" />
										</div>
										<label class="col-sm-2 control-label" for="input-rightMenu<?php echo $language['language_id']; ?>"><?php echo $listYear; ?></label>
										<div class="col-sm-10">
											<input type="text" name="rightMenu[<?php echo $language['language_id']; ?>][listYear]" value="<?php echo isset($rightMenu[$language['language_id']]) ? $rightMenu[$language['language_id']]['listYear'] : ''; ?>" placeholder="<?php echo $listYear; ?>" id="input-rightMenu<?php echo $language['language_id']; ?>" class="form-control" />
										</div>
										<label class="col-sm-2 control-label" for="input-rightMenu<?php echo $language['language_id']; ?>"><?php echo $listMonth; ?></label>
										<div class="col-sm-10">
											<input type="text" name="rightMenu[<?php echo $language['language_id']; ?>][listMonth]" value="<?php echo isset($rightMenu[$language['language_id']]) ? $rightMenu[$language['language_id']]['listMonth'] : ''; ?>" placeholder="<?php echo $listMonth; ?>" id="input-rightMenu<?php echo $language['language_id']; ?>" class="form-control" />
										</div>
										<label class="col-sm-2 control-label" for="input-rightMenu<?php echo $language['language_id']; ?>"><?php echo $listDay; ?></label>
										<div class="col-sm-10">
											<input type="text" name="rightMenu[<?php echo $language['language_id']; ?>][listDay]" value="<?php echo isset($rightMenu[$language['language_id']]) ? $rightMenu[$language['language_id']]['listDay'] : ''; ?>" placeholder="<?php echo $listDay; ?>" id="input-rightMenu<?php echo $language['language_id']; ?>" class="form-control" />
										</div>
										<label class="col-sm-2 control-label" for="input-rightMenu<?php echo $language['language_id']; ?>"><?php echo $listWeek; ?></label>
										<div class="col-sm-10">
											<input type="text" name="rightMenu[<?php echo $language['language_id']; ?>][listWeek]" value="<?php echo isset($rightMenu[$language['language_id']]) ? $rightMenu[$language['language_id']]['listWeek'] : ''; ?>" placeholder="<?php echo $listWeek; ?>" id="input-rightMenu<?php echo $language['language_id']; ?>" class="form-control" />
										</div>
									</div>
								  </div>
								<?php } ?>
								</div>
							</div>
							<div  class="tab-pane" id="tab-license">
							  <div><?php echo $text_license?></div>
							  <div class="form-group">								
								<label class="col-sm-2 control-label" for="input-value"><?php echo $entry_license; ?></label>
								<div class="col-sm-10">
								  <input type="text" name="value" value="<?php echo $value;?>" placeholder="<?php echo $entry_license; ?>" id="input-value" class="form-control" />
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
	</div>
<script type="text/javascript"><!--
		$('.date').datetimepicker({
			pickTime: false
		});
		$('#language a:first').tab('show');
//--></script>
<script type="text/javascript"><!--
	var checkboxSelected = document.querySelectorAll('input[id=rightColumn]');

	var listOptions = document.querySelector('select#input-event-initialView');
	var check='<?php echo $initialView; ?>';
	//console.log(check);
	buildList();
	function buildList() {
		//console.log('1');
	  listOptions.innerHTML = '';
	  [].forEach.call(checkboxSelected, function(e) {
		  //console.log(e);
		if (e.checked) {
			
		  var option = document.createElement('option');
		  option.setAttribute('value', e.getAttribute('value'));	
		  option.innerHTML = e.getAttribute('data-value');
		  if (e.defaultValue===check){option.setAttribute('selected', 'selected');}
		  listOptions.appendChild(option)
		}
	  })
	}

	[].forEach.call(checkboxSelected, function(e) {
			e.onchange = buildList;
	});
--></script>
	<?php echo $footer; ?>