<?php

namespace app\controllers;

use Yii;
use app\models\User;
use yii\web\Controller;
use app\models\LoginForm;

class SiteController extends Controller
{
	public function actions()
	{
		return array(
			'error' => array(
				'class' => 'yii\web\ErrorAction',
			),
		);
	}

	public function behaviors() {
		return array(
			'access' => array(
				'class' => \yii\web\AccessControl::className(),
				'only' => array('login'),
				'rules' => array(
					array(
						'allow' => true,
						'roles' => array('?'),
					),
					array(
						'allow' => false,
						'denyCallback' => array($this, 'goHome'),
					),
				),
			),
		);
	}

	public function actionIndex()
	{
		return $this->render('index');
	}

	public function actionLogin()
	{
		$serviceName = Yii::$app->getRequest()->get('service');
		if (isset($serviceName)) {
			/** @var $eauth \nodge\eauth\ServiceBase */
			$eauth = Yii::$app->getComponent('eauth')->getIdentity($serviceName);
			$eauth->setRedirectUrl(Yii::$app->getUser()->getReturnUrl());
			$eauth->setCancelUrl(Yii::$app->getUrlManager()->createAbsoluteUrl('site/login'));

			try {
				if ($eauth->authenticate()) {
//					var_dump($eauth->getIsAuthenticated(), $eauth->getAttributes()); exit;

					$identity = User::findByEAuth($eauth);
					Yii::$app->getUser()->login($identity);

					// special redirect with closing popup window
					$eauth->redirect();
				}
				else {
					// close popup window and redirect to cancelUrl
					$eauth->cancel();
				}
			}
			catch (\nodge\eauth\ErrorException $e) {
				// save error to show it later
				Yii::$app->getSession()->setFlash('error', 'EAuthException: '.$e->getMessage());

				// close popup window and redirect to cancelUrl
//				$eauth->cancel();
				$eauth->redirect($eauth->getCancelUrl());
			}
		}

		$model = new LoginForm();
		if ($model->load($_POST) && $model->login()) {
			return $this->goBack();
		}
		else {
			return $this->render('login', array(
				'model' => $model,
			));
		}
	}

	public function actionLogout()
	{
		Yii::$app->user->logout();
		return $this->goHome();
	}
}
