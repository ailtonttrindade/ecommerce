<?php 
namespace code\Model; 
use \code\DB\sql;
use \code\Model;

class User extends Model{
	
	const SESSION = "User";

	public static function Login($login, $password)
	{
		$sql = new Sql();
		
		$results = $sql->select("SELECT * FROM tb_users WHERE deslogin = :LOGIN", array(":LOGIN"=>$login
		));
		if (count($results)===0)
		{
			throw new \Exception("Usuario inexistente ou senha Inválida.");
		}
		$data = $results[0];
		if(password_verify($password, $data["despassword"]) === true)
		{
			$user = new User();

			$user->setData($data);

			$_SESSION[User::SESSION] = $user->getValues();

			return $user;

		}else {
			throw new \Exception("Usuario inexistente ou senha Inválida.");
		}
	}
	public static function verifyLogin($inadmin = true)
	{

		if (
			 !isset($_SESSION[User::SESSION])
			 ||
			 !$_SESSION[User::SESSION]
			 ||
			 !(int)$_SESSION[User::SESSION]["iduser"] > 0
			 ||
			 (bool)$_SESSION[User::SESSION]["inadmin"] !== $inadmin
			){
				header("Location: /admin/login");
				exit;
		}
	}
	public static function logout()
	{
		$_SESSION[User::SESSION] = NULL;
	}
}
?>