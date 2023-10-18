<?php
/**
 * 관리자 메모
 */
class CLS_ADMIN_MEMO
{
    private $db;

	function __construct()
	{
		$this->db = new DB_HELPER;
	}

    //메모 목록
    public function memo_list($section, $gubun) {
        $sub_sql = "";

        $sql = "
            SELECT
                *
            FROM admin_memo_log
            WHERE section='$section' AND gubun='$gubun' AND del_flag='N'
            ORDER BY reg_date DESC";

        return $this->db->getQuery($sql);
    }

    //메모 저장
    public static function memo_save($params) {
        $db = new DB_HELPER;

        $sql = "
                INSERT INTO admin_memo_log (
                    section, gubun, writer, content,
                    del_flag ,reg_ip, reg_id, reg_date
                ) VALUES (
                    '". $params['section'] ."', '". $params['gubun'] ."', '". $params['writer'] ."', '". $params['content'] ."',
                    'N', '". $params['reg_ip'] ."', '". $params['reg_id'] ."', NOW()
                )
            ";

        return $db->insert($sql);
    }

    //메모 삭제
    public function memo_delete($params) {
        $sql = "
                UPDATE admin_memo_log SET
                    del_flag = 'Y',
                    upt_ip = '". $params['upt_ip'] ."',
                    upt_id = '". $params['upt_id'] ."',
                    upt_date = NOW()
                WHERE idx='". $params['idx'] ."'
            ";

        return $this->db->update($sql);
    }
}

function adminMemoSystemSave($section, $gubun, $content, $writer='시스템등록', $reg_id='system') {
    $db = new DB_HELPER;

    $sql = "
            INSERT INTO admin_memo_log (
                section, gubun, writer, content,
                del_flag ,reg_ip, reg_id, reg_date
            ) VALUES (
                '$section', '$gubun', '$writer', '$content',
                'N', '". NOW_IP ."', '$reg_id', NOW()
            )
        ";
    $db->insert($sql);
}