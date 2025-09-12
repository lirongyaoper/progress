<?php
/**
 * Tree类核心递归逻辑简化版
 * 专门用于理解递归传参的核心机制
 */

// 简化的数据
$data = array(
    1 => array('id'=>'1','parentid'=>0,'name'=>'A'),
    2 => array('id'=>'2','parentid'=>1,'name'=>'B'),
    3 => array('id'=>'3','parentid'=>2,'name'=>'C')
);

echo "<h1>Tree类递归传参核心机制</h1>";

echo "<h2>原始数据展示</h2>";
echo "<div style='background: #f8f9fa; padding: 15px; border: 1px solid #dee2e6; margin: 10px 0;'>";
echo "<h3>简化测试数据</h3>";
echo "<pre style='background: #ffffff; padding: 10px; border: 1px solid #ddd;'>";
echo "简化数据结构:\n";
print_r($data);
echo "</pre>";

echo "<h3>数据关系表</h3>";
echo "<table border='1' cellpadding='5' style='border-collapse: collapse; margin: 10px 0;'>";
echo "<tr style='background: #e9ecef;'>";
echo "<th>ID</th><th>父节点ID (parentid)</th><th>节点名称</th><th>层级</th><th>说明</th>";
echo "</tr>";
foreach($data as $item) {
    $level = '';
    $desc = '';
    if($item['parentid'] == 0) {
        $level = 'Level 1';
        $desc = '根节点';
    } elseif($item['parentid'] == 1) {
        $level = 'Level 2';
        $desc = 'A的子节点';
    } elseif($item['parentid'] == 2) {
        $level = 'Level 3';
        $desc = 'B的子节点';
    }
    echo "<tr>";
    echo "<td>{$item['id']}</td>";
    echo "<td>{$item['parentid']}</td>";
    echo "<td>{$item['name']}</td>";
    echo "<td>{$level}</td>";
    echo "<td>{$desc}</td>";
    echo "</tr>";
}
echo "</table>";
echo "</div>";

echo "<h2>树形结构可视化</h2>";
echo "<div style='background: #e8f5e9; padding: 15px; border-left: 4px solid #4caf50;'>";
echo "<pre style='font-size: 14px; line-height: 1.4;'>";
echo "树形结构图:\n";
echo "A(1) - parentid=0\n";
echo "└─B(2) - parentid=1\n";
echo "   └─C(3) - parentid=2\n";
echo "</pre>";

echo "<h3>父子关系链</h3>";
echo "<ul>";
echo "<li><strong>A(1)</strong>: 根节点，parentid=0，有1个子节点 B(2)</li>";
echo "<li><strong>B(2)</strong>: A的子节点，parentid=1，有1个子节点 C(3)</li>";
echo "<li><strong>C(3)</strong>: B的子节点，parentid=2，没有子节点（叶子节点）</li>";
echo "</ul>";
echo "</div>";

echo "<h2>简化查找机制演示</h2>";
echo "<div style='background: #fff3e0; padding: 15px; border-left: 4px solid #ff9800; margin: 10px 0;'>";
echo "<h3>模拟get_child()查找过程</h3>";

// 模拟查找函数
function simple_get_child($data, $parent_id) {
    $children = array();
    foreach($data as $item) {
        if($item['parentid'] == $parent_id) {
            $children[] = $item;
        }
    }
    return $children;
}

// 演示查找过程
$search_ids = array(0, 1, 2, 3);
foreach($search_ids as $pid) {
    $children = simple_get_child($data, $pid);
    echo "<strong>查找 parentid = {$pid} 的子节点:</strong> ";
    if(!empty($children)) {
        $names = array();
        foreach($children as $child) {
            $names[] = "{$child['name']}({$child['id']})";
        }
        echo "找到 " . implode(', ', $names);
    } else {
        echo "没有找到子节点";
    }
    echo "<br>";
}

echo "<br><strong>这就是递归的基础：</strong><br>";
echo "1. 查找 parentid=0 → 找到A(1) → 递归查找A的子节点<br>";
echo "2. 查找 parentid=1 → 找到B(2) → 递归查找B的子节点<br>";
echo "3. 查找 parentid=2 → 找到C(3) → 递归查找C的子节点<br>";
echo "4. 查找 parentid=3 → 没有找到 → 递归终止<br>";
echo "</div>";

// 创建简化版递归函数，专门用于演示传参
function simple_get_tree($data, $myid, $prefix = '', $level = 0) {
    // 显示当前调用信息
    $indent = str_repeat("  ", $level);
    echo "{$indent}<strong>📞 调用 Level {$level}:</strong> myid={$myid}, prefix='{$prefix}'<br>";
    
    // 查找子节点
    $children = array();
    foreach($data as $item) {
        if($item['parentid'] == $myid) {
            $children[] = $item;
        }
    }
    
    if(empty($children)) {
        echo "{$indent}❌ 没有找到子节点，递归终止<br><br>";
        return;
    }
    
    echo "{$indent}✅ 找到 " . count($children) . " 个子节点<br>";
    
    $total = count($children);
    for($i = 0; $i < $total; $i++) {
        $child = $children[$i];
        $is_last = ($i == $total - 1);
        
        // 选择连接符
        $connector = $is_last ? '└─' : '├─';
        
        // 生成当前节点的显示
        $current_display = $prefix . $connector . $child['name'];
        echo "{$indent}🔸 处理节点 {$child['id']}: {$current_display}<br>";
        
        // 计算传递给下一级的前缀
        $next_prefix = $prefix . ($is_last ? '   ' : '│  ');
        echo "{$indent}📤 下级前缀: '{$next_prefix}'<br>";
        
        // 检查是否需要递归
        $has_children = false;
        foreach($data as $item) {
            if($item['parentid'] == $child['id']) {
                $has_children = true;
                break;
            }
        }
        
        if($has_children) {
            echo "{$indent}🔄 节点 {$child['id']} 有子节点，开始递归...<br>";
            simple_get_tree($data, $child['id'], $next_prefix, $level + 1);
        } else {
            echo "{$indent}✋ 节点 {$child['id']} 无子节点<br>";
        }
        echo "<br>";
    }
    
    echo "{$indent}🏁 Level {$level} 结束<br>";
}

echo "<h2>🎯 递归调用演示</h2>";
echo "<div style='background: #f8f9fa; padding: 15px; border: 1px solid #dee2e6; font-family: monospace;'>";
simple_get_tree($data, 0);
echo "</div>";

echo "<h2>📚 核心传参机制解析</h2>";
echo "<div style='background: #e7f3ff; padding: 15px; border-left: 4px solid #0066cc;'>";

echo "<h3>1. 参数的含义</h3>";
echo "<ul>";
echo "<li><code>myid</code>: 当前要查找子节点的父节点ID</li>";
echo "<li><code>prefix</code>: 累积的前缀字符串，用于显示层级关系</li>";
echo "</ul>";

echo "<h3>2. 递归调用时参数如何变化</h3>";
echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
echo "<tr style='background: #f0f0f0;'>";
echo "<th>调用层级</th><th>myid (输入)</th><th>prefix (输入)</th><th>找到的子节点</th><th>传给下级的myid</th><th>传给下级的prefix</th>";
echo "</tr>";

echo "<tr>";
echo "<td>Level 0</td>";
echo "<td>0</td>";
echo "<td>''</td>";
echo "<td>A(1)</td>";
echo "<td>1</td>";
echo "<td>'   '</td>";
echo "</tr>";

echo "<tr>";
echo "<td>Level 1</td>";
echo "<td>1</td>";
echo "<td>'   '</td>";
echo "<td>B(2)</td>";
echo "<td>2</td>";
echo "<td>'      '</td>";
echo "</tr>";

echo "<tr>";
echo "<td>Level 2</td>";
echo "<td>2</td>";
echo "<td>'      '</td>";
echo "<td>C(3)</td>";
echo "<td>3</td>";
echo "<td>'         '</td>";
echo "</tr>";

echo "<tr>";
echo "<td>Level 3</td>";
echo "<td>3</td>";
echo "<td>'         '</td>";
echo "<td>无</td>";
echo "<td>-</td>";
echo "<td>-</td>";
echo "</tr>";

echo "</table>";

echo "<h3>3. prefix累积规律</h3>";
echo "<ul>";
echo "<li><strong>如果当前节点是最后一个子节点:</strong> 新prefix = 旧prefix + '   ' (3个空格)</li>";
echo "<li><strong>如果当前节点不是最后一个:</strong> 新prefix = 旧prefix + '│  ' (竖线+2个空格)</li>";
echo "</ul>";

echo "<h3>4. 为什么要这样传参？</h3>";
echo "<ol>";
echo "<li><strong>myid的递增:</strong> 每层递归都要查找当前节点的子节点，所以myid变为当前节点的id</li>";
echo "<li><strong>prefix的累积:</strong> 为了在显示时保持树形结构的缩进和连线效果</li>";
echo "<li><strong>连接符的选择:</strong> 根据是否为最后一个子节点决定使用 └─ 还是 ├─</li>";
echo "</ol>";

echo "</div>";

echo "<h2>🎨 对比原版get_tree方法</h2>";
echo "<div style='background: #fff9e6; padding: 15px; border-left: 4px solid #ff9900;'>";
echo "<h3>原版get_tree中的关键变量对应关系:</h3>";
echo "<ul>";
echo "<li><code>myid</code> ↔ 我们的 myid (完全相同)</li>";
echo "<li><code>adds</code> ↔ 我们的 prefix (功能相同，都是累积前缀)</li>";
echo "<li><code>j</code> ↔ 我们的 connector (├─ 或 └─)</li>";
echo "<li><code>k</code> ↔ 我们累积逻辑中的 '│  ' 或 '   '</li>";
echo "<li><code>nbsp</code> ↔ 我们的空格字符</li>";
echo "</ul>";

echo "<h3>原版递归调用语句解析:</h3>";
echo "<pre style='background: #f5f5f5; padding: 10px;'>";
echo "\$this->get_tree(\$id, \$str, \$sid, \$adds.\$k.\$nbsp, \$str_group);\n";
echo "                 │     │     │     │\n";
echo "                 │     │     │     └── 新的prefix = 旧prefix + k + nbsp\n";
echo "                 │     │     └────── sid不变，选中状态传递\n";
echo "                 │     └──────────── str不变，模板传递\n";
echo "                 └────────────────── 新的myid = 当前节点id";
echo "</pre>";
echo "</div>";

echo "<h2>实际Tree类对比测试</h2>";
echo "<div style='background: #f0f8ff; padding: 15px; border: 1px solid #0066cc; margin: 10px 0;'>";
echo "<h3>使用真实Tree类生成相同结果</h3>";

// 包含原始tree类并测试
require_once 'tree.class.php';
$real_tree = new tree();
$real_tree->init($data);
$real_tree->icon = array('│','├','└');
$real_tree->nbsp = " ";

echo "<strong>真实Tree类输出:</strong><br>";
echo "<pre style='background: #ffffff; padding: 10px; border: 1px solid #ddd;'>";
$real_result = $real_tree->get_tree(0, "\$spacer\$name\n");
echo htmlspecialchars($real_result);
echo "</pre>";

echo "<h3>参数使用对比</h3>";
echo "<table border='1' cellpadding='5' style='border-collapse: collapse; width: 100%;'>";
echo "<tr style='background: #e9ecef;'>";
echo "<th>功能</th><th>我们的简化版</th><th>原版Tree类</th><th>说明</th>";
echo "</tr>";
echo "<tr>";
echo "<td>父节点查找参数</td><td>myid</td><td>myid</td><td>完全相同，都是要查找子节点的父ID</td>";
echo "</tr>";
echo "<tr>";
echo "<td>前缀累积参数</td><td>prefix</td><td>adds</td><td>功能相同，都用于累积层级前缀</td>";
echo "</tr>";
echo "<tr>";
echo "<td>连接符</td><td>connector ('└─'或'├─')</td><td>j (icon[1]或icon[2])</td><td>都用于表示树形连接</td>";
echo "</tr>";
echo "<tr>";
echo "<td>递归传递的前缀</td><td>prefix + ('   '或'│  ')</td><td>adds + k + nbsp</td><td>累积方式相同</td>";
echo "</tr>";
echo "<tr>";
echo "<td>HTML模板</td><td>直接输出</td><td>str参数 + eval()</td><td>原版支持模板替换</td>";
echo "</tr>";
echo "</table>";
echo "</div>";

echo "<h2>完整数据流程图</h2>";
echo "<div style='background: #fff9e6; padding: 15px; border-left: 4px solid #ff9900;'>";
echo "<pre style='font-family: monospace; line-height: 1.6;'>";
echo "数据输入 → 递归处理 → 格式生成 → 最终输出\n";
echo "    ↓           ↓           ↓           ↓\n";
echo "数组结构    查找子节点    计算前缀    树形显示\n";
echo "    │           │           │           │\n";
echo "    │      get_child()      │           │\n";
echo "    │      判断层级         │           │\n";
echo "    │      递归调用      spacer +      │\n";
echo "    │      传递参数      name         │\n";
echo "    │                                  │\n";
echo "    └─────────────────── HTML输出 ←───┘\n";
echo "</pre>";

echo "<h3>核心理解要点</h3>";
echo "<ol>";
echo "<li><strong>数据驱动:</strong> 通过parentid建立父子关系</li>";
echo "<li><strong>递归查找:</strong> 每层都查找当前节点的子节点</li>";
echo "<li><strong>参数传递:</strong> myid变化，prefix累积，其他保持</li>";
echo "<li><strong>格式控制:</strong> 通过前缀累积实现树形缩进</li>";
echo "<li><strong>终止条件:</strong> 没有子节点时递归结束</li>";
echo "</ol>";
echo "</div>";

?>
