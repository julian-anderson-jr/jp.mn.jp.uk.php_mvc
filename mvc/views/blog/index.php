<?php $this->setTargetTitle('title', 'ユーザのトップページ') ?>

<form action="<?php echo $base_url; ?>/messages/post" method="post">
  <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>" />

  <?php if (isset($errors) && count($errors) > 0): ?>
  <?php print $this->render('errors', array('errors' => $errors)); ?>
  <?php endif; ?>
  <p>投稿する記事を入力：</p>
  <textarea name="message" rows="4" cols="60"><?php print $this->escape($message); ?>
  </textarea>
  <p><input type="submit" value="投稿する" /></p>
</form>
<h2>記事一覧</h2>
<div id="messagelist">
  <?php foreach ($messagelist as $messages): ?>
  <?php print $this->render('blog/messages', array('messages' => $messages)); ?>
  <?php endforeach; ?>
</div>
