<?php
/**
 * 基础练习题解答
 * 
 * 这里包含了所有基础练习题的参考答案和详细解释
 */

echo "=== PHP 递归基础练习题 - 参考答案 ===\n\n";

/**
 * 练习 1：计算数字的位数
 */
function countDigits($n) {
    // 处理 0 的特殊情况
    if ($n == 0) {
        return 1;
    }
    
    // 转为正数处理
    $n = abs($n);
    
    // 基础情况：小于 10 的数字只有 1 位
    if ($n < 10) {
        return 1;
    }
    
    // 递归情况：去掉最后一位（除以10），位数加1
    return 1 + countDigits(floor($n / 10));
}

echo "练习 1 - 计算数字位数：\n";
echo "解题思路：\n";
echo "- 基础情况：n < 10 时返回 1\n";
echo "- 递归关系：digits(n) = 1 + digits(n/10)\n";
echo "- 每次除以 10 去掉一位数字\n\n";

echo "测试结果：\n";
echo "countDigits(12345) = " . countDigits(12345) . "\n";
echo "countDigits(0) = " . countDigits(0) . "\n";
echo "countDigits(9) = " . countDigits(9) . "\n";
echo "countDigits(-123) = " . countDigits(-123) . "\n\n";

/**
 * 练习 2：计算数组中的最大值
 */
function findMax($arr) {
    // 基础情况：空数组
    if (empty($arr)) {
        return null;
    }
    
    // 基础情况：只有一个元素
    if (count($arr) === 1) {
        return $arr[0];
    }
    
    // 获取第一个元素
    $first = $arr[0];
    
    // 递归找出剩余部分的最大值
    $restMax = findMax(array_slice($arr, 1));
    
    // 返回第一个元素和剩余部分最大值中的较大者
    return $first > $restMax ? $first : $restMax;
}

// 另一种实现方式（使用索引）
function findMaxWithIndex($arr, $index = 0) {
    // 基础情况：到达数组末尾
    if ($index === count($arr) - 1) {
        return $arr[$index];
    }
    
    // 递归获取剩余部分的最大值
    $maxOfRest = findMaxWithIndex($arr, $index + 1);
    
    // 返回当前元素和剩余部分最大值中的较大者
    return $arr[$index] > $maxOfRest ? $arr[$index] : $maxOfRest;
}

echo "练习 2 - 查找最大值：\n";
echo "解题思路：\n";
echo "- 基础情况：数组只有一个元素时，该元素就是最大值\n";
echo "- 递归关系：max(arr) = max(arr[0], max(arr[1:]))\n";
echo "- 比较第一个元素和剩余部分的最大值\n\n";

echo "测试结果：\n";
$testArray = [3, 7, 2, 9, 1, 5];
echo "findMax([3,7,2,9,1,5]) = " . findMax($testArray) . "\n";
echo "findMaxWithIndex([3,7,2,9,1,5]) = " . findMaxWithIndex($testArray) . "\n\n";

/**
 * 练习 3：判断回文字符串
 */
function isPalindrome($str) {
    // 去除空格并转为小写（可选）
    $str = str_replace(' ', '', strtolower($str));
    
    $len = strlen($str);
    
    // 基础情况：空串或单字符是回文
    if ($len <= 1) {
        return true;
    }
    
    // 检查首尾字符是否相同
    if ($str[0] !== $str[$len - 1]) {
        return false;
    }
    
    // 递归检查去掉首尾的子串
    return isPalindrome(substr($str, 1, -1));
}

echo "练习 3 - 判断回文：\n";
echo "解题思路：\n";
echo "- 基础情况：长度 <= 1 的字符串是回文\n";
echo "- 递归关系：首尾相同 && 中间部分是回文\n";
echo "- 每次递归去掉首尾两个字符\n\n";

echo "测试结果：\n";
echo "isPalindrome('racecar') = " . (isPalindrome('racecar') ? 'true' : 'false') . "\n";
echo "isPalindrome('hello') = " . (isPalindrome('hello') ? 'true' : 'false') . "\n";
echo "isPalindrome('A man a plan a canal Panama') = " . 
     (isPalindrome('A man a plan a canal Panama') ? 'true' : 'false') . "\n\n";

/**
 * 练习 4：二进制转换
 */
function toBinary($n) {
    // 基础情况
    if ($n === 0) {
        return "0";
    }
    if ($n === 1) {
        return "1";
    }
    
    // 递归情况：n/2 的二进制表示 + n%2
    return toBinary(floor($n / 2)) . ($n % 2);
}

// 处理负数的版本
function toBinaryComplete($n) {
    if ($n === 0) return "0";
    
    $sign = "";
    if ($n < 0) {
        $sign = "-";
        $n = -$n;
    }
    
    return $sign . toBinaryHelper($n);
}

function toBinaryHelper($n) {
    if ($n === 0) return "";
    if ($n === 1) return "1";
    
    return toBinaryHelper(floor($n / 2)) . ($n % 2);
}

echo "练习 4 - 二进制转换：\n";
echo "解题思路：\n";
echo "- 基础情况：0 返回 '0'，1 返回 '1'\n";
echo "- 递归关系：binary(n) = binary(n/2) + (n%2)\n";
echo "- 不断除以 2，收集余数\n\n";

echo "测试结果：\n";
echo "toBinary(10) = " . toBinary(10) . "\n";
echo "toBinary(7) = " . toBinary(7) . "\n";
echo "toBinary(0) = " . toBinary(0) . "\n";
echo "toBinary(255) = " . toBinary(255) . "\n\n";

/**
 * 练习 5：数组扁平化
 */
function flattenArray($arr) {
    $result = [];
    
    foreach ($arr as $element) {
        if (is_array($element)) {
            // 递归处理子数组，合并结果
            $result = array_merge($result, flattenArray($element));
        } else {
            // 非数组元素直接加入结果
            $result[] = $element;
        }
    }
    
    return $result;
}

// 另一种实现（使用 array_reduce）
function flattenArrayReduce($arr) {
    return array_reduce($arr, function($carry, $item) {
        if (is_array($item)) {
            return array_merge($carry, flattenArrayReduce($item));
        }
        $carry[] = $item;
        return $carry;
    }, []);
}

echo "练习 5 - 数组扁平化：\n";
echo "解题思路：\n";
echo "- 遍历数组的每个元素\n";
echo "- 如果是数组，递归扁平化后合并\n";
echo "- 如果不是数组，直接加入结果\n\n";

echo "测试结果：\n";
$nestedArray = [1, [2, 3], [4, [5, 6]], 7, [8, [9, [10]]]];
echo "原始数组：";
print_r($nestedArray);
echo "扁平化后：";
print_r(flattenArray($nestedArray));
echo "\n";

/**
 * 练习 6：计算 GCD（最大公约数）
 */
function gcd($a, $b) {
    // 确保 a >= b
    if ($b > $a) {
        return gcd($b, $a);
    }
    
    // 基础情况：b 为 0，GCD 是 a
    if ($b === 0) {
        return $a;
    }
    
    // 递归情况：GCD(a, b) = GCD(b, a % b)
    return gcd($b, $a % $b);
}

// 简化版本
function gcdSimple($a, $b) {
    return $b === 0 ? $a : gcdSimple($b, $a % $b);
}

echo "练习 6 - 最大公约数：\n";
echo "解题思路（欧几里得算法）：\n";
echo "- 基础情况：b = 0 时，GCD = a\n";
echo "- 递归关系：GCD(a,b) = GCD(b, a%b)\n";
echo "- 这是一个尾递归，可以优化为循环\n\n";

echo "测试结果：\n";
echo "gcd(48, 18) = " . gcd(48, 18) . "\n";
echo "gcd(100, 35) = " . gcd(100, 35) . "\n";
echo "gcd(17, 19) = " . gcd(17, 19) . " (互质数)\n\n";

/**
 * 练习 7：生成帕斯卡三角形的某一行
 */
function pascalRow($n) {
    // 基础情况
    if ($n === 0) {
        return [1];
    }
    
    // 获取上一行
    $prevRow = pascalRow($n - 1);
    
    // 构建当前行
    $currentRow = [1]; // 开头总是 1
    
    // 中间的元素是上一行相邻元素的和
    for ($i = 0; $i < count($prevRow) - 1; $i++) {
        $currentRow[] = $prevRow[$i] + $prevRow[$i + 1];
    }
    
    $currentRow[] = 1; // 结尾总是 1
    
    return $currentRow;
}

// 使用组合数公式的版本
function pascalRowFormula($n) {
    $row = [];
    for ($k = 0; $k <= $n; $k++) {
        $row[] = combination($n, $k);
    }
    return $row;
}

function combination($n, $k) {
    if ($k === 0 || $k === $n) return 1;
    return combination($n - 1, $k - 1) + combination($n - 1, $k);
}

echo "练习 7 - 帕斯卡三角形：\n";
echo "解题思路：\n";
echo "- 基础情况：第 0 行是 [1]\n";
echo "- 递归关系：每行基于上一行生成\n";
echo "- 规律：row[i] = prevRow[i-1] + prevRow[i]\n\n";

echo "测试结果：\n";
for ($i = 0; $i <= 5; $i++) {
    echo "第 $i 行：[" . implode(", ", pascalRow($i)) . "]\n";
}
echo "\n";

/**
 * 练习 8：深度复制对象
 */
function deepCopy($data) {
    // 处理 null
    if ($data === null) {
        return null;
    }
    
    // 处理数组
    if (is_array($data)) {
        $copy = [];
        foreach ($data as $key => $value) {
            $copy[$key] = deepCopy($value);
        }
        return $copy;
    }
    
    // 处理对象
    if (is_object($data)) {
        $copy = clone $data;
        foreach ($data as $key => $value) {
            $copy->$key = deepCopy($value);
        }
        return $copy;
    }
    
    // 其他类型（标量值）直接返回
    return $data;
}

echo "练习 8 - 深度复制：\n";
echo "解题思路：\n";
echo "- 判断数据类型\n";
echo "- 数组：创建新数组，递归复制每个元素\n";
echo "- 对象：克隆对象，递归复制每个属性\n";
echo "- 标量值：直接返回\n\n";

echo "测试结果：\n";
$original = [
    'name' => 'John',
    'scores' => [90, 85, 92],
    'info' => (object)['age' => 25, 'city' => 'NYC']
];

$copy = deepCopy($original);
$copy['scores'][0] = 100;
$copy['info']->age = 30;

echo "原始数据：\n";
print_r($original);
echo "修改副本后的原始数据（应该保持不变）：\n";
print_r($original);
echo "\n";

/**
 * 练习 9：统计二叉树的节点数
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
    // 基础情况：空树有 0 个节点
    if ($root === null) {
        return 0;
    }
    
    // 递归情况：1（当前节点）+ 左子树节点数 + 右子树节点数
    return 1 + countNodes($root->left) + countNodes($root->right);
}

echo "练习 9 - 统计二叉树节点：\n";
echo "解题思路：\n";
echo "- 基础情况：null 节点返回 0\n";
echo "- 递归关系：count = 1 + count(left) + count(right)\n";
echo "- 每个节点贡献 1，加上其子树的节点数\n\n";

echo "测试结果：\n";
$root = new TreeNode(1);
$root->left = new TreeNode(2);
$root->right = new TreeNode(3);
$root->left->left = new TreeNode(4);
$root->left->right = new TreeNode(5);
echo "二叉树节点数 = " . countNodes($root) . "\n";
echo "空树节点数 = " . countNodes(null) . "\n\n";

/**
 * 练习 10：实现 range 函数
 */
function recursiveRange($start, $end) {
    // 基础情况：start > end
    if ($start > $end) {
        return [];
    }
    
    // 基础情况：start == end
    if ($start === $end) {
        return [$start];
    }
    
    // 递归情况：当前元素 + 剩余范围
    return array_merge([$start], recursiveRange($start + 1, $end));
}

// 尾递归版本
function recursiveRangeTail($start, $end, $acc = []) {
    if ($start > $end) {
        return $acc;
    }
    
    $acc[] = $start;
    return recursiveRangeTail($start + 1, $end, $acc);
}

echo "练习 10 - 递归 range：\n";
echo "解题思路：\n";
echo "- 基础情况：start > end 返回空数组\n";
echo "- 递归关系：[start] + range(start+1, end)\n";
echo "- 每次递归增加 start，直到超过 end\n\n";

echo "测试结果：\n";
echo "recursiveRange(1, 5) = [" . implode(", ", recursiveRange(1, 5)) . "]\n";
echo "recursiveRange(3, 3) = [" . implode(", ", recursiveRange(3, 3)) . "]\n";
echo "recursiveRange(5, 3) = [" . implode(", ", recursiveRange(5, 3)) . "] (空数组)\n\n";

echo "=== 学习总结 ===\n";
echo "1. 递归的关键是找到基础情况和递归关系\n";
echo "2. 基础情况防止无限递归\n";
echo "3. 递归关系将问题分解为更小的子问题\n";
echo "4. 每次递归调用都应该向基础情况靠近\n";
echo "5. 注意处理边界情况（空值、负数等）\n";
echo "6. 某些递归可以优化为尾递归或迭代\n";
