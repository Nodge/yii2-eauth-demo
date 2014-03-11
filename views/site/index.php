<?php

use \yii\helpers\Html;
use \yii\helpers\Url;

/**
 * @var yii\base\View $this
 */
$this->title = \Yii::$app->name;
?>

<div class="jumbotron">
	<h1>EAuth for Yii2</h1>

	<p class="lead">EAuth extension allows to authenticate users by the <strong>OpenID</strong><br/>and <strong>OAuth</strong> providers.</p>
	<p class="lead">See <?php echo Html::a('login', array('site/login')); ?> page for demo.</p>
	<p class="lead">For more details please visit our <a href="https://github.com/Nodge/yii2-eauth">GitHub</a> page.</p>

	<a class="btn btn-lg btn-primary" href="https://github.com/Nodge/yii2-eauth/blob/master/README.md">Get started with EAuth</a>
	&nbsp; &nbsp;
	<?php if (Yii::$app->getUser()->isGuest) : ?>
	  <a class="btn btn-lg btn-success" href="<?php echo Url::to(array('site/login')); ?>">Login (demo)</a>
	<?php else : ?>
	  <a class="btn btn-lg btn-success" href="<?php echo Url::to(array('site/logout')); ?>">Logout</a>
	<?php endif; ?>
</div>