<?php
namespace backend\models;
use yii;
use yii\web\IdentityInterface;

class Goods extends yii\db\ActiveRecord implements yii\web\IdentityInterface{


    public static function findIdentity($id)
    {
        return self::findOne(['id' => $id]);
    }


    public static function findIdentityByAccessToken($token, $type = null)
    {

    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {

    }


    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }
    public function rules()
    {
        return [
            [['name','goods_category_id','brand_id',
                'market_price','shop_price','stock',
                'is_on_sale','sort'],'required' ],
            [['logo'],'string'],
            [['sort','status'],'integer'],
            [['market_price','shop_price'],'match','pattern'=>'/^\d+\.\d{2}$/','message'=>'价格后面两位小数']


        ];
    }
    public function attributeLabels()
    {
        return [
            'name'=>'商品名称',
            'goods_category_id'=>'商品分类',
            'brand_id'=>'品牌分类',
            'market_price'=>'市场价格',
            'shop_price'=>'商品价格',
            'stock'=>'商品库存',
            'is_on_sale'=>'是否在售',
            'status'=>'状态',
            'sort'=>'排序',
            'logo'=>'Logo图片',

        ];
    }
    public function getGoodsIntro(){
      return $this->hasOne(GoodsIntro::className(),['goods_id'=>'id']);
    }
    public function getBrand(){
        return $this->hasOne(Brand::className(),['id'=>'brand_id']);
    }
    public function getGoodsCategory(){
        return $this->hasOne(GoodsCategory::className(),['id'=>'goods_category_id']);
    }
}