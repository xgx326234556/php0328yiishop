<?php
namespace frontend\controllers;
use frontend\models\Cart;
use frontend\models\Goods;
use frontend\models\Order;
use frontend\models\OrderGoods;
use frontend\models\Path;
use yii\db\Exception;
use yii\web\Controller;

class OrderController extends Controller{
    public $layout=false;
    public $enableCsrfValidation=false;
    public function actionFlow(){
        if(\Yii::$app->user->isGuest){
            \Yii::$app->session->setFlash('success','未登录请登录');
            return $this->redirect(['/login/login']);
        }else{
             $member_id=\Yii::$app->user->identity->getId();
             $path=Path::find()->where(['member_id'=>$member_id])->all();
             $rows=Order::$deliveries;
             $data=Order::$dispatching;
             $cart=Cart::find()->where(['member_id'=>$member_id])->all();
             $amount=[];
             $goods_id=[];
             foreach ($cart as $v){
                 $goods_id[]=$v->goods_id;
                 $amount[$v->goods_id]=$v->amount;
             }
            $goods= Goods::find()->where(['in', 'id',$goods_id])->all();
            return $this->render('flow2',['path'=>$path,'rows'=>$rows
            ,'datas'=>$data,'goods'=>$goods,'amount'=>$amount]);
        }


}
public function actionAddOrder(){
        //接收post数据

    $member_id=\Yii::$app->user->identity->getId();
    $data=\Yii::$app->request->post();
    $zje=isset($data['zje'])?$data['zje']:0;
    $address_id=isset($data['address_id'])?$data['address_id']:0;
    $pay=isset($data['pay'])?$data['pay']:0;
    //总金额
    //判断是否有值
    if($zje && $address_id && $pay  ){


        $delivery=$data['delivery'];
        //获取商品的小计
        $total=unserialize($data['total']);

        //收货地址表数据
        $path=Path::findOne(['id'=>$address_id]);
        //送货方式表数据
        $deliverys=Order::$deliveries[$delivery];
        //获取支付方式
        $pays=Order::$dispatching[$pay];
        //获取购物车的数据
        $cart=Cart::find()->where(['member_id'=>$member_id])->all();
        $goods_id=[];
        foreach ($cart as $v){
            $goods_id[$v->goods_id]=$v->amount;
        }
        $ret=[];
        foreach ($cart as $a){
            $ret[]=$a->goods_id;
        }

        //获取商品数据
        $goods=Goods::find()->where(['in','id',$ret])->all();
        //实列表单模型
        //开启事物
        $transaction=\Yii::$app->db->beginTransaction();
        //遍历循环商品得到每一条的库存
        foreach ($goods as $stock){
            //遍历查询购物车里面的每一条数据
            $carts=Cart::findOne(['goods_id'=>$stock->id]);
            try{
                if(($stock->stock)>($carts->amount)){
                    $model=new Order();
                    $model1=new OrderGoods();
                    $model->member_id=$member_id;
                    $model->name=$path->name;

                    $model->province=$path->shen;
                    $model->city=$path->shi;
                    $model->area=$path->xian;
                    $model->address=$path->xpath;
                    $model->tel=$path->tel;
                    $model->delivery_id=$delivery;
                    $model->delivery_name=$deliverys['name'];
                    $model->delivery_price=$deliverys['price'];
                    $model->payment_id=$pay;
                    $model->payment_name=$pays['name'];
                    $model->total=$total[$stock->id];
                    $model->status=1;
                    $model->create_time=time();
                    $model->save();
                    $model1->order_id=$model->id;
                    $model1->goods_id=$stock->id;

                    $model1->goods_name=$stock->name;
                    $model1->logo=$stock->logo;
                    $model1->price=$stock->shop_price;
                    $model1->amount=$goods_id[$stock->id];
                    $model1->total=$total[$stock->id];
                    $model1->save();
                    $stock->stock=$stock->stock-$model1->amount;
                    $stock->save(false);
                    $transaction->commit();
                }else{
                    \Yii::$app->session->setFlash('success','商品数量不够');
                    return $this->redirect(['order/flow']);
                }
            }catch (Exception $e){
                $transaction->rollBack();
            }


        }
        $gouwuche=Cart::find()->where(['member_id'=>$member_id])->all();
        foreach ($gouwuche as $delete){
            $ramove_cart=Cart::findOne(['id'=>$delete->id]);
            $ramove_cart->delete();
        }
        return $this->redirect(['order/order-index']);

    }else{
        return $this->redirect(['order/flow']);
    }

}
public function actionOrderIndex(){
    $models=Order::find()->all();
    return $this->render('order',['models'=>$models]);
}
public function actionDelete($id){
    $model=Order::findOne(['id'=>$id]);
    $model1=OrderGoods::findOne(['order_id'=>$id]);
    $model->delete();
    $model1->delete();
    return $this->redirect(['order/order-index']);
}

}