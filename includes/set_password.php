<?php 

require_once '../config/db.php';


$select  = "SELECT * FROM `students` where PASSWORD=''";
$select_result = mysqli_query($connect, $select);
while ($student = mysqli_fetch_assoc(($select_result))) {
	?>
	<script>
		var jntu = `<?php echo $student['JNTUNO'];?>`;
		console.log(jntu);
		fetch('./create_passwords.php',{
		 method:"POST",
		 headers:{
			"Content-Type":"application/json"
		 },
			body:JSON.stringify({"jntu":jntu})
		})
		.then(res => res.text())
		.then(data => console.log(data))
	</script>
		<?php
}
?>