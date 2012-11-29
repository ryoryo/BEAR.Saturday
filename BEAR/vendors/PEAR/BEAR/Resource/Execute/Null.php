<?php
/**
 * BEAR
 *
 * PHP versions 5
 *
 * @category   BEAR
 * @package    BEAR_Resource
 * @subpackage Execute
 * @author     Akihito Koriyama <koriyama@users.sourceforge.jp>
 * @copyright  2008 Akihito Koriyama  All rights reserved.
 * @license    http://opensource.org/licenses/bsd-license.php BSD
 * @version    SVN: Release: $Id: Null.php 687 2009-07-03 14:49:14Z koriyama@users.sourceforge.jp $
 * @link       http://api.bear-project.net/BEAR_Resource/BEAR_Resource.html
 */
/**
 * ヌルリソース
 *
 * <pre>
 * nullを返すリソースです。
 * </pre>
 *
 * @category   BEAR
 * @package    BEAR_Resource
 * @subpackage Execute
 * @author     Akihito Koriyama <koriyama@users.sourceforge.jp>
 * @copyright  2008 Akihito Koriyama  All rights reserved.
 * @license    http://opensource.org/licenses/bsd-license.php BSD
 * @version    SVN: Release: $Id: Null.php 687 2009-07-03 14:49:14Z koriyama@users.sourceforge.jp $
 * @link       http://api.bear-project.net/BEAR_Resource/BEAR_Resource.html
 *  */
class BEAR_Resource_Execute_Null extends BEAR_Resource_Execute_Adaptor
{

    /**
     * コンストラクタ
     *
     * @param array $config 設定
     */
    public function __construct(array $config)
    {
        parent::__construct($config);
    }

    /**
     * リソースアクセス
     *
     * リソースを使用します。
     *
     * @param array  $uri    URI
     * @param array  $values 引数
     * @param string $method リクエストメソッド（無効)
     *
     * @return mixed
     */
    public function request()
    {
        return null;
    }
}