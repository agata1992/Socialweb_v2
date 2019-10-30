<?php
	
namespace AppBundle\Service;

class AdditionalService
{
	public function _s_has_upper_letters($string)
	{
		return preg_match('/[A-Z]/',$string);
	}

	public function _s_has_lower_letters($string)
	{
		return preg_match('/[a-z]/',$string);
	}
	
	public function _s_has_numbers($string)
	{
		return preg_match('/\d/', $string);
	}

	public function _s_has_special_chars($string)
	{
		return preg_match('/[^a-zA-Z\d]/',$string);
	}	
}