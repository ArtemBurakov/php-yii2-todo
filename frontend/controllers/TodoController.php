<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Todo;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TodoController implements the CRUD actions for Todo model.
 */
class TodoController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function renderIndex($dataProvider)
    {
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    // Get active tasks.
    public function actionActive()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Todo::find()->where(['status' => 10]),
        ]);

        return $this->renderIndex($dataProvider);
    }

    // Get completed tasks.
    public function actionCompleted()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Todo::find()->where(['status' => 20]),
        ]);

        return $this->renderIndex($dataProvider);
    }

    // Get archive tasks.
    public function actionArchive()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Todo::find()->where(['status' => 0]),
        ]);

        return $this->renderIndex($dataProvider);
    }

    /**
     * Displays a single Todo model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Todo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Todo();
        $model->status = Todo::STATUS_ACTIVE;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Todo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Changes task status to done.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDone($id)
    {
        $model = $this->findModel($id);
        $model->status = Todo::STATUS_DONE;

        if ($model->save()) {
            return $this->redirect(['active']);
        }
    }

    /**
     * Deletes an existing Todo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->status = Todo::STATUS_DELETED;

        if ($model->save()) {
            return $this->redirect(['active']);
        }
    }

    /**
     * Finds the Todo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Todo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Todo::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('frontend', 'The requested page does not exist.'));
    }
}
