<?php $this->setTargetTitle('title', 'アカウントを作成') ?>
<h2>ユーザアカウントを作成</h2>
<form action="<?php echo $base_url; ?>/account/register" method="post">
  <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>" />

  <?php if (isset($errors) && count($errors) > 0): ?>
  <?php print $this->render('errors', array('errors' => $errors)); ?>
  <?php endif; ?>
  
  <?php print $this->render('account/inputs', array(
    'uid' => $uid,
    'pwd' => $pwd,
    'name' => $name,
    'email' => $email,
    'birth_day' => $birth_day,
  )); ?>
  <p><input type="submit" value="登録" /></p>
</form>
