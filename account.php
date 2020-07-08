<?php 
	include 'connection.php';
	
	if(isset($_GET['logout'])){
		unset($_SESSION['user']);
	}

	$tip='';

	if(!empty($_POST)){
		if($_POST['do'] == 'Register'){
			$username = trim($_POST['username']);
			$password = md5(trim($_POST['password']));
			$regTime = time();

			$sql = "SELECT * FROM users WHERE username = '{$username}'";
			$mysqliRes = $db ->query($sql);
			$userInfo = $mysqliRes->fetch_array(MYSQLI_ASSOC);

			if(!$userInfo){
				$sql2 = "INSERT INTO users (username, password, reg_time) VALUES ('{$username}', '{$password}', {$regTime})";
				$addUser = $db->query($sql2);
				if(addUser){
					$tip = "<div class='alert alert-success'>User created</div>";
				}
			}
			else{
				$tip = "<div class='alert alert-success'>User Already exist</div>";
			}
			
		}
		else{
			$username = trim($_POST['username']);
			$password = trim($_POST['password']);
			$sql = "SELECT * FROM users WHERE username = '{$username}'";
			$mysqliRes = $db ->query($sql);
			if($mysqliRes === false){
				echo "Something wrong with sql";
				exit;
			}
			$userInfo = $mysqliRes->fetch_array(MYSQLI_ASSOC);
			if(!$userInfo){
				$tip = "<div class='alert alert-danger'>Wrong username</div>";
			}
			else{
				if(md5($password) == $userInfo['password']){
					$_SESSION['user'] = $userInfo;
					header('location:./index.php');
				}else{
					$tip = "<div class='alert alert-danger'>Wrong password</div>";
				}
			}			
		}
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
			<h2 class='title text-primary'>Message Board</h2>
			
			<?php echo $tip; ?>

			<div class='row'>
				<div class='col-md-6 mb-4'>
					<h4 class='title'>Login</h4>
					<form action='account.php' method='post'>
						<input type='text' name='username' class='form-control mb-2' placeholder='Username'>
						<input type='password' name='password' class='form-control mb-2' placeholder='Password'>
						<input type='submit' name='do' value='Login' class='btn btn-primary px-4'>
					</form>
				</div>

				<div class='col-md-6 mb-4'>
					<h4 class='title'>Register</h4>		
					<form action='account.php' method='post'>
						<input type='text' name='username' class='form-control mb-2' placeholder='Username'>
						<input type='password' name='password' class='form-control mb-2' placeholder='Password'>
						<input type='submit' name='do' value='Register' class='btn btn-primary px-4'>
					</form>
				</div>
			</div>
		</div>
	</div>
</body>
</html>