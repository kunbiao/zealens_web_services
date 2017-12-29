<?php
/**
 * @name UserController
 * @author
 * @desc
 */
namespace frontend\controllers;
class UserController extends \frontend\controllers\CommonController
{
    public function init()
    {
        parent::init();
        $this->_bizType     = "user";
    }

    public function actionLogin()
    {
        $this->_func = __FUNCTION__;
        $this->chkDebug();
        $unionID = $this->_postData['union_id'];
        if (empty($unionID)) {
            $this->_errCode = 101;
            $this->_errMsg = '用户微信ID为空';
            $this->outputMsg();
        }

        //TODO, get userinfo from cache.
        $user=new \frontend\models\User();
        $userInfo = $user->getUserInfoWithUnionID($unionID);
        if (empty($userInfo)) {
//            trace($unionID, 'loginAction', 'DEBUG', true);
            $userInfo = $user->addUser($unionID);
        }

        $this->_reinfo = $userInfo;
        $this->outputMsg();
    }

    public function getTokenAction()
    {
        header("Content-Type:text/html; charset=gbk");
        $url='https://api.weixin.qq.com/sns/userinfo?access_token=';
        $url .= $_GET['token'];
        $url .= '&openid=';
        $url .= $_GET['openid'];

        $html = file_get_contents($url);

        // $html = mb_convert_encoding($html, "gbk", "UTF-8");
        $html = iconv("UTF-8", "gbk", $html);
        echo $html;
    }
}

