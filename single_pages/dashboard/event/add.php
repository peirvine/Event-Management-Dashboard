<?php $form = Core::make('helper/form');
$filetool = Core::make('helper/concrete/file_manager');
$date = Loader::helper('date');
 ?>

<form action="" method="post" enctype="multipart/form-data">
<div class="form-group">
	<?php echo $form->label('event_name', 'Event Name:') ?>
	<?php echo $form->text('event_name',$eventName) ?>
</div>
<div class="form-group">
	<?php echo $form->label('event_date', 'Event Date:') ?>
	<?php echo Loader::helper('form/date_time')->date('event_date', $eventDate); ?>
</div>
<div class="form-group">
	<?php echo $form->label('event_description', 'Event Description') ?>
	<?php echo $form->textarea('event_description',$eventDescription) ?>
</div>



<input type="submit" class="btn btn-primary" value="Add" name="event_add" /><a href="/dashboard/event/" class='btn btn-danger' type="submit">Cancel</a>
</form>
