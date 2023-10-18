<?php
/**
 * SMS 관리
 */
class CLS_SMS
{
	private $db;

	function __construct()
	{
		$this->db = new DB_HELPER;
	}

	//회원 목록
	public function member_list($params = null, &$total_cnt=0, &$total_page=1) {
        if (chkBlank($params)) return null;

		$sub_sql = "";

		//가입 시작일 검색
		if ($params['sch_sdate'] != "") {
			$sub_sql .= " AND TIMESTAMPDIFF(DAY, DATE_FORMAT(reg_date,'%Y-%m-%d'), '". $params['sch_sdate'] ."') <= 0";
		}

		//가입 종료일 검색
		if ($params['sch_edate'] != "") {
			$sub_sql .= " AND TIMESTAMPDIFF(DAY, DATE_FORMAT(reg_date,'%Y-%m-%d'), '". $params['sch_edate'] ."') >= 0";
        }

		//회원구분검색
		if ($params['sch_gubun'] != "") {
			$sub_sql .= " AND usr_gubun='". $params['sch_gubun'] ."'";
		}

		//검색어 검색
		if ($params['sch_word'] != "") {
			switch ($params['sch_type']) {
				case "1" : $sub_sql .= " AND usr_id LIKE '%". $params['sch_word'] ."%'"; break;
				case "2" : $sub_sql .= " AND usr_name LIKE '%". $params['sch_word'] ."%'"; break;
				default : $sub_sql .= " AND (usr_id LIKE '%". $params['sch_word'] ."%' OR usr_name LIKE '%". $params['sch_word'] ."%')"; break;
			}
		}

        $sql = "
				SELECT
					*,
					CASE
						WHEN gender='M' THEN '남성'
						ELSE '여성'
					END AS gender_name,
					CASE
						WHEN status='Y' THEN '이용중'
						ELSE '이용정지'
					END AS status_name,
					CASE
                        WHEN usr_gubun = '10' THEN '일반회원'
                        WHEN usr_gubun = '80' THEN '탈퇴회원'
						ELSE '기타회원'
					END AS usr_gubun_name
				FROM member
				WHERE usr_gubun < 80 AND status IN ('Y', 'N') $sub_sql
				ORDER BY reg_date DESC
            ";
        $rows = $this->db->getList($sql, $params['page'], $params['list_size'], $total_cnt, $total_page);

        return $rows;
    }

    //SMS 발송이력 목록
    public function send_list($params = null, &$total_cnt=0, &$total_page=1) {
        if (chkBlank($params)) return null;

		$sub_sql = "";

		//발송일 시작 검색
		if ($params['sch_sdate'] != "") {
			$sub_sql .= " AND TIMESTAMPDIFF(DAY, DATE_FORMAT(reserve_date,'%Y-%m-%d'), '". $params['sch_sdate'] ."') <= 0";
		}

		//발송일 종료 검색
		if ($params['sch_edate'] != "") {
			$sub_sql .= " AND TIMESTAMPDIFF(DAY, DATE_FORMAT(reserve_date,'%Y-%m-%d'), '". $params['sch_edate'] ."') >= 0";
        }

		//발송일 상태 검색
		if ($params['sch_status'] != "") {
			$sub_sql .= " AND status LIKE '%". $params['sch_status'] ."%'";
		}

		//검색어 검색
		if ($params['sch_word'] != "") {
			switch ($params['sch_type']) {
				case "1" : $sub_sql .= " AND usr_id LIKE '%". $params['sch_word'] ."%'"; break;
                case "2" : $sub_sql .= " AND recipient_name LIKE '%". $params['sch_word'] ."%'"; break;
                case "3" : $sub_sql .= " AND RIGHT(recipient_tel,4) LIKE '%". $params['sch_word'] ."%'"; break;
                case "4" : $sub_sql .= " AND send_msg LIKE '%". $params['sch_word'] ."%'"; break;
				default : $sub_sql .= " AND (usr_id LIKE '%". $params['sch_word'] ."%' OR
                                            recipient_name LIKE '%". $params['sch_word'] ."%' OR
                                            RIGHT(recipient_tel,4) LIKE '%". $params['sch_word'] ."%' OR
                                            send_msg LIKE '%". $params['sch_word'] ."%')"; break;
			}
		}

        $sql = "
				SELECT
                    *,
                    CASE
                        WHEN send_gubun='I' THEN '즉시발송'
                        WHEN send_gubun='R' THEN '예약발송'
					END AS send_gubun_name,
					CASE
                        WHEN status=0 THEN '대기'
                        WHEN status=1 THEN '완료'
                        WHEN status=2 THEN '취소'
                        WHEN status=3 THEN '실패'
					END AS status_name
				FROM sms_send_log
				WHERE del_flag='N' $sub_sql
				ORDER BY reserve_date DESC
            ";
        $rows = $this->db->getList($sql, $params['page'], $params['list_size'], $total_cnt, $total_page);

        return $rows;
    }

    //SMS 발송이력 상세
    public function send_view($idx) {
        $sql = "
                SELECT
                    *,
                    CASE
                        WHEN send_gubun='I' THEN '즉시발송'
                        WHEN send_gubun='R' THEN '예약발송'
                    END AS send_gubun_name,
                    CASE
                        WHEN status=0 THEN '대기'
                        WHEN status=1 THEN '완료'
                        WHEN status=2 THEN '취소'
                        WHEN status=3 THEN '실패'
                    END AS status_name
                FROM sms_send_log
                WHERE idx='$idx' AND del_flag='N'
            ";

        return $this->db->getQueryValue($sql);
    }

    //SMS 발송 저장
    public function send_save($params = null) {
        if (chkBlank($params)) return false;

        //SMS문자발송시 전송내용이 90byte이상이면 LMS, 첨부파일되면 MMS
        $params['send_type'] = "SMS";
        if (returnToByte($params['send_msg']) > 90) $params['send_type'] = "LMS";
        if ($params['up_file'] != "") $params['send_type'] = "MMS";

        //Date 타입 체크
        if (chkBlank($params['reserve_date'])) {
            $params['reserve_date'] = date("Y-m-d H:i:s");
        }

        if (isDate($params['reserve_date'])) return false;

        //SMS/LMS/MMS 발송로그 저장
        $sql = "
                INSERT INTO sms_send_log (
                    usr_id, section, send_type, send_msg,
                    recipient_name, recipient_tel, sender_name, sender_tel,
                    send_gubun, reserve_date, send_date, up_file, item_etc,
                    status, status_memo, del_flag, reg_ip, reg_id, reg_date
                ) VALUES (
                    '". $params['usr_id'] ."', '". $params['section'] ."', '". $params['send_type'] ."', '". $params['send_msg'] ."',
                    '". $params['rec_name'] ."', '". $params['rec_tel'] ."', '". $params['sender_name'] ."', '". $params['sender_tel'] ."',
                    '". $params['send_gubun'] ."', '". $params['reserve_date'] ."', NULL, '". $params['up_file'] ."', '". $params['item_etc'] ."',
                    0, '', 'N', '". $params['reg_ip'] ."', '". $params['reg_id'] ."', NOW()
                );
            ";
        $this->db->insert($sql);

        $params['idx'] = $this->db->getLastInsertId();
        if (chkBlank($params['idx'])) return false;

		//SMS API 전송
		$this->send_sms($params);

		return true;
    }

    //SMS 예약발송 취소
    public function send_cancel_save($params) {
        $status_memo = $params['upt_id']."님이 예약발송을 취소하였습니다.";

        $sql = "
            UPDATE sms_send_log SET
                status=2,
                status_memo='$status_memo',
                upt_ip = '". $params['upt_ip'] ."',
                upt_id = '". $params['upt_id'] ."',
                upt_date = NOW()
            WHERE idx='". $params['idx'] ."'
        ";

        if (!$this->db->update($sql)) {
            return false;
        } else {
            return true;
        }
    }


	//SMS 발송 API | 업체 맞춰 수정
	public function send_sms ($params) {
		global $CONST_SMS_SENDER;
		global $CONST_SMS_ID;
		global $CONST_SMS_PW;


		$params['rec_tel'] = str_replace("-", "", $params['rec_tel']);
		$params['send_tel'] = str_replace("-", "", $CONST_SMS_SENDER[0]);

		if ($params['send_type'] == 'SMS') {
			//SMS 전송 API
			include_once ($_SERVER["DOCUMENT_ROOT"]."/module/lib/class/nusoap_tong.php");

			$webService = "http://webservice.tongkni.co.kr/sms.3/ServiceSMS.asmx?WSDL";

			$tong_sms = new SMS($webService);
			$result = $tong_sms->SendSMS($CONST_SMS_ID, $CONST_SMS_PW, $params['send_tel'], $params['rec_tel'], $params['send_msg']);
		} else {
			//LMS 전송 API
			include_once ($_SERVER["DOCUMENT_ROOT"]."/module/lib/class/nusoap_tong_lms.php");

			$webService = "http://lmsservice.tongkni.co.kr/lms.1/ServiceLMS.asmx?WSDL";

			$tong_lms = new LMS($webService);
			$result = $tong_lms->SendLMS($CONST_SMS_ID, $CONST_SMS_PW, $params['send_tel'], $params['rec_tel'], SITE_NAME. "에서 발송한 문자입니다.", $params['send_msg']);
		}

		//발송 DB저장
        if ($result > 0) {
            $status_memo = "성공 [$result]";

            //문자 발송 성공시
            $sql = "
                    UPDATE sms_send_log SET
                        status=1,
                        status_memo='$status_memo',
                        send_date=NOW(),
                        upt_ip = '". $params['reg_ip'] ."',
                        upt_id = '". $params['reg_id'] ."'
                    WHERE idx='". $params['idx'] ."'
                ";
        } else {
            $status_memo = "발송실패 [$result]";

            //문자 발송 실패시
            $sql = "
                    UPDATE sms_send_log SET
                        status=3,
                        status_memo='$status_memo',
                        upt_ip = '". $params['reg_ip'] ."',
                        upt_id = '". $params['reg_id'] ."'
                    WHERE idx='". $params['idx'] ."'
                ";
        }

		if (!$this->db->update($sql)) {
			return false;
		} else {
			return true;
		}
	}


    //카카오 알림톡 발송
    public function kakao_send($tmp_number, $kakao_rec, $kakao_add = null, $sender="") {
        global $DEV_MODE, $CONST_TEST_SMS, $CONST_SMS_SENDER;

		if ($DEV_MODE) {
			$kakao_rec = array(
				array(
					'name'=>SITE_NAME,
					'phone'=>$CONST_TEST_SMS[0]
				)
			);
		}

        //발신번호
		$kakao_sender  = $sender;
		if (chkBlank($kakao_sender)) $kakao_sender = $CONST_SMS_SENDER[0];
		$kakao_sender = str_replace("-", "", $kakao_sender);
        $params['sender_name'] = SITE_NAME;
        $params['sender_tel']  = $kakao_sender;

        //수신번호
        $params['rec_name'] = '';
        $params['rec_tel']  = '';
        for ($i=0; $i<count($kakao_rec); $i++) {
            if ($params['rec_name'] != '') $params['rec_name'] .= ", ";
            if ($params['rec_tel'] != '') $params['rec_tel'] .= ", ";

            $params['rec_name'] .= $kakao_rec[$i]['name'];
            $params['rec_tel']  .= str_replace("-", "", $kakao_rec[$i]['phone']);
        }


        //발송템플릿
        if ($tmp_number == 'TI_2078') {
            $params['send_code'] = 'TI_2078';
            $params['send_code_name'] = '예약취소 안내';
            $params['send_title'] = '괌 퍼시픽 렌트카 예약취소 안내';
            $params['send_msg'] =
'[예약취소 안내]
안녕하세요. 괌 퍼시픽 렌트카입니다.

고객님께서 예약하신 예약정보가 취소되었습니다.

◆ 고객명 : #{고객명}
◆ 여행지 : '. $kakao_add[0] .'
◆ 예약번호 : '. $kakao_add[1] .'
◆ 취소일시 : '. $kakao_add[2] .'

감사합니다.';
        } else if ($tmp_number == 'TL_9130') {
            $params['send_code'] = 'TL_9130';
            $params['send_code_name'] = '예약확정 안내';
            $params['send_title'] = '괌 퍼시픽 렌트카 예약확정 안내';
            $params['send_msg'] =
'[예약확정 안내]
안녕하세요. 괌 퍼시픽 렌트카입니다.

고객님의 예약정보가 아래와 같이 확정되었습니다.

◆ 고객명 : #{고객명}
◆ 여행지 : '. $kakao_add[0] .'
◆ 예약번호 : '. $kakao_add[1] .'
◆ 확정서번호 : '. $kakao_add[2] .'

<확정서 보기>
'. $kakao_add[3] .'

※ 공항 픽업 및 호텔 픽업의 경우 필히 약속 된 장소에서만 픽업(직원미팅)이 가능 합니다. 다른 장소에서 대기 시 직원 미팅이 불가능 하며 직원 미팅을 못 하시는 경우 차량 대여는 불가능 하며 당사는 어떠한 책임도 없습니다.
※ 확정서를 확인 하시면 차량 이용 관련 자세한 내용 확인이 가능 합니다 확정서 확인 꼭 부탁 드리겠습니다.
※ 출발전 현지 한국인 직원 카톡 (아이디- pacificrent) 연결 후   픽업 시간 기준 3일 전에는 꼭 연락 부탁 드리겠습니다.

감사합니다.';
        } else if ($tmp_number == 'TJ_5941') {
            $params['send_code'] = 'TJ_5941';
            $params['send_code_name'] = '결제완료안내(퍼시픽)	';
            $params['send_title'] = '괌 퍼시픽 렌트카 결제완료 안내';
            $params['send_btn_1'] = json_encode(array(
                "button" => array(
                    array(
                        "name" => "나의 예약정보 바로가기",
                        "linkType" => "WL",
                        "linkTypeName" => "웹링크",
                        "linkMo" => "http://pacificrentcar.kr/mypage/mypage.php",
                        "linkPc" => "http://pacificrentcar.kr/mypage/mypage.php",
                        "linkIos" => "",
                        "linkAnd" => ""
                    )
                )
            ), JSON_UNESCAPED_UNICODE);
            $params['send_msg'] =
'[결제완료 안내]
안녕하세요. 괌 퍼시픽 렌트카입니다.
온라인 예약 대행수수료 결제가 확인되었습니다

요청 하신 차량은 요청 중이며 차량 확정 시 확정안내 카톡이 발송이 됩니다.

◆ 고객명 : #{고객명}
◆ 여행지 : '. $kakao_add[0] .'
◆ 예약번호 : '. $kakao_add[1] .'
◆ 결제일자 : '. $kakao_add[2] .'
◆ 결제금액 : '. $kakao_add[3] .'

감사합니다.';
        } else if ($tmp_number == 'TJ_5940') {
            $params['send_code'] = 'TJ_5940';
            $params['send_code_name'] = '예약접수안내(퍼시픽)';
            $params['send_title'] = '괌 퍼시픽 렌트카 예약 접수';
            $params['send_btn_1'] = json_encode(array(
                    "button" => array(
                        array(
                            "name" => "나의 예약정보 바로가기",
                            "linkType" => "WL",
                            "linkTypeName" => "웹링크",
                            "linkMo" => "http://pacificrentcar.kr/mypage/mypage.php",
                            "linkPc" => "http://pacificrentcar.kr/mypage/mypage.php",
                            "linkIos" => "",
                            "linkAnd" => ""
                        )
                    )
                ), JSON_UNESCAPED_UNICODE);
            $params['send_msg'] =
'[예약접수 안내]
안녕하세요. 괌 퍼시픽 렌트카입니다.

고객님께서 등록하신 예약정보가 아래와 같이 접수되었습니다.

◆ 고객명 : #{고객명}
◆ 여행지 : '. $kakao_add[0] .'
◆ 예약번호 : '. $kakao_add[1] .'

※ 예약번호는 차량확정번호가 아니며 예약확인을 위한 번호 입니다.
※ 예약 대행수수료 무통장 입금하신 후, "나의 예약정보"에서 무통장 입금확인 요청을 해 주셔야 차량요청이 됩니다. (현금결제만 가능)
※ 예약대행수수료 입금 전용계좌 : 차량 한대 기준 10,000원 (신한은행 110-485-440315 아이피씨코리아)
※ 차량 확정 후 대행수수료는 차량 취소시에도 별도 환불되지 않습니다.

감사합니다.';
        } else if ($tmp_number == 'TI_2076') {
            $params['send_code'] = 'TI_2076';
            $params['send_code_name'] = '문의접수 답변';
            $params['send_title'] = '괌 퍼시픽 렌트카 문의접수 답변';
            $params['send_msg'] =
'[문의접수 답변안내]
안녕하세요. 괌 퍼시픽 렌트카입니다.

고객님께서 작성하신 문의사항에 대한 답변이 메일로 발송되었습니다.

◆ 고객명 : #{고객명}
◆ 여행지 : '. $kakao_add[0] .'
◆ 이메일 주소 : '. $kakao_add[1] .'

감사합니다.';
} else if ($tmp_number == 'TJ_5942') {
    $params['send_code'] = 'TJ_5942';
    $params['send_code_name'] = '차량예약불가(퍼시픽)';
    $params['send_title'] = '괌 퍼시픽 렌트카 차량 예약 불가 안내';
    $params['send_btn_1'] = json_encode(array(
        "button" => array(
            array(
                "name" => "문의하기 바로가기",
                "linkType" => "WL",
                "linkTypeName" => "웹링크",
                "linkMo" => "http://pacificrentcar.kr/notice/inquiry.php",
                "linkPc" => "http://pacificrentcar.kr/notice/inquiry.php",
                "linkIos" => "",
                "linkAnd" => ""
            )
        )
    ), JSON_UNESCAPED_UNICODE);
    $params['send_msg'] =
'[차량 예약 불가]
안녕하세요 괌 퍼시픽 렌트카 입니다.
요청하신 날짜에 차량이 마감이 되어 예약이 불가능합니다.
양해 부탁 드리겠습니다.

입금 해 주신 예약대행수수료는 현금 결제 시 "홈페이지) 고객센터 > 문의하기" 에 예약자명 및 환불받으실 계좌를 올려주시면 환불처리 해 드리겠습니다.
카드결제의 경우 카드승인취소 해 드리겠습니다.

감사합니다.';
} else if ($tmp_number == 'TJ_5944') {
    $params['send_code'] = 'TJ_5944';
    $params['send_code_name'] = '출발확정(퍼시픽)';
    $params['send_title'] = '괌 퍼시픽 렌트카 출발 확정 안내';
    $params['send_btn_1'] = json_encode(array(
        "button" => array(
            array(
                "name" => "나의 예약정보 바로가기",
                "linkType" => "WL",
                "linkTypeName" => "웹링크",
                "linkMo" => "http://pacificrentcar.kr/mypage/mypage.php",
                "linkPc" => "http://pacificrentcar.kr/mypage/mypage.php",
                "linkIos" => "",
                "linkAnd" => ""
            )
        )
    ), JSON_UNESCAPED_UNICODE);
    $params['send_msg'] =
'안녕하세요 괌퍼시픽 렌트카 입니다.

1. 이용전 "확정서"는 필히 확인 부탁 드립니다.
2. 현지 도착 후 차량 관련 문의는 카톡 아이디 "PACIFICRENT" 로 가능 하며 현지 한국인 저희 직원 입니다.
3. 차량 인수 반납 시 차량 외부 및 내부 주유량은 필히 사진 또는 동영상 촬영 후 보관 부탁 드리겠습니다.
4. 한국 운전면허증원본. 카드( 비자 마스터 아멕스), 여권 필히 준비 해 주세요.
5. 괌 퍼시픽 렌터카와 즐거운 괌 여행 되세요.

감사합니다.';
} else if ($tmp_number == 'TM_7935') {
    $params['send_code'] = 'TM_7935';
    $params['send_code_name'] = '대행수수료 미입금 (TM_7935)';
    $params['send_title'] = '괌 퍼시픽 대행수수료 미입금 취소 안내';
    $params['send_btn_1'] = json_encode(array(
        "button" => array(
            array(
                "name" => "홈페이지 바로가기",
                "linkType" => "WL",
                "linkTypeName" => "웹링크",
                "linkMo" => "http://www.pacificrentcar.kr/",
                "linkPc" => "http://www.pacificrentcar.kr/",
                "linkIos" => "",
                "linkAnd" => ""
            )
        )
    ), JSON_UNESCAPED_UNICODE);
    $params['send_msg'] =
'[대행수수료 미입금]

요청하신 차량은 대행수수료 미입금(결제)으로 차량이 취소되었습니다.

차량예약을 원하시면 재 예약 후 대행 수수료 입금 부탁드립니다.

감사합니다.';
}

        //Kakao 발송로그 저장
        $sql = "
                INSERT INTO sms_send_log (
                    usr_id, section, send_type, send_msg,
                    recipient_name, recipient_tel, sender_name, sender_tel,
                    send_gubun, reserve_date, send_date, up_file, item_etc,
                    status, status_memo, del_flag, reg_ip, reg_id, reg_date
                ) VALUES (
                    'non-member', '". $params['send_code'] ."', 'KAKAO', '". $params['send_msg'] ."',
                    '". $params['rec_name'] ."', '". $params['rec_tel'] ."', '". $params['sender_name'] ."', '". $params['sender_tel'] ."',
                    'I', NOW(), NULL, '', '',
                    0, '', 'N', INET_ATON('". NOW_IP ."'), '". $params['reg_id'] ."', NOW()
                );
            ";
        $this->db->insert($sql);

        $params['idx'] = $this->db->getLastInsertId();

        //카카오 알리톡 API 토큰키 발행
        $oCurl = curl_init();
        curl_setopt($oCurl, CURLOPT_PORT, "https:");
        curl_setopt($oCurl, CURLOPT_URL, "https://kakaoapi.aligo.in/akv10/token/create/30/s/");
        curl_setopt($oCurl, CURLOPT_POST, 1);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS, array(
                "apikey" => "cc2rs9ywlv8e56id3yxco1g75oayd1d1",
                "userid" => "jotour"
            ));
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, true);
            $ret = curl_exec($oCurl);
        curl_close($oCurl);

        $result = json_decode($ret, true);

        if ($result['code'] == '0') {
            //카카오 알림톡 API 연동
            $sms_url            = "https://kakaoapi.aligo.in/akv10/alimtalk/send/"; // 전송요청 URL
            $host_info          = explode("/", $sms_url);
            $port               = $host_info[0] == 'https:' ? 443 : 80;
            $sms['apikey']      = "cc2rs9ywlv8e56id3yxco1g75oayd1d1";   //인증용 API Key
            $sms['userid']      = "jotour";   //사용자id
            $sms['token']       = $result['token'];   //생성한 토큰
            $sms['senderkey']   = "c6c5fc02a2fee6f4c00a9b71bd764bbef548df6f";   //발신프로파일 키
            $sms['tpl_code']    = $params['send_code'];     //템플릿 코드
            $sms['sender']      = $params['sender_tel'];    //발신자 연락처
            for ($i=0; $i<count($kakao_rec); $i++) {
                $sms['receiver_'. ($i+1)]  = $kakao_rec[$i]['phone'];  //수신자 연락처
                $sms['recvname_'. ($i+1)]  = $kakao_rec[$i]['name'];   //수신자 이름
                $sms['subject_'. ($i+1)]   = $params['send_title'];    //알림톡 제목
                $sms['message_'. ($i+1)]   = $params['send_msg'];      //알림톡 내용
                $sms['message_'. ($i+1)]   = str_replace("#{고객명}", $sms['recvname_'. ($i+1)], $sms['message_'. ($i+1)]);
                $sms['message_'. ($i+1)]   = str_replace("#{휴대폰번호}", $sms['receiver_'. ($i+1)], $sms['message_'. ($i+1)]);
                $sms['fsubject_'. ($i+1)]  = $params['send_title'];    //실패시 대체문자 제목
                $sms['fmessage_'. ($i+1)]  = $params['send_msg'];      //실패시 대체문자 내용
                $sms['fmessage_'. ($i+1)]   = str_replace("#{고객명}", $sms['recvname_'. ($i+1)], $sms['fmessage_'. ($i+1)]);
                $sms['fmessage_'. ($i+1)]   = str_replace("#{휴대폰번호}", $sms['receiver_'. ($i+1)], $sms['fmessage_'. ($i+1)]);
            }
            if (!chkBlank($params['send_btn_1'])) {
                $sms['button_1'] = $params['send_btn_1'];
            }
            $sms['failover']    = "Y"; //실패시 대체문자 전송기능
            $sms['testMode']    = "N"; //테스트 모드 적용여부 (Y or N)

            $oCurl = curl_init();
            curl_setopt($oCurl, CURLOPT_PORT, $port);
            curl_setopt($oCurl, CURLOPT_URL, $sms_url);
            curl_setopt($oCurl, CURLOPT_POST, 1);
            curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($oCurl, CURLOPT_POSTFIELDS, $sms);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, true);
                $ret = curl_exec($oCurl);
            curl_close($oCurl);

            $result = json_decode($ret, true);
        }

		//발송 DB저장
        if ($result['code'] == '0') {
            $status_memo = "성공 [". $result['message'] ."]";

            //문자 발송 성공시
            $sql = "
                    UPDATE sms_send_log SET
                        status=1,
                        status_memo='$status_memo',
                        send_date=NOW(),
                        upt_ip = INET_ATON('". $params['reg_ip'] ."'),
                        upt_id = '". $params['reg_id'] ."'
                    WHERE idx='". $params['idx'] ."'
                ";
        } else {
            $status_memo = "발송실패 [". $result['message'] ."]";

            //문자 발송 실패시
            $sql = "
                    UPDATE sms_send_log SET
                        status=3,
                        status_memo='$status_memo',
                        upt_ip = INET_ATON('". $params['reg_ip'] ."'),
                        upt_id = '". $params['reg_id'] ."'
                    WHERE idx='". $params['idx'] ."'
                ";
        }

		if (!$this->db->update($sql)) {
			return false;
		} else {
			return true;
		}
    }
}