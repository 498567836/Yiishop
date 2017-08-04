<?php

namespace frontend\controllers;

use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Order;
use frontend\models\OrderGoods;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use yii\db\Exception;
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
    public function actionGetCart(){//获取cookie中购物车数据
        $cookies = \Yii::$app->request->cookies;
        $cart = unserialize($cookies->get('cart'));
        var_dump( $cart);exit;
        //var_dump( array_keys($cart));exit;
    }
    public function actionOrder(){
        $user_id=\Yii::$app->user->id;
        $address=Address::find()->where(['=','user_id',$user_id])->orderBy('status DESC')->all();
        $goods_cart=Cart::find()->where(['=','member_id',$user_id])->asArray()->all();
        $goods_id=[];
        $carts=[];
        if ($goods_cart){
            foreach ($goods_cart as $goods2){
                $goods_id[]=$goods2['goods_id'];
                $carts[$goods2['goods_id']]=$goods2['amount'];
            }
        }
        $goods= Goods::find()->where(['in','id',$goods_id])->all();
        if(\Yii::$app->request->isPost){
            $address_id=\Yii::$app->request->post('address_id');
            $delivery_id=\Yii::$app->request->post('delivery');
            $payment_id=\Yii::$app->request->post('pay');
            $model=new Order();
            $transaction = \Yii::$app->db->beginTransaction();
            $address=Address::findOne(['=','id',$address_id]);
            try {
                $model->member_id = $user_id;
                $model->name = $address->username;
                $model->province = $address->province;
                $model->city = $address->city;
                $model->zone = $address->zone;
                $model->address = $address->address;
                $model->tel = $address->tel;
                $model->delivery_id = $delivery_id;
                $model->delivery_name = Order::$delivery[$delivery_id]['name'];
                $model->delivery_price = Order::$delivery[$delivery_id]['price'];
                $model->payment_id=$payment_id ;
                $model->payment_name = Order::$pay[$payment_id]['name'];
                $model->create_time = time();
                $model->total = $model->delivery_price;
                if ($payment_id==1 || $payment_id==2){
                    $model->status =2;
                }elseif ($payment_id==3){
                    $model->status =3;
                }elseif ($payment_id==4){
                $model->status =1;
                }
                foreach ($goods_id as $id){
                    $goods_buy=Goods::findOne(['=','id',$id]);
                    if($goods_buy->stock>=$carts[$id]){
                        $model->total+=$goods_buy->shop_price*$carts[$id];
                    }else{
                        throw new Exception('商品库存不足，无法继续下单，请修改购物车商品数量');
                    }
                }
                //var_dump($model);exit;
                foreach ($goods_id as $id){
                    $model->save();
                    $goods_order=new OrderGoods();
                    $goods_order->order_id=$model->id;
                    $goods_buy=Goods::findOne(['=','id',$id]);
                    $goods_order->goods_id=$id;
                    $goods_order->goods_name=$goods_buy->name;
                    $goods_order->logo=$goods_buy->logo;
                    $goods_order->price=$goods_buy->shop_price;
                    $goods_order->amount=$carts[$id];
                    $goods_order->total=$goods_buy->shop_price*$carts[$id];
                    $goods_order->save();
                    //更新商品库存
                    $goods_buy->stock-=$carts[$id];
                    $goods_buy->save();
                    //更新购物车数据
                    Cart::deleteAll(['goods_id'=>$id,'member_id'=>$user_id]);
                }
                //提交事务
                $transaction->commit();
                $status='订单提交成功，我们将及时为您处理';
                return $this->render('status',['status'=>$status]);
            }catch (Exception $e) {
                //回滚
                $transaction->rollBack();
                $status='商品库存不足';
                return $this->render('status',['status'=>$status]);
            }
        }else{//get方式访问
            //var_dump( $goods);exit;
            return $this->render('order',['address'=>$address,'goods'=>$goods,'carts'=>$carts]);
        }
    }
public function actionOrderList(){
        $order=Order::find()->where(['member_id'=>\Yii::$app->user->id])->all();
        //$order_goods=OrderGoods::find()->where(['order_id'=>\Yii::$app->user->id])->all();
        return $this->render('order-list',['order'=>$order]);
}

}