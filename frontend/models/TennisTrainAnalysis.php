<?php
namespace frontend\models;
class TennisTrainAnalysis extends Common
{
    public function rules()
    {
        return [
            [['train_id', 'ua_inside_hit', 'ua_ground_hit', 'ua_off_hit', 'ub_inside_hit', 'ub_ground_hit', 'ub_off_hit', 'status'], 'integer'],
            [['ua_hit_attr', 'ua_down_attr', 'ub_hit_attr', 'ub_down_attr'], 'required'],
            [['ua_hit_attr', 'ua_down_attr', 'ub_hit_attr', 'ub_down_attr'], 'string'],
            [['create_time'], 'safe'],
            [['ua_down', 'ub_down'], 'string', 'max' => 64],
        ];
    }
    public function isTrainAnalysisSaved($trainID)
    {
        $where = array(
            'train_id' => $trainID,
            'status' => 1
        );
        return TennisTrainAnalysis::find()->where($where)->count();

    }

    public function insertTrainData($params)
    {
        $aSideHitAttr = $this->buildAttrDataFromString(isset($params['hitAttrA']) ? $params['hitAttrA'] : '', false);
        $aSideDownAttr = $this->buildAttrDataFromString(isset($params['downAttrA']) ? $params['downAttrA'] : '',true);
        $bSideHitAttr = $this->buildAttrDataFromString(isset($params['hitAttrB']) ? $params['hitAttrB'] : '', false);
        $bSideDownAttr = $this->buildAttrDataFromString(isset($params['downAttrB']) ? $params['downAttrB'] : '', true);

        $trainAnalyseData = array(
            // 训练id
            'train_id' => isset($params['train_id']) ? $params['train_id'] : 0,

            // A面用户数据
            'ua_inside_hit' => isset($params['inside_hitA']) ? $params['inside_hitA'] : 0,
            'ua_ground_hit' => isset($params['ground_hitA']) ? $params['ground_hitA'] : 0,
            'ua_off_hit' => isset($params['off_hitA']) ? $params['off_hitA'] : 0,
            'ua_down' => isset($params['downA']) ? $params['downA'] : '',
            'ua_hit_attr' => $aSideHitAttr,
            'ua_down_attr' => $aSideDownAttr,

            // B面用户数据
            'ub_inside_hit' => isset($params['inside_hitB']) ? $params['inside_hitB'] : 0,
            'ub_ground_hit' => isset($params['ground_hitB']) ? $params['ground_hitB'] : 0,
            'ub_off_hit' => isset($params['off_hitB']) ? $params['off_hitB'] : 0,
            'ub_down' => isset($params['downB']) ? $params['downB'] : '',
            'ub_hit_attr' => $bSideHitAttr,
            'ub_down_attr' => $bSideDownAttr,

            'status' => 1,
            'create_time' => date('Y-m-d H:i:s')
        );

        $res = $this->addRecord($trainAnalyseData);
        if (empty($res)) {
            return false;
        }

        return true;
    }

    private function buildAttrDataFromString($attribute, $antiPositive)
    {
        // example:"-57100,-38900;-49600,-1700;-58400,-13400"
        $posArr = explode(';', $attribute);
        if (empty($posArr)) {
            return json_encode(array());
        }

        $attrData = array();
        foreach ($posArr as $value) {
            if (!empty($value)) {
                $pos = explode(',', $value, 3);
                if (sizeof($pos) == 3) {
                    // 发球时，落点为正取反；击球时，击球点为负取反
                    if ($antiPositive && $pos[0] >= 0 || empty($antiPositive) && $pos[0] < 0) {
                        $attrData[] = array(
                            'x' => -intval($pos[0] / 10),
                            'y' => -intval($pos[1] / 10),
                            'val' => $pos[2]
                        );
                    } else {
                        $attrData[] = array(
                            'x' => intval($pos[0] / 10),
                            'y' => intval($pos[1] / 10),
                            'val' => $pos[2]
                        );
                    }
                }
            }
        }

        return json_encode($attrData);
    }
}
