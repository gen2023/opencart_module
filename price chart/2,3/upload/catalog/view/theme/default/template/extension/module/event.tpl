<?php echo $header; ?>
<div class="container" id="introPanel">
	<div class="row"><?php echo $column_left; ?>
    	<?php if ($column_left && $column_right) { ?>
    		<?php $class = 'col-sm-6'; ?>
    	<?php } elseif ($column_left || $column_right) { ?>
    		<?php $class = 'col-sm-9'; ?>
    	<?php } else { ?>
    		<?php $class = 'col-sm-12'; ?>
    	<?php }?>
    	<div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
    		<h2><?php echo $heading_title; ?></h2>
      		<div id="calendar"></div>
      		<?php  
			if (!$events) { ?>
      			<p><?php echo $text_empty; ?></p>
      		<?php }	?>
      		<?php echo $content_bottom; ?>
        </div>
   	 	<?php echo $column_right; ?>
    </div>
</div>
<script>

  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: '<?php echo $rightColumnMenu; ?>'
      },
	  views: {
		  <?php foreach ($textButton as  $key=> $value) { ?>
			<?php echo $key; ?> : { buttonText: '<?php echo $value; ?>'},
		<?php } ?>
      },
	  initialView: '<?php echo $initialView; ?>',
      navLinks: true, 
	  firstDay: <?php echo $firstDay; ?>,
	  locale: '<?php echo $eventLocale; ?>',
	  dayMaxEvents: <?php echo $dayMaxEvents; ?>,
	  eventOrder: 'sort_order',
      events: [
		<?php foreach ($events as $event) {?>
				<?php $new_from=explode('-',date("Y-m-d", strtotime($event['date_from'])));?>
				<?php $new_to=explode('-',date("Y-m-d", strtotime($event['date_to']))); ?>
			{
			title: '<?php print_r( $event["mindescription"]); ?>',
			color: '<?php echo $event["color"]; ?>',
			url: '<?php echo $event["href"]; ?>',
			start: '<?php echo $new_to[0] . "-" . $new_to[1] . "-".$new_to[2] . "T" . $event["time_to"]; ?>',
			end: '<?php echo $new_from[0] . "-" . $new_from[1] ."-" . $new_from[2] . "T" . $event["time_from"]; ?>',
			sort_order: '<?php echo $event["sort_order"]; ?>'

			},
		<?php } ?>{}] 
    });

    calendar.render();
  });

</script>
	
<?php echo $footer; ?>