<?php

namespace App\Leaf;

use App\Component\Base;
use FFI\Exception;

/**
 * 木構造の葉部分の処理を担うクラス
 */
class Action extends Base
{
    public function __construct($process)
    {
        parent::__construct($process);
    }

    public function __invoke() 
    {
        return parent::__invoke();
    }

    // アクションノードの下にノードは追加できない
    public function add(Base $leaf)
    {
        throw new Exception('This method is not allowed.');
    }
}