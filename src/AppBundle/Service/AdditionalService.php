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
	
	public function getAgeName($age){
		
		if($age == 1)
			return 'rok';
		elseif($age % 100 >=10 && $age % 100 <= 19)
			return 'lat';
		else{
			if(in_array($age % 10, [0,1,5,6,7,8,9]))
				return 'lat';
		
			if(in_array($age % 10, [2,3,4]))
				return 'lat';
		}
	}
}