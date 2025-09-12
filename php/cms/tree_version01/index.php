<?php
/**
 * Tree类学习演示主页
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tree类递归学习演示</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
        .demo-link { 
            display: block; 
            padding: 15px; 
            margin: 10px 0; 
            background: #f8f9fa; 
            border: 1px solid #dee2e6; 
            text-decoration: none; 
            color: #333;
            border-radius: 5px;
        }
        .demo-link:hover { background: #e9ecef; }
        .description { color: #666; font-size: 14px; margin-top: 5px; }
        h1 { color: #2c3e50; }
        h2 { color: #34495e; border-bottom: 2px solid #3498db; padding-bottom: 5px; }
    </style>
</head>
<body>
    <h1>🌳 Tree类递归调用学习演示</h1>
    
    <p>这个项目包含了多个演示文件，帮助你一步一步理解 <code>tree.class.php</code> 中的递归调用机制，特别是递归时的传参逻辑。</p>
    
    <h2>📚 学习顺序建议</h2>
    
    <a href="core_recursion.php" class="demo-link">
        <strong>1. 核心递归机制 (推荐先看)</strong>
        <div class="description">最简化的递归逻辑演示，专门解释传参机制，易于理解</div>
    </a>
    
    <a href="recursion_explanation.php" class="demo-link">
        <strong>2. 详细递归过程</strong>
        <div class="description">手工模拟真实的get_tree方法递归过程，包含完整的参数计算</div>
    </a>
    
    <a href="demo.php" class="demo-link">
        <strong>3. 实时递归追踪</strong>
        <div class="description">使用修改版的Tree类实时追踪递归调用过程</div>
    </a>
    
    <a href="visual_recursion.php" class="demo-link">
        <strong>4. 可视化递归演示</strong>
        <div class="description">完整的HTML页面，提供最详细的可视化递归过程</div>
    </a>
    
    <a href="advanced_demo.php" class="demo-link">
        <strong>5. 高级复杂演示 (新增)</strong>
        <div class="description">复杂组织架构数据，展示Tree类在实际应用中的性能和特点</div>
    </a>
    
    <h2>🔍 原始文件</h2>
    
    <a href="tree.class.php" class="demo-link">
        <strong>tree.class.php</strong>
        <div class="description">原始的树形类文件</div>
    </a>
    
    <a href="data.php" class="demo-link">
        <strong>data.php</strong>
        <div class="description">测试数据文件</div>
    </a>
    
    <h2>🎯 核心学习要点</h2>
    
    <div style="background: #e8f4f8; padding: 15px; border-left: 4px solid #3498db; margin: 20px 0;">
        <h3>递归传参的关键理解</h3>
        <ol>
            <li><strong>myid参数:</strong> 每次递归时传递当前节点的ID，用于查找该节点的子节点</li>
            <li><strong>adds参数:</strong> 累积的前缀字符串，实现树形结构的缩进效果</li>
            <li><strong>累积公式:</strong> 新adds = 旧adds + k + nbsp</li>
            <li><strong>k的值:</strong> 根据当前节点是否为同级最后一个来决定</li>
        </ol>
    </div>
    
    <div style="background: #fff9e6; padding: 15px; border-left: 4px solid #f39c12; margin: 20px 0;">
        <h3>递归调用语句解析</h3>
        <pre style="background: #f8f9fa; padding: 10px; border-radius: 3px;">
$this->get_tree($id, $str, $sid, $adds.$k.$nbsp, $str_group);
                │     │     │     │
                │     │     │     └── 新的前缀 = 旧前缀 + 连接符 + 空格
                │     │     └────── 选中状态传递
                │     └──────────── HTML模板传递  
                └────────────────── 新的父节点ID</pre>
    </div>
</body>
</html>
