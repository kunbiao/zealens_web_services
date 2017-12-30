<?php
namespace common\components;
use \yii\base\Controller;
use yii\base\InlineAction;
class zController extends Controller //这里需要继承自\yii\base\Controller
{
    /**
     * Author:Steven
     * Desc:重写路由，处理访问控制器支持驼峰命名法
     * @param string $id
     * @return null|object|InlineAction
     */
    public function createAction($id)
    {
        if ($id === '') {
            $id = $this->defaultAction;
        }
        $actionMap = $this->actions();
        if (isset($actionMap[$id])) {
            return \Yii::createObject($actionMap[$id], [$id, $this]);
        } elseif (preg_match('/^[a-z0-9\\-_]+$/', $id) && strpos($id, '--') === false && trim($id, '-') === $id) {
            $methodName = 'action' . str_replace(' ', '', ucwords(implode(' ', explode('-', $id))));
            if (method_exists($this, $methodName)) {
                $method = new \ReflectionMethod($this, $methodName);
                if ($method->isPublic() && $method->getName() === $methodName) {
                    return new InlineAction($id, $this, $methodName);
                }
            }
        } else {
            $methodName = 'action' . $id;
            if (method_exists($this, $methodName)) {
                $method = new \ReflectionMethod($this, $methodName);
                if ($method->isPublic() && $method->getName() === $methodName) {
                    return new InlineAction($id, $this, $methodName);
                }
            }
        }
        return null;
    }
}