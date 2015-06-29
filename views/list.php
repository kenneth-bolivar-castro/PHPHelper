<ul>
  <?php foreach (Anchors::getAnchors() as $value): ?>
    <li><a href="<?php print $value['href'] ?>"><?php echo $value['title'] ?></a></li>
  <?php endforeach; ?>
</ul>
