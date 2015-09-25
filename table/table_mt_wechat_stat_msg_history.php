<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      table_mt_wechat_stat_msg_history.php @MT
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_mt_wechat_stat_msg_history extends discuz_table {
	public function __construct() {
		$this->_table = 'mt_wechat_stat_msg_history';
		$this->_pk    = 'id';

		parent::__construct();
	}
}

?>