<!doctype html>
<html>
    <head>
        <meta charset="utf-8"/>
        <title>Märkmed</title>
        <link rel="stylesheet" type="text/css" href="kujundus.css">
    </head>
	
    <body>
	    
		<div id="wrap">
		<h1 class="appheading">Märkmed</h1>
		<?php foreach (message_list() as $message):?>
		    <p class="message">
			    <?= $message; ?>
			</p>
		<?php endforeach; ?>
		
		<div class="logoutbutton">
		    <form method="post" action="<?= $_SERVER['PHP_SELF']; ?>">
			    <input type="hidden" name="action" value="logout">
				<input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
				<button type="submit">Logige välja!</button>
			</form>
		</div>
		
        <h1>Märkmed</h1>
        <p>
		    <button type="button" id="kuva-lisa-vorm">Ava märkmete sisestamise vorm</button>
		</p>
        <div id="lisa-vorm-vaade">
			
            <form id="lisa-vorm" method="post" action="<?= $_SERVER['PHP_SELF']; ?>">
                
				<input type="hidden" name="action" value="add">
				<input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
				<p>
				    <button type="button" id="peida-lisa-vorm">Peida märkmete sisestamise vorm</button>
				</p>
				<h2>Lisage uus märge</h2>

				<table>
                    <tr>
                        <td>Märkme sisu:</td>
                        <td>
                            <input type="text" id="nimetus" name="nimetus">
                        </td>
                    </tr>
					<tr>
                        <td>Märkme tähtaeg:</td>
                        <td>
                            <input type="datetime-local" id="aeg" name="aeg">
                        </td>
                    </tr>
                    <tr>
                        <td>Märkme prioriteet:</td>
                        <td>
                            <input type="number" id="kohad" name="kohad" min="1" max="5" step="1">
                        </td>
                    </tr>
                </table>
				
				<p>
                    <button type="submit" id="lisa-nupp">Lisage uus märge!</button>
				</p>
				
            </form>
			
        </div>
		
		<!-- Märkmete ülevaatetabel -->
        <table id="kirjed" border="1">
            <th>
                <tr>
                    <th>Märkme sisu</th>
					<th>Märkme tähtaeg</th>
					<th>Märkme prioriteet</th>
                    <th>Märkme kustutamine</th>
                </tr>
            </th>
			
            <tbody>
            <?php 
                foreach ( model_load($page) as $rida ): ?>
                    <tr>
                        <td>
                            <?= htmlspecialchars($rida['nimetus']); ?>
                        </td>
						<td>
                            <?= $rida['aeg']; ?>
                        </td>
						<td>
                            <?= $rida['kohad']; ?>
                        </td>
                        <td>
                            <form method="post" action="<?= $_SERVER['PHP_SELF']; ?>">
							    <input type="hidden" name="action" value="delete">
								<input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                                <input type="hidden" name="kinoseansi_id" value="<?= $rida['kinoseansi_id']; ?>">
                                <button type="submit">Kustutage märge</button>
                            </form>
                        </td>
                    </tr>
            <?php endforeach; ?>
            
			</tbody>
        </table>
		
		<p>
		    <a href="<?= $_SERVER['PHP_SELF']; ?>?page=<?= $page - 1; ?>">
			    Eelmine lehekülg
			</a>
			|
			<a href="<?= $_SERVER['PHP_SELF']; ?>?page=<?= $page + 1; ?>">
			    Järgmine lehekülg
			</a>
			
		</p>

        <script src="kino.js"></script>
		</div>
    </body>

</html>
