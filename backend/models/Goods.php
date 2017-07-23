<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "goods".
 *
 * @property integer $id
 * @property string $name
 * @property string $sn
 * @property string $logo
 * @property integer $goods_category_id
 * @property integer $brand_id
 * @property string $market_price
 * @property string $shop_price
 * @property integer $stock
 * @property integer $is_on_sale
 * @property integer $status
 * @property integer $sort
 * @property integer $create_time
 * @property integer $view_times
 */
class Goods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods';
    }
    public $logoFile;
    public static function status_options(){
        return $status=[
            //状态(1正常 0回收站)
            1=>'正常',0=>'回收站'
        ];
    }
    public static function is_on_sale_options(){
        return $status=[
            //是否在售(1在售 0下架)
            1=>'在售',0=>'下架'
        ];
    }
    public  function getBrands(){
        $this->hasOne(Brand::className(),['id'=>'brand_id']);//hasOne 返回一个对象
        $Brand=Brand::find()->all();
        $b=[];
        foreach ($Brand as $a){
            $b[$a->id]=$a->name;
        }
        return  $b;
    }
    public function getGoodsCategorys(){
        $this->hasOne(GoodsCategory::className(),['id'=>'goods_category_id']);//hasOne 返回一个对象
        $Brand=GoodsCategory::find()->all();
        $b=[];
        foreach ($Brand as $a){
            $b[$a->id]=$a->name;
        }
        return  $b;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_category_id', 'brand_id', 'stock', 'is_on_sale', 'status', 'sort','market_price', 'shop_price','name'], 'required','message'=>'{attribute}必填'],
           // [['goods_category_id', 'brand_id', 'stock', 'is_on_sale', 'status', 'sort', 'create_time', 'view_times'], 'integer'],
            [['market_price', 'shop_price','stock','sort'], 'number'],
            [['logo'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '商品ID',
            'name' => '商品名称',
            'sn' => '货号',
            'logo' => 'LOGO',
            'goods_category_id' => '商品分类',
            'brand_id' => '品牌分类',
            'market_price' => '市场价格',
            'shop_price' => '商品价格',
            'stock' => '库存',
            'is_on_sale' => '是否在售',
            'status' => '状态',
            'sort' => '排序',
            'create_time' => '添加时间',
            'view_times' => '浏览次数',
        ];
    }
}
