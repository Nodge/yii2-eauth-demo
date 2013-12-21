<?php

$config = array(
	'id' => 'bootstrap',
	'name' => 'Yii2 EAuth extension demo',
	'basePath' => dirname(__DIR__),
	'extensions' => require(__DIR__ . '/../vendor/yiisoft/extensions.php'),
	'components' => array(
		'cache' => array(
			'class' => 'yii\caching\FileCache',
		),
		'user' => array(
			'identityClass' => 'app\models\User',
		),
		'errorHandler' => array(
			'errorAction' => 'site/error',
		),
		'i18n' => array(
			'translations' => array(
				'eauth' => array(
					'class' => 'yii\i18n\PhpMessageSource',
					'basePath' => '@eauth/messages',
				),
			),
		),
		'eauth' => array(
			'class' => 'nodge\eauth\EAuth',
			'popup' => true, // Use the popup window instead of redirecting.
			'cache' => false, // Cache component name or false to disable cache. Defaults to 'cache' on production environments.
			'cacheExpire' => 0, // Cache lifetime. Defaults to 0 - means unlimited.
//			'httpClient' => array(
				// uncomment this to use streams in safe_mode
				//'useStreamsFallback' => true,
//			),
//			'tokenStorage' => array(
//				'class' => '@app\eauth\DatabaseTokenStorage',
//			),
			'services' => array(
				'google' => array(
					'class' => 'nodge\eauth\services\GoogleOpenIDService',
					//'realm' => '*.example.org', // your domain, can be with wildcard to authenticate on subdomains.
				),
				'google_oauth' => array(
					// register your app here: https://code.google.com/apis/console/
					'class' => 'nodge\eauth\services\GoogleOAuth2Service',
					'clientId' => '...',
					'clientSecret' => '...',
					'title' => 'Google (OAuth)',
				),
				'facebook' => array(
					// register your app here: https://developers.facebook.com/apps/
					'class' => 'nodge\eauth\services\FacebookOAuth2Service',
					'clientId' => '...',
					'clientSecret' => '...',
				),
				'twitter' => array(
					// register your app here: https://dev.twitter.com/apps/new
					'class' => 'nodge\eauth\services\TwitterOAuth1Service',
					'key' => '...',
					'secret' => '...',
				),
				'yahoo' => array(
					'class' => 'nodge\eauth\services\YahooOpenIDService',
				),
				'linkedin' => array(
					// register your app here: https://www.linkedin.com/secure/developer
					'class' => 'nodge\eauth\services\LinkedinOAuth1Service',
					'key' => '...',
					'secret' => '...',
					'title' => 'LinkedIn (OAuth1)',
				),
				'linkedin_oauth2' => array(
					// register your app here: https://www.linkedin.com/secure/developer
					'class' => 'nodge\eauth\services\LinkedinOAuth2Service',
					'clientId' => '...',
					'clientSecret' => '...',
					'title' => 'LinkedIn (OAuth2)',
				),
				'github' => array(
					// register your app here: https://github.com/settings/applications
					'class' => 'nodge\eauth\services\GitHubOAuth2Service',
					'clientId' => '...',
					'clientSecret' => '...',
				),
				'live' => array(
					// register your app here: https://account.live.com/developers/applications/index
					'class' => 'nodge\eauth\services\LiveOAuth2Service',
					'clientId' => '...',
					'clientSecret' => '...',
				),
				'steam' => array(
					'class' => 'nodge\eauth\services\SteamOpenIDService',
				),
				'yandex' => array(
					'class' => 'nodge\eauth\services\YandexOpenIDService',
					//'realm' => '*.example.org', // your domain, can be with wildcard to authenticate on subdomains.
				),
				'yandex_oauth' => array(
					// register your app here: https://oauth.yandex.ru/client/my
					'class' => 'nodge\eauth\services\YandexOAuth2Service',
					'clientId' => '...',
					'clientSecret' => '...',
					'title' => 'Yandex (OAuth)',
				),
				'vkontakte' => array(
					// register your app here: https://vk.com/editapp?act=create&site=1
					'class' => 'nodge\eauth\services\VKontakteOAuth2Service',
					'clientId' => '...',
					'clientSecret' => '...',
				),
				'mailru' => array(
					// register your app here: http://api.mail.ru/sites/my/add
					'class' => 'nodge\eauth\services\MailruOAuth2Service',
					'clientId' => '...',
					'clientSecret' => '...',
				),
				'odnoklassniki' => array(
					// register your app here: http://dev.odnoklassniki.ru/wiki/pages/viewpage.action?pageId=13992188
					// ... or here: http://www.odnoklassniki.ru/dk?st.cmd=appsInfoMyDevList&st._aid=Apps_Info_MyDev
					'class' => 'nodge\eauth\services\OdnoklassnikiOAuth2Service',
					'clientId' => '...',
					'clientSecret' => '...',
					'clientPublic' => '...',
					'title' => 'Odnoklas.',
				),
			),
		),

		// (optionally) you can configure pretty urls
		'urlManager' => array(
			'enablePrettyUrl' => true,
			'showScriptName' => false,
			'rules' => array(
				'' => 'site/index',
				'login' => 'site/login',
				'logout' => 'site/logout',
			),
		),

		// (optionally) you can configure logging
		'log' => array(
			'traceLevel' => YII_DEBUG ? 3 : 0,
			'targets' => array(
				array(
					'class' => 'yii\log\FileTarget',
					'levels' => array('error', 'warning'),
				),
				array(
					'class' => 'yii\log\FileTarget',
					'logFile' => '@app/runtime/logs/eauth.log',
					'categories' => array('nodge\eauth\*'),
					'logVars' => array(),
				),
			),
		),
	),
	'params' => require(__DIR__ . '/params.php'),
);

if (file_exists(__DIR__.'/web-local.php')) {
	$localConfig = require 'web-local.php';
	$config = \yii\helpers\ArrayHelper::merge($config, $localConfig);
}

$eauthServices = array_keys($config['components']['eauth']['services']);
array_unshift($config['components']['urlManager']['rules'], array(
	'route' => 'site/login',
	'pattern' => 'login/<service:('.implode('|', $eauthServices).')>',
));

return $config;