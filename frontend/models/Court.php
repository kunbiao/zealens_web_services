<?php
namespace frontend\models;

use yii\db\ActiveRecord;

class Court extends Common
{
    public function isValidToken($token, $clientVersion)
    {
        $where = array(
            'access_token' => $token,
            'client_version_code' => $clientVersion
            // 'status' => 0
        );
        return !empty(Court::find()->asArray()->where($where)->one());
    }

    public function createGroundToken($token, $groundID, $version)
    {
        $time = time();
        $sessionID = md5($token.md5("@~cookie#_{$groundID}_{$time}"));

        // update client session.
        $where = array(
            'access_token' => $token,
            'client_version_code' => $version,
            // 'status' => 0
        );
        $data = array(
            'ground_id' => $groundID,
            'access_token' => $sessionID,
            'activate_time' => date('Y-m-d H:i:s'),
            'update_time' => date('Y-m-d H:i:s'),
            'status' => 1
        );
        $res = $this->updateRecord($data, $where);
        if (empty($res)) {
            return "";
        }

        return $sessionID;
    }

    public function chkGroundToken($token, $groundID)
    {
        $sessionData = $this->getGroundToken($groundID);
        if (empty($sessionData)) {
            return false;
        }

        // $expire = strtotime($sessionData['token_exptime']);
        // if (time() > $expire) {
        //     // 票据过期
        //     return false;
        // }

        return 0 == strcasecmp($token, $sessionData['access_token']);
    }

    public function getCourtInfo($groundID)
    {
        $result = array();
        $where = array('ground_id' => $groundID, 'status' => 1);
        Court::find()->where($where)->one();
//        $courtRecord = $this->where($where)->find();
        $courtRecord = Court::find()->asArray()->where($where)->one();
        if (empty($courtRecord)) {
            return $result;
        }

        $siteID = $courtRecord['site_id'];
        $site=new Site();
        $siteRecord = $site->getSiteInfo($siteID);
        $siteRecord['coordinate'] = array('lat' => $siteRecord['latitude'], 'lng' => $siteRecord['longitude']);
        $token = $courtRecord['access_token'];
        unset($courtRecord['site_id']);
        unset($courtRecord['id']);
        unset($courtRecord['type']);
        unset($courtRecord['ground_id']);
        unset($courtRecord['access_token']);
        unset($courtRecord['status']);
        unset($courtRecord['create_time']);
        unset($courtRecord['update_time']);
        unset($courtRecord['client_version_code']);
        unset($courtRecord['server_cfg_version_code']);
        unset($courtRecord['server_bin_version_code']);
        unset($courtRecord['client_url']);
        unset($courtRecord['server_cfg_url']);
        unset($courtRecord['server_bin_url']);
        unset($siteRecord['id']);
        unset($siteRecord['type']);
        unset($siteRecord['longitude']);
        unset($siteRecord['latitude']);
        unset($siteRecord['ground_num']);
        unset($siteRecord['status']);
        unset($siteRecord['create_time']);
        unset($siteRecord['update_time']);

        $result['site'] = $siteRecord;
        $result['court'] = $courtRecord;
        $result['access_token'] = $token;
        return $result;
    }

    public function getUpdateInfo($groundID, $sourceType)
    {
        $result = array();
        $where = array('ground_id' => $groundID, 'status' => 1);
        $updateInfo = Court::find()->where($where)->asArray()->one();
        if (empty($updateInfo)) {
            return $result;
        }

        if ($sourceType == 2) {
            unset($updateInfo['client_version_code']);
            unset($updateInfo['client_url']);
        } else {
            unset($updateInfo['server_cfg_version_code']);
            unset($updateInfo['server_bin_version_code']);
            unset($updateInfo['server_cfg_url']);
            unset($updateInfo['server_bin_url']);
        }

        unset($updateInfo['site_id']);
        unset($updateInfo['id']);
        unset($updateInfo['type']);
        unset($updateInfo['ground_id']);
        unset($updateInfo['name']);
        unset($updateInfo['access_token']);
        unset($updateInfo['lf']);
        unset($updateInfo['ld']);
        unset($updateInfo['lw']);
        unset($updateInfo['dd']);
        unset($updateInfo['ds']);
        unset($updateInfo['dw']);

        return $updateInfo;
    }

    public function updateGroundToken($token, $groundID, $clientVersion)
    {
        $time = time();
        $sessionID = md5($token.md5("@~cookie#_{$groundID}_{$time}"));

        // update client session.
        $where = array(
            'ground_id' => $groundID,
            'client_version' => $clientVersion,
            'status' => 1
        );
        $data = array(
            'access_token' => $sessionID,
            'update_time' => date('Y-m-d H:i:s')
        );
        // $res = $this->updateRecord($data, $where);
        // if (empty($res)) {
        //     return false;
        // }

        return $sessionID;
    }

    private function getGroundToken($groundID)
    {
        $where = array(
            'ground_id' => $groundID,
            'status' => 1
        );
        
        return Court::find()->where($where)->one();
    }
}