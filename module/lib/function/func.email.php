<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;


	//----------------------------------------------------------------------
	//	Function name	: sendEmail
	//	Parameter		:
	//			subject						= 메일 제목
	//			to_mail						= 받는사람 이메일 주소
	//			to_name						= 받는사람 이름
	//			from_mail					= 보내는 사람 이메일 주소
	//			from_name					= 보내는 사람 이름
	//			content						= 메일 내용
	//	Return			:
	//	Description		:
	//			메일 발송 함수
	//----------------------------------------------------------------------
	function sendEmail($subject, $to_email, $to_name, $from_email, $from_name, $content, $attach = "") {
		global $DEV_MODE, $CONST_TEST_EAMIL;

        /*
		if ($DEV_MODE) {
			$to_email = "";
			foreach($CONST_TEST_EAMIL as $val) {
				if ($to_email != "") $to_email .= ",";
				$to_email .= $val;
			}
		}

		if (chkBlank($to_name)) {
			$to_config = $to_email;
		} else {
			//$to_config = "\"". $to_name . "\" <". $to_email .">";
			$to_config = $to_email;
		}

		if (chkBlank($from_name)) {
			$from_config = $from_email."\r\n";
		} else {
			$from_config = "From: ". $from_name ." <". $from_email .">\r\n";
		}


		$from_config.= "MIME-Version: 1.0\r\n";
		$from_config.= "Content-Type: text/html; charset=utf-8\r\n";
		$from_config.= "X-Mailer: PHP\r\n";


		$subject = '=?UTF-8?B?'.base64_encode( $subject ).'?=';
		$result = mail($to_config, $subject, $content, $from_config);

        //var_dump($result);
        */


        // PHPMailer 선언
        $mail = new PHPMailer(true);

        // 디버그 모드(production 환경에서는 주석 처리한다.)
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;

        // SMTP 서버 세팅
        $mail->isSMTP();
        try {
            // 구글 smtp 설정
            $mail->Host = "smtp.gmail.com";

            // SMTP 암호화 여부
            $mail->SMTPAuth = true;

            // SMTP 포트
            $mail->Port = 465;

            // SMTP 보안 프초트콜
            $mail->SMTPSecure = "ssl";

            // gmail 유저 아이디
            $mail->Username = "pacificrentcarguam@gmail.com";

            // gmail 패스워드
            $mail->Password ="gmjywuogiayzpsop";

            // 인코딩 셋
            $mail->CharSet = 'utf-8';
            $mail->Encoding = "base64";

            // 보내는 사람
            $mail->setFrom($from_email, $from_name);

            // 받는 사람
            if ($DEV_MODE) {
                $to_email = "";
                foreach($CONST_TEST_EAMIL as $email) {
                    $mail->AddAddress($email, "");
                }
            } else {
                if (is_array($to_email)) {
                    foreach($to_email as $email) {
                        $mail->AddAddress($email, $to_name);
                    }
                } else {
                    $mail->AddAddress($to_email, $to_name);
                }
            }

            // 본문 html 타입 설정
            $mail->isHTML(true);

            // 제목
            $mail->Subject = $subject;

            // 본문 (HTML 전용)
            $mail->Body    = $content;

            $mail->Send();
            //echo "발송성공";
        } catch (phpmailerException $e) {
            echo $e->errorMessage();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
	}