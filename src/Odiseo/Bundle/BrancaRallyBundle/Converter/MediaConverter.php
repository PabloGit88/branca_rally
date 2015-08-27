<?php

namespace Odiseo\Bundle\BrancaRallyBundle\Converter;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\Container;
use FFMpeg\Format\Audio\Mp3;
use Odiseo\Bundle\BrancaRallyBundle\Format\Audio\Ogg;
use FFMpeg\Format\Audio\Aac;
	
class MediaConverter extends ContainerAware
{
	public function __construct(Container $container)
	{
		$this->setContainer($container);
	}
	
	public function convertAudio($sourceFile)
	{
		$filePathInfo = pathinfo($sourceFile);
		$ffmpeg = $this->container->get('dubture_ffmpeg.ffmpeg');
		 
		// Open audio
		$audio = $ffmpeg->open($sourceFile);

		//$aacFormat = new Aac();
		$mp3Format = new Mp3();
		$oggFormat = new Ogg();
		
		if($filePathInfo['extension'] != 'aac')
		{
			//No funciona: Unknown encoder 'libfdk_aac' (hay que volver a compilar ffmpeg)
			//$audio->save($aacFormat, $filePathInfo['dirname'].'/'.$filePathInfo['filename'].'.aac');
		}
		
		if($filePathInfo['extension'] != 'mp3')
		{
			$audio->save($mp3Format, $filePathInfo['dirname'].'/'.$filePathInfo['filename'].'.mp3');
		}
		
		if($filePathInfo['extension'] != 'ogg')
		{
			$audio->save($oggFormat, $filePathInfo['dirname'].'/'.$filePathInfo['filename'].'.ogg');
		}
	}
	
	public function getDuration($sourceFile)
	{
		$total_seconds = 0;
		
		try {
			$time = exec($this->container->getParameter('ffmpeg_binary')." -i ".escapeshellarg($sourceFile) . " 2>&1 | grep 'Duration' | cut -d ' ' -f 4 | sed s/,//");
			list($hms, $milli) = explode('.', $time);
			list($hours, $minutes, $seconds) = explode(':', $hms);
			$total_seconds = ($hours * 3600) + ($minutes * 60) + $seconds;
		}catch (\Exception $e){}
		
		return $total_seconds;
	}
}