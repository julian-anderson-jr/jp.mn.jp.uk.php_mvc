<?php
class auth_userModel extends ExecuteModel {
	public function insert($uid, $pwd, $name, $email, $birth_day) {
		$pwd = password_hash($pwd, PASSWORD_DEFAULT);
		$now = new DateTime();
		$now = $now->modify('+30 minute');
		$now = $now->format('Y-m-d H:i:s');
		$birth_day2 = DateTime::createFromFormat('Y/m/d', $birth_day);
		$birth_day = $birth_day2->format('Y-m-d') . ' 00:00:00';
		//$birth_day = new DateTime($birth_day);
		$sql = "
			insert into auth_user(uid, pwd, name, is_lock, error_count, valid_mail, birth_day, first_limit, email)
			values(:uid, :pwd, :name, :is_lock, :error_count, :valid_mail, :birth_day, :first_limit, :email)
		";
		
		$stmt = $this->execute($sql, array(
			':uid'			=> $uid,
			':pwd'			=> $pwd,
			':name'			=> $name,
			':is_lock'		=> 0,
			':error_count'	=> 0,
			':valid_mail'	=> 0,
			':birth_day'	=> $birth_day,
			':first_limit'	=> $now,
			':email'		=> $email,
		));
	}
	
	public function getAuth_UserRecored($uid) {
		$sql = "
			select * from auth_user where uid = :uid
		";
		
		$userdata = $this->getRecord($sql, array(':uid' => $uid));
		return $userdata;
	}
	
	public function setEmailValid() {
		
		return false;
	}
	
	public function getUIDExists($uid){
		$sql = "
			select count(1) as cnt from auth_user where uid = :uid
		";
		
		$row = $this->getRecord($sql, array(':uid' => $uid));
		if ($row['cnt'] === 0) {
			return true;
		}
		return false;
	}
	
	public function getMailExists($email){
		$sql = "
			select count(1) as cnt from auth_user where email = :email
		";
		
		$row = $this->getRecord($sql, array(':email' => $email));
		if ($row['cnt'] === 0) {
			return true;
		}
		return false;
	}
	
	public function update($uid, $pwd, $name, $email, $birth_day) {
		$birth_day2 = DateTime::createFromFormat('Y/m/d', $birth_day);
		$birth_day = $birth_day2->format('Y-m-d') . ' 00:00:00';
		if (!strlen(pwd)) {
			$now = new DateTime();
			$sql = "
				update auth_user
				set 
				uid = :uid, 
				pwd = :pwd, 
				name = :name, 
				email = :email,
				birth_day = :birth_day
				where uid = :uid
			";
			
			$stmt = $this->execute($sql, array(
				':uid'		=> $uid,
				':name'		=> $name,
				':birth_day'=> $birth_day,
				':email'	=> $email,
			));
			return true;
		} else {
			$pwd = password_hash($pwd, PASSWORD_DEFAULT);
			$now = new DateTime();
			$sql = "
				update auth_user
				set 
				uid = :uid, 
				pwd = :pwd, 
				name = :name, 
				email = :email,
				birth_day = :birth_day
				where uid = :uid
			";
			
			$stmt = $this->execute($sql, array(
				':uid'		=> $uid,
				':pwd'		=> $pwd,
				':name'		=> $name,
				':birth_day'=> $birth_day,
				':email'	=> $email,
			));
		}
	}
	
	public function setErrorCount($uid) {
		$sql = "
			update auth_user
			set 
			error_count = error_count + 1, 
			is_lock = case when error_count + 1 >= 6 then 1 else 0 end
			where uid = :uid
		";
		
		$stmt = $this->execute($sql, array(
			':uid'		=> $uid,
		));
	}
	
	public function setUnlock($uid, $is_lock = 0) {
		$sql = "
			update auth_user
			set 
			is_lock = :is_lock,
			error_count = :error_count 
			where uid = :uid
		";
		$error_count = 0;
		if ($is_lock == 1) {
			$error_count  = 6;
		}
		$stmt = $this->execute($sql, array(
			':uid'		=> $uid,
			':is_lock'	=> $is_lock,
			':error_count '	=> $error_count,
		));
	}
	
	public function setValidMail($uid, $valid_mail = 0) {
		$sql = "
			update auth_user
			set 
			valid_mail = :valid_mail
			where uid = :uid
		";
		
		$stmt = $this->execute($sql, array(
			':valid_mail'=> $valid_mail,
			':uid'		=> $uid,
		));
	}
}