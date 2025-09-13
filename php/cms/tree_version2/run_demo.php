<?php
/**
 * run_demo.php - 可视化演示启动脚本
 * 
 * 这个脚本帮助用户快速启动可视化演示
 * 提供多种方式查看Tree类的可视化效果
 */

echo "===== 🎨 Tree类可视化演示启动器 =====\n\n";

// 检查文件是否存在
$demo_file = __DIR__ . '/assets/tree_demo.html';
$style_file = __DIR__ . '/assets/style.css';

echo "📋 检查文件状态：\n";
echo "   HTML演示文件: " . (file_exists($demo_file) ? "✅ 存在" : "❌ 缺失") . "\n";
echo "   CSS样式文件: " . (file_exists($style_file) ? "✅ 存在" : "❌ 缺失") . "\n\n";

if (!file_exists($demo_file) || !file_exists($style_file)) {
    echo "❌ 演示文件缺失，请确保 assets/ 目录下有完整文件！\n";
    exit(1);
}

echo "🚀 启动方式选择：\n\n";

echo "方式一：直接在浏览器中打开\n";
echo "   文件路径: " . realpath($demo_file) . "\n";
echo "   直接双击或拖拽到浏览器即可\n\n";

echo "方式二：使用PHP内置服务器\n";
echo "   在终端中运行以下命令：\n";
echo "   cd " . __DIR__ . "\n";
echo "   php -S localhost:8080\n";
echo "   然后访问: http://localhost:8080/assets/tree_demo.html\n\n";

echo "方式三：使用Python简单服务器\n";
echo "   在终端中运行以下命令：\n";
echo "   cd " . __DIR__ . "\n";
echo "   python3 -m http.server 8080\n";
echo "   然后访问: http://localhost:8080/assets/tree_demo.html\n\n";

echo "方式四：使用Node.js服务器（需要安装http-server）\n";
echo "   npm install -g http-server\n";
echo "   cd " . __DIR__ . "\n";
echo "   http-server -p 8080\n";
echo "   然后访问: http://localhost:8080/assets/tree_demo.html\n\n";

// 获取用户选择
echo "选择启动方式 (1-4) 或按 Enter 获取帮助信息: ";
$input = trim(fgets(STDIN));

switch ($input) {
    case '1':
        echo "\n📂 正在尝试在默认浏览器中打开...\n";
        $demo_url = "file://" . realpath($demo_file);
        
        // 根据操作系统选择打开命令
        if (PHP_OS_FAMILY === 'Windows') {
            exec("start \"\" \"$demo_url\"");
        } elseif (PHP_OS_FAMILY === 'Darwin') {
            exec("open \"$demo_url\"");
        } else {
            exec("xdg-open \"$demo_url\"");
        }
        
        echo "✅ 如果浏览器没有自动打开，请手动访问：\n";
        echo "   $demo_url\n";
        break;
        
    case '2':
        echo "\n🌐 正在启动PHP内置服务器...\n";
        echo "服务器将在 http://localhost:8080 启动\n";
        echo "演示地址: http://localhost:8080/assets/tree_demo.html\n";
        echo "按 Ctrl+C 停止服务器\n\n";
        
        // 改变工作目录并启动服务器
        chdir(__DIR__);
        exec("php -S localhost:8080", $output, $return_code);
        break;
        
    case '3':
        echo "\n🐍 使用Python启动服务器的命令：\n";
        echo "cd " . __DIR__ . "\n";
        echo "python3 -m http.server 8080\n\n";
        echo "请在终端中手动执行上述命令\n";
        break;
        
    case '4':
        echo "\n📦 使用Node.js启动服务器的命令：\n";
        echo "npm install -g http-server\n";
        echo "cd " . __DIR__ . "\n";
        echo "http-server -p 8080\n\n";
        echo "请在终端中手动执行上述命令\n";
        break;
        
    default:
        echo "\n📖 使用说明：\n\n";
        
        echo "🎯 可视化演示功能：\n";
        echo "   • 交互式树形结构展示\n";
        echo "   • 5种不同的演示数据集\n";
        echo "   • 节点展开/折叠功能\n";
        echo "   • 实时PHP代码示例\n";
        echo "   • 树操作演示（路径查找、兄弟节点等）\n";
        echo "   • 响应式设计，支持移动设备\n\n";
        
        echo "🎨 演示数据集：\n";
        echo "   1. 公司组织架构 - 展示部门层级关系\n";
        echo "   2. 电商分类系统 - 商品分类管理\n";
        echo "   3. 在线教育课程 - 课程体系结构\n";
        echo "   4. 网站导航菜单 - 多级菜单演示\n";
        echo "   5. 文件系统结构 - 目录文件层级\n\n";
        
        echo "🔧 交互功能：\n";
        echo "   • 点击节点查看详细信息\n";
        echo "   • 点击箭头展开/折叠子节点\n";
        echo "   • 全部展开/折叠控制\n";
        echo "   • 显示/隐藏节点ID和路径\n";
        echo "   • 实时树操作演示\n\n";
        
        echo "💡 学习建议：\n";
        echo "   1. 先查看可视化演示理解概念\n";
        echo "   2. 对比HTML演示和PHP代码实现\n";
        echo "   3. 尝试不同的演示数据集\n";
        echo "   4. 完成练习题加深理解\n\n";
        
        echo "重新运行此脚本选择启动方式。\n";
        break;
}

echo "\n🎉 感谢使用Tree类学习项目！\n";
echo "📚 更多资源：\n";
echo "   • 查看 docs/ 目录下的详细文档\n";
echo "   • 运行 examples/ 目录下的PHP示例\n";
echo "   • 完成 exercises/ 目录下的练习\n";
?>
