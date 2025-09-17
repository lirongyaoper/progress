<?php
/**
 * 阶乘函数栈帧演示
 * 详细展示递归调用过程中栈帧的创建和销毁
 */

// 全局变量用于跟踪栈帧
$stack_frames = [];
$call_depth = 0;

function factorial($n) {
    global $stack_frames, $call_depth;
    
    // 创建新的栈帧
    $call_depth++;
    $frame_id = $call_depth;
    
    // 记录栈帧信息
    $stack_frames[$frame_id] = [
        'function' => 'factorial',
        'parameter_n' => $n,
        'depth' => $call_depth,
        'status' => 'active',
        'waiting_for' => null,
        'return_value' => null
    ];
    
    echo "<div style='margin: 5px 0; padding: 10px; border: 2px solid #007acc; background: #f0f8ff;'>";
    echo "<strong>📥 栈帧{$frame_id}: factorial($n) 入栈</strong><br>";
    echo "参数: \$n = $n<br>";
    echo "返回地址: " . ($frame_id > 1 ? "factorial(" . ($n+1) . ")" : "main()") . "<br>";
    echo "当前栈深度: $call_depth<br>";
    displayStack();
    echo "</div>";
    
    // 基础情况
    if ($n <= 1) {
        echo "<div style='margin: 5px 0; padding: 10px; border: 2px solid #28a745; background: #f0fff0;'>";
        echo "<strong>🎯 基础情况: factorial($n) = 1</strong><br>";
        echo "栈帧{$frame_id} 准备返回值: 1<br>";
        echo "</div>";
        
        $stack_frames[$frame_id]['return_value'] = 1;
        $stack_frames[$frame_id]['status'] = 'returning';
        
        echo "<div style='margin: 5px 0; padding: 10px; border: 2px solid #ffc107; background: #fffbf0;'>";
        echo "<strong>📤 栈帧{$frame_id}: factorial($n) 出栈</strong><br>";
        echo "返回值: 1<br>";
        displayStack();
        echo "</div>";
        
        unset($stack_frames[$frame_id]);
        $call_depth--;
        return 1;
    }
    
    // 递归情况
    echo "<div style='margin: 5px 0; padding: 10px; border: 2px solid #fd7e14; background: #fff5f0;'>";
    echo "<strong>🔄 递归调用: factorial($n) 需要调用 factorial(" . ($n-1) . ")</strong><br>";
    echo "栈帧{$frame_id} 等待 factorial(" . ($n-1) . ") 的返回值<br>";
    echo "</div>";
    
    $stack_frames[$frame_id]['waiting_for'] = "factorial(" . ($n-1) . ")";
    
    // 递归调用
    $result = factorial($n - 1);
    
    // 计算结果
    $final_result = $n * $result;
    
    echo "<div style='margin: 5px 0; padding: 10px; border: 2px solid #6f42c1; background: #f8f0ff;'>";
    echo "<strong>🧮 计算结果: factorial($n) = $n * $result = $final_result</strong><br>";
    echo "栈帧{$frame_id} 计算完成，准备返回<br>";
    echo "</div>";
    
    $stack_frames[$frame_id]['return_value'] = $final_result;
    $stack_frames[$frame_id]['status'] = 'returning';
    
    echo "<div style='margin: 5px 0; padding: 10px; border: 2px solid #ffc107; background: #fffbf0;'>";
    echo "<strong>📤 栈帧{$frame_id}: factorial($n) 出栈</strong><br>";
    echo "返回值: $final_result<br>";
    displayStack();
    echo "</div>";
    
    unset($stack_frames[$frame_id]);
    $call_depth--;
    return $final_result;
}

function displayStack() {
    global $stack_frames;
    
    echo "<div style='background: #e9ecef; padding: 8px; margin: 5px 0; border-radius: 4px;'>";
    echo "<strong>当前调用栈状态:</strong><br>";
    
    if (empty($stack_frames)) {
        echo "栈为空<br>";
    } else {
        // 从栈顶到栈底显示
        $frames = array_reverse($stack_frames, true);
        foreach ($frames as $id => $frame) {
            $status_icon = $frame['status'] == 'active' ? '🟢' : '🟡';
            echo "栈帧{$id}: {$status_icon} {$frame['function']}({$frame['parameter_n']})";
            if ($frame['waiting_for']) {
                echo " [等待: {$frame['waiting_for']}]";
            }
            if ($frame['return_value'] !== null) {
                echo " [返回: {$frame['return_value']}]";
            }
            echo "<br>";
        }
    }
    echo "</div>";
}

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>阶乘函数栈帧演示</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            text-align: center;
            border-bottom: 3px solid #3498db;
            padding-bottom: 10px;
        }
        .code-block {
            background: #2d3748;
            color: #e2e8f0;
            padding: 15px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            margin: 10px 0;
            overflow-x: auto;
        }
        .explanation {
            background: #e8f4fd;
            border-left: 4px solid #3498db;
            padding: 15px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔄 阶乘函数递归栈帧演示</h1>
        
        <div class="explanation">
            <h3>📖 阶乘函数代码:</h3>
            <div class="code-block">
function factorial($n) {<br>
&nbsp;&nbsp;&nbsp;&nbsp;// 每次调用都会创建一个新的栈帧<br>
&nbsp;&nbsp;&nbsp;&nbsp;if ($n <= 1) {<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;return 1;<br>
&nbsp;&nbsp;&nbsp;&nbsp;}<br>
&nbsp;&nbsp;&nbsp;&nbsp;return $n * factorial($n - 1);<br>
}
            </div>
        </div>

        <div class="explanation">
            <h3>🎯 栈帧说明:</h3>
            <ul>
                <li><strong>📥 入栈</strong>: 每次函数调用都会创建新的栈帧</li>
                <li><strong>🟢 活跃状态</strong>: 栈帧正在执行或等待结果</li>
                <li><strong>🟡 返回状态</strong>: 栈帧已计算完成，准备返回</li>
                <li><strong>📤 出栈</strong>: 栈帧销毁，返回到调用者</li>
            </ul>
        </div>

        <h2>🚀 执行 factorial(4) 的完整过程:</h2>
        
        <?php
        echo "<div style='background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "<h3>🎬 开始执行 factorial(4)</h3>";
        echo "</div>";
        
        $result = factorial(4);
        
        echo "<div style='background: #d1ecf1; border: 1px solid #bee5eb; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "<h3>🎉 最终结果: factorial(4) = $result</h3>";
        echo "</div>";
        ?>

        <div class="explanation">
            <h3>📝 关键概念总结:</h3>
            <ol>
                <li><strong>栈帧生命周期</strong>: 函数调用时创建，返回时销毁</li>
                <li><strong>参数传递</strong>: 每个栈帧都有自己的参数副本</li>
                <li><strong>返回地址</strong>: 记录函数完成后要回到的位置</li>
                <li><strong>执行顺序</strong>: 先入后出（LIFO - Last In First Out）</li>
                <li><strong>内存管理</strong>: 栈帧自动管理内存分配和释放</li>
            </ol>
        </div>

        <div class="explanation">
            <h3>🔍 你提到的栈帧分析:</h3>
            <ul>
                <li><strong>栈帧4</strong>: factorial(4) 最先被调用，最后返回</li>
                <li><strong>栈帧3</strong>: factorial(3) 被栈帧4调用，向栈帧4返回</li>
                <li><strong>栈帧2</strong>: factorial(2) 被栈帧3调用，向栈帧3返回</li>
                <li><strong>栈帧1</strong>: factorial(1) 被栈帧2调用，向栈帧2返回</li>
            </ul>
            <p><strong>这就是为什么返回地址指向上一级调用者的原因！</strong></p>
        </div>
    </div>
</body>
</html>
