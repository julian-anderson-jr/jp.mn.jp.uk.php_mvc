<?php $this->setTargetTitle('title', 'サインイン') ?>
<h2>サインイン</h2>
<form action="<?php echo $base_url; ?>/account/authenticate" method="post">
  <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>" />

  <?php if (isset($errors) && count($errors) > 0): ?>
  <?php print $this->render('errors', array('errors' => $errors)); ?>
  <?php endif; ?>
  
  <?php print $this->render('account/inputsin', array(
    'uid' => $uid,
    'pwd' => $pwd,
  )); ?>
  <p>
    <a href="<?php print $base_url; ?>/account/signup">新規登録の方はこちら</a>
  </p>
  <p><input type="submit" value="サインイン" /></p>
</form>
