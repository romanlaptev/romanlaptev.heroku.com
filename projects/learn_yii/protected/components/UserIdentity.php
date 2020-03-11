<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate() {
/*
		$users=array(
			// username => password
			'demo'=>'demo',
			'admin'=>'admin',
		);
		if(!isset($users[$this->username]))
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		elseif($users[$this->username]!==$this->password)
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else
			$this->errorCode=self::ERROR_NONE;
*/

		$users = Users::model()->find( "LOWER(login)=?", array( strtolower( $this->username) ) );
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
//echo "<pre>";
//print_r($users);
//echo "</pre>";

		if( ($users === null) or 
				( $this->password !== $users->pass ) 
		){
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		} else {
			//$this->user_id = $users->user_id;
			$this->errorCode=self::ERROR_NONE;
		}

		return !$this->errorCode;
	}//end authenticate()

/*
	public function getId()	{
		return $this->user_id;
	}//end 
*/

}//end class
