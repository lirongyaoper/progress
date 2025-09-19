<?php
/**
 * 示例 5：字符串反转
 * 展示递归如何处理字符串和数组
 * 
 * 递归思路：
 * 1. 取出第一个字符
 * 2. 反转剩余部分
 * 3. 将第一个字符放到最后
 */

// 递归反转字符串
function reverseString($str) {
    // 基础情况：空字符串或单字符
    if (strlen($str) <= 1) {
        return $str;
    }
    
    // 递归情况：
    // 1. 取出第一个字符
    $firstChar = $str[0];
    // 2. 获取剩余部分
    $remaining = substr($str, 1);
    // 3. 反转剩余部分 + 第一个字符
    return reverseString($remaining) . $firstChar;
}

// 可视化递归过程
function reverseStringVisual($str, $level = 0) {
    $indent = str_repeat("  ", $level);
    echo $indent . "反转: '$str'\n";
    
    if (strlen($str) <= 1) {
        echo $indent . "基础情况: 返回 '$str'\n";
        return $str;
    }
    
    $firstChar = $str[0];
    $remaining = substr($str, 1);
    
    echo $indent . "分解: 首字符='$firstChar', 剩余='$remaining'\n";
    echo $indent . "递归反转剩余部分...\n";
    
    $reversedRemaining = reverseStringVisual($remaining, $level + 1);
    
    $result = $reversedRemaining . $firstChar;
    echo $indent . "组合: '$reversedRemaining' + '$firstChar' = '$result'\n";
    
    return $result;
}

// 数组反转（递归版本）
function reverseArray($arr) {
    // 基础情况：空数组或单元素数组
    if (count($arr) <= 1) {
        return $arr;
    }
    
    // 取出第一个元素
    $first = array_shift($arr);
    
    // 递归反转剩余部分，然后将第一个元素放到最后
    $reversed = reverseArray($arr);
    array_push($reversed, $first);
    
    return $reversed;
}

// 使用索引的数组反转
function reverseArrayByIndex($arr, $start = 0, $end = null) {
    if ($end === null) {
        $end = count($arr) - 1;
    }
    
    // 基础情况：开始索引 >= 结束索引
    if ($start >= $end) {
        return $arr;
    }
    
    // 交换首尾元素
    $temp = $arr[$start];
    $arr[$start] = $arr[$end];
    $arr[$end] = $temp;
    
    // 递归处理中间部分
    return reverseArrayByIndex($arr, $start + 1, $end - 1);
}

// 回文检查（使用递归）
function isPalindrome($str) {
    // 移除空格和转换为小写
    $str = strtolower(str_replace(' ', '', $str));
    
    // 基础情况：空字符串或单字符是回文
    if (strlen($str) <= 1) {
        return true;
    }
    
    // 检查首尾字符是否相同
    if ($str[0] !== $str[strlen($str) - 1]) {
        return false;
    }
    
    // 递归检查中间部分
    return isPalindrome(substr($str, 1, -1));
}

// 测试和演示
echo "=== 字符串反转 ===\n\n";

$testStrings = ["Hello", "递归", "12345", "A"];
foreach ($testStrings as $str) {
    echo "原始: '$str' => 反转: '" . reverseString($str) . "'\n";
}

echo "\n=== 递归过程可视化 ===\n";
echo "反转 'HELLO' 的过程：\n\n";
reverseStringVisual("HELLO");

echo "\n=== 数组反转 ===\n";
$arr = [1, 2, 3, 4, 5];
echo "原始数组: [" . implode(", ", $arr) . "]\n";
echo "递归反转: [" . implode(", ", reverseArray($arr)) . "]\n";
echo "索引反转: [" . implode(", ", reverseArrayByIndex($arr)) . "]\n";

echo "\n=== 回文检查 ===\n";
$palindromes = [
    "racecar" => true,
    "A man a plan a canal Panama" => true,
    "hello" => false,
    "上海自来水来自海上" => true,
    "递归" => false
];

foreach ($palindromes as $str => $expected) {
    $result = isPalindrome($str);
    $status = $result ? "是" : "不是";
    $correct = $result === $expected ? "✓" : "✗";
    echo "$correct '$str' $status 回文\n";
}

echo "\n=== 性能考虑 ===\n";
$longString = str_repeat("a", 1000) . "b" . str_repeat("a", 1000);
$start = microtime(true);
$reversed = reverseString(substr($longString, 0, 100)); // 只测试前100个字符
$time = microtime(true) - $start;
echo "反转100个字符耗时: " . number_format($time * 1000, 4) . " ms\n";
echo "注意：对于很长的字符串，递归可能不是最佳选择\n";

echo "\n=== 思考题 ===\n";
echo "1. 字符串反转的递归调用深度和字符串长度有什么关系？\n";
echo "2. 为什么回文检查适合用递归实现？\n";
echo "3. 如何修改代码以支持 UTF-8 多字节字符？\n";
