<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      table_mt_wechat.php @MT
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_mt_wechat_stat_keyword extends discuz_table {
	public function __construct() {
		$this->_table = 'mt_wechat_stat_keyword';
		$this->_pk    = 'id';

		parent::__construct();
	}

	public function fetch_account_keywrod($accountid,$keywordid){
		return DB::result_first('SELECT * FROM %t WHERE accountid=%d AND keywordid=%d', array($this->_table, $accountid , $keywordid));
	}

	public function update_count($id){
		return DB::query('UPDATE %t SET count=count+1,lastupdate=unix_timestamp(now()) WHERE id=%d', array($this->_table,$id));
	}
}

?>