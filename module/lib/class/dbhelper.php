<?php
    class DB_HELPER
    {
        private $conn;

        public $fetchType;

        public $errno = '';
        public $error = '';

        public $row_cnt = 0;

        // Connects to the database
        function __construct()
        {
			global $CONST_DB_IP, $CONST_DB_ID, $CONST_DB_PW, $CONST_DB_NAME, $CONST_DB_PORT;

            $this->conn = new mysqli( $CONST_DB_IP, $CONST_DB_ID, $CONST_DB_PW, $CONST_DB_NAME, $CONST_DB_PORT );
            $this->conn->set_charset("utf8");
            $this->conn->autocommit(true);

            $this->fetchType = 'array';
        }

        function __destruct()
        {
            //$this->conn->close();
        }

        function close()
        {
            $this->conn->close();
        }

        //DB 트랜젝션 시작
        function beginTrans()
        {
            $this->conn->autocommit(false);
        }

        //DB 트랜젝션 커밋
        function commitTrans()
        {
            $this->conn->commit();
        }

        //DB 트랜젝션 롤백
        function rollbackTrans()
        {
            $this->conn->rollback();
        }

        //목록 데이터 불러오기
        function getList($sql, $page=1, $list_size=10, &$total_cnt=0, &$total_page=1)
        {
            //총조회수
            if (!$result = $this->conn->query($sql)) {
                $this->errno = $this->conn->errno;
                $this->error = $this->conn->error;

                return false;
            }

            $total_cnt  = $result->num_rows;
            $total_page = totalPage($total_cnt, $list_size);

            //조회 개수 입력
            $this->row_cnt = $total_cnt;

            $rows = array();
            $limit_start = ($page-1) * $list_size;
            $limit_end   = $list_size;

            //목록 불러오기
            if (substr($sql, -1) == ";") $sql = substr($sql,0,strlen($sql)-1);
            if ($result = $this->conn->query($sql ." LIMIT $limit_start, $limit_end")) {
                if ($this->fetchType == "array") {
                    while($row = $result->fetch_array()) {
                        array_push($rows, $row);
                    }
                } else {
                    while($row = $result->fetch_assoc()) {
                        array_push($rows, $row);
                    }
                }

                return $rows;
            } else {
                $this->errno = $this->conn->errno;
                $this->error = $this->conn->error;

                return false;
            }
        }

        //쿼리 데이터 불러오기
        function getQuery($sql)
        {
            if ($result = $this->conn->query($sql)) {
                //조회 개수 입력
                $this->row_cnt = $result->num_rows;

                $rows = array();
                if ($this->fetchType == "array") {
                    while($row = $result->fetch_array()) {
                        array_push($rows, $row);
                    }
                } else {
                    while($row = $result->fetch_assoc()) {
                        array_push($rows, $row);
                    }
                }

                return $rows;
            } else {
                $this->errno = $this->conn->errno;
                $this->error = $this->conn->error;

                return false;
            }
        }

        //데이터 한개만 불러오기
        function getQueryValue($sql)
        {
            if (substr($sql, -1) == ";") $sql = substr($sql,0,strlen($sql)-1);
            if ($result = $this->conn->query($sql . " LIMIT 1")) {
                //조회 개수 입력
                $this->row_cnt = $result->num_rows;

                if ($this->fetchType == "array") {
                    $row = $result->fetch_array();
                } else {
                    $row = $result->fetch_assoc();
                }

                if (is_null($row) || $this->row_cnt == 0) {
                    return false;
                } else {
                    return $row;
                }
            } else {
                $this->errno = $this->conn->errno;
                $this->error = $this->conn->error;

                return false;
            }
        }

        //마지막 고유번호 불러오기
        function getLastInsertId()
        {
            return $this->conn->insert_id;
        }

        //insert, update, delete 처리개수
        function getAffectedRows()
        {
            return $this->conn->affected_rows;
        }

        //쿼리 Insert
        function insert($sql)
        {
            if ($result = $this->conn->query($sql )) {
                /*
                $ids = $this->conn->insert_id;

                if (is_null($ids) || $ids == 0) {
                    return false;
                } else {
                    return $ids;
                }
                */

                return true;
            } else {
                $this->errno = $this->conn->errno;
                $this->error = $this->conn->error;

                return false;
            }
        }

        //쿼리 Update
        function update($sql)
        {
            if ($result = $this->conn->query($sql)) {
                /*
                $row = $this->conn->affected_rows;

                if (is_null($row) || $row == 0) {
                    return false;
                } else {
                    return $row;
                }
                */

                return true;
            } else {
                $this->errno = $this->conn->errno;
                $this->error = $this->conn->error;

                return false;
            }
        }

        //쿼리 Delete
        function delete($sql)
        {
            if ($result = $this->conn->query($sql )) {
                /*
                $row = $this->conn->affected_rows;

                if (is_null($row) || $row == 0) {
                    return false;
                } else {
                    return $row;
                }
                */

                return true;
            } else {
                $this->errno = $this->conn->errno;
                $this->error = $this->conn->error;

                return false;
            }
        }
    }