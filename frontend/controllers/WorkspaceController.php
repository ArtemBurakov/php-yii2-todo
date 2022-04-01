<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Workspace;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * WorkspaceController implements the CRUD actions for Workspace model.
 */
class WorkspaceController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['active', 'completed', 'archive'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
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

    // Get active workspaces.
    public function actionActive()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Workspace::find()->where([
                'status' => 10,
                'user_id' => Yii::$app->user->identity->id,
                ]),
        ]);

        return $this->renderIndex($dataProvider);
    }

    // Get archive workspaces.
    public function actionArchive()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Workspace::find()->where([
                'status' => 0,
                'user_id' => Yii::$app->user->identity->id,
                ]),
        ]);

        return $this->renderIndex($dataProvider);
    }

    /**
     * Displays a single Workspace model.
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
     * Creates a new Workspace model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Workspace();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Workspace model.
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
     * Deletes an existing Workspace model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->status = Workspace::STATUS_DELETED;

        if ($model->save()) {
            return $this->redirect(['active']);
        }
    }

    /**
     * Finds the Workspace model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Workspace the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Workspace::find()->where(['id' => $id, 'user_id' => \Yii::$app->user->identity->id])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('frontend', 'The requested page does not exist.'));
    }
}
