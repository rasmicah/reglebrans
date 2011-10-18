<!DOCTYPE html>
<html>
<head>
	<title><?php echo $title;?></title>
	<link rel='stylesheet' type='text/css' href='./styles/style.css' />
</head>
<body>
	<div id='wrapper'>
		<div id='header'>
			<p>Midway Distrubution Management Console</p>
		</div>
		
		<div id='nav'>
			<table>
				<tr>
					<td><a href='<?php echo UrlHelper::generateUrl('user', 'getusers'); ?>'>Users</a></td>
					<td><a href='<?php echo UrlHelper::generateUrl('warehouse', 'getwarehouses'); ?>'>Warehouse</a></td>
					<td><a href='<?php echo UrlHelper::generateUrl('department', 'getdepartments'); ?>'>Departments</a></td>
					<td><a href='<?php echo UrlHelper::generateUrl('item', 'getitems'); ?>'>Items</a></td>
					<td><a href='<?php echo UrlHelper::generateUrl('category', 'getcategories'); ?>'>Categories</a></td>
					<td><a href='#'>Deliveries</a></td>
				</tr>
			</table>
		</div>
		
		<div id='content'>							
			<?php echo $content?>			
		</div>
		<div id='footer'>
			<p>
				Midway Distributors<br /><br />
				<em>Developed by Garrett Rasmicah Stephenson 2011</em>
			</p>
		</div>
	</div>
</body>
</html>