<?php
/**
 * run_examples.php - 示例运行脚本
 * 
 * 这个脚本帮助初学者快速运行所有示例，了解Tree类的功能
 * 
 * 使用方法：
 * php run_examples.php
 * 
 * 或在浏览器中访问此文件
 */

echo "<!DOCTYPE html>\n";
echo "<html>\n<head>\n";
echo "<meta charset='UTF-8'>\n";
echo "<title>Tree类学习项目 - 示例演示</title>\n";
echo "<style>\n";
echo "body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }\n";
echo ".container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }\n";
echo ".example-section { margin: 30px 0; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }\n";
echo ".example-title { color: #333; border-bottom: 2px solid #007cba; padding-bottom: 10px; margin-bottom: 15px; }\n";
echo "pre { background: #f8f8f8; padding: 15px; border-radius: 5px; overflow-x: auto; border-left: 4px solid #007cba; }\n";
echo ".success { color: #28a745; font-weight: bold; }\n";
echo ".error { color: #dc3545; font-weight: bold; }\n";
echo ".info { color: #17a2b8; font-weight: bold; }\n";
echo ".nav-menu { margin: 20px 0; }\n";
echo ".nav-menu a { display: inline-block; margin: 5px 10px; padding: 8px 15px; background: #007cba; color: white; text-decoration: none; border-radius: 3px; }\n";
echo ".nav-menu a:hover { background: #0056b3; }\n";
echo "</style>\n";
echo "</head>\n<body>\n";

echo "<div class='container'>\n";
echo "<h1>🌳 Tree类学习项目 - 示例演示</h1>\n";

echo "<div class='nav-menu'>\n";
echo "<a href='#example1'>基础使用</a>\n";
echo "<a href='#example2'>菜单演示</a>\n";
echo "<a href='#example3'>练习题目</a>\n";
echo "<a href='#example4'>类比较</a>\n";
echo "</div>\n";

// 示例1：基础使用演示
echo "<div class='example-section' id='example1'>\n";
echo "<h2 class='example-title'>📚 示例1：基础使用演示</h2>\n";

try {
    echo "<p class='info'>正在运行 examples/basic_usage.php...</p>\n";
    echo "<pre>\n";
    
    ob_start();
    include 'examples/basic_usage.php';
    $output = ob_get_clean();
    
    echo htmlspecialchars($output);
    echo "</pre>\n";
    echo "<p class='success'>✅ 基础示例运行成功！</p>\n";
    
} catch (Exception $e) {
    echo "<p class='error'>❌ 运行出错: " . htmlspecialchars($e->getMessage()) . "</p>\n";
}

echo "</div>\n";

// 示例2：菜单演示
echo "<div class='example-section' id='example2'>\n";
echo "<h2 class='example-title'>🌐 示例2：网站菜单演示</h2>\n";

try {
    echo "<p class='info'>菜单演示代码预览 (examples/menu_demo.php)：</p>\n";
    
    $menu_content = file_get_contents('examples/menu_demo.php');
    $lines = explode("\n", $menu_content);
    
    echo "<pre>\n";
    $line_count = 0;
    
    foreach ($lines as $line) {
        $line_count++;
        if ($line_count > 80) { // 只显示前80行
            echo "... (更多内容请直接运行 php examples/menu_demo.php 查看)\n";
            break;
        }
        
        if (strpos($line, '//') !== false || strpos($line, '*/') !== false || strpos($line, 'echo "=====') !== false) {
            echo htmlspecialchars($line) . "\n";
        }
    }
    echo "</pre>\n";
    echo "<p class='success'>✅ 菜单示例代码加载成功！要运行完整演示，请执行：php examples/menu_demo.php</p>\n";
    
} catch (Exception $e) {
    echo "<p class='error'>❌ 读取菜单示例出错: " . htmlspecialchars($e->getMessage()) . "</p>\n";
}

echo "</div>\n";

// 示例3：练习题展示
echo "<div class='example-section' id='example3'>\n";
echo "<h2 class='example-title'>🎯 示例3：练习题目</h2>\n";

echo "<p class='info'>练习文件预览 (exercises/exercise_01.php)：</p>\n";

try {
    $exercise_content = file_get_contents('exercises/exercise_01.php');
    $lines = explode("\n", $exercise_content);
    
    echo "<pre>\n";
    $in_comment = false;
    $line_count = 0;
    
    foreach ($lines as $line) {
        $line_count++;
        if ($line_count > 100) { // 只显示前100行
            echo "... (更多内容请查看完整文件)\n";
            break;
        }
        
        if (strpos($line, '/**') !== false) $in_comment = true;
        if (strpos($line, '*/') !== false) $in_comment = false;
        
        if ($in_comment || strpos($line, '//') !== false || strpos($line, 'echo "=====') !== false) {
            echo htmlspecialchars($line) . "\n";
        } elseif (strpos($line, 'TODO:') !== false) {
            echo "<span style='background: yellow;'>" . htmlspecialchars($line) . "</span>\n";
        }
    }
    echo "</pre>\n";
    
    echo "<p class='success'>✅ 练习文件加载成功！要完成练习，请直接编辑 exercises/exercise_01.php 文件。</p>\n";
    
} catch (Exception $e) {
    echo "<p class='error'>❌ 读取练习文件出错: " . htmlspecialchars($e->getMessage()) . "</p>\n";
}

echo "</div>\n";

// 示例4：类功能比较
echo "<div class='example-section' id='example4'>\n";
echo "<h2 class='example-title'>🔍 示例4：SimpleTree vs 原版Tree类功能比较</h2>\n";

echo "<table border='1' cellpadding='10' cellspacing='0' style='width: 100%; border-collapse: collapse;'>\n";
echo "<tr style='background: #f0f0f0;'>\n";
echo "<th>功能特性</th>\n";
echo "<th>SimpleTree (学习版)</th>\n";
echo "<th>原版Tree类</th>\n";
echo "<th>说明</th>\n";
echo "</tr>\n";

$comparisons = array(
    array('基础树形操作', '✅ 支持', '✅ 支持', '获取子节点、父节点等基本功能'),
    array('递归遍历', '✅ 支持', '✅ 支持', '获取所有子孙节点'),
    array('树形文本生成', '✅ 支持', '✅ 高级版本', '学习版较简单，原版功能更丰富'),
    array('HTML生成', '❌ 需自实现', '✅ 内置支持', '原版支持多种HTML格式'),
    array('模板系统', '❌ 无', '✅ 支持eval模板', '原版支持动态模板解析'),
    array('缓存机制', '❌ 无', '✅ 内置缓存', '原版有性能优化'),
    array('多选支持', '❌ 无', '✅ 支持', '原版支持复选框等多选模式'),
    array('TreeView支持', '❌ 无', '✅ 支持', '原版支持jQuery TreeView'),
    array('JSON输出', '❌ 无', '✅ 支持', '原版可生成JSON格式数据'),
    array('代码复杂度', '⭐ 简单易懂', '⭐⭐⭐ 较复杂', '学习版专注核心概念'),
    array('学习难度', '⭐ 适合初学', '⭐⭐⭐ 需要基础', '建议先学SimpleTree'),
    array('生产环境', '🔸 教学用途', '✅ 生产可用', '原版更适合实际项目')
);

foreach ($comparisons as $comp) {
    echo "<tr>\n";
    echo "<td><strong>{$comp[0]}</strong></td>\n";
    echo "<td>{$comp[1]}</td>\n";
    echo "<td>{$comp[2]}</td>\n";
    echo "<td>{$comp[3]}</td>\n";
    echo "</tr>\n";
}

echo "</table>\n";

echo "<h3>学习建议：</h3>\n";
echo "<ol>\n";
echo "<li><strong>第一阶段</strong>：掌握 SimpleTree 的所有方法和概念</li>\n";
echo "<li><strong>第二阶段</strong>：完成所有练习题，理解实际应用场景</li>\n";
echo "<li><strong>第三阶段</strong>：研究原版 tree.class.php，学习高级特性</li>\n";
echo "<li><strong>实践阶段</strong>：在实际项目中应用，根据需要选择合适的版本</li>\n";
echo "</ol>\n";

echo "</div>\n";

// 学习进度检查
echo "<div class='example-section'>\n";
echo "<h2 class='example-title'>📈 学习进度自检</h2>\n";

echo "<h3>基础知识检查：</h3>\n";
echo "<ul>\n";
echo "<li>□ 理解树形结构的基本概念（根节点、叶子节点、父子关系）</li>\n";
echo "<li>□ 掌握Tree类的数据格式要求</li>\n";
echo "<li>□ 能够使用 init() 方法初始化数据</li>\n";
echo "<li>□ 理解 getChildren() 和 getAllDescendants() 的区别</li>\n";
echo "<li>□ 能够生成简单的树形文本结构</li>\n";
echo "</ul>\n";

echo "<h3>应用能力检查：</h3>\n";
echo "<ul>\n";
echo "<li>□ 能够实现网站菜单的生成</li>\n";
echo "<li>□ 能够生成面包屑导航</li>\n";
echo "<li>□ 理解递归在树形结构中的应用</li>\n";
echo "<li>□ 能够处理树形数据的增删改操作</li>\n";
echo "<li>□ 了解Tree类的性能优化策略</li>\n";
echo "</ul>\n";

echo "<h3>项目实践检查：</h3>\n";
echo "<ul>\n";
echo "<li>□ 完成所有练习题目</li>\n";
echo "<li>□ 能够独立设计树形数据结构</li>\n";
echo "<li>□ 能够根据需求选择合适的Tree方法</li>\n";
echo "<li>□ 了解常见问题的解决方案</li>\n";
echo "<li>□ 能够在实际项目中应用Tree类</li>\n";
echo "</ul>\n";

echo "</div>\n";

// 相关资源
echo "<div class='example-section'>\n";
echo "<h2 class='example-title'>📚 相关学习资源</h2>\n";

echo "<h3>项目文件结构：</h3>\n";
echo "<pre>\n";
echo "tree_learning_project/\n";
echo "├── README.md                 # 项目介绍\n";
echo "├── docs/                     # 学习文档\n";
echo "│   ├── 01_基础概念.md\n";
echo "│   ├── 02_数据结构.md\n";
echo "│   ├── 03_核心方法.md\n";
echo "│   └── 04_实战应用.md\n";
echo "├── src/                      # 源代码\n";
echo "│   └── SimpleTree.php        # 简化版Tree类\n";
echo "├── examples/                 # 示例代码\n";
echo "│   ├── basic_usage.php       # 基础使用示例\n";
echo "│   └── menu_demo.php         # 菜单应用示例\n";
echo "├── exercises/                # 练习题\n";
echo "│   ├── exercise_01.php       # 基础练习\n";
echo "│   └── solutions/            # 练习答案\n";
echo "└── run_examples.php          # 示例运行脚本(当前文件)\n";
echo "</pre>\n";

echo "<h3>下一步学习：</h3>\n";
echo "<ol>\n";
echo "<li>📖 阅读完整文档：从 docs/01_基础概念.md 开始</li>\n";
echo "<li>💻 运行示例代码：php examples/basic_usage.php</li>\n";
echo "<li>✏️ 完成练习题：编辑 exercises/exercise_01.php</li>\n";
echo "<li>🔧 实践应用：在自己的项目中使用Tree类</li>\n";
echo "<li>📈 进阶学习：研究原版 tree.class.php 的高级功能</li>\n";
echo "</ol>\n";

echo "</div>\n";

echo "<div style='text-align: center; margin-top: 40px; padding: 20px; background: #f8f9fa; border-radius: 5px;'>\n";
echo "<h3>🎉 恭喜完成Tree类学习项目演示！</h3>\n";
echo "<p>现在你可以开始深入学习Tree类的各个功能模块了。</p>\n";
echo "<p>记住：<strong>理论结合实践，多动手多思考</strong>，这样才能真正掌握Tree类！</p>\n";
echo "</div>\n";

echo "</div>\n";
echo "</body>\n</html>\n";
?>
