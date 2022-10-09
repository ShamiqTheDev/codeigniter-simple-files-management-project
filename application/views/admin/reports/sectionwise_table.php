<table class="table table-striped table-bordered table-hover pdf_table" id="sample_1">
	<thead>
		<tr>
			<th>Section</th>
			<th>File Type</th>
			<th>Start Date</th>
			<th>End Date</th>
			<th>Total Files</th>
			<th width="15%">Total Scanned Files</th>
			<th width="15%">To be Scanned</th>
			
		</tr>
	</thead>
	<tbody>
		<?php 
			$totalFileCount_sum=0;
			$pending_count_sum=0;
			$total_file_pending_sum=0;
		?>
		<?php
			if($sectionwise_reporting)
			{
				foreach($sectionwise_reporting as $key => $get_sectionwise_reporting)
				{
				?>
				<tr>
					<td><?php echo $session_section[$get_sectionwise_reporting['sectionId']]['sectionName']; ?></td>
					<td><?php echo $file_types[$get_sectionwise_reporting['fileTypeId']]['fileType']; ?></td>
					<td><?php echo ($get_sectionwise_reporting['startDate'] != '0000-00-00') ? date('d-m-Y',strtotime($get_sectionwise_reporting['startDate'])) : '-'; ?></td>
					<td><?php echo ($get_sectionwise_reporting['endDate'] != '0000-00-00') ? date('d-m-Y',strtotime($get_sectionwise_reporting['endDate'])) : '-'; ?></td>
					<td class="text-right"><?php echo $get_sectionwise_reporting['totalFileCount']; $totalFileCount_sum = $totalFileCount_sum + $get_sectionwise_reporting['totalFileCount']; ?></td>
					<td class="text-right"><?php echo $get_sectionwise_reporting['fileScan']; $pending_count_sum = $pending_count_sum + $get_sectionwise_reporting['fileScan']; ?></td>
					<td class="text-right"><?php echo $total_file_pending = ($get_sectionwise_reporting['totalFileCount']-$get_sectionwise_reporting['fileScan']); $total_file_pending_sum = $total_file_pending_sum +  $total_file_pending  ?></td>
					
				</tr>
				<?php }
			}?>
			<tr>
				<td colspan='4' class="text-right" style="font-weight:600;">Total</td>
				<td class="text-right" style="font-weight:600;"><?php echo $totalFileCount_sum; ?></td>
				<td class="text-right" style="font-weight:600;"><?php echo $pending_count_sum; ?></td>
				<td class="text-right" style="font-weight:600;"><?php echo $total_file_pending_sum; ?></td>
			</tr>
	</tbody>
</table>