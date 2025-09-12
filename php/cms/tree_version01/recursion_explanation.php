<?php
/**
 * Tree类递归传参机制详解
 * 
 * 这个文件专门解释get_tree方法中的递归传参逻辑
 */

require_once 'tree.class.php';

// 简单的测试数据
$simple_data = array(
    1 => array('id'=>'1','parentid'=>0,'name'=>'A'),
    2 => array('id'=>'2','parentid'=>1,'name'=>'B'),
    3 => array('id'=>'3','parentid'=>1,'name'=>'C'),
    4 => array('id'=>'4','parentid'=>2,'name'=>'D')
);

echo "<h1>Tree类递归传参机制详解</h1>";

echo "<h2>原始数据展示</h2>";
echo "<div style='background: #f8f9fa; padding: 15px; border: 1px solid #dee2e6; margin: 10px 0;'>";
echo "<h3>数组数据结构</h3>";
echo "<pre style='background: #ffffff; padding: 10px; border: 1px solid #ddd;'>";
echo "简单测试数据:\n";
print_r($simple_data);
echo "</pre>";

echo "<h3>数据关系表</h3>";
echo "<table border='1' cellpadding='5' style='border-collapse: collapse; margin: 10px 0;'>";
echo "<tr style='background: #e9ecef;'>";
echo "<th>ID</th><th>父节点ID (parentid)</th><th>节点名称</th><th>层级关系</th>";
echo "</tr>";
foreach($simple_data as $item) {
    $level_desc = $item['parentid'] == 0 ? "根节点" : "子节点";
    echo "<tr>";
    echo "<td>{$item['id']}</td>";
    echo "<td>{$item['parentid']}</td>";
    echo "<td>{$item['name']}</td>";
    echo "<td>{$level_desc}</td>";
    echo "</tr>";
}
echo "</table>";
echo "</div>";

echo "<h2>树形结构可视化</h2>";
echo "<div style='background: #e8f5e9; padding: 15px; border-left: 4px solid #4caf50;'>";
echo "<pre style='font-size: 14px; line-height: 1.4;'>";
echo "树形结构图:\n";
echo "A(1) - parentid=0\n";
echo "├─B(2) - parentid=1\n";
echo "│  └─D(4) - parentid=2\n";
echo "└─C(3) - parentid=1\n";
echo "</pre>";

echo "<h3>父子关系分析</h3>";
echo "<ul>";
echo "<li><strong>A(1)</strong>: 根节点，parentid=0，有2个子节点 B(2) 和 C(3)</li>";
echo "<li><strong>B(2)</strong>: A的子节点，parentid=1，有1个子节点 D(4)</li>";
echo "<li><strong>C(3)</strong>: A的子节点，parentid=1，没有子节点</li>";
echo "<li><strong>D(4)</strong>: B的子节点，parentid=2，没有子节点</li>";
echo "</ul>";
echo "</div>";

echo "<h2>get_child()方法工作演示</h2>";
echo "<div style='background: #fff3e0; padding: 15px; border-left: 4px solid #ff9800; margin: 10px 0;'>";
echo "<h3>查找各节点的子节点</h3>";

// 创建tree实例
$tree_demo = new tree();
$tree_demo->init($simple_data);

// 演示get_child方法的工作
$parent_ids = array(0, 1, 2, 3, 4);
foreach($parent_ids as $pid) {
    $children = $tree_demo->get_child($pid);
    echo "<strong>get_child({$pid}):</strong> ";
    if($children) {
        $child_names = array();
        foreach($children as $child) {
            $child_names[] = "{$child['name']}({$child['id']})";
        }
        echo "找到 " . implode(', ', $child_names);
    } else {
        echo "没有找到子节点";
    }
    echo "<br>";
}
echo "<br>";

echo "<strong>这就是递归查找的基础：</strong><br>";
echo "- get_child(0) → 找到A(1) → 作为第一层级<br>";
echo "- get_child(1) → 找到B(2), C(3) → 作为第二层级<br>";
echo "- get_child(2) → 找到D(4) → 作为第三层级<br>";
echo "- get_child(3) → 没有找到 → 递归终止<br>";
echo "- get_child(4) → 没有找到 → 递归终止<br>";
echo "</div>";

// 手工模拟递归过程
echo "<h2>手工模拟get_tree递归过程</h2>";

// 创建tree实例
$tree = new tree();
$tree->init($simple_data);
$tree->icon = array('│','├','└');
$tree->nbsp = "&nbsp;";

echo "<div style='font-family: monospace; background: #f8f8f8; padding: 15px;'>";

// 定义实际的模板参数
$str_template = "<div>\$spacer\$name</div>";
$str_group_template = "<div class='group'>\$spacer\$name</div>";

echo "<h3>实际参数赋值</h3>";
echo "<strong>模板参数:</strong><br>";
echo "- \$str = \"" . htmlspecialchars($str_template) . "\"<br>";
echo "- \$str_group = \"" . htmlspecialchars($str_group_template) . "\"<br><br>";

echo "<h3>第1次调用: get_tree(0, \$str, 0, '', \$str_group)</h3>";
echo "<strong>参数:</strong><br>";
echo "- myid = 0 (查找parentid=0的节点)<br>";
echo "- \$str = \"" . htmlspecialchars($str_template) . "\"<br>";
echo "- \$sid = 0 (没有选中的节点)<br>";
echo "- adds = '' (初始为空)<br>";
echo "- \$str_group = \"" . htmlspecialchars($str_group_template) . "\"<br><br>";

echo "<strong>找到子节点:</strong> A(id=1)<br>";
echo "<strong>处理A节点:</strong><br>";
echo "- number=1, total=1 (A是唯一子节点)<br>";
echo "- 因为是最后一个: j = '└'<br>";
echo "- adds为空，所以 k = ''<br>";
echo "- spacer = '' + '└' = '└'<br>";
echo "- 生成: └A<br><br>";

echo "<strong>检查A是否有子节点:</strong> 有B(2)和C(3)<br>";
echo "<strong>准备递归调用:</strong><br>";
echo "- 新的myid = 1 (A的id)<br>";
echo "- 新的adds = '' + '' + '&nbsp;' = '&nbsp;'<br>";
echo "<span style='color: red;'>→ 递归调用: get_tree(1, \$str, 0, '&nbsp;', \$str_group)</span><br><br>";

echo "<h3>第2次调用: get_tree(1, \$str, 0, '&nbsp;', \$str_group)</h3>";
echo "<strong>参数:</strong><br>";
echo "- myid = 1 (查找parentid=1的节点)<br>";
echo "- \$str = \"" . htmlspecialchars($str_template) . "\" (保持不变)<br>";
echo "- \$sid = 0 (保持不变)<br>";
echo "- adds = '&nbsp;' (从上级传入)<br>";
echo "- \$str_group = \"" . htmlspecialchars($str_group_template) . "\" (保持不变)<br><br>";

echo "<strong>找到子节点:</strong> B(id=2), C(id=3)<br>";
echo "<strong>处理B节点 (number=1):</strong><br>";
echo "- number=1, total=2 (B不是最后一个)<br>";
echo "- 不是最后一个: j = '├'<br>";
echo "- adds不为空，所以 k = '│'<br>";
echo "- spacer = '&nbsp;' + '├' = '&nbsp;├'<br>";
echo "- 生成: &nbsp;├B<br><br>";

echo "<strong>检查B是否有子节点:</strong> 有D(4)<br>";
echo "<strong>准备递归调用:</strong><br>";
echo "- 新的myid = 2 (B的id)<br>";
echo "- 新的adds = '&nbsp;' + '│' + '&nbsp;' = '&nbsp;│&nbsp;'<br>";
echo "<span style='color: red;'>→ 递归调用: get_tree(2, \$str, 0, '&nbsp;│&nbsp;', \$str_group)</span><br><br>";

echo "<h3>第3次调用: get_tree(2, \$str, 0, '&nbsp;│&nbsp;', \$str_group)</h3>";
echo "<strong>参数:</strong><br>";
echo "- myid = 2 (查找parentid=2的节点)<br>";
echo "- \$str = \"" . htmlspecialchars($str_template) . "\" (保持不变)<br>";
echo "- \$sid = 0 (保持不变)<br>";
echo "- adds = '&nbsp;│&nbsp;' (从上级传入)<br>";
echo "- \$str_group = \"" . htmlspecialchars($str_group_template) . "\" (保持不变)<br><br>";

echo "<strong>找到子节点:</strong> D(id=4)<br>";
echo "<strong>处理D节点:</strong><br>";
echo "- number=1, total=1 (D是唯一子节点)<br>";
echo "- 因为是最后一个: j = '└'<br>";
echo "- adds不为空，所以 k = '│'<br>";
echo "- spacer = '&nbsp;│&nbsp;' + '└' = '&nbsp;│&nbsp;└'<br>";
echo "- 生成: &nbsp;│&nbsp;└D<br><br>";

echo "<strong>检查D是否有子节点:</strong> 没有<br>";
echo "<strong>第3次调用结束，返回第2次调用</strong><br><br>";

echo "<h3>返回第2次调用，继续处理C节点</h3>";
echo "<strong>处理C节点 (number=2):</strong><br>";
echo "- number=2, total=2 (C是最后一个)<br>";
echo "- 因为是最后一个: j = '└'<br>";
echo "- adds不为空，但C是最后一个，所以 k = ''<br>";
echo "- spacer = '&nbsp;' + '└' = '&nbsp;└'<br>";
echo "- 生成: &nbsp;└C<br><br>";

echo "<strong>检查C是否有子节点:</strong> 没有<br>";
echo "<strong>第2次调用结束，返回第1次调用</strong><br><br>";

echo "<strong>第1次调用结束</strong><br>";

echo "</div>";

echo "<h2>关键传参规律总结</h2>";
echo "<div style='background: #e8f4f8; padding: 15px; border-left: 4px solid #2196F3;'>";
echo "<h3>1. myid参数的变化</h3>";
echo "<ul>";
echo "<li>每次递归时，myid变为当前正在处理的节点的id</li>";
echo "<li>用于查找该节点的所有子节点 (parentid = myid)</li>";
echo "</ul>";

echo "<h3>2. adds参数的累积规律</h3>";
echo "<ul>";
echo "<li><strong>初始值:</strong> '' (空字符串)</li>";
echo "<li><strong>累积公式:</strong> 新adds = 旧adds + k + nbsp</li>";
echo "<li><strong>k的值:</strong>";
echo "  <ul>";
echo "  <li>如果当前节点不是同级最后一个，且上级adds不为空: k = '│'</li>";
echo "  <li>如果当前节点是同级最后一个，或上级adds为空: k = ''</li>";
echo "  </ul>";
echo "</li>";
echo "</ul>";

echo "<h3>3. 图标选择规律</h3>";
echo "<ul>";
echo "<li><strong>j的值 (当前节点的连接符):</strong>";
echo "  <ul>";
echo "  <li>如果是同级最后一个节点: j = '└' (icon[2])</li>";
echo "  <li>如果不是同级最后一个节点: j = '├' (icon[1])</li>";
echo "  </ul>";
echo "</li>";
echo "</ul>";

echo "<h3>4. spacer的组成</h3>";
echo "<ul>";
echo "<li><strong>spacer = adds + j</strong></li>";
echo "<li>adds提供层级缩进</li>";
echo "<li>j提供当前节点的连接符</li>";
echo "</ul>";
echo "</div>";

echo "<h2>实际运行结果对比</h2>";

echo "<h3>使用我们定义的模板参数运行</h3>";
$tree2 = new tree();
$tree2->init($simple_data);
$tree2->icon = array('│','├','└');
$tree2->nbsp = "&nbsp;";

// 使用我们定义的模板参数
$result = $tree2->get_tree(0, $str_template, 0, '', $str_group_template);
echo "<div style='border: 1px solid #ccc; padding: 10px; background: white;'>";
echo "<strong>HTML输出:</strong><br>";
echo $result;
echo "</div>";

echo "<h3>参数详细解释</h3>";
echo "<div style='background: #f0f8ff; padding: 10px; border-left: 3px solid #0066cc;'>";
echo "<ul>";
echo "<li><strong>\$str参数:</strong> \"" . htmlspecialchars($str_template) . "\"<br>";
echo "   - 这是HTML模板，其中 \$spacer 会被替换为层级前缀，\$name 会被替换为节点名称</li>";
echo "<li><strong>\$str_group参数:</strong> \"" . htmlspecialchars($str_group_template) . "\"<br>";
echo "   - 用于根级节点的特殊样式（当 parentid=0 时使用）</li>";
echo "<li><strong>\$sid参数:</strong> 0<br>";
echo "   - 表示选中的节点ID，0表示没有选中任何节点</li>";
echo "</ul>";
echo "</div>";

echo "<h3>模板变量替换过程演示</h3>";
echo "<div style='background: #fff8e1; padding: 10px; border-left: 3px solid #ff9800;'>";
echo "<strong>在每个递归层级中，模板变量是这样被替换的：</strong><br><br>";

echo "<strong>节点A (Level 1):</strong><br>";
echo "- 模板: \"" . htmlspecialchars($str_template) . "\"<br>";
echo "- \$spacer = '└'<br>";
echo "- \$name = 'A'<br>";
echo "- 结果: \"" . htmlspecialchars("<div>└A</div>") . "\"<br><br>";

echo "<strong>节点B (Level 2):</strong><br>";
echo "- 模板: \"" . htmlspecialchars($str_template) . "\" (保持不变)<br>";
echo "- \$spacer = '&nbsp;├'<br>";
echo "- \$name = 'B'<br>";
echo "- 结果: \"" . htmlspecialchars("<div>&nbsp;├B</div>") . "\"<br><br>";

echo "<strong>节点D (Level 3):</strong><br>";
echo "- 模板: \"" . htmlspecialchars($str_template) . "\" (保持不变)<br>";
echo "- \$spacer = '&nbsp;│&nbsp;└'<br>";
echo "- \$name = 'D'<br>";
echo "- 结果: \"" . htmlspecialchars("<div>&nbsp;│&nbsp;└D</div>") . "\"<br><br>";

echo "<strong>节点C (Level 2):</strong><br>";
echo "- 模板: \"" . htmlspecialchars($str_template) . "\" (保持不变)<br>";
echo "- \$spacer = '&nbsp;└'<br>";
echo "- \$name = 'C'<br>";
echo "- 结果: \"" . htmlspecialchars("<div>&nbsp;└C</div>") . "\"<br><br>";

echo "<strong>关键理解:</strong><br>";
echo "1. \$str 和 \$str_group 在整个递归过程中<span style='color: red; font-weight: bold;'>保持不变</span><br>";
echo "2. 只有 \$myid 和 \$adds 在递归调用时会改变<br>";
echo "3. \$sid 通常也保持不变，除非需要传递不同的选中状态<br>";
echo "</div>";

// 创建ASCII版本便于理解
echo "<h2>ASCII版本结果</h2>";
$tree3 = new tree();
$tree3->init($simple_data);
$tree3->icon = array('|','├','└');
$tree3->nbsp = " ";

$ascii_result = $tree3->get_tree(0, "\$spacer\$name\n");
echo "<pre style='background: #f5f5f5; padding: 10px;'>";
echo htmlspecialchars($ascii_result);
echo "</pre>";

echo "<h2>完整数据与结果对照表</h2>";
echo "<div style='background: #f8f9fa; padding: 15px; border: 1px solid #dee2e6;'>";
echo "<table border='1' cellpadding='8' style='border-collapse: collapse; width: 100%;'>";
echo "<tr style='background: #e9ecef;'>";
echo "<th>节点数据</th><th>递归层级</th><th>传入的myid</th><th>传入的adds</th><th>生成的spacer</th><th>最终输出</th>";
echo "</tr>";

$levels = array(
    array('data' => 'A(1) parentid=0', 'level' => '1', 'myid' => '父查找: 0', 'adds' => "''", 'spacer' => "'└'", 'output' => '└A'),
    array('data' => 'B(2) parentid=1', 'level' => '2', 'myid' => '父查找: 1', 'adds' => "'&nbsp;'", 'spacer' => "'&nbsp;├'", 'output' => '&nbsp;├B'),
    array('data' => 'D(4) parentid=2', 'level' => '3', 'myid' => '父查找: 2', 'adds' => "'&nbsp;│&nbsp;'", 'spacer' => "'&nbsp;│&nbsp;└'", 'output' => '&nbsp;│&nbsp;└D'),
    array('data' => 'C(3) parentid=1', 'level' => '2', 'myid' => '父查找: 1', 'adds' => "'&nbsp;'", 'spacer' => "'&nbsp;└'", 'output' => '&nbsp;└C')
);

foreach($levels as $level) {
    echo "<tr>";
    echo "<td>{$level['data']}</td>";
    echo "<td>Level {$level['level']}</td>";
    echo "<td>{$level['myid']}</td>";
    echo "<td>{$level['adds']}</td>";
    echo "<td>{$level['spacer']}</td>";
    echo "<td style='font-family: monospace;'>" . htmlspecialchars($level['output']) . "</td>";
    echo "</tr>";
}
echo "</table>";

echo "<h3>数据流程总结</h3>";
echo "<ol>";
echo "<li><strong>数据输入:</strong> 包含id、parentid、name的二维数组</li>";
echo "<li><strong>递归查找:</strong> 通过get_child()方法根据parentid查找子节点</li>";
echo "<li><strong>参数传递:</strong> myid和adds在递归中变化，模板参数保持不变</li>";
echo "<li><strong>格式生成:</strong> 根据层级和位置生成相应的连接符和缩进</li>";
echo "<li><strong>输出结果:</strong> 最终生成具有树形结构的HTML</li>";
echo "</ol>";
echo "</div>";

?>
