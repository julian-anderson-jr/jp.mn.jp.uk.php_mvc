<?php $this->setTargetTitle('title', $this->escape($messages['user_name'])) ?>

<?php print $this->render('blog/messages', array('messages' => $messages)); ?>
