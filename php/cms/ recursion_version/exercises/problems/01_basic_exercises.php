<?php
/**
 * 基础练习题集
 * 
 * 这些练习题帮助你掌握递归的基本概念和技巧
 * 请先尝试自己解决，然后查看 solutions 目录中的答案
 */

echo "=== PHP 递归基础练习题 ===\n\n";

/**
 * 练习 1：计算数字的位数
 * 
 * 编写一个递归函数，计算一个正整数有多少位数字
 * 例如：countDigits(12345) 应该返回 5
 * 
 * 提示：
 * - 基础情况：当数字小于 10 时，只有 1 位
 * - 递归情况：去掉最后一位，位数加 1
 */
function countDigits($n) {
    // TODO: 实现这个函数
    // 你的代码写在这里
}

// 测试用例
echo "练习 1 测试：\n";
// echo "countDigits(12345) = " . countDigits(12345) . " (应该是 5)\n";
// echo "countDigits(0) = " . countDigits(0) . " (应该是 1)\n";
// echo "countDigits(9) = " . countDigits(9) . " (应该是 1)\n\n";

/**
 * 练习 2：计算数组中的最大值
 * 
 * 使用递归找出数组中的最大值
 * 不要使用 PHP 的 max() 函数
 * 
 * 提示：
 * - 基础情况：数组只有一个元素
 * - 递归情况：比较第一个元素和剩余部分的最大值
 */
function findMax($arr) {
    // TODO: 实现这个函数
    // 你的代码写在这里
}

// 测试用例
echo "练习 2 测试：\n";
$testArray = [3, 7, 2, 9, 1, 5];
// echo "findMax([3,7,2,9,1,5]) = " . findMax($testArray) . " (应该是 9)\n\n";

/**
 * 练习 3：判断回文字符串
 * 
 * 使用递归判断一个字符串是否是回文
 * 回文：正着读和反着读都一样的字符串
 * 
 * 提示：
 * - 基础情况：空字符串或单个字符是回文
 * - 递归情况：首尾字符相同，且去掉首尾后的子串也是回文
 */
function isPalindrome($str) {
    // TODO: 实现这个函数
    // 你的代码写在这里
}

// 测试用例
echo "练习 3 测试：\n";
// echo "isPalindrome('racecar') = " . (isPalindrome('racecar') ? 'true' : 'false') . " (应该是 true)\n";
// echo "isPalindrome('hello') = " . (isPalindrome('hello') ? 'true' : 'false') . " (应该是 false)\n";
// echo "isPalindrome('a') = " . (isPalindrome('a') ? 'true' : 'false') . " (应该是 true)\n\n";

/**
 * 练习 4：二进制转换
 * 
 * 编写递归函数将十进制数转换为二进制字符串
 * 例如：toBinary(10) 应该返回 "1010"
 * 
 * 提示：
 * - 基础情况：当数字为 0 时返回 "0"，为 1 时返回 "1"
 * - 递归情况：当前数字除以 2 的结果递归处理，加上余数
 */
function toBinary($n) {
    // TODO: 实现这个函数
    // 你的代码写在这里
}

// 测试用例
echo "练习 4 测试：\n";
// echo "toBinary(10) = " . toBinary(10) . " (应该是 1010)\n";
// echo "toBinary(7) = " . toBinary(7) . " (应该是 111)\n";
// echo "toBinary(0) = " . toBinary(0) . " (应该是 0)\n\n";

/**
 * 练习 5：数组扁平化
 * 
 * 将嵌套数组扁平化为一维数组
 * 例如：[1, [2, 3], [4, [5, 6]]] 变成 [1, 2, 3, 4, 5, 6]
 * 
 * 提示：
 * - 遍历数组的每个元素
 * - 如果元素是数组，递归处理
 * - 如果不是数组，直接加入结果
 */
function flattenArray($arr) {
    // TODO: 实现这个函数
    // 你的代码写在这里
}

// 测试用例
echo "练习 5 测试：\n";
$nestedArray = [1, [2, 3], [4, [5, 6]]];
// echo "flattenArray([1,[2,3],[4,[5,6]]]) = ";
// print_r(flattenArray($nestedArray));
// echo " (应该是 [1,2,3,4,5,6])\n\n";

/**
 * 练习 6：计算 GCD（最大公约数）
 * 
 * 使用欧几里得算法递归计算两个数的最大公约数
 * 
 * 提示：
 * - 基础情况：当 b 为 0 时，GCD 是 a
 * - 递归情况：GCD(a, b) = GCD(b, a % b)
 */
function gcd($a, $b) {
    // TODO: 实现这个函数
    // 你的代码写在这里
}

// 测试用例
echo "练习 6 测试：\n";
// echo "gcd(48, 18) = " . gcd(48, 18) . " (应该是 6)\n";
// echo "gcd(100, 35) = " . gcd(100, 35) . " (应该是 5)\n\n";

/**
 * 练习 7：生成帕斯卡三角形的某一行
 * 
 * 帕斯卡三角形（杨辉三角）的规律：
 * 第 0 行：[1]
 * 第 1 行：[1, 1]
 * 第 2 行：[1, 2, 1]
 * 第 3 行：[1, 3, 3, 1]
 * 
 * 提示：
 * - 每一行的开头和结尾都是 1
 * - 中间的数字是上一行相邻两个数字的和
 */
function pascalRow($n) {
    // TODO: 实现这个函数
    // 你的代码写在这里
}

// 测试用例
echo "练习 7 测试：\n";
// echo "pascalRow(0) = [" . implode(", ", pascalRow(0)) . "] (应该是 [1])\n";
// echo "pascalRow(3) = [" . implode(", ", pascalRow(3)) . "] (应该是 [1,3,3,1])\n\n";

/**
 * 练习 8：深度复制对象
 * 
 * 递归地复制一个包含嵌套数组和对象的数据结构
 * 确保是深度复制，而不是引用复制
 * 
 * 提示：
 * - 如果是数组，递归复制每个元素
 * - 如果是对象，创建新对象并复制属性
 * - 其他类型直接返回
 */
function deepCopy($data) {
    // TODO: 实现这个函数
    // 你的代码写在这里
}

// 测试用例
echo "练习 8 测试：\n";
$testData = [
    'name' => 'John',
    'scores' => [90, 85, 92],
    'info' => (object)['age' => 25, 'city' => 'NYC']
];
// $copy = deepCopy($testData);
// $copy['scores'][0] = 100;
// echo "修改副本后，原数据的 scores[0] = " . $testData['scores'][0] . " (应该还是 90)\n\n";

/**
 * 练习 9：统计二叉树的节点数
 * 
 * 给定一个二叉树，递归计算总节点数
 * 
 * 树节点定义：
 * class TreeNode {
 *     public $value;
 *     public $left;
 *     public $right;
 * }
 */
class TreeNode {
    public $value;
    public $left;
    public $right;
    
    public function __construct($value) {
        $this->value = $value;
        $this->left = null;
        $this->right = null;
    }
}

function countNodes($root) {
    // TODO: 实现这个函数
    // 你的代码写在这里
}

// 测试用例
echo "练习 9 测试：\n";
$root = new TreeNode(1);
$root->left = new TreeNode(2);
$root->right = new TreeNode(3);
$root->left->left = new TreeNode(4);
$root->left->right = new TreeNode(5);
// echo "二叉树节点数 = " . countNodes($root) . " (应该是 5)\n\n";

/**
 * 练习 10：实现 range 函数
 * 
 * 递归实现一个类似 PHP range() 的函数
 * 生成从 start 到 end 的数字数组
 * 
 * 提示：
 * - 基础情况：start > end 时返回空数组
 * - 递归情况：[start] + recursiveRange(start+1, end)
 */
function recursiveRange($start, $end) {
    // TODO: 实现这个函数
    // 你的代码写在这里
}

// 测试用例
echo "练习 10 测试：\n";
// echo "recursiveRange(1, 5) = [" . implode(", ", recursiveRange(1, 5)) . "] (应该是 [1,2,3,4,5])\n";
// echo "recursiveRange(3, 3) = [" . implode(", ", recursiveRange(3, 3)) . "] (应该是 [3])\n\n";

echo "=== 练习提示 ===\n";
echo "1. 先理解每个问题的递归结构\n";
echo "2. 找出基础情况（递归终止条件）\n";
echo "3. 确定递归关系（如何将问题分解）\n";
echo "4. 写出递归调用\n";
echo "5. 测试边界情况\n\n";

echo "完成后，查看 solutions/01_basic_solutions.php 对比答案！\n";
