<?php
/**
 * 示例 3：计算 1 到 n 的和
 * 展示递归如何处理累加问题
 * 
 * 问题：计算 1 + 2 + 3 + ... + n
 * 递归思路：sum(n) = n + sum(n-1)
 */

// 递归计算和
function sum($n) {
    // 基础情况：当 n 为 0 时，和为 0
    if ($n <= 0) {
        return 0;
    }
    
    // 递归情况：n + (1到n-1的和)
    return $n + sum($n - 1);
}

// 可视化递归过程
function sum_visual($n, $level = 0) {
    $indent = str_repeat("│ ", $level);
    $arrow = $level > 0 ? "└─> " : "";
    
    echo $indent . $arrow . "sum($n) 被调用\n";
    
    if ($n <= 0) {
        echo $indent . "    返回 0 (基础情况)\n";
        return 0;
    }
    
    echo $indent . "    计算: $n + sum(" . ($n-1) . ")\n";
    
    $result = $n + sum_visual($n - 1, $level + 1);
    
    echo $indent . "    sum($n) = $result\n";
    
    return $result;
}

// 数组求和（更实用的例子）
function array_sum_recursive($arr, $index = 0) {
    // 基础情况：到达数组末尾
    if ($index >= count($arr)) {
        return 0;
    }
    
    // 递归情况：当前元素 + 剩余元素的和
    return $arr[$index] + array_sum_recursive($arr, $index + 1);
}

// 另一种数组求和方式（使用数组切片）
function array_sum_slice($arr) {
    // 基础情况：空数组
    if (empty($arr)) {
        return 0;
    }
    
    // 取出第一个元素，递归处理剩余部分
    $first = array_shift($arr);
    return $first + array_sum_slice($arr);
}

// 测试和演示
echo "=== 计算 1 到 n 的和 ===\n\n";

// 基本测试
for ($i = 1; $i <= 5; $i++) {
    echo "1 + 2 + ... + $i = " . sum($i) . "\n";
}

echo "\n=== 递归过程可视化 ===\n";
echo "计算 sum(5) 的过程：\n\n";
$result = sum_visual(5);
echo "\n结果：1 + 2 + 3 + 4 + 5 = $result\n";

echo "\n=== 数组求和 ===\n";
$numbers = [10, 20, 30, 40, 50];
echo "数组: [" . implode(", ", $numbers) . "]\n";
echo "递归求和（索引方式）: " . array_sum_recursive($numbers) . "\n";
echo "递归求和（切片方式）: " . array_sum_slice($numbers) . "\n";
echo "PHP内置函数: " . array_sum($numbers) . "\n";

echo "\n=== 性能对比 ===\n";
$n = 1000;
$start = microtime(true);
$result1 = sum($n);
$time1 = microtime(true) - $start;

$start = microtime(true);
$result2 = $n * ($n + 1) / 2; // 数学公式
$time2 = microtime(true) - $start;

echo "递归计算 1+2+...+$n = $result1 (耗时: " . number_format($time1 * 1000, 4) . " ms)\n";
echo "公式计算 1+2+...+$n = $result2 (耗时: " . number_format($time2 * 1000, 4) . " ms)\n";

echo "\n=== 思考题 ===\n";
echo "1. sum(5) 会产生多少次函数调用？\n";
echo "2. 为什么数组求和的两种方式结果相同但过程不同？\n";
echo "3. 什么时候应该使用递归，什么时候应该使用其他方法？\n";
