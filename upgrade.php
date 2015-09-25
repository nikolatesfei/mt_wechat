<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      upgrade.php @MT
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$sql[''] = <<<EOF

EOF;
foreach($sql as $key => $value) {
	if($_GET['fromversion'] < $key) runquery($value);
}

$finish = TRUE;

?>