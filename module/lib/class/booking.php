<?php
/**
 * 예약관리
 */

class CLS_BOOKING
{
	private $db;

	function __construct()
	{
		$this->db = new DB_HELPER;
	}

	//예약접수 정보 저장
	public function booking_save_proc($params) {
		$this->db->beginTrans();

		$sql = "
				INSERT INTO booking (
					booking_num, goods_idx, goods_category, goods_title, goods_options,
                    goods_rent_day1_amt, goods_rent_day7_amt, goods_rent_day30_amt, goods_car_seat_amt,
					out_date, out_airline, in_date, in_airline, hotel,
					rental_sdate, rental_edate, rental_time, rental_day, rental_amt,
					pickup_area, return_area,
					name, eng_name1, eng_name2, phone, email,
					adult_cnt, child_cnt, infant_cnt,
					infant_seat_cnt, infant_seat_amt, child_seat_cnt, child_seat_amt,
					booster_seat_cnt, booster_seat_amt, total_seat_amt, seat_free_cnt,
					add_option_1, add_option_1_amt, add_option_1_flag,
					add_option_2, add_option_2_amt, add_option_2_flag,
					airport_meeting, airport_meeting_amt, airport_meeting_flag,
                    airport_car_return_amt, airport_car_return_flag,
					booking_memo,
					total_rental_amt, total_add_amt, booking_agency_fee,
					status, confirm_status, confirm_num,
					del_flag, reg_ip, reg_id, reg_date,
					driver_name1, driver_name_eng1, driver_home_addr1, driver_local_addr1,
					driver_phone1, driver_birthdate1, driver_license1, driver_license_expiry_date1,
					driver_name2, driver_name_eng2, driver_home_addr2, driver_local_addr2,
					driver_phone2, driver_birthdate2, driver_license2, driver_license_expiry_date2,
                    local_send_email_flag
				) VALUES (
					'". $params['booking_num'] ."', '". $params['goods_idx'] ."', '". $params['goods_category'] ."', '". $params['goods_title'] ."', '". $params['goods_options'] ."',
                    '". $params['goods_rent_day1_amt'] ."', '". $params['goods_rent_day7_amt'] ."', '". $params['goods_rent_day30_amt'] ."', '". $params['goods_car_seat_amt'] ."',
					'". $params['out_date'] ."', '". $params['out_airline'] ."', '". $params['in_date'] ."', '". $params['in_airline'] ."', '". $params['hotel'] ."',
					'". $params['rental_sdate'] ."', '". $params['rental_edate'] ."', '". $params['rental_time'] ."', '". $params['rental_day'] ."', '". $params['rental_amt'] ."',
					'". $params['pickup_area'] ."', '". $params['return_area'] ."',
					'". $params['name'] ."', '". $params['eng_name1'] ."', '". $params['eng_name2'] ."', '". $params['phone'] ."', '". $params['email'] ."',
					'". $params['adult_cnt'] ."', '". $params['child_cnt'] ."', '". $params['infant_cnt'] ."',
					'". $params['infant_seat_cnt'] ."', '". $params['infant_seat_amt'] ."', '". $params['child_seat_cnt'] ."', '". $params['child_seat_amt'] ."',
					'". $params['booster_seat_cnt'] ."', '". $params['booster_seat_amt'] ."', '". $params['total_seat_amt'] ."', '". $params['seat_free_cnt'] ."',
					'". $params['add_option_1'] ."', '". $params['add_option_1_amt'] ."', '". $params['add_option_1_flag'] ."',
					'". $params['add_option_2'] ."', '". $params['add_option_2_amt'] ."', '". $params['add_option_2_flag'] ."',
					'". $params['airport_meeting'] ."', '". $params['airport_meeting_amt'] ."', '". $params['airport_meeting_flag'] ."',
					'". $params['airport_car_return_amt'] ."', '". $params['airport_car_return_flag'] ."',
					'". $params['booking_memo'] ."',
					'". $params['total_rental_amt'] ."', '0', '". $params['booking_agency_fee'] ."',
					'20', '10', NULL,
					'N', '". $params['reg_ip'] ."', '". $params['reg_id'] ."', NOW(),
					'". $params['driver_name1'] ."', '". $params['driver_name_eng1'] ."', '". $params['driver_home_addr1'] ."', '". $params['driver_local_addr1'] ."',
					'". $params['driver_phone1'] ."', '". $params['driver_birthdate1'] ."', '". $params['driver_license1'] ."', '". $params['driver_license_expiry_date1'] ."',
					'". $params['driver_name2'] ."', '". $params['driver_name_eng2'] ."', '". $params['driver_home_addr2'] ."', '". $params['driver_local_addr2'] ."',
					'". $params['driver_phone2'] ."', '". $params['driver_birthdate2'] ."', '". $params['driver_license2'] ."', '". $params['driver_license_expiry_date2'] ."',
                    'N'
				)
			";
		$result = $this->db->insert($sql);
		if ($result == false) {
			$this->db->rollbackTrans();

			return false;
		}

		$booking_idx = $this->db->getLastInsertId();

		//출발일 재고 업데이트
		$sql = "
				UPDATE goods_stock SET
					stock_cnt = stock_cnt - 1
				WHERE goods_idx='". $params['goods_idx'] ."' AND sdate='". $params['rental_sdate'] ."'
			";
		$result = $this->db->update($sql);

		if ($result == false) {
			$this->db->rollbackTrans();
		} else {
			$this->db->commitTrans();

			adminMemoSystemSave('booking_view', $booking_idx, '사용자가 예약접수를 하였습니다.');
		}

		return $result;
	}

    //예약접수 정보 수정
    public function booking_modify_proc($params) {
        $this->db->beginTrans();

        //예약정보 불러오기
        $booking_view = $this->booking_view($params['booking_idx']);
        if ($booking_view == false) {
            $this->db->rollbackTrans();
            return false;
        }

        //Airport(공항픽업) -> 호텔 변경시 공항픽업 추가,할인 내역 삭제
        if ($params['airport_meeting'] == "N") {
            $sql = "DELETE FROM booking_add_amount WHERE booking_idx = '". $booking_view['idx'] ."' AND content LIKE '%공항픽업%'";
            if ($this->db->delete($sql) == false) {
                $this->db->rollbackTrans();

                return false;
            } else {
                //추가,할인 총 합산 업데이트
                $sql = "
                        UPDATE booking SET
                            total_add_amt = (SELECT IFNULL(SUM(amount),0) FROM booking_add_amount WHERE booking_idx='". $booking_view['idx'] ."'),
                            upt_ip = '". $params['upt_ip'] ."',
                            upt_id = '". $params['upt_id'] ."',
                            upt_date = NOW()
                        WHERE idx='". $booking_view['idx'] ."'
                    ";
                if ($this->db->update($sql) == false) {
                    $this->db->rollbackTrans();

                    return false;
                }
            }
        }

        $sql = "
                UPDATE booking SET
                    out_date                = '". $params['out_date'] ."',
                    out_airline             = '". $params['out_airline'] ."',
                    hotel                   = '". $params['hotel'] ."',
                    rental_sdate            = '". $params['rental_sdate'] ."',
                    rental_edate            = '". $params['rental_edate'] ."',
                    rental_time             = '". $params['rental_time'] ."',
                    rental_day              = '". $params['rental_day'] ."',
                    rental_amt              = '". $params['rental_amt'] ."',
                    pickup_area             = '". $params['pickup_area'] ."',
                    return_area             = '". $params['return_area'] ."',
                    infant_seat_cnt         = '". $params['infant_seat_cnt'] ."',
                    infant_seat_amt         = '". $params['infant_seat_amt'] ."',
                    child_seat_cnt          = '". $params['child_seat_cnt'] ."',
                    child_seat_amt          = '". $params['child_seat_amt'] ."',
                    booster_seat_cnt        = '". $params['booster_seat_cnt'] ."',
                    booster_seat_amt        = '". $params['booster_seat_amt'] ."',
                    total_seat_amt          = '". $params['total_seat_amt'] ."',
                    airport_meeting         = '". $params['airport_meeting'] ."',
                    airport_meeting_flag    = '". $params['airport_meeting_flag'] ."',
                    airport_car_return_amt  = '". $params['airport_car_return_amt'] ."',
                    airport_car_return_flag = '". $params['airport_car_return_flag'] ."',
                    total_rental_amt        = '". $params['total_rental_amt'] ."',
                    local_send_email_flag   = '". $params['local_send_email_flag'] ."',
                    upt_ip                  = '". $params['upt_ip'] ."',
                    upt_id                  = '". $params['upt_id'] ."',
                    upt_date                = NOW()
                WHERE idx='". $params['booking_idx'] ."'
            ";
        if ($this->db->update($sql) == false) {
            $this->db->rollbackTrans();

            return false;
        }

        //렌트기간 변경이 있을경우 재고 변경
        if ($booking_view['rental_sdate'] != $params['rental_sdate']) {
            //이전재고 원복
            $sql = "
                    UPDATE goods_stock SET
                        stock_cnt = stock_cnt + 1
                    WHERE goods_idx='". $booking_view['goods_idx'] ."' AND sdate='". $booking_view['rental_sdate'] ."'
                ";
            if ($this->db->update($sql) == false) {
                $this->db->rollbackTrans();

                return false;
            }

            //변경재고 처리
            $sql = "
                    UPDATE goods_stock SET
                        stock_cnt = stock_cnt - 1
                    WHERE goods_idx='". $booking_view['goods_idx'] ."' AND sdate='". $params['rental_sdate'] ."'
                ";
            if ($this->db->update($sql) == false) {
                $this->db->rollbackTrans();

                return false;
            }
        }

        $admin_memo = "";
        if (!chkBlank($params['out_date']) && $params['out_date'] != $booking_view['out_date']) {
            $admin_memo .= "[출국일: ". $booking_view['out_date'] ." → ". $params['out_date'] ."]";
        }
        if (!chkBlank($params['out_airline']) && $params['out_airline'] != $booking_view['out_airline']) {
            $admin_memo .= "[출국 항공편: ". $booking_view['out_airline'] ." → ". $params['out_airline'] ."]";
        }
        if (!chkBlank($params['hotel']) && $params['hotel'] != $booking_view['hotel']) {
            $admin_memo .= "[투숙호텔: ". $booking_view['hotel'] ." → ". $params['hotel'] ."]";
        }
        if (!chkBlank($params['rental_sdate']) && $params['rental_sdate'] != $booking_view['rental_sdate']) {
            $admin_memo .= "[수령일: ". $booking_view['rental_sdate'] ." → ". $params['rental_sdate'] ."]";
        }
        if (!chkBlank($params['rental_time']) && $params['rental_time'] != $booking_view['rental_time']) {
            $admin_memo .= "[수령시간: ". $booking_view['rental_time'] ." → ". $params['rental_time'] ."]";
        }
        if (!chkBlank($params['rental_edate']) && $params['rental_edate'] != $booking_view['rental_edate']) {
            $admin_memo .= "[반납일: ". $booking_view['rental_edate'] ." → ". $params['rental_edate'] ."]";
        }
        if (!chkBlank($params['rental_day']) && $params['rental_day'] != $booking_view['rental_day']) {
            $admin_memo .= "[렌트기간: ". $booking_view['rental_day'] ."일 → ". $params['rental_day'] ."일]";
        }
        if (!chkBlank($params['rental_amt']) && $params['rental_amt'] != $booking_view['rental_amt']) {
            $admin_memo .= "[렌트비용: ". $booking_view['rental_amt'] ."일 → ". $params['rental_amt'] ."일]";
        }
        if (!chkBlank($params['pickup_area']) && $params['pickup_area'] != $booking_view['pickup_area']) {
            $admin_memo .= "[인수/픽업 장소: ". $booking_view['pickup_area'] ." → ". $params['pickup_area'] ."]";
        }
        if (!chkBlank($params['return_area']) && $params['return_area'] != $booking_view['return_area']) {
            $admin_memo .= "[차량반납 장소: ". $booking_view['return_area'] ." → ". $params['return_area'] ."]";
        }
        if (!chkBlank($params['infant_seat_cnt']) && $params['infant_seat_cnt'] != $booking_view['infant_seat_cnt']) {
            $admin_memo .= "[유아 보조시트: ". $booking_view['infant_seat_cnt'] ." → ". $params['infant_seat_cnt'] ."]";
        }
        if (!chkBlank($params['infant_seat_amt']) && $params['infant_seat_amt'] != $booking_view['infant_seat_amt']) {
            $admin_memo .= "[유아 보조시트 비용: ". $booking_view['infant_seat_amt'] ." → ". $params['infant_seat_amt'] ."]";
        }
        if (!chkBlank($params['child_seat_cnt']) && $params['child_seat_cnt'] != $booking_view['child_seat_cnt']) {
            $admin_memo .= "[어린이 보조시트: ". $booking_view['child_seat_cnt'] ." → ". $params['child_seat_cnt'] ."]";
        }
        if (!chkBlank($params['child_seat_amt']) && $params['child_seat_amt'] != $booking_view['child_seat_amt']) {
            $admin_memo .= "[어린이 보조시트 비용: ". $booking_view['child_seat_amt'] ." → ". $params['child_seat_amt'] ."]";
        }
        if (!chkBlank($params['booster_seat_cnt']) && $params['booster_seat_cnt'] != $booking_view['booster_seat_cnt']) {
            $admin_memo .= "[부스터 시트: ". $booking_view['booster_seat_cnt'] ." → ". $params['booster_seat_cnt'] ."]";
        }
        if (!chkBlank($params['booster_seat_amt']) && $params['booster_seat_amt'] != $booking_view['booster_seat_amt']) {
            $admin_memo .= "[부스터 시트 비용: ". $booking_view['booster_seat_amt'] ." → ". $params['booster_seat_amt'] ."]";
        }
        if (!chkBlank($params['total_seat_amt']) && $params['total_seat_amt'] != $booking_view['total_seat_amt']) {
            $admin_memo .= "[총 아동 보조 시트 비용: ". $booking_view['total_seat_amt'] ." → ". $params['total_seat_amt'] ."]";
        }
        if (!chkBlank($params['total_rental_amt']) && $params['total_rental_amt'] != $booking_view['total_rental_amt']) {
            $admin_memo .= "[총 렌탈비용: ". $booking_view['total_rental_amt'] ." → ". $params['total_rental_amt'] ."]";
        }
        if (!chkBlank($params['local_send_email_flag']) && $params['local_send_email_flag'] != $booking_view['local_send_email_flag']) {
            $admin_memo .= "[현지 메일 발송 여부: ". $booking_view['local_send_email_flag'] ." → ". $params['local_send_email_flag'] ."]";
        }


        adminMemoSystemSave('booking_view', $params['booking_idx'], '관리자가 예약접수를 수정 하였습니다.'.$admin_memo);


        $this->db->commitTrans();
        return true;
    }


	//예약접수 상세보기
	public function booking_view($idx=null, $booking_num='', $booker_name='', $booker_phone='', $booker_email='') {
		$sub_sql = "";

		if ($idx != "") {
			$sub_sql .= " AND idx='$idx' ";
		}
		if ($booking_num != "") {
			$sub_sql .= " AND booking_num='$booking_num' ";
		}
		if ($booker_name != "") {
			$sub_sql .= " AND name='$booker_name' ";
		}
		if ($booker_phone != "") {
			$sub_sql .= " AND phone='$booker_phone' ";
		}
		if ($booker_email != "") {
			$sub_sql .= " AND email='$booker_email' ";
		}

		$sql = "
				SELECT
					*
				FROM booking
				WHERE del_flag='N' $sub_sql
				ORDER BY reg_date DESC
			";
		return $this->db->getQueryValue($sql);
	}

	//예약자 리스트
	public function booking_list($params = null, &$total_cnt=0, &$total_page=1) {
		if (chkBlank($params)) return false;

		$sub_sql = "";

		//접수일 검색
		if ($params['sch_sdate'] != "") {
			$sub_sql .= " AND TIMESTAMPDIFF(DAY, DATE_FORMAT(reg_date,'%Y-%m-%d'), '". $params['sch_sdate'] ."') <= 0";
		}

		//접수일 검색
		if ($params['sch_edate'] != "") {
			$sub_sql .= " AND TIMESTAMPDIFF(DAY, DATE_FORMAT(reg_date,'%Y-%m-%d'), '". $params['sch_edate'] ."') >= 0";
		}

		//픽업일 검색
		if ($params['sch_picksdate'] != "") {
			$sub_sql .= " AND TIMESTAMPDIFF(DAY, DATE_FORMAT(rental_sdate,'%Y-%m-%d'), '". $params['sch_picksdate'] ."') <= 0";
		}

		//픽업일 검색
		if ($params['sch_pickedate'] != "") {
			$sub_sql .= " AND TIMESTAMPDIFF(DAY, DATE_FORMAT(rental_sdate,'%Y-%m-%d'), '". $params['sch_pickedate'] ."') >= 0";
		}

		//구분 검색
		if ($params['sch_cate'] != "") {
			$sub_sql .= " AND category='". $params['sch_cate'] ."'";
		}

		//상태 검색
		if ($params['sch_status'] != "") {
			$sub_sql .= " AND status='". $params['sch_status'] ."'";
		}

		//검색어 검색
		if ($params['sch_word'] != "") {
			switch ($params['sch_type']) {
				case "1" : $sub_sql .= " AND booking_num LIKE '%". $params['sch_word'] ."%'"; break;
				case "2" : $sub_sql .= " AND confirm_num LIKE '%". $params['sch_word'] ."%'"; break;
				case "3" : $sub_sql .= " AND name LIKE '%". $params['sch_word'] ."%'"; break;
				case "4" : $sub_sql .= " AND phone LIKE '%". $params['sch_word'] ."%'"; break;
				case "5" : $sub_sql .= " AND email LIKE '%". $params['sch_word'] ."%'"; break;
				default : $sub_sql .= " AND (
												booking_num LIKE '%". $params['sch_word'] ."%' OR
												confirm_num LIKE '%". $params['sch_word'] ."%' OR
												name LIKE '%". $params['sch_word'] ."%' OR
												phone LIKE '%". $params['sch_word'] ."%' OR
												email LIKE '%". $params['sch_word'] ."%'
											)
										"; break;
			}
		}

		$sql = "
			SELECT
				*
			FROM booking
			WHERE del_flag='N' $sub_sql
			ORDER BY reg_date DESC
		";

		return $this->db->getList($sql, $params['page'], $params['list_size'], $total_cnt, $total_page);
	}


	//예약상태 업데이트 처리
	public function booking_status_proc($params) {
		$this->db->beginTrans();

		$sql = "
			UPDATE booking SET
				status = '". $params['status'] ."',
				status_change_dt = NOW(),
				upt_ip = '". $params['upt_ip'] ."',
				upt_id = '". $params['upt_id'] ."',
				upt_date = NOW()
			WHERE idx='". $params['booking_idx'] ."'
		";
		$result = $this->db->update($sql);
		if ($result == false) {
			$this->db->rollbackTrans();

			return false;
		}

		//취소완료, 환불완료시 재고상태 업데이트
		if ($params['status'] == '12' || $params['status'] == '22' || $params['status'] == '32' || $params['status'] == '42') {
			//출발일 재고 업데이트
			$sql = "
					UPDATE goods_stock SET
						stock_cnt = stock_cnt + 1
					WHERE goods_idx='". $params['goods_idx'] ."' AND sdate='". $params['rental_sdate'] ."'
				";
			$result = $this->db->update($sql);
		}

		//결제완료 상태
		if ($params['status'] == '30') {
			$sql = "
					UPDATE booking SET
						status = '30',
						status_request_dt = NOW(),
						payment_method = '". $params['payment_method'] ."',
						payment_status = '20',
						payment_status_dt = NOW(),
						payment_req_dt = NOW(),
						payment_dt = NOW(),
						payment_tid = ''
					WHERE idx='". $params['booking_idx'] ."'
				";
			$result = $this->db->update($sql);
		}

		if ($result == false) {
			$this->db->rollbackTrans();
		} else {
			$this->db->commitTrans();

			adminMemoSystemSave('booking_view', $params['booking_idx'], '관리자가 예약정보 상태를 수정 하였습니다.');
		}

		return $result;
	}

	//예약 결제 처리
	public function payment_proc($params) {
		$sql = "
				UPDATE booking SET
                    status = '". iif($params['payment_method']=='BANK', '23', '30') ."',
					status_request_dt = NOW(),
					payment_method = '". $params['payment_method'] ."',
					payment_status = '20',
					payment_status_dt = NOW(),
					payment_req_dt = NOW(),
					payment_dt = NOW(),
					payment_tid = '". $params['payment_tid'] ."'
				WHERE idx='". $params['booking_idx'] ."'
			";

		if ($this->db->update($sql) == false) {
			return false;
		} else {
			adminMemoSystemSave('booking_view', $params['booking_idx'], '사용자가 결제완료 요청하였습니다.');

			return true;
		}
	}

	//추가옵션 상태 업데이트(아이스박스, 네이게이션, 공항픽업)
	public function option_status_proc($params) {
		if ($params['gubun'] == 'icebox') {
			$sql = "UPDATE booking SET add_option_1_flag='". $params['status'] ."' WHERE idx='". $params['booking_idx'] ."'";

			$log_cont = '관리자가 추가선택사항의 아이스박스를 예약불가 상태로 수정 하였습니다.';
			$add_cont = '아이스박스';
		} else if ($params['gubun'] == 'navi') {
			$sql = "UPDATE booking SET add_option_2_flag='". $params['status'] ."' WHERE idx='". $params['booking_idx'] ."'";

			$log_cont = '관리자가 추가선택사항의 네이게이션을 예약불가 상태로 수정 하였습니다.';
			$add_cont = '네비게이션';
		} else if ($params['gubun'] == 'meet') {
			$sql = "UPDATE booking SET airport_meeting_flag='". $params['status'] ."' WHERE idx='". $params['booking_idx'] ."'";

			$log_cont = '관리자가 공항픽업을 예약불가 상태로 수정 하였습니다.';
			$add_cont = '공항픽업';
		} else {
			return false;
		}

		$result = $this->db->update($sql);
		if ($result == false) {
			return false;
		}

        if ($params['status'] == 'Y') {
            $gubun  = 'P';
            $amount = $params['amount'];
            $add_cont = $add_cont.' 확정 추가';
        } else {
            $gubun  = 'M';
            $amount = $params['amount']*-1;
            $add_cont = $add_cont.' 예약불가 할인';
        }

		//추가,할인 내역 등록
		$sql = "
				INSERT INTO booking_add_amount (
					booking_idx, content, gubun, amount,
					reg_ip, reg_id, reg_date
				) VALUES (
					'". $params['booking_idx'] ."', '". $add_cont ."', '". $gubun ."', '". $amount ."',
					'". $params['reg_ip'] ."', '". $params['reg_id'] ."', NOW()
				)
			";
		$result = $this->db->insert($sql);

		if ($result == false) {
			return false;
		} else {
			//추가,할인 총 합산 업데이트
			$sql = "
					UPDATE booking SET
                        total_add_amt = (SELECT IFNULL(SUM(amount),0) FROM booking_add_amount WHERE booking_idx='". $params['booking_idx'] ."'),
						upt_ip = '". $params['upt_ip'] ."',
						upt_id = '". $params['upt_id'] ."',
						upt_date = NOW()
					WHERE idx='". $params['booking_idx'] ."'
				";
			$this->db->update($sql);

			adminMemoSystemSave('booking_view', $params['booking_idx'], $log_cont);

			return true;
		}
	}

	//추가・할인 내역 목록
	public function booking_add_amount_list($booking_idx) {
		$sql = "SELECT * FROM booking_add_amount WHERE booking_idx='$booking_idx' ORDER BY reg_date";

		return $this->db->getQuery($sql);
	}

	//확정서 정보 업데이트
	public function confirm_status_proc($params) {
		$sql = "
				UPDATE booking SET
					confirm_status = '". $params['confirm_status'] ."',
					confirm_num = '". $params['confirm_num'] ."',
					upt_ip = '". $params['upt_ip'] ."',
					upt_id = '". $params['upt_id'] ."',
					upt_date = NOW()
				WHERE idx='". $params['booking_idx'] ."'
			";

		if ($this->db->update($sql) == false) {
			return false;
		} else {
			adminMemoSystemSave('booking_view', $params['booking_idx'], '관리자가 확정서 번호 상태를 수정하였습니다.');

			return true;
		}
	}


	//담당자 안내문 저장
	public function notice_save_proc($params) {
		$sql = "
				UPDATE booking SET
					notice = '". $params['notice'] ."',
					upt_ip = '". $params['upt_ip'] ."',
					upt_id = '". $params['upt_id'] ."',
					upt_date = NOW()
				WHERE idx='". $params['booking_idx'] ."'
			";

		if ($this->db->update($sql) == false) {
			return false;
		} else {
			adminMemoSystemSave('booking_view', $params['booking_idx'], '관리자가 담당자 안내문 저장 하였습니다.');

			return true;
		}
	}

	//예약취소 접수
	public function cancel_req_proc($params) {
		$sql = "
				UPDATE booking SET
					status = status + 1,
					status_change_dt = NOW(),
					upt_ip = '". $params['upt_ip'] ."',
					upt_id = '". $params['upt_id'] ."',
					upt_date = NOW()
				WHERE idx='". $params['booking_idx'] ."'
			";
		$result = $this->db->update($sql);

		if ($result == false) {
			return false;
		} else {
			adminMemoSystemSave('booking_view', $params['booking_idx'], '사용자가 예약취소를 접수 하였습니다.');

			return true;
		}
	}

	//결제 검증정보 저장
	public function payment_verify_proc($receiptId) {
		$bootpayApi = new BootpayApi;

		$bootpay = $bootpayApi::setConfig(
			"60fe4f237b5ba400217bd968",
			"EdWArhKDsmOMJt+krTCrFIbABsB0XodDpMgeUQGHDbg="
		);

		//AccessToken 요청
		$response = $bootpay->requestAccessToken();
		if ($response->status === 200) {
			$token = $response->data->token;
		} else {
			return false;
		}

		//$response = $bootpay->requestAccessToken();

		//Token이 발행되면 그 이후에 verify 처리 한다.
		if ($response->status === 200) {
			//검증 정보 불러오기
			$result = $bootpay->verify($receiptId);

			//검증 API DB 저장
			$sql = "
					INSERT INTO payment (
						receipt_id, response_text, reg_ip, reg_date
					) VALUES (
						'". $receiptId ."', '". json_encode($result, JSON_UNESCAPED_UNICODE) ."', '". NOW_IP ."', NOW()
					)
				";
			if ($this->db->insert($sql) == false) {
				return false;
			} else {
				return (array)$result->data;
			}
		} else {
			return false;
		}
	}





	//선택상품 예약자 리스트
	public function choice_tour_booking_list($params = null, &$total_cnt=0, &$total_page=1) {
		if (chkBlank($params)) return false;

		$sub_sql = "";

		//접수일 검색
		if ($params['sch_sdate'] != "") {
			$sub_sql .= " AND TIMESTAMPDIFF(DAY, DATE_FORMAT(reg_date,'%Y-%m-%d'), '". $params['sch_sdate'] ."') <= 0";
		}

		//접수일 검색
		if ($params['sch_edate'] != "") {
			$sub_sql .= " AND TIMESTAMPDIFF(DAY, DATE_FORMAT(reg_date,'%Y-%m-%d'), '". $params['sch_edate'] ."') >= 0";
		}

		//상태 검색
		if ($params['sch_status'] != "") {
			$sub_sql .= " AND status='". $params['sch_status'] ."'";
		}

		//검색어 검색
		if ($params['sch_word'] != "") {
			switch ($params['sch_type']) {
				case "1" : $sub_sql .= " AND booking_num LIKE '%". $params['sch_word'] ."%'"; break;
				case "2" : $sub_sql .= " AND name LIKE '%". $params['sch_word'] ."%'"; break;
				case "3" : $sub_sql .= " AND phone LIKE '%". $params['sch_word'] ."%'"; break;
				case "4" : $sub_sql .= " AND email LIKE '%". $params['sch_word'] ."%'"; break;
				default : $sub_sql .= " AND (
												booking_num LIKE '%". $params['sch_word'] ."%' OR
												name LIKE '%". $params['sch_word'] ."%' OR
												phone LIKE '%". $params['sch_word'] ."%' OR
												email LIKE '%". $params['sch_word'] ."%'
											)
										"; break;
			}
		}

		$sql = "
			SELECT
				*
			FROM choice_tour_booking
			WHERE del_flag='N' $sub_sql
			ORDER BY reg_date DESC
		";

		return $this->db->getList($sql, $params['page'], $params['list_size'], $total_cnt, $total_page);
	}

	//선택상품 예약접수 상세보기
	public function choice_tour_booking_view($idx=null, $booking_num='', $booker_name='', $booker_phone='', $booker_email='') {
		$sub_sql = "";

		if ($idx != "") {
			$sub_sql .= " AND idx='$idx' ";
		}
		if ($booking_num != "") {
			$sub_sql .= " AND booking_num='$booking_num' ";
		}
		if ($booker_name != "") {
			$sub_sql .= " AND name='$booker_name' ";
		}
		if ($booker_phone != "") {
			$sub_sql .= " AND phone='$booker_phone' ";
		}
		if ($booker_email != "") {
			$sub_sql .= " AND email='$booker_email' ";
		}

		$sql = "
				SELECT
					*
				FROM choice_tour_booking
				WHERE del_flag='N' $sub_sql
				ORDER BY reg_date DESC
			";
		return $this->db->getQueryValue($sql);
	}

    //선택상품 예약접수 정보 저장
    public function choice_tour_booking_save_proc($params) {
		$this->db->beginTrans();

		$sql = "
				INSERT INTO choice_tour_booking (
                    booking_num, goods_idx, goods_title,
                    tour_date, tour_time, pickup_area,
                    adult_cnt, adult_amt, child_cnt, child_amt, infant_cnt, infant_amt,
                    name, eng_name1, eng_name2, phone, email,
                    booking_memo,
                    total_tour_amt, total_add_amt, booking_agency_fee,
                    status,
                    del_flag, reg_ip, reg_id, reg_date
				) VALUES (
                    '". $params['booking_num'] ."', '". $params['goods_idx'] ."', '". $params['goods_title'] ."',
                    '". $params['tour_date'] ."', '". $params['tour_time'] ."', '". $params['pickup_area'] ."',
                    '". $params['adult_cnt'] ."', '". $params['adult_amt'] ."', '". $params['child_cnt'] ."', '". $params['child_amt'] ."', '". $params['infant_cnt'] ."', '". $params['infant_amt'] ."',
                    '". $params['name'] ."', '". $params['eng_name1'] ."', '". $params['eng_name2'] ."', '". $params['phone'] ."', '". $params['email'] ."',
                    '". $params['booking_memo'] ."',
                    '". $params['total_tour_amt'] ."', 0, '". $params['booking_agency_fee'] ."',
					'20',
					'N', '". $params['reg_ip'] ."', '". $params['reg_id'] ."', NOW()
				)
			";
		$result = $this->db->insert($sql);
		if ($result == false) {
			$this->db->rollbackTrans();

			return false;
		}

		$booking_idx = $this->db->getLastInsertId();

		if ($result == false) {
			$this->db->rollbackTrans();
		} else {
			$this->db->commitTrans();

			adminMemoSystemSave('choice_tour_booking_view', $booking_idx, '사용자가 예약접수를 하였습니다.');
		}

		return $result;
    }

	//선택상품 예약취소 접수
	public function choice_tour_cancel_req_proc($params) {
		$sql = "
				UPDATE choice_tour_booking SET
					status = status + 1,
					status_change_dt = NOW(),
					upt_ip = '". $params['upt_ip'] ."',
					upt_id = '". $params['upt_id'] ."',
					upt_date = NOW()
				WHERE idx='". $params['booking_idx'] ."'
			";
		$result = $this->db->update($sql);

		if ($result == false) {
			return false;
		} else {
			adminMemoSystemSave('choice_tour_booking_view', $params['booking_idx'], '사용자가 예약취소를 접수 하였습니다.');

			return true;
		}
	}

	//선택상품 예약 결제 처리
	public function choice_tour_payment_proc($params) {
		$sql = "
				UPDATE choice_tour_booking SET
					status = '30',
					status_request_dt = NOW(),
					payment_method = '". $params['payment_method'] ."',
					payment_status = '20',
					payment_status_dt = NOW(),
					payment_req_dt = NOW(),
					payment_dt = NOW(),
					payment_tid = '". $params['payment_tid'] ."'
				WHERE idx='". $params['booking_idx'] ."'
			";

		if ($this->db->update($sql) == false) {
			return false;
		} else {
			adminMemoSystemSave('choice_tour_booking_view', $params['booking_idx'], '사용자가 결제완료 요청하였습니다.');

			return true;
		}
	}

    //선택상품 예약상태 업데이트 처리
	public function choice_tour_booking_status_proc($params) {
		$this->db->beginTrans();

		$sql = "
			UPDATE choice_tour_booking SET
				status = '". $params['status'] ."',
				status_change_dt = NOW(),
				upt_ip = '". $params['upt_ip'] ."',
				upt_id = '". $params['upt_id'] ."',
				upt_date = NOW()
			WHERE idx='". $params['booking_idx'] ."'
		";
		$result = $this->db->update($sql);
		if ($result == false) {
			$this->db->rollbackTrans();

			return false;
		}

		//취소완료, 환불완료시 재고상태 업데이트
		if ($params['status'] == '12' || $params['status'] == '22' || $params['status'] == '32' || $params['status'] == '42') {
			//출발일 재고 업데이트
			$sql = "
					UPDATE goods_stock SET
						stock_cnt = stock_cnt + 1
					WHERE goods_idx='". $params['goods_idx'] ."' AND sdate='". $params['rental_sdate'] ."'
				";
			$result = $this->db->update($sql);
		}

		//결제완료 상태
		if ($params['status'] == '30') {
			$sql = "
					UPDATE choice_tour_booking SET
						status = '30',
						status_request_dt = NOW(),
						payment_method = '". $params['payment_method'] ."',
						payment_status = '20',
						payment_status_dt = NOW(),
						payment_req_dt = NOW(),
						payment_dt = NOW(),
						payment_tid = ''
					WHERE idx='". $params['booking_idx'] ."'
				";
			$result = $this->db->update($sql);
		}

		if ($result == false) {
			$this->db->rollbackTrans();
		} else {
			$this->db->commitTrans();

			adminMemoSystemSave('choice_tour_booking_view', $params['booking_idx'], '관리자가 예약정보 상태를 수정 하였습니다.');
		}

		return $result;
	}

	//선택상품 담당자 안내문 저장
	public function choice_tour_notice_save_proc($params) {
		$sql = "
				UPDATE choice_tour_booking SET
					notice = '". $params['notice'] ."',
					upt_ip = '". $params['upt_ip'] ."',
					upt_id = '". $params['upt_id'] ."',
					upt_date = NOW()
				WHERE idx='". $params['booking_idx'] ."'
			";

		if ($this->db->update($sql) == false) {
			return false;
		} else {
			adminMemoSystemSave('choice_tour_booking_view', $params['booking_idx'], '관리자가 담당자 안내문 저장 하였습니다.');

			return true;
		}
	}



    //렌트상품 및 선택상품 예약 체크
    public function booking_check($booking_num, $booker_phone) {
		$sql = "
                SELECT * FROM (
                    SELECT
                        'rental' AS gubun,
                        idx
                    FROM booking
                    WHERE booking_num='$booking_num' AND phone='$booker_phone' AND del_flag='N'
                    UNION ALL
                    SELECT
                        'choice_tour' AS gubun,
                        idx
                    FROM choice_tour_booking
                    WHERE booking_num='$booking_num' AND phone='$booker_phone' AND del_flag='N'
                ) t
                ORDER BY idx DESC
			";
		return $this->db->getQueryValue($sql);
    }
}
