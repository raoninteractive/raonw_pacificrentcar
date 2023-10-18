<?php
	function encryption($str) {
		if (chkBlank($str)) return "";

		$key = hash('sha256', CONST_SECRET_KEY);
		$iv  = substr(hash('sha256', CONST_SECRET_IV), 0, 32)    ;

		return str_replace("=", "", base64_encode(
					 openssl_encrypt($str, "AES-256-CBC", $key, 0, $iv))
		);
	}

	function decryption($str) {
		$key = hash('sha256', CONST_SECRET_KEY);
		$iv = substr(hash('sha256', CONST_SECRET_IV), 0, 32);

		return openssl_decrypt(
					base64_decode($str), "AES-256-CBC", $key, 0, $iv
				);
	}

	function encryptionByPw($str) {
		if (chkBlank($str)) return "";

		$key = hash('sha256', CONST_SECRET_KEY);
		$iv  = substr(hash('sha256', CONST_SECRET_IV), 0, 32);

		return openssl_decrypt(
				base64_decode($str), "AES-256-CBC", $key, 0, $iv
		);
	}