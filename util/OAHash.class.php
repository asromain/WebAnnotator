<?php


class OAHash
{

	public function __construct()
	{

	}

	static public function getHash($before, $strTarget, $after)
	{
		$schemeIdentifier = 'hash'; // scheme identifier = 'hash'
		$contextLength = 10; // context length number of characters to the left and right used in the message for the hash-diges
		$overallLength = strlen($strTarget); // overall length of the addressed string
		$hash = md5($before . '(' . $strTarget . ')' . $after); // the message digest, a 32-character hexadecimal MD5 hash created from the string and the context. The message M consists of a certain number C of characters (see 2. context length above) to the left of the string, a bracket `(', the string itself, another bracket `)' and C characters to the right of the string: `leftContext(String)rightContext'. If there are not enough characters to left or right, C is adjusted and decreased on the corresponding side (see the 'Hurra!' example below)
		$strEncTarget = urlencode($strTarget);  // the string itself, the first 20 (or less, if the string is shorter) characters of the addressed string, urlencoded
		
		$result = $schemeIdentifier . '_' .$contextLength . '_' .$overallLength . '_' .$hash . '_' .$strEncTarget;

		return $result;
	}

	static public function getOffset($text, $hash)
	{
		$hashArr = split('_', $hash);
		if ($hashArr < 5) {
			// echo "split fail on hash str";
			return "split fail on hash str";
		}
		$schemeIdentifier = $hashArr[0];// scheme identifier = 'hash'
		$contextLength = $hashArr[1];// context length number of characters to the left and right used in the message for the hash-diges
		$overallLength = $hashArr[2];// overall length of the addressed string
		$hash = $hashArr[3]; // the message digest, a 32-character hexadecimal MD5 hash created from the string and the context. The message M consists of a certain number C of characters (see 2. context length above) to the left of the string, a bracket `(', the string itself, another bracket `)' and C characters to the right of the string: `leftContext(String)rightContext'. If there are not enough characters to left or right, C is adjusted and decreased on the corresponding side (see the 'Hurra!' example below)
		$strTarget = urldecode($hashArr[4]);  // the string itself, the first 20 (or less, if the string is shorter) characters of the addressed string, urlencoded
		
		// $strTarget = str_replace(".", "\.", $strTarget);
		// indexof au lieu de pregmatch v ?

		// on cherche ou est le hash
		$pattern = '/'.$strTarget.'/';
		preg_match($pattern, $text, $matches, PREG_OFFSET_CAPTURE); // pattern subject matches

		foreach ($matches as $match) {
			$offset = $match[1];
			$before = substr($text, $offset-$contextLength, $contextLength);
			$after = substr($text, $offset+$overallLength, $contextLength);
			if ( $hash == md5($before . '(' . $strTarget . ')' . $after) ) {
				return $offset;
			}
		}

		return -1;
	}

	static public function getTarget($hash)
	{
		$hashArr = split('_', $hash);
		if ($hashArr < 5) {
			// echo "split fail on hash str";
			return false;
		}
		return urldecode($hashArr[4]);  // the string itself, the first 20 (or less, if the string is shorter) characters of the addressed string, urlencoded
	}

}