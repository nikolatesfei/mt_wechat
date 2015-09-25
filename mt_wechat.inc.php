<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      mt_vote.inc.php @MT
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

// $vars     = $_G['cache']['plugin']['mt_vote'];
if($_G['mod']=='module'){
	$folder    = 'module';
	$modfunarr = array('vote','wechat');
	$modfun    = !in_array($_GET['modfun'], $modfunarr) ? 'vote' : $_GET['modfun'];
	$classarr  = array('index','list','view','news');
	$class     = !in_array($_GET['class'], $classarr) ? 'index' : $_GET['class'];
}else if($_G['mod']=='admin'){
	if(empty($_G['uid'])){
		showmessage('to_login','',array(),array('showmsg'=>true,'login'=>1));
	}
	if(!in_array($_G['groupid'],unserialize($vars['group'])) || $_G['adminid'] != 1){
		showmessage('mt_vote:no_authority');
	}
	$folder     = 'admin';
	$modfunarr = array('vote','wechat');
	$modfun    = !in_array($_GET['modfun'], $modfunarr) ? 'vote' : $_GET['modfun'];
	switch ($modfun) {
		case 'vote':
			$classarray = array('index','add','edit','sign','child');
			break;
		case 'wechat':
			$classarray = array('index','replybasic','replynews','menu','special');
			break;
	}
	$class = !in_array($_GET['class'], $classarray) ? $classarray[0] : $_GET['class'];
}
include DISCUZ_ROOT.'./source/plugin/mt_wechat/'.$folder.'/'.$modfun.'/'.$class.'.php';

?>