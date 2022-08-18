  <table>
    <tr>
      <th>ユーザID</th>
      <td>
        <div class="col-12">
          <input type="text" name="uid" style="width:150px;" value="<?php print $this->escape($uid); ?>"/><span class="text-danger">*</span>
        </div>
      </td>
    </tr>
    <tr>
      <th>パスワード</th>
      <td>
        <div class="col-12">
          <input type="password" name="pwd" style="width:150px;" value="<?php print $this->escape($pwd); ?>"/><span class="text-danger">*</span>
        </div>
      </td>
    </tr>
  </table>
