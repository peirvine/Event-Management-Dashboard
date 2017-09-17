<?php
if ($show == 'list'){

?>
<div class="ccm-dashboard-header-buttons">
	<a class="btn btn-primary" href="/dashboard/event/add">Add A Event</a>
</div>
<table class="table table-striped">
<thead>
<tr>
	<th>Event Name</th>
	<th>Event Date</th>
	<th>Event Description</th>
	<th>Actions</th>
</tr>
</thead>
<tbody id='categorylist'>
<?php
while ($row = $r->fetchRow()) {
	$time = strtotime($row['event_date']);
	echo "<tr id='category". $row['event_id'] ."'>
		<td>". $row['event_name'] ."</td>
		<td>". date('M. j Y', $time) ."</td>
		<td>". $row['event_description'] ."</td>
		<td>
			<a href='/dashboard/event/edit/". $row['event_id'] ."' class='btn btn-default'>Edit</a>
			<a href='/dashboard/event/?delete=". $row['event_id'] ."' class='btn btn-danger' onClick='return confirm(\"Are you sure you wish to delete this event? This can not be undone.\");'>Delete</a>
		</td>
	</tr>";

}

?>
</tbody>
</table>

<script type="text/javascript">
 jQuery(function() {
	jQuery('#categorylist').sortable({
		axis: 'y',
		containment: 'parent',
		update: function(event, ui) {
			jQuery.post(
				'/dashboard/event/savecategoryorder',
				{'categoryorder':jQuery('#categorylist').sortable('toArray').join(',').replace(/category/g,'')}
			);
		}
	});
	jQuery('#categorylist').disableSelection();
});
</script>

<?php
/*			jQuery.post(
				'/dashboard/jrc_crud/savecategoryorder/',
				{'categoryorder':jQuery('#categorylist').sortable('toArray').join(',').replace(/category/g,'')}
			);*/
} elseif ($show == 'form'){




?>


<form action="" method="post" enctype="multipart/form-data">
<div class="form-group">
	<?php echo $form->label('event_name', 'Event Name:') ?>
	<?php echo $form->text('event_name',$data['event_name']) ?>
</div>
<div class="form-group">
	<?php echo $form->label('event_date', 'Event Date:') ?>
	<?php echo Loader::helper('form/date_time')->date('event_date', $data['event_date']); ?>
</div>
<div class="form-group">
	<?php echo $form->label('event_description', 'Event Description') ?>
	<?php echo $form->textarea('event_description',$data['event_description']) ?>
</div>


<input type="hidden" name="id" value="<?php echo $editID ?>" />
<input type="submit" class="btn btn-primary" value="Save Changes" name="edit" />
</form>





<?php


}