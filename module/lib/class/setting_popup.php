<?php
/**
 * 팝업 관리
 */
class CLS_SETTING_POPUP
{
	private $db;

	function __construct()
	{
		$this->db = new DB_HELPER;
	}

	//팝업관리 관리자 목록
	public function popup_admin_list($params = null, &$total_cnt=0, &$total_page=1) {
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
                FROM setting_popup
                WHERE del_flag='N' $sub_sql
                ORDER BY reg_date DESC
            ";
        return $this->db->getList($sql, $params['page'], $params['list_size'], $total_cnt, $total_page);
	}

    //팝업 목록
    public function popup_list($open_flag = "") {
        $sub_sql = "";

        if ($open_flag != "") $sub_sql .= " AND open_flag='$open_flag'";
        $sql = "
                SELECT
                    *
                FROM setting_popup
                WHERE
                    TIMESTAMPDIFF(DAY, sdate, CURDATE()) >= 0 AND
                    TIMESTAMPDIFF(DAY, edate, CURDATE()) <= 0 AND
                    del_flag='N' ". $sub_sql ."
                ORDER BY sdate ASC, edate ASC, idx DESC
            ";

        return $this->db->getQuery($sql);
    }

    //팝업 상세
    public function popup_view($idx) {
        $sql = "SELECT * FROM setting_popup WHERE idx='$idx' AND del_flag='N'";

        return $this->db->getQueryValue($sql);
    }

    //팝업 정보 저장
    public function popup_save($params) {
        if (chkBlank($params['idx'])) {
            $sql = "
                    INSERT INTO setting_popup (
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
                    UPDATE setting_popup SET
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

            $result = $this->db->update($sql);
        }

        return $result;
    }

    //팝업 삭제
    public function popup_delete($params) {
        $sql = "
                UPDATE setting_popup SET
                    del_flag = 'Y',
                    upt_ip = '". $params['upt_ip'] ."',
                    upt_id = '". $params['upt_id'] ."',
                    upt_date = NOW()
                WHERE idx='". $params['idx'] ."'
            ";

        return $this->db->update($sql);
    }

    //팝업 첨부이미지 삭제
    public function popup_file_delete($params) {
		$view = $this->popup_view($params['idx']);
		if ($view == false) return null;

		//첨부파일 정보
		$up_file = $view['up_file_'. $params['fnum']];

        $sql = "
                UPDATE setting_popup SET
                    up_file_". $params['fnum'] ." = '',
                    upt_ip = '". $params['upt_ip'] ."',
                    upt_id = '". $params['upt_id'] ."',
                    upt_date = NOW()
                WHERE idx='". $params['idx'] ."'
            ";
		$result = $this->db->update($sql);

		if ($result !== false && $up_file != "") {
			 fileDelete("/upload/popup/", getUpfileName($up_file));
		}

        return $result;
    }
}