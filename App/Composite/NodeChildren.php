<?php
namespace App\Composite;

use App\Composite\Node;

/**
 * 行動ノードを処理するための内部ノード群
 */

/**
 * 保有しているノード内の処理がTrueである限り処理し続ける
 */
class Sequencer extends Node
{
    public function __invoke()
    {
        foreach ($this->nodes as $node) {
            if (!$node()) {
                return false;
            }
        }
        return true;
    }
}

/**
 * 保有しているノード内の処理がFalseである限り処理し続ける
 */
class Selector extends Node
{
    function __invoke()
    {
        foreach ($this->nodes as $node) {
            if ($node()) {
                return true;
            }
        }
        return false;
        
    }
}

/**
 * 指定した回数保有しているノードの処理をする
 */
class Repeater extends Node
{
    private $loop; // ループ回数

    // ループ回数セッター
    function __set($name, $value)
    {
        $this->loop = $value;
    }

    function __invoke()
    {
       for ($i = 0; $i < $this->loop; $i++) {
           foreach ($this->nodes as $node) {
               $node();
           }
       }
       return true;
    }
}

/**
 * 保有しているノードの処理を１度づつ行う
 * 処理の内容的にはsequencerと同様？
 */
class Parallel extends Sequencer
{
    /*
    function __invoke()
    {
        foreach ($this->leafs as $leaf) {
            $leaf();
        }
    }
    */
}