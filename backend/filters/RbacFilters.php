<?php
namespace backend\filters;
use yii\base\ActionFilter;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class RbacFilters extends ActionFilter{
    public function beforeAction($action)
    {


        if(\Yii::$app->user->isGuest){
            return $action->controller->redirect(\Yii::$app->user->loginUrl);
        }
        if(!\Yii::$app->user->can($action->uniqueId)){
            throw new ForbiddenHttpException('你没有权限');
        }
        return parent::beforeAction($action);
    }
}


