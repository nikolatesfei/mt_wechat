<?php
class wechatControl{
	private $weObj;
	private $account;
	private $ruleid;
	private $keywordid;
	private $module;

	public function __construct($weObj,$account)
	{
		$this->weObj   = $weObj;
		$this->account = $account;
	}

	private function setMsgAttribute($ruleid,$keywordid,$module){
		$this->ruleid    = $ruleid;
		$this->keywordid = $keywordid;
		$this->module    = $module;
	}

	function revType(){
		return $this->weObj->getRev()->getRevType();
	}

	function revEvent(){
		return $this->weObj->getRevEvent();
	}

	function fromUser(){
		return $this->weObj->getRevFrom();
	}

	function revContent(){
		return diconv($this->weObj->getRev()->getRevContent(),'utf-8',CHARSET);
	}

	function replySystem(){
		$this->replyBasic('welcome to wechat!');
	}

	function replyBasic($replycontent){
		$this->weObj->text(diconv($replycontent,CHARSET,'utf-8'))->reply();
	}

	function replyNews($replydata){
		$siteurl = C::t('#mt_wechat#mt_wechat_account_settings')->fetch_accountid_for_url($this->account['id']);
		if(!$siteurl){
			$this->replyBasic('lose data');
		}
		foreach ($replydata as $key => $value) {
			if($value['iscontent']){
				$value['url'] = $siteurl.'plugin.php?id=mt_wechat&mod=module&modfun=wechat&class=news&newsid='.$value['id'];
			}
			$newsData [] = array(
				'Title'       => diconv($value['title'],CHARSET,'utf-8'),
				'Description' => diconv($value['description'],CHARSET,'utf-8'),
				'PicUrl'      => $siteurl.'data/attachment/portal/'.$value['thumb'],
				'Url'         => $siteurl.'plugin.php?id=mt_wechat&mod=module&modfun=wechat&class=news&newsid='.$value['id']
				);
		}
		$this->weObj->news($newsData)->reply();
	}

	function event_user(){
		$openid = $this->fromUser();
		$wechatuser = C::t('#mt_wechat#mt_wechat_user')->fetch_by_openid($openid);
		$event = $this->revEvent();
		if($event=='subscribe'){
			$inData = array('subscribe'=>1,'subscribe_time'=>time());
			if($wechatuser){
				C::t('#mt_wechat#mt_wechat_user')->update($wechatuser['id'],$inData);
			}else{
				$inData['openid']     = $openid;
				$inData['account_id'] = $this->account['id'];
				if($this->account['level']%2==0){
					$user = $this->weObj->getUserInfo($openid);
					if(!$this->weObj->errCode){
						$inData['nickname'] = $user['nickname'];
						$inData['sex'] = $user['sex'];
						$inData['province'] = $user['province'];
						$inData['city'] = $user['city'];
						$inData['country'] = $user['country'];
						$inData['headimgurl'] = substr($user['headimgurl'],0,-2);
					}
				}
				C::t('#mt_wechat#mt_wechat_user')->insert($inData);
			}
			$welcome = C::t('#mt_wechat#mt_wechat_account_settings')->fetch_accountid_for_welcome($this->account['id']);
			if(isset($welcome)){
				$this->keywordText($welcome);
			}
			$this->replySystem();
		}else if($event=='unsubscribe'){
			if($wechatuser){
				C::t('#mt_wechat#mt_wechat_user')->update($wechatuser['id'],array('subscribe'=>0,'subscribe_time'=>''));
				$bindmodel = unserialize($this->account['bind_model']);
				foreach ($bindmodel as $key => $value) {
					if($value['model']=='vote' && $value['accountid']==$this->account['id']){
						require_once 'wechat_vote.php';event_user_unsub_vote($value['modelid'],$wechatuser);
					}
				}
			}
		}
	}

	function keywordText($trankeyword=null){
		$keyword = $trankeyword ? $trankeyword : $this->revContent();
		$rule = C::t('#mt_wechat#mt_wechat_rule_keyword')->fetch_account_keword($this->account['id'],$keyword);
		if($rule){
			$this->setMsgAttribute($rule['ruleid'],$rule['id'],$rule['module']);
			// $this->in_msg_history($keyword);
			$replylist = C::t('#mt_wechat#mt_wechat_'.$rule['module'].'_reply')->fetch_all_by_search(array(array('ruleid',$rule['ruleid'])),0,0,'displayorder','asc');
			if($replylist){
				$this->stat_keyword();
				switch ($rule['module']) {
					case 'basic':
						$replycontent = $replylist[rand(0,(count($replylist)-1))];
						$this->replyBasic($replycontent['content']);
					case 'news' :
						$this->replyNews($replylist);
				}
			}
		}
	}

	function in_msg_history($message){
		C::t('#mt_wechat#mt_wechat_stat_msg_history')->insert(array(
			'accountid' => $this->account['id'],
			'ruleid'    => $this->ruleid,
			'keywordid' => $this->keywordid,
			'from_user' => $this->fromUser(),
			'module'    => $this->module,
			'message'   => $message,
			'dateline'  => time()
			));
	}

	function stat_rule(){

	}

	function stat_keyword(){
		$statkeyword = C::t('#mt_wechat#mt_wechat_stat_keyword')->fetch_account_keywrod($this->account['id'],$this->keywordid);
		if($statkeyword){
			C::t('#mt_wechat#mt_wechat_stat_keyword')->update_count($statkeyword['id']);
		}else{
			C::t('#mt_wechat#mt_wechat_stat_keyword')->insert(array(
				'accountid'  => $this->account['id'],
				'ruleid'     => $this->ruleid,
				'keywordid'  => $this->keywordid,
				'count'      => 1,
				'lastupdate' => time(),
				'dateline'   => time()
				));
		}
	}
}