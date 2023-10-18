<?
    class CLS_JWT
    {
        protected $alg;

        //만료시간 지정 (초단위, 기본60분)
        public $expire_time = 0;

        //만료시간 체크
        public $expire_check = true;

        //동일세션 체크
        public $session_check = false;

        function __construct()
        {
            //사용할 알고리즘
            $this->alg = 'sha256';

            $this->expire_time = 1 * 60 * 60;
        }

        function hashing(array $data)
        {
            // 토큰의 헤더
            $header = json_encode(array(
                    'alg'=>$this->alg,
                    'session'=>USER_SESSION,
                    'time'=>time(),
                    'typ'=>'JWT'
            ));

            // 전달할 데이터
            $payload = json_encode($data);

            // 시그니처 토큰 확인에서 제일 중요
            // 충분히 복잡하게 구현해야함
            $signature = hash($this->alg, $header.$payload);

            return base64_encode($header.'.'.$payload.'.'.$signature);
        }

        function dehashing($token)
        {
            // 토큰 만들때의 구분자 . 으로 나누기
            $parted = explode('.', base64_decode($token));

            $signature = $parted[2];

            //위에서 토큰 만들때와 같은 방식으로 시그니처 만들고 비교
            if(hash($this->alg, $parted[0].$parted[1]) == $signature) {
            } else {
                return false;
            }

            $header  = json_decode($parted[0], true);
            $payload = json_decode($parted[1],true);

            //만료시간 체크
            if ($this->expire_check && dateDiff("s", date('Y-m-d H:i:s', $header['time']), date('Y-m-d H:i:s')) > $this->expire_time) {
                return false;
            }

            //동일세션 체크
            if ($this->session_check && $header['session'] != USER_SESSION) {
                return false;
            }

            //복호화값 빈값 체크
            if (chkBlank($payload)) return false;

            return $payload;
        }
    }