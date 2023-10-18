<?php
/**
 * 게시판
 */

class CLS_BOARD
{
	private $db;

	function __construct()
	{
		$this->db = new DB_HELPER;
	}

	//게시판 공지사항 목록
	public function notice_list($params = null) {
        if (chkBlank($params)) return false;

        $sub_sql = "";

        if ($params['open_flag'] != "") {
            $sub_sql .= " AND a.open_flag = '". $params['open_flag'] ."'";
        }

        $sql = "
				SELECT
					a.*,
					b.category_name
				FROM board a LEFT OUTER JOIN board_category b ON a.category=b.category_idx
				WHERE a.bbs_code='". $params['bbs_code'] ."' AND a.notice_flag='Y' AND a.del_flag='N' $sub_sql
				ORDER BY a.groups DESC, a.sort ASC
			";

		return $this->db->getQuery($sql);
	}

    //게시판 목록
    public function list($params = null, &$total_cnt=0, &$total_page=1) {
        if (chkBlank($params)) return false;

        $sub_sql = "";

        if ($params['open_flag'] != "") {
            $sub_sql .= " AND a.open_flag = '". $params['open_flag'] ."'";
        }

		if ($params['sch_cate'] != "") {
			$sub_sql .= " AND a.category = '". $params['sch_cate'] ."'";
		}

		if ($params['sch_word'] != "") {
			switch ($params['sch_type']) {
				case "1" : $sub_sql .= " AND a.title LIKE '%". $params['sch_word'] ."%'"; break;
				case "2" : $sub_sql .= " AND a.content LIKE '%". $params['sch_word'] ."%'"; break;
				default : $sub_sql .= " AND (
												a.title LIKE '%". $params['sch_word'] ."%' OR
												a.content LIKE '%". $params['sch_word'] ."%'
											)
										"; break;
			}
		}

        $sql = "
				SELECT
					a.*,
					b.category_name
				FROM board a LEFT OUTER JOIN board_category b ON a.category=b.category_idx
				WHERE a.bbs_code='". $params['bbs_code'] ."' AND a.notice_flag='N' AND a.del_flag='N' $sub_sql
				ORDER BY a.groups DESC, a.sort ASC
			";
        $rows = $this->db->getList($sql, $params['page'], $params['list_size'], $total_cnt, $total_page);

        return $rows;
    }

	//게시판 정보 이전,다음글
	public function view_prev_next($params, $gubun, $is_notice = "") {
        if (chkBlank($params)) return false;

        $sub_sql = "";

        if ($params['open_flag'] != "") {
            $sub_sql .= " AND open_flag = '". $params['open_flag'] ."'";
        }

		if ($params['sch_cate'] != "") {
			$sub_sql .= " AND category = '". $params['sch_cate'] ."'";
		}

		if ($is_notice != "") {
			$sub_sql .= " AND notice_flag = '$is_notice'";
		} else {
			$sub_sql .= " AND notice_flag = 'N'";
		}

		if ($params['sch_word'] != "") {
			switch ($params['sch_type']) {
				case "1" : $sub_sql .= " AND title LIKE '%". $params['sch_word'] ."%'"; break;
				case "2" : $sub_sql .= " AND content LIKE '%". $params['sch_word'] ."%'"; break;
				default : $sub_sql .= " AND (
												title LIKE '%". $params['sch_word'] ."%' OR
												content LIKE '%". $params['sch_word'] ."%'
											)
										"; break;
			}
		}

		//게시글 순번 불러오기
		$sql = "
				SELECT row_num FROM (
					SELECT
						idx, @row_num := @row_num + 1 AS row_num
					FROM board a,
						 (SELECT @row_num := 0) b
					WHERE bbs_code='". $params['bbs_code'] ."' AND del_flag='N' $sub_sql
					ORDER BY groups DESC, sort ASC
				) t
				WHERE idx='". $params['idx'] ."'
			";
		$row = $this->db->getQueryValue($sql);
		$row_num = $row['row_num'];

		if ($gubun == "prev") {
			$sql = "
					SELECT idx, title FROM (
						SELECT
							idx, title, @row_num := @row_num + 1 AS row_num
						FROM board a,
							 (SELECT @row_num := 0) b
						WHERE bbs_code='". $params['bbs_code'] ."' AND del_flag='N' $sub_sql
						ORDER BY groups DESC, sort ASC
					) t
					WHERE row_num=($row_num + 1)
				";

			return $this->db->getQueryValue($sql);
		} else if ($gubun == "next") {
			$sql = "
					SELECT idx, title FROM (
						SELECT
							idx, title, @row_num := @row_num + 1 AS row_num
						FROM board a,
							 (SELECT @row_num := 0) b
						WHERE bbs_code='". $params['bbs_code'] ."' AND del_flag='N' $sub_sql
						ORDER BY groups DESC, sort ASC
					) t
					WHERE row_num=($row_num - 1)
				";

			return $this->db->getQueryValue($sql);
		} else  {
			return false;
		}
	}

    //게시판 정보 불러오기
    public function view($params) {
		$sub_sql = "";

        if ($params['notice_flag'] != "") {
            $sub_sql .= " AND a.notice_flag = '". $params['notice_flag'] ."'";
        }

        if ($params['open_flag'] != "") {
            $sub_sql .= " AND a.open_flag = '". $params['open_flag'] ."'";
        }

        $sql = "
				SELECT
					a.*,
					b.category_name
				FROM board a LEFT OUTER JOIN board_category b ON a.category=b.category_idx
				WHERE a.idx='". $params['idx'] ."' AND a.del_flag='N' $sub_sql
			";

        return $this->db->getQueryValue($sql);
    }

    //조회수 업데이트
    public function view_check($idx) {
        if (getSession("VIEW_".$idx) != "Y") {
            $sql = "UPDATE board SET view_cnt = view_cnt + 1 WHERE idx='$idx'AND del_flag='N'";
            $this->db->update($sql);

            setSession("VIEW_".$idx, "Y");
		}
    }

    //게시판 데이터 저장
    public function save_proc($params) {
		$this->db->beginTrans();

        if ($params['mode'] == "reg") {
            $sql = "SELECT IFNULL(MAX(groups), 0) + 1 FROM board WHERE bbs_code='". $params['bbs_code'] ."'";
            $result = $this->db->getQueryValue($sql);
            $params['groups'] = $result[0];

            $sql = "
					INSERT INTO board (
						bbs_code, groups, depth, sort,
						category, writer, passwd, title, content,
						list_img, up_file_1, up_file_2, up_file_3,
						view_cnt, notice_flag, secret_flag, open_flag, del_flag,
						reg_ip, reg_id, reg_date,
						link1, link2
					) VALUES (
						'". $params['bbs_code'] ."', '". $params['groups'] ."', '0', '0',
						'". $params['category'] ."', '". $params['writer'] ."', '". $params['passwd'] ."', '". $params['title'] ."', '". $params['content'] ."',
						'". $params['list_img'] ."', '". $params['up_file_1'] ."', '". $params['up_file_2'] ."', '". $params['up_file_3'] ."',
						'0', '". $params['notice_flag'] ."', '". $params['secret_flag'] ."', '". $params['open_flag'] ."', 'N',
						'". $params['reg_ip'] ."', '". $params['reg_id'] ."', NOW(),
						'". $params['link1'] ."', '". $params['link2'] ."'
					)
				";

			$result = $this->db->insert($sql);
        } else if ($params['mode'] == "reply") {
            $sql = "
                    SELECT
                        groups,
                        (depth + 1) AS depth,
                        sort
                    FROM board a
                    WHERE bbs_code='". $params['bbs_code'] ."' AND idx='". $params['idx'] ."'
                ";
            $result = $this->db->getQueryValue($sql);
            $params['groups'] = $result[0];
            $params['depth']  = $result[1];
            $params['sort']   = $result[2];

            $sql = "UPDATE board set sort = sort + 1 WHERE bbs_code='". $params['bbs_code'] ."' AND groups='". $params['groups'] ."' AND sort > '". $params['sort'] ."'";
            $result = $this->db->update($sql);

			if ($result !== false) {
				$sql = "
						INSERT INTO board (
							bbs_code, groups, depth, sort,
							category, writer, passwd, title, content,
							list_img, up_file_1, up_file_2, up_file_3,
							view_cnt, notice_flag, secret_flag, open_flag, del_flag,
							reg_ip, reg_id, reg_date,
							link1, link2
						) VALUES (
							'". $params['bbs_code'] ."', '". $params['groups'] ."', '". $params['depth'] ."', '". ($params['sort']+1) ."',
							'". $params['category'] ."', '". $params['writer'] ."', '". $params['passwd'] ."', '". $params['title'] ."', '". $params['content'] ."',
							'". $params['list_img'] ."', '". $params['up_file_1'] ."', '". $params['up_file_2'] ."', '". $params['up_file_3'] ."',
							'0', '". $params['notice_flag'] ."', '". $params['secret_flag'] ."', '". $params['open_flag'] ."', 'N',
							'". $params['reg_ip'] ."', '". $params['reg_id'] ."', NOW(),
							'". $params['link1'] ."', '". $params['link2'] ."'
						)
					";

				$result = $this->db->insert($sql);
			}
        } else {
            $sql = "
					UPDATE board SET
						category = '". $params['category'] ."',
						writer = '". $params['writer'] ."',
						passwd = '". $params['passwd'] ."',
						title = '". $params['title'] ."',
						content = '". $params['content'] ."',
						list_img = '". $params['list_img'] ."',
						up_file_1 = '". $params['up_file_1'] ."',
						up_file_2 = '". $params['up_file_2'] ."',
						up_file_3 = '". $params['up_file_3'] ."',
						notice_flag = '". $params['notice_flag'] ."',
						secret_flag = '". $params['secret_flag'] ."',
						open_flag = '". $params['open_flag'] ."',
						link1 = '". $params['link1'] ."',
						link2 = '". $params['link2'] ."',
						upt_ip = '". $params['upt_ip'] ."',
						upt_id = '". $params['upt_id'] ."',
						upt_date = NOW()
					WHERE bbs_code='". $params['bbs_code'] ."' AND idx='". $params['idx'] ."'
				";

			$result = $this->db->update($sql);
        }

		if ($result == false) {
			$this->db->rollbackTrans();

			return false;
		} else {
			$this->db->commitTrans();

			return true;
		}
    }

    //게시판 데이터 삭제
    public function delete_proc($params) {
        $sql = "
				UPDATE board SET
					del_flag = 'Y',
					upt_ip = '". $params['upt_ip'] ."',
					upt_id = '". $params['upt_id'] ."',
					upt_date = NOW()
				WHERE idx='". $params['idx'] ."'
			";

        return $this->db->update($sql);
    }

    //게시판 댓글 전체 목록
    public function comment_list_all($params = null, &$total_cnt=0, &$total_page=1) {
        if (chkBlank($params)) return false;

        $sub_sql = "";

		//댓글 등록일 검색
		if ($params['sch_sdate'] != "") {
			$sub_sql .= " AND TIMESTAMPDIFF(DAY, DATE_FORMAT(reg_date,'%Y-%m-%d'), '". $params['sch_sdate'] ."') <= 0";
		}

		//댓글 등록일 검색
		if ($params['sch_edate'] != "") {
			$sub_sql .= " AND TIMESTAMPDIFF(DAY, DATE_FORMAT(reg_date,'%Y-%m-%d'), '". $params['sch_edate'] ."') >= 0";
		}

		if ($params['sch_gubun'] != "") {
			$sub_sql .= " AND gubun = '". $params['sch_gubun'] ."'";
		}

		if ($params['sch_word'] != "") {
			switch ($params['sch_type']) {
				case "1" : $sub_sql .= " AND reg_id LIKE '%". $params['sch_word'] ."%'"; break;
				case "2" : $sub_sql .= " AND reg_name LIKE '%". $params['sch_word'] ."%'"; break;
				case "2" : $sub_sql .= " AND comment LIKE '%". $params['sch_word'] ."%'"; break;
				default : $sub_sql .= " AND (
												reg_id LIKE '%". $params['sch_word'] ."%' OR
												reg_name LIKE '%". $params['sch_word'] ."%' OR
												comment LIKE '%". $params['sch_word'] ."%'
											)
										"; break;
			}
		}

        $sql = "
				WITH list AS (
					SELECT
						b.bbs_code AS gubun,
						a.idx,
						b.title,
						a.comment,
						c.usr_id AS reg_id,
						c.usr_name AS reg_name,
						a.reg_date
					FROM board_comment a
						LEFT OUTER JOIN board b ON a.bbs_idx=b.idx
						LEFT OUTER JOIN member c ON a.reg_id = c.usr_id
					WHERE a.del_flag='N'
				)
				SELECT
					*,
					CASE
						WHEN gubun='notice' THEN '공지사항'
						WHEN gubun='it' THEN 'IT뉴스 및 정보'
						WHEN gubun='data' THEN '자료실'
						WHEN gubun='qna' THEN 'Q&A 게시판'
						WHEN gubun='content' THEN '콘텐츠 강의'
					END AS gubun_name
				FROM list WHERE 1 $sub_sql
				ORDER BY reg_date DESC
			";

        $rows = $this->db->getList($sql, $params['page'], $params['list_size'], $total_cnt, $total_page);

        return $rows;
    }

    //게시판 댓글 목록
    public function comment_list($params = null, &$total_cnt=0, &$total_page=1) {
        if (chkBlank($params)) return null;

		$sub_sql = "";

		if ($params['bbs_idx'] != '') {
			$sub_sql .= " AND a.bbs_idx='". $params['bbs_idx'] ."'";
		}

        $sql = "
				SELECT
					a.*,
					c.usr_name AS reg_name,
					c.nick_name AS reg_nick
				FROM board_comment a LEFT OUTER JOIN board b ON a.bbs_idx=b.idx
					LEFT OUTER JOIN member c ON a.reg_id = c.usr_id
				WHERE a.del_flag='N' $sub_sql
				ORDER BY a.reg_date DESC
			";

		return $this->db->getList($sql, $params['page'], $params['list_size'], $total_cnt, $total_page);
	}

    //게시판 댓글 정보 불러오기
    public function comment_view($idx) {
        $sql = "
				SELECT
					a.*,
					c.usr_name AS reg_name,
					c.nick_name AS reg_nick
				FROM board_comment a LEFT OUTER JOIN board b ON a.bbs_idx=b.idx
					LEFT OUTER JOIN member c ON a.reg_id = c.usr_id
				WHERE a.idx='$idx' AND a.del_flag='N'
				ORDER BY a.reg_date DESC
			";

        return $this->db->getQueryValue($sql);
    }

	//게시판 댓글 저장
	function comment_save_proc($params) {
		$this->db->beginTrans();

		if (chkBlank($params['cmt_idx'])) {
			$sql = "
					INSERT INTO board_comment (
						bbs_idx, comment,
						del_flag, reg_ip, reg_id, reg_date
					) VALUES (
						'". $params['bbs_idx'] ."', '". $params['comment'] ."',
						'N', '". $params['reg_ip'] ."', '". $params['reg_id'] ."', NOW()
					)
				";
			$result = $this->db->insert($sql);
		} else {
			$sql = "
					UPDATE board_comment SET
						comment = '". $params['comment'] ."',
						upt_ip = '". $params['upt_ip'] ."',
						upt_id = '". $params['upt_id'] ."',
						upt_date = NOW()
					WHERE bbs_idx='". $params['bbs_idx'] ."' AND idx='". $params['cmt_idx'] ."'
				";
			$result = $this->db->update($sql);
		}

		//댓글수 없데이트
		if ($result !== false) {
			$sql = "
					UPDATE board SET
						comment_cnt=(SELECT COUNT(*) FROM board_comment WHERE bbs_idx='". $params['bbs_idx'] ."' AND del_flag='N'),
						upt_ip = '". $params['upt_ip'] ."',
						upt_id = '". $params['upt_id'] ."',
						upt_date = NOW()
					WHERE idx='". $params['bbs_idx'] ."'
				";

			$result = $this->db->update($sql);
		}

		if ($result == false) {
			$this->db->rollbackTrans();

			return false;
		} else {
			$this->db->commitTrans();

			return true;
		}
	}

	//게시판 댓글 삭제
	function comment_delete_proc($params) {
		$this->db->beginTrans();

		$sub_sql = "";

		if (chkBlank($params['bbs_idx'])) {
			$cmt_view = $this->comment_view($params['idx']);
			$params['bbs_idx'] = $cmt_view['bbs_idx'];
		}

		if ($params['reg_id'] != '') {
			$sub_sql .= " AND reg_id='". $params['reg_id'] ."'";
		}

		//댓글 삭제 처리
        $sql = "
				UPDATE board_comment SET
					del_flag = 'Y',
					upt_ip = '". $params['upt_ip'] ."',
					upt_id = '". $params['upt_id'] ."',
					upt_date = NOW()
				WHERE bbs_idx='". $params['bbs_idx'] ."' AND idx='". $params['idx'] ."' $sub_sql
			";
		$result = $this->db->update($sql);

		//댓글수 없데이트
		if ($result !== false) {
			$sql = "
					UPDATE board SET
						comment_cnt=(SELECT COUNT(*) FROM board_comment WHERE bbs_idx='". $params['bbs_idx'] ."' AND del_flag='N')
					WHERE idx='". $params['bbs_idx'] ."'
				";

			$result = $this->db->update($sql);
		}

		if ($result == false) {
			$this->db->rollbackTrans();

			return false;
		} else {
			$this->db->commitTrans();

			return true;
		}
	}

    //첨부파일 삭제
    public function file_delete($params) {
		$view = $this->view($params);
		if ($view == false) return null;

		if ($params['gubun'] == "") $params['gubun'] = "attach";

		//첨부파일 정보
		if ($params['gubun'] == "attach") {
			$up_file = $view['up_file_'. $params['fnum']];

			$sql = "
					UPDATE board SET
						up_file_". $params['fnum'] ." = '',
						upt_ip = '". $params['upt_ip'] ."',
						upt_id = '". $params['upt_id'] ."',
						upt_date = NOW()
					WHERE idx='". $params['idx'] ."'
				";
		} else {
			$up_file = $view['list_img'];

			$sql = "
					UPDATE board SET
						list_img = '',
						upt_ip = '". $params['upt_ip'] ."',
						upt_id = '". $params['upt_id'] ."',
						upt_date = NOW()
					WHERE idx='". $params['idx'] ."'
				";
		}

		$result = $this->db->update($sql);

		if ($result !== false && $up_file != "") {
			if ($params['gubun'] == "attach") {
				fileDelete("/upload/board/attach/", getUpfileName($up_file));
			} else {
				fileDelete("/upload/board/thumb/", getUpfileName($up_file));
			}
		}

        return $result;
	}

	//문의게시판 목록
	public function inquiry_list($params = null, &$total_cnt=0, &$total_page=1) {
        if (chkBlank($params)) return false;

        $sub_sql = "";

		if ($params['sch_word'] != "") {
			switch ($params['sch_type']) {
				case "1" : $sub_sql .= " AND name LIKE '%". $params['sch_word'] ."%'"; break;
				case "2" : $sub_sql .= " AND phone LIKE '%". $params['sch_word'] ."%'"; break;
				case "3" : $sub_sql .= " AND email LIKE '%". $params['sch_word'] ."%'"; break;
				case "4" : $sub_sql .= " AND content LIKE '%". $params['sch_word'] ."%'"; break;
				default : $sub_sql .= " AND (
												name LIKE '%". $params['sch_word'] ."%' OR
												phone LIKE '%". $params['sch_word'] ."%' OR
												email LIKE '%". $params['sch_word'] ."%' OR
												content LIKE '%". $params['sch_word'] ."%'
											)
										"; break;
			}
		}

        $sql = "
				SELECT
					*
				FROM board_inquiry
				WHERE del_flag='N' $sub_sql
				ORDER BY reg_date DESC
			";
        $rows = $this->db->getList($sql, $params['page'], $params['list_size'], $total_cnt, $total_page);

        return $rows;
	}

	//문의게시판 상세
	public function inquiry_view($idx) {
        $sql = "
				SELECT
					*
				FROM board_inquiry
				WHERE idx='$idx' AND del_flag='N'
				ORDER BY reg_date DESC
			";

		$view = $this->db->getQueryValue($sql);

		if ($view == false) {
			return false;
		} else {
			if ($view['view_flag'] == 'N') {
				$sql = "
						UPDATE board_inquiry SET
							view_flag = 'Y',
							view_confirm_dt = NOW()
						WHERE idx='$idx'
					";
				$this->db->update($sql);
			}

			return $view;
		}
	}

	//문의게시판 저장
	public function inquiry_save_proc($params) {
		$sql = "
				INSERT INTO board_inquiry (
					name, phone, email, content,
					del_flag, reg_ip, reg_id, reg_date
				) VALUES (
					'". $params['name'] ."', '". $params['phone'] ."', '". $params['email'] ."', '". $params['content'] ."',
					'N', '". $params['reg_ip'] ."', '". $params['reg_id'] ."', NOW()
				)
			";
		return $this->db->insert($sql);
	}

	//문의게시판 답변
	public function inquiry_answer_save_proc($params) {
		$sql = "
			UPDATE board_inquiry SET
				answer_content = '". $params['answer_content'] ."',
				answer_date = NOW(),
				upt_ip = '". $params['upt_ip'] ."',
				upt_id = '". $params['upt_id'] ."',
				upt_date = NOW()
			WHERE idx='". $params['idx'] ."'
		";

		return $this->db->update($sql);
	}

	//문의게시판 삭제
	public function inquiry_delete_proc($params) {
		$sql = "
			UPDATE board_inquiry SET
				del_flag = 'Y',
				upt_ip = '". $params['upt_ip'] ."',
				upt_id = '". $params['upt_id'] ."',
				upt_date = NOW()
			WHERE idx='". $params['idx'] ."'
		";

		return $this->db->update($sql);
	}

	//카테고리 목록
	public static function category_list($bbs_code, $depth, $category_idx = null) {
		$db = new DB_HELPER();

		$sub_sql = "";

		if ($depth > 1 && chkBlank($category_idx)) return null;

        if ($category_idx != "") {
            $sub_sql .= " AND parent_idx = '$category_idx'";
        }

		$sql = "
				SELECT
					*
				FROM board_category
				WHERE bbs_code='$bbs_code' AND depth='$depth' AND del_flag='N' $sub_sql
				ORDER BY sort, category_idx
			";

		return $db->getQuery($sql);
	}

	//부모 카테고리 목록
	public function parent_category_list($category_idx) {
		$sql = "
				SELECT * FROM (
					SELECT
						lst.category_idx,
						lst.parent_idx,
						cate.depth,
						cate.sort,
						cate.bbs_code,
						cate.page_code,
						cate.category_name
					FROM (
						SELECT
							_idx AS category_idx,
							parent_idx,
							@depth := @depth + 1 AS depth
						FROM (
							SELECT
								@idx AS _idx,
								(
									SELECT @idx := parent_idx
									FROM  board_category
									WHERE category_idx = _idx
								) AS parent_idx,
								@d := @d + 1 AS depth
							FROM (
								SELECT
									@idx := $category_idx,
									@d := 0,
									@depth := 0
							) vars, board_category
							WHERE @idx <> 0
							ORDER BY depth DESC
						) tmp
					) lst INNER JOIN board_category cate ON lst.category_idx = cate.category_idx
					WHERE cate.depth > 0
				) t
				WHERE 1
			";

		return $this->db->getQuery($sql);
	}

	//카테고리 상세
	public function category_view($category) {
		$sql = "
				SELECT
					*
				FROM board_category
				WHERE category_idx='$category' AND del_flag='N'
				ORDER BY sort, category_idx
			";

		return $this->db->getQueryValue($sql);
	}

	//카테고리명
	public static function category_name($category) {
		$db = new DB_HELPER;

		$sql = "
				SELECT
					category_name
				FROM board_category
				WHERE category_idx='$category' AND del_flag='N'
			";
		$row = $db->getQueryValue($sql);

		return $row['category_name'];
	}

	//페이지코드별 카테고리 고유번호 불러오기
	public static function get_category($page_code) {
		$db = new DB_HELPER;

		$sql = "
				SELECT
					category_idx
				FROM board_category
				WHERE page_code='$page_code' AND del_flag='N'
			";

		$row = $db->getQueryValue($sql);

		return $row['category_idx'];
	}

	//게시판 코드 체크
	public function bbs_code_check($bbs_code) {
		$code_arr = array('notice', 'faq');

		$check_cnt = 0;
		foreach($code_arr AS $item) {
			if ($item == $bbs_code) $check_cnt++;
		}

		if ($check_cnt == 0) {
			return false;
		} else {
			return true;
		}
	}

	//게시판 공지사항 사용 체크
	public function isNotice($bbs_code) {
		$code_arr = array('notice');

		$check_cnt = 0;
		foreach($code_arr AS $item) {
			if ($item == $bbs_code) $check_cnt++;
		}

		if ($check_cnt == 0) {
			return false;
		} else {
			return true;
		}
	}

	//게시판 카테고리 사용 체크
	public function isCategory($bbs_code) {
		$code_arr = array('faq');

		$check_cnt = 0;
		foreach($code_arr AS $item) {
			if ($item == $bbs_code) $check_cnt++;
		}

		if ($check_cnt == 0) {
			return false;
		} else {
			return true;
		}
	}

	//게시판 에디터 사용 체크
	public function isHtml($bbs_code) {
		$code_arr = array('notice', 'faq');

		$check_cnt = 0;
		foreach($code_arr AS $item) {
			if ($item == $bbs_code) $check_cnt++;
		}

		if ($check_cnt == 0) {
			return false;
		} else {
			return true;
		}
	}

	//게시판 목록이미지 사용 체크
	public function isListThumb($bbs_code) {
		$code_arr = array();

		$check_cnt = 0;
		foreach($code_arr AS $item) {
			if ($item == $bbs_code) $check_cnt++;
		}

		if ($check_cnt == 0) {
			return false;
		} else {
			return true;
		}
	}

	//게시판 첨부파일 사용 체크
	public function isUpfile($bbs_code) {
		$code_arr = array('notice');

		$check_cnt = 0;
		foreach($code_arr AS $item) {
			if ($item == $bbs_code) $check_cnt++;
		}

		if ($check_cnt == 0) {
			return false;
		} else {
			return true;
		}
	}

	//게시판 외부링크 사용 체크
	public function isLink($bbs_code) {
		$code_arr = array();

		$check_cnt = 0;
		foreach($code_arr AS $item) {
			if ($item == $bbs_code) $check_cnt++;
		}

		if ($check_cnt == 0) {
			return false;
		} else {
			return true;
		}
	}

	//게시판 댓글 사용 체크
	public function isComment($bbs_code) {
		$code_arr = array();

		$check_cnt = 0;
		foreach($code_arr AS $item) {
			if ($item == $bbs_code) $check_cnt++;
		}

		if ($check_cnt == 0) {
			return false;
		} else {
			return true;
		}
	}

	//게시판 내용 html 태그 제거
	public static function stripTags($content, $length = 300) {
		$content = htmlDecode($content);
		$content = replaceContTag($content);
		$content = preg_replace('/\n/', '', $content);
		$content = preg_replace('/\s{2,}/', ' ', $content);
		$content = returnToCut($content, $length, '…', false);

		return $content;
	}
}
