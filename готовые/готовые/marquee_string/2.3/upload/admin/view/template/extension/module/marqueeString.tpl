<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-featured" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach($breadcrumbs as $_key => $breadcrumb) { ?> 
        <li>
          <a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        </li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger">
      <i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">
          <i class="fa fa-pencil"></i> <?php echo $text_edit; ?>
        </h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-featured" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active">
              <a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a>
            </li>
            <li>
              <a href="#tab-editText" data-toggle="tab"><?php echo $tab_editText; ?></a>
            </li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-name">
                  <?php echo $entry_name; ?>
                </label>
                <div class="col-sm-10">
                  <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control"/>
                </div>
              </div>
              <div class="form-group">
			  <ul class="nav nav-tabs" id="language">
              <?php foreach ($languages as $language) { ?>
              <li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
              <?php } ?>
            </ul>
			<div class="tab-content">
			<?php foreach ($languages as $language) { ?>
			<div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
                <div class="form-group">
                <label class="col-sm-2 control-label" for="input-text">
                  <?php echo $text_text; ?>
                </label>
                <div class="col-sm-10">
				<textarea name="string[<?php echo $language['language_id']; ?>][string]" placeholder="<?php echo $entry_description; ?>" id="input-string<?php echo $language['language_id']; ?>" data-lang="<?php echo $lang; ?>" class="form-control summernote"><?php echo isset($string[$language['language_id']]['string']) ? $string[$language['language_id']]['string'] : ''; ?></textarea>
                 </div></div></div>
			{% endfor %}
              </div></div>
              <div class="form-group">
                <div class="col-sm-4 form-group-item">
                  <label class="control-label" for="input-delayBeforeStart">
                    <span data-toggle="tooltip" title="<?php echo $help_delayBeforeStart; ?>">
                      <?php echo $entry_delayBeforeStart; ?>
                    </span>
                  </label>
                  <div>
                    <input type="number" name="delayBeforeStart" value="<?php echo $delayBeforeStart; ?>" placeholder="<?php echo $entry_delayBeforeStart; ?>" id="input-delayBeforeStart" class="form-control"/>
                  </div>
                </div>
                <div class="col-sm-4 form-group-item">
                  <label class="control-label" for="input-direction">
                    <span data-toggle="tooltip" title="<?php echo $help_direction; ?>">
                      <?php echo $entry_direction; ?>
                    </span>
                  </label>
                  <div>
                    <select name="direction" id="input-direction" class="form-control">
                      <?php if ( !$direction) { ?>
                      <option value="0" selected="selected">
                        <?php echo $text_left; ?>
                      </option>
                      <option value="1"><?php echo $text_right; ?></option>
                      <option value="2"><?php echo $text_up; ?></option>
                      <option value="3"><?php echo $text_down; ?></option>
                      <?php } elseif ($direction == 1) { ?>
                      <option value="0"><?php echo $text_left; ?></option>
                      <option value="1" selected="selected">
                        <?php echo $text_right; ?>
                      </option>
                      <option value="2"><?php echo $text_up; ?></option>
                      <option value="3"><?php echo $text_down; ?></option>
                      <?php } elseif ($direction == 2) { ?>
                      <option value="0"><?php echo $text_left; ?></option>
                      <option value="1"><?php echo $text_right; ?></option>
                      <option value="2" selected="selected">
                        <?php echo $text_up; ?>
                      </option>
                      <option value="3"><?php echo $text_down; ?></option>
                      <?php } elseif ($direction == 3) { ?>
                      <option value="0"><?php echo $text_left; ?></option>
                      <option value="1"><?php echo $text_right; ?></option>
                      <option value="2"><?php echo $text_up; ?></option>
                      <option value="3" selected="selected">
                        <?php echo $text_down; ?>
                      </option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="col-sm-4 form-group-item">
                  <label class="control-label" for="input-duplicated">
                    <span data-toggle="tooltip" title="<?php echo $help_duplicated; ?>">
                      <?php echo $entry_duplicated; ?>
                    </span>
                  </label>
                  <div>
                    <select name="duplicated" id="input-duplicated" class="form-control" >
                      <?php if ($duplicated) { ?>
                      <option value="1" selected="selected">
                        <?php echo $text_enabled; ?>
                      </option>
                      <option value="0"><?php echo $text_disabled; ?></option>
                      <?php } else { ?>
                      <option value="1"><?php echo $text_enabled; ?></option>
                      <option value="0" selected="selected">
                        <?php echo $text_disabled; ?>
                      </option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-4 form-group-item">
                  <label class="control-label" for="input-duration">
                    <span data-toggle="tooltip" title="<?php echo $help_duration; ?>">
                      <?php echo $entry_duration; ?>
                    </span>
                  </label>
                  <div>
                    <input type="number" name="duration" value="<?php echo $duration; ?>" placeholder="<?php echo $entry_duration; ?>" id="input-duration" class="form-control" />
                  </div>
                </div>
                <div class="col-sm-4 form-group-item">
                  <label class="control-label" for="input-speed">
                    <span data-toggle="tooltip" title="<?php echo $help_speed; ?>">
                      <?php echo $entry_speed; ?>
                    </span>
                  </label>
                  <div>
                    <input type="number" name="speed" value="<?php echo $speed; ?>" placeholder="<?php echo $entry_speed; ?>" id="input-speed" class="form-control" />
                  </div>
                </div>
                <div class="col-sm-4 form-group-item">
                  <label class="control-label" for="input-gap">
                    <span data-toggle="tooltip" title="<?php echo $help_gap; ?>">
                      <?php echo $entry_gap; ?>
                    </span>
                  </label>
                  <div>
                    <input type="number" name="gap" value="<?php echo $gap; ?>" placeholder="<?php echo $entry_gap; ?>" id="input-gap" class="form-control" />
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-4 form-group-item">
                  <label class="control-label" for="input-pauseOnHover">
                    <span data-toggle="tooltip" title="<?php echo $help_pauseOnHover; ?>">
                      <?php echo $entry_pauseOnHover; ?>
                    </span>
                  </label>
                  <div>
                    <select name="pauseOnHover" id="input-pauseOnHover" class="form-control" >
                      <?php if ($pauseOnHover) { ?>
                      <option value="1" selected="selected">
                        <?php echo $text_enabled; ?>
                      </option>
                      <option value="0"><?php echo $text_disabled; ?></option>
                      <?php } else { ?>
                      <option value="1"><?php echo $text_enabled; ?></option>
                      <option value="0" selected="selected">
                        <?php echo $text_disabled; ?>
                      </option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="col-sm-4 form-group-item">
                  <label class="control-label" for="input-pauseOnCycle">
                    <span data-toggle="tooltip" title="<?php echo $help_pauseOnCycle; ?>">
                      <?php echo $entry_pauseOnCycle; ?>
                    </span>
                  </label>
                  <div>
                    <input type="number" name="pauseOnCycle" value="<?php echo $pauseOnCycle; ?>" placeholder="<?php echo $entry_pauseOnCycle; ?>" id="input-pauseOnCycle" class="form-control" />
                  </div>
                </div>
                <div class="col-sm-4 form-group-item">
                  <label class="control-label" for="input-startVisible">
                    <span data-toggle="tooltip" title="<?php echo $help_startVisible; ?>">
                      <?php echo $entry_startVisible; ?>
                    </span>
                  </label>
                  <div>
                    <select name="startVisible" id="input-startVisible" class="form-control" >
                      <?php if ($startVisible) { ?>
                      <option value="1" selected="selected">
                        <?php echo $text_enabled; ?>
                      </option>
                      <option value="0"><?php echo $text_disabled; ?></option>
                      <?php } else { ?>
                      <option value="1"><?php echo $text_enabled; ?></option>
                      <option value="0" selected="selected">
                        <?php echo $text_disabled; ?>
                      </option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-4 form-group-item">
                  <label class="control-label" for="input-width">
                    <?php echo $entry_width; ?>
                  </label>
                  <div>
                    <input type="number" name="width" value="<?php echo $width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-width" class="form-control" />
                  </div>
                </div>
                <div class="col-sm-4 form-group-item">
                  <label class="control-label" for="input-height">
                    <?php echo $entry_height; ?>
                  </label>
                  <div>
                    <input type="number" name="height" value="<?php echo $height; ?>" placeholder="<?php echo $entry_height; ?>" id="input-height" class="form-control" />
                  </div>
                </div>
                <div class="col-sm-4 form-group-item">
                  <label class="control-label" for="input-status">
                    <?php echo $entry_status; ?>
                  </label>
                  <div>
                    <select name="status" id="input-status" class="form-control" >
                      <?php if ($status) { ?>
                      <option value="1" selected="selected">
                        <?php echo $text_enabled; ?>
                      </option>
                      <option value="0"><?php echo $text_disabled; ?></option>
                      <?php } else { ?>
                      <option value="1"><?php echo $text_enabled; ?></option>
                      <option value="0" selected="selected">
                        <?php echo $text_disabled; ?>
                      </option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-4 form-group-item">
                    <label class="control-label" for="input-amount">
                      <span data-toggle="tooltip" title="<?php echo $help_amount; ?>">
                        <?php echo $entry_amount; ?>
                      </span>
                    </label>
                    <div>
                      <input type="number" name="amount" value="<?php echo $amount; ?>" placeholder="<?php echo $entry_amount; ?>" id="input-amount" class="form-control" />
                    </div>
                  </div>
                  <div class="col-sm-4 form-group-item">
                    <label class="control-label" for="input-textBg">
                      <span data-toggle="tooltip" title="<?php echo $help_textBg; ?>"><?php echo $entry_textBg; ?></span
                      >
                    </label>
                    <div>
                      <input type="text" name="textBg" value="<?php echo $textBg; ?>" placeholder="<?php echo $entry_textBg; ?>" id="input-textBg" class="form-control" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-editText">
              <div class="form-group">
                <div class="col-sm-4 form-group-item">
                  <label class="control-label" for="input-textColor">
                    <span data-toggle="tooltip" title="<?php echo $help_textColor; ?>">
                      <?php echo $entry_textColor; ?></span
                    >
                  </label>
                  <div>
                    <input type="text" name="textColor" value="<?php echo $textColor; ?>" placeholder="<?php echo $entry_textColor; ?>" id="input-textColor" class="form-control" />
                  </div>
                </div>
                <div class="col-sm-4 form-group-item">
                  <label class="control-label" for="input-fontSize">
                    <?php echo $entry_fontSize; ?>
                  </label>
                  <div>
                    <input type="number" name="fontSize" value="<?php echo $fontSize; ?>" placeholder="<?php echo $entry_fontSize; ?>" id="input-fontSize" class="form-control" />
                  </div>
                </div>
                <div class="col-sm-4 form-group-item">
                  <label class="control-label" for="input-css">
                    <?php echo $entry_css; ?>
                  </label>
                  <div>
                    <textarea name="css" placeholder="<?php echo $entry_css; ?>" id="input-css" class="form-control" >
                      <?php echo $css; ?>
                    </textarea>
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
