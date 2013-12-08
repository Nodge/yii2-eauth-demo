<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\LoginForm $model
 */
$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?php echo Html::encode($this->title); ?></h1>

<?php
	if (Yii::$app->getSession()->hasFlash('error')) {
		echo '<div class="alert alert-danger">'.Yii::$app->getSession()->getFlash('error').'</div>';
	}
?>

<p class="lead">Do you already have an account on one of these sites? Click the logo to log in with it here:</p>
<?php echo \yii2eauth\Widget::widget(array('action' => 'site/login')); ?>
<hr/>

<p>Please fill out the following fields to login:</p>

<?php $form = ActiveForm::begin(array('options' => array('class' => 'form-horizontal', 'id' => 'login-form'))); ?>
	<?php echo $form->field($model, 'username')->textInput(); ?>
	<?php echo $form->field($model, 'password')->passwordInput(); ?>
	<?php echo $form->field($model, 'rememberMe')->checkbox(); ?>
	<div class="form-actions">
		<?php echo Html::submitButton('Login', array('class' => 'btn btn-primary')); ?>
	</div>
<?php ActiveForm::end(); ?>
