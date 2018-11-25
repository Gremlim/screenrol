<?php

namespace tools;

//TODO: SHOULD USE DIFFERENT FUNCTIONS FOR PHP > 7.1, since these are not
//supported there!.

class crypt{

	const					key='rnvoxjnj';
	const					initialization_vector='12345678';
	const					mcrypt_mode='cbc';

	public static function network_crypt($_str, $_key=self::key, $_iv=self::initialization_vector){

		return base64_encode(self::crypt(base64_encode($_str), $_key, $_iv));
	}

	public static function network_decrypt($_str, $_key=self::key, $_iv=self::initialization_vector){

		return base64_decode(self::decrypt(base64_decode($_str), $_key, $_iv));
	}

	public static function crypt($_str, $_key=self::key, $_iv=self::initialization_vector){

		$cypher=mcrypt_module_open(MCRYPT_BLOWFISH,'','cbc','');
		mcrypt_generic_init($cypher, $_key, $_iv);
		$encrypted=mcrypt_generic($cypher, $_str);
		mcrypt_generic_deinit($cypher);
		return $encrypted;
	}

	public static function decrypt($_str, $_key=self::key, $_iv=self::initialization_vector){

		$cypher=mcrypt_module_open(MCRYPT_BLOWFISH,'','cbc','');
		mcrypt_generic_init($cypher, $_key, $_iv);
		$decrypted=mdecrypt_generic($cypher,$_str);
		mcrypt_generic_deinit($cypher);
		return $decrypted;
	}
}
