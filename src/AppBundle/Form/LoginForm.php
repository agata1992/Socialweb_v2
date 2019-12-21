<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
	
class LoginForm extends AbstractType{
	
	public function buildForm(FormBuilderInterface $builder,array $opt){
	
		$builder
			->add('email',EmailType::class,array(
				'label'=>'Email'
			))
			->add('password',PasswordType::class,array(
				'label'=>'Hasło'
			))
			->add('submit',SubmitType::class,array(
				'label'=>'Zaloguj',
				'attr'=>array('class'=>'btn-primary')
			));
	}
}	
?>