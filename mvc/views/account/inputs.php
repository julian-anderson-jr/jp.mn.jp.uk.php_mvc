  <table>
    <tr>
      <th>ユーザID</th>
      <td>
        <input type="text" name="uid" value="<?php print $this->escape($uid); ?>"/><span class="text-danger">*</span>
      </td>
    </tr>
    <tr>
      <th>パスワード</th>
      <td>
        <input type="password" name="pwd" value="<?php print $this->escape($pwd); ?>"/><span class="text-danger">*</span>
      </td>
    </tr>
    <tr>
      <th>氏名</th>
      <td>
        <input type="text" name="name" value="<?php print $this->escape($name); ?>"/><span class="text-danger">*</span>
      </td>
    </tr>
    <tr>
      <th>e-mail</th>
      <td>
        <input type="text" name="email" value="<?php print $this->escape($email); ?>"/><span class="text-danger">*</span>
      </td>
    </tr>
    <tr>
      <th>誕生日</th>
      <td>
        <input type="text" id="birth_day" name="birth_day" value="<?php print $this->escape($birth_day); ?>"/><span class="text-danger">*</span>
      </td>
    </tr>
  </table>
  <script type="text/javascript">
    $('#birth_day').datepicker({
        format: 'yyyy/mm/dd'
    });
  </script>
