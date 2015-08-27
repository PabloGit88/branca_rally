<?php

namespace Odiseo\Bundle\BrancaRallyBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Odiseo\Bundle\BrancaRallyBundle\Entity\UserRaceParticipation;
use Odiseo\Bundle\BrancaRallyBundle\Game\Team;

class UserRaceParticipationType extends AbstractType
{

	private $team ;
	private $activeRace;
	
	function __construct( $teamSelected , $activeRace ) {
		
		$this->team = $teamSelected;
		$this->activeRace = $activeRace;
	
	}
	
	public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$teamArray =array($this->team => Team::getTeamName($this->team));
    	
    	$builder
        ->add('race', 'entity', array(
        		'class' => 'OdiseoBrancaRallyBundle:Race',
    			'choices' => array( $this->activeRace ),
        		'required' => true,
        		'label'    => 'Carrera',
        ))
        ->add('team', 'choice', array('choices' => $teamArray,
  				/*'empty_data'  => null,
        		'empty_value' => '     ',*/
        		'required' => true,
        		'label'    => 'Equipo'
        ))
        ->add('soundFile', 'file', array(
        		'required' => true,
        		'label'    => 'Archivo de audio'
        ))
        ;
    }
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'Odiseo\Bundle\BrancaRallyBundle\Entity\UserRaceParticipation',
      			'cascade_validation' => true,
		));
	}

    public function getName()
    {
        return 'odiseo_brancarally_userRaceParticipation';
    }
}
