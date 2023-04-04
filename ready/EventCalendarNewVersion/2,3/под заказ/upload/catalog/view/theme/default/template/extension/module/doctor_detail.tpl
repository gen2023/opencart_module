<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <div class="row">
        <?php if ($column_left && $column_right) { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } elseif ($column_left || $column_right) { ?>
        <?php $class = 'col-sm-9'; ?>
        <?php } else { ?>
        <?php $class = 'col-sm-12'; ?>
        <?php } ?>
        <div class="<?php echo $class; ?>">
			<div class="row">						
				<?php if($doctor) { ?>							
					<div class="doctor">									
						<?php foreach ($doctor as $doctor) { ?>								
							<div class="doctorInfo">												  
								<div class="image"><img src="<?php echo $doctor['image']; ?>" alt="<?php echo $doctor['title']; ?>"/></div>
								<div class="doctorInfoDescription">
									<h4 class="doctorName"><?php echo $doctor['title']; ?></h4>
									<div class="doctorPost"><b><?php echo $doctor['post']; ?></b></div>
									<div class="doctorDescription"><?php echo $doctor['description']; ?></div>
								</div>
							</div>
						<?php } ?>								
					</div>
				<?php } ?>
			</div>
			<div class="row">
				<?php foreach ($events as $event) { ?>
					<div class="eventList col-xs-12">
						<div class="eventItem">
							<?php if($event['thumb']) { ?>
								<div class="image"><a href="<?php echo $event['href']; ?>">
								<img style="margin-right:20px;" align="left" src="<?php echo $event['thumb']; ?>" alt="<?php echo $event['title']; ?>" title="<?php echo $event['title']; ?>" class="img-responsive" /></a></div>
							<?php }?>
							<div>
								<div class="caption" style="margin-left:25px">
									<i>
										<?php if ($event['date_to']===$event['date_from']) {?>
											<?php echo $event['date_to']; ?>
										<?php } else {?>
											<?php echo $event['date_to']; ?> - <?php echo $event['date_from']; ?>
										<?php }?>
									</i><br>
									<a href="<?php echo $event['href']; ?>">
									  <span style="font-size: 12px;"><?php echo $event['title1']; ?></span><br />
									  <span style="font-size: 15px;"><?php echo $event['title']; ?></span>
									</a>
									<p><?php echo $event['description']; ?>...</p>
									<h2><?php echo $event['price']; ?></h2>
								</div>
								<div class="buttonEventDetail">
									<div class="pull-right"><a href="<?php echo $event['href']; ?>" class="btn btn-primary"><?php echo $text_more; ?></a></div>
								</div>							
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
      </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>
