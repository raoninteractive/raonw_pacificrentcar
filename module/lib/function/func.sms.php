<?
	//카카오 알림톡 템플릿
	$CONT_SMS_TEMPLATE = array(
		array(
			'code'=>'0001',
			'title'=>'예약취소 안내',
			'tmpl_key'=>'17936',
            'message'=>'[예약취소 안내]
			안녕하세요. 토요타 렌터카입니다.

			고객님께서 예약정보가 취소되었습니다.

			■ 고객명 : [고객명]
			■ 여행지 : [추가정보1]
			■ 예약번호 : [추가정보2]
			■ 취소일시 : [추가정보3]

			감사합니다.'
		),
		array(
			'code'=>'0002',
			'title'=>'예약확정 안내',
			'tmpl_key'=>'17934',
            'message'=>'[예약확정 안내]
			안녕하세요. 토요타 렌터카입니다.

			고객님의 예약정보가 아래와 같이 확정되었습니다.

			■ 고객명 : [고객명]
			■ 여행지 : [추가정보1]
			■ 예약번호 : [추가정보2]
			■ 확정서번호 : [추가정보3]

			감사합니다.'
		),
		array(
			'code'=>'0003',
			'title'=>'결제완료',
			'tmpl_key'=>'17931',
            'message'=>'[결제완료 안내]
			안녕하세요. 토요타 렌터카입니다.

			온라인 예약 대행수수료 결제가 완료되었습니다.

			■ 고객명 : [고객명]
			■ 여행지 : [추가정보1]
			■ 예약번호 : [추가정보2]
			■ 결제일자 : [추가정보3]
			■ 결제금액 : [추가정보4]

			감사합니다.'
		),
		array(
			'code'=>'0004',
			'title'=>'예약접수',
			'tmpl_key'=>'17929',
            'message'=>'[예약접수 안내]
			안녕하세요. 토요타 렌터카입니다.

			고객님께서 등록하신 예약정보가 아래와 같이 접수되었습니다.

			■ 고객명 : [고객명]
			■ 여행지 : [추가정보1]
			■ 예약번호 : [추가정보2]

			홈페이지의 예약확인 화면에서 접수결과를 확인할 수 있습니다.

			감사합니다.'
		),
		array(
			'code'=>'0005',
			'title'=>'문의접수 답변',
			'tmpl_key'=>'17928',
            'message'=>'[문의접수 답변안내]
			안녕하세요. 토요타 렌터카입니다.

			고객님이 작성하신 문의사항에 대한 답변이 메일로 발송되었습니다.

			■ 고객명 : [고객명]
			■ 여행지 : [추가정보1]
			■ 이메일주소 : [추가정보2]

			감사합니다.'
		)
    );

    function getSmsTemplateList() {
        global $CONT_SMS_TEMPLATE;

        return $CONT_SMS_TEMPLATE;
    }

    function getSmsTemplateName($code, $item='message') {
        global $CONT_SMS_TEMPLATE;

		for ($i=0; $i<count($CONT_SMS_TEMPLATE); $i++) {
			if ($CONT_SMS_TEMPLATE[$i]['code'] == $code || $CONT_SMS_TEMPLATE[$i]['title'] == $code) {
				return $CONT_SMS_TEMPLATE[$i][$item];
			}
		}

        return "";
    }



	//알림톡 발송
	function smsKakaoSend($tmp_number, $kakao_rec, $kakao_add = null, $sender="") {
		global $CONST_SMS_SENDER;

		// tmp_number : 오렌지메세지 사이트에서 템플릿번호를 확인하시고 입력해주세요.
		// kakao_sender : 오렌지메세지 사이트에서 등록하신 발신번호를 넣어주세요. ( 하이픈까지 일치해야 합니다 )
		// kakao_name : 받으시는 분의 고객명
		// kakao_phone : 받으시는 분 휴대폰번호

		$kakao_sender  = $sender;
		if (chkBlank($kakao_sender)) $kakao_sender = $CONST_SMS_SENDER[0];
		$kakao_sender = str_replace("-", "", $kakao_sender);


		$kakao_080         = "N";   // 대체문자발송시 080 무료수신거부를 사용하시는 경우에는 Y
		$kakao_res         = "" ;   // 예약발송인 경우에는 Y
		$kakao_res_date    = "" ;   // 예약인 경우에만 필요, 예) 2017-12-24 07:08:09
		$TRAN_REPLACE_TYPE = "S" ;  // 알림톡 실패시 대체문자 발송 ( 공백:미발송, S : SMS로 발송, L : LMS로 발송 )

		// 추가정보 1~10 에 대한 값이 필요하신 경우 값을 넣어주세요
		$kakao_add1     = "" ;
		$kakao_add2     = "" ;
		$kakao_add3     = "" ;
		$kakao_add4     = "" ;
		$kakao_add5     = "" ;
		$kakao_add6     = "" ;
		$kakao_add7     = "" ;
		$kakao_add8     = "" ;
		$kakao_add9     = "" ;
		$kakao_add10    = "" ;

		// url에 변수를 사용하시는 경우에는 주석을 풀어주세요.
		// 값은 별수를 제외하고는 템플릿과 일치해야 합니다.)
		/*
		$kakao_url1_1   = "" ;  // 모바일링크 또는 ios 링크
		$kakao_url1_2   = "" ;  // pc링크 또는 안드로이드 링크

		$kakao_url2_1   = "" ;  // 모바일링크 또는 ios 링크
		$kakao_url2_2   = "" ;  // pc링크 또는 안드로이드 링크

		$kakao_url3_1   = "" ;  // 모바일링크 또는 ios 링크
		$kakao_url3_2   = "" ;  // pc링크 또는 안드로이드 링크

		$kakao_url4_1   = "" ;  // 모바일링크 또는 ios 링크
		$kakao_url4_2   = "" ;  // pc링크 또는 안드로이드 링크

		$kakao_url5_1   = "" ;  // 모바일링크 또는 ios 링크
		$kakao_url5_2   = "" ;  // pc링크 또는 안드로이드 링크
		*/

		// Authorization 값은 오렌지메세지 사이트에서 발급받으신 키 값을 넣어주세요.
		$headers = array(
				"Content-Type: application/json; charset=utf-8",
				"Authorization: +0yV0856TYvLkRL+OITmmmyH/yR5TYYgCETntgESApY="
		);

		$parameters = array(
				"tmp_number" => $tmp_number,
				"kakao_sender" => $kakao_sender,
				"total_ea" => count($kakao_rec),
				"kakao_080" => $kakao_080,
				"kakao_res" => $kakao_res,
				"kakao_res_date" => $kakao_res_date,
				"TRAN_REPLACE_TYPE" => $TRAN_REPLACE_TYPE,
				"data" => array()
		);

		for ($i=0; $i<count($kakao_rec); $i++) {
			array_push($parameters['data'], array(
				"kakao_name" => $kakao_rec[$i]['name'],
				"kakao_phone" => str_replace("-", "", $kakao_rec[$i]['phone'])
			));


			for ($k=0; $k<count($kakao_add); $k++) {
				$parameters['data'][$i]["kakao_add".($k+1)] = $kakao_add[$k];
			}
		}

		//print_r($parameters);
		//exit;

		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, "https://www.apiorange.com/api/group/notice.do");
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($parameters));
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_NOSIGNAL, true);
		curl_setopt($curl, CURLOPT_VERBOSE, false);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

		$response = curl_exec($curl);
	}


	function smsSend($params) {
		global $CONST_SMS_SENDER;

		$db = new DB_HELPER;

		$params['rec_tel'] = str_replace("-", "", $params['rec_tel']);

		//SMS 전송 API


		//발송 DB저장
		$sql = "
					INSERT INTO sms_send_log (
						section, send_msg,
						recipient_name, recipient_tel,
						sender_name, sender_tel,
						reg_ip, reg_date
					) VALUES (
						'". $params['section'] ."', '". $params['send_msg'] ."',
						'". $params['rec_name'] ."', '". $params['rec_tel'] ."',
						'". SITE_NAME ."', '". $CONST_SMS_SENDER[0] ."',
						'". $params['reg_ip'] ."', NOW()
					)
				";
		return $db->insert($sql);
	}
?>