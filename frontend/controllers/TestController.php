<?php
namespace frontend\controllers;
use common\components\zController;
use Yii;
class TestController extends zController {
    public function actionTestForward() {
        echo "This shows after forward.\r";
        print_r(Yaf_Dispatcher::getInstance()->getRequest());
    }

    public function TestRedirectAction() {
        echo "This shows after redirect.\r";
        print_r(Yaf_Dispatcher::getInstance()->getRequest());
    }

    public function actionTest() {
        echo "This is the new action.\r";
    }
}