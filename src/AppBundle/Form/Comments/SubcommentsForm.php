<?php
	
namespace AppBundle\Form\Comments;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
	
class SubcommentsForm extends AbstractType{
	
	private $name;
	
	public function getBlockPrefix(){
		return $this->name;
	}
	
	public function buildForm(FormBuilderInterface $builder,array $opt){
	
		$this->name = 'subcomment_'.$opt['attr']['id'];
	
		$builder
			->add('text',TextType::class,array(
				'attr' => array('placeholder' => 'Odpowiedz')
			))
			->add('submit', SubmitType::class,array(
				'label'=>'Dodaj',
				'attr'=>array('class'=>'btn-primary')
			));
	}
}	
?>