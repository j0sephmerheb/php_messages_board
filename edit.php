<?php 
	include 'connection.php';

	if(!$_SESSION['user']){
		header('location:./account.php');
	}

	$tip = '';

	if(!empty($_POST)){
		// Update data
		if(!isset($_SESSION['user'])){
			$tip='Please login first';
		}else{
			$content = trim($_POST['content']);
			$id = $_POST['id'];
			$sql = "UPDATE messages SET content = '{$content}' WHERE id = {$id}";
			$update = $db->query($sql);
			header('location:./index.php');
		}
	}else{
		$id = $_GET['id'];
		$sql = "SELECT * FROM messages WHERE id = {$id}";
		$mysqliRes = $db ->query($sql);
		$msgInfo = $mysqliRes->fetch_array(MYSQLI_ASSOC);
	}
?>
<!DOCTYPE html>
<!--[if IE 8]>        <html class='ie8'><![endif]-->
<!--[if IE 9]>        <html class='ie9'><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html><!--<![endif]-->
<head>
	<?php include('f-meta.php'); ?> 
	<link rel='stylesheet' href='css/bootstrap.min.css'>
	<link rel='stylesheet' href='css/styles-en.css'>
</head>
<body>    
	<div class='bg-dark py-4'>
		<div class='container bg-white p-4'>
			<h2 class="title text-primary">Message Board</h2>

			<?php 
				if(!isset($_SESSION['user'])){
					echo '<div class="mb-1"><small>Please <a href="./account.php">Login</a></small></div>';					
				}		
			?>

			<form action="./edit.php" method="post">
				<textarea class="form-control mb-2" name='content' rows='5'><?php echo $msgInfo['content']; ?></textarea>
				<input type="hidden" name="id" value="<?php echo $msgInfo['id']; ?>" >
				<input type="submit" value="update" class="btn btn-primary px-4" onclick="return checkLogin()" >				
			</form>

			</div>
		</div>
		
		<!-- Script -->
		<script>
			var login = <?php echo isset($_SESSION['user']) ? 1 : 0; ?>

			function checkLogin(){
				if(login == 0){
					alert('Please Login');
					return false;
				}
			}
		</script>
	</body>
</html>