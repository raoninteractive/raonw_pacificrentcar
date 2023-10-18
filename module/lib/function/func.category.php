<?
	//상품분류
	$CONT_GOODS_CATE = array(
		array(
			'code'=>'C001',
			'name'=>'괌(GUAM)'
		)
    );

    function getGoodsCateList() {
        global $CONT_GOODS_CATE;

        return $CONT_GOODS_CATE;
    }

    function getGoodsCateName($code, $item='name') {
        global $CONT_GOODS_CATE;

        for ($i=0; $i<count($CONT_GOODS_CATE); $i++) {
            if ($CONT_GOODS_CATE[$i]['code'] == $code) {
                return $CONT_GOODS_CATE[$i][$item];
            }
        }

        return "";
    }

    //예약상태
	$CONT_RESV_STATUS = array(
		array(
			'code'=>'10',
            'name'=>'예약접수',
            'name2'=>'예약접수'
        ),
        array(
			'code'=>'11',
            'name'=>'예약접수 취소요청',
            'name2'=>'예약접수 취소요청'
        ),
        array(
			'code'=>'12',
            'name'=>'예약접수 취소완료',
            'name2'=>'예약접수 취소완료'
        ),
		array(
			'code'=>'20',
            'name'=>'결제대기',
            'name2'=>'결제대기(접수완료)'
        ),
		array(
			'code'=>'23',
            'name'=>'결제대기',
            'name2'=>'결제대기(입금확인요청)'
        ),
        array(
			'code'=>'21',
            'name'=>'결제대기 취소요청',
            'name2'=>'결제대기 취소요청(결제대기)'
        ),
        array(
			'code'=>'24',
            'name'=>'결제대기 취소완료',
            'name2'=>'결제대기 취소완료(미입금취소)'
        ),
        array(
			'code'=>'22',
            'name'=>'결제대기 취소완료',
            'name2'=>'결제대기 취소완료(접수취소)'
        ),
		array(
			'code'=>'30',
            'name'=>'예약대기',
            'name2'=>'예약대기(결제완료)'
        ),
        array(
			'code'=>'31',
            'name'=>'예약대기 취소요청',
            'name2'=>'예약대기 취소요청(환불대기)'
        ),
        array(
			'code'=>'32',
            'name'=>'예약대기 취소완료',
            'name2'=>'예약대기 취소완료(환불완료)'
		),
		array(
			'code'=>'40',
            'name'=>'예약확정',
            'name2'=>'예약확정'
        ),
        array(
			'code'=>'43',
            'name'=>'예약불가',
            'name2'=>'예약불가(환불예정)'
		),
        array(
			'code'=>'44',
            'name'=>'예약불가',
            'name2'=>'예약불가(환불완료)'
		),
		array(
			'code'=>'41',
            'name'=>'예약확정 취소요청',
            'name2'=>'예약확정 취소요청(환불대기)'
        ),
        array(
			'code'=>'42',
            'name'=>'예약확정 취소완료',
            'name2'=>'예약확정 취소완료(환불완료)'
		),
		array(
			'code'=>'50',
            'name'=>'출발확정',
            'name2'=>'출발확정'
        ),
        array(
			'code'=>'51',
            'name'=>'출발확정 취소요청',
            'name2'=>'출발확정 취소요청(환불대기)'
        ),
		array(
			'code'=>'52',
            'name'=>'출발확정',
            'name2'=>'출발확정 취소완료(환불완료)'
        )
    );

    function getResvStatusList() {
        global $CONT_RESV_STATUS;

        return $CONT_RESV_STATUS;
    }

    function getResvStatusName($code, $item='name') {
        global $CONT_RESV_STATUS;

        for ($i=0; $i<count($CONT_RESV_STATUS); $i++) {
            if ($CONT_RESV_STATUS[$i]['code'] == $code) {
                return $CONT_RESV_STATUS[$i][$item];
            }
        }

        return "";
    }

    //결제상태
	$CONT_PAY_STATUS = array(
        array(
			'code'=>'10',
            'name'=>'입급대기'
        ),
        array(
			'code'=>'20',
            'name'=>'결제완료'
        ),
        array(
			'code'=>'30',
            'name'=>'결제취소'
        ),
        array(
			'code'=>'40',
            'name'=>'결제환불'
		)
    );

    function getPayStatusList() {
        global $CONT_PAY_STATUS;

        return $CONT_PAY_STATUS;
    }

    function getPayStatusName($code, $item='name') {
        global $CONT_PAY_STATUS;

        for ($i=0; $i<count($CONT_PAY_STATUS); $i++) {
            if ($CONT_PAY_STATUS[$i]['code'] == $code) {
                return $CONT_PAY_STATUS[$i][$item];
            }
        }

        return "";
    }


    //카시트 무료개수
    $CONST_CAR_SEAT_FREE = 2;

    //카시트
    $CONST_CAR_SEAT = array(
        array(
            "code"=>"C001",
            "name"=>"신생아용 카시트(12개월 미만)"
        ),
        array(
            "code"=>"C002",
            "name"=>"아동용 카시트(12~24개월)"
        ),
        array(
            "code"=>"C003",
            "name"=>"부스터 카시트(24개월 이상)"
        )
    );

    function getCarSeatList() {
        global $CONST_CAR_SEAT;

        return $CONST_CAR_SEAT;
    }

    function getCarSeatName($code, $item='name') {
        global $CONST_CAR_SEAT;

        for ($i=0; $i<count($CONST_CAR_SEAT); $i++) {
            if ($CONST_CAR_SEAT[$i]['code'] == $code) {
                return $CONST_CAR_SEAT[$i][$item];
            }
        }

        return "";
    }


    //괌,사이판 예약관련 카테고리 불러오기(인수/픽업 장소, 반납장소, 호텔, 항공사)
    function getBookingSettingInfoList($gubun, $name) {
        $db = new DB_HELPER;

        $sql = "SELECT * FROM setting_info";
        $view = $db->getQueryValue($sql);

        $result = array();
        if ($gubun == 'C001') {
            $result = $view['guam_'. $name];
            $result = explode("\n", $result);
        } else {
            $result = $view['saipan_'. $name];
            $result = explode("\n", $result);
        }

        return $result;
    }

    //괌,사이판 예약관련 카테고리 불러오기(인수/픽업 장소, 반납장소, 호텔, 항공사, 확정서 안내 사항)
    function getBookingSettingInfoView($gubun) {
        $db = new DB_HELPER;

        $sql = "SELECT * FROM setting_info";
        $view = $db->getQueryValue($sql);

        $result = array();
        if ($gubun == 'C001') {
            $result = array(
                'pickup_area'=>explode("\n", $view['guam_pickup_area']),
                'return_area'=>explode("\n", $view['guam_return_area']),
                'hotel'=>explode("\n", $view['guam_hotel']),
                'out_airline'=>explode("\n", $view['guam_out_airline']),
                'in_airline'=>explode("\n", $view['guam_in_airline']),
                'guide_notice'=>$view['guam_guide_notice'],
                'choice_pickup'=>explode("\n", $view['guam_choice_pickup'])
            );
        } else {
            $result = array(
                'pickup_area'=>explode("\n", $view['saipan_pickup_area']),
                'return_area'=>explode("\n", $view['saipan_return_area']),
                'hotel'=>explode("\n", $view['saipan_hotel']),
                'out_airline'=>explode("\n", $view['saipan_out_airline']),
                'in_airline'=>explode("\n", $view['saipan_in_airline']),
                'guide_notice'=>$view['saipan_guide_notice'],
                'choice_pickup'=>explode("\n", $view['saipan_choice_pickup'])
            );
        }

        return $result;
    }


	//확정서 상태
	$CONT_CONFIRM_CATE = array(
		array(
			'code'=>'10',
			'name'=>'발급대기'
		),
		array(
			'code'=>'20',
			'name'=>'발행준비중'
        ),
		array(
			'code'=>'30',
			'name'=>'발행완료'
		)
    );

    function getConfirmCateList() {
        global $CONT_CONFIRM_CATE;

        return $CONT_CONFIRM_CATE;
    }

    function getConfirmCateName($code, $item='name') {
        global $CONT_CONFIRM_CATE;

        for ($i=0; $i<count($CONT_CONFIRM_CATE); $i++) {
            if ($CONT_CONFIRM_CATE[$i]['code'] == $code) {
                return $CONT_CONFIRM_CATE[$i][$item];
            }
        }

        return "";
    }