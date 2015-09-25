<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      table_mt_wechat_account_settings.php @MT
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_mt_wechat_account_settings extends discuz_table {
	public function __construct() {
		$this->_table = 'mt_wechat_account_settings';
		$this->_pk    = 'id';

		parent::__construct();
	}

	public function fetch_accountid_for_url($accountid){
		return DB::result_first('SELECT `siteurl` FROM %t WHERE accountid=%d', array($this->_table, $accountid));
	}

	public function fetch_accountid_for_default($accountid){
		return DB::result_first('SELECT `default` FROM %t WHERE accountid=%d', array($this->_table, $accountid));
	}

	public function fetch_accountid_for_welcome($accountid){
		return DB::result_first('SELECT `welcome` FROM %t WHERE accountid=%d', array($this->_table, $accountid));
	}
}

?>