<?php
/**
 * 示例 1：简单倒计时
 * 这是最基础的递归示例，展示了递归的基本结构
 * 
 * 学习要点：
 * 1. 基础情况（Base Case）：n <= 0 时停止
 * 2. 递归调用：countdown($n - 1)
 * 3. 递归前进：每次 n 减 1
 */

// 递归实现倒计时
function countdown($n) {
    // 基础情况：当 n 为 0 或负数时停止递归
    if ($n <= 0) {
        echo "发射！🚀\n";
        echo "递归结束\n\n";
        return;
    }
    
    // 打印当前数字
    echo "倒计时: $n\n";
    
    // 递归调用：继续倒计时，但数字减 1
    // 注意：这里参数变小了，确保会到达基础情况
    countdown($n - 1);
}

// 带调试信息的倒计时（帮助理解递归过程）
function countdown_debug($n, $level = 0) {
    $indent = str_repeat("  ", $level);
    
    echo $indent . "进入 countdown($n) - 递归层级: $level\n";
    
    if ($n <= 0) {
        echo $indent . "到达基础情况！n = $n\n";
        echo $indent . "发射！🚀\n";
        echo $indent . "从 countdown($n) 返回 - 递归层级: $level\n";
        return;
    }
    
    echo $indent . "当前数字: $n\n";
    echo $indent . "准备调用 countdown(" . ($n-1) . ")\n";
    
    countdown_debug($n - 1, $level + 1);
    
    echo $indent . "从 countdown($n) 返回 - 递归层级: $level\n";
}

// 测试区域
echo "=== 简单倒计时 ===\n";
countdown(5);

echo "\n=== 带调试信息的倒计时 ===\n";
echo "观察递归的调用和返回过程：\n\n";
countdown_debug(3);

echo "\n=== 思考题 ===\n";
echo "1. 如果调用 countdown(-5) 会发生什么？\n";
echo "2. 如果去掉基础情况会发生什么？\n";
echo "3. 递归调用的顺序是怎样的？\n\n";

echo "提示：运行下面的代码看看会发生什么\n";
echo "countdown(-5); // 会立即打印'发射！'\n";
