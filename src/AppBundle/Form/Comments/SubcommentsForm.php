<?php
	
namespace AppBundle\Form\Comments;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
			->add('text',TextareaType::class,array(
				'attr' => array('placeholder' => 'Odpowiedz','rows'=>2)
			))
			->add('submit', SubmitType::class,array(
				'label'=>'Dodaj',
				'attr'=>array('class'=>'btn-primary')
			));
	}
}	
?>