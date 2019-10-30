<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
	
class RegisterForm extends AbstractType{
	
	public function buildForm(FormBuilderInterface $builder,array $opt){
	
		$builder
			->add('name',TextType::class,array(
				'label'=>'Imię'
			))
			->add('surname',TextType::class,array(
				'label'=>'Nazwisko'
			))
			->add('email',EmailType::class,array(
				'label'=>'Email'
			))
			->add('password',PasswordType::class,array(
				'label'=>'Hasło'
			))
			->add('password2',PasswordType::class,array(
				'label'=>'Powtórz hasło'
			))
			->add('city',TextType::class,array(
				'label'=>'Miasto'
			))
			->add('birthdate',BirthdayType::class,array(
				'label'=>'Data urodzenia',
				'empty_data'=>NULL,
				'placeholder' => '---',
			))
			->add('submit',SubmitType::class,array(
				'label'=>'Rejestruj',
				'attr'=>array('class'=>'btn-primary')
			));
	}
}	
?>