<?php
namespace backend\controllers;
use backend\filters\RbacFilters;
use backend\models\Admin;
use yii\web\Controller;
use yii\web\Request;
use yii\data\Pagination;
use backend\models\LogoForm;
use yii;
class AdminController extends Controller
{
    public function actionAdd()
    {
        $model = new Admin(['scenario'=>Admin::SCENARIO_ADD]);
        $request = new Request();
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password);
                $model->created_at = time();
                $model->status = 1;
                $model->save();

                if(is_array($model->role)){
                    $authManager=Yii::$app->authManager;
                    foreach ($model->role as $roleName){
                        $role=$authManager->getRole($roleName);
                        if($role){
                            $authManager->assign($role,$model->id);
                        }
                    }
                }
                \Yii::$app->session->setFlash('success', '添加成功哦亲');
                return $this->redirect(['admin/index']);
            }
        }

        return $this->render('add', ['model' => $model]);
    }

    public function actionIndex($keyword='')
    {
        //如果存在执行以下代码
        $query = Admin::find()->where(['and','status=1',"username like '%{$keyword}%'"]);
        $total = $query->count();
        $perPage = 2;
        //分页工具类
        $pager = new Pagination([
            'totalCount' => $total,
            'defaultPageSize' => $perPage
        ]);
        //按条件取出数据
        $row = $query->limit($pager->limit)->offset($pager->offset)->orderBy('id desc')->all();

        return $this->render('index', ['models' => $row, 'pager' => $pager]);
        //如果session为空，判断cookie是否存在，如果存在执行以下代码
    }


    public function actionEdit($id)
    {
        $authManager=Yii::$app->authManager;
        //根据id找到需要回显的角色数据
        $roles=$authManager->getRolesByUser($id);
        $model = Admin::findOne(['id' => $id]);
        $request = new Request();
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                if($model->password){
                    $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password);
                }

                $model->updated_at = time();
                //$model->status=1;
                $model->save();
                //取消绑定
                if(is_array($roles)){
                    foreach ($roles as $v){
                        $authManager->revoke($v,$id);
                    }
                }
                if(is_array($model->role)){
                    foreach ($model->role as $roleName){
                        $role=$authManager->getRole($roleName);
                        if($role){
                            $authManager->assign($role,$model->id);
                        }
                    }
                }
                \Yii::$app->session->setFlash('success', '修改成功哦亲');
                return $this->redirect(['admin/index']);
            }
        }
        //回显数据
        $model->role=yii\helpers\ArrayHelper::map($roles,'name','name');
        return $this->render('add', ['model' => $model]);
    }

    public function actionDelete($id)
    {
        $authManager=Yii::$app->authManager;
        $roles=$authManager->getRolesByUser($id);
        $model = Admin::findOne(['id' => $id]);
        $model->status = 0;
        $model->save();
        if(is_array($roles)){
            foreach ($roles as $v){
                $authManager->revoke($v,$id);
            }
        }
        \Yii::$app->session->setFlash('success', '删除哦亲');
        return $this->redirect(['admin/index']);
    }
    public function actionLogin()
    {
        $model = new LogoForm();
        //判断请求方式
        $rquest = new Request();
        if ($rquest->isPost) {
            $model->load($rquest->post());
            if ($model->validate() && $model->login()) {
                //输出登录成功
                \yii::$app->session->setFlash('success', '登录成功');
                return $this->redirect(['admin/index']);
            }
        }
        return $this->render('logo', ['model' => $model]);
    }


    public function actionUser()
    {
        //可以通过 Yii::$app->user 获得一个 User实例，
        $user = \Yii::$app->user;

        // 当前用户的身份实例。未认证用户则为 Null 。
        $identity = \Yii::$app->user->identity;
        //var_dump($identity);

        // 当前用户的ID。 未认证用户则为 Null 。
        $id = \Yii::$app->user->id;
        //var_dump($id);
        // 判断当前用户是否是游客（未认证的）
        $isGuest = \Yii::$app->user->isGuest;
        var_dump($isGuest);
    }
    public function actionLogout()
    {
        \Yii::$app->user->logout();
        return $this->redirect(['admin/login']);
    }
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilters::className(),
                'only'=>['add','edit','delete','index'],

            ]
        ];
    }

}