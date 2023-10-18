<?php
	//파일 업로드
	function fileUpload($name, $path, $fileMaxSize, $fileTypes="FILE", $orgNameSave="Y", $permitFileExt="", $msgTypes="JSON", $msgUrl="RELOAD", $msgTarget="P"){
		//파일용량 설정
		$fileMaxSize = $fileMaxSize * 1024 * 1024;

		//파일확장자 설정
		if (chkBlank($permitFileExt)) { $permitFileExt=CONST_FILE_EXTS; }

		//파일업로드 경로 설정
		$upload_path = chkMapPath($path);

		if((!$upload_path || !$name) && ($fileTypes != "FILE" || $fileTypes != "IMG")) throw new Exception("잘못된 요청 정보 입니다.");
		if(!is_dir($upload_path)) throw new Exception("해당 디렉토리가 존재하지 않습니다.");

		try {
			$files = array();
			if(!empty($_FILES)){
				if(is_string($_FILES[$name]["name"])) {
					$filename = array($_FILES[$name]["name"]);
					$tmp_name = array($_FILES[$name]["tmp_name"]);
					$error    = array($_FILES[$name]["error"]);
				} else if (is_array($_FILES[$name]["name"])) {
					$filename = $_FILES[$name]["name"];
					$tmp_name = $_FILES[$name]["tmp_name"];
					$error    = $_FILES[$name]["error"];
				}

				for($i=0, $cnt=count($filename); $i<$cnt; $i++){
					if($filename[$i]){
						if($error[$i]){
							switch($error[$i]){
								case 1: $msg = "PHP 파일업로드 용량제한보다 큰 파일입니다."; break;
								case 2: $msg = "HTML폼 파일업로드 용량제한보다 큰 파일입니다."; break;
								case 3: $msg = "손상된 파일입니다."; break;
								case 4: $msg = "업로드할 파일이 없습니다."; break;
								case 6: $msg = "임시폴더가 존재하지 않습니다."; break;
								case 7: $msg = "임시폴더에 파일을 저장할 수 없습니다."; break;
								case 8: $msg = "업로드할 수 없는 확장자입니다."; break;
							}
							throw new Exception($msg);
						}

						//파일용량 체크
						if(filesize($tmp_name[$i]) > $fileMaxSize) throw new Exception("제한된 용량초과입니다.". getFileSize($fileMaxSize)." 이하의 파일만 업로드 가능합니다.");

						//파일확장자 체크
						$pathinfo = pathinfo($filename[$i]);
						$ext      = strtolower($pathinfo["extension"]);

						if ($fileTypes == "FILE") {
							$tmp_ext_chk = false;
							foreach (explode(",", $permitFileExt) as $item) {
								if (trim($item) == $ext) {
									$tmp_ext_chk = true;
									break;
								}
							}

							if (!$tmp_ext_chk) throw new Exception("확장자 ".$ext."는 등록할 수 없는 파일형식입니다.");
						} else if ($fileTypes == "IMG") {
							if (strpos(mime_content_type($tmp_name[$i]), "image") === false) throw new Exception("이미지 파일만 등록 가능합니다.");
						}

						//이미지 회전, 투명도처리
						if ($ext == "jpg" || $ext == "jpeg" || $ext == "png" || $ext == "bmp" || $ext == "wbmp" || $ext == "gif") {
							if($ext == "jpg" || $ext == "jpeg"){
								$background = imagecolorallocate($tmp_name[$i] , 0, 0, 0);
								imagecolortransparent($tmp_name[$i], $background);
								imagealphablending($tmp_name[$i], false);
								imagesavealpha($tmp_name[$i], true);

								$image = imagecreatefromjpeg($tmp_name[$i]);
							}else if($ext == "png"){
								$image = imagecreatefrompng($tmp_name[$i]);
							}else if($ext == "bmp" || $ext == "wbmp"){
								$image = imagecreatefromwbmp($tmp_name[$i]);
							}else if($ext == "gif"){
								$background = imagecolorallocate($tmp_name[$i] , 0, 0, 0);
								imagecolortransparent($tmp_name[$i], $background);
								imagealphablending($tmp_name[$i], false);
								imagesavealpha($tmp_name[$i], true);

								$image = imagecreatefromgif($tmp_name[$i]);
							}

							$exif = exif_read_data($tmp_name[$i]);
							if(!empty($exif['Orientation'])) {
								switch($exif['Orientation']) {
									case 8:
										$image = imagerotate($image ,90 ,0);
										break;
									case 3:
										$image = imagerotate($image ,180 ,0);
										break;
									case 6:
										$image = imagerotate($image ,-90 ,0);
										break;
								}
								if($ext == "jpg" || $ext == "jpeg"){
									imagejpeg($image, $tmp_name[$i]);
								}else if($ext == "png"){
									imagepng($image, $tmp_name[$i]);
								}else if($ext == "bmp" || $ext == "wbmp"){
									imagewbmp($image, $tmp_name[$i]);
								}else if($ext == "gif"){
									imagegif($image, $tmp_name[$i]);
								}
							}
						}

						//원본파일명
						$upload_orgname  = $filename[$i];

						//파일사이즈
						$upload_filesize = filesize($tmp_name[$i]);


						//파일정보 설정
						if ($orgNameSave == "Y") {
							//원본파일명
							$upload_filename = getFileNameCheck($upload_path, $filename[$i]);
						} else {
							//랜덤파일명
							$upload_filename = getFileNameCheck($upload_path, getRandFileName().".".$ext);
						}

						//파일업로드 시작
						if(is_uploaded_file($tmp_name[$i])) {
							if (!fileMove($tmp_name[$i], $path, $upload_filename)) {
								throw new Exception("파일업로드 작업중 오류가 발생되었습니다.\n파일을 다시 확인해주세요.");
							}
						} else {
							throw new Exception("파일업로드 작업중 오류가 발생되었습니다.\n파일경로를 다시 확인해주세요.");
						}

						$files[$i]["file_name"]      = $upload_filename;
						$files[$i]["file_org_name"]  = $upload_orgname;
						$files[$i]["file_size"]      = $upload_filesize;
						$files[$i]["file_info"]      = $upload_filename .'|@|'. $upload_orgname .'|@|'. $upload_filesize;
					} else {
						$files[$i]["file_name"]      = "";
						$files[$i]["file_org_name"]  = "";
						$files[$i]["file_size"]      = 0;
						$files[$i]["file_info"]      = "";
					}
				}
			}

			return $files;
		} catch (Exception $e) {
			//print_r($e);

			//echo $e->getMessage();
			if ($msgTypes=="JSON") {
				fnMsgJson(400, $e->getMessage(), "");
			} else if ($msgTypes=="EDITOR") {
				//echo "{\"uploaded\": 0, \"error\": { \"message\": \"". $e->getMessage() ."\"}}";
				exit;
			} else {
				fnMsgGo(400, $e->getMessage(), $msgUrl, $msgTarget);
			}
		}
	}

	//썸네일생성
	function makeThumbnail($path, $file_name, $target_path, $new_width=120, $new_height=120, $is_over=false){
		$origin_path = PHYSICAL_PATH.$path;
		$target_path = PHYSICAL_PATH.$target_path;

		$ori_file = $origin_path."/".$file_name;

		$ext        = explode(".", $file_name);
		$ext        = strtolower($ext[1]);
		if ($is_over == false) {
			$thumbnail  = getFileNameCheck($target_path, $file_name);
			$new_img    = $target_path."/".$thumbnail;
		} else {
			$thumbnail  = $file_name;
			$new_img    = $target_path."/".$thumbnail;
		}
		$size       = getimagesize($ori_file);
		$width      = $size[0];
		$height     = $size[1];
		$tmp_width  = $width;
		$tmp_height = $height;

		if ($tmp_width > $new_width) {
			$ratio = $new_width / $tmp_width;

			$tmp_width = $new_width;
			$tmp_height = (int)($tmp_height * $ratio);
		}

		if ($tmp_height > $new_height) {
			$ratio = $new_height / $tmp_height;

			$tmp_height = $new_height;
			$tmp_width = (int)($tmp_width * $ratio);
		}

		$tmp_img = imagecreatetruecolor( $tmp_width, $tmp_height );

		if ($ext == "png") {
			$background = imagecolorallocate($tmp_img , 0, 0, 0);
			imagecolortransparent($tmp_img, $background);
			imagealphablending($tmp_img, false);
			imagesavealpha($tmp_img, true);

			$img = imagecreatefrompng($ori_file);
			imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $tmp_width, $tmp_height, $width, $height );
			if(!imagepng( $tmp_img, $new_img )){$thumbnail = "";}
		} else if($ext == "jpg" || $ext == "jpeg") {
			$img = imagecreatefromjpeg($ori_file);
			imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $tmp_width, $tmp_height, $width, $height );
			if(!imagejpeg( $tmp_img, $new_img )){$thumbnail = "";}
		} else if($ext == "gif") {
			$background = imagecolorallocate($tmp_img , 0, 0, 0);
			imagecolortransparent($tmp_img, $background);
			imagealphablending($tmp_img, false);
			imagesavealpha($tmp_img, true);

			$img = imagecreatefromgif($ori_file);
			imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $tmp_width, $tmp_height, $width, $height );
			if(!imagegif( $tmp_img, $new_img )){$thumbnail = "";}
		} else if($ext == "wbmp") {
			$img = imagecreatefromwbmp($ori_file);
			imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $tmp_width, $tmp_height, $width, $height );
			if(!imagewbmp( $tmp_img, $new_img )){$thumbnail = "";}
		} else {
			$thumbnail = "";
		}

		return $thumbnail;
	}

	//파일사이즈
	function getFileSize($size) {
		$sizes = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
		if ($size == 0) {
			return('n/a');
		} else {
			return (round($size/pow(1024, ($k = floor(log($size, 1024)))), 2) . $sizes[$k]);
		}
	}

	//폴더맵체크
	function chkMapPath($path) {
		$upload_path = PHYSICAL_PATH."/";
		$arrTarget  = explode("/",$path);

		$k=0;
		for ($i=0; $i<count($arrTarget);$i++) {
			if ($arrTarget[$i] != "") {
				if ($k > 0) $upload_path .= "/";
				$upload_path .= changeValueCharset(trim($arrTarget[$i]));

				if(@mkdir($upload_path, 0777)) {
					if(is_dir($upload_path)) {
						@chmod($upload_path, 0777);
					}
				}

				$k++;
			}
		}

		$upload_path = str_replace("//", "/", $upload_path);

		return $upload_path;
	}

	//폴더삭제
	function dirDelete($dir_path) {
		if (is_string($dir_path)) {
			$upload_path = chkMapPath($dir_path);

			if ($files = @scandir($upload_path)) {
				foreach ($files as $file) {
					if ($file != "." && $file != "..") {
						unlink($upload_path."/".$file);
					}
				}

				rmdir($upload_path);
			}
		} else if (is_array($dir_path)) {
			foreach ($dir_path as $dir) {
				$upload_path = chkMapPath($dir);
				if ($files = @scandir($upload_path)) {
					foreach($files as $file){
						if($file != "." && $file != "..") {
							unlink($upload_path."/".$file);
						}
					}
				}

				rmdir($upload_path);
			}
		}
	}

	//파일삭제
	function fileDelete($path, $file_name){
		$file_name = changeValueCharset($file_name);
		$upload_path = chkMapPath($path);

		if(empty($file_name) || empty($path)){
			return false;
		}

		if (is_string($file_name)) {
			//로컬파일 삭제
			if(is_file($upload_path."/".$file_name)){
				if(unlink($upload_path."/".$file_name)){
					return 1;
				}
			}
		} else if (is_array($file_name)) {
			$cnt = count($file_name);
			$success = 0;
			for($i=0; $i<$cnt; $i++){
				if(is_file($upload_path."/".$file_name[$i])){
					if(unlink($upload_path."/".$file_name[$i])){
						$success++;
					}
				}
			}

			if($success) {
				return $success;
			}
		}

		return false;
	}

	//파일이동
	function fileMove($ori_path, $target_path, $filename, $isOver=true) {
		$filename = changeValueCharset($filename);

		if (left($ori_path,13)=="/private/tmp/" || left($ori_path,5)=="/tmp/" || right($ori_path,4)==".tmp") {
			$moveTarget = $ori_path;
		} else {
			$moveTarget = chkMapPath($ori_path) ."/". $filename;
		}

		if (file_exists($moveTarget)) {
			$tmpPath = chkMapPath($target_path);

			if ($isOver) {
				$tmpTarget = $tmpPath ."/". $filename;

				if (file_exists($tmpTarget)) @unlink($tmpTarget);
				copy($moveTarget, $tmpTarget);
			} else {
				$filename = getFileNameCheck($tmpPath, $filename);
				$tmpTarget = $tmpPath ."/". $filename;

				@copy($moveTarget, $tmpTarget);
			}

			if (file_exists($moveTarget)) @unlink($moveTarget);

			return $filename;
		} else {
			return false;
		}
	}

	//파일복사
	function fileCopy($ori_path, $target_path, $filename, $isOver=true) {
		$filename = changeValueCharset($filename);

		if (left($ori_path,5)=="/tmp/" || right($ori_path,4)==".tmp") {
			$copyTarget = $ori_path;
		} else {
			$copyTarget = chkMapPath($ori_path) ."/". $filename;
		}

		if (file_exists($copyTarget)) {
			$tmpPath = chkMapPath($target_path);

			if ($isOver) {
				$tmpTarget = $tmpPath ."/". $filename;

				if (file_exists($tmpTarget)) @unlink($tmpTarget);
				@copy($copyTarget, $tmpTarget);
			} else {
				$filename = getFileNameCheck($tmpPath, $filename);
				$tmpTarget = $tmpPath ."/". $filename;

				@copy($copyTarget, $tmpTarget);
			}

			return $filename;
		} else {
			return false;
		}
	}


	//고유파일명
	function getFileNameCheck($upload_path, $file_name) {
		$file_name = str_replace(" ", "_", $file_name);
		$file_name = str_replace(",", "", $file_name);
		$fileName  = substr($file_name ,0 ,strrpos($file_name, "."));
		$fileExt   = substr($file_name ,strrpos($file_name, ".")+1);


		$tmpFileName = $fileName.".".$fileExt;
		$i=1;

		while(file_exists($upload_path."/".changeValueCharset($tmpFileName))) {
			$tmpFileName = $fileName."(".$i.").".$fileExt;
			$i++;
		}

		return $tmpFileName;
	}

	//랜덤파일명
	function getRandFileName() {
		return date("YmdHis")."_".strtoupper(left(str_replace("-", "", getGUID()),5));
	}



	//----------------------------------------------------------------------
	//	Description
	//		파일 공통다운로드
	//	Params
	//		up_file   = 서버파일명
	//		save_file = 파일저장명
	//		path      = 파일저장경로
	//	Return
	//		파일삭제 리컨값없음
	//----------------------------------------------------------------------
	function fileDown($up_file, $save_file, $path) {
		$file_path  = chkMapPath($path);
		$file_path .= "/". $up_file;


		if (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident/') !== false) {
			//$save_file = changeValueCharset($save_file);
			$save_file = iconv('utf-8', 'euc-kr', $save_file);

			// IE인 경우 헤더 변경
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
		}

		$save_file = str_replace(' ', '_', $save_file);
		$save_file = str_replace(',', '', $save_file);

		$file_size = filesize($file_path);

		header("Pragma: public");
		header("Expires: 0");
		header("Content-Type: application/octet-stream");
		header("Content-Disposition: attachment; filename=\"$save_file\"");
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: $file_size");

		$fh = fopen($file_path, "rb");
		fpassthru($fh);
		fclolse($fh);

		//ob_clean();
		//flush();
		//readfile($file_path);
	}

	//파일경로 이미지 체크
	function filePathCheck($file_path) {
		if (chkBlank($file_path)) {
			return "/upload/noimg.jpg";
		}

		if (is_file(PHYSICAL_PATH.$file_path)) {
			return $file_path;
		} else {
			return "/upload/noimg.jpg";
		}

	}

	//파일 확장자 불러오기
	function getFileExt($file_name) {
		return substr(strrchr($file_name, '.'), 1);
	}


	//업로드 첨부파일명
	function getUpfileName($file) {
		if (chkBlank($file)) return "";

		$tmp_arr = explode('|@|', $file);
		if (strpos($file, '|@|') === false) return $file;
		if (count($tmp_arr) != 3) return "";

		return $tmp_arr[0];
	}

	//업로드 첨부파일 원본명
	function getUpfileOriName($file) {
		if (chkBlank($file)) return "";

		$tmp_arr = explode('|@|', $file);
		if (strpos($file, '|@|') === false) return $file;
		if (count($tmp_arr) != 3) return "";

		return $tmp_arr[1];
	}

	//업로드 첨부파일 용량
	function getUpfileSize($file) {
		if (chkBlank($file)) return "";

		$tmp_arr = explode('|@|', $file);
		if (strpos($file, '|@|') === false) return $file;
		if (count($tmp_arr) != 3) return "";

		return $tmp_arr[2];
	}