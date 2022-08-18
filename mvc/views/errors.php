    <ul class="border border-danger text-danger" style="width: 40%">
      <?php foreach ($errors as $error): ?>
      <li><?php print $this->escape($error); ?></li>
      <?php endforeach; ?>
    </ul>
