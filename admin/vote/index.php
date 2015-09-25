<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      index.php.php @MT
 */
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$perpage = 10;
$page = max(1, intval($_GET['page']));
$start = ($page - 1) * $perpage;
if($start < 0) $start = 0;
$param = array();
$count = C::t('#dzapp_sing#dzapp_sing')->count_by_search($param);
if($count) {
	$query = C::t('#dzapp_sing#dzapp_sing')->fetch_all_by_search($param, $start, $perpage);
	foreach($query as $value) {
		$value['singpreview'] = explode("\t", trim($value['singpreview']));
		$value['dateline'] = dgmdate($value['dateline']);
		$value['starttime'] = dgmdate($value['starttime']);
		$value['endtime'] = dgmdate($value['endtime']);
		$value['regstarttime'] = dgmdate($value['regstarttime']);
		$value['regendtime'] = dgmdate($value['regendtime']);
		$value['pic'] = $value['pic'] ? pic_get($value['pic'], 'portal', 0, $value['pic_remote']) : 'static/image/common/nophoto.gif';
		$list[] = $value;
	}
	unset($_GET['page']);
	$theurl = 'plugin.php?'.url_implode($_GET);
	$multi = multi($count, $perpage, $page, $theurl);
}

?>