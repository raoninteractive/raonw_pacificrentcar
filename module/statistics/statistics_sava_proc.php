<?
	$db = new DB_HELPER();
	$sql = "SELECT idx FROM statistics WHERE ip='".$_SERVER["REMOTE_ADDR"]."' AND TIMESTAMPDIFF(DAY,DATE_FORMAT(reg_date,'%Y-%m-%d'),CURDATE()) = 0";
	if (!$db->getQueryValue($sql) && strpos($_SERVER["HTTP_USER_AGENT"],"Googlebot")===false) {
		$sql = "
			INSERT INTO statistics (
				referrer_url, page_url, agent, ip, reg_date
			) VALUES (
				'". iif($_SERVER["HTTP_REFERER"]!='', $_SERVER["HTTP_REFERER"], '') ."', '". $_SERVER["REQUEST_URI"] ."', '". $_SERVER["HTTP_USER_AGENT"] ."', '". $_SERVER["REMOTE_ADDR"] ."', NOW()
			)
		";
		$db->insert($sql);
	}
?>
