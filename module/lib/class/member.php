<?php
/**
 * 회원 관리
 */
class CLS_MEMBER
{
	private $db;

	function __construct()
	{
		$this->db = new DB_HELPER;
	}

	//본인인증 중복 체크
	public static function is_checkplus_check($ci, $di, $old_ci = "", $old_di = "") {
		$db = new DB_HELPER;

		$sub_sql = "";
		if ($old_ci != "" && $old_di != "") {
			$sub_sql = $sub_sql . " AND (ci != '$old_ci' AND di != '$old_di')";
		}

		$sql = "SELECT usr_idx FROM member WHERE (ci = '$ci' AND di = '$di') $sub_sql";

		if ($db->getQueryValue($sql)) {
			return true;
		} else {
			return false;
		}
	}

	//아이디 중복 체크
	public static function is_id_check($usr_id, $old_usr_id = "") {
		$db = new DB_HELPER;

		$sub_sql = "";
		if ($old_usr_id != "") {
			$sub_sql = $sub_sql . " AND usr_id != '$old_usr_id'";
		}

		$sql = "SELECT usr_idx FROM member WHERE usr_id = '$usr_id' $sub_sql";

		if ($db->getQueryValue($sql)) {
			return true;
		} else {
			return false;
		}
	}

	//휴대폰번호 중복 체크
	public static function is_phone_check($usr_phone, $old_usr_phone = "") {
		$db = new DB_HELPER;

		$sub_sql = "";
		if ($old_usr_phone != "") {
			$sub_sql = $sub_sql . " AND usr_phone != '$old_usr_phone'";
		}

		$sql = "SELECT usr_idx FROM member WHERE usr_phone = '$usr_phone' $sub_sql";

		if ($db->getQueryValue($sql)) {
			return true;
		} else {
			return false;
		}
	}

	//닉네임 중복 체크
	public static function is_nick_check($nick_name, $old_nick_name = "") {
		$db = new DB_HELPER;

		$sub_sql = "";
		if ($old_nick_name != "") {
			$sub_sql = $sub_sql . " AND nick_name != '$old_nick_name'";
		}

		$sql = "SELECT usr_idx FROM member WHERE nick_name = '$nick_name' $sub_sql";

		if ($db->getQueryValue($sql)) {
			return true;
		} else {
			return false;
		}
	}

	//이메일 중복 체크
	public static function is_email_check($email, $old_email = "") {
		$db = new DB_HELPER;

		$sub_sql = "";
		if ($old_email != "") {
			$sub_sql = $sub_sql . " AND usr_email != '$old_email'";
		}

		$sql = "SELECT usr_idx FROM member WHERE usr_email = '$email' $sub_sql";

		if ($db->getQueryValue($sql)) {
			return true;
		} else {
			return false;
		}
	}


	//비밀번호 초기화
	public function passwd_reset_save($params) {
		$row = $this->db->getQueryValue("SELECT * FROM member WHERE usr_idx='". $params['usr_idx'] ."'");
		if ($row == false) return false;

		//비밀번호 초기화
		$params['usr_pwd'] = encryption(CONST_RESET_PWD. right($row['usr_phone'], 4));

		$sql = "
				UPDATE member SET
					usr_pwd = '". $params['usr_pwd'] ."',
					pwd_last_date = NOW(),
					upt_ip = '". $params['upt_ip'] ."',
					upt_id = '". $params['upt_id'] ."',
					upt_date = NOW()
				WHERE usr_idx = '". $params['usr_idx'] ."'
			";

		return $this->db->update($sql);
	}

	//회원 탈퇴 처리
	public function out_save($params) {
		$row = $this->db->getQueryValue("SELECT * FROM member WHERE usr_idx='". $params['usr_idx'] ."'");
		if ($row == false) return false;

		$sub_sql = "";
		if ($params['usr_pwd'] != '') {
			$sub_sql .= " AND usr_pwd = '". $params['usr_pwd'] ."'";
		}

		$sql = "
				UPDATE member SET
					usr_gubun = 80,
					out_old_gubun = '". $row['usr_gubun'] ."',
					out_reason = '". $params['out_reason'] ."',
					out_date = NOW(),
					upt_ip = '". $params['upt_ip'] ."',
					upt_id = '". $params['upt_id'] ."',
					upt_date = NOW()
				WHERE usr_idx = '". $params['usr_idx'] ."' $sub_sql
			";

		return $this->db->update($sql);
	}

	//마지막 로그인 저장
	public function last_visit_update($usr_id) {
		$sql = "UPDATE member SET visit_cnt=visit_cnt+1, visit_last_date=NOW() WHERE usr_id='$usr_id'";

		return $this->db->update($sql);
	}

	//관리자 비밀번호 변경기간 체크
	public static function admin_pwd_check($usr_id) {
		$db = new DB_HELPER;

        return false;

        /*
		$row = $db->getQueryValue("SELECT usr_pwd, usr_phone, pwd_last_date FROM member WHERE usr_id='$usr_id'");
		if ($row == false) return false;

		if (encryption(CONST_RESET_PWD . right($row['usr_phone'],4)) == $row['usr_pwd']) {
			//기본 비밀번호 일경우
			return true;
		} else if (dateDiff("d", $row['pwd_last_date'], date("Y-m-d H:i:s")) > MEMBER_PWD_EXPIRE_DAY) {
			//비밀번호 변경기간이 지났을경우
			return true;
		} else {
			return false;
		}
        */
	}

	//최고관리자 불러오기
	public static function is_master($usr_id) {
		$db = new DB_HELPER;

		$row = $db->getQueryValue("SELECT usr_idx FROM member WHERE usr_id='$usr_id' AND usr_gubun=99 AND status='Y'");
		if ($row) {
			return true;
		} else {
			return false;
		}
	}

	//관리자 목록
	public function admin_list($params = null, &$total_cnt=0, &$total_page=1) {
        if (chkBlank($params)) return null;

        $sub_sql = "";

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
                a.*,
				CASE
					WHEN a.status='Y' THEN '이용중'
					ELSE '이용정지'
				END AS status_name,
				b.title AS usr_gubun_name,
				b.status AS usr_gubun_status
            FROM member a INNER JOIN setting_admin_auth b ON a.usr_gubun=b.gubun
            WHERE a.usr_gubun BETWEEN 90 AND 99 AND a.status IN ('Y', 'N') $sub_sql
            ORDER BY a.reg_date DESC";
        $rows = $this->db->getList($sql, $params['page'], $params['list_size'], $total_cnt, $total_page);

        return $rows;
	}

	//관리자 상세
	public function admin_view($usr_identy) {
		$sql = "
			SELECT
                a.*,
				b.title AS usr_gubun_name,
				b.status AS usr_gubun_status,
				b.menu_auth
			FROM member a INNER JOIN setting_admin_auth b ON a.usr_gubun=b.gubun
			WHERE (a.usr_idx='$usr_identy' OR a.usr_id='$usr_identy') AND a.usr_gubun BETWEEN 90 AND 99 AND a.status IN ('Y', 'N')";

		return $this->db->getQueryValue($sql);
	}

	//관리자 정보 저장
	public function admin_save($params) {
		if (chkBlank($params['usr_idx'])) {
			//등록
			$sql = "
					INSERT INTO member (
						usr_gubun, usr_id, usr_pwd, usr_name, usr_email, usr_phone,
						status, pwd_last_date, reg_ip, reg_id, reg_date
					) VALUES (
						'". $params['usr_gubun'] ."', '". $params['usr_id'] ."', '". $params['usr_pwd'] ."', '". $params['usr_name'] ."', '". $params['usr_email'] ."', '". $params['usr_phone'] ."',
						'". $params['status'] ."', NOW(), '". $params['reg_ip'] ."', '". $params['reg_id'] ."', NOW()
					)
				";
			$result = $this->db->insert($sql);
		} else {
			//수정
			$sql = "
					UPDATE member SET
						usr_gubun = '". $params['usr_gubun'] ."',
						usr_id = '". $params['usr_id'] ."',
						usr_pwd = ". iif($params['usr_pwd']!="", "'". $params['usr_pwd'] ."'", "usr_pwd") .",
						usr_name = '". $params['usr_name'] ."',
						usr_email = '". $params['usr_email'] ."',
						usr_phone = '". $params['usr_phone'] ."',
						status = '". $params['status'] ."',
						upt_ip = '". $params['upt_ip'] ."',
						upt_id = '". $params['upt_id'] ."',
						upt_date = NOW()
					WHERE usr_idx = '". $params['usr_idx'] ."'
				";
			$result = $this->db->update($sql);
		}

		return $result;
	}

	//관리자 정보 삭제
	public function admin_delete($params) {
		$sql = "
				UPDATE member SET
					status = 'D',
					upt_ip = '". $params['upt_ip'] ."',
					upt_id = '". $params['upt_id'] ."',
					upt_date = NOW()
				WHERE usr_idx = '". $params['usr_idx'] ."'
			";
		$result = $this->db->update($sql);

		return $result;
	}

	//관리자 정보 수정 팝업 저장
	public function amdin_modify_save($params) {
		//회원정보 수정
		$sql = "
				UPDATE member SET
					usr_id = '". $params['usr_id'] ."',
					usr_pwd = '". $params['usr_pwd'] ."',
					usr_name = '". $params['usr_name'] ."',
					usr_email = '". $params['usr_email'] ."',
					usr_phone = '". $params['usr_phone'] ."',
					upt_ip = '". $params['upt_ip'] ."',
					upt_id = '". $params['upt_id'] ."',
					upt_date = NOW()
				WHERE usr_idx = '". $params['usr_idx'] ."'
			";
		$result = $this->db->update($sql);

		//비밀번호 변경시
		if ($result && $params['usr_pwd_old'] != $params['usr_pwd']) {
			$sql = "
					UPDATE member SET
						pwd_last_date = NOW()
					WHERE usr_idx = '". $params['usr_idx'] ."'
				";
			$result = $this->db->update($sql);
		}

		return $result;
	}

	//관리자 비밀번호 변경 저장
	public function admin_passwd_save($params) {
		$sql = "
				UPDATE member SET
					usr_pwd = '". $params['usr_pwd'] ."',
					pwd_last_date = NOW(),
					upt_ip = '". $params['upt_ip'] ."',
					upt_id = '". $params['upt_id'] ."',
					upt_date = NOW()
				WHERE usr_idx = '". $params['usr_idx'] ."'
			";
		return $this->db->update($sql);
	}

	//회원 목록
	public function user_list($params = null, &$total_cnt=0, &$total_page=1) {
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

		//그룹구분검색
		if ($params['sch_gubun'] != "") {
			$sub_sql .= " AND usr_gubun='". $params['sch_gubun'] ."'";
		}

		//회원상태검색
		if ($params['sch_status'] != "") {
			$sub_sql .= " AND status='". $params['sch_status'] ."'";
		}

		//검색어 검색
		if ($params['sch_word'] != "") {
			switch ($params['sch_type']) {
				case "1" : $sub_sql .= " AND usr_id LIKE '%". $params['sch_word'] ."%'"; break;
				case "2" : $sub_sql .= " AND usr_name LIKE '%". $params['sch_word'] ."%'"; break;
				case "3" : $sub_sql .= " AND RIGHT(usr_phone,4) LIKE '%". $params['sch_word'] ."%'"; break;
				default : $sub_sql .= " AND (usr_id LIKE '%". $params['sch_word'] ."%' OR usr_name LIKE '%". $params['sch_word'] ."%' OR RIGHT(usr_phone,4) LIKE '%". $params['sch_word'] ."%')"; break;
			}
		}

        $sql = "
				SELECT
					*,
					CASE
						WHEN usr_gubun='10' THEN '일반회원'
						WHEN usr_gubun='80' THEN '탈퇴회원'
						ELSE '기타회원'
					END AS usr_gubun_name,
					CASE
						WHEN gender='M' THEN '남성'
						WHEN gender='F' THEN '여성'
						ELSE ''
					END AS gender_name
				FROM member
				WHERE usr_gubun < 80 AND status IN ('Y', 'N') $sub_sql
				ORDER BY reg_date DESC
			";

		return $this->db->getList($sql, $params['page'], $params['list_size'], $total_cnt, $total_page);
	}

	//탈퇴회원 목록
	public function out_list($params = null, &$total_cnt=0, &$total_page=1) {
        if (chkBlank($params)) return null;

        $sub_sql = "";

		//탈퇴 시작일 검색
		if ($params['sch_sdate'] != "") {
			$sub_sql .= " AND TIMESTAMPDIFF(DAY, DATE_FORMAT(out_date,'%Y-%m-%d'), '". $params['sch_sdate'] ."') <= 0";
		}

		//탈퇴 종료일 검색
		if ($params['sch_edate'] != "") {
			$sub_sql .= " AND TIMESTAMPDIFF(DAY, DATE_FORMAT(out_date,'%Y-%m-%d'), '". $params['sch_edate'] ."') >= 0";
		}

		//검색어 검색
		if ($params['sch_word'] != "") {
			switch ($params['sch_type']) {
				case "1" : $sub_sql .= " AND usr_id LIKE '%". $params['sch_word'] ."%'"; break;
				case "2" : $sub_sql .= " AND usr_name LIKE '%". $params['sch_word'] ."%'"; break;
				case "3" : $sub_sql .= " AND RIGHT(usr_phone,4) LIKE '%". $params['sch_word'] ."%'"; break;
				default : $sub_sql .= " AND (usr_id LIKE '%". $params['sch_word'] ."%' OR usr_name LIKE '%". $params['sch_word'] ."%' OR RIGHT(usr_phone,4) LIKE '%". $params['sch_word'] ."%')"; break;
			}
		}

        $sql = "
				SELECT
					*,
					CASE
						WHEN usr_gubun='10' THEN '일반회원'
						WHEN usr_gubun='80' THEN '탈퇴회원'
						ELSE '기타회원'
					END AS usr_gubun_name,
					CASE
						WHEN gender='M' THEN '남성'
						WHEN gender='F' THEN '여성'
						ELSE ''
					END AS gender_name
				FROM member
				WHERE usr_gubun = 80 AND status IN ('Y', 'N') $sub_sql
				ORDER BY reg_date DESC
			";

        return $this->db->getList($sql, $params['page'], $params['list_size'], $total_cnt, $total_page);
	}

	//회원 상세
	public function user_view($usr_identy) {
		$sql = "
				SELECT
					*,
					CASE
						WHEN usr_gubun='10' THEN '일반회원'
						WHEN usr_gubun='80' THEN '탈퇴회원'
						ELSE '기타회원'
					END AS usr_gubun_name,
					CASE
						WHEN gender='M' THEN '남성'
						WHEN gender='F' THEN '여성'
						ELSE ''
					END AS gender_name
				FROM member
				WHERE (usr_idx='$usr_identy' OR usr_id='$usr_identy') AND usr_gubun < 90 AND status IN ('Y', 'N')
			";

		return $this->db->getQueryValue($sql);
	}

	//회원 정보 저장
	public function user_save($params) {
		if (chkBlank($params['usr_idx'])) {
			//회원정보 저장
			$sql = "
					INSERT INTO member (
						usr_gubun, usr_id, usr_pwd,
						usr_name, nick_name, usr_email, usr_phone,
						birthdate, gender,
						up_file_1,
						zipcode, addr, addr_detail,
						recv_email_flag, recv_email_dt,
						recv_sms_flag, recv_sms_dt,
						sns_naver_id, sns_naver_dt,
						status, pwd_last_date, reg_ip, reg_id, reg_date
					) VALUES (
						'". $params['usr_gubun'] ."', '". $params['usr_id'] ."', '". $params['usr_pwd'] ."',
						'". $params['usr_name'] ."', '". $params['nick_name'] ."', '". $params['usr_email'] ."', '". $params['usr_phone'] ."',
						'". $params['birthdate'] ."', '". $params['gender'] ."',
						'". $params['zipcode'] ."', '". $params['addr'] ."', '". $params['addr_detail'] ."',
						'". $params['up_file_1'] ."',
						'". $params['recv_email_flag'] ."', NOW(),
						'". $params['recv_sms_flag'] ."', NOW(),
						'". $params['sns_naver_id'] ."', ". iif($params['sns_naver_id']!="", "NOW()", "NULL") .",
						'". $params['status'] ."', NOW(), '". $params['reg_ip'] ."', '". $params['reg_id'] ."', NOW()
					)
				";

			return $this->db->insert($sql);
		} else {
			//회원정보 업데이트
			$sql = "
					UPDATE member SET
						usr_gubun = '". $params['usr_gubun'] ."',
						usr_pwd = '". $params['usr_pwd'] ."',
						usr_name = '". $params['usr_name'] ."',
						usr_email = '". $params['usr_email'] ."',
						usr_phone = '". $params['usr_phone'] ."',
						birthdate = '". $params['birthdate'] ."',
						gender = '". $params['gender'] ."',
						zipcode = '". $params['zipcode'] ."',
						addr = '". $params['addr'] ."',
						addr_detail = '". $params['addr_detail'] ."',
						up_file_1 = '". $params['up_file_1'] ."',
						recv_email_flag = '". $params['recv_email_flag'] ."',
						". iif($params['recv_email_flag'] != $params['old_recv_email_flag'], "recv_email_dt = NOW(),", "") ."
						recv_sms_flag = '". $params['recv_sms_flag'] ."',
						". iif($params['recv_sms_flag'] != $params['old_recv_sms_flag'], "recv_sms_dt = NOW(),", "") ."
						status = '". $params['status'] ."',
						upt_ip = '". $params['upt_ip'] ."',
						upt_id = '". $params['upt_id'] ."',
						upt_date = NOW()
					WHERE usr_idx = '". $params['usr_idx'] ."'
				";

			return $this->db->update($sql);
		}
	}

	//회원 비밀번호 변경 저장
	public function user_passwd_save($params) {
		$sql = "
				UPDATE member SET
					usr_pwd = '". $params['usr_pwd'] ."',
					pwd_last_date = NOW(),
					upt_ip = '". $params['upt_ip'] ."',
					upt_id = '". $params['upt_id'] ."',
					upt_date = NOW()
				WHERE usr_id='". $params['usr_id'] ."'
			";

		return $this->db->update($sql);
	}

	//회원 파일 삭제 저장
	public function user_file_del($fnum, $usr_idx) {
		$usr_view = $this->user_view($usr_idx);
		if ($usr_view == false) return false;

		//파일 삭제
		if ($usr_view['up_file_1'] != '') {
			fileDelete('/upload/member', getUpfileName($usr_view['up_file_1']));
		}

		$sql = "
				UPDATE member SET
					up_file_$fnum = '',
					upt_date = NOW()
				WHERE usr_idx='". $usr_idx ."'
			";

		return $this->db->update($sql);
	}

	//회원 SNS가입정보 체크
	public function sns_view($sns_id) {
		$sql = "
				SELECT
					*,
					CASE
						WHEN usr_gubun='10' THEN '일반회원'
						WHEN usr_gubun='80' THEN '탈퇴회원'
						ELSE '기타회원'
					END AS usr_gubun_name,
					CASE
						WHEN gender='M' THEN '남성'
						WHEN gender='F' THEN '여성'
						ELSE ''
					END AS gender_name
				FROM member
				WHERE sns_naver_id='$sns_id' AND usr_gubun < 90 AND status IN ('Y', 'N')
			";

		return $this->db->getQueryValue($sql);
	}

	//회원 SNS 아이디 연결 / 해제
	public function sns_conn_save($params) {
		//회원정보 업데이트
		$sql = "
				UPDATE member SET
					sns_". $params['gubun'] ."_id = '". $params['sns_id'] ."',
					sns_". $params['gubun'] ."_dt = NOW(),
					upt_ip = '". $params['upt_ip'] ."',
					upt_id = '". $params['upt_id'] ."',
					upt_date = NOW()
				WHERE usr_idx = '". $params['usr_idx'] ."'
			";

		return $this->db->update($sql);
	}

	//회원 아이디, 비번찾기
	public function find_id_passwd($params) {
		if ($params['gubun'] == 'id') {
			$sql = "
					SELECT
						*
					FROM member
					WHERE usr_name='". $params['find_name'] ."'
						AND REPLACE(birthdate,'-','')='". $params['find_birthdate'] ."'
						AND (usr_email='". $params['find_email'] ."' OR REPLACE(usr_phone,'-','')='". $params['find_phone'] ."')
						AND usr_gubun < 90 AND status IN ('Y', 'N')
					ORDER BY reg_date
				";

			return $this->db->getQuery($sql);
		} else {
			$sql = "
					SELECT
						*
					FROM member
					WHERE usr_id='". $params['find_id'] ."'
						AND usr_name='". $params['find_name'] ."'
						AND REPLACE(birthdate,'-','')='". $params['find_birthdate'] ."'
						AND (usr_email='". $params['find_email'] ."' OR REPLACE(usr_phone,'-','')='". $params['find_phone'] ."')
						AND usr_gubun < 90 AND status IN ('Y', 'N')
					ORDER BY reg_date
				";

			return $this->db->getQueryValue($sql);
		}
	}
}
