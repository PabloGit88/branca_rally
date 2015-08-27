<?php

namespace Odiseo\Bundle\BrancaRallyBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class TokenService
{
	
	private $key, $iv;

	function __construct( $pass ) {
		$this->key = hash( 'sha256', $pass, true );
		$this->iv = mcrypt_create_iv(32, MCRYPT_RAND);
	}

	function encodeToken( $input ) {
		$encrypted = mcrypt_encrypt( MCRYPT_RIJNDAEL_256, $this->key, $input, MCRYPT_MODE_ECB, $this->iv );
		$base64Encode = base64_encode($encrypted) ;
		return urlencode( $base64Encode );
	}

	function decodeToken( $input ) {
		$urlDecoded = urldecode( $input );
		$base64Decoded  =  base64_decode($urlDecoded);
		return  trim(mcrypt_decrypt( MCRYPT_RIJNDAEL_256, $this->key, $base64Decoded , MCRYPT_MODE_ECB, $this->iv ));
	}
	
	
}