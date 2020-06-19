<?php

namespace App\Component;

/**
 * 木構造の枝、葉の共通API
 * Compositeパターンにより木構造を実現する
 */
abstract class Base
{
    private $process; // 各ノードの処理を入れる変数

    public function __construct($process)
    {
        $this->process = $process;
    }

    // 各ノードの中身を関数として返す
    public function __invoke()
    {
        $process = $this->process;
        return $process();
    }

    public abstract function add(Base $action);
}