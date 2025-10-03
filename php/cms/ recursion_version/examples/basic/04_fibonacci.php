<?php
/**
 * 示例 4：斐波那契数列
 * 展示递归的经典应用，同时介绍递归的性能问题
 * 
 * 斐波那契数列：0, 1, 1, 2, 3, 5, 8, 13, 21...
 * 规律：F(n) = F(n-1) + F(n-2)
 * 基础情况：F(0) = 0, F(1) = 1
 */

// 基础递归实现（效率低）
function fibonacci($n) {
    // 基础情况
    if ($n <= 1) {
        return $n;
    }
    
    // 递归情况：前两个数之和
    return fibonacci($n - 1) + fibonacci($n - 2);
}

// 带计数的递归（展示重复计算问题）
$call_count = 0;
function fibonacci_with_count($n) {
    global $call_count;
    $call_count++;
    
    if ($n <= 1) {
        return $n;
    }
    
    return fibonacci_with_count($n - 1) + fibonacci_with_count($n - 2);
}

// 可视化递归树
function fibonacci_tree($n, $level = 0, $prefix = "") {
    $indent = str_repeat("  ", $level);
    
    if ($n <= 1) {
        echo $indent . $prefix . "fib($n) = $n\n";
        return $n;
    }
    
    echo $indent . $prefix . "fib($n) = fib(" . ($n-1) . ") + fib(" . ($n-2) . ")\n";
    
    $left = fibonacci_tree($n - 1, $level + 1, "├─ ");
    $right = fibonacci_tree($n - 2, $level + 1, "└─ ");
    
    return $left + $right;
}

// 优化版本1：带缓存的递归（记忆化）
$memo = [];
function fibonacci_memo($n) {
    global $memo;
    
    // 检查缓存
    if (isset($memo[$n])) {
        return $memo[$n];
    }
    
    // 基础情况
    if ($n <= 1) {
        return $n;
    }
    
    // 计算并缓存结果
    $memo[$n] = fibonacci_memo($n - 1) + fibonacci_memo($n - 2);
    return $memo[$n];
}

// 优化版本2：迭代实现
function fibonacci_iterative($n) {
    if ($n <= 1) {
        return $n;
    }
    
    $prev = 0;
    $curr = 1;
    
    for ($i = 2; $i <= $n; $i++) {
        $temp = $curr;
        $curr = $prev + $curr;
        $prev = $temp;
    }
    
    return $curr;
}

// 测试和演示
echo "=== 斐波那契数列 ===\n\n";

// 打印前10个斐波那契数
echo "前10个斐波那契数：\n";
for ($i = 0; $i < 10; $i++) {
    echo "F($i) = " . fibonacci($i) . "\n";
}

echo "\n=== 递归树可视化 ===\n";
echo "计算 F(5) 的递归树：\n\n";
fibonacci_tree(5);

echo "\n=== 重复计算问题 ===\n";
$call_count = 0;
$result = fibonacci_with_count(10);
echo "计算 F(10) = $result\n";
echo "函数调用次数：$call_count 次\n";
echo "注意：存在大量重复计算！\n";

echo "\n=== 性能对比 ===\n";
$test_n = 30;

// 基础递归
$start = microtime(true);
$result1 = fibonacci($test_n);
$time1 = microtime(true) - $start;

// 记忆化递归
$memo = [];
$start = microtime(true);
$result2 = fibonacci_memo($test_n);
$time2 = microtime(true) - $start;

// 迭代版本
$start = microtime(true);
$result3 = fibonacci_iterative($test_n);
$time3 = microtime(true) - $start;

echo "计算 F($test_n) 的结果和耗时：\n";
echo "基础递归: $result1 (耗时: " . number_format($time1 * 1000, 2) . " ms)\n";
echo "记忆化递归: $result2 (耗时: " . number_format($time2 * 1000, 2) . " ms)\n";
echo "迭代版本: $result3 (耗时: " . number_format($time3 * 1000, 2) . " ms)\n";

echo "\n=== 递归的教训 ===\n";
echo "1. 递归不一定是最优解\n";
echo "2. 重复计算会严重影响性能\n";
echo "3. 记忆化可以优化递归性能\n";
echo "4. 有时迭代比递归更合适\n";

echo "\n=== 思考题 ===\n";
echo "1. 为什么基础递归版本这么慢？\n";
echo "2. 记忆化是如何提升性能的？\n";
echo "3. 什么情况下应该选择递归而不是迭代？\n";
