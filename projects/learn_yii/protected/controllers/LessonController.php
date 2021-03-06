<?php

class LessonController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			//'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view', 'list'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
//echo "public function actionView($id)";
//echo "<br>";

		$lesson = $this->loadModel($id);
		$course = Courses::model()->find("course_id = :course_id", array(":course_id" => $lesson->course_id));
		$this->render('view',array(
			'model'=>$lesson,
			'course'=>$course,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate(  )
	{
		if ( !empty($_REQUEST['course_id']) )
		{
			$course_id = Yii::app()->request->getParam('course_id');
		}
		else
			$course_id = 0;

		$course = Courses::model()->find("course_id = :course_id", array(":course_id" =>$course_id ));
		$courses = Courses::model()->findAll();
		$list = CHtml::listData( $courses, 'course_id', 'title' );

		$model=new Lessons;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Lessons']))
		{
			$model->attributes=$_POST['Lessons'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->lesson_id));
		}

		$this->render('create',array(
			'model'=>$model,
			'course'=>$course,
			'courses_list'=>$list,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$lesson=$this->loadModel($id);
		$course = Courses::model()->find("course_id = :course_id", array(":course_id" => $lesson->course_id));

		$courses = Courses::model()->findAll();
		//$list = array_values( CHtml::listData($courses, 'course_id', 'title') );
		$list = CHtml::listData( $courses, 'course_id', 'title' );

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Lessons']))
		{
/*
echo "<pre>";
print_r($_REQUEST);
echo "</pre>";
*/
			$lesson->attributes=$_POST['Lessons'];
			if($lesson->save())
				$this->redirect(array('view','id'=>$lesson->lesson_id));
		}

		$this->render('update',array(
			'model'=>$lesson,
			'course'=>$course,
			'courses_list'=>$list,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
//echo "public function actionIndex()";
//echo "<br>";
		$dataProvider=new CActiveDataProvider('Lessons');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Список уроков, не входящих в курсы
	 */
	public function actionList()
	{
//echo "public function actionList()";
//echo "<br>";
		$criteria = new CDbCriteria();
		$criteria->addInCondition('course_id', array('0') );
		$dataProvider=new CActiveDataProvider('Lessons', array('criteria' => $criteria) );
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Lessons('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Lessons']))
			$model->attributes=$_GET['Lessons'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Lessons the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Lessons::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Lessons $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='lessons-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

}//end class
