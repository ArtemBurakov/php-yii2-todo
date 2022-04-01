<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Workspace */

$this->title = Yii::t('frontend', 'Update Workspace: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'Workspaces'), 'url' => ['active']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('frontend', 'Update');
?>
<div class="workspace-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
