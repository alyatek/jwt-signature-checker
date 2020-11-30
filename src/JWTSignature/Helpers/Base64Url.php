<?php

namespace JWTSignature\Helpers;

class Base64Url
{

	public static function encode($data): string
	{
		return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
	}

	public static function decode($data): string
	{
		return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
	}
}
