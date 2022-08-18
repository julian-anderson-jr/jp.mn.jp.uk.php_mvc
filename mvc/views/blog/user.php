<?php $this->setTargetTitle('title', $this->escape($user['name'])) ?>

<h2><?php print $this->escape($user['name']); ?></h2>
<div id="messagelist">
  <?php foreach ($messagelist as $messages): ?>
  <?php print $this->render('blog/messages', array('messages' => $messages)); ?>
  <?php endforeach; ?>
</div>
