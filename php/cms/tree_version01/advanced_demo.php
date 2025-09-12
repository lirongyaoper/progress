<?php
/**
 * Tree类高级演示 - 复杂数据结构
 */

require_once 'tree.class.php';
require_once 'data.php';

echo "<!DOCTYPE html>";
echo "<html><head>";
echo "<meta charset='UTF-8'>";
echo "<title>Tree类高级演示 - 复杂数据结构</title>";
echo "<style>";
echo "body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }";
echo "table { border-collapse: collapse; width: 100%; margin: 10px 0; }";
echo "th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }";
echo "th { background-color: #f2f2f2; }";
echo ".highlight { background-color: #fff3cd; }";
echo "</style>";
echo "</head><body>";

echo "<h1>🏢 Tree类高级演示 - 复杂组织架构</h1>";

echo "<h2>📊 复杂数据结构展示</h2>";
echo "<div style='background: #f8f9fa; padding: 15px; border: 1px solid #dee2e6; margin: 10px 0;'>";
echo "<h3>组织架构数据</h3>";
echo "<pre style='background: #ffffff; padding: 10px; border: 1px solid #ddd; max-height: 300px; overflow-y: auto;'>";
echo "复杂组织架构数据:\n";
print_r($complex_data);
echo "</pre>";

echo "<h3>组织层级分析表</h3>";
echo "<table>";
echo "<tr>";
echo "<th>部门ID</th><th>上级部门</th><th>部门名称</th><th>层级</th><th>下属部门数</th><th>部门类型</th>";
echo "</tr>";

// 创建tree实例分析数据
$tree_analyzer = new tree();
$tree_analyzer->init($complex_data);

foreach($complex_data as $item) {
    $children = $tree_analyzer->get_child($item['id']);
    $child_count = $children ? count($children) : 0;
    
    // 计算层级
    $level = 1;
    $current = $item;
    while($current['parentid'] != 0) {
        $level++;
        foreach($complex_data as $parent) {
            if($parent['id'] == $current['parentid']) {
                $current = $parent;
                break;
            }
        }
    }
    
    // 确定部门类型
    $type = '';
    if($item['parentid'] == 0) {
        $type = '总部';
    } elseif($child_count > 0) {
        $type = '管理部门';
    } else {
        $type = '执行部门';
    }
    
    $row_class = $level == 1 ? 'highlight' : '';
    echo "<tr class='{$row_class}'>";
    echo "<td>{$item['id']}</td>";
    echo "<td>{$item['parentid']}</td>";
    echo "<td>{$item['name']}</td>";
    echo "<td>Level {$level}</td>";
    echo "<td>{$child_count}</td>";
    echo "<td>{$type}</td>";
    echo "</tr>";
}
echo "</table>";
echo "</div>";

echo "<h2>🌳 组织架构树形图</h2>";
echo "<div style='background: #e8f5e9; padding: 15px; border-left: 4px solid #4caf50;'>";
echo "<h3>完整组织架构</h3>";

// 生成树形结构
$org_tree = new tree();
$org_tree->init($complex_data);
$org_tree->icon = array('│','├','└');
$org_tree->nbsp = "&nbsp;&nbsp;";

$tree_result = $org_tree->get_tree(0, "<div style='padding: 2px 0;'>\$spacer\$name</div>");
echo "<div style='background: white; padding: 10px; border: 1px solid #ddd; font-family: monospace;'>";
echo $tree_result;
echo "</div>";
echo "</div>";

echo "<h2>📈 递归深度分析</h2>";
echo "<div style='background: #fff3e0; padding: 15px; border-left: 4px solid #ff9800;'>";

// 分析递归深度
$max_depth = 0;
$depth_count = array();

foreach($complex_data as $item) {
    $depth = 1;
    $current = $item;
    while($current['parentid'] != 0) {
        $depth++;
        foreach($complex_data as $parent) {
            if($parent['id'] == $current['parentid']) {
                $current = $parent;
                break;
            }
        }
    }
    
    if($depth > $max_depth) $max_depth = $depth;
    
    if(!isset($depth_count[$depth])) {
        $depth_count[$depth] = 0;
    }
    $depth_count[$depth]++;
}

echo "<h3>层级统计</h3>";
echo "<table style='width: 50%;'>";
echo "<tr><th>层级</th><th>部门数量</th><th>说明</th></tr>";
for($i = 1; $i <= $max_depth; $i++) {
    $count = isset($depth_count[$i]) ? $depth_count[$i] : 0;
    $desc = '';
    switch($i) {
        case 1: $desc = '公司级别'; break;
        case 2: $desc = '部门级别'; break;
        case 3: $desc = '组级别'; break;
        case 4: $desc = '团队级别'; break;
        default: $desc = '子级别';
    }
    echo "<tr>";
    echo "<td>Level {$i}</td>";
    echo "<td>{$count}</td>";
    echo "<td>{$desc}</td>";
    echo "</tr>";
}
echo "</table>";

echo "<h3>递归调用次数分析</h3>";
echo "<p><strong>总层级深度:</strong> {$max_depth} 层</p>";
echo "<p><strong>最大递归调用次数:</strong> " . ($max_depth - 1) . " 次</p>";
echo "<p><strong>节点总数:</strong> " . count($complex_data) . " 个</p>";
echo "</div>";

echo "<h2>🔄 性能对比测试</h2>";
echo "<div style='background: #f0f8ff; padding: 15px; border: 1px solid #0066cc;'>";

// 性能测试
$start_time = microtime(true);
$iterations = 1000;

for($i = 0; $i < $iterations; $i++) {
    $perf_tree = new tree();
    $perf_tree->init($complex_data);
    $perf_tree->icon = array('│','├','└');
    $perf_tree->nbsp = "&nbsp;";
    $temp_result = $perf_tree->get_tree(0, "\$spacer\$name\n");
}

$end_time = microtime(true);
$execution_time = ($end_time - $start_time) * 1000; // 转换为毫秒

echo "<h3>性能测试结果</h3>";
echo "<table style='width: 60%;'>";
echo "<tr><th>测试项目</th><th>结果</th></tr>";
echo "<tr><td>数据节点数</td><td>" . count($complex_data) . " 个</td></tr>";
echo "<tr><td>最大层级深度</td><td>{$max_depth} 层</td></tr>";
echo "<tr><td>测试迭代次数</td><td>{$iterations} 次</td></tr>";
echo "<tr><td>总执行时间</td><td>" . number_format($execution_time, 2) . " 毫秒</td></tr>";
echo "<tr><td>平均单次执行</td><td>" . number_format($execution_time / $iterations, 4) . " 毫秒</td></tr>";
echo "</table>";

echo "<p><strong>性能结论:</strong> Tree类在处理 " . count($complex_data) . " 个节点的复杂层级结构时，";
echo "平均单次生成时间约为 " . number_format($execution_time / $iterations, 4) . " 毫秒，性能表现良好。</p>";
echo "</div>";

echo "<h2>🎯 学习要点总结</h2>";
echo "<div style='background: #e7f3ff; padding: 15px; border-left: 4px solid #0066cc;'>";
echo "<h3>复杂数据结构的处理特点</h3>";
echo "<ol>";
echo "<li><strong>多层级嵌套:</strong> 最深可达 {$max_depth} 层，递归调用自动处理</li>";
echo "<li><strong>分支复杂度:</strong> 一个父节点可以有多个子节点，tree类能正确处理</li>";
echo "<li><strong>动态层级:</strong> 不同分支的深度可以不同，递归会自然终止</li>";
echo "<li><strong>性能稳定:</strong> 即使数据量增大，递归机制仍然高效</li>";
echo "<li><strong>参数传递:</strong> 核心参数（myid、adds）在复杂结构中依然遵循相同规律</li>";
echo "</ol>";

echo "<h3>实际应用场景</h3>";
echo "<ul>";
echo "<li>🏢 <strong>组织架构管理:</strong> 如本例展示的公司部门结构</li>";
echo "<li>📁 <strong>文件目录树:</strong> 操作系统的文件夹层级结构</li>";
echo "<li>📋 <strong>分类管理:</strong> 电商网站的商品分类体系</li>";
echo "<li>🗺️ <strong>地理区域:</strong> 国家-省份-城市-区县的行政区划</li>";
echo "<li>📚 <strong>知识体系:</strong> 学科-章节-小节的教学内容组织</li>";
echo "</ul>";
echo "</div>";

echo "</body></html>";
?>
