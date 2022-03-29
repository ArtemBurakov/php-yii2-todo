<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Note;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * NoteController implements the CRUD actions for Note model.
 */
class NoteController extends Controller
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

    // Get active notes.
    public function actionActive()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Note::find()->where([
                'status' => 10,
                'user_id' => Yii::$app->user->identity->id,
                ]),
        ]);

        return $this->renderIndex($dataProvider);
    }

    // Get completed notes.
    public function actionCompleted()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Note::find()->where([
                'status' => 20,
                'user_id' => Yii::$app->user->identity->id,
                ]),
        ]);

        return $this->renderIndex($dataProvider);
    }

    // Get archive notes.
    public function actionArchive()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Note::find()->where([
                'status' => 0,
                'user_id' => Yii::$app->user->identity->id,
                ]),
        ]);

        return $this->renderIndex($dataProvider);
    }

    /**
     * Displays a single Note model.
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
     * Creates a new Note model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Note();
        $model->status = Note::STATUS_ACTIVE;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Note model.
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
     * Changes note status to done.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDone($id)
    {
        $model = $this->findModel($id);
        $model->status = Note::STATUS_DONE;

        if ($model->save()) {
            return $this->redirect(['active']);
        }
    }

    /**
     * Deletes an existing Note model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->status = Note::STATUS_DELETED;

        if ($model->save()) {
            return $this->redirect(['active']);
        }
    }

    /**
     * Finds the Note model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Note the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Note::find()->where(['id' => $id, 'user_id' => \Yii::$app->user->identity->id])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('frontend', 'The requested page does not exist.'));
    }
}
