<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Workspace */

$this->title = Yii::t('frontend', 'Create Workspace');
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'Workspaces'), 'url' => ['active']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="workspace-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
