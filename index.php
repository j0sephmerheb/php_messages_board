<?php 
	include 'connection.php';

	if(isset($_GET['logout'])){
		unset($_SESSION['user']);
	}

	$tip='';

	// Delete
	if(isset($_GET['del']) && isset($_GET['id']) ){
		$id = $_GET['id'];
		if($_GET['del'] == '1'){
			$sql = "DELETE FROM messages WHERE id = {$id}";
			$del = $db->query($sql);
			if($del){
				$tip='Comment Deleted';
			}
		}
		header('location:./index.php');
	}

	if(!empty($_POST)){
		// add data
		if(!isset($_SESSION['user'])){
			$tip='Please login first';
		}else{
			$content = trim($_POST['content']);
			$uid = $_SESSION['user']['id'];
			$add_time = time();
			$sql = "INSERT INTO messages (content, uid, add_time) VALUES ('{$content}',  {$uid}, {$add_time})";
			$add = $db->query($sql);
		}
	}

	// select data
	$sql = "SELECT mg.id, mg.content, mg.add_time, us.username FROM messages AS mg LEFT JOIN users AS us ON us.id = mg.uid ORDER BY mg.id DESC";
	$mysqliRes = $db->query($sql);
	if($mysqliRes === false){
		echo 'something wrong with your sql';
		exit;
	}
	
	$rows = [];
	while ($row = $mysqliRes->fetch_array(MYSQLI_ASSOC)){
		$rows[] = $row;
	}

	// Number of comments
	$msgNum = count($rows);
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
				echo $tip;

				if(isset($_SESSION['user'])){
					echo '<div class="mb-1"><small>';
					echo 'Welcome, <strong> ' . $_SESSION['user']['username'] . ' </strong>';
					echo '<a href="./index.php?logout=1">Logout</a>';
					echo '</small></div>';
				}
				else{
					echo '<div class="mb-1"><small>Please <a href="./account.php">Login</a> lo leave a comment</small></div>';
				}		
			?>

			<form action="./index.php" method="post">
				<textarea class="form-control mb-2" name='content'></textarea>
				<input type="submit" value="Publish" class="btn btn-primary px-4" onclick="return checkLogin();">
			</form>

			<!-- Message -->
			<div class="messages mt-4 pt-4">
				<div class="mb-2">
					<small>Number of comments: <strong><?php echo $msgNum; ?></strong></small>
				</div>
				<?php 
					foreach($rows as $row):
				?>

				<div class="content">
					<div class="username"><?php echo $row['username'];?></div>
					
					<div class="time">
						<?php echo date('Y-m-d H:i:s', $row[add_time]); ?>						
					</div>

					<div class="message">
						<?php echo $row['content']; ?>
					</div>

					<?php 
						if($_SESSION['user']['identity'] == 1){
							echo '<div class="btns">';
							echo '<a onclick="return deleteMsg();" href="./index.php?del=1&id='.$row['id'].'" class="del">Delete</a> ' ;
							echo '<a href="./edit.php?id='.$row['id'].'" class="edit">Edit</a>' ;
							echo '</div>';
						}
					?>
				</div>

				<?php endforeach; ?>					
			</div>
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

		function deleteMsg(){
			return confirm('Are you sure?');
		}
	</script>
</body>
</html>