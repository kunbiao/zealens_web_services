<?php
use config\Debug;
//计算时间戳
function getMicrotime()
{
    list($usec,$sec) = explode(" ",microtime());
    return round(($usec+$sec) * 1000);
}
/**
 * Trace记录
 *
 * @param string $value 变量
 * @param string $label 错误代码
 * @param string $level 日志级别
 * @param boolean $record 是否记录日志
 * @return void|array
 */
function trace($value = '[trace]', $label = '', $level = '', $record = false)
{

    return Debug::trace($value,$label,$level,$record);
}