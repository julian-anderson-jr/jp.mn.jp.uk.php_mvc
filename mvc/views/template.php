<!DOCTYPE html>
<html>
<head>
<meta charset='UTF-8'>
<title>
<?php if (isset($title)) : print $this->escape($title) . ' - '; endif; ?>
Weblog
</title>
<link rel="stylesheet" href="/mvc/web/css/bootstrap.min.css">
<script src="/mvc/web/js/jquery-3.5.1.slim.min.js"></script>
<script src="/mvc/web/js/bootstrap.min.js"></script>
<script src="/mvc/web/js/cmn001.js"></script>
<link rel="stylesheet" type="text/css" href="/mvc/web/css/bootstrap-datepicker.min.css">
<script type="text/javascript" src="/mvc/web/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="/mvc/web/js/bootstrap-datepicker.ja.min.js"></script>
</head>
<body>
<div id="nav">
  <p>
    <?php if ($session->isAuthenticated()): ?>
      <a href="<?php print $base_url; ?>/">トップページ</a> ＞ 
      <a href="<?php print $base_url; ?>/account">アカウント</a>
    <?php else: ?>
    <?php endif; ?>
</div>
<div id="main">
  <?php print $_content; ?>
</div>
</body>
</html>
