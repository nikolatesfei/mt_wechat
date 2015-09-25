<?php
$root = explode("source",dirname(__FILE__));
require_once $root[0].'./source/plugin/mt_wechat/wechat/mt_class_core.php';
$discuz = C::app();
$discuz->init();
if(!$_GET['accountid'] || !is_numeric($_GET['accountid'])){return false;}
$account = C::t('#mt_wechat#mt_wechat_account')->fetch($_GET['accountid']);
if(!$account){return false;}
include "wechat.class.php";
$options = array(
		'appid'=> $account['key'],
		'appsecret'=> $account['secret'],
		'token'=> $account['token'],
        'encodingaeskey'=> $account['encodingaeskey']
	);
$weObj = new Wechat($options);
$weObj->valid(false,$account['id']);
include "wechatControl.php";
$wechat = new wechatControl($weObj,$account);
// replyBasic();
// $weObj->text($weObj->checkAuth())->reply();
// $weObj->text($weObj->getRevFrom())->reply();
// $weObj->text($weObj->getRevTo())->reply();
// $weObj->text($weObj->getForeverCount())->reply();
// $weObj->text($weObj->getRev()->getRevContent())->reply();
// $weObj->text($weObj->getRevID())->reply();
// $type = $weObj->getRev()->getRevType();
switch($wechat->revType()) {
	case Wechat::MSGTYPE_EVENT:
		$wechat->event_user();
		break;
	case Wechat::MSGTYPE_TEXT:
		$default = C::t('#mt_wechat#mt_wechat_account_settings')->fetch_accountid_for_default($account['id']);
		if(isset($default)){
			$wechat->keywordText($default);
		}
		$wechat->replySystem();
}







