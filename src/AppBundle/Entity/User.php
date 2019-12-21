<?php
namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 *
 */
 
class User{
	
	/**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
	
	private $id;
	
	/**
	 * @Assert\NotBlank(message = "Pole nie może być puste")
	 *
	 * @ORM\Column(type="string",length=255)
	 */
	 
	private $name;
	
	/**
	 * @Assert\NotBlank(message = "Pole nie może być puste")
	 *
	 * @ORM\Column(type="string",length=255)
	 */
	
	private $surname;
	
	/**
	 * @Assert\NotBlank(message = "Pole nie może być puste")
	 * @Assert\Email(message = "Wpisz poprawny email")
	 *
	 * @ORM\Column(type="string",length=255)
	 */
	
	private $email;
	
	/**
	 * @Assert\NotBlank(message = "Pole nie może być puste")
	 * @Assert\Length(min=8,minMessage="Hasło musi mieć przynajmniej 8 znaków")
	 *
	 * @ORM\Column(type="string",length=255)
	 */
	
	private $password;
	
	/**
	 * @Assert\NotBlank(message = "Pole nie może być puste") 
	 */
	
	private $password2;
	
	/**
	 * @ORM\Column(type="string",length=255)
	 */
	 
	private $salt;
	
	/**
	 * @ORM\Column(type="string",length=255)
	 */
	 
	private $city;
	
	
	/**
	 * @Assert\NotBlank(message = "Pole nie może być puste")
	 *
	 * @ORM\Column(type="date")
	 */
	 
	private $birthdate;
	
	/**
     * @ORM\Column(type="integer")
	 */
	 
	private $activate;
	
	/**
	 * @Assert\NotBlank(message = "Pole nie może być puste")
	 *
	 * @ORM\Column(type="string",length=1)
	 */
	 
	private $sex;
	
	/**
	 * @ORM\Column(type="string",length=255)
	 */
	
	private $profileImg;
	
	public function getId(){
		
		return $this->id;
	}
	
	public function getName(){
		
		return $this->name;
	}
	public function getSurname(){
		
		return $this->surname;
	}
	
	public function getEmail(){
		
		return $this->email;
	}
	
	public function getPassword(){
		
		return $this->password;
	}
	
	public function getPassword2(){
		
		return $this->password2;
	}
	
	public function getSalt(){
		
		return $this->salt;
	}
	
	public function getCity(){
		
		return $this->city;
	}
	
	public function getBirthdate(){
		
		return $this->birthdate;
	}
	
	public function getActivate(){
		
		return $this->activate;
	}
	
	public function getSex(){
		
		return $this->sex;
	}
	
	public function getProfileImg(){
		
		return $this->profileImg;
	}
	
	public function setName($name){
		
		return $this->name = $name;
	}
	
	public function setSurname($surname){
		
		return $this->surname = $surname;
	}
	
	public function setEmail($email){
		
		return $this->email = $email;
	}
	
	public function setPassword($password){
		
		return $this->password = $password;
	}
	
	public function setPassword2($password2){
		
		return $this->password2 = $password2;
	}
	
	public function setSalt($salt){
		
		return $this->salt = $salt;
	}
	
	public function setCity($city){
		
		return $this->city = $city;
	}
	
	public function setBirthdate($birthdate){
		
		return $this->birthdate = $birthdate;
	}
	
	public function setActivate($activate){
		
		return $this->activate = $activate;
	}
	
	public function setSex($sex){
		
		return $this->sex = $sex;
	}
	
	public function setProfileImg($image){
		
		return $this->profileImg = $image;
	}
}
?>