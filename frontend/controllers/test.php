<?php
namespace frontend\controllers;
use app\models\Admin;
use app\models\User;
use frontend\controllers\base\BaseController;
use GuzzleHttp\Psr7\Request;
use yii\base\Model;
use Yii;
/**
 * 用户控制器
 */
//用户列表
class UserController extends BaseController{
    public $name;
    public function actionIndex()
    {
//        Request::queryString;

//        $request = Yii::$app->request;
        $request = Yii::$app->request;
//        $a=User::find()->all();
//        var_dump($a);
// 等同于 $User->update();
        $a=new User();
        $a->ceshi();
    
        die;
        $get = $request->get();
        var_dump($get);die;
        $params = $request->bodyParams;
        var_dump($params);
        return $this->render('index');
    }
}