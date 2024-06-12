<?php include('db_connect.php');?>
<?php 
// Load the database configuration file 
 
// Get status message 
if(!empty($_GET['status'])){ 
    switch($_GET['status']){ 
        case 'succ': 
            $statusType = 'alert-success'; 
            $statusMsg = 'Member data has been imported successfully.'; 
            break; 
        case 'err': 
            $statusType = 'alert-danger'; 
            $statusMsg = 'Something went wrong, please try again.'; 
            break; 
        case 'invalid_file': 
            $statusType = 'alert-danger'; 
            $statusMsg = 'Please upload a valid Excel file.'; 
            break; 
        default: 
            $statusType = ''; 
            $statusMsg = ''; 
    } 
} 
?>
	<style>
		input[type=checkbox]
	{
	/* Double-sized Checkboxes */
	-ms-transform: scale(1.3); /* IE */
	-moz-transform: scale(1.3); /* FF */
	-webkit-transform: scale(1.3); /* Safari and Chrome */
	-o-transform: scale(1.3); /* Opera */
	transform: scale(1.3);
	padding: 10px;
	cursor:pointer;
	}
	
	.bts{
		margin-top:50px;
	}
	
	</style>
	<div class="container-fluid">
		
		<div class="col-lg-12">
			<div class="row">
				<div class="col-md-12">
					
				</div>
			</div>
			<div class="row">
				<!-- FORM Panel -->
				
				<!-- Table Panel -->
				<div class="col-md-12">
					<div class="card">
						<div class="card-header">
							<b>List of Students </b>
							<span class="new float:right"><a class="bts btn btn-danger btn-block btn-sm col-sm-2 float-right" href="javascript:void(0)" id="new_student">
						<i class="fa fa-plus"></i> New 
					</a></span>
							<br><br>
					<form class="row g-2" style="" action="upload_file.php" method="post" enctype="multipart/form-data">
						<div class="col1-auto">
							<input type="file" class="form-control" name="file" id="fileInput" />
						</div>
						<div class="col-auto">
							<input type="submit" class="btn btn-danger mb-3" name="importSubmit" value="Import">
						</div>
						
					</form>
					
					
						</div>
						<div class="card-body">
							<table class="table table-condensed table-bordered table-hover">
								<thead>
									<tr>
										<th class="text-center">#</th>
										<th class="">LRN</th>
										<th class="">First Name</th>
										<th class="">Middle Name</th>
										<th class="">Last Name</th>
										<th class="">Year Level</th>
										<th class="">Section</th>
										<th class="">Information</th>
										<th class="text-center">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$i = 1;
									$student = $conn->query("SELECT * FROM student order by firstname asc ");
									while($row=$student->fetch_assoc()):
									?>
									<tr>
										<td class="text-center"><?php echo $i++ ?></td>
										<td>
											<p> <b><?php echo $row['id_no'] ?></b></p>
										</td>
										<td>
											<p> <b><?php echo ucwords($row['firstname']) ?></b></p>
										</td><td>
											<p> <b><?php echo ucwords($row['middlename']) ?></b></p>
										</td><td>
											<p> <b><?php echo ucwords($row['lastname']) ?></b></p>
										</td>
										<td>
											<p> <b><?php echo $row['course'] ?></b></p>
										</td>
										
										<td>
											<p> <b><?php echo $row['level'] ?></b></p>
										</td>
										<td class="">
											<p><small>Email: <i><b><?php echo $row['email'] ?></i></small></p>
											<p><small>Contact #: <i><b><?php echo $row['contact'] ?></i></small></p>
											<p><small>Address: <i><b><?php echo $row['address'] ?></i></small></p>
										</td>
										<td class="text-center">
											<button class="btn btn-sm btn-outline-dark edit_student" type="button" data-id="<?php echo $row['id'] ?>" >Edit</button>
											<button class="btn btn-sm btn-outline-danger delete_student" type="button" data-id="<?php echo $row['id'] ?>">Archive</button>
										</td>
									</tr>
									<?php endwhile; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<!-- Table Panel -->
			</div>
		</div>	

	</div>
	<style>
		
		td{
			vertical-align: middle !important;
		}
		td p{
			margin: unset
		}
		img{
			max-width:100px;
			max-height: :150px;
		}
	</style>
	<script>
		$(document).ready(function(){
			$('table').dataTable()
		})
		$('#new_student').click(function(){
			uni_modal("New Student ","manage_student.php","mid-large")
			
		})
		$('.edit_student').click(function(){
			uni_modal("Manage Student  Details","manage_student.php?id="+$(this).attr('data-id'),"mid-large")
			
		})
		$('.delete_student').click(function(){
			_conf("Are you sure to delete this Student ?","delete_student",[$(this).attr('data-id')])
		})
		function delete_student($id){
			start_load()
			$.ajax({
				url:'ajax.php?action=delete_student',
				method:'POST',
				data:{id:$id},
				success:function(resp){
					if(resp==1){
						alert_toast("Data successfully deleted",'success')
						setTimeout(function(){
							location.reload()
						},1500)

					}
				}
			})
		}
	</script>