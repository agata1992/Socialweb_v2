<?php
	
namespace AppBundle\Service;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;	
use Symfony\Component\HttpFoundation\Cookie;

class CookieService
{
	protected $requestStack;
	
	public function __construct(RequestStack $requestStack)
	{
		$this->requestStack = $requestStack;
	}	
	
	public function check_exist_user_cookie()
	{
		$request = $this->requestStack->getCurrentRequest();
		$cookie = $request->cookies;
		
		if(!$cookie->get("user") == null)
		{
			$user = $cookie->get("user");
		}
		else
			$user = '';
	
		return $user;
	}	
	
	public function set_cookie($email)
	{
		$cookie = new Cookie('user',$email,time() + ( 24 * 60 * 60));
		$response = new Response;
		$response->headers->setCookie($cookie);
		$response->send();
	}
	
	public function delete_user_cookie()
	{
		$response = new Response();
		$response->headers->clearCookie('user');
		$response->send();
	}
}