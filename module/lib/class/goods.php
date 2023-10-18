<?php
/**
 * 상품관리
 */

class CLS_GOODS
{
	private $db;

	function __construct()
	{
		$this->db = new DB_HELPER;
	}

	//상품, 상품안내, 상세일정 항목 첨부 이미지 삭제
	public function upfile_delete_proc($params) {
		if ($params['gubun'] == 'thumb') {
			//상품 첨부 파일
			$view = $this->goods_view($params['idx']);
			if ($view == false) return false;

			$up_file = $view['up_file_'. $params['fnum']];

			$sql = "
					UPDATE goods SET
						up_file_". $params['fnum'] ." = '',
						upt_ip = '". $params['upt_ip'] ."',
						upt_id = '". $params['upt_id'] ."',
						upt_date = NOW()
					WHERE idx = '". $params['idx'] ."'
				";
			if ($this->db->update($sql) == false) {
				return false;
			} else {
				fileDelete('/upload/goods/thumb', getUpfileName($up_file));

				adminMemoSystemSave('goods_write', $params['idx'], '상품 상품이미지'. $params['fnum'] .'을 삭제 하였습니다.');

				return true;
			}
		} else {
			return false;
		}
	}

	//상품 등록
	public function goods_save_proc($params, &$goods_idx) {
		if (chkBlank($params['idx'])) {
			$sql = "
					INSERT INTO goods (
						category, title,
						up_file_1, up_file_2, up_file_3, up_file_4, up_file_5,
						option_1, option_2, option_3, option_4,
						option_5, option_6, option_7, option_8,
						option_3_amt, option_4_amt, option_5_amt, option_6_amt,
						day1_amt, day7_amt, day30_amt, agency_fee, content,
						open_flag, keyword, total_stock_cnt,
						main_open_flag, main_sort, sort,
						del_flag, reg_ip, reg_id, reg_date
					) VALUES (
						'". $params['category']. "', '". $params['title'] ."',
						'". $params['up_file_1']. "', '". $params['up_file_2'] ."', '". $params['up_file_3'] ."', '". $params['up_file_4']. "', '". $params['up_file_5'] ."',
						'". $params['option_1']. "', '". $params['option_2'] ."', '". $params['option_3'] ."', '". $params['option_4'] ."',
						'". $params['option_5'] ."', '". $params['option_6'] ."', '". $params['option_7'] ."', '". $params['option_8'] ."',
						'". $params['option_3_amt']. "', '". $params['option_4_amt'] ."', '". $params['option_5_amt'] ."', '". $params['option_6_amt'] ."',
						'". $params['day1_amt']. "', '". $params['day7_amt'] ."', '". $params['day30_amt'] ."', '". $params['agency_fee'] ."', '". $params['content'] ."',
						'". $params['open_flag']. "', '". $params['keyword']. "', '0',
						'". $params['main_open_flag']. "', '". $params['main_sort']. "', '". $params['sort']. "',
						'N', '". $params['reg_ip'] ."', '". $params['reg_id'] ."', NOW()
					)
				";
			$result = $this->db->insert($sql);

			$goods_idx = $this->db->getLastInsertId();

			if ($result != false) adminMemoSystemSave('goods_write', $goods_idx, '상품을 등록하였습니다.');
		} else {
			$sql = "
					UPDATE goods SET
						category = '". $params['category'] ."',
						title = '". $params['title'] ."',
						up_file_1 = '". $params['up_file_1'] ."',
						up_file_2 = '". $params['up_file_2'] ."',
						up_file_3 = '". $params['up_file_3'] ."',
						up_file_4 = '". $params['up_file_4'] ."',
						up_file_5 = '". $params['up_file_5'] ."',
						up_file_6 = '". $params['up_file_6'] ."',
						option_1 = '". $params['option_1'] ."',
						option_2 = '". $params['option_2'] ."',
						option_3 = '". $params['option_3'] ."',
						option_4 = '". $params['option_4'] ."',
						option_5 = '". $params['option_5'] ."',
						option_6 = '". $params['option_6'] ."',
						option_7 = '". $params['option_7'] ."',
						option_8 = '". $params['option_8'] ."',
						option_3_amt = '". $params['option_3_amt'] ."',
						option_4_amt = '". $params['option_4_amt'] ."',
						option_5_amt = '". $params['option_5_amt'] ."',
						option_6_amt = '". $params['option_6_amt'] ."',
						day1_amt = '". $params['day1_amt'] ."',
						day7_amt = '". $params['day7_amt'] ."',
						day30_amt = '". $params['day30_amt'] ."',
						agency_fee = '". $params['agency_fee'] ."',
						content = '". $params['content'] ."',
						open_flag = '". $params['open_flag'] ."',
						keyword = '". $params['keyword'] ."',
						main_open_flag = '". $params['main_open_flag'] ."',
						main_sort = '". $params['main_sort'] ."',
						sort = '". $params['sort'] ."',
						upt_ip = '". $params['upt_ip'] ."',
						upt_id = '". $params['upt_id'] ."',
						upt_date = NOW()
					WHERE idx='". $params['idx'] ."'
				";
			$result = $this->db->update($sql);

			$goods_idx = $params['idx'];

			if ($result != false) adminMemoSystemSave('goods_write', $goods_idx, '상품을 수정하였습니다.');
		}

		return $result;
	}

	//상품 상세보기
	public function goods_view($goods_idx, $open_flag='') {
		$sub_sql = "";

		if ($open_flag != '') {
			$sub_sql .= " AND open_flag='Y'";
		}

		$sql = "
				SELECT
					*
				FROM goods
				WHERE del_flag='N' AND idx='$goods_idx' $sub_sql
			";

		return $this->db->getQueryValue($sql);
	}

	//상품 관리자 목록 보기
	public function goods_list_admin($params = null, &$total_cnt=0, &$total_page=1) {
		if (chkBlank($params)) return false;

		$sub_sql = "";

		//등록일 검색
		if ($params['sch_sdate'] != "") {
			$sub_sql .= " AND TIMESTAMPDIFF(DAY, DATE_FORMAT(reg_date,'%Y-%m-%d'), '". $params['sch_sdate'] ."') <= 0";
		}

		//등록일 검색
		if ($params['sch_edate'] != "") {
			$sub_sql .= " AND TIMESTAMPDIFF(DAY, DATE_FORMAT(reg_date,'%Y-%m-%d'), '". $params['sch_edate'] ."') >= 0";
		}

		//분류 검색
		if ($params['sch_cate'] != "") {
			$sub_sql .= " AND category='". $params['sch_cate'] ."'";
		}

		//노출상태 검색
		if ($params['sch_open'] != "") {
			$sub_sql .= " AND open_flag='". $params['sch_open'] ."'";
		}

		//검색어 (제목, 특징, 키워드)
		if ($params['sch_word'] != "") {
			$sub_sql .= " AND (title LIKE '%". $params['sch_word'] ."%' OR content LIKE '%". $params['sch_word'] ."%' OR keyword LIKE '%". $params['sch_word'] ."%')";
		}

		$sql = "
				SELECT
					*
				FROM goods
				WHERE del_flag='N' $sub_sql
				ORDER BY sort DESC, reg_date DESC
			";

		return $this->db->getList($sql, $params['page'], $params['list_size'], $total_cnt, $total_page);
	}

	//상품 목록 보기
	public function goods_list($params = null, &$total_cnt=0, &$total_page=1) {
		if (chkBlank($params)) return false;

		$sub_sql = "";

		//카테고리 분류 검색
		if ($params['gubun'] != "") {
			$sub_sql .= " AND category='". $params['gubun'] ."'";
		}

		$sql = "
				SELECT
					idx, title, up_file_1, option_1, option_2, option_7, option_8, day1_amt, content, keyword, total_stock_cnt,
					(
						SELECT COUNT(idx) FROM booking WHERE status IN ('10', '20', '30', '40') AND del_flag='N'
					) AS popular_cnt,
					(
						SELECT IFNULL(SUM(stock_cnt),0) FROM goods_stock WHERE goods_idx=a.idx AND TIMESTAMPDIFF(DAY, DATE_FORMAT(sdate,'%Y-%m-%d'), CURDATE()) <= 0 AND del_flag='N'
					) AS rest_stock_cnt
				FROM goods a
				WHERE open_flag='Y' AND total_stock_cnt > 0 AND del_flag='N' $sub_sql
				ORDER BY sort DESC, popular_cnt DESC, idx DESC
			";

		return $this->db->getList($sql, $params['page'], $params['list_size'], $total_cnt, $total_page);
	}

	//상품 일정별 재고 목록
	public function stock_list($params = null, &$total_cnt=0, &$total_page=1) {
		if (chkBlank($params)) return false;

		$sub_sql = "";

		if (chkBlank($params['page'])) $params['page'] = 1;
		if (chkBlank($params['list_size'])) $params['list_size'] = 999999;

		//날짜 검색
		if ($params['sch_sdate'] != "") {
			$sub_sql .= " AND TIMESTAMPDIFF(DAY, DATE_FORMAT(a.sdate,'%Y-%m-%d'), '". $params['sch_sdate'] ."') <= 0";
		}

		//날짜 검색
		if ($params['sch_edate'] != "") {
			$sub_sql .= " AND TIMESTAMPDIFF(DAY, DATE_FORMAT(a.sdate,'%Y-%m-%d'), '". $params['sch_edate'] ."') >= 0";
		}

		//재고여부 검색
		if ($params['sch_stock'] != "") {
			if ($params['sch_stock'] == 'Y') {
				$sub_sql .= " AND a.stock_cnt > 0";
			} else {
				$sub_sql .= " AND a.stock_cnt <= 0";
			}
		}

        $sql = "
				SELECT
					a.*,
					(
						SELECT COUNT(*) FROM booking WHERE goods_idx=a.goods_idx AND rental_sdate=a.sdate AND status IN ('10', '20', '30', '40') AND del_flag='N'
					) AS booking_cnt
				FROM goods_stock a INNER JOIN goods b ON a.goods_idx=b.idx
				WHERE a.goods_idx='". $params['goods_idx'] ."' AND a.del_flag='N' $sub_sql
				ORDER BY a.sdate
			";
		if (chkBlank($params['page'])) {
			$rows = $this->db->getQuery($sql);

			$total_cnt = count($rows);
		} else {
			$rows = $this->db->getList($sql, $params['page'], $params['list_size'], $total_cnt, $total_page);
		}

        return $rows;
	}

	//상품 일정별 재고 상세보기
	public function stock_view($stock_idx) {
		$sql = "
				SELECT
					a.*,
					(
						SELECT COUNT(*) FROM booking WHERE goods_idx=a.goods_idx AND rental_sdate=a.sdate AND status IN ('10', '20', '30', '40') AND del_flag='N'
					) AS booking_cnt
				FROM goods_stock a INNER JOIN goods b ON a.goods_idx=b.idx
				WHERE a.stock_idx='$stock_idx' AND a.del_flag='N'
			";

		return $this->db->getQueryValue($sql);
	}

	//상품 일정별 재고  등록,수정
	public function stock_save_proc($params, &$err_msg='') {
		global $DEV_MODE;

		$this->db->beginTrans();

		$loop_sdate = $params['sdate'];
		$loop_edate = $params['edate'];

		$group_idxs = '';
		while (dateDiff("d", $loop_sdate, $loop_edate) >= 0) {
			$sdate = $loop_sdate;

			if ($group_idxs != '') $group_idxs .= ', ';

			if (chkBlank($params['stock_idx'])) {
				//출발일이 중복된 경우 수정, 없으면 추가
				$sql = "SELECT stock_idx FROM goods_stock WHERE goods_idx='". $params['goods_idx'] ."' AND sdate='". $sdate ."' AND del_flag='N'";
				$stock_view = $this->db->getQueryValue($sql);
				if ($stock_view == false) {
					$sql = "
							INSERT INTO goods_stock (
								goods_idx, sdate, stock_cnt,
								del_flag, reg_ip, reg_id, reg_date
							) VALUES (
								'". $params['goods_idx'] ."', '". $sdate ."', '". $params['stock_cnt'] ."',
								'N', '". $params['reg_ip'] ."', '". $params['reg_id'] ."', NOW()
							)
						";
					if ($this->db->insert($sql) == false) {
						$this->db->rollbackTrans();

						$err_msg = "F001".iif($this->db->error != '', iif($DEV_MODE, ' '. $this->db->error, ''), '');

						return false;
					} else {
						$group_idxs .= $this->db->getLastInsertId();
					}
				} else {
					$sql = "
							UPDATE goods_stock SET
								stock_cnt = '". $params['stock_cnt'] ."',
								upt_ip = '". $params['upt_ip']. "',
								upt_id = '". $params['upt_id']. "',
								upt_date = NOW()
							WHERE goods_idx='". $params['goods_idx'] ."' AND sdate='". $sdate ."'
						";
					if ($this->db->update($sql) == false) {
						$this->db->rollbackTrans();
						$err_msg = "F002".iif($this->db->error != '', iif($DEV_MODE, ' '. $this->db->error, ''), '');

						return false;
					} else {
						$group_idxs .= $stock_view['stock_idx'];
					}
				}
			} else {
				$sql = "
						UPDATE goods_stock SET
							stock_cnt = '". $params['stock_cnt'] ."',
							upt_ip = '". $params['upt_ip'] ."',
							upt_id = '". $params['upt_id'] ."',
							upt_date = NOW()
						WHERE stock_idx='". $params['stock_idx'] ."'
					";
				if ($this->db->update($sql) == false) {
					$this->db->rollbackTrans();
					$err_msg = "F003".iif($this->db->error != '', iif($DEV_MODE, ' '. $this->db->error, ''), '');

					return false;
				}
			}

			$loop_sdate = dateAdd("d", 1, $loop_sdate);
		}

		//상품재고 업데이트
		$sql = "
				UPDATE goods SET
					total_stock_cnt = (SELECT SUM(stock_cnt) FROM goods_stock WHERE goods_idx='". $params['goods_idx'] ."' AND del_flag='N')
				WHERE idx = '". $params['goods_idx'] ."'
			";
		$this->db->update($sql);

		if (chkBlank($params['stock_idx'])) {
			if ($params['gubun']=='reg') {
				adminMemoSystemSave('goods_write', $params['goods_idx'], '상품재고를 일괄 등록 등록하였습니다. ['. $group_idxs .']');
			} else {
				adminMemoSystemSave('goods_write', $params['goods_idx'], '상품재고를 일괄 수정하였습니다. ['. $group_idxs .']');
			}
		} else {
			adminMemoSystemSave('goods_write', $params['goods_idx'], '상품재고를 수정하였습니다. ['. $params['stock_idx'] .']');
		}

		$this->db->commitTrans();

		return true;
	}

	//상품 일정별 재고 삭제
	public function stock_delete_proc($params) {
		$sql = "
				UPDATE goods_stock SET
					del_flag = 'Y',
					upt_ip = '". $params['upt_ip'] ."',
					upt_id = '". $params['upt_id'] ."',
					upt_date = NOW()
				WHERE stock_idx='". $params['stock_idx'] ."'
			";
		if ($this->db->update($sql) == false) {
			return false;
		} else {
			//상품재고 업데이트
			$sql = "
					UPDATE goods SET
						total_stock_cnt = (SELECT SUM(stock_cnt) FROM goods_stock WHERE goods_idx='". $params['goods_idx'] ."' AND del_flag='N')
					WHERE idx = '". $params['goods_idx'] ."'
				";
			$this->db->update($sql);

			adminMemoSystemSave('goods_write', $params['goods_idx'], '상품재고를 삭제하였습니다. ['. $params['stock_idx'] .']');

			return true;
		}
	}



	//선택상품 목록 보기
	public function choice_tour_list($params = null, &$total_cnt=0, &$total_page=1) {
		if (chkBlank($params)) return false;

		$sub_sql = "";

		$sql = "
				SELECT
					idx, title, introduction, adult_amt, child_amt, infant_amt, agency_fee, up_file_1, sdate, edate
				FROM choice_tour_goods
				WHERE open_flag='Y' AND del_flag='N' $sub_sql
				ORDER BY sort DESC, idx DESC
			";

		return $this->db->getList($sql, $params['page'], $params['list_size'], $total_cnt, $total_page);
	}

	//선택상품 관리자 목록 보기
	public function choice_tour_list_admin($params = null, &$total_cnt=0, &$total_page=1) {
		if (chkBlank($params)) return false;

		$sub_sql = "";

		//등록일 검색
		if ($params['sch_sdate'] != "") {
			$sub_sql .= " AND TIMESTAMPDIFF(DAY, DATE_FORMAT(reg_date,'%Y-%m-%d'), '". $params['sch_sdate'] ."') <= 0";
		}

		//등록일 검색
		if ($params['sch_edate'] != "") {
			$sub_sql .= " AND TIMESTAMPDIFF(DAY, DATE_FORMAT(reg_date,'%Y-%m-%d'), '". $params['sch_edate'] ."') >= 0";
		}


		//노출상태 검색
		if ($params['sch_open'] != "") {
			$sub_sql .= " AND open_flag='". $params['sch_open'] ."'";
		}

		//검색어 (제목, 특징, 키워드)
		if ($params['sch_word'] != "") {
			$sub_sql .= " AND (title LIKE '%". $params['sch_word'] ."%' OR introduction LIKE '%". $params['sch_word'] ."%')";
		}

		$sql = "
				SELECT
					*
				FROM choice_tour_goods
				WHERE del_flag='N' $sub_sql
				ORDER BY sort DESC, reg_date DESC
			";

		return $this->db->getList($sql, $params['page'], $params['list_size'], $total_cnt, $total_page);
	}

	//선택상품 상세보기
	public function choice_tour_view($goods_idx, $open_flag='') {
		$sub_sql = "";

		if ($open_flag != '') {
			$sub_sql .= " AND open_flag='Y'";
		}

		$sql = "
				SELECT
					*
				FROM choice_tour_goods
				WHERE del_flag='N' AND idx='$goods_idx' $sub_sql
			";

		return $this->db->getQueryValue($sql);
	}

	//선택상품 등록
	public function choice_tour_save_proc($params, &$goods_idx) {
		if (chkBlank($params['idx'])) {
			$sql = "
					INSERT INTO choice_tour_goods (
                        title, tour_time, introduction,
                        adult_amt, child_amt, infant_amt, agency_fee,
                        up_file_1, up_file_2, content, notice,
                        sdate, edate, open_flag,
                        sort, del_flag, reg_ip, reg_id, reg_date
					) VALUES (
                        '". $params['title']. "', '". $params['tour_time']. "', '". $params['introduction']. "',
                        '". $params['adult_amt']. "', '". $params['child_amt']. "', '". $params['infant_amt']. "', '". $params['agency_fee']. "',
                        '". $params['up_file_1']. "', '". $params['up_file_2']. "', '". $params['content']. "', '". $params['notice']. "',
                        '". $params['sdate']. "', '". $params['edate']. "', '". $params['open_flag']. "',
                        '". $params['sort']. "', 'N', '". $params['reg_ip'] ."', '". $params['reg_id'] ."', NOW()
					)
				";
			$result = $this->db->insert($sql);

			$goods_idx = $this->db->getLastInsertId();

			if ($result != false) adminMemoSystemSave('choice_write', $goods_idx, '상품을 등록하였습니다.');
		} else {
			$sql = "
					UPDATE choice_tour_goods SET
                        title        = '". $params['title'] ."',
                        tour_time    = '". $params['tour_time'] ."',
                        introduction = '". $params['introduction'] ."',
                        adult_amt    = '". $params['adult_amt'] ."',
                        child_amt    = '". $params['child_amt'] ."',
                        infant_amt   = '". $params['infant_amt'] ."',
                        agency_fee   = '". $params['agency_fee'] ."',
                        up_file_1    = '". $params['up_file_1'] ."',
                        up_file_2    = '". $params['up_file_2'] ."',
                        content      = '". $params['content'] ."',
                        notice       = '". $params['notice'] ."',
                        sdate        = '". $params['sdate'] ."',
                        edate        = '". $params['edate'] ."',
                        open_flag    = '". $params['open_flag'] ."',
						sort         = '". $params['sort'] ."',
						upt_ip       = '". $params['upt_ip'] ."',
						upt_id       = '". $params['upt_id'] ."',
						upt_date     = NOW()
					WHERE idx='". $params['idx'] ."'
				";
			$result = $this->db->update($sql);

			$goods_idx = $params['idx'];

			if ($result != false) adminMemoSystemSave('choice_write', $goods_idx, '상품을 수정하였습니다.');
		}

		return $result;
	}
}
