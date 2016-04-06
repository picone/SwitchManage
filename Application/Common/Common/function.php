<?php
/**
 * 时间格式化函数
 * @param int $timestamp
 * @return string
 */
function dgmdate($timestamp){
    $offset=NOW_TIME-$timestamp;
    if($offset<60){
        $result=$offset.'秒';
    }else if(($offset=intval($offset/60))<60){
        $result=$offset.'分钟';
    }else if(($offset=intval($offset/60))<24){
        $result=$offset.'小时';
    }else{
        $result=intval($offset/24).'天';
    }
    return $result.'前';
}

/**
 * 格式化byte
 * @param string $bytes byte单位
 * @return string
 */
function byte_format($bytes){
    if($bytes<1024){
        return $bytes.'B';
    }else if(($bytes=intval($bytes/1024))<1024){
        return $bytes.'K';
    }else if(($bytes=intval($bytes/1024))<1024){
        return $bytes.'M';
    }else{
        return intval($bytes/1024).'G';
    }
}