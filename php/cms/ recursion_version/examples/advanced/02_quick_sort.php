<?php
/**
 * 进阶示例 2：快速排序
 * 展示递归在分治排序算法中的应用
 * 
 * 快速排序原理：
 * 1. 选择一个基准元素（pivot）
 * 2. 将小于基准的元素放到左边，大于基准的放到右边
 * 3. 递归排序左右两部分
 */

// 基本快速排序
function quickSort($arr) {
    // 基础情况：数组长度 <= 1
    if (count($arr) <= 1) {
        return $arr;
    }
    
    // 选择基准（这里选择中间元素）
    $pivot = $arr[floor(count($arr) / 2)];
    
    // 分区
    $left = [];
    $middle = [];
    $right = [];
    
    foreach ($arr as $value) {
        if ($value < $pivot) {
            $left[] = $value;
        } elseif ($value > $pivot) {
            $right[] = $value;
        } else {
            $middle[] = $value;
        }
    }
    
    // 递归排序并合并
    return array_merge(
        quickSort($left),
        $middle,
        quickSort($right)
    );
}

// 原地快速排序（更高效的实现）
function quickSortInPlace(&$arr, $left = 0, $right = null) {
    if ($right === null) {
        $right = count($arr) - 1;
    }
    
    // 基础情况
    if ($left >= $right) {
        return;
    }
    
    // 分区并获取基准位置
    $pivotIndex = partition($arr, $left, $right);
    
    // 递归排序左右部分
    quickSortInPlace($arr, $left, $pivotIndex - 1);
    quickSortInPlace($arr, $pivotIndex + 1, $right);
}

// 分区函数
function partition(&$arr, $left, $right) {
    // 选择最右边的元素作为基准
    $pivot = $arr[$right];
    
    // 小于基准的元素的最右位置
    $i = $left - 1;
    
    for ($j = $left; $j < $right; $j++) {
        if ($arr[$j] <= $pivot) {
            $i++;
            // 交换
            $temp = $arr[$i];
            $arr[$i] = $arr[$j];
            $arr[$j] = $temp;
        }
    }
    
    // 将基准放到正确位置
    $temp = $arr[$i + 1];
    $arr[$i + 1] = $arr[$right];
    $arr[$right] = $temp;
    
    return $i + 1;
}

// 可视化快速排序过程
function quickSortVisual($arr, $depth = 0) {
    $indent = str_repeat("  ", $depth);
    
    echo $indent . "排序数组: [" . implode(", ", $arr) . "]\n";
    
    if (count($arr) <= 1) {
        echo $indent . "基础情况：数组已排序\n";
        return $arr;
    }
    
    $pivotIndex = floor(count($arr) / 2);
    $pivot = $arr[$pivotIndex];
    echo $indent . "选择基准: $pivot (位置: $pivotIndex)\n";
    
    $left = [];
    $middle = [];
    $right = [];
    
    foreach ($arr as $value) {
        if ($value < $pivot) {
            $left[] = $value;
        } elseif ($value > $pivot) {
            $right[] = $value;
        } else {
            $middle[] = $value;
        }
    }
    
    echo $indent . "分区结果:\n";
    echo $indent . "  左边 (< $pivot): [" . implode(", ", $left) . "]\n";
    echo $indent . "  中间 (= $pivot): [" . implode(", ", $middle) . "]\n";
    echo $indent . "  右边 (> $pivot): [" . implode(", ", $right) . "]\n";
    
    echo $indent . "递归排序左边...\n";
    $sortedLeft = quickSortVisual($left, $depth + 1);
    
    echo $indent . "递归排序右边...\n";
    $sortedRight = quickSortVisual($right, $depth + 1);
    
    $result = array_merge($sortedLeft, $middle, $sortedRight);
    echo $indent . "合并结果: [" . implode(", ", $result) . "]\n";
    
    return $result;
}

// 随机化快速排序（避免最坏情况）
function randomizedQuickSort(&$arr, $left = 0, $right = null) {
    if ($right === null) {
        $right = count($arr) - 1;
    }
    
    if ($left >= $right) {
        return;
    }
    
    // 随机选择基准
    $randomIndex = rand($left, $right);
    // 交换到最右边
    $temp = $arr[$randomIndex];
    $arr[$randomIndex] = $arr[$right];
    $arr[$right] = $temp;
    
    $pivotIndex = partition($arr, $left, $right);
    
    randomizedQuickSort($arr, $left, $pivotIndex - 1);
    randomizedQuickSort($arr, $pivotIndex + 1, $right);
}

// 三路快速排序（处理大量重复元素）
function quickSort3Way($arr) {
    if (count($arr) <= 1) {
        return $arr;
    }
    
    $pivot = $arr[array_rand($arr)];
    
    $less = [];
    $equal = [];
    $greater = [];
    
    foreach ($arr as $value) {
        if ($value < $pivot) {
            $less[] = $value;
        } elseif ($value > $pivot) {
            $greater[] = $value;
        } else {
            $equal[] = $value;
        }
    }
    
    return array_merge(
        quickSort3Way($less),
        $equal,
        quickSort3Way($greater)
    );
}

// 测试和演示
echo "=== 快速排序演示 ===\n\n";

// 基本测试
$testArrays = [
    [3, 1, 4, 1, 5, 9, 2, 6, 5],
    [10, 9, 8, 7, 6, 5, 4, 3, 2, 1],
    [1, 2, 3, 4, 5],
    [5, 5, 5, 5, 5]
];

foreach ($testArrays as $arr) {
    echo "原始数组: [" . implode(", ", $arr) . "]\n";
    $sorted = quickSort($arr);
    echo "排序结果: [" . implode(", ", $sorted) . "]\n\n";
}

echo "=== 可视化排序过程 ===\n";
$demoArray = [6, 3, 8, 2, 9, 1];
echo "演示数组: [" . implode(", ", $demoArray) . "]\n\n";
$result = quickSortVisual($demoArray);

echo "\n=== 原地排序 ===\n";
$arr = [3, 7, 1, 4, 9, 2, 6, 5, 8];
echo "原始数组: [" . implode(", ", $arr) . "]\n";
quickSortInPlace($arr);
echo "排序结果: [" . implode(", ", $arr) . "]\n";

echo "\n=== 性能比较 ===\n";
// 生成测试数据
$sizes = [100, 1000, 10000];
foreach ($sizes as $size) {
    // 随机数组
    $randomArray = [];
    for ($i = 0; $i < $size; $i++) {
        $randomArray[] = rand(1, 1000);
    }
    
    // 复制数组用于不同算法
    $arr1 = $randomArray;
    $arr2 = $randomArray;
    $arr3 = $randomArray;
    
    // 测试基本快速排序
    $start = microtime(true);
    $sorted1 = quickSort($arr1);
    $time1 = microtime(true) - $start;
    
    // 测试原地快速排序
    $start = microtime(true);
    quickSortInPlace($arr2);
    $time2 = microtime(true) - $start;
    
    // 测试 PHP 内置排序
    $start = microtime(true);
    sort($arr3);
    $time3 = microtime(true) - $start;
    
    echo "\n数组大小: $size\n";
    echo "基本快速排序: " . number_format($time1 * 1000, 2) . " ms\n";
    echo "原地快速排序: " . number_format($time2 * 1000, 2) . " ms\n";
    echo "PHP sort(): " . number_format($time3 * 1000, 2) . " ms\n";
}

echo "\n=== 处理特殊情况 ===\n";

// 已排序数组（最坏情况）
$sorted = range(1, 20);
echo "已排序数组测试...\n";

// 大量重复元素
$duplicates = array_merge(
    array_fill(0, 10, 1),
    array_fill(0, 10, 2),
    array_fill(0, 10, 3)
);
shuffle($duplicates);
echo "重复元素数组: [" . implode(", ", array_slice($duplicates, 0, 10)) . "...]\n";
$result = quickSort3Way($duplicates);
echo "三路快排结果: [" . implode(", ", array_slice($result, 0, 10)) . "...]\n";

echo "\n=== 复杂度分析 ===\n";
echo "时间复杂度:\n";
echo "- 最好情况: O(n log n) - 每次分区都平衡\n";
echo "- 平均情况: O(n log n)\n";
echo "- 最坏情况: O(n²) - 每次分区极不平衡\n";
echo "\n空间复杂度:\n";
echo "- 递归调用栈: O(log n) 到 O(n)\n";
echo "- 原地排序版本: O(log n) 仅用于递归栈\n";
