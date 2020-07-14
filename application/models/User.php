<?php

class User extends ActiveRecord\Model
{
	var $password = FALSE;
	
	function before_save()
	{
		if($this->password)
			$this->hashed_password = $this->hash_password($this->password);
	}

	function set_password($plaintext)
	{

		$this->hashed_password = $this->hash_password($plaintext);
	}
	private function hash_password($password)
	{
		$salt = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
		$hash = hash('sha256', $salt . $password);

		return $salt . $hash;
	}

	private function validate_password($password)
	{
		$salt = substr($this->hashed_password, 0, 64);
		$hash = substr($this->hashed_password, 64, 64);

		$password_hash = hash('sha256', $salt . $password);

		return $password_hash == $hash;
	}

	public static function validate_login($username, $password)
	{
         $date = date("Y-m-d H:i:s");
		 $user = User::find_by_username($username);

		if($user && $user->validate_password($password))
		{
				User::login($user->id);
				if($user->role=='admin')
				{
					$action = "Ingreso de Usuario Administador ".$user->username;
	                $dataDB = array(
                           "action" => $action,
                           "created_by" => $user->username.' '.$user->firstname.' '.$user->lastname
                        );   
					
	                //print_r($dataDB);
	                Traza::create($dataDB);
	                ///////////////////////////////        
	                //die();

				}
				return $user;
		} else {
			return FALSE;
		}
	}

	public static function login($user_id)
	{
		$CI =& get_instance();
		$CI->session->set_userdata('user_id', $user_id);
	}

	public static function logout()
	{
		$CI =& get_instance();
		$CI->session->sess_destroy();

	}
}
