<?php

namespace app\controllers;

use app\models\Participante;
use app\models\ParticipanteSearch;
use app\models\TipoParticipante;
use app\models\Equipo;
use app\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * ParticipanteController implements the CRUD actions for Participante model.
 */
class ParticipanteController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],

                'access' => [
                    'class' => \yii\filters\AccessControl::class,
                    'rules' => [
                        [
                            'actions' => ['index'],
                            'allow' => true,
                            'roles' => ['sysadmin','admin', 'gestor'],
                        ],
                        [
                            'actions' => ['update','view','add-equipo', 'abandonar-equipo','delete','create'],
                            'allow' => true,
                            'roles' => ['sysadmin','admin', 'gestor', 'usuario'],
                        ],
                    ],
                ],
            ]
        );
    }
    

    /**
     * Lists all Participante models.
     *
     * @return string
     */
    public function actionIndex()
    {

        $searchModel = new ParticipanteSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Participante model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $participante = $this->findModel($id);

        $query = $model->getEquipos()->with('torneos');
        $equiposDataProvider = new \yii\data\ActiveDataProvider(['query' => $query]);

        $tieneEquipo = $query->count() > 0;

        return $this->render('view', [
            'model' => $model,
            'participante' => $participante,//modelo
            'equiposDataProvider' => $equiposDataProvider,
            'tieneEquipo' => $tieneEquipo,
        ]);
    }


    public function actionAddEquipo($id)
    {
        $participante = $this->findModel($id);
        $equipoModel = new Equipo();

        // Obtén los ID de los de los equipos a los que pertenece
        $equiposDelParticipante = ArrayHelper::map($participante->equipos, 'id', 'id');

        // Filtra los equipos en los que no está y obtine nombre y licencia
        $equiposDisponibles = Equipo::find()
            ->where(['NOT IN', 'id', $equiposDelParticipante])
            ->all();

            $listaEquipos = ArrayHelper::map($equiposDisponibles, 'id', function ($equipo) {
                return $equipo->nombre . ' (' . $equipo->licencia . ')';
            });

        if (\Yii::$app->request->isPost) {
            $equipoId = \Yii::$app->request->post('Equipo')['id'];
            if ($equipoId && !in_array($equipoId, $equiposDelParticipante)) {
                // Lógica para añadir el participante al equipo
                \Yii::$app->db->createCommand()->insert('equipo_participante', [
                    'equipo_id' => $equipoId,
                    'participante_id' => $id,
                ])->execute();

                return $this->redirect(['view', 'id' => $id]);
            }
        }

        return $this->render('add-equipo', [
            'participante' => $participante,
            'equipoModel' => $equipoModel,
            'listaEquipos' => $listaEquipos,
        ]);
    }

    public function actionAbandonarEquipo($equipoId, $participanteId)
    {
        // Aquí va la lógica para eliminar la relación entre el equipo y el participante
        \Yii::$app->db->createCommand()->delete('equipo_participante', [
            'equipo_id' => $equipoId,
            'participante_id' => $participanteId,
        ])->execute();

        return $this->redirect(['view', 'id' => $participanteId]);
    }


    /**
     * Creates a new Participante model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Participante();
        $usuarioModel = new User();
        $userType = \Yii::$app->request->post('userType', null);

        $idUser = \Yii::$app->request->get('id', null);

        // Obtener todos los tipos de participantes
        $tiposParticipantes = TipoParticipante::find()->all();
        $listaTiposParticipantes = ArrayHelper::map($tiposParticipantes, 'id', 'nombre');

        // Convertir a un array para el desplegable
        $listaTiposParticipantes = ArrayHelper::map($tiposParticipantes, 'id', 'nombre');    

          // Obtener usuarios que no están vinculados a un participante
        $usuarios = User::find()->leftJoin('participante', 'usuario.id = participante.usuario_id')
            ->where(['participante.usuario_id' => null])
            ->all();
        $listaUsuarios = ArrayHelper::map($usuarios, 'id', 'nombre'); // Ajusta 'nombre' según tu modelo User

        if ($this->request->isPost) {
             // Cargar datos en el modelo Participante
            $model->load($this->request->post());
            
            // Verificar si se seleccionó un usuario existente
            if (!empty($this->request->post('Participante')['usuario_id'])) {
                // Participante vinculado a un usuario existente
                if ($model->save()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            } else {
                $usuarioModel->load($this->request->post());
                // Creación de un nuevo usuario y participante
                if ($usuarioModel->save()) {
                    $model->usuario_id = $usuarioModel->id;
                    if ($model->save()) {
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'listaTiposParticipantes' => $listaTiposParticipantes,
            'listaUsuarios' => $listaUsuarios,
            'idUser' => $idUser,
            'usuarioModel' => $usuarioModel,
            'userType' => $userType,
        ]);
    }

    /**
     * Updates an existing Participante model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $usuarioModel = User::findOne($model->usuario_id);

        // Obtener todos los tipos de participantes
        $tiposParticipantes = TipoParticipante::find()->all();
        $listaTiposParticipantes = ArrayHelper::map($tiposParticipantes, 'id', 'nombre');

        // Convertir a un array para el desplegable
        $listaTiposParticipantes = ArrayHelper::map($tiposParticipantes, 'id', 'nombre');    

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save() && $usuarioModel->load($this->request->post()) && $usuarioModel->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'listaTiposParticipantes' => $listaTiposParticipantes,
            'usuarioModel' => $usuarioModel,
        ]);
    }

    /**
     * Deletes an existing Participante model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $participante = Participante::findOne($id);
            if (!$participante) {
                throw new NotFoundHttpException("Participante no encontrado.");
            }
    
            // Comprobar equipos y estado de torneos
            $puedeBorrar = true;
            $fechaActual = new \DateTime();
            $fechaActualString = $fechaActual->format('Y-m-d H:i:s');
            foreach ($participante->equipos as $equipo) {
                foreach ($equipo->torneos as $torneo) {
                    if ($torneo->fecha_fin === null || $torneo->fecha_fin > $fechaActualString) {
                        // El torneo no ha terminado o la fecha fin es null
                        $puedeBorrar = false;
                        break 2; // Sale de ambos bucles
                    }
                }
            }
            if ($puedeBorrar) {
                // Eliminar el participante de los equipos que estan en algun torneo que ya ha finalizado
                \Yii::$app->db->createCommand()->delete('equipo_participante', ['participante_id' => $id])->execute();
                //Eliminar doc del participante
                \Yii::$app->db->createCommand()->delete('participante_documento', ['participante_id' => $id])->execute();
                // Borrar el participante
                $participante->delete();
                $transaction->commit();
                \Yii::$app->session->setFlash('success', 'Participante borrado con éxito.');
                return $this->redirect(['user/view-profile', 'id' => $participante->usuario->id]);
            } else {
                // No se puede borrar el participante
                \Yii::$app->session->setFlash('error', 'El participante pertenece a un equipo que está en un torneo activo .');
                return $this->redirect(['view', 'id' => $id]);
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * Finds the Participante model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Participante the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Participante::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
