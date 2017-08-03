<?php

namespace frontend\controllers;

use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use frontend\models\Cart;
use yii\web\Controller;
use yii\web\Cookie;

class GoodsController extends Controller
{
    public $layout=false;
    public $enableCsrfValidation=false;
     public function actionIndex(){
         $model=GoodsCategory::find()->where(['=','parent_id','0'])->all();
         //var_dump($model);exit;
         return $this->render('index',['model'=>$model]);
     }
    public function actionList($id){
         $pid=[];
        $pid_0=GoodsCategory::find()->select('id')->where(['=','parent_id',$id])->all();
        if(empty($pid_0)){
            $pid[]=$id;
        }else{
            foreach ($pid_0 as $id_0){
                $pid[]=$id_0->id;
                $pid_1=GoodsCategory::find()->select('id')->where(['=','parent_id',$id_0->id])->all();
                foreach ($pid_1 as $id_1){
                    $pid[]=$id_1->id;
                }
            }
        }
        //var_dump($pid);exit;
        $model=Goods::find()->where(['in','goods_category_id',$pid])->all();
        return $this->render('list',['model'=>$model]);
    }
    public function actionShow($id){
        $model=Goods::findOne(['=','id',$id]);
        $goods_intro=GoodsIntro::findOne(['=','goods_id',$id]);
        $goods_gallery=GoodsGallery::find()->where(['=','goods_id',$id])->all();
        //var_dump($goods_gallery[0]->path);exit;
        return $this->render('show',['model'=>$model,'goods_intro'=>$goods_intro,'goods_gallery'=>$goods_gallery]);
    }
    public function actionAddCart(){
        $goods_id=\Yii::$app->request->get('goods_id');
        $amount=\Yii::$app->request->get('amount');
        if( \Yii::$app->user->isGuest){//未登录
            $cookies=\Yii::$app->request->cookies;
            if($cookies->get('cart')==null){
                $carts=[$goods_id=>$amount];
           }else{
                $cookie=$cookies->get('cart');
                //var_dump($cookie);exit;
               $carts = unserialize($cookie->value);
               if(isset($carts[$goods_id])){
                   //购物车中已经有该商品，更新数量
                   $carts[$goods_id] += $amount;
               }else{
                   //购物车中没有该商品
                   $carts[$goods_id] = $amount;
                }
                //var_dump($carts[$goods_id]);exit;
           }
            $cookie=new Cookie([
                'name'=>'cart',
                'value'=>serialize($carts),
                'expire'=>7*24*3600+time(),
            ]);
            $cookies = \Yii::$app->response->cookies;
            $cookies->add($cookie);
       }else{//已登录
            $member_id=\Yii::$app->user->getId();
            $model=Cart::find()->where(['=','member_id',$member_id])->andWhere(['=','goods_id',$goods_id])->one();
            if($model){
                $model->amount+=$amount;
            }else{
                $model=new Cart();
                $model->member_id=\Yii::$app->user->getId();
                $model->goods_id=$goods_id;
                $model->amount=$amount;
            }
           $model->save();
       }
        return $this->redirect(['cart']);
    }
    public function actionCart(){
        if( \Yii::$app->user->isGuest){//未登录
            $cookies=\Yii::$app->request->cookies;
            $cart=$cookies->get('cart');
            if ($cart==null){
                $carts=[];
            }else{
                $carts=unserialize($cart->value);
            }
            $models = Goods::find()->where(['in','id',array_keys($carts)])->asArray()->all();
        }else{//已登录
            $member_id=\Yii::$app->user->getId();
            $goods=Cart::find()->where(['=','member_id',$member_id])->asArray()->all();
            //var_dump($goods);exit;
            $goods_id=[];
            $carts=[];
            if ($goods){
                foreach ($goods as $goods2){
                    $goods_id[]=$goods2['goods_id'];
                    $carts[$goods2['goods_id']]=$goods2['amount'];
                }
            }
            $models = Goods::find()->where(['in','id',$goods_id])->asArray()->all();
            //var_dump( $carts);exit;
        }
        return $this->render('cart',['models'=>$models,'carts'=>$carts]);
    }
    public function actionEditCart(){
        $goods_id = \Yii::$app->request->post('goods_id');
        $amount = \Yii::$app->request->post('amount');
        if( \Yii::$app->user->isGuest){//未登录
            $cookies=\Yii::$app->request->cookies;
            if($cookies->get('cart')==null){
                $carts=[$goods_id=>$amount];
            }else{
                $cookie=$cookies->get('cart');
                //var_dump($cookie);exit;
                $carts = unserialize($cookie->value);
                if(isset($carts[$goods_id])){
                    //购物车中已经有该商品，更新数量
                    $carts[$goods_id] = $amount;
                }else{
                    //购物车中没有该商品
                    $carts[$goods_id] = $amount;
                }
                //var_dump($carts[$goods_id]);exit;
            }
            $cookie=new Cookie([
                'name'=>'cart',
                'value'=>serialize($carts),
                'expire'=>7*24*3600+time(),
            ]);
            $cookies = \Yii::$app->response->cookies;
            $cookies->add($cookie);
        }else{//已登录
            $member_id=\Yii::$app->user->getId();
            $model=Cart::find()->where(['=','member_id',$member_id])->andWhere(['=','goods_id',$goods_id])->one();
            if($model){
                $model->amount=$amount;
            }else{
                $model=new Cart();
                $model->member_id=\Yii::$app->user->getId();
                $model->goods_id=$goods_id;
                $model->amount=$amount;
            }
            $model->save();
        }
        return 'success';
    }
    public function actionDeleteCart($goods_id){
        if( \Yii::$app->user->isGuest){//未登录
            $cookies=\Yii::$app->request->cookies;
            if($cookies->get('cart')!=null){
                $cookie=$cookies->get('cart');
                $carts = unserialize($cookie->value);
                if(isset($carts[$goods_id])){
                    //购物车中已经有该商品，更新数量
                    //$cookies->remove('user');
                    unset($carts[$goods_id]);
                    $cookie=new Cookie([
                        'name'=>'cart',
                        'value'=>serialize($carts),
                        'expire'=>7*24*3600+time(),
                    ]);
                    $cookies = \Yii::$app->response->cookies;
                    $cookies->add($cookie);
                }
            }
        }else{//已登录
            $member_id=\Yii::$app->user->getId();
            Cart::deleteAll(['goods_id'=>$goods_id,'member_id'=>$member_id]);
        }
        return $this->redirect(['cart']);
        //return '删除成功';
    }
    public function actionGetCart(){
        $cookies = \Yii::$app->request->cookies;
        $cart = unserialize($cookies->get('cart'));
        var_dump($cart);exit;
    }


}