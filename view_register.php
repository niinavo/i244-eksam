<!doctype html>
<html>
    <head>
	
        <meta charset="utf-8"/>
        <title>Registreerimise vorm</title>
		<link rel="stylesheet" type="text/css" href="kujundus.css"> 
		
    </head>
	
    <body>
	    
		<div id="wrap">
        <?php foreach (message_list() as $message):?>
		    <p class="message">
			    <?= $message; ?>
			</p>
		<?php endforeach; ?>
		
		<h1 class="appheading">Märkmed</h1>
		<h1>Registreerimise vorm</h1>
		
		<form method="post" action="<?= $_SERVER['PHP_SELF']; ?>">
		    
		    <input type="hidden" name="action" value="register">
			<input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
		    
			<table>
			    <tr>
				    <td>Kasutajanimi:</td>
					<td>
					    <input type="text" name="kasutajanimi" required>
					</td>
				</tr>
				
				<tr>
				    <td>Parool:</td>
					<td>
					    <input type="password" name="parool" required>
					</td>
				</tr>
			</table>
			
			<p>
			    <button type="submit">Registreerige kasutajaks!</button>
			</p>
		
		</form>
    </div>
	</body>

</html>