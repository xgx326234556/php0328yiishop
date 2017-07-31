<?php
namespace frontend\models;
use yii\db\ActiveRecord;

class Path extends ActiveRecord{

   public function rules()
   {
       return [
           [['name','shen','shi','xian','xpath','tel'],'required'],

       ];
   }

}