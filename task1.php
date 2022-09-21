<!DOCTYPE html>
<html>
<head>
<title>Task</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
<link rel="stylesheet" href="style.css">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>

function showBlock(){

	$('.removableClass').css('display','block');
	$('#loader-wrapper').css('display','none');
}
	
  $(window).on('load', function(){

  	const myTimeout = setTimeout(showBlock, 3000);	
   
  });


</script>

</head>
<body >
	<div id="loader-wrapper">
    <div id="loader"></div>
</div>
	


<?php 
// To Download File from link
ini_set('memory_limit', '1024M');
	
	if(!file_exists("download.csv")){
		$open = fopen("downloaded.csv", "r");
		$data = fgetcsv($open, 1000, ","); 
	}
	else {
		$url = 'https://media.githubusercontent.com/media/robert-koch-institut/SARS-CoV-2-Infektionen_in_Deutschland_Archiv/master/Archiv/2022-09-21_Deutschland_SarsCov2_Infektionen.csv';
		$source = file_get_contents($url);
		file_put_contents('downloaded.csv', $source);
		$open = fopen("downloaded.csv", "r");
		$data = fgetcsv($open, 1000, ","); 
	}



$conn = new mysqli("localhost","root","","pagination_task");

// Check connection


			
if ($conn -> connect_errno) {
  echo "Failed to connect to MySQL: " . $conn -> connect_error;
  exit();
}





  $lastWeekDate = date("Y-m-d", strtotime("last week monday"));
  // echo $lastWeekDate;
?> 



<?php
$query = "select *from table1 where Meldedatum >= '".$lastWeekDate."'";  
 $result = mysqli_query($conn, $query);  
$number_of_result = mysqli_num_rows($result); 

if($number_of_result == 0){


	while (!feof($open ) )//($data = fgetcsv($open, 1000, ",")) !== FALSE) 
		{
	$data = fgetcsv($open, 4096, ",");
	
	if (! empty($data)) {
            if(date("Y-m-d",strtotime($data[3])) >= $lastWeekDate && $data[3] < date('Y-m-d')){

            	// if( $count < $end && $count >= $start){
        $sql = "INSERT INTO table1( IdLandkreis, Altersgruppe, Geschlecht, Meldedatum, Refdatum, IstErkrankungsbeginn, NeuerFall, NeuerTodesfall, NeuGenesen, AnzahlFall, AnzahlTodesfall, AnzahlGenesen)
			VALUES ('$data[0]','$data[1]','$data[2]','$data[3]','$data[4]','$data[5]','$data[6]','$data[7]','$data[8]','$data[9]','$data[10]','$data[11]')";

			if ($conn->query($sql) === TRUE) {
			  
			} else {
			  echo "Error: " . $sql . "<br>" . $conn->error;
			}
			
			}
		}

	}

}



            ?>
       
 <?php 

//Pagination
$results_per_page = 1000;  
$query = "select *from table1";  
 $result = mysqli_query($conn, $query);  
$number_of_result = mysqli_num_rows($result); 
  $number_of_page = ceil ($number_of_result / $results_per_page);  

  if (!isset ($_GET['page']) ) {  
        $page = 1;  
    } else {  
        $page = $_GET['page'];  
    }  

   $page_first_result = ($page-1) * $results_per_page;  
   
   //Show Data

   $query = "SELECT *FROM table1 ORDER BY Meldedatum DESC LIMIT " . $page_first_result . ',' . $results_per_page;  
    $result = mysqli_query($conn, $query);  

?> 

<div class="container">
	<div class="removableClass">
<table class="table table-hover mt-2">
	<thead class="thead-dark">
	  <tr>
    <th scope="col">IdLandkreis</th>
    <th scope="col">Altersgruppe</th>
    <th scope="col">Geschlecht</th>
    <th scope="col">Meldedatum</th>
    <th scope="col">Refdatum</th>
    <th scope="col">IstErkrankungsbeginn</th>
    <th scope="col">NeuerFall</th>
    <th scope="col">NeuerTodesfall</th>
    <th scope="col">NeuGenesen</th>
    <th scope="col">AnzahlFall</th>
    <th scope="col">AnzahlTodesfall</th>
    <th scope="col">AnzahlGenesen</th>

  </tr>
</thead>
<?php

 while ($row = mysqli_fetch_array($result)) {  
 ?>	<tr>
 	<td> <?php echo $row['IdLandkreis']; ?> </td>
 	<td> <?php echo $row['Altersgruppe']; ?> </td>
 	<td> <?php echo $row['Geschlecht']; ?> </td>
 	<td> <?php echo $row['Meldedatum']; ?> </td>
 	<td> <?php echo $row['Refdatum']; ?> </td>
 	<td> <?php echo $row['IstErkrankungsbeginn']; ?> </td>
 	<td> <?php echo $row['NeuerFall']; ?> </td>
 	<td> <?php echo $row['NeuerTodesfall']; ?> </td>
 	<td> <?php echo $row['NeuGenesen']; ?> </td>
 	<td> <?php echo $row['AnzahlFall']; ?> </td>
 	<td> <?php echo $row['AnzahlTodesfall']; ?> </td>
 	<td> <?php echo $row['AnzahlGenesen']; ?> </td>
	</tr>
       
  <?php  } ?> 

</table>

<div align="center">
<?php
if($page != 1){
echo '<a href = "task1.php?page=' . ($page-1) . '" class="button"><span>Back </span> </a>';
echo '<a href = "task1.php?page=' . ($page+1) . '" class="button"><span> Next </span> </a>';

}
else {

echo '<a href = "task1.php?page=' . ($page+1) . '" class="button">Next </a>';

}

 ?>
</div>
</div>
</div>
</body>
</html>