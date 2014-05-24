<?php

namespace app\models;

use Yii;
use \yii\base\ErrorException;
use yii\web\IdentityInterface;

class User extends \yii\base\Object implements \yii\web\IdentityInterface
{
	public $id;
	public $username;
	public $password;
	public $authKey;

	/**
	 * @var array EAuth attributes
	 */
	public $profile;

	private static $users = array(
		'100' => array(
			'id' => '100',
			'username' => 'admin',
			'password' => 'admin',
			'authKey' => 'test100key',
		),
		'101' => array(
			'id' => '101',
			'username' => 'demo',
			'password' => 'demo',
			'authKey' => 'test101key',
		),
	);

	public static function findIdentity($id)
	{
		if (Yii::$app->getSession()->has('user-'.$id)) {
			return new self(Yii::$app->getSession()->get('user-'.$id));
		}
		else {
			return isset(self::$users[$id]) ? new self(self::$users[$id]) : null;
		}
	}

	public static function findByUsername($username)
	{
		foreach (self::$users as $user) {
			if (strcasecmp($user['username'], $username) === 0) {
				return new self($user);
			}
		}
		return null;
	}

	/**
	 * @param \nodge\eauth\ServiceBase $service
	 * @return User
	 * @throws ErrorException
	 */
	public static function findByEAuth($service) {
		if (!$service->getIsAuthenticated()) {
			throw new ErrorException('EAuth user should be authenticated before creating identity.');
		}

		$id = $service->getServiceName().'-'.$service->getId();
		$attributes = array(
			'id' => $id,
			'username' => $service->getAttribute('name'),
			'authKey' => md5($id),
			'profile' => $service->getAttributes(),
		);
		$attributes['profile']['service'] = $service->getServiceName();
		Yii::$app->getSession()->set('user-'.$id, $attributes);
		return new self($attributes);
	}

	public function getId() {
		return $this->id;
	}

	public function getAuthKey() {
		return $this->authKey;
	}

	public function validateAuthKey($authKey) {
		return $this->authKey === $authKey;
	}

	public function validatePassword($password) {
		return $this->password === $password;
	}

	/**
	 * Finds an identity by the given secrete token.
	 *
	 * @param string $token the secrete token
	 * @param null $type type of $token
	 * @return IdentityInterface the identity object that matches the given token.
	 * Null should be returned if such an identity cannot be found
	 * or the identity is not in an active state (disabled, deleted, etc.)
	 */
	public static function findIdentityByAccessToken($token, $type = null) {
		// TODO: Implement findIdentityByAccessToken() method.
		return null;
	}
}
