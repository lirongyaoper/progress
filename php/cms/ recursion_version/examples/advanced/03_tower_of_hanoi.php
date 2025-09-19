<?php
/**
 * 进阶示例 3：汉诺塔问题
 * 展示递归在解决经典数学问题中的应用
 * 
 * 汉诺塔规则：
 * 1. 一次只能移动一个盘子
 * 2. 大盘子不能放在小盘子上面
 * 3. 只能通过三个柱子来移动
 * 
 * 递归解法：
 * 要将 n 个盘子从 A 移到 C：
 * 1. 先将 n-1 个盘子从 A 移到 B（借助 C）
 * 2. 将最大的盘子从 A 移到 C
 * 3. 将 n-1 个盘子从 B 移到 C（借助 A）
 */

// 基本汉诺塔实现
function hanoi($n, $from = 'A', $to = 'C', $aux = 'B') {
    // 基础情况：只有一个盘子
    if ($n === 1) {
        echo "移动盘子 1 从 $from 到 $to\n";
        return;
    }
    
    // 步骤 1：将 n-1 个盘子从起始柱移到辅助柱
    hanoi($n - 1, $from, $aux, $to);
    
    // 步骤 2：将最大的盘子从起始柱移到目标柱
    echo "移动盘子 $n 从 $from 到 $to\n";
    
    // 步骤 3：将 n-1 个盘子从辅助柱移到目标柱
    hanoi($n - 1, $aux, $to, $from);
}

// 带步骤计数的汉诺塔
function hanoiWithCount($n, $from = 'A', $to = 'C', $aux = 'B', &$count = 0) {
    if ($n === 1) {
        $count++;
        return;
    }
    
    hanoiWithCount($n - 1, $from, $aux, $to, $count);
    $count++;
    hanoiWithCount($n - 1, $aux, $to, $from, $count);
}

// 可视化汉诺塔状态
class HanoiVisualizer {
    private $towers;
    private $moveCount;
    private $moves;
    
    public function __construct($n) {
        // 初始化三个塔
        $this->towers = [
            'A' => range($n, 1, -1), // [n, n-1, ..., 2, 1]
            'B' => [],
            'C' => []
        ];
        $this->moveCount = 0;
        $this->moves = [];
    }
    
    public function solve($n, $from = 'A', $to = 'C', $aux = 'B') {
        $this->solveRecursive($n, $from, $to, $aux);
        return $this->moves;
    }
    
    private function solveRecursive($n, $from, $to, $aux) {
        if ($n === 1) {
            $this->moveDisk($from, $to);
            return;
        }
        
        $this->solveRecursive($n - 1, $from, $aux, $to);
        $this->moveDisk($from, $to);
        $this->solveRecursive($n - 1, $aux, $to, $from);
    }
    
    private function moveDisk($from, $to) {
        $disk = array_pop($this->towers[$from]);
        array_push($this->towers[$to], $disk);
        $this->moveCount++;
        
        $move = [
            'step' => $this->moveCount,
            'disk' => $disk,
            'from' => $from,
            'to' => $to,
            'state' => $this->getState()
        ];
        
        $this->moves[] = $move;
        
        // 显示移动
        echo "步骤 {$this->moveCount}: 移动盘子 $disk 从 $from 到 $to\n";
        $this->displayTowers();
    }
    
    private function getState() {
        return [
            'A' => array_values($this->towers['A']),
            'B' => array_values($this->towers['B']),
            'C' => array_values($this->towers['C'])
        ];
    }
    
    public function displayTowers() {
        $maxHeight = max(
            count($this->towers['A']),
            count($this->towers['B']),
            count($this->towers['C'])
        );
        
        echo "\n";
        // 从上到下显示
        for ($level = $maxHeight - 1; $level >= 0; $level--) {
            echo "  ";
            foreach (['A', 'B', 'C'] as $tower) {
                if (isset($this->towers[$tower][$level])) {
                    echo str_pad($this->towers[$tower][$level], 3, ' ', STR_PAD_BOTH);
                } else {
                    echo " | ";
                }
                echo "   ";
            }
            echo "\n";
        }
        echo " ---   ---   --- \n";
        echo "  A     B     C  \n\n";
    }
}

// 汉诺塔变体：限制移动（不能直接从 A 到 C）
function hanoiRestricted($n, $from, $to, $aux, &$moves = []) {
    if ($n === 1) {
        // 不能直接从 A 到 C 或从 C 到 A
        if (($from === 'A' && $to === 'C') || ($from === 'C' && $to === 'A')) {
            $moves[] = "$from -> $aux";
            $moves[] = "$aux -> $to";
        } else {
            $moves[] = "$from -> $to";
        }
        return;
    }
    
    hanoiRestricted($n - 1, $from, $aux, $to, $moves);
    hanoiRestricted(1, $from, $to, $aux, $moves);
    hanoiRestricted($n - 1, $aux, $to, $from, $moves);
}

// 计算最少步数
function minMoves($n) {
    return pow(2, $n) - 1;
}

// 测试和演示
echo "=== 汉诺塔问题演示 ===\n\n";

// 基本演示
echo "3个盘子的汉诺塔：\n";
hanoi(3);

echo "\n=== 步数计算 ===\n";
for ($n = 1; $n <= 10; $n++) {
    $count = 0;
    hanoiWithCount($n, 'A', 'C', 'B', $count);
    $formula = minMoves($n);
    echo "n = $n: 需要 $count 步 (公式: 2^$n - 1 = $formula)\n";
}

echo "\n=== 可视化演示 ===\n";
echo "4个盘子的汉诺塔过程：\n\n";
$visualizer = new HanoiVisualizer(4);
echo "初始状态：\n";
$visualizer->displayTowers();

$moves = $visualizer->solve(4);

echo "\n=== 移动模式分析 ===\n";
// 分析移动模式
$diskMoves = [];
foreach ($moves as $move) {
    $disk = $move['disk'];
    if (!isset($diskMoves[$disk])) {
        $diskMoves[$disk] = [];
    }
    $diskMoves[$disk][] = $move['from'] . '->' . $move['to'];
}

foreach ($diskMoves as $disk => $movements) {
    echo "盘子 $disk 的移动: " . implode(", ", $movements) . "\n";
}

echo "\n=== 汉诺塔变体 ===\n";
echo "限制移动规则（不能直接 A<->C）：\n";
$restrictedMoves = [];
hanoiRestricted(3, 'A', 'C', 'B', $restrictedMoves);
echo "移动序列：\n";
foreach ($restrictedMoves as $i => $move) {
    echo "步骤 " . ($i + 1) . ": $move\n";
}

echo "\n=== 递归深度分析 ===\n";
function analyzeRecursionDepth($n, $depth = 0, &$maxDepth = 0) {
    $maxDepth = max($maxDepth, $depth);
    
    if ($n === 1) {
        return;
    }
    
    analyzeRecursionDepth($n - 1, $depth + 1, $maxDepth);
    analyzeRecursionDepth($n - 1, $depth + 1, $maxDepth);
}

for ($n = 1; $n <= 10; $n++) {
    $maxDepth = 0;
    analyzeRecursionDepth($n, 0, $maxDepth);
    echo "n = $n 盘子，最大递归深度: $maxDepth\n";
}

echo "\n=== 时间复杂度 ===\n";
echo "汉诺塔的时间复杂度：O(2^n)\n";
echo "这是因为：\n";
echo "- T(n) = 2*T(n-1) + 1\n";
echo "- T(1) = 1\n";
echo "- 解得：T(n) = 2^n - 1\n";

echo "\n=== 实际应用 ===\n";
echo "汉诺塔问题的应用：\n";
echo "1. 磁盘调度算法\n";
echo "2. 数据备份策略\n";
echo "3. 递归算法教学\n";
echo "4. 心理学认知测试\n";
