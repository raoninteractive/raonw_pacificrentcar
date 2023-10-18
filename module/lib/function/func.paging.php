<?php
	//----------------------------------------------------------------------
	// Description
	//		총 페이지수 구하기
	// Params
	//		total_cnt : 총 페이지 개수
	//		list_size : 게시물 노출개수
	// Return
	//
	//----------------------------------------------------------------------
	function totalPage($total_cnt, $list_size) {
		if ($total_cnt == 0) {
			return 1;
		} else if (($total_cnt%$list_size) == 0) {
			return (int)($total_cnt / $list_size);
		} else {
			return (int)($total_cnt / $list_size) + 1;
		}
	}

	//----------------------------------------------------------------------
	// Description
	//		관리자 페이징 처리
	// Params
	//		total_page  : 총 페이지 수
	//		block_size  : 페이징 블럭 수
	//		page        : 현재 페이지 번호
	//		page_params : 파라미터 값
	//		list_page   : 리스트 페이지 주소
	// Return
	//
	//----------------------------------------------------------------------
	function adminPaging($total_page, $block_size, $page, $page_params, $list_page = "") {
		if ($page%$block_size == 0) {
			$min_page = (int)($page / $block_size - 1) * $block_size + 1;
		} else {
			$min_page = (int)($page / $block_size) * $block_size + 1;
		}

		if (($min_page + $block_size - 1) > $total_page) {
			$max_page = $total_page;
		} else {
			$max_page = $min_page + $block_size - 1;
		}

		if ($page == 1) {
			echo '<a href="javascript:;" class="btn_page first">처음으로</a>' . PHP_EOL;
		} else {
			echo '<a href="'. $list_page .'?page=1'. $page_params .'" class="btn_page first">처음으로</a>' . PHP_EOL;
		}

		if ($min_page > $block_size) {
			echo '<a href="'. $list_page .'?page='. ($min_page-1) . $page_params .'" class="btn_page prev">이전</a>' . PHP_EOL;
		} else {
			echo '<a href="javascript:;" class="btn_page prev">이전</a>' . PHP_EOL;
		}

		for ($i=$min_page; $i<=$max_page; $i++) {
			if ($i == $page) {
				echo '<a href="javascript:;" class="direct curr">'. $i .'</a>' . PHP_EOL;
			} else {
				echo '<a href="'. $list_page .'?page='. $i . $page_params .'" class="direct">'. $i .'</a>' . PHP_EOL;
			}
		}

		if ($max_page < $total_page) {
			echo '<a href="'. $list_page .'?page='. ($max_page+1) . $page_params .'" class="btn_page next">다음</a>' . PHP_EOL;
		} else {
			echo '<a href="javascript:;" class="btn_page next">다음</a>' . PHP_EOL;
		}

		if ($page == $total_page) {
			echo '<a href="javascript:;" class="btn_page last">마지막</a>' . PHP_EOL;
		} else {
			echo '<a href="'. $list_page .'?page='. $total_page . $page_params .'" class="btn_page last">마지막</a>' . PHP_EOL;
		}
	}

	//----------------------------------------------------------------------
	// Description
	//		관리자 스크립팅 페이징 처리
	// Params
	//		total_page  : 총 페이지 수
	//		block_size  : 페이징 블럭 수
	//		page        : 현재 페이지 번호
	//		func		: 함수명
	//		argu		: 전달인수
	// Return
	//
	//----------------------------------------------------------------------
	function frontPaging($total_page, $block_size, $page, $page_params, $list_page = "") {
		if ($page%$block_size == 0) {
			$min_page = (int)($page / $block_size - 1) * $block_size + 1;
		} else {
			$min_page = (int)($page / $block_size) * $block_size + 1;
		}

		if (($min_page + $block_size - 1) > $total_page) {
			$max_page = $total_page;
		} else {
			$max_page = $min_page + $block_size - 1;
		}

		echo '<ul>'. PHP_EOL;

		if ($page == 1) {
			echo '<li class="first"><a href="javascript:;" title="처음 페이지">처음</a></li>' . PHP_EOL;
		} else {
			echo '<li class="first"><a href="'. $list_page .'?page=1'. $page_params .'" title="처음 페이지">처음</a></li>' . PHP_EOL;
		}

		if ($min_page > $block_size) {
			echo '<li class="prev"><a href="'. $list_page .'?page='. ($min_page-1) . $page_params .'" title="이전 페이지">이전</a></li>' . PHP_EOL;
		} else {
			echo '<li class="prev"><a href="javascript:;" title="이전 페이지">이전</a></li>' . PHP_EOL;
		}

		for ($i=$min_page; $i<=$max_page; $i++) {
			if ($i == $page) {
				echo '<li class="on"><a href="javascript:;" title="'. $i .' 페이지">'. $i .'</a></li>' . PHP_EOL;
			} else {
				echo '<li><a href="'. $list_page .'?page='. $i . $page_params .'" title="'. $i .' 페이지">'. $i .'</a></li>' . PHP_EOL;
			}
		}

		if ($max_page < $total_page) {
			echo '<li class="next"><a href="'. $list_page .'?page='. ($max_page+1) . $page_params .'" title="다음 페이지">다음</a></li>' . PHP_EOL;
		} else {
			echo '<li class="next"><a href="javascript:;" title="다음 페이지">다음</a></li>' . PHP_EOL;
		}

		if ($page == $total_page) {
			echo '<li class="last"><a href="javascript:;" title="끝 페이지">끝</a>' . PHP_EOL;
		} else {
			echo '<li class="last"><a href="'. $list_page .'?page='. $total_page . $page_params .'" title="끝 페이지">끝</a></li>' . PHP_EOL;
		}

		echo '</ul>'. PHP_EOL;
	}


	//----------------------------------------------------------------------
	// Description
	//		관리자 스크립트호출 페이징 처리
	// Params
	//		total_page  : 총 페이지 수
	//		block_size  : 페이징 블럭 수
	//		page        : 현재 페이지 번호
	//		func		: 스크립트 함수명
	// Return
	//
	//----------------------------------------------------------------------
	function adminScriptPaging($total_page, $block_size, $page, $func) {
		if ($page%$block_size == 0) {
			$min_page = (int)($page / $block_size - 1) * $block_size + 1;
		} else {
			$min_page = (int)($page / $block_size) * $block_size + 1;
		}

		if (($min_page + $block_size - 1) > $total_page) {
			$max_page = $total_page;
		} else {
			$max_page = $min_page + $block_size - 1;
		}


		if ($page == 1) {
			echo '<a href="javascript:;" class="btn_page first">처음으로</a>' . PHP_EOL;
		} else {
			$click_func = str_replace("{page}", 1, $func);
			echo '<a href="javascript:;" class="btn_page first" onclick="'. $click_func .'">처음으로</a>' . PHP_EOL;
		}

		if ($min_page > $block_size) {
			$click_func = str_replace("{page}", $min_page-1, $func);
			echo '<a href="javascript:;" class="btn_page prev" onclick="'. $click_func .'">이전</a>' . PHP_EOL;
		} else {
			echo '<a href="javascript:;" class="btn_page prev">이전</a>' . PHP_EOL;
		}

		for ($i=$min_page; $i<=$max_page; $i++) {
			if ($i == $page) {
				echo '<a href="javascript:;" class="direct curr">'. $i .'</a>' . PHP_EOL;
			} else {
				$click_func = str_replace("{page}", $i, $func);
				echo '<a href="javascript:;" class="direct" onclick="'. $click_func .'">'. $i .'</a>' . PHP_EOL;
			}
		}

		if ($max_page < $total_page) {
			$click_func = str_replace("{page}", $max_page+1, $func);
			echo '<a href="javascript:;" class="btn_page next" onclick="'. $click_func .'">다음</a>' . PHP_EOL;
		} else {
			echo '<a href="javascript:;" class="btn_page next">다음</a>' . PHP_EOL;
		}

		if ($page == $total_page) {
			echo '<a href="javascript:;" class="btn_page last">마지막</a>' . PHP_EOL;
		} else {
			$click_func = str_replace("{page}", $total_page, $func);
			echo '<a href="javascript:;" class="btn_page last" onclick="'. $click_func .'">마지막</a>' . PHP_EOL;
		}
	}


	//----------------------------------------------------------------------
	// Description
	//		사용자 스크립트호출 페이징 처리
	// Params
	//		total_page  : 총 페이지 수
	//		block_size  : 페이징 블럭 수
	//		page        : 현재 페이지 번호
	//		func		: 스크립트 함수명
	// Return
	//
	//----------------------------------------------------------------------
	function frontScriptPaging($total_page, $block_size, $page, $func) {
		if ($page%$block_size == 0) {
			$min_page = (int)($page / $block_size - 1) * $block_size + 1;
		} else {
			$min_page = (int)($page / $block_size) * $block_size + 1;
		}

		if (($min_page + $block_size - 1) > $total_page) {
			$max_page = $total_page;
		} else {
			$max_page = $min_page + $block_size - 1;
		}

		echo '<div class="num">'. PHP_EOL;

		/*if ($page == 1) {
			echo '<a href="javascript:;">맨앞</a>' . PHP_EOL;
		} else {
			$click_func = str_replace("{page}", 1, $func);
			echo '<a href="javascript:;" onclick="'. $click_func .'">맨앞</a>' . PHP_EOL;
		}*/

		if ($min_page > $block_size) {
			$click_func = str_replace("{page}", $min_page-1, $func);
			echo '<a href="javascript:;" onclick="'. $click_func .'"><img src="/img/ic_page_before.png" alt="이전"></a>' . PHP_EOL;
		} else {
			echo '<a href="javascript:;"><img src="/img/ic_page_before.png" alt="이전"></a>' . PHP_EOL;
		}

		for ($i=$min_page; $i<=$max_page; $i++) {
			if ($i == $page) {
				echo '<a href="javascript:;" class="on" >'. $i .'</a>' . PHP_EOL;
			} else {
				$click_func = str_replace("{page}", $i, $func);
				echo '<a href="javascript:;" onclick="'. $click_func .'"">'. $i .'</a>' . PHP_EOL;
			}
		}

		if ($max_page < $total_page) {
			$click_func = str_replace("{page}", $max_page+1, $func);
			echo '<a href="javascript:;" onclick="'. $click_func .'"><img src="/img/ic_page_next.png" alt="다음"></a>' . PHP_EOL;
		} else {
			echo '<a href="javascript:;"><img src="/img/ic_page_next.png" alt="다음"></a>' . PHP_EOL;
		}

		/*if ($page == $total_page) {
			echo '<a href="javascript:;">맨뒤</a>' . PHP_EOL;
		} else {
			$click_func = str_replace("{page}", $total_page, $func);
			echo '<a href="javascript:;" onclick="'. $click_func .'">맨뒤</a>' . PHP_EOL;
		}*/

		echo '</div>'. PHP_EOL;
	}