<?php
class mail_historyModel extends ExecuteModel {
	public function insert($uid, $to, $from) {
		$now = new DateTime();
		$now2 = new DateTime();
		$now2 = $now2->modify('-30 day');
		$sql = "
			delete from mail_history
			where sendtime < :sendtime
		";

		$stmt = $this->execute($sql, array(
			':sendtime'	=> $now2->format('Y-m-d H:i:s'),
		));

		$sql = "
			insert into mail_history(uid, sendtime, to_email, from_email)
			values(:uid, :sendtime, :to_email, :from_email)
		";

		$stmt = $this->execute($sql, array(
			':uid'		=> $uid,
			':sendtime'	=> $now->format('Y-m-d H:i:s'),
			':to_email'	=> $to,
			':from_email'	=> $from,
		));
	}
	
}