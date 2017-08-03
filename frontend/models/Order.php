<?php
namespace frontend\models;
use yii\db\ActiveRecord;

class Order extends ActiveRecord{
  public static $deliveries=[
      1=>['name'=>'顺丰快递','price'=>'25','detail'=>'速度快，服务好，价格贵'],
      2=>['name'=>'圆通快递','price'=>'10','detail'=>'速度一般，服务一般，价格便宜'],
      3=>['name'=>'EMS','price'=>'20','detail'=>'速度一般，服务一般，价格贵，国内任何地址都可以送到'],
  ];
  public static $dispatching=[
      1=>['name'=>'货到付款','content'=>'送货上门后再收款，支持现金、POS机刷卡、支票支付'],
      2=>['name'=>'在线支付','content'=>'即时到帐，支持绝大数银行借记卡及部分银行信用卡'],
      3=>['name'=>'上门自提','content'=>'自提时付款，支持现金、POS刷卡、支票支付'],
      4=>['name'=>'邮局汇款','content'=>'通过快钱平台收款 汇款后1-3个工作日到账'],
      ];
}