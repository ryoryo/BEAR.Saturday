<?php
/**
 * This file is part of the BEAR.Saturday package.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
$bearPath = realpath(dirname(dirname(dirname(__DIR__))));
$vendorPEARPath = "{$bearPath}/BEAR/vendors/PEAR";
ini_set('include_path', $bearPath . PATH_SEPARATOR . $vendorPEARPath . PATH_SEPARATOR . get_include_path());
ini_set('error_log', 'syslog');
set_error_handler(array('BEAR_Bin_Bear', 'errorHandler'));

/**
 * BEAR CLI
 *
 * <pr>
 * Commands:
 * create       create resource.
 * read         show resource.
 * update       update resource.
 * delete       delete resource.
 * init-app     create new application.
 * set-app      set application path.
 * show-app     show application path.
 * clear-cache  clear all cache.
 * clear-log    clear all log.
 * clear-all    clear cache and log.
 * make-doc     make application documents.
 * </pre>
 */
class BEAR_bin_bear
{
    /**
     * bearシェル実行
     */
    public function run()
    {
        // アプリケーションパスが正しく設定されているなら実行
        $this->init();
        $this->exec();
    }

    /**
     * 初期化
     */
    public function init()
    {
        $appPath = $this->_getAppPath();
        ob_start();
        if ($appPath) {
            ini_set('include_path', $appPath . ':' . get_include_path());
            $bearMode = $this->getBearMode($appPath);
            $_SERVER['bearmode'] = $bearMode;
            /** @noinspection PhpIncludeInspection */
            include_once "{$appPath}/App.php";
            // CLI用ページをセット
            if (! BEAR::exists('page')) {
                BEAR::set('page', new BEAR_Page_Cli(array()));
            }
        }
        $this->_initBear();
    }

    /**
     * 実行
     */
    public function exec()
    {
        if ($_SERVER['argc'] == 1) {
            /* @noinspection PhpExpressionResultUnusedInspection */
            $_SERVER['argc'] == 2;
            $argv = array('bear.php', '--help');
        } else {
            $argv = $_SERVER['argv'];
        }
        $config = array('argv' => $argv, 'cli' => true);
        $shell = BEAR::dependency('BEAR_Dev_Shell', $config, true);
        /* @var $shell BEAR_Dev_Shell */
        $shell->execute();
        $display = $shell->getDisplay();
        $display = $display ? $display : "\nOk.";
        echo $display . "\n";
    }

    /**
     * bearmodeの取得
     *
     * htaccessファイルからモードを読みます
     *
     * @param string $appPath アプリケーションルートパス
     *
     * @return int
     */
    public function getBearMode($appPath)
    {
        $matches = array();
        $path = $appPath . '/htdocs/.htaccess';
        if (! file_exists($path)) {
            return 0;
        }
        $htaccess = file_get_contents($path);
        preg_match('/bearmode (\d+)/is', $htaccess, $matches);

        return (isset($matches[1])) ? $matches[1] : 0;
    }

    /**
     * CLI Error handler
     *
     * @param $errno
     * @param $errmsg
     * @param $file
     * @param $line
     * @param $errcontext
     */
    public static function errorHandler(
        $errno,
        $errmsg,
        $file,
        $line,
        /* @noinspection PhpUnusedParameterInspection */
        $errcontext
    ) {
        {
            if ($errno & E_DEPRECATED || $errno & E_STRICT) {
                return;
            }
            $errortype = array(
                E_ERROR => 'Error',
                E_WARNING => 'Warning',
                E_PARSE => 'Parsing Error',
                E_NOTICE => 'Notice',
                E_CORE_ERROR => 'Core Error',
                E_CORE_WARNING => 'Core Warning',
                E_COMPILE_ERROR => 'Compile Error',
                E_COMPILE_WARNING => 'Compile Warning',
                E_USER_ERROR => 'User Error',
                E_USER_WARNING => 'User Warning',
                E_USER_NOTICE => 'User Notice'
            );
            $prefix = $errortype[$errno];
            error_log("{$prefix}[{$errno}]: $errmsg in $file on line $line\n", 0);
        }
    }

    /**
     * Get App path by -app or ~/.bearrc
     *
     * @return mixed
     */
    private function _getAppPath()
    {
        $argv = $_SERVER['argv'];
        $count = count($argv);
        $count--;
        $hasLastOption = $count > 0 && isset($argv[$count]) && isset($argv[$count - 1]);
        $hasAppOption = ($hasLastOption && $argv[$count - 1] === '--app') || ($hasLastOption && $argv[$count - 1] === '-a');
        if ($hasAppOption === true) {
            $appPath = realpath($argv[$count]);
            if (isset($argv[$count]) && $appPath && file_exists($appPath . '/App.php')) {
                return $appPath;
            }
            die("Invalid app path [{$path}]\n");
        }
        $bearrc = getenv('HOME') . '/.bearrc';
        if (file_exists($bearrc)) {
            $bearrc = unserialize(file_get_contents($bearrc));
            $appPath = $bearrc['app'];
            if (file_exists("{$appPath}/App.php")) {
                return $appPath;
            }
        }

        return false;
    }

    /**
     * BEAR初期化
     */
    private function _initBear()
    {
        if (! class_exists('BEAR')) {
            include_once 'BEAR.php';
        }
        BEAR::init();
    }
}

//bearコマンド実行
$bin = new BEAR_Bin_Bear();
$bin->run();
