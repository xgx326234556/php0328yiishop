<?php
namespace frontend\controllers;
use backend\models\Brand;
use backend\models\Goods;
use frontend\models\Cart;
use frontend\models\GoodsCategory;
use frontend\models\GoodsGallery;
use frontend\models\GoodsIntro;
use yii\web\Controller;
use yii\data\Pagination;
use yii\web\Cookie;

class IndexController extends Controller
{
    public $layout = false;
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        $models = GoodsCategory::find()->all();

        return $this->render('index', ['models' => $models]);
    }

    public function actionGoods($id)
    {
        $good = Goods::findOne(['id' => $id]);
        $good_intro=GoodsIntro::findOne(['goods_id'=>$id]);
        $goodgallery = GoodsGallery::find()->where(['goods_id' => $id])->all();
        $models = GoodsCategory::find()->all();
        $brand=Brand::findOne(['id'=>$good->brand_id]);
        return $this->render('goods', [
            'models' => $models, 'good' => $good, 'goodgallery' => $goodgallery
        ,'good_intro'=>$good_intro,'brand'=>$brand]);
    }

    public function actionList($id)
    {
        $models = GoodsCategory::find()->all();
        $cate = GoodsCategory::findOne(['id' => $id]);
        if ($cate->depth == 2) {
            $query = Goods::find()->where(['goods_category_id' => $id]);
            $total = $query->count();
            $perPage = 3;
            $pager = new Pagination([
                'totalCount' => $total,
                'defaultPageSize' => $perPage,
            ]);
            $goods = $query->limit($pager->limit)->offset($pager->offset)->all();
            return $this->render('list', [
                'models' => $models, 'goods' => $goods, 'pager' => $pager]);

        } elseif ($cate->depth == 1) {
            $ids = GoodsCategory::find()->select('id')->where(['parent_id' => $id])->asArray()->column();
            $query = Goods::find()->where(['in', 'goods_category_id', $ids]);
            $total = $query->count();
            $perPage = 3;
            $pager = new Pagination([
                'totalCount' => $total,
                'defaultPageSize' => $perPage,
            ]);
            $goods = $query->limit($pager->limit)->offset($pager->offset)->all();
            return $this->render('list', [
                'models' => $models, 'goods' => $goods, 'pager' => $pager]);
        } elseif ($cate->depth == 0) {
            $ids = GoodsCategory::find()->select('id')->where(['parent_id' => $id])->asArray()->column();
            $idss = GoodsCategory::find()->select('id')->where(['in', 'parent_id', $ids])->asArray()->column();
            $query = Goods::find()->where(['in', 'goods_category_id', $idss]);
            $total = $query->count();
            $perPage = 3;
            $pager = new Pagination([
                'totalCount' => $total,
                'defaultPageSize' => $perPage,
            ]);
            $goods = $query->limit($pager->limit)->offset($pager->offset)->all();
            return $this->render('list', [
                'models' => $models, 'goods' => $goods, 'pager' => $pager]);
        }


    }

    public function actionAddToCart($amount, $goods_id)
    {


        //判断该用户是否登录
        if (\Yii::$app->user->isGuest) {
            //将数据保存到cookie中
            $cookie = \Yii::$app->request->cookies;
            //判断cookie中是否存在值如果不存在保存值到cookie中
            $goods = $cookie->get('goods');
            if ($goods == null) {
                $cates = [$goods_id => $amount];
            } else {
                //取出cookie对应的值
                $cates = unserialize($goods->value);
                //判断id是否有值有就叠加
                if (isset($cates[$goods_id])) {
                    $cates[$goods_id] += $amount;
                    //没有就添加
                } else {
                    $cates[$goods_id] = $amount;
                }

            }
            //保存到cookie中
            $cookies = \Yii::$app->response->cookies;
            $cookie = new Cookie([
                'name' => 'goods',
                'value' => serialize($cates),
                'expire' => 2 * 24 * 3600 + time(),
            ]);
            $cookies->add($cookie);
        } else {
              $model = new Cart();
               $member_id = \Yii::$app->user->identity->getId();
               $models = Cart::find()
                ->andWhere(['member_id'=>$member_id])
                ->andWhere(['goods_id'=>$goods_id])
                ->one();
                   if (!$models) {
                       $model->amount = $amount;
                       $model->goods_id = $goods_id;
                       $model->member_id = $member_id;
                       $model->save(false);
                   } else {
                       $models->amount += $amount;
                       $models->save();
                   }

        }

        return $this->redirect(['index/cart']);


    }

    public function actionCart()
    {
        if (\Yii::$app->user->isGuest) {
            $cookies = \Yii::$app->request->cookies;
            $model = $cookies->get('goods');
            if ($model == null) {
                $models = [];
            } else {
                $models = unserialize($model->value);
            }
            $rows = Goods::find()->where(['in', 'id', array_keys($models)])->all();
            return $this->render('flow1', ['models' => $rows, 'cart' => $models]);
        } else {

            $member_id = \Yii::$app->user->identity->getId();
            $models = Cart::find()->where(['member_id' => $member_id])->all();
            $goods_id = [];
            $cart = [];
            foreach ($models as $model) {
                //将得到得goods_id放入数组中
                $goods_id[] = $model->goods_id;
                //模拟一个数组显示页面用
                $cart[$model->goods_id] = $model->amount;
            }
           //查询出所有商品
            $rows = Goods::find()->where(['in', 'id', $goods_id])->all();
            return $this->render('flow1', ['models' => $rows, 'cart' => $cart]);
        }


    }

    public function actionAjaxCart($goods_id,$amount)
    {
        if (\Yii::$app->user->isGuest) {
            $cookies = \Yii::$app->request->cookies;
            //获取cookie中的购物车数据
            $cart = $cookies->get('goods');
            if ($cart == null) {
                $carts = [$goods_id => $amount];
            } else {
                $carts = unserialize($cart->value);
                if (isset($carts[$goods_id])) {
                    //购物车中已经有该商品，更新数量
                    $carts[$goods_id] = $amount;
                } else {
                    //购物车中没有该商品
                    $carts[$goods_id] = $amount;
                }
            }
            //将商品id和商品数量写入cookie
            $cookies = \Yii::$app->response->cookies;
            $cookie = new Cookie([
                'name' => 'goods',
                'value' => serialize($carts),
                'expire' => 2 * 24 * 3600 + time()
            ]);
            $cookies->add($cookie);
            return 'success';
        }else{
            $member_id = \Yii::$app->user->identity->getId();

            $models = Cart::find()
                ->andWhere(['member_id'=>$member_id])
                ->andWhere(['goods_id'=>$goods_id])
                ->one();
                $models->amount = $amount;
                $models->save();


            }

    }
    public function actionDelete($id){
       if(!\Yii::$app->user->isGuest){
           $member_id=\Yii::$app->user->identity->getId();
           $models = Cart::find()
               ->andWhere(['member_id'=>$member_id])
               ->andWhere(['goods_id'=>$id])
               ->one();
           $models->delete();
           return $this->redirect(['index/cart']);
       }
       else{
           $cookies=\Yii::$app->request->cookies;
           $carts=unserialize($cookies->get('goods'));
           unset($carts[$id]);
           $cookies=\Yii::$app->response->cookies;
           //实例化cookie
           $cookie=new Cookie([
               'name'=>'goods',//cookie名
               'value'=>serialize($carts) ,//cookie值
               'expire'=>2*24*3600+time(),//设置过期时间
           ]);
           $cookies->add($cookie);//将数据保存到cookie
           return $this->redirect(['index/cart']);

       }
    }
}