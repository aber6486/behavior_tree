<?php
require_once("App/Component/Base.php");
require_once("App/Composite/Node.php");
require_once("App/Leaf/Action.php");

use App\Composite\Sequencer;
use App\Composite\Selector;
use App\Composite\Repeater;
use App\Composite\Parallel;
use App\Leaf\Action;

echo "skill1の発動確率: ";
$input = trim(fgets(STDIN));
//var_dump($input);

$my_hp = 150;
$enemy_hp = 120;
$loop = 2;
$skill_1_prob = $input;
$skill_1_damage = 50;
$skill_2_prob = 100;
$skill_2_damage = 60;

// 根
$root_sequencer = new Sequencer(); 

// 出発の行動
$start = new Action(function() {echo "出発\n"; return true;});
$root_sequencer->add($start);

// 敵に寄るか行動選択
$close_enemy_action = new Action(function() {echo "敵に寄る\n"; return true;});
$check_my_hp_action = new Action(function() use ($my_hp, $close_enemy_action) {
    if ($my_hp >= 100) {
        echo "自分のHPが100以上\n";
        return $close_enemy_action();
    }
});
$root_sequencer->add($check_my_hp_action);

// 友達A,Bを召喚
$parallel = new Parallel();
$frend_a = new Action(function() {echo "友達A\n"; return true;});
$frens_b = new Action(function() {echo "友達B\n"; return true;});
$parallel->add($frend_a);
$parallel->add($frens_b);
$root_sequencer->add($parallel);

// スキル１、２の内一つを選択する処理を２回繰り返す
$repeater = new Repeater(); // 繰り返しノード初期化
$repeater->loop = $loop; // ループ回数を指定
$skill_selector = new Selector(); // 行動選択ノード初期化
// スキル１が発動した場合の行動：スキル１は確率で発動
$skill_a_action = new Action(function() use ($skill_1_prob, $skill_1_damage, &$enemy_hp) {
    if ($skill_1_prob >= random_int(0, 100)) {
        echo "skillA\n";
        $enemy_hp -= $skill_1_damage;
        return true;
    } else {
        return false;
    }
});
// スキル２が発動した場合の行動：スキル２は必ず発動
$skill_b_action = new Action(function() use ($skill_2_damage ,&$enemy_hp) {
    echo "skillB\n"; 
    $enemy_hp -= $skill_2_damage;
    return true;
});
$skill_selector->add($skill_a_action);
$skill_selector->add($skill_b_action);
$repeater->add($skill_selector);
$root_sequencer->add($repeater);

// エンド選択処理：敵のHPによって行動を選択
$check_enemy_selector = new Selector();
$end_1_action = new Action(function() {echo "End1\n"; return true;});
$end_2_action = new Action(function() {echo "End2\n"; return true;});
// 敵のHPが０になっていたらエンド１を返すアクション
$enemy_dead_action = new Action(function() use (&$enemy_hp, $end_1_action) {
    if ($enemy_hp <= 0) {
        echo "敵が死んだら\n";
        return $end_1_action();
    }
});
// 敵のHPが残っていたらエンド２を返すアクション
$enemy_live_action = new Action(function() use (&$enemy_hp, $end_2_action) {
    if ($enemy_hp >= 0) {
        echo "敵が生きてる\n";
        return $end_2_action();
    }
});
$check_enemy_selector->add($enemy_dead_action);
$check_enemy_selector->add($enemy_live_action);
$root_sequencer->add($check_enemy_selector);

//var_dump($enemy_hp);
$root_sequencer(); // 根のシーケンサーノードからスタート
//var_dump($enemy_hp);