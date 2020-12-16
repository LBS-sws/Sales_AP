<?php

class IntegralController extends Controller
{
	public $function_id='HA06';

	public function filters()
	{
		return array(
			'enforceRegisteredStation',
			'enforceSessionExpiration', 
			'enforceNoConcurrentLogin',
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
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
			array('allow', 
				'actions'=>array('new','edit','delete','save','downs'),
				'expression'=>array('IntegralController','allowReadWrite'),
			),
			array('allow', 
				'actions'=>array('index','view'),
				'expression'=>array('IntegralController','allowReadOnly'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionIndex($pageNum=0) 
	{
		$model = new IntegralList;
		if (isset($_POST['IntegralList'])) {
			$model->attributes = $_POST['IntegralList'];
		} else {
			$session = Yii::app()->session;
			if (isset($session['criteria_ha06']) && !empty($session['criteria_ha06'])) {
				$criteria = $session['criteria_ha06'];
				$model->setCriteria($criteria);
			}
		}
		$model->determinePageNum($pageNum);
		$model->retrieveDataByPage($model->pageNum);
		$this->render('index',array('model'=>$model));
	}


//	public function actionSave()
//	{
//		if (isset($_POST['IntegralForm'])) {
//			$model = new IntegralForm($_POST['IntegralForm']['scenario']);
//			$model->attributes = $_POST['IntegralForm'];
//			if ($model->validate()) {
//				$model->saveData();
//				Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Save Done'));
//				$this->redirect(Yii::app()->createUrl('performance/edit',array('index'=>$model->id)));
//			} else {
//				$message = CHtml::errorSummary($model);
//				Dialog::message(Yii::t('dialog','Validation Message'), $message);
//				$this->render('form',array('model'=>$model,));
//			}
//		}
//	}

	public function actionView($index)
	{
		$model = new IntegralForm('view');
		if (!$model->retrieveData($index)) {
			throw new CHttpException(404,'The requested page does not exist.');
		} else {
//            print_r('<pre>');
//            print_r($model);
			$this->render('form',array('model'=>$model,));
		}
	}
	
	public function actionNew()
	{
		$model = new IntegralForm('new');
		$this->render('form',array('model'=>$model,));
	}

    public function actionDowns($index)
    {
        $model = new IntegralForm('new');
        $model->retrieveData($index);
        $model->downEx($model);
    }
//	public function actionEdit($index)
//	{
//		$model = new IntegralForm('edit');
//		if (!$model->retrieveData($index)) {
//			throw new CHttpException(404,'The requested page does not exist.');
//		} else {
//			$this->render('form',array('model'=>$model,));
//		}
//	}
	
//	public function actionDelete()
//	{
//		$model = new CusttypeForm('delete');
//		if (isset($_POST['IntegralForm'])) {
//			$model->attributes = $_POST['IntegralForm'];
//			if ($model->isOccupied($model->id)) {
//				Dialog::message(Yii::t('dialog','Warning'), Yii::t('dialog','This record is already in use'));
//				$this->redirect(Yii::app()->createUrl('custtype/edit',array('index'=>$model->id)));
//			} else {
//				$model->saveData();
//				Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Record Deleted'));
//				$this->redirect(Yii::app()->createUrl('custtype/index'));
//			}
//		}
//	}
	
	public static function allowReadWrite() {
		return Yii::app()->user->validRWFunction('HA06');
	}
	
	public static function allowReadOnly() {
		return Yii::app()->user->validFunction('HA06');
	}
}
