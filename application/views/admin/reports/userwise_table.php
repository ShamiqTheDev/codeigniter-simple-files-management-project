<table class="table table-striped table-bordered table-hover pdf_table" id="sample_1">
	<thead>
		<tr>
			
			<th>User</th>
			<th>Section</th>
			<th>File Type</th>
			<th  width="20%">Number of Files Scanned</th>
			
		</tr>
	</thead>
	<tbody>
		<?php 
			$totalFileCount_sum=0;
			$pending_count_sum=0;
			$total_file_pending_sum=0;
		?>
		<?php
			if($userwise_reporting)
			{
				foreach($userwise_reporting as $key => $get_userwise_reporting)
				{
				?>
				<tr>
					<td><?php echo $get_userwise_reporting['upro_first_name']." ".$get_userwise_reporting['upro_last_name']." (".$get_userwise_reporting['uacc_username'].")"; ?></td>
					<td><?php echo $session_section[$get_userwise_reporting['sectionId']]['sectionName']; ?></td>
					<td><?php echo $file_types[$get_userwise_reporting['fileTypeId']]['fileType']; ?></td>
					<td class="text-right"><?php echo $get_userwise_reporting['fileScan']; $pending_count_sum = $pending_count_sum + $get_userwise_reporting['fileScan']; ?></td>
					
				</tr>
				<?php }
			}?>
			<tr>
				<td colspan="3" class="text-right" style="font-weight:600;">Total</td>
				<td class="text-right" style="font-weight:600;"><?php echo $pending_count_sum; ?></td>
			</tr>
	</tbody>
</table>