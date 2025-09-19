<?php
/**
 * PHP 递归学习项目 - 运行入口
 * 
 * 这个脚本帮助你快速运行各种示例和练习
 */

echo "\n";
echo "╔══════════════════════════════════════════════════════════╗\n";
echo "║          PHP 递归学习项目 - 从零到精通                   ║\n";
echo "╚══════════════════════════════════════════════════════════╝\n\n";

function showMenu() {
    echo "请选择要运行的内容：\n\n";
    echo "【基础示例】\n";
    echo "  1. 简单倒计时\n";
    echo "  2. 阶乘计算\n";
    echo "  3. 数字求和\n";
    echo "  4. 斐波那契数列\n";
    echo "  5. 字符串反转\n";
    echo "\n【进阶示例】\n";
    echo "  6. 二分查找\n";
    echo "  7. 快速排序\n";
    echo "  8. 汉诺塔问题\n";
    echo "  9. 树的操作\n";
    echo "  10. 回溯算法\n";
    echo "\n【练习题】\n";
    echo "  11. 基础练习题\n";
    echo "  12. 基础练习题答案\n";
    echo "  13. 进阶练习题\n";
    echo "  14. 进阶练习题答案\n";
    echo "\n【其他】\n";
    echo "  15. 查看项目文档\n";
    echo "  16. 打开可视化演示（需要浏览器）\n";
    echo "  0. 退出\n";
    echo "\n请输入选项编号: ";
}

function runExample($file) {
    $path = __DIR__ . '/' . $file;
    if (file_exists($path)) {
        echo "\n" . str_repeat("=", 60) . "\n";
        echo "运行: $file\n";
        echo str_repeat("=", 60) . "\n\n";
        
        // 捕获输出，避免重复的 echo
        ob_start();
        include $path;
        $output = ob_get_clean();
        
        // 如果文件已经有输出，直接显示
        if (!empty($output)) {
            echo $output;
        }
        
        echo "\n" . str_repeat("=", 60) . "\n";
        echo "按回车键继续...";
        fgets(STDIN);
    } else {
        echo "\n错误：文件不存在 - $path\n";
        echo "按回车键继续...";
        fgets(STDIN);
    }
}

function showDocumentation() {
    echo "\n可用的文档：\n\n";
    echo "1. docs/01_递归基础概念.md - 递归入门知识\n";
    echo "2. docs/02_递归执行原理.md - 深入理解递归\n";
    echo "3. docs/03_递归模式详解.md - 各种递归模式\n";
    echo "4. docs/04_递归优化技巧.md - 性能优化方法\n";
    echo "\n提示：使用 'cat docs/文件名' 命令查看文档内容\n";
    echo "\n按回车键继续...";
    fgets(STDIN);
}

function openVisualization() {
    $htmlFile = __DIR__ . '/visualization/index.html';
    
    echo "\n可视化演示文件位置：\n";
    echo $htmlFile . "\n\n";
    
    // 尝试在不同系统上打开浏览器
    $commands = [
        'xdg-open' => 'Linux',
        'open' => 'macOS',
        'start' => 'Windows'
    ];
    
    $opened = false;
    foreach ($commands as $cmd => $os) {
        if (PHP_OS_FAMILY === $os || (PHP_OS_FAMILY === 'Linux' && $cmd === 'xdg-open')) {
            $fullCmd = $cmd . ' ' . escapeshellarg($htmlFile);
            @exec($fullCmd . ' 2>/dev/null', $output, $return);
            if ($return === 0) {
                echo "已在默认浏览器中打开...\n";
                $opened = true;
                break;
            }
        }
    }
    
    if (!$opened) {
        echo "无法自动打开浏览器，请手动打开以下文件：\n";
        echo $htmlFile . "\n";
    }
    
    echo "\n按回车键继续...";
    fgets(STDIN);
}

// 主循环
while (true) {
    system('clear'); // Linux/Mac
    // system('cls'); // Windows
    
    showMenu();
    $choice = trim(fgets(STDIN));
    
    switch ($choice) {
        case '1':
            runExample('examples/basic/01_simple_countdown.php');
            break;
        case '2':
            runExample('examples/basic/02_factorial.php');
            break;
        case '3':
            runExample('examples/basic/03_sum_of_numbers.php');
            break;
        case '4':
            runExample('examples/basic/04_fibonacci.php');
            break;
        case '5':
            runExample('examples/basic/05_string_reverse.php');
            break;
        case '6':
            runExample('examples/advanced/01_binary_search.php');
            break;
        case '7':
            runExample('examples/advanced/02_quick_sort.php');
            break;
        case '8':
            runExample('examples/advanced/03_tower_of_hanoi.php');
            break;
        case '9':
            runExample('examples/advanced/04_tree_operations.php');
            break;
        case '10':
            runExample('examples/advanced/05_backtracking.php');
            break;
        case '11':
            runExample('exercises/problems/01_basic_exercises.php');
            break;
        case '12':
            runExample('exercises/solutions/01_basic_solutions.php');
            break;
        case '13':
            runExample('exercises/problems/02_advanced_exercises.php');
            break;
        case '14':
            runExample('exercises/solutions/02_advanced_solutions.php');
            break;
        case '15':
            showDocumentation();
            break;
        case '16':
            openVisualization();
            break;
        case '0':
            echo "\n感谢使用！祝你学习愉快！\n\n";
            exit(0);
        default:
            echo "\n无效的选项，请重新选择。\n";
            echo "按回车键继续...";
            fgets(STDIN);
    }
}

?>
