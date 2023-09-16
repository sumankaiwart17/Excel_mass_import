<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<style>
body{
 padding : 40px 80px!important;
}
table{
  width : 100% !important;
  margin : 0 auto !important;
  border: 1px solid #c0c0c0;
}
</style>

</head>
<body>

<?php
$con=mysqli_connect('localhost','root','','excel_import');
if(isset($_POST['submit'])){
	$file=$_FILES['doc']['tmp_name'];
	
	$ext=pathinfo($_FILES['doc']['name'],PATHINFO_EXTENSION);
	if($ext=='xlsx' || $ext=='xls'){
		require('PHPExcel/PHPExcel.php');
		require('PHPExcel/PHPExcel/IOFactory.php');
		
		
		$obj=PHPExcel_IOFactory::load($file);
		foreach($obj->getWorksheetIterator() as $sheet){
			$getHighestRow=$sheet->getHighestRow();
			for($i=0;$i<=$getHighestRow;$i++){
				$child_name=$sheet->getCellByColumnAndRow(0,$i)->getValue();
				$primary_classroom=$sheet->getCellByColumnAndRow(1,$i)->getValue();
				$Child_age=$sheet->getCellByColumnAndRow(2,$i)->getValue();
				$attends_days=$sheet->getCellByColumnAndRow(3,$i)->getValue();
				$day_sch=$sheet->getCellByColumnAndRow(4,$i)->getValue();
				if($child_name!=''){
					mysqli_query($con,"insert into child_info(child_name,primary_classroom,Child_age,attends_days,day_sch) values('$child_name','$primary_classroom','$Child_age','$attends_days','$day_sch')");
				}
			}
		}
	}else{
    		echo "<div class='alert alert-danger' role='alert' style='margin:15px 105px 0px 15px'>
    Invailid File Formate
</div>";
	}
}


if(isset($_POST['delete'])){
 mysqli_query($con,"TRUNCATE TABLE  child_info");
}
?>
<div class = "d-flex justify-content-between"> 
<form method="post" enctype="multipart/form-data" class = "d-flex justify-content-between">
	 <input type="file" name="doc" class="m-3 form-control">
    <input type="submit" class="btn btn-success m-3" name="submit" value = "Import">
</form>
<div class="m-3">
<form method="post">
  <input type="submit" class="btn btn-danger" name="delete" value = "Delete All" style = "margin-right:100px">
</form>
</div>


</div>


<table class="table table-hover m-4" style="white-space: nowrap; width: 90% !important;
    max-width: 90%; !important" >
  <thead>
    <tr>
      <th >#</th>
      <th>Child's Name</th>
      <th >Primary Classroom</th>
      <th >Child's Age</th>
	  <th>Attends the following days</th>
	  <th> </th>
    </tr>
  </thead>
  <tbody>
    
    <?php $query = "SELECT * FROM `child_info`;";
  

  $result =  $result = mysqli_query($con, $query);
  
    if (mysqli_num_rows($result) > 0) 
    {
        // OUTPUT DATA OF EACH ROW
        while($row = $result->fetch_assoc())
        {
           echo "<tr><td>" . $row['id']."</td>
                       <td>" . $row['child_name']."</td>
					   <td>" . $row['primary_classroom']."</td>
                       <td>" . $row['Child_age']."</td>
                       <td>" . $row['attends_days']."</td>
                       <td>" . $row['day_sch']."</td></tr>"; 
					   
        }
    } 
    else {
        
    }
  ?>
    </tr>
  </tbody>
</table>
<script>
  if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
</script> 
</body>
</html>