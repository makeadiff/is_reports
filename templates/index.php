<h1><?php echo $page_title ?></h1>

<table class="table table-striped">
	<tr><th>Name</th><th>Teacher Count</th><th colspan="2" width="200">Completion</th></tr>
	<?php foreach ($data as $row) { ?>
	<tr>
		<td><a href="index.php?city_id=<?php echo $row['city_id'] ?>"><?php echo $row['name'] ?></a></td>
		<td><?php echo $row['teacher_count'] ?></td>
		<td width="30"><?php echo $row['completion_percentage'] ?>%</td>
		<td class="progress">
<?php if($row['completion_percentage']) { ?><div class="complete" style="width:<?php echo $row['completion_percentage'] ?>%; background-color: green;">&nbsp;</div><?php } ?>
<?php if(95-$row['completion_percentage']) { ?><div class="incomplete" style="width:<?php echo 95-$row['completion_percentage'] ?>%; background-color: red;">&nbsp;</div><?php } ?>
</div></td>
	</tr>
	<?php } ?>
</table>
