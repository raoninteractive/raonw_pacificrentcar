<?
    class CLog {

        private static $isexe = true; // 작동 유무

        public static function isexe($bool) {
            if ($bool == true) {
                CLog::$isexe = true;
            } else {
                CLog::$isexe = false;
            }
        }

        public static function init($path='/', $filename = "lprolog.log") {
            // 초기화
			$log_path = chkMapPath("/upload/log/$path");

            $file = fopen("$log_path/$filename", "a+");
			chmod("$log_path/$filename", 0777);
            return $file;
        }
        public static function write($arr_str, $path='/', $filename='') {
            if(CLog::$isexe) {
				if ($filename == '') $filename = date("Ymd"). ".log";

                // 초기화
                $file = CLog::init($path, $filename);
                if($file == false)
                    return;

                $log_string  = '';
				$log_string .= date("Y/m/d H:i:s")." ";
				$log_string .= gethostbyaddr($_SERVER["REMOTE_ADDR"])." ";
                for($i = 0 ; $i < count($arr_str); $i++) {
                    $log_string .= key($arr_str);
                    $log_string .= " : ";
                    $log_string .= current($arr_str)."\t";
                    next($arr_str);
                }
				$log_string .= "\r\n";

                flock($file, LOCK_EX);
                fputs($file, $log_string);
                flock($file, LOCK_UN);

                fclose($file);
            }
        }
    }
?>