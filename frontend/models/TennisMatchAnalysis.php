<?php
namespace frontend\models;
class TennisMatchAnalysis extends Common
{
    public function isMatchAnalysisSaved($matchID)
    {
        $where = array(
            'match_id' => $matchID,
            'status' => 1
        );

        return TennisMatchAnalysis::find()->where($where)->count() != 0;
    }
    public function rules()
    {
        return [
            [['match_id', 'ua_net_hit', 'ua_inside_hit', 'ua_ground_hit', 'ua_off_hit', 'ub_net_hit', 'ub_inside_hit', 'ub_ground_hit', 'ub_off_hit', 'status'], 'integer'],
            [['ua_ace_attr', 'ua_fsin_attr', 'ua_ssin_attr', 'ua_hit_attr', 'ub_ace_attr', 'ub_fsin_attr', 'ub_ssin_attr', 'ub_hit_attr'], 'required'],
            [['ua_ace_attr', 'ua_fsin_attr', 'ua_ssin_attr', 'ua_hit_attr', 'ub_ace_attr', 'ub_fsin_attr', 'ub_ssin_attr', 'ub_hit_attr'], 'string'],
            [['create_time'], 'safe'],
            [['ua_fsin', 'ua_ssin', 'ub_fsin', 'ub_ssin'], 'string', 'max' => 64],
        ];
    }
    public function insertMatchData($params)
    {
        $aSideAceAttr = $this->buildAttrDataFromString(isset($params['aceAttrA']) ? $params['aceAttrA'] : '', true);
        $aSideFsinAttr = $this->buildAttrDataFromString(isset($params['fsinAttrA']) ? $params['fsinAttrA'] : '', true);
        $aSideSsinAttr = $this->buildAttrDataFromString(isset($params['ssinAttrA']) ? $params['ssinAttrA'] : '', true);
        $aSideHitAttr = $this->buildAttrDataFromString(isset($params['hitAttrA']) ? $params['hitAttrA'] : '', false);
        $bSideAceAttr = $this->buildAttrDataFromString(isset($params['aceAttrB']) ? $params['aceAttrB'] : '', true);
        $bSideFsinAttr = $this->buildAttrDataFromString(isset($params['fsinAttrB']) ? $params['fsinAttrB'] : '', true);
        $bSideSsinAttr = $this->buildAttrDataFromString(isset($params['ssinAttrB']) ? $params['ssinAttrB'] : '', true);
        $bSideHitAttr = $this->buildAttrDataFromString(isset($params['hitAttrB']) ? $params['hitAttrB'] : '', false);
        // insert match statistics table
        $matchAnalysisData = array(
            // 比赛id
            'match_id' => isset($params['match_id']) ? $params['match_id'] : 0,

            // A面用户数据
            'ua_net_hit' => isset($params['net_hitA']) ? $params['net_hitA'] : 0,
            'ua_inside_hit' => isset($params['inside_hitA']) ? $params['inside_hitA'] : 0,
            'ua_ground_hit' => isset($params['ground_hitA']) ? $params['ground_hitA'] : 0,
            'ua_off_hit' => isset($params['off_hitA']) ? $params['off_hitA'] : 0,
            'ua_fsin' => isset($params['fsinA']) ? $params['fsinA'] : '',
            'ua_ssin' => isset($params['ssinA']) ? $params['ssinA'] : '',
            'ua_ace_attr' => $aSideAceAttr,
            'ua_fsin_attr' => $aSideFsinAttr,
            'ua_ssin_attr' => $aSideSsinAttr,
            'ua_hit_attr' => $aSideHitAttr,

            // B面用户数据
            'ub_net_hit' => isset($params['net_hitB']) ? $params['net_hitB'] : 0,
            'ub_inside_hit' => isset($params['inside_hitB']) ? $params['inside_hitB'] : 0,
            'ub_ground_hit' => isset($params['ground_hitB']) ? $params['ground_hitB'] : 0,
            'ub_off_hit' => isset($params['off_hitB']) ? $params['off_hitB'] : 0,
            'ub_fsin' => isset($params['fsinB']) ? $params['fsinB'] : '',
            'ub_ssin' => isset($params['ssinB']) ? $params['ssinB'] : '',
            'ub_ace_attr' => $bSideAceAttr,
            'ub_fsin_attr' => $bSideFsinAttr,
            'ub_ssin_attr' => $bSideSsinAttr,
            'ub_hit_attr' => $bSideHitAttr,

            'status' => 1,
            'create_time' => date('Y-m-d H:i:s')
        );

        $res = $this->addRecord($matchAnalysisData);
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
                $pos = explode(',', $value, 2);
                if (sizeof($pos) == 2) {
                    // 发球时，落点为正取反；击球时，击球点为负取反
                    if ($antiPositive && $pos[0] >= 0 || empty($antiPositive) && $pos[0] < 0) {
                        $attrData[] = array(
                            'x' => -intval($pos[0] / 10),
                            'y' => -intval($pos[1] / 10),
                            'val' => 1
                        );
                    } else {
                        $attrData[] = array(
                            'x' => intval($pos[0] / 10),
                            'y' => intval($pos[1] / 10),
                            'val' => 1
                        );
                    }
                }
            }
        }

        return json_encode($attrData);
    }
}
