<?php
/**
 * 进阶示例 5：回溯算法
 * 展示递归在解决约束满足问题中的应用
 * 
 * 包含内容：
 * 1. N皇后问题
 * 2. 数独求解
 * 3. 迷宫寻路
 * 4. 子集生成
 */

// ==================== N皇后问题 ====================
class NQueens {
    private $solutions;
    private $board;
    private $n;
    
    public function __construct($n) {
        $this->n = $n;
        $this->solutions = [];
        $this->board = array_fill(0, $n, array_fill(0, $n, 0));
    }
    
    // 求解N皇后
    public function solve() {
        $this->solutions = [];
        $this->placeQueens(0);
        return $this->solutions;
    }
    
    // 递归放置皇后
    private function placeQueens($row) {
        // 基础情况：所有皇后都已放置
        if ($row === $this->n) {
            // 保存当前解
            $this->solutions[] = $this->copyBoard();
            return;
        }
        
        // 尝试在当前行的每一列放置皇后
        for ($col = 0; $col < $this->n; $col++) {
            if ($this->isSafe($row, $col)) {
                // 放置皇后
                $this->board[$row][$col] = 1;
                
                // 递归处理下一行
                $this->placeQueens($row + 1);
                
                // 回溯：移除皇后
                $this->board[$row][$col] = 0;
            }
        }
    }
    
    // 检查位置是否安全
    private function isSafe($row, $col) {
        // 检查列
        for ($i = 0; $i < $row; $i++) {
            if ($this->board[$i][$col] === 1) {
                return false;
            }
        }
        
        // 检查左对角线
        for ($i = $row - 1, $j = $col - 1; $i >= 0 && $j >= 0; $i--, $j--) {
            if ($this->board[$i][$j] === 1) {
                return false;
            }
        }
        
        // 检查右对角线
        for ($i = $row - 1, $j = $col + 1; $i >= 0 && $j < $this->n; $i--, $j++) {
            if ($this->board[$i][$j] === 1) {
                return false;
            }
        }
        
        return true;
    }
    
    // 复制棋盘
    private function copyBoard() {
        $copy = [];
        for ($i = 0; $i < $this->n; $i++) {
            $copy[] = array_slice($this->board[$i], 0);
        }
        return $copy;
    }
    
    // 打印棋盘
    public static function printBoard($board) {
        $n = count($board);
        echo "+";
        for ($i = 0; $i < $n; $i++) {
            echo "--+";
        }
        echo "\n";
        
        for ($i = 0; $i < $n; $i++) {
            echo "|";
            for ($j = 0; $j < $n; $j++) {
                echo ($board[$i][$j] === 1 ? "Q " : ". ") . "|";
            }
            echo "\n+";
            for ($j = 0; $j < $n; $j++) {
                echo "--+";
            }
            echo "\n";
        }
    }
}

// ==================== 数独求解器 ====================
class SudokuSolver {
    private $board;
    private $size = 9;
    private $boxSize = 3;
    
    public function __construct($board) {
        $this->board = $board;
    }
    
    // 求解数独
    public function solve() {
        if ($this->solveRecursive()) {
            return $this->board;
        }
        return null;
    }
    
    // 递归求解
    private function solveRecursive() {
        // 找到下一个空格
        $empty = $this->findEmpty();
        if ($empty === null) {
            return true; // 所有格子都已填满
        }
        
        list($row, $col) = $empty;
        
        // 尝试填入1-9
        for ($num = 1; $num <= 9; $num++) {
            if ($this->isValid($row, $col, $num)) {
                // 填入数字
                $this->board[$row][$col] = $num;
                
                // 递归求解剩余部分
                if ($this->solveRecursive()) {
                    return true;
                }
                
                // 回溯：清空格子
                $this->board[$row][$col] = 0;
            }
        }
        
        return false; // 无解
    }
    
    // 查找空格
    private function findEmpty() {
        for ($i = 0; $i < $this->size; $i++) {
            for ($j = 0; $j < $this->size; $j++) {
                if ($this->board[$i][$j] === 0) {
                    return [$i, $j];
                }
            }
        }
        return null;
    }
    
    // 检查数字是否有效
    private function isValid($row, $col, $num) {
        // 检查行
        for ($j = 0; $j < $this->size; $j++) {
            if ($this->board[$row][$j] === $num) {
                return false;
            }
        }
        
        // 检查列
        for ($i = 0; $i < $this->size; $i++) {
            if ($this->board[$i][$col] === $num) {
                return false;
            }
        }
        
        // 检查3x3方格
        $boxRow = floor($row / $this->boxSize) * $this->boxSize;
        $boxCol = floor($col / $this->boxSize) * $this->boxSize;
        
        for ($i = 0; $i < $this->boxSize; $i++) {
            for ($j = 0; $j < $this->boxSize; $j++) {
                if ($this->board[$boxRow + $i][$boxCol + $j] === $num) {
                    return false;
                }
            }
        }
        
        return true;
    }
    
    // 打印数独
    public static function printSudoku($board) {
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
}

// ==================== 迷宫寻路 ====================
class MazeSolver {
    private $maze;
    private $rows;
    private $cols;
    private $path;
    private $visited;
    
    // 方向：上、右、下、左
    private $directions = [
        [-1, 0], [0, 1], [1, 0], [0, -1]
    ];
    
    public function __construct($maze) {
        $this->maze = $maze;
        $this->rows = count($maze);
        $this->cols = count($maze[0]);
        $this->path = [];
        $this->visited = array_fill(0, $this->rows, array_fill(0, $this->cols, false));
    }
    
    // 求解迷宫
    public function solve($startRow = 0, $startCol = 0, $endRow = null, $endCol = null) {
        if ($endRow === null) $endRow = $this->rows - 1;
        if ($endCol === null) $endCol = $this->cols - 1;
        
        $this->path = [];
        $this->visited = array_fill(0, $this->rows, array_fill(0, $this->cols, false));
        
        if ($this->findPath($startRow, $startCol, $endRow, $endCol)) {
            return $this->path;
        }
        
        return null;
    }
    
    // 递归寻找路径
    private function findPath($row, $col, $endRow, $endCol) {
        // 基础情况：到达终点
        if ($row === $endRow && $col === $endCol) {
            $this->path[] = [$row, $col];
            return true;
        }
        
        // 标记当前位置为已访问
        $this->visited[$row][$col] = true;
        $this->path[] = [$row, $col];
        
        // 尝试四个方向
        foreach ($this->directions as $dir) {
            $newRow = $row + $dir[0];
            $newCol = $col + $dir[1];
            
            // 检查新位置是否有效
            if ($this->isValid($newRow, $newCol)) {
                if ($this->findPath($newRow, $newCol, $endRow, $endCol)) {
                    return true;
                }
            }
        }
        
        // 回溯：从路径中移除当前位置
        array_pop($this->path);
        return false;
    }
    
    // 检查位置是否有效
    private function isValid($row, $col) {
        return $row >= 0 && $row < $this->rows &&
               $col >= 0 && $col < $this->cols &&
               $this->maze[$row][$col] === 0 &&
               !$this->visited[$row][$col];
    }
    
    // 打印迷宫和路径
    public function printMazeWithPath($path = null) {
        if ($path === null) {
            $path = $this->path;
        }
        
        // 创建路径映射
        $pathMap = [];
        foreach ($path as $i => $pos) {
            $pathMap[$pos[0] . ',' . $pos[1]] = $i + 1;
        }
        
        for ($i = 0; $i < $this->rows; $i++) {
            for ($j = 0; $j < $this->cols; $j++) {
                $key = $i . ',' . $j;
                if (isset($pathMap[$key])) {
                    echo str_pad($pathMap[$key], 2, ' ', STR_PAD_LEFT) . " ";
                } elseif ($this->maze[$i][$j] === 1) {
                    echo "## ";
                } else {
                    echo ".. ";
                }
            }
            echo "\n";
        }
    }
}

// ==================== 子集生成 ====================
class SubsetGenerator {
    // 生成所有子集
    public static function generateSubsets($arr) {
        $subsets = [];
        self::backtrack($arr, 0, [], $subsets);
        return $subsets;
    }
    
    // 递归回溯生成子集
    private static function backtrack($arr, $start, $current, &$subsets) {
        // 添加当前子集
        $subsets[] = array_slice($current, 0);
        
        // 尝试添加剩余元素
        for ($i = $start; $i < count($arr); $i++) {
            // 添加元素
            $current[] = $arr[$i];
            
            // 递归生成包含该元素的子集
            self::backtrack($arr, $i + 1, $current, $subsets);
            
            // 回溯：移除元素
            array_pop($current);
        }
    }
    
    // 生成指定大小的组合
    public static function generateCombinations($arr, $k) {
        $combinations = [];
        self::backtrackCombinations($arr, 0, [], $combinations, $k);
        return $combinations;
    }
    
    private static function backtrackCombinations($arr, $start, $current, &$combinations, $k) {
        // 找到一个有效组合
        if (count($current) === $k) {
            $combinations[] = array_slice($current, 0);
            return;
        }
        
        // 剩余元素不足
        if ($start >= count($arr)) {
            return;
        }
        
        // 尝试添加剩余元素
        for ($i = $start; $i < count($arr); $i++) {
            $current[] = $arr[$i];
            self::backtrackCombinations($arr, $i + 1, $current, $combinations, $k);
            array_pop($current);
        }
    }
}

// 测试和演示
echo "=== N皇后问题 ===\n\n";

// 4皇后问题
$nQueens = new NQueens(4);
$solutions = $nQueens->solve();
echo "4皇后问题有 " . count($solutions) . " 个解：\n\n";

foreach ($solutions as $i => $solution) {
    echo "解 " . ($i + 1) . ":\n";
    NQueens::printBoard($solution);
    echo "\n";
}

// 8皇后问题统计
$nQueens8 = new NQueens(8);
$solutions8 = $nQueens8->solve();
echo "8皇后问题有 " . count($solutions8) . " 个解\n\n";

echo "=== 数独求解 ===\n\n";

// 示例数独题目
$sudoku = [
    [5,3,0,0,7,0,0,0,0],
    [6,0,0,1,9,5,0,0,0],
    [0,9,8,0,0,0,0,6,0],
    [8,0,0,0,6,0,0,0,3],
    [4,0,0,8,0,3,0,0,1],
    [7,0,0,0,2,0,0,0,6],
    [0,6,0,0,0,0,2,8,0],
    [0,0,0,4,1,9,0,0,5],
    [0,0,0,0,8,0,0,7,9]
];

echo "数独题目：\n";
SudokuSolver::printSudoku($sudoku);

$solver = new SudokuSolver($sudoku);
$solution = $solver->solve();

echo "\n数独解答：\n";
if ($solution) {
    SudokuSolver::printSudoku($solution);
} else {
    echo "无解！\n";
}

echo "\n=== 迷宫寻路 ===\n\n";

// 创建迷宫（0=通路，1=墙）
$maze = [
    [0, 1, 0, 0, 0],
    [0, 1, 0, 1, 0],
    [0, 0, 0, 1, 0],
    [1, 1, 0, 0, 0],
    [0, 0, 0, 1, 0]
];

echo "迷宫（##=墙，..=通路）：\n";
$mazeSolver = new MazeSolver($maze);
$mazeSolver->printMazeWithPath([]);

$path = $mazeSolver->solve(0, 0, 4, 4);
echo "\n找到路径（数字表示步骤）：\n";
if ($path) {
    $mazeSolver->printMazeWithPath($path);
    echo "\n路径坐标：\n";
    foreach ($path as $i => $pos) {
        echo "步骤 " . ($i + 1) . ": ({$pos[0]}, {$pos[1]})\n";
    }
} else {
    echo "无解！\n";
}

echo "\n=== 子集生成 ===\n\n";

$set = [1, 2, 3];
echo "集合: [" . implode(", ", $set) . "]\n";
echo "所有子集：\n";
$subsets = SubsetGenerator::generateSubsets($set);
foreach ($subsets as $subset) {
    echo "[" . implode(", ", $subset) . "]\n";
}

echo "\n从 [1,2,3,4] 中选择 2 个元素的组合：\n";
$combinations = SubsetGenerator::generateCombinations([1, 2, 3, 4], 2);
foreach ($combinations as $combo) {
    echo "[" . implode(", ", $combo) . "]\n";
}

echo "\n=== 回溯算法总结 ===\n";
echo "1. 核心思想：尝试-验证-回退\n";
echo "2. 适用场景：寻找所有解、满足约束的解\n";
echo "3. 时间复杂度：通常是指数级\n";
echo "4. 优化技巧：剪枝、记忆化、启发式搜索\n";
