<?php
/**
 * 관리자 메뉴 및 권한
 */
class CLS_SETTING_MENU_AUTH
{
	private $db;

	function __construct()
	{
		$this->db = new DB_HELPER;
	}

	//관리자 메뉴 최대 서브메뉴 수
	public function max_menu_count() {
		$sql = "SELECT COUNT(*) AS menu_cnt FROM setting_admin_menu WHERE parent_code!='' GROUP BY parent_code ORDER BY menu_cnt DESC LIMIT 1";
		$rows = $this->db->getQueryValue($sql);

		return $rows['menu_cnt'];
	}

    //관리자 메뉴 목록
    public function menu_list($parent_code, $open_flag = "") {
        $sub_sql = "";

        if ($open_flag != "") $sub_sql .= " AND open_flag='$open_flag'";
        $sql = "SELECT * FROM setting_admin_menu WHERE parent_code='$parent_code' ". $sub_sql ." ORDER BY sort ASC, parent_code ASC, code ASC";

        return $this->db->getQuery($sql);
    }

    //관리자 메뉴 상세정보
    public function menu_view($code, $open_flag = "") {
        $sub_sql = "";

        if ($open_flag != "") $sub_sql .= " AND open_flag='$open_flag'";
        $sql = "SELECT * FROM setting_admin_menu WHERE code='$code'".  $sub_sql;

        return $this->db->getQueryValue($sql);
	}

	//관리자 게시판 페이지코드 불러오기
	public function board_menu_code($bbs_code) {
		$sql = "
				SELECT
					code
				FROM setting_admin_menu
				WHERE SUBSTRING_INDEX(page_url,'?',-1) = 'bbs_code=$bbs_code'
			";

		$row = $this->db->getQueryValue($sql);
		if ($row == false) return null;

		return $row['code'];
	}

	//관리자 게시판 코드명 불러오기
	public function board_menu_name($bbs_code) {
		$sql = "
				SELECT
					code_name
				FROM setting_admin_menu
				WHERE page_url LIKE '%bbs_code=$bbs_code%'
			";

		$row = $this->db->getQueryValue($sql);
		if ($row == false) return "";

		return $row['code_name'];
	}

	//관리자 페이지코드의 1차,2차 코드명 및 설명 불러오기
	public function menu_code_name($code, &$page_name = "", &$page_sub_name = "", &$explain = "") {
		$sql = "
				SELECT
					a.idx,
					a.parent_code,
					a.code,
					b.code_name AS page_name,
					a.code_name AS page_sub_name,
					a.explain
				FROM setting_admin_menu a INNER JOIN setting_admin_menu b ON a.parent_code=b.code
				WHERE a.code='$code'
			";

		$row = $this->db->getQueryValue($sql);

		$page_name     = $row['page_name'];
		$page_sub_name = $row['page_sub_name'];
		$explain       = $row['explain'];
	}

	//관리자 첫번째 메뉴 링크 불러오기
	public static function menu_first_page($gubun, $parent_code = "") {
		$db = new DB_HELPER;

		//관리자 권한 정보 불러오기
		$sql = "SELECT menu_auth FROM setting_admin_auth WHERE gubun='$gubun'";
		$rows = $db->getQueryValue($sql);
		if ($rows == false) return "";

		$menu_arr = explode(',', $rows['menu_auth']);
		if ($parent_code == "") {
			$menu_code = $menu_arr[0];
		} else {
			for ($i=0; $i<count($menu_arr);$i++) {
				if ($parent_code == substr($menu_arr[$i],0,2)) {
					$menu_code = $menu_arr[$i];
					break;
				}
			}
		}

		//권한중 첫번재 메뉴 코드정보 불러오기
		$sql = "SELECT page_url FROM setting_admin_menu WHERE code='$menu_code' AND open_flag='Y'";
		$rows = $db->getQueryValue($sql);
		if ($rows == false) return "";

		return $rows['page_url'];
	}

	//관리자 SNB 메뉴 권한체크
	public static function menu_auth_check($menu_auth, $category1, $category2 = "") {
		$menu_arr = explode(',', $menu_auth);

		if ($category2 == "") {
			//1차 카테고리 체크
			for ($i=0; $i<count($menu_arr);$i++) {
				if ($category1 == substr($menu_arr[$i],0,2)) {
					return true;
				}
			}
		} else {
			//2차 카테고리 체크
			for ($i=0; $i<count($menu_arr);$i++) {
				if ($category2 == $menu_arr[$i]) {
					return true;
				}
			}
		}

		return false;
	}

	//관리자 권한정보 저장
    public function admin_auth_save($params) {
        $sql = "
            UPDATE setting_admin_auth SET
                title = '". $params['title'] ."',
                description = '". $params['description'] ."',
                menu_auth = '". $params['menu_auth'] ."',
                status = '". $params['status'] ."',
                upt_ip = '". $params['upt_ip'] ."',
                upt_id = '". $params['upt_id'] ."',
                upt_date = NOW()
            WHERE gubun='". $params['gubun'] ."'
        ";

        return $this->db->update($sql);
    }

	//관리자 권한관리 목록
    public function admin_auth_list() {
        $sql = "
			SELECT
				*,
				CASE
					WHEN status = 'Y' THEN '사용'
					ELSE '사용중지'
				END AS status_name
			FROM setting_admin_auth
			WHERE 1
			ORDER BY gubun ASC";

        return $this->db->getQuery($sql);
    }

	//관리자 권한관리 상세
	public function admin_auth_view($gubun) {
		$sql = "
			SELECT
				*,
				CASE
					WHEN status = 'Y' THEN '사용'
					ELSE '사용중지'
				END AS status_name
			FROM setting_admin_auth
			WHERE gubun='$gubun'";
		$row = $this->db->getQueryValue($sql);

		return $row;
	}

	//관리자 권한정보 체크
	public function is_menu_auth($gubun, $page_num) {
		//관리자 권한 정보 불러오기
		$sql = "SELECT menu_auth FROM setting_admin_auth WHERE gubun='$gubun'";
		$rows = $this->db->getQueryValue($sql);
		if ($rows == false) return false;

		//해당 메뉴 체크
		$menu_arr = explode(',', $rows['menu_auth']);
		for ($i=0; $i<count($menu_arr);$i++) {
			if ($page_num == $menu_arr[$i]) {
				return true;
			}
		}

		return false;
	}
}