<?php

namespace App\Composite;

require_once("NodeChildren.php");

use App\Component\Base;

/**
 * 木構造の枝部分の処理を担うクラス
 */
class Node extends Base
{
    protected $nodes;

    public function __construct()
    {
        $this->nodes = [];
    }

    // 処理ノードに行動ノードを追加する
    public function add(Base $node)
    {
        array_push($this->nodes, $node);
    }
}
