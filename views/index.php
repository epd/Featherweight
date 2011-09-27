
<h1><?php echo APP_NAME; ?></h1>
<p>This is my application written in the Featherweight framework.</p>
<h2>List of example information:</h2>
<ul>
  <?php foreach ($examples AS $e): ?>
  <li><?php echo $e['title']; ?></li>
  <?php endforeach; ?>
</ul>

