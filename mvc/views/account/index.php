<?php $this->setTargetTitle('title', 'アカウント') ?>
<h2>アカウント情報</h2>
ユーザID：<a href="<?php print $base_url; ?>/user/<?php print $this->escape($auth_user['uid']); ?>">
<?php print $this->escape($auth_user['uid']); ?>
</a>

<ul>
  <li>
    <a href="<?php print $base_url; ?>/account/signout">サインアウト</a>
  </li>
</ul>
