<?php
namespace AppBundle\Form\Photos;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
	
class AlbumForm extends AbstractType{
	
	public function buildForm(FormBuilderInterface $builder,array $opt){
	
		$builder
			->add('title',TextType::class,array(
				'label'=>'Nazwa'
			))
			->add('access',ChoiceType::class,array(
				'label'=>'Typ',
				'empty_data'=>NULL,
				'placeholder'=>'---',
				'choices'=>array(
					'Publiczny'=>'0',
					'Prywatny'=>'1',
					'Dla znajomych' =>'2'
				)
			))
			->add('submit',SubmitType::class,array(
				'label'=>'Dodaj',
				'attr'=>array('class'=>'btn-primary')
			));
	}
}	
?>