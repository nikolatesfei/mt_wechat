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
if(!is_numeric($_GET['newsid']) || !$news = C::t('#mt_wechat#mt_wechat_news_reply')->fetch($_GET['newsid'])){
	showmessage('no news data');
}
if($_G['mobile']){
	$mobile = '/touch/';
}
include template('diy:news', 0, 'source/plugin/mt_wechat/template/wechat');
?>