<?php
	/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      index.php @MT
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$perpage = 10;
$page = max(1, intval($_GET['page']));
$start = ($page - 1) * $perpage;
if($start < 0) $start = 0;

$count = C::t('#mt_vote#mt_wechat_account')->count_by_search();
if($count){
	$list = C::t('#mt_vote#mt_wechat_account')->fetch_all_by_search(null,$start,$perpage);
	foreach ($list as $key =>$value) {
		$list[$key]['isconnect'] = $list['isconnect'] ? '已接入' : '未接入'; 
		switch ($value['level']) {
			case 1: $list[$key]['level']='基础订阅号'; break;
			case 2: $list[$key]['level']='认证订阅号'; break;
			case 3: $list[$key]['level']='基础服务号'; break;
			case 4: $list[$key]['level']='认证服务号'; break;
		}
	}
	unset($_GET['page']);
	$theurl = 'plugin.php?'.url_implode($_GET);
	$multi = multi($count, $perpage, $page, $theurl);
}
include template('diy:index', 0, 'source/plugin/mt_vote/template/admin/wechat');

?>