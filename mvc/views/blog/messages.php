<div class="message">
  <div class="message_content">
    <a href="<?php print $base_url; ?>/user/<?php print $this->escape($messages['user_name']); ?>">
    <?php print $this->escape($messages['user_name']); ?>
    </a>
    <?php print $this->escape($messages['message']); ?>
  </div>
  <div>
    <a href="<?php print $base_url; ?>/user/<?php print $this->escape($messages['user_name']); 
      ?>/messages/<?php print $this->escape($messages['id']); ?>">
    <?php print $this->escape($messages['update_at']); ?>
    </a>
  </div>
</div>
