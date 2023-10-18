<?php
/**
 * 배너 관리
 */
class CLS_SETTING_BANNER
{
	private $db;

	function __construct()
	{
		$this->db = new DB_HELPER;
	}

	//배너관리 관리자 목록
	public function banner_admin_list($params = null, &$total_cnt=0, &$total_page=1) {
        if (chkBlank($params)) return null;

        $sub_sql = "";

		//시작일 검색
		if ($params['sch_sdate'] != "") {
			$sub_sql .= " AND TIMESTAMPDIFF(DAY, sdate, '". $params['sch_sdate'] ."') <= 0";
		}

		//종료일 검색
		if ($params['sch_edate'] != "") {
			$sub_sql .= " AND TIMESTAMPDIFF(DAY, edate, '". $params['sch_edate'] ."') >= 0";
		}

		//노출상태 검색
		if ($params['sch_open'] != "") {
			$sub_sql .= " AND open_flag='". $params['sch_open'] ."'";
		}

        $sql = "
				SELECT
					*,
					CASE
						WHEN open_flag = 'Y' THEN '노출'
						ELSE '숨김'
					END AS open_flag_name
				FROM setting_banner
				WHERE del_flag='N' $sub_sql
				ORDER BY reg_date DESC
			";

		return $this->db->getList($sql, $params['page'], $params['list_size'], $total_cnt, $total_page);
	}

    //배너 목록
    public function banner_list($section) {
        $sql = "
				SELECT
					*
				FROM setting_banner
				WHERE section='$section' AND open_flag='Y' AND del_flag='N'
				ORDER BY idx DESC
			";

		return $this->db->getQuery($sql);
    }

    //배너 상세
    public function banner_view($idx) {
        $sql = "SELECT * FROM setting_banner WHERE idx='$idx' AND del_flag='N'";

        return $this->db->getQueryValue($sql);
    }

    //배너 정보 저장
    public function banner_save($params) {
        if (chkBlank($params['idx'])) {
            $sql = "
					INSERT INTO setting_banner (
						section, title, content, sdate, edate,
						up_file_1, up_file_2, target_pc, link_pc, target_mobile, link_mobile,
						open_flag, del_flag ,reg_ip, reg_id, reg_date
					) VALUES (
						'". $params['section'] ."', '". $params['title'] ."', '". $params['content'] ."', '". $params['sdate'] ."', '". $params['edate'] ."',
						'". $params['up_file_1'] ."', '". $params['up_file_2'] ."', '". $params['target_pc'] ."', '". $params['link_pc'] ."', '". $params['target_mobile'] ."', '". $params['link_mobile'] ."',
						'". $params['open_flag'] ."', 'N', '". $params['reg_ip'] ."', '". $params['reg_id'] ."', NOW()
					)
				";

			$result = $this->db->insert($sql);
        } else {
            $sql = "
					UPDATE setting_banner SET
						section = '". $params['section'] ."',
						content = '". $params['content'] ."',
						title = '". $params['title'] ."',
						sdate = '". $params['sdate'] ."',
						edate = '". $params['edate'] ."',
						up_file_1 = '". $params['up_file_1'] ."',
						up_file_2 = '". $params['up_file_2'] ."',
						target_pc = '". $params['target_pc'] ."',
						link_pc = '". $params['link_pc'] ."',
						target_mobile = '". $params['target_mobile'] ."',
						up_file_2 = '". $params['up_file_2'] ."',
						link_mobile = '". $params['link_mobile'] ."',
						open_flag = '". $params['open_flag'] ."',
						upt_ip = '". $params['upt_ip'] ."',
						upt_id = '". $params['upt_id'] ."',
						upt_date = NOW()
					WHERE idx='". $params['idx'] ."'
				";

			$result = $this->db->insert($sql);
        }

        return $result;
    }

	//메인 비쥬얼 제외 중복체크
	public function banner_area_check($idx, $area) {
		if (chkBlank($idx)) {
			$sql = "SELECT * FROM setting_banner WHERE section='$area' AND del_flag='N'";
		} else {
			$sql = "SELECT * FROM setting_banner WHERE idx != '$idx' AND del_flag='N'";
			if ($this->db->getQueryValue($sql) == false) {
				//등록된 내역이 없으면 패스
				return true;
			} else {
				//등록된 내역이 있을경우 현재글 제외한 곳 체크
				$sql = "SELECT * FROM setting_banner WHERE idx != '$idx' AND section='$area' AND del_flag='N'";
			}
		}
		$row = $this->db->getQueryValue($sql);

		if ($row == false) {
			return true;
		} else {
			return false;
		}
	}

    //배너 삭제
    public function banner_delete($params) {
        $sql = "
            UPDATE setting_banner SET
                del_flag = 'Y',
				upt_ip = '". $params['upt_ip'] ."',
				upt_id = '". $params['upt_id'] ."',
				upt_date = NOW()
            WHERE idx='". $params['idx'] ."'
        ";

        return $this->db->update($sql);
    }

    //배너 첨부이미지 삭제
    public function banner_file_delete($params) {
		$view = $this->banner_view($params['idx']);
		if ($view == false) return null;

		//첨부파일 정보
		$up_file = $view['up_file_'. $params['fnum']];

        $sql = "
            UPDATE setting_banner SET
				up_file_". $params['fnum'] ." = '',
				upt_ip = '". $params['upt_ip'] ."',
				upt_id = '". $params['upt_id'] ."',
				upt_date = NOW()
            WHERE idx='". $params['idx'] ."'
        ";
		$result = $this->db->update($sql);

		if ($result !== false && $up_file != "") {
			 fileDelete("/upload/banner/", getUpfileName($up_file));
		}

        return $result;
    }
}