<?php
    function getipLocation($ip = ''){
        if(empty($ip)){
            $ip = GetIp();
        }
        $res = @file_get_contents('http://ip.taobao.com/service/getIpInfo.php?ip=' . $ip);
        if(empty($res)){ return false; }
        $res         = substr($res,3);
        $jsonMatches = array();
        preg_match('#\{.+?\}#', $res, $jsonMatches);
        if(!isset($jsonMatches[0])){ return false; }
        $json = json_decode($jsonMatches[0], true);
        if(!$json){return false;}
        $json['country']  = diconv($json['country'],'utf-8',CHARSET);
        $json['province'] = diconv($json['province'],'utf-8',CHARSET);
        $json['city']     = diconv($json['city'],'utf-8',CHARSET);
        $json['area']     = diconv($json['area'],'utf-8',CHARSET);
        $json['region']   = diconv($json['region'],'utf-8',CHARSET);
        $json['isp']      = diconv($json['isp'],'utf-8',CHARSET);
        return $json;
    }
?>