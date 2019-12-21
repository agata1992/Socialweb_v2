<?php
	
namespace AppBundle\Form\Posts;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
	
class PostsForm extends AbstractType{
	
	public function buildForm(FormBuilderInterface $builder,array $opt){
	
		$builder
			->add('text',TextType::class,array(
				'attr' => array('placeholder' => 'Dodaj post')
			))
			->add('submit', SubmitType::class,array(
				'label'=>'Dodaj',
				'attr'=>array('class'=>'btn-primary')
			));
	}
}	
?>