<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class U extends CUserIdentity
{
	private $_id;

	/**
	 * Authenticates a user.
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		$user=Users::model()->find('LOWER(username)=?',array(strtolower($this->username)));             
		//zmf::test($user);exit();
		if($user===null)
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		else if(!$this->validatePassword($user->password,$user['hash']))
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else
		{
			$this->_id=$user->id;
			$this->username=$user->username;
			$this->errorCode=self::ERROR_NONE;
		}
		return $this->errorCode==self::ERROR_NONE;
	}

	/**
	 * @return integer the ID of the user record
	 */
	public function getId()
	{
		return $this->_id;
	}
        public function validatePassword($password,$hash='')
	{
            //echo $password.'@####@'.$this->password;
            
		return md5($this->password.$hash)==$password ? true:false;
	}
}