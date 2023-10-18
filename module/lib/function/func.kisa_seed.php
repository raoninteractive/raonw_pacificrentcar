<?
function seed_encrypt($str, $bszUser_key='', $bszIV='', $hex_flag=true) {
    if (is_null($str) || empty($str) || $str == '') return "";

    if ($bszUser_key == '') $bszUser_key = CONST_KISA_KEY;
    if ($bszIV == '') $bszIV = CONST_KISA_IV;

    //1. 평문을 Hex 값으로 변경한다
    $str = strToHex($str);
    $str = substr( $str , 1, strlen($str)); // Hex 값으로 변환시 각 값 사이에 콤마를 찍게되는데, 맨 앞의 콤마를 삭제합니다.

    //2. 변경된 Hex 값으로 암호화 Encryption 한다.
    $return = encryptToHex($str, $bszUser_key, $bszIV, $hex_flag);

    //3. 암호화된 Hex 값을 다시 문자열로 변경한다.
    $return = str_replace(",","", $return);  // 암호화 후, 결과값은 Hex 로 나오는데, 그 값들 사이의  콤마를 없애줍니다.
    $return = hexToStr( $return);

    //4. 문자열로 변경된 암호화된 Hex 값을 Base64로 인코딩 한다.
    $return = base64_encode($return);

    return $return;
}

function seed_decrypt($str, $bszUser_key='', $bszIV='', $hex_flag=true) {
    if (is_null($str) || empty($str) || $str == '') return "";

    if ($bszUser_key == '') $bszUser_key = CONST_KISA_KEY;
    if ($bszIV == '') $bszIV = CONST_KISA_IV;

    //1. 암호문을 Base64로 디코딩 한다.
    $return = base64_decode($str);

    //2. Base64로 디코딩 된 값을 Hex 값으로 변경한다.
    $return = strToHex($return);
    $return = substr( $return , 1, strlen($return));  // Hex 변환시 값 사이에 콤마를 찍는데, 맨 앞의 콤마를  삭제합니다.

    //3. Hex 값으로 변경된 값을 Decryption 한다.
    $return = HexToDecrypt($return, $bszUser_key, $bszIV, $hex_flag);

    //4. 디코드된 값을 스트링으로 변경한다.
    $return = str_replace(",","", $return); // 디코드된 Hex 값 사이의 콤마를 제거합니다.
    $return = hexToStr( $return);

    return $return;
}

function HexToDecrypt($str, $bszUser_key, $bszIV, $hex_flag) {
    $planBytes = explode(",", $str);
    $keyBytes  = explode(",", $bszUser_key);
    $IVBytes   = explode(",", $bszIV);

    if ($hex_flag) {
        //Hex 값으로 변경
        for($i = 0; $i < 16; $i++)    {
            $keyBytes[$i] = hexdec($keyBytes[$i]);
            $IVBytes[$i]  = hexdec($IVBytes[$i]);
        }
    }

    for ($i = 0; $i < count($planBytes); $i++) {
        $planBytes[$i] = hexdec($planBytes[$i]);
    }

    if (count($planBytes) == 0) {
        return $str;
    }

    $pdwRoundKey = array_pad(array(),32,0);

    $planBytresMessage = null;
    $bszPlainText      = null;

    $bszPlainText = KISA_SEED_CBC::SEED_CBC_Decrypt($keyBytes, $IVBytes, $planBytes, 0, count($planBytes));
    for($i=0;$i< sizeof($bszPlainText);$i++) {
        $planBytresMessage .= sprintf("%02X", $bszPlainText[$i]).",";
    }

    return substr($planBytresMessage,0,strlen($planBytresMessage)-1);
}


function encryptToHex($str, $bszUser_key, $bszIV, $hex_flag) {
    $planBytes = explode(",", $str);
    $keyBytes  = explode(",", $bszUser_key);
    $IVBytes   = explode(",", $bszIV);

    if ($hex_flag) {
        //Hex 값으로 변경
        for($i = 0; $i < 16; $i++)    {
            $keyBytes[$i] = hexdec($keyBytes[$i]);
            $IVBytes[$i]  = hexdec($IVBytes[$i]);
        }
    }

    for ($i = 0; $i < count($planBytes); $i++) {
        $planBytes[$i] = hexdec($planBytes[$i]);
    }

    if (count($planBytes) == 0) {
        return $str;
    }

    $pdwRoundKey = array_pad(array(),32,0);

    $ret           = null;
    $bszChiperText = null;

    $bszChiperText = KISA_SEED_CBC::SEED_CBC_Encrypt($keyBytes, $IVBytes, $planBytes, 0, count($planBytes));
    $r = count($bszChiperText);
    for($i=0;$i< $r;$i++) {
        $ret .= sprintf("%02X", $bszChiperText[$i]).",";
    }

    return substr($ret,0,strlen($ret)-1);
}

//아래 두 함수는 문자열을 Hex 값으로 변경하거나 Hex 값을 문자열로 변경해주는 함수입니다
function strToHex($string){
    $hex='';
    for ($i=0; $i < strlen($string); $i++){
        $hex .= "," . dechex(ord($string[$i]));
    }

    return $hex;
}
function hexToStr($hex){
    $string='';
    for ($i=0; $i < strlen($hex)-1; $i+=2){
        $string .= chr(hexdec($hex[$i].$hex[$i+1]));
    }

    return $string;
}


/*
    //지금부터는 앞서 설명한 A서버에서 B서버로 전달하는 과정과 일치하는 순서입니다

    // 최초의 입력문자열
    $str = "홍길동";
    echo "1. 최초 입력값: ";
    echo $str;
    echo "<br><br>";

    // 1. A 서버 : 홍길동을 Hex 값으로 변경한다
    $str = strToHex($str);
    $str = substr( $str , 1, strlen($str)); // Hex 값으로 변환시 각 값 사이에 콤마를 찍게되는데, 맨 앞의 콤마를 삭제합니다.

    echo "2. 홍길동 헥사값 : ";
    echo $str;
    echo "<br><br>";

    // 2. A 서버 : 변경된 Hex 값으로 암호화 Encryption 한다.
    $return = encrypt($g_bszIV, $g_bszUser_key, $str);
    echo "3. 헥사값을 암호화:";
    echo $return;
    echo "<br><br>";

    // 3. A 서버 : 암호화된 Hex 값을 다시 문자열로 변경한다.
    $return = str_replace(",","", $return);  // 암호화 후, 결과값은 Hex 로 나오는데, 그 값들 사이의  콤마를 없애줍니다.
    $return = hexToStr( $return);
    echo "4. 암호화를 다시 스트링 : ";
    echo $return;
    echo "<br><br>";

    // 4. A 서버 : 문자열로 변경된 암호화된 Hex 값을 Base64로 인코딩 한다.
    echo "5. 암호화를 base64_encode : ";
    $return = base64_encode($return);
    echo $return;
    echo "<br><br>";

    // 5. B 서버 : 전달받은 암호문을 Base64로 디코딩 한다.
    echo "6. 암호화를 base64_decode : ";
    $return = base64_decode($return);
    echo $return;
    echo "<br><br>";

    // 6. B 서버 : Base64로 디코딩 된 값을 Hex 값으로 변경한다.
    $return = strToHex($return);
    $return = substr( $return , 1, strlen($return));  // Hex 변환시 값 사이에 콤마를 찍는데, 맨 앞의 콤마를  삭제합니다.
    echo "7. 디코드를 헥사 : ";
    echo $return;
    echo "<br><br>";

    // 7. B 서버 : Hex 값으로 변경된 값을 Decryption 한다.
    $return = decrypt($g_bszIV, $g_bszUser_key, $return);
    echo "8. 암호화를 복호화:";
    echo $return;
    echo "<br><br>";

    // 8. B 서버 : 디코드된 값을 스트링으로 변경한다.
    $return = str_replace(",","", $return); // 디코드된 Hex 값 사이의 콤마를 제거합니다.
    $return = hexToStr( $return);
    echo "9. 복호화를 스트링 : ";
    echo $return;
    echo "<br><br>";
*/