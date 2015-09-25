<?php
function event_user_unsub_vote($voteid,$wechatuser){

	// 1. 找当前取消关注用户投票记录
	$recordlist = C::t('#mt_vote#mt_vote_record')->fetch_all_by_search(array('uid'=>$wechatuser['id']));
	// 2. 检测投票当前是否在投票期间内,状态符合,未减过票,将该用户投票的对象票数减一,并记录删减投票记录
	foreach ($recordlist as $key => $value) {
		$vote = C::t('#mt_vote#mt_vote')->fetch($value['voteid']);
		if(!$vote || $vote['visible']==0 || time()<$vote['starttime'] || time()>$vote['endtime']){
			continue;
		}
		if($vote['is_group'] && $value['gropid']){
			$group = C::t('#mt_vote#mt_vote_group')->fetch($value['gropid']);
			if(!$group || !$group['visible']){
				continue;
			}
		}
		$child = C::t('#mt_vote#mt_vote_child')->fetch($value['childid']);
		if(!$child){continue;}
		$unsub = C::t('#mt_vote#mt_vote_record_unsub')->fetch_by_record($value['id']);
		if($unsub){continue;}
		C::t('#mt_vote#mt_vote_record_unsub')->insert(array(
			'uid'          => $wechatuser['id'],
			'voterecordid' => $value['id'],
			'voteid'       => $value['voteid'],
			'childid'      => $value['childid'],
			'groupid'      => $value['gropid'],
			'dateline'     => time()
			));
		C::t('#mt_vote#mt_vote_child')->increase($child['id']);
	}
}

?>