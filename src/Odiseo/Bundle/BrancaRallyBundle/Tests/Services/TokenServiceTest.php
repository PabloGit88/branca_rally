<?php

namespace Odiseo\Bundle\BrancaRallyBundle\Tests\Services;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TokenServiceTest extends WebTestCase
{
	private static  $tokenService;
	private static  $textToEncrypt = "email@dominio.com";
	
	public static function setUpBeforeClass()
	{
		$kernel = static::createKernel();
		$kernel->boot();
		$container = $kernel->getContainer();
		self::$tokenService  = $container->get('app.services.token');
	}
	
	public function testEncryptation()
    {
    	$textEncrypted = self::$tokenService->encodeToken(self::$textToEncrypt);
    	$this->assertNotEquals($textEncrypted ,self::$textToEncrypt);
    }
    
    public function testDesencryptation(){

    	echo ("Para encriptar: " . self::$textToEncrypt   . "\n" );
    	$encrypted = self::$tokenService->encodeToken( self::$textToEncrypt);
    	echo ("Encriptada: " . $encrypted    . "\n" );
    	$desencrypted = self::$tokenService->decodeToken($encrypted);
    	echo ("Desencriptada: " . $desencrypted  . "\n" );
		$this->assertNotEquals($encrypted, $desencrypted);

    }
}
