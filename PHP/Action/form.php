<h1>Remplissez le formulaire:</h1>
<form action="Render.php" method="post">
<?php 
foreach($questions as $question): ?>
    <p><?php echo $question->render();?></p>
    <?php endforeach; ?>
<input type="submit" value="valider">
</form>
