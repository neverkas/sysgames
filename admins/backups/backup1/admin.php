<?php
/*
	http://jsfiddle.net/gVPkS/2/
*/
?>
<?php require_once 'libs/datetime_expiration.php'; ?>
<?php require_once 'header.php'; ?>
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
	<?php if(get_login()==NULL){ ?>
	<div id="app-sysgames-login" onClick="javascript:facebookLogin();">
		<span>INICIAR SESION</span>
	</div>
	<?php } ?>
<?php if(get_login()==TRUE && (get_facebook_id()=='100004392010248' || get_facebook_id()=='1221046838')){ ?>
<?php# if(get_login()==TRUE && (get_facebook_id()=='100004138148032' || get_facebook_id()=='100004392010248' || get_facebook_id()=='100002027098444')){ ?>
<style type="text/css">
#block-pays{
	margin:0 auto;
	width:970px;
}
a, img, input{	outline:none; }
.text-align-center{ text-align:center; }
.align-left{ float:left; }
.clear{ clear:both; }
.row-with-medium{ width:120px; }
.row-with-small{ width:45px; }
#block-head{
    height: 35px;
    line-height: 35px;
    color: #FFFFFF;
    font-family: 'Oswald';
    font-size: 1em;
    letter-spacing: 1px;
    text-transform: uppercase;	
    text-align:center;
}
	.row-head{
	    background-color: #222222;
		margin-right: 1px;
	}
.block-data{
	overflow: hidden;
	margin-bottom:1px;
	height:25px;
	line-height:25px;
	color:#222;
	font-family:'Trebuchet MS';
	font-size:0.8em;
	text-align:left;
/*	text-indent:15px; */
}
.row-data{
	margin-right:1px;
}
.row-data-red{
	background-color:rgb(230,190,190);
}
.row-data-green{
	background-color:rgb(190,230,190);
}
.row-icons img{
	width:15px;
	height:15px;
	border:none;
}
.status-block{
	margin:0 auto;
	width:60px;
}
.status-item{
	display:inline-block;
	margin-right:1px;
	text-align:center;
	border-radius:5px;
	-moz-border-radius:5px;
	-webkit-border-radius:5px;
	-o-border-radius:5px;
}
.status-field{
	cursor:pointer;
	margin:0;
	padding:0;
	display: block;
    height: 25px;
    width: 25px;
}
.status-red{
	background-color: rgb(205,0,0);
}
.status-green{
	background-color: rgb(0,205,0);
}

/* Search #Start */
	#search-block{
		margin:0 auto 25px;
		width:275px;		
	}
	#search-content {
	    background-color: #FFFFFF;
	    border: 1px solid #444444;
	    border-radius: 3px 3px 3px 3px;
	    height: 22px;
	    position: relative;
	    width: 100%;
	}
	#search-button {
	    background: url("http://www.sysgames.net/images/web/default/search.png") no-repeat scroll center top transparent;
	    border: medium none;
	    cursor: pointer;
	    height: 18px;
	    width: 18px;
	}
	#search-text {
	    background: none repeat scroll 0 0 transparent;
	    border: medium none;
	    height: 22px;
	    text-indent: 5px;
	    width: 90%;
	}
/* Search #End */
</style>
<?php
$db 	= new PDO('mysql:dbname=emmade6_sys_pagos;host=localhost', 'emmade6_5y5g4m3s', '@)!%g!CX6IZOm-%');
$query 	= "select * from sys_info";
?>
<div id="search-block">
	<div id="search-content">							
		<input type="text" dir="ltr" autocomplete="off" id="search-text" placeholder="Buscar código de barras..." title="Search" spellcheck="false" maxlength="54">
		<input type="button" id="search-button">
	</div>
</div>
<div id="block-pays">
	<div id="block-head">
		<div class="row-head align-left row-with-medium">Nick</div>
		<div class="row-head align-left row-with-medium">Servidor</div>
		<div class="row-head align-left row-with-medium">Admin</div>
		<div class="row-head align-left row-with-medium">Clave</div>
		<div class="row-head align-left row-with-medium">Precio</div>
		<div class="row-head align-left row-with-medium">Vencimiento</div>
		<div class="row-head align-left row-with-medium">Estado</div>
		<div class="row-head align-left row-with-medium">Acciones</div>
		<div class="clear"></div>
	</div>
<?php $count=0; ?>
<?php foreach($db->query($query) as $data){ ?>
<?php ++$count; ?>
	<?php
		$facebook_id 	= $data['facebook_id'];
		$ticket 		= $data['ticket'];
		$status 		= $data['status'];
		$server_name 	= ucfirst($data['server_name']);
		$admin_name 	= $data['admin_name'];
		$admin_type 	= ucfirst($data['admin_type']);
		$admin_price 	= $data['admin_price'];
		$admin_password = $data['admin_password'];
		$expiration 	= check_expiration($data['expiration']);
		#$expiration 	= $data['expiration'];

		$status_class 	= ($status == 0) ? 'row-data-red' : 'row-data-green'; 

		$checked_red 	= ($status == 0) ? 'checked="checked"' : '';
		$checked_green 	= ($status == 1) ? 'checked="checked"' : '';
	?>
	<div id="<?php echo $ticket; ?>" class="block-data <?php echo $status_class; ?>">
		<div class="fields" style="display:none;">
			<input type="hidden" class='field' value="<?php echo $facebook_id; ?>" />
		</div>
		<div class="row-data align-left row-with-medium">
			<span><?php echo $admin_name; ?></span>
		</div>
		<div class="row-data align-left row-with-medium">
			<span><?php echo $server_name; ?></span>
		</div>
		<div class="row-data align-left row-with-medium">
			<span><?php echo $admin_type; ?></span>
		</div>
		<div class="row-data align-left row-with-medium">
			<span><?php echo $admin_password; ?></span>
		</div>
		<div class="row-data align-left row-with-medium">
			<span>$<?php echo $admin_price; ?></span>
		</div>
		<div class="row-data align-left row-with-medium">
			<span><?php echo $expiration; ?></span>
		</div>
		<div class="row-status row-data align-left row-with-medium">
			<div class="status-block">
				<div class="status-item status-red">
					<input name="status-<?php echo $count; ?>" class="status-field" type="radio" value="0" <?php echo $checked_red;?> />
				</div>			
				<div class="status-item status-green">
					<input name="status-<?php echo $count; ?>" class="status-field" type="radio" value="1" <?php echo $checked_green;?> />
				</div>	
			</div>		
		</div>
		<div class="row-data align-left row-with-medium">
			<a href="">EDIT</a>
			<a href="">DEL</a>
		</div>
		<div class="clear"></div>
	</div>
<?php } ?>
</div>
<?php } ?>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript">
(function($){
$(document).ready(function(){
	/*
	$(".status-field").live('click', function(){
		value = $(this).parent().parent()..parent().find("input:checked").val();
		console.log(value);
	});

	$(".status-item").live('click', function(){
		block_data 	= $(this).parent().parent();
		value 		= $(this).parent().find("input:checked").val();

		if(value==0){
			block_data.prop('class', 'block-data row-data-red');			
		}
		if(value==1){
			block_data.prop('class', 'block-data row-data-green');			
		}

		$.post('change.php', {'field':'status', 'value':value}, function(data){
			console.log(data);
		});
	});
	*/
	$("#search-button").live('click', function(){
		text = $("#search-text").val();
		$(".block-data").css('display', 'none');
		$("#"+text).css('display', 'block');
	});
});
})(jQuery);
</script>
</body>
</html>
