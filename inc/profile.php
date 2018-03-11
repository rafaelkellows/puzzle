        <div class="profile" style="display: block;">
          <p>Olá, <strong><?php print $usuario->getName(); ?>.</strong></p>
          <div id="contenedor">
            <div><strong>Último acesso em </strong><?php print $usuario->getVisited(); ?></div>
          </div>
        </div>
        <div class="intro">
          <p style="font-size: .7em; font-weight: bold"><a class="logo" href="./home.php"><img src="images/icons/puzzleIcon.png" /></a><br></p>
