<?php
	function connect_mysql(){
		$mysqli = @new mysqli(DB_IP, DB_ID, DB_PW, DB_NAME, DB_PORT);

		if($mysqli->connect_errno){
			return false;
		}else{
			$mysqli->set_charset("utf8");
			return $mysqli;
		}
	}

	function close_mysql($db) {
		mysqli_close($db);
	}

	function getQueryInsertId($sql){
		$db = connect_mysql();

		if (substr($sql, -1) != ";") {
			$sql = $sql .";";
		}

		$rows = array();
		if($result = $db->multi_query("
			SET SESSION TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;
			$sql
			SET SESSION TRANSACTION ISOLATION LEVEL REPEATABLE READ;
		")){
			$i=0;
			do {
				if ($i == 1) {
					if ($db->affected_rows > 0) {
						return $db->insert_id;
					} else {
						return null;
					}
				}

				if (!$db->more_results()) exit;

				$i++;
			} while ($db->next_result());

			return null;
		}

		close_mysql($db);

		return null;
	}

	function getQueryResult($sql){
		$db = connect_mysql();

		if (substr($sql, -1) != ";") {
			$sql = $sql .";";
		}

		$rows = array();
		if($result = $db->multi_query("
			SET SESSION TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;
			$sql
			SET SESSION TRANSACTION ISOLATION LEVEL REPEATABLE READ;
		")){
			$i=0;
			do {
				if ($i == 1) {
					return $db->affected_rows;
				}

				if (!$db->more_results()) exit;

				$i++;
			} while ($db->next_result());

			return 0;
		}

		close_mysql($db);

		return 0;
	}

	function getQueryValue($sql, &$row_cnt=0) {
		global $fetchType;
		$fetchType = iif(chkBlank($fetchType), "array", "assoc");

		$db = connect_mysql();

		if (substr($sql, -1) != ";") {
			$sql = $sql .";";
		}

		//목록
		$rows = array();
		if($result = $db->multi_query("
			SET SESSION TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;
			$sql;
			SET SESSION TRANSACTION ISOLATION LEVEL REPEATABLE READ;
		")){
			do {
				if ($result = $db->store_result()) {
					$row = $result->fetch_row();

					$row_cnt = $result->num_rows;
					$result->free();

					return $row[0];
				}
			} while ($db->next_result());

			return false;
		}

		close_mysql($db);

		return false;
	}

	function getQueryList($sql) {
		global $fetchType;
		$fetchType = iif(chkBlank($fetchType), "array", "assoc");

		$db = connect_mysql();

		if (substr($sql, -1) != ";") {
			$sql = $sql .";";
		}

		//목록
		$rows = array();
		if($result = $db->multi_query("
			SET SESSION TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;
			$sql;
			SET SESSION TRANSACTION ISOLATION LEVEL REPEATABLE READ;
		")){
			do {
				if ($result = $db->store_result()) {
					if ($fetchType=="array") {
						while($row = $result->fetch_array()) {
							array_push($rows, $row);
						}
					} else {
						while($row = $result->fetch_assoc()) {
							array_push($rows, $row);
						}
					}

					$result->free();
				}
			} while ($db->next_result());

			return $rows;
		}

		close_mysql($db);

		return false;
	}

	function getDataList($sql, $page=1, $list_size=10, &$total_cnt=0, &$total_page=1) {
		global $fetchType;
		$fetchType = iif(chkBlank($fetchType), "array", "assoc");

		$db = connect_mysql();

		if (substr($sql, -1) != ";") {
			$total_sql = $sql .";";
		} else {
			$total_sql = $sql;
		}

		if (substr($sql, -1) == ";") {
			$sql = substr($sql,0,strlen($sql)-1);
		}


		//총조회수
		if ($result = $db->multi_query("
			SET SESSION TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;
			$total_sql;
			SET SESSION TRANSACTION ISOLATION LEVEL REPEATABLE READ;
		")) {
			do {
				if ($result = $db->store_result()) {
					$total_cnt  = $result->num_rows;
					$total_page = totalPage($total_cnt, $list_size);
					$result->free();
				}
			} while ($db->next_result());
		}

		//목록
		$rows = array();
		$limit_start = ($page-1) * $list_size;
		$limit_end   = $list_size;


		if($db->multi_query("
			SET SESSION TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;
			$sql LIMIT $limit_start, $limit_end;
			SET SESSION TRANSACTION ISOLATION LEVEL REPEATABLE READ;
		")){
			do {
				if ($result = $db->store_result()) {
					if ($fetchType=="array") {
						while($row = $result->fetch_array()) {
							array_push($rows, $row);
						}
					} else {
						while($row = $result->fetch_assoc()) {
							array_push($rows, $row);
						}
					}

					$result->free();

					if ($db->more_results()) break;
				}
			} while ($db->next_result());

			return $rows;
		}

		close_mysql($db);

		return false;
	}

	function getDataView($sql, &$row_cnt=0) {
		$db = connect_mysql();

		if (substr($sql, -1) != ";") {
			$sql = $sql .";";
		}

		if($result = $db->multi_query("
			SET SESSION TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;
			$sql;
			SET SESSION TRANSACTION ISOLATION LEVEL REPEATABLE READ;
		")) {
			do {
				if ($result = $db->store_result()) {
					$row = $result->fetch_array();
					$row_cnt = $result->num_rows;
					$result->free();
				}
			} while ($db->next_result());

			if (is_null($row)) return false;

			return $row;
		}

		close_mysql($db);

		return false;
	}
