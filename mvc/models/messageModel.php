<?php
class messageModel extends ExecuteModel {
	public function insert($uid, $message) {
		$now = new DateTime();
		$sql = "
			insert into message(uid, message, update_at)
			values(:uid, :message, :update_at)
		";
		
		$stmt = $this->execute($sql, array(
			':uid'		=> $uid,
			':message'	=> $message,
			':update_at'=> $now->format('Y-m-d H:i:s'),
		));
	}
	
	public function getUserData($uid) {
		$sql = "
			select a.*, b.name as user_name from message a
			inner join auth_user b
			on a.uid = b.uid
			where a.uid = :uid
			order by a.update_at desc
		";
		
		$userdata = $this->getAllRecord($sql, array(':uid' => $uid,));
		return $userdata;
	}
	
	public function getSpecificMessage($id, $uid) {
		$sql = "
			select a.*, b.name as user_name from message a
			inner join auth_user b
			on a.uid = b.uid
			where a.uid = :uid and a.id = :id
			order by a.update_at desc
		";
		
		$userdata = $this->getRecord($sql, array(':uid' => $uid, ':id' => $id,));
		return $userdata;
	}
	
	public function getDBExists($id){
		$sql = "
			select count(1) as cnt from auth_user where id = :id
		";
		
		$row = $this->getRecord($sql, array(':id' => $id));
		if ($row['cnt'] === 0) {
			return true;
		}
		return false;
	}
	
	public function update($uid, $pwd, $name, $email) {
		$pwd = password_hash($pwd, PASSWORD_DEFAULT);
		$now = new DateTime();
		$sql = "
			update auth_user
			set 
			uid = :uid, 
			pwd = :pwd, 
			name = :name, 
			email = :email
			where uid = :uid
		";
		
		$stmt = $this->execute($sql, array(
			':uid'		=> $uid,
			':pwd'		=> $pwd,
			':name'		=> $name,
			':email'	=> $email,
		));
	}
	
	public function setUnlock($uid, $is_lock = 0) {
		$sql = "
			update auth_user
			set 
			is_lock = :is_lock, 
			where uid = :uid
		";
		
		$stmt = $this->execute($sql, array(
			':is_lock'		=> $is_lock,
			':uid'		=> $uid,
		));
	}
	
	public function setValidMail($uid, $valid_mail = 0) {
		$sql = "
			update auth_user
			set 
			valid_mail = :valid_mail, 
			where uid = :uid
		";
		
		$stmt = $this->execute($sql, array(
			':valid_mail'=> $valid_mail,
			':uid'		=> $uid,
		));
	}
}