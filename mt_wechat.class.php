<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      mt_vote.class.php @MT
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class plugin_mt_vote {
	
	function plugin_mt_vote() {
		global $_G;

	}

	function global_usernav_extra1(){
		global $_G;
		if(!in_array($_G['groupid'],unserialize($_G['cache']['plugin']['mt_vote']['group'])) || $_G['adminid'] != 1){
			return '';
		}
		return '<span class="pipe">|</span><a href="plugin.php?id=mt_vote&admin=index">vote mamager</a> ';
	}
}

?>