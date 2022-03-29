<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Note */

$this->title = Yii::t('frontend', 'Create Note');
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'Notes'), 'url' => ['active']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="note-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
