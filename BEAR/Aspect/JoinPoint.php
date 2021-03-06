<?php
/**
 * This file is part of the BEAR.Saturday package.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */

/**
 * ジョインポイント
 *
 * アドバイスが織り込まれるターゲットクラスのリフレクションを用いて
 * 情報を取得したりメソッドを実行したりできます。
 */
class BEAR_Aspect_JoinPoint extends BEAR_Base
{
    /**
     * 折り込み元のオリジナルメソッドを実行
     *
     * アドバイス織り込み対象元のメソッドを実行します。
     *
     * @param array $values 引数
     *
     * @return mixed
     */
    public function proceed(array $values)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $result = $this->_config['ref']['method']->invoke($this->_config['object'], $values);

        return $result;
    }

    /**
     * 引数の取得
     *
     * @return array
     */
    public function getArgument()
    {
        return $this->_config['values'];
    }

    /**
     * joinpointの取得
     *
     * jointpointを取得します。
     *
     * @return stdClass
     */
    public function getObject()
    {
        return $this->_config['object'];
    }

    /**
     * joinpointのメソッドリフレクションを返す
     *
     * @return ReflectionMethod
     */
    public function getMethodReflection()
    {
        return $this->_config['ref']['method'];
    }

    /**
     * joinpointのクラスリフレクションを返す
     *
     * @return ReflectionClass
     */
    public function getClassReflection()
    {
        return $this->_config['ref']['class'];
    }
}
