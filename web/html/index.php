<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es">
	<?php
	if (!empty($_GET['chp'])) {
		if ($_GET['chp'] == 'usr'){
			exec('echo "usr" > /etc/beek/beek-music.lst');
		}
		else{
			exec('echo "default" > /etc/beek/beek-music.lst');
		}	
	}
	
	if (!empty($_GET['mc'])) {
		$command = '/etc/beek/beek-music '.$_GET['mc'];
		//echo "$command";
		shell_exec($command);
	}
	
	if (!empty($_GET['add'])) {
		$command = '/etc/beek/beek-music add';
		//echo $command;
		exec($command);
	}
	if (!empty($_GET['heat'])) {
		$command = '/etc/beek/beek-heat '.$_GET['heat'];
		//echo "$command";
		$heatres = shell_exec($command);
	}
	
	$command = 'sudo /etc/beek/get_connected.sh';
	$users = exec('cat /etc/beek/users.now |  sed \'1d\' | paste -sd "," -');
	$list = exec('/etc/beek/beek-music playlist');
	?>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<!-- Latest compiled and minified JavaScript -->
		<script src="jquery/jquery.js"></script>
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css"/>
		<!-- Optional theme -->
		<link rel="stylesheet" href="bootstrap/css/bootstrap-theme.min.css" />
		<!-- Latest compiled and minified JavaScript -->
		<script src="bootstrap/js/bootstrap.min.js"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<style>
			.panel-primary>.panel-heading{
				background:#00A8CE;
				border-color: #00A8CE;
			}
			.panel-danger>.panel-heading{
				background:#E02A3D;
				border-color: #E02A3D;
				color: #ffffff;
			}
			.btn-primary{
				background:#00A8CE;
				border-color: #00A8CE;
			}
			.btn-warning{
				background:#CBD500;
				border-color: #CBD500;
			}
			.btn-success{
				background:#008C39;
				border-color: #008C39;
			}
			.btn-danger{
				background:#E02A3D;
				border-color: #E02A3D;
			}
		</style>
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-md-12" style="text-align:center">
					<a href="/">
					<img src="images/beek.png" class="img-responsive" alt="Responsive image" style="width:50%;margin:auto;">
					</a>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-danger">
					  <div class="panel-heading">
						<h3 class="panel-title">Usuarios conectados</h3>
					  </div>
						<ul class="list-group">
					  	<?php
							$usrs = explode(",", $users);
							foreach($usrs as $usr) {
								echo '<li class="list-group-item">';
								echo $usr;
								echo '</li>';
							}
						?>
							 </ul>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-primary">
					  <div class="panel-heading">
						<h3 class="panel-title">Reproduciendo (Lista: <?php echo $list; ?>)</h3>
					  </div>
						<a href="/">
						  <div class="panel-body">
							<?php echo exec('/etc/beek/beek-music status'); ?>
						  </div>
						</a>
					</div>
				</div>
			</div>
			<?php
				if ($list != Personal){
					?>
				<form action="index.php" method="get" class="form-inline">
						<div class="form-group">
							<input type="hidden" id="add" name="add" value="add">
							<button style="width:99%" class="btn btn-success btn-lg" type="submit">Añadir a playlist personal</button>
						</div>
					</form>
			<?php
				}
			?>
			<div class="row" class="music-controll">
				<div class="col-md-12">
					<h4>Music control</h4>
					<form action="index.php" method="get" class="form-inline">
						<div class="form-group">
							<input type="hidden" id="mc" name="mc" value="pause">
							<button style="width:23%" class="btn btn-primary btn-lg" onclick="$('#mc').val('play');" type="submit"><span class="glyphicon glyphicon-play" aria-hidden="true"></span></button>
							<button style="width:23%" class="btn btn-primary btn-lg" onclick="$('#mc').val('pause');" type="submit"><span class="glyphicon glyphicon-pause" aria-hidden="true"></span></button>
							<button style="width:23%" class="btn btn-success btn-lg" onclick="$('#mc').val('prev');" type="submit"><span class="glyphicon glyphicon-step-backward" aria-hidden="true"></span></button>
							<button style="width:23%" class="btn btn-success btn-lg" onclick="$('#mc').val('next');" type="submit"><span class="glyphicon glyphicon-step-forward" aria-hidden="true"></span></button>
						</div>
					</form>
				</div>
			</div>
			<div class="row" class="vol-controll">
				<div class="col-md-12">
					<h4>Volume control</h4>
					<form action="index.php" method="get" class="form-inline">
						<div class="form-group">
							<input type="hidden" id="volc" name="mc" value="pause">
							<button style="width:48%" class="btn btn-warning btn-lg" onclick="$('#volc').val('voldown');" type="submit">
								<span class="glyphicon glyphicon-volume-down" aria-hidden="true"></span></button>
							<button style="width:48%" class="btn btn-warning btn-lg" onclick="$('#volc').val('volup');" type="submit">
								<span class="glyphicon glyphicon-volume-up" aria-hidden="true"></span></button>
						</div>
					</form>
				</div>
			</div>
			<div class="row" class="pl-controll">
				<div class="col-md-12">
					<h4>Playlist control</h4>
					<form action="index.php" method="get" class="form-inline">
						<div class="form-group">
							<input type="hidden" id="plst" name="chp" value="def">
							<button style="width:48%" class="btn btn-primary btn-lg" onclick="$('#plst').val('def');" type="submit">All</button>
							<button style="width:48%" class="btn btn-info btn-lg" onclick="$('#plst').val('usr');" type="submit">Personal</button>
						</div>
					</form>
				</div>
			</div>
			<div class="row" class="heat-controll">
				<div class="col-md-12">
					<h4>Heat Controll</h4>
					<div class="alert alert-info">Temperatura actual <?php echo exec('/etc/beek/beek-heat sense'); ?></div>
					<?php 
						if (!empty($heatres)){?>
							<div class="alert alert-warning">
								<?php  echo $heatres; ?>
							</div>
						<?php }
					?>
					<form action="index.php" method="get" class="form-inline">
						<div class="form-group">
							<input type="hidden" id="heat" name="heat" value="raise">
							<button style="width:48%" class="btn btn-danger btn-lg" onclick="$('#heat').val('raise');" type="submit">
								<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
							</button>
							<button style="width:48%" class="btn btn-warning btn-lg" onclick="$('#heat').val('lower');" type="submit">
								<span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</body>
	<script>
	setTimeout(function (){
		window.location.href='/';
	},10000);
	</script>
</html>

