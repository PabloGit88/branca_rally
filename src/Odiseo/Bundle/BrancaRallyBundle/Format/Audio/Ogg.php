<?php

namespace Odiseo\Bundle\BrancaRallyBundle\Format\Audio;

use FFMpeg\Format\Audio\DefaultAudio;

/**
 * The Ogg audio format
 */
class Ogg extends DefaultAudio
{
    public function __construct()
    {
        $this->audioCodec = 'libvorbis';
    }

    /**
     * {@inheritdoc}
     */
    public function getExtraParams()
    {
        return array('-strict', '-2');
    }

    public function getAudioKiloBitrate()
    {
    	return 0;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getAvailableAudioCodecs()
    {
        return array('libvorbis');
    }
}
