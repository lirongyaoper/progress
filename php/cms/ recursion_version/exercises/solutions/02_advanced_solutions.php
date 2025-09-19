<?php
/**
 * 进阶练习题解答
 * 
 * 包含详细的解题思路和优化方案
 */

echo "=== PHP 递归进阶练习题 - 参考答案 ===\n\n";

/**
 * 练习 1：生成所有可能的括号组合
 */
function generateParentheses($n) {
    $result = [];
    backtrackParentheses($result, "", 0, 0, $n);
    return $result;
}

function backtrackParentheses(&$result, $current, $open, $close, $max) {
    // 基础情况：生成了 n 对括号
    if (strlen($current) === $max * 2) {
        $result[] = $current;
        return;
    }
    
    // 可以添加左括号的条件：还有剩余
    if ($open < $max) {
        backtrackParentheses($result, $current . "(", $open + 1, $close, $max);
    }
    
    // 可以添加右括号的条件：不超过左括号数量
    if ($close < $open) {
        backtrackParentheses($result, $current . ")", $open, $close + 1, $max);
    }
}

echo "练习 1 - 括号生成：\n";
echo "解题思路：\n";
echo "- 使用回溯法探索所有可能\n";
echo "- 约束条件：右括号数不能超过左括号数\n";
echo "- 递归参数：当前字符串、左括号数、右括号数\n\n";

$parentheses = generateParentheses(3);
echo "n=3 的所有括号组合：\n";
foreach ($parentheses as $p) {
    echo $p . "\n";
}
echo "共 " . count($parentheses) . " 种组合\n\n";

/**
 * 练习 2：解决数独谜题
 */
function solveSudoku(&$board) {
    return solveSudokuHelper($board);
}

function solveSudokuHelper(&$board) {
    // 找到下一个空格
    for ($row = 0; $row < 9; $row++) {
        for ($col = 0; $col < 9; $col++) {
            if ($board[$row][$col] === 0) {
                // 尝试填入 1-9
                for ($num = 1; $num <= 9; $num++) {
                    if (isValidSudoku($board, $row, $col, $num)) {
                        $board[$row][$col] = $num;
                        
                        // 递归求解
                        if (solveSudokuHelper($board)) {
                            return true;
                        }
                        
                        // 回溯
                        $board[$row][$col] = 0;
                    }
                }
                return false; // 无法填入任何数字
            }
        }
    }
    return true; // 所有格子都已填满
}

function isValidSudoku($board, $row, $col, $num) {
    // 检查行
    for ($x = 0; $x < 9; $x++) {
        if ($board[$row][$x] === $num) {
            return false;
        }
    }
    
    // 检查列
    for ($x = 0; $x < 9; $x++) {
        if ($board[$x][$col] === $num) {
            return false;
        }
    }
    
    // 检查 3x3 方格
    $startRow = floor($row / 3) * 3;
    $startCol = floor($col / 3) * 3;
    for ($i = 0; $i < 3; $i++) {
        for ($j = 0; $j < 3; $j++) {
            if ($board[$startRow + $i][$startCol + $j] === $num) {
                return false;
            }
        }
    }
    
    return true;
}

function printSudoku($board) {
    echo "+-------+-------+-------+\n";
    for ($i = 0; $i < 9; $i++) {
        if ($i % 3 === 0 && $i !== 0) {
            echo "+-------+-------+-------+\n";
        }
        for ($j = 0; $j < 9; $j++) {
            if ($j % 3 === 0) {
                echo "| ";
            }
            echo ($board[$i][$j] === 0 ? '.' : $board[$i][$j]) . " ";
        }
        echo "|\n";
    }
    echo "+-------+-------+-------+\n";
}

echo "练习 2 - 数独求解：\n";
echo "解题思路：\n";
echo "- 回溯法：尝试每个空格的所有可能\n";
echo "- 验证：检查行、列、3x3方格\n";
echo "- 优化：可以使用位运算或集合加速验证\n\n";

/**
 * 练习 3：找出所有子集
 */
function subsets($nums) {
    $result = [];
    backtrackSubsets($nums, 0, [], $result);
    return $result;
}

function backtrackSubsets($nums, $start, $current, &$result) {
    // 每个递归状态都是一个有效子集
    $result[] = $current;
    
    // 从 start 开始，避免重复
    for ($i = $start; $i < count($nums); $i++) {
        // 包含当前元素
        $current[] = $nums[$i];
        // 递归处理剩余元素
        backtrackSubsets($nums, $i + 1, $current, $result);
        // 回溯：移除当前元素
        array_pop($current);
    }
}

// 位运算方法
function subsetsBitwise($nums) {
    $n = count($nums);
    $result = [];
    
    // 2^n 个子集
    for ($i = 0; $i < (1 << $n); $i++) {
        $subset = [];
        for ($j = 0; $j < $n; $j++) {
            // 检查第 j 位是否为 1
            if ($i & (1 << $j)) {
                $subset[] = $nums[$j];
            }
        }
        $result[] = $subset;
    }
    
    return $result;
}

echo "练习 3 - 子集生成：\n";
echo "解题思路：\n";
echo "- 方法1：回溯法，每个元素选或不选\n";
echo "- 方法2：位运算，用二进制表示选择\n";
echo "- 时间复杂度：O(2^n)\n\n";

$subsets = subsets([1, 2, 3]);
echo "[1,2,3] 的所有子集：\n";
foreach ($subsets as $subset) {
    echo "[" . implode(",", $subset) . "] ";
}
echo "\n共 " . count($subsets) . " 个子集\n\n";

/**
 * 练习 4：找零钱问题
 */
function coinChange($coins, $amount) {
    $memo = array_fill(0, $amount + 1, -2); // -2 表示未计算
    return coinChangeHelper($coins, $amount, $memo);
}

function coinChangeHelper($coins, $amount, &$memo) {
    // 基础情况
    if ($amount === 0) return 0;
    if ($amount < 0) return -1;
    
    // 检查记忆化
    if ($memo[$amount] !== -2) {
        return $memo[$amount];
    }
    
    $minCoins = PHP_INT_MAX;
    
    // 尝试每种硬币
    foreach ($coins as $coin) {
        $res = coinChangeHelper($coins, $amount - $coin, $memo);
        if ($res >= 0 && $res < $minCoins) {
            $minCoins = $res + 1;
        }
    }
    
    // 记忆化结果
    $memo[$amount] = ($minCoins === PHP_INT_MAX) ? -1 : $minCoins;
    return $memo[$amount];
}

// 动态规划版本
function coinChangeDP($coins, $amount) {
    $dp = array_fill(0, $amount + 1, $amount + 1);
    $dp[0] = 0;
    
    for ($i = 1; $i <= $amount; $i++) {
        foreach ($coins as $coin) {
            if ($coin <= $i) {
                $dp[$i] = min($dp[$i], $dp[$i - $coin] + 1);
            }
        }
    }
    
    return $dp[$amount] > $amount ? -1 : $dp[$amount];
}

echo "练习 4 - 找零钱问题：\n";
echo "解题思路：\n";
echo "- 递归关系：minCoins(amount) = 1 + min(minCoins(amount-coin))\n";
echo "- 使用记忆化避免重复计算\n";
echo "- 可以转换为动态规划\n\n";

echo "coins=[1,2,5], amount=11: " . coinChange([1,2,5], 11) . " 个硬币\n";
echo "coins=[2], amount=3: " . coinChange([2], 3) . " (无解)\n\n";

/**
 * 练习 5：最长公共子序列
 */
function longestCommonSubsequence($str1, $str2) {
    $memo = [];
    return lcsHelper($str1, $str2, 0, 0, $memo);
}

function lcsHelper($str1, $str2, $i, $j, &$memo) {
    // 基础情况
    if ($i >= strlen($str1) || $j >= strlen($str2)) {
        return 0;
    }
    
    // 检查记忆化
    $key = $i . "," . $j;
    if (isset($memo[$key])) {
        return $memo[$key];
    }
    
    // 字符相同
    if ($str1[$i] === $str2[$j]) {
        $memo[$key] = 1 + lcsHelper($str1, $str2, $i + 1, $j + 1, $memo);
    } else {
        // 字符不同，尝试两种可能
        $memo[$key] = max(
            lcsHelper($str1, $str2, $i + 1, $j, $memo),
            lcsHelper($str1, $str2, $i, $j + 1, $memo)
        );
    }
    
    return $memo[$key];
}

// 动态规划版本
function lcsDP($str1, $str2) {
    $m = strlen($str1);
    $n = strlen($str2);
    $dp = array_fill(0, $m + 1, array_fill(0, $n + 1, 0));
    
    for ($i = 1; $i <= $m; $i++) {
        for ($j = 1; $j <= $n; $j++) {
            if ($str1[$i-1] === $str2[$j-1]) {
                $dp[$i][$j] = $dp[$i-1][$j-1] + 1;
            } else {
                $dp[$i][$j] = max($dp[$i-1][$j], $dp[$i][$j-1]);
            }
        }
    }
    
    return $dp[$m][$n];
}

echo "练习 5 - 最长公共子序列：\n";
echo "解题思路：\n";
echo "- 如果字符相同：LCS = 1 + LCS(剩余部分)\n";
echo "- 如果不同：LCS = max(跳过str1字符, 跳过str2字符)\n";
echo "- 使用记忆化或动态规划优化\n\n";

echo "LCS('ABCDGH', 'AEDFHR') = " . longestCommonSubsequence('ABCDGH', 'AEDFHR') . "\n\n";

/**
 * 练习 6：平衡二叉树检查
 */
class TreeNode {
    public $val;
    public $left;
    public $right;
    
    function __construct($val = 0, $left = null, $right = null) {
        $this->val = $val;
        $this->left = $left;
        $this->right = $right;
    }
}

function isBalanced($root) {
    return checkBalance($root) !== -1;
}

function checkBalance($node) {
    // 基础情况
    if ($node === null) {
        return 0;
    }
    
    // 检查左子树
    $leftHeight = checkBalance($node->left);
    if ($leftHeight === -1) {
        return -1; // 左子树不平衡
    }
    
    // 检查右子树
    $rightHeight = checkBalance($node->right);
    if ($rightHeight === -1) {
        return -1; // 右子树不平衡
    }
    
    // 检查当前节点
    if (abs($leftHeight - $rightHeight) > 1) {
        return -1; // 当前节点不平衡
    }
    
    // 返回高度
    return max($leftHeight, $rightHeight) + 1;
}

echo "练习 6 - 平衡二叉树：\n";
echo "解题思路：\n";
echo "- 递归计算高度，同时检查平衡性\n";
echo "- 使用 -1 表示不平衡，避免重复计算\n";
echo "- 一旦发现不平衡，立即返回\n\n";

/**
 * 练习 7：单词拆分
 */
function wordBreak($s, $wordDict) {
    $memo = [];
    $dictSet = array_flip($wordDict); // 转为集合便于查找
    return wordBreakHelper($s, $dictSet, 0, $memo);
}

function wordBreakHelper($s, $dictSet, $start, &$memo) {
    // 基础情况
    if ($start === strlen($s)) {
        return true;
    }
    
    // 检查记忆化
    if (isset($memo[$start])) {
        return $memo[$start];
    }
    
    // 尝试每个可能的结束位置
    for ($end = $start + 1; $end <= strlen($s); $end++) {
        $word = substr($s, $start, $end - $start);
        if (isset($dictSet[$word]) && wordBreakHelper($s, $dictSet, $end, $memo)) {
            $memo[$start] = true;
            return true;
        }
    }
    
    $memo[$start] = false;
    return false;
}

echo "练习 7 - 单词拆分：\n";
echo "解题思路：\n";
echo "- 递归尝试每个可能的拆分点\n";
echo "- 使用记忆化避免重复计算\n";
echo "- 可以用动态规划自底向上解决\n\n";

echo "wordBreak('leetcode', ['leet','code']) = " . 
     (wordBreak('leetcode', ['leet','code']) ? 'true' : 'false') . "\n\n";

/**
 * 练习 8：排列组合
 */
function permute($nums) {
    $result = [];
    permuteBacktrack($nums, 0, $result);
    return $result;
}

function permuteBacktrack(&$nums, $start, &$result) {
    // 基础情况：生成了一个完整排列
    if ($start === count($nums)) {
        $result[] = array_slice($nums, 0);
        return;
    }
    
    // 将每个元素放到 start 位置
    for ($i = $start; $i < count($nums); $i++) {
        // 交换
        $temp = $nums[$start];
        $nums[$start] = $nums[$i];
        $nums[$i] = $temp;
        
        // 递归处理剩余部分
        permuteBacktrack($nums, $start + 1, $result);
        
        // 回溯：恢复原状
        $temp = $nums[$start];
        $nums[$start] = $nums[$i];
        $nums[$i] = $temp;
    }
}

echo "练习 8 - 全排列：\n";
echo "解题思路：\n";
echo "- 回溯法：交换元素生成不同排列\n";
echo "- 每个位置尝试所有可能的元素\n";
echo "- 记得回溯恢复原状态\n\n";

$perms = permute([1, 2, 3]);
echo "[1,2,3] 的全排列：\n";
foreach ($perms as $perm) {
    echo "[" . implode(",", $perm) . "] ";
}
echo "\n共 " . count($perms) . " 种排列\n\n";

/**
 * 练习 9：迷宫最短路径
 */
function shortestPath($maze, $start, $end) {
    $rows = count($maze);
    $cols = count($maze[0]);
    $visited = array_fill(0, $rows, array_fill(0, $cols, false));
    $minPath = PHP_INT_MAX;
    
    dfsShortestPath($maze, $start[0], $start[1], $end[0], $end[1], 
                    $visited, 0, $minPath);
    
    return $minPath === PHP_INT_MAX ? -1 : $minPath;
}

function dfsShortestPath($maze, $x, $y, $endX, $endY, &$visited, $path, &$minPath) {
    // 到达终点
    if ($x === $endX && $y === $endY) {
        $minPath = min($minPath, $path);
        return;
    }
    
    // 剪枝：当前路径已经不是最优
    if ($path >= $minPath) {
        return;
    }
    
    $visited[$x][$y] = true;
    
    // 四个方向
    $directions = [[0,1], [1,0], [0,-1], [-1,0]];
    foreach ($directions as $dir) {
        $newX = $x + $dir[0];
        $newY = $y + $dir[1];
        
        if (isValidMove($maze, $newX, $newY, $visited)) {
            dfsShortestPath($maze, $newX, $newY, $endX, $endY, 
                          $visited, $path + 1, $minPath);
        }
    }
    
    $visited[$x][$y] = false; // 回溯
}

function isValidMove($maze, $x, $y, $visited) {
    $rows = count($maze);
    $cols = count($maze[0]);
    
    return $x >= 0 && $x < $rows && 
           $y >= 0 && $y < $cols && 
           $maze[$x][$y] === 0 && 
           !$visited[$x][$y];
}

echo "练习 9 - 迷宫最短路径：\n";
echo "解题思路：\n";
echo "- DFS + 回溯 + 剪枝\n";
echo "- 记录已访问避免循环\n";
echo "- BFS 通常更适合最短路径\n\n";

/**
 * 练习 10：表达式求值
 */
class ExpressionEvaluator {
    private $expression;
    private $index;
    
    public function evaluate($expression) {
        $this->expression = str_replace(' ', '', $expression);
        $this->index = 0;
        return $this->parseExpression();
    }
    
    private function parseExpression() {
        $result = $this->parseTerm();
        
        while ($this->index < strlen($this->expression)) {
            $op = $this->expression[$this->index];
            if ($op === '+' || $op === '-') {
                $this->index++;
                $right = $this->parseTerm();
                $result = ($op === '+') ? $result + $right : $result - $right;
            } else {
                break;
            }
        }
        
        return $result;
    }
    
    private function parseTerm() {
        $result = $this->parseFactor();
        
        while ($this->index < strlen($this->expression)) {
            $op = $this->expression[$this->index];
            if ($op === '*' || $op === '/') {
                $this->index++;
                $right = $this->parseFactor();
                $result = ($op === '*') ? $result * $right : $result / $right;
            } else {
                break;
            }
        }
        
        return $result;
    }
    
    private function parseFactor() {
        if ($this->expression[$this->index] === '(') {
            $this->index++; // 跳过 '('
            $result = $this->parseExpression();
            $this->index++; // 跳过 ')'
            return $result;
        }
        
        // 解析数字
        $num = 0;
        while ($this->index < strlen($this->expression) && 
               is_numeric($this->expression[$this->index])) {
            $num = $num * 10 + intval($this->expression[$this->index]);
            $this->index++;
        }
        
        return $num;
    }
}

function evaluateExpression($expression) {
    $evaluator = new ExpressionEvaluator();
    return $evaluator->evaluate($expression);
}

echo "练习 10 - 表达式求值：\n";
echo "解题思路：\n";
echo "- 递归下降解析\n";
echo "- 处理运算符优先级\n";
echo "- 递归处理括号\n\n";

echo "evaluateExpression('2*(3+4)') = " . evaluateExpression('2*(3+4)') . "\n";
echo "evaluateExpression('10+2*6') = " . evaluateExpression('10+2*6') . "\n\n";

echo "=== 学习总结 ===\n";
echo "1. 回溯法是解决组合问题的利器\n";
echo "2. 记忆化可以大幅提升递归性能\n";
echo "3. 递归解析是处理嵌套结构的自然方式\n";
echo "4. 剪枝可以减少不必要的递归调用\n";
echo "5. 某些递归问题可以转换为动态规划\n";
