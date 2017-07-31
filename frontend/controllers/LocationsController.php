<?php
namespace frontend\controllers;
use frontend\models\Locations;
use frontend\models\Path;
use yii\web\Controller;
use yii\web\Request;

class LocationsController extends Controller{
     public $layout=0;
     //public $enableCsrfValidation=false;
     public function actionIndex(){
         $modelx=new Path();//定义一个对象没有实际意义只是为了不报错
     $id=\Yii::$app->user->identity;//获取用户id
     $id1=$id->id;
     $models=Path::find()->where(['member_id'=>$id1])->all();
         return $this->render(
             'address',['models'=>$models,'modelx'=>$modelx]);
 }
 //三级联动获取省
 public function actionAdd(){
     $id=isset($_GET['pid'])?$_GET['pid']-0:0;
     $model=Locations::find()->where(['parent_id'=>$id])->asArray()->all();
     return json_encode($model);
 }
 //三级联动获取市
 public function actionAdd2(){
     if(!$_GET['pid']==0){
         $id=$_GET['pid'];
         $model=Locations::find()->where(['parent_id'=>$id])->asArray()->all();
         return json_encode($model);

     }
 }
 //三级联动获取县
 public function actionAdd3(){
     if(!$_GET['pid']==0){
         $id=$_GET['pid'];
         $model=Locations::find()->where(['parent_id'=>$id])->asArray()->all();
         return json_encode($model);

     }
 }
 //添加保存收获地址
 public function actionPathAdd(){
     $model=new Path();
     if($model->load(\Yii::$app->request->post()) && $model->validate()){
         $models=\Yii::$app->user->identity;
         $model->member_id=$models->id;
         $model1=Locations::findOne(['id'=>$model->shen]);
         $model2=Locations::findOne(['id'=>$model->shi]);
         $model3=Locations::findOne(['id'=>$model->xian]);
         $model->shen=$model1->name;
         $model->shi=$model2->name;
         $model->xian=$model3->name;
         $model->save();
         return $this->redirect(['locations/index']);
     }

 }
 //修改并回显收获地址
 public function actionPathEdit($id){
     $modelx=Path::findOne(['id'=>$id]);
     //获取用户id
     $id=\Yii::$app->user->identity;
     $id1=$id->id;
     //根据用户id查询用户地址回显默认地址用
     $models=Path::find()->where(['member_id'=>$id1])->all();
       $request=new Request();
       if($request->isPost){
           $modelx->load($request->post());
           if($modelx->validate())
           {
               $modelx->member_id=$id1;
               //为省市县等地区字段赋值
               $model1=Locations::findOne(['id'=>$modelx->shen]);
               $model2=Locations::findOne(['id'=>$modelx->shi]);
               $model3=Locations::findOne(['id'=>$modelx->xian]);
               $modelx->shen=$model1->name;
               $modelx->shi=$model2->name;
               $modelx->xian=$model3->name;
               //保存
               $modelx->save(false);
               //跳转
               return $this->redirect(['locations/index']);

           }
       }
         //给用户id字段赋值

     return $this->render(
         'address',['modelx'=>$modelx,'models'=>$models]);

 }
 //删除收获地址
 public function actionDelete($id){
     $model=Path::findOne(['id'=>$id]);
     $model->delete();
     return $this->redirect(['locations/index']);
 }
}