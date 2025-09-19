<?php
/**
 * 示例 2：计算阶乘
 * 阶乘是递归的经典例子，展示了递归如何简化数学计算
 * 
 * 数学定义：
 * n! = n × (n-1) × (n-2) × ... × 1
 * 递归定义：
 * n! = n × (n-1)!
 * 0! = 1 (基础情况)
 */

// 递归计算阶乘
function factorial($n) {
    // 基础情况：0! = 1, 1! = 1
    if ($n <= 1) {
        return 1;
    }
    
    // 递归情况：n! = n × (n-1)!
    return $n * factorial($n - 1);
}

// 带过程展示的阶乘计算
function factorial_with_process($n, $level = 0) {
    $indent = str_repeat("  ", $level);
    
    echo $indent . "计算 $n!\n";
    
    if ($n <= 1) {
        echo $indent . "基础情况：$n! = 1\n";
        return 1;
    }
    
    echo $indent . "需要计算：$n × " . ($n-1) . "!\n";
    
    // 递归计算 (n-1)!
    $result = $n * factorial_with_process($n - 1, $level + 1);
    
    echo $indent . "得到结果：$n! = $result\n";
    
    return $result;
}

// 循环版本（用于对比）
function factorial_loop($n) {
    $result = 1;
    for ($i = 1; $i <= $n; $i++) {
        $result *= $i;
    }
    return $result;
}

// 测试和演示
echo "=== 阶乘计算 ===\n\n";

// 基本测试
for ($i = 0; $i <= 5; $i++) {
    echo "$i! = " . factorial($i) . "\n";
}

echo "\n=== 递归过程展示 ===\n";
echo "让我们看看 5! 是如何计算的：\n\n";
$result = factorial_with_process(5);
echo "\n最终结果：5! = $result\n";

echo "\n=== 递归 vs 循环 ===\n";
$n = 10;
$recursive_result = factorial($n);
$loop_result = factorial_loop($n);
echo "递归计算 $n! = $recursive_result\n";
echo "循环计算 $n! = $loop_result\n";
echo "结果" . ($recursive_result === $loop_result ? "相同" : "不同") . "\n";

echo "\n=== 栈溢出演示 ===\n";
echo "注意：计算很大的阶乘可能导致栈溢出\n";
echo "例如：factorial(100000) 会导致错误\n";
echo "这是递归的一个限制\n";

echo "\n=== 思考题 ===\n";
echo "1. 为什么 0! = 1？这个基础情况的设定有什么好处？\n";
echo "2. 如果输入负数会发生什么？应该如何处理？\n";
echo "3. 递归版本和循环版本，哪个更容易理解？为什么？\n";
