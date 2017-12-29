<?php
/**
 * @name UpdateController
 * @author
 * @desc
 */
namespace frontend\controllers;
class UpdateController extends CommonController
{
    public function init()
    {
        parent::init();
        $this->_bizType = "update";
//        $this->_action = "";
    }

    public function actionGetUpdateInfo()
    {
        $this->_func = __FUNCTION__;
        $this->chkDebug();

        $sourceType = $this->_postData['source_type'];
        if (empty($sourceType)) {
            $this->_errCode = 10001;
            $this->_errMsg = '非法请求来源';
            $this->outputMsg();
        }
        if ($sourceType != 2) {
            $this->_errCode = 10002;
            $this->_errMsg = '非法请求来源';
            $this->outputMsg();
        }

        $groundID = $this->_postData['ground_id'];
        $Court=new \frontend\models\Court();
        $updateInfo = $Court->getUpdateInfo($groundID, $sourceType);
        if (empty($updateInfo)) {
            $this->_errCode = 10003;
            $this->_errMsg = '获得升级信息失败';
            $this->outputMsg();
        }

        $this->_reinfo = $updateInfo;
        $this->outputMsg();
    }
}

