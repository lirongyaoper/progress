<?php
/**
 * 进阶示例 1：二分查找
 * 展示递归在分治算法中的应用
 * 
 * 二分查找原理：
 * 1. 比较中间元素
 * 2. 如果目标值小于中间元素，在左半部分查找
 * 3. 如果目标值大于中间元素，在右半部分查找
 * 4. 重复直到找到或范围为空
 */

// 递归实现二分查找
function binarySearch($arr, $target, $left = 0, $right = null) {
    // 初始化右边界
    if ($right === null) {
        $right = count($arr) - 1;
    }
    
    // 基础情况：范围无效
    if ($left > $right) {
        return -1; // 未找到
    }
    
    // 计算中间位置
    $mid = floor(($left + $right) / 2);
    
    // 找到目标
    if ($arr[$mid] === $target) {
        return $mid;
    }
    
    // 目标在左半部分
    if ($target < $arr[$mid]) {
        return binarySearch($arr, $target, $left, $mid - 1);
    }
    
    // 目标在右半部分
    return binarySearch($arr, $target, $mid + 1, $right);
}

// 可视化二分查找过程
function binarySearchVisual($arr, $target, $left = 0, $right = null, $depth = 0) {
    if ($right === null) {
        $right = count($arr) - 1;
    }
    
    $indent = str_repeat("  ", $depth);
    
    // 显示当前搜索范围
    echo $indent . "搜索范围: [";
    for ($i = $left; $i <= $right && $i < count($arr); $i++) {
        if ($i > $left) echo ", ";
        echo $arr[$i];
    }
    echo "]\n";
    
    if ($left > $right) {
        echo $indent . "范围为空，未找到 $target\n";
        return -1;
    }
    
    $mid = floor(($left + $right) / 2);
    echo $indent . "中间位置: $mid, 值: {$arr[$mid]}\n";
    
    if ($arr[$mid] === $target) {
        echo $indent . "找到了！位置: $mid\n";
        return $mid;
    }
    
    if ($target < $arr[$mid]) {
        echo $indent . "$target < {$arr[$mid]}，搜索左半部分\n";
        return binarySearchVisual($arr, $target, $left, $mid - 1, $depth + 1);
    } else {
        echo $indent . "$target > {$arr[$mid]}，搜索右半部分\n";
        return binarySearchVisual($arr, $target, $mid + 1, $right, $depth + 1);
    }
}

// 查找第一个出现的位置（处理重复元素）
function binarySearchFirst($arr, $target, $left = 0, $right = null) {
    if ($right === null) {
        $right = count($arr) - 1;
    }
    
    if ($left > $right) {
        return -1;
    }
    
    $mid = floor(($left + $right) / 2);
    
    if ($arr[$mid] === $target) {
        // 继续在左边查找是否有更早的位置
        $earlier = binarySearchFirst($arr, $target, $left, $mid - 1);
        return $earlier !== -1 ? $earlier : $mid;
    }
    
    if ($target < $arr[$mid]) {
        return binarySearchFirst($arr, $target, $left, $mid - 1);
    }
    
    return binarySearchFirst($arr, $target, $mid + 1, $right);
}

// 查找插入位置
function searchInsertPosition($arr, $target, $left = 0, $right = null) {
    if ($right === null) {
        $right = count($arr) - 1;
    }
    
    // 基础情况
    if ($left > $right) {
        return $left; // 插入位置
    }
    
    $mid = floor(($left + $right) / 2);
    
    if ($arr[$mid] === $target) {
        return $mid;
    }
    
    if ($target < $arr[$mid]) {
        return searchInsertPosition($arr, $target, $left, $mid - 1);
    }
    
    return searchInsertPosition($arr, $target, $mid + 1, $right);
}

// 测试和演示
echo "=== 二分查找演示 ===\n\n";

$arr = [1, 3, 5, 7, 9, 11, 13, 15, 17, 19];
echo "有序数组: [" . implode(", ", $arr) . "]\n\n";

// 基本二分查找
$targets = [7, 10, 1, 19, 20];
foreach ($targets as $target) {
    $result = binarySearch($arr, $target);
    if ($result !== -1) {
        echo "查找 $target: 找到，位置 $result\n";
    } else {
        echo "查找 $target: 未找到\n";
    }
}

echo "\n=== 可视化查找过程 ===\n";
echo "查找数字 11 的过程：\n";
binarySearchVisual($arr, 11);

echo "\n=== 处理重复元素 ===\n";
$arrWithDuplicates = [1, 2, 2, 2, 3, 4, 4, 5, 6, 7];
echo "数组（有重复）: [" . implode(", ", $arrWithDuplicates) . "]\n";

$target = 2;
$firstPos = binarySearchFirst($arrWithDuplicates, $target);
echo "查找 $target 的第一个位置: $firstPos\n";

echo "\n=== 查找插入位置 ===\n";
$sortedArr = [1, 3, 5, 6];
$insertTargets = [5, 2, 7, 0];
echo "有序数组: [" . implode(", ", $sortedArr) . "]\n";
foreach ($insertTargets as $target) {
    $pos = searchInsertPosition($sortedArr, $target);
    echo "插入 $target 的位置: $pos\n";
}

echo "\n=== 性能分析 ===\n";
// 创建大数组
$bigArray = range(1, 1000000, 2); // 1, 3, 5, ..., 999999
$searchTarget = 654321;

$start = microtime(true);
$result = binarySearch($bigArray, $searchTarget);
$binaryTime = microtime(true) - $start;

// 线性查找对比
$start = microtime(true);
$linearResult = array_search($searchTarget, $bigArray);
$linearTime = microtime(true) - $start;

echo "数组大小: " . count($bigArray) . " 个元素\n";
echo "二分查找: " . ($result !== -1 ? "找到" : "未找到") . " (耗时: " . number_format($binaryTime * 1000, 4) . " ms)\n";
echo "线性查找: " . ($linearResult !== false ? "找到" : "未找到") . " (耗时: " . number_format($linearTime * 1000, 4) . " ms)\n";
echo "性能提升: " . number_format($linearTime / $binaryTime, 2) . " 倍\n";

echo "\n=== 递归深度 ===\n";
$n = count($bigArray);
$maxDepth = ceil(log($n, 2));
echo "数组大小: $n\n";
echo "最大递归深度: $maxDepth\n";
echo "这就是 O(log n) 的含义！\n";
