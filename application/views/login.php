<!DOCTYPE html>
<html lang="es">
<head>
<meta
	http-equiv="content-type"
	content="text/html; charset=utf-8"
/>
<link
	rel="shortcut icon"
	type="image/ico"
	href="<?php echo base_url('/images/favicon.ico');?>"
/>
<title>Setepros Login</title>
<link
	href="<?php echo base_url('css/styles.css');?>"
	type="text/css"
	media="screen"
	rel="stylesheet"
/>
</head>
<body id="login">
	<div id="top_margin"></div>
	<div id="wrapper">
		<div id="content">
			<div id="header">
				<h1>
					<a href=""><img
						src="<?php echo base_url('/images/logo_transparent.png');?>"
						alt="Setepros"
					> </a>
				</h1>
			</div>
			<div id="login_title">
				<div id="dark_title">
					<h2>Login</h2>
				</div>
				<div id="dark_title_shadow_left"></div>
				<div id="dark_title_shadow_right"></div>
			</div>

			<form
				name="login"
				method="post"
				action=""
			>
				<fieldset class="form">
					<?php if ($feedback){?>
					<p class="error">
						<img
							src="<?php echo base_url('/images/error.png');?>"
							height="16px"
							width="16px"
							style="margin-right: 10px;"
						>
						<?php echo $feedback;?>

					</p>
					<?php }?>


					<p>
						<label for="user_name">Username:</label> <input
							name="username"
							id="user_name"
							type="text"
							value=""
						/>
					</p>
					<p>
						<label for="user_password">Password:</label> <input
							name="password"
							id="user_password"
							type="password"
						/>
					</p>
					<div id="button_container">
						<button
							type="submit"
							name="submit"
							value="login"
						>
							<img
								src="<?php echo base_url('/images/login_icon.png');?>"
								alt="acceder"
								style="margin-right: 10px;"
							/>Login
						</button>
					</div>
				</fieldset>
				</form>
		
		</div>
	</div>
</body>
</html>