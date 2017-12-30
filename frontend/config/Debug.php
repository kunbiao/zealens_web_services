<?php
namespace config;
class Debug {

    static public $error_code = array(
        '1'    => "E_ERROR",                // 致命的运行时错误。错误无法恢复。脚本的执行被中断。
        '2'    => "E_WARNING",              //非致命的运行时错误。脚本的执行不会中断。
        '4'    => "E_PARSE",                //编译时语法解析错误。解析错误只应该由解析器生成。
        '8'    => "E_NOTICE",               //运行时提示。可能是错误，也可能在正常运行脚本时发生。
        '16'   => "E_CORE_ERROR",           //由 PHP 内部生成的错误。     4
        '32'   => "E_CORE_WARNING",         //由 PHP 内部生成的警告。     4
        '64'   => "E_COMPILE_ERROR",        //由 Zend 脚本引擎内部生成的错误。     4
        '128'  => "E_COMPILE_WARNING",      //由 Zend 脚本引擎内部生成的警告。     4
        '256'  => "E_USER_ERROR",           //由于调用 trigger_error() 函数生成的运行时错误。     4
        '512'  => "E_USER_WARNING",         //由于调用 trigger_error() 函数生成的运行时警告。     4
        '1024' => "E_USER_NOTICE",          //由于调用 trigger_error() 函数生成的运行时提示。     4
        '2048' => "E_STRICT",               //运行时提示。对增强代码的互用性和兼容性有益。     5
        '4096' => "E_RECOVERABLE_ERROR",    //可捕获的致命错误。（参阅 set_error_handler()）     5
        '8191' => "E_ALL",                  //所有的错误和警告，除了 E_STRICT。     5
    );

    /**
     * 自定义异常处理
     * @access public
     * @param mixed $e 异常对象
     */
    static public function appException($e) {
        $error = array();
        $error['message']   =   $e->getMessage();
        $trace              =   $e->getTrace();
        if('E'==$trace[0]['function']) {
            $error['file']  =   $trace[0]['file'];
            $error['line']  =   $trace[0]['line'];
        }else{
            $error['file']  =   $e->getFile();
            $error['line']  =   $e->getLine();
        }
        $error['trace']     =   $e->getTraceAsString();
        $error['type']      =   E_USER_ERROR;
        self::halt($error);
    }

    /**
     * 自定义错误处理
     * @access public
     * @param int $errno 错误类型
     * @param string $errstr 错误信息
     * @param string $errfile 错误文件
     * @param int $errline 错误行数
     * @return void
     */
    static public function appError($errno, $errstr, $errfile, $errline) {
        switch ($errno) {
            case E_ERROR:
            case E_PARSE:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_USER_ERROR:
                $error['line'] = $errline;
                $error['file'] = $errfile;
                $error['message'] = $errstr;
                $error['type'] = $errno;
                self::halt($error);
                break;
            default:
                $error['line'] = $errline;
                $error['file'] = $errfile;
                $error['message'] = $errstr;
                $error['type'] = $errno;
                self::halt($error);
                break;
        }
    }

    static public function fatalError()
    {
        //trace(memoryUsage(), '', 'DEBUG', true);
        if ($e = error_get_last()) {
            //trace(memoryUsage(), '', 'DEBUG', true);
            self::halt($e);
        }
        //trace(memoryUsage(), '', 'DEBUG', true);
        // Core\Log::save();

    }

    /**
     * 错误输出
     *
     * @param array $error 错误
     * @return void
     */
    static public function halt($error)
    {
        if (C('application.log.record')) {
            $error_str = self::$error_code[$error['type']] . ' : ' . $error['message'] . ' IN FILE: ' . $error['file'] . ' ON LINE ' . $error['line'];
            Core\Log::record($error_str, Core\Log::ERR, true);
        }

        if (C('application.debug')) {
            $error_str = '<br /><br /><b>' . self::$error_code[$error['type']] . '</b>: ' . $error['message'] . ' IN FILE: <b>' . $error['file'] . '</b> ON LINE <b>' . $error['line'] . '</b><br /><br />';
            echo $error_str;
            debug_print_backtrace();
        }
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
    static public function trace($value = '[trace]', $label = '', $level = '', $record = false)
    {
        static $_trace = array();
        if ('[trace]' === $value) { // 获取trace信息
            return $_trace;
        } else {
            $info = ($label ? $label . ':' : '') . print_r($value, true);
            $level = strtoupper($level);
            $record = C('application.log.record') ? true : $record;
            if ($record) {
                // Core\Log::record($info, $level, $record);
                Core\Log::write($info, $level);
            } else {
                if (! isset($_trace[$level]) ) {
                    $_trace[$level] = array();
                }
                $_trace[$level][] = $info;
            }
        }
    }
}
