<?php
require 'libs/datetime_expiration.php';
require_once 'header.php';

$send_ticket 	= NULL;
$ticket_email 	= NULL;
$promociones_servers = 0;
if(get_login()==TRUE && get_facebook_id()!=NULL)
{
	/* INFO #START  */

		$list_servers 		= array(
			'publico' 	=>'cs.sysgames.net:27015',
			'deathrun' 	=>'cs.sysgames.net:27016',
			'surf' 	=>'cs.sysgames.net:27017',
#			'kz' 	=>'cs.sysgames.net:27016',
		);
		$list_admins 		= array(
			15 => 'basico',
			20 => 'full'
		);
		$promociones_servers = 4;
		$list_promociones 	= array(
			35 => 'basico',
			45 => 'full',
		);

		if($_POST){
			try{
				$db = new PDO('mysql:dbname=emmade6_sys_pagos;host=localhost', 'emmade6_5y5g4m3s', '@)!%g!CX6IZOm-%');

				$generate_code 	= time().uniqid();
				$facebook 		= "https://www.facebook.com/{$login_id}";
				$admin_password = $_POST['admin']['password'];
				$admin_name 	= $_POST['admin']['name'];



				if($_POST['buy-type']=='normal'){
					$server_name 	= $_POST['admin']['server'];
					$server_address = $list_servers[$server_name];
					$admin_price 	= $_POST['admin']['type'];
					$admin_type 	= $list_admins[$admin_price];
				}
				if($_POST['buy-type']=='promocion'){
					$server_name 	= 'promocion';
					$server_address	= 'promocion';
					$admin_price 	= $_POST['admin']['promocion'];
					$admin_type 	= $list_promociones[$admin_price];
				}

				# Chequeo si ya existe, un administrador con ese nick en ese servidor
				$query_checkName = $db->prepare('SELECT id FROM sys_info WHERE admin_name = :admin_name AND server_address = :server_address');
				$query_checkName->bindParam(':admin_name', $admin_name, PDO::PARAM_STR);
				$query_checkName->bindParam(':server_address', $server_address, PDO::PARAM_STR);
				$query_checkName->execute();
				$check_name = $query_checkName->fetchAll();

				# Chequeo si la persona ya tiene administrador en ese servidor
				$query_checkAdmin = $db->prepare('SELECT id FROM sys_info WHERE facebook_id = :facebook_id AND server_address = :server_address');
				$query_checkAdmin->bindParam(':facebook_id', get_facebook_id(), PDO::PARAM_STR);
				$query_checkAdmin->bindParam(':server_address', $server_address, PDO::PARAM_STR);
				$query_checkAdmin->execute();
				$check_admin = $query_checkAdmin->fetchAll();

			# Si esta todo ok, que cree el admin
			if(!$check_admin[0] && !$check_name[0]){

				$get_fields = array(get_facebook_id(), $generate_code, $server_name, $server_address, $admin_name, $admin_type, $admin_price, $admin_password, get_date_today(), create_expiration());

				$add_admin 	= $db->prepare("INSERT INTO sys_info (facebook_id, ticket, server_name, server_address, admin_name, admin_type, admin_price, admin_password, created, expiration) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
				$add_admin->execute($get_fields);

				$query_email = $db->prepare('SELECT email FROM sys_accounts WHERE facebook_id = :facebook_id ');
				$query_email->bindParam(':facebook_id', get_facebook_id(), PDO::PARAM_STR);
				$query_email->execute();
				$query_get_email = $query_email->fetchAll();
				
				$sufijo 	= '5y5GAMES';
#				$concepto 	= 'admin-comun';
				$concepto 	= "Compra de Administrador";
				$hash 		= md5($generate_code.$concepto.$admin_price.$sufijo);
#				$hash 			= md5($generate_code.$concepto.$precio.'5y5GAMES');  #906482baa1912db8f0c899f734a9cc13
#				$cuenta_digital ="https://www.cuentadigital.com/api.php?id=538552&precio={$admin_price}&venc=30&codigo={$generate_code}&hacia=".$query_get_email[0]['email']."&concepto={$concepto}&hash={$hash}";


				$cuenta_digital ="https://www.cuentadigital.com/api.php?id=538552&precio={$admin_price}&codigo={$generate_code}&hacia=".$query_get_email[0]['email']."&concepto={$concepto}&hash={$hash}";
				echo "<iframe src='{$cuenta_digital}' style='display:none;'></iframe>";

				#print_x($get_fields);
				$send_ticket = TRUE;
				$ticket_email=$query_get_email[0]['email'];
			}

		
/*
			print_x($check_name);
			print_x($check_admin);
*/
			}
			catch(PDOExceptin $e){
				echo $e->getMessage();
			}			
		}
	/* INFO #END  */
}


#print_x($user_info);
#print_x($_SESSION);
#print_x($_POST);

/*
	OAuthException: An active access token must be used to query information about the current user.
*/
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="css/style.css" />
</head>
<body>
<div id="fb-root" class="fb_reset"></div>
<script type="text/javascript" language="javascript" src="http://connect.facebook.net/en_US/all.js"></script>
<script type="text/javascript">
	window.fbAsyncInit = function(){
		FB.init({ appId: '136672609824881', cookie: true, xfbml: true, oauth: true});
	};
	function facebookLogin(){
		FB.login(function(response){
			if(response.status == 'connected'){ location.reload(); }
		}, { scope: 'email,offline_access,publish_stream'}); 		
	}
</script>
<?php# if($_POST) print_x($_POST); ?>
<div id="page">
	<?php if($send_ticket==TRUE){ ?>
		<div align='center'>
			Has comprado con éxito el Administrador<br />
			Te hemos enviado la boleta a <?php echo $ticket_email; ?>
		</div>
	<?php }else{ ?>
	<?php if(get_login() == TRUE){ ?>
	<div style="border: 1px solid rgb(0, 0, 0); border-radius: 5px 5px 5px 5px; color: rgb(255, 255, 255); background: none repeat scroll 0% 0% rgb(17, 17, 17); width: 150px; box-shadow: 0px 3px 2px rgb(0, 0, 0); margin: 0px 55px 15px;">
		<div align="center" style="margin:8px 0;">
			<img src="http://graph.facebook.com/<?php echo get_facebook_id(); ?>/picture?width=95&height=95" width="95" height="95" />
		</div>
		<div align="center" style="background: none repeat scroll 0px 0px rgb(51, 51, 51); box-shadow: 0px 0px 3px rgb(204, 204, 204); margin: 15px 0px 8px; text-transform: uppercase;">
			<b><?php echo $user_name; ?></b>
		</div>
	</div>
	<?php } ?>
<div id="block-pagos">
	<?php if(get_login() == NULL){ ?>
	<div id="app-sysgames-login" onClick="javascript:facebookLogin();">
		<span>INICIAR SESION</span>
	</div>
	<?php } ?>
	<div id="errors">
		<?php
		if($_POST){			
			if($check_name && $check_name[0]){
				echo '<span id="fields_empty">*Ya existe otro Administrador con ese nombre</span>';
			}
			if($check_admin && $check_admin[0]){
				echo '<span id="fields_empty">*Ya tenes un Administrador en ese servidor</span>';
			}
		}
		?>		
	</div>
	<form action="" method="post">
	<div class="row">
		<div class="col-data align-left">
			<div>
				<span class="text-type-small">Nombre del Admininistrador</span>
			</div>	
			<div>
				<input id="admin-name" class="admin-field input-type-medium" type="text" name="admin[name]" />
			</div>
		</div>
		<div class="col-help align-left">
			<span class="text-type-small">
				<u>Nombre:</u>
			</span>
			<span class="text-type-small text-color-red">
				Ingrese el nombre de su admininistrador para poder identificarlo.
			</span>
			<br />
			<span class="text-type-small">Ej. shutow3n</span>
		</div>
		<div class="clear"></div>
	</div>
	<div class="row">
		<div class="col-data align-left">
			<div>
				<span class="text-type-small">Clave de Acceso</span>
			</div>	
			<div>
				<input autocomplete="off" name="admin[password]" class="admin-field input-type-medium" type="text" id="password">
			</div>
		</div>
		<div class="col-help align-left">
			<span class="text-type-small">
				<u>Clave:</u>
			</span>
			<span class="text-type-small text-color-red">
				Ingrese la clave con la que podrás acceder a tu admin en el Servidor.
			</span>
			<br />
			<span class="text-type-small">Ej. Para acceder al servidor</span> <span class="text-type-small text-color-green">setinfo _pw tuclave</span>
			<br />
			<span class="text-type-small text-style-strong">Nivel de la Clave</span>
			<span id="pwdMeter" class="neutral">Muy Fácil</span>
		</div>
		<div class="clear"></div>
	</div>
	<div class="row">
		<div class="col-data align-left">
			<div>
				<span class="text-type-small">Repetir Clave de Acceso</span>
			</div>	
			<div>
				<input id="repeat-password" class="admin-field input-type-medium" type="text" name="admin[repeat_password]" />
			</div>
		</div>
		<div class="col-help align-left">
			<span class="text-type-small">
				<u>Repetir Clave:</u>
			</span>
			<span class="text-type-small text-color-red">
				Pedimos que escriba su clave nuevamente, para confirmar que la escribió de forma correcta
			</span>
		</div>
		<div class="clear"></div>
	</div>
	<div class="row">
		<div class="col-data align-left">
			<div>
				<span class="text-type-small">Tipo de Compra</span>
			</div>
			<div>
				<div>
					<label class='align-left label-type-small text-type-small'>
						<input id='buy-type-normal' type="radio" name="buy-type" value="normal">Normal
					</label>
					<label class='align-left label-type-small text-type-small'>
						<input id='buy-type-promocion' type="radio" name="buy-type" value="promocion">Promociones
					</label>
				</div>
			</div>
		</div>
		<div class="col-help align-left">
			<span class="text-type-small">
				<u>Tipo de Compra:</u>
			</span><br />
			<span class="text-type-small text-color-red">Elegi si comprar un admin individual, o una promocion con varios admins</span>
		</div>
		<div class="clear"></div>
	</div>
	<div class="row buy-type admin-promocion">
		<div class="col-data align-left">
			<div>
				<span class="text-type-small">Promociones (Admin en <?php echo $promociones_servers; ?> Servidores)</span>
			</div>
			<div>
				<select id="admin-promocion" class="admin-field input-type-medium">
					<option selected="selected" value=''>Elija una opción</option>
					<option disabled="disabled">------------------------------------------------------</option>
					<?php if($list_promociones){ ?>
						<?php foreach($list_promociones as $price=>$name){ ?>
						<option value="<?php echo $price; ?>"><?php echo ucfirst($name); ?> ($<?php echo $price; ?>)</option>
						<?php } ?>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="col-help align-left">
			<span class="text-type-small">
				<u>Promociones:</u>
			</span><br />
			<span class="text-type-small text-color-red">Comprar Admin en todos los Servidores</span><br />
			<span class="text-type-small text-color-red" style="display:none;">No incluye el Servidor <b>[Mix/Closed]</b></span>
		</div>
		<div class="clear"></div>	
	</div>
	<div class="row buy-type admin-normal">
		<div class="col-data align-left">
			<div>
				<span class="text-type-small">Servidor</span>
			</div>
			<div>
				<select id="admin-server" class="admin-field input-type-medium">
					<option selected="selected" value=''>Elija una opción</option>
					<option disabled="disabled">------------------------------------------------------</option>
					<?php if($list_servers){ ?>
						<?php foreach($list_servers as $name=>$address){ ?>
						<option value="<?php echo $name; ?>"><?php echo ucfirst($name); ?></option>
						<?php } ?>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="col-help align-left">
			<span class="text-type-small">
				<u>Servidor:</u>
			</span>
			<span class="text-type-small text-color-red">
				Elija el servidor en el que haras uso de admin.
			</span>
		</div>
		<div class="clear"></div>	
	</div>
	<div class="row buy-type admin-normal">
		<div class="col-data align-left">
			<div>
				<span class="text-type-small">Tipo de Admin</span>
			</div>
			<div>
				<select id="admin-type" class="admin-field input-type-medium">
					<option selected="selected" value=''>Elija una opción</option>
					<option disabled="disabled">------------------------------------------------------</option>
					<?php if($list_admins){ ?>
						<?php foreach($list_admins as $price => $name){ ?>
						<option value="<?php echo $price; ?>"><?php echo ucfirst($name); ?> ($<?php echo $price; ?>)</option>
						<?php } ?>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="col-help align-left">
			<span class="text-type-small">
				<u>Admin:</u>
			</span>
			<span class="text-type-small text-color-red">
				Elija el tipo de admin que va a pagar. El precio varia segun los privilegios que tendrás.
			</span>
		</div>	
		<div class="clear"></div>	
	</div>
	<div>
		<input id="create-admin" type="button" value="Crear Admin">
	</div>
	</form>
</div>
<?php } ?>
</div>
<script type="text/javascript" src="js/jquery.js"></script>
<?php if($user){ ?>
	<script type="text/javascript">
	(function($){
		var help = $(this).parent().parent().parent().parent();

		$("input[name='buy-type']").on({
			change: function(){
				value = $(this).val();
				buy_type = $(".buy-type");
				admin_normal = $(".admin-normal");
				admin_promocion = $(".admin-promocion");

				if(value=='normal'){
					$("#admin-promocion").removeAttr('name');
					$("#admin-server").attr('name', 'admin[server]');
					$("#admin-type").attr('name', 'admin[type]');
					buy_type.stop()
					buy_type.fadeOut(700)
					admin_normal.stop();
					admin_normal.fadeIn(700);
				}
				if(value=='promocion'){
					$("#admin-server").removeAttr('name');
					$("#admin-type").removeAttr('name');
					$("#admin-promocion").attr('name', 'admin[promocion]');
					buy_type.stop()
					buy_type.fadeOut(700)
					admin_promocion.stop();
					admin_promocion.fadeIn(700);
				}
			},
//			mouseout: function(){help.fadeIn(700); }	
		});
	})(jQuery);
	</script>
	<script type="text/javascript">
	window.error = new Array();
	window.error['fields_empty'] 	= 'Algunos campos quedaron sin completar';
	window.error['password_check'] 	= 'Las claves no coinciden';
	</script>
	<script type="text/javascript" language="javascript" src="js/jquery.pwdMeter.js"></script>
	<script type="text/javascript" language="javascript" src="js/functions.js"></script>
	<script type="text/javascript" language="javascript">
	(function($){
		$(document).ready(function(){
			$('form').attr('method', 'post');
			$('input, select').removeAttr('disabled');
			$('#password').pwdMeter({
				minLength: 6,
				displayGeneratePassword: true,
				generatePassText: 'Generar Clave',
				generatePassClass: 'GeneratePasswordLink',
				RandomPassLength: 13
			});
		});
	})(jQuery);
	</script>
<?php }else{ ?>
	<script type="text/javascript" language="javascript">
	(function($){
		$(document).ready(function(){
			$('form').removeAttr('method');
			$('input, select').prop('disabled', 'disabled');
		});
	})(jQuery);
	</script>
<?php } ?>
</body>
</html>


