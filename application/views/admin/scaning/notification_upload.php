<!DOCTYPE html>
<html lang="en">
	<head>
		<title>CSS Template</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<style>
			* {
			box-sizing: border-box;
			}
			
			body {
			font-family: Arial, Helvetica, sans-serif;
			}
			
			/* Style the header */
			header {
			background-color: #666;
			padding: 30px;
			text-align: center;
			font-size: 35px;
			color: white;
			}
			
			/* Create two columns/boxes that floats next to each other */
			nav {
			float: left;
			width: 30%;
			height: 300px; /* only for demonstration, should be removed */
			background: #ccc;
			padding: 20px;
			}
			
			/* Style the list inside the menu */
			nav ul {
			list-style-type: none;
			padding: 0;
			}
			
			article {
			float: left;
			padding: 20px;
			width: 70%;
			background-color: #f1f1f1;
			height: 300px; /* only for demonstration, should be removed */
			}
			
			/* Clear floats after the columns */
			section:after {
			content: "";
			display: table;
			clear: both;
			}
			
			/* Style the footer */
			footer {
			background-color: #777;
			padding: 10px;
			text-align: center;
			color: white;
			}
			
			/* Responsive layout - makes the two columns/boxes stack on top of each other instead of next to each other, on small screens */
			@media (max-width: 600px) {
			nav, article {
			width: 100%;
			height: auto;
			}
			}
		</style>
	</head>
	<body>
		
		<h2>CSS Layout Float</h2>
		<p></p>
		<p></p>
		
		<header>
			<h2>Cities</h2>
		</header>
		
		<section>
			<nav>
				<ul>
					<li><a href="#">London</a></li>
					<li><a href="#">Paris</a></li>
					<li><a href="#">Tokyo</a></li>
				</ul>
			</nav>
			
			<article>
			
				<form action="<?php echo base_url(); ?>ScanningFilesServices/upload_file_details" class="form-horizontal" role="custom_field_form" id="custom_field_form" enctype="multipart/form-data" method="post" accept-charset="utf-8" novalidate="novalidate">
					<table>
						<tr>
							<td>Section ID </td>
							<td><input type="text" name="section_id" value="" id="download_no" class="form-control" placeholder="Insert Notification No"></td>
						<tr>
					
						<tr>
							<td>File Type ID </td>
							<td><input type="text" name="file_type_id" value="" id="download_no" class="form-control" placeholder="Insert Notification No"></td>
						<tr>
						
						<tr>
							<td>General Category ID </td>
							<td><input type="text" name="general_category_id" value="" id="download_no" class="form-control" placeholder="Insert Notification No"></td>
						<tr>
								
						<tr>
							<td>Subject </td>
							<td><input type="text" name="subject" value="" id="post_title" class="form-control" placeholder="Insert Subject"></td>
						</tr>
						<tr>
							<td>Notification File </td>
							<td><input type="file" name="file[document]" id="document"></td>
						</tr>
						
						
						<tr>
							<td>Note Sheet </td>
							<td><input type="file" name="file[note_sheet]" id="note_sheet"></td>
						</tr>
					</table>
					
					</div>
					<div class="panel-body">
						<div class="col-md-2 pull-right">
							<button id="submit_btn" class="btn btn-yellow btn-block" type="submit">
								Save <i class="fa fa-arrow-circle-right"></i>
							</button>
						</div>
					</div>
					
				</form>
			</article>
		</section>
		
		<footer>
			<p>Footer</p>
		</footer>
		
	</body>
</html>
