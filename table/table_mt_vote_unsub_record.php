<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      table_mt_vote_unsub_record.php @MT
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_mt_vote_unsub_record extends discuz_table {
	public function __construct() {
		$this->_table = 'mt_vote_unsub_record';
		$this->_pk    = 'id';

		parent::__construct();
	}

	public function count_by_search($param) {
		return DB::result_first('SELECT COUNT(*) FROM %t %i', array($this->_table, $this->wheresql($param)));
	}

	public function fetch_all_by_search($param, $start = 0, $limit = 0, $order = 'dateline', $sort = 'DESC') {
		$ordersql =  $order ? " ORDER BY $order $sort " : '';
		return DB::fetch_all('SELECT * FROM %t %i %i '.DB::limit($start, $limit), array($this->_table, $this->wheresql($param), $ordersql));
	}

	public function fetch_by_record($recordid){
		return DB::fetch_first('SELECT * FROM %t WHERE  voterecordid=%d', array($this->_table, $recordid));
	}

	public function wheresql($param) {
		foreach($param as $value) {
			if($value[1]) {
				$wherearr[] = DB::field($value[0], is_array($value[1]) ? $value[1] : $value[3].$value[1].$value[4], $value[2] ? $value[2] : '=');
			}
		}
		$wheresql = $wherearr ? 'WHERE '.implode(' AND ', $wherearr) : '';
		return $wheresql;
	}
}

?>