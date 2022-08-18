<?php $this->setTargetTitle('title', '仮登録完了') ?>
<h2>仮登録完了</h2>
<form action="" method="post">
  <?php if (isset($errors) && count($errors) > 0): ?>
  <?php print $this->render('errors', array('errors' => $errors)); ?>
  <?php endif; ?>
  
  <table border="1">
    <tr>
      <th>ユーザID</th>
      <td>
        <?php print $this->escape($uid); ?>
      </td>
    </tr>
    <tr>
      <th>e-mail</th>
      <td>
        <?php print $this->escape($email); ?>
      </td>
    </tr>
    <tr>
    </tr>
    <tr>
      <th>処理結果</th>
      <td>
        <pre>
          <?php print $this->escape($message); ?>
        </pre>
      </td>
    </tr>
    
  </table>
</form>
