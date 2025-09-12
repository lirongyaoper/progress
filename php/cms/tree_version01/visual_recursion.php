<?php
/**
 * 可视化递归调用栈演示
 */

require_once 'tree.class.php';

// 测试数据
$data = array(
    1 => array('id'=>'1','parentid'=>0,'name'=>'根节点'),
    2 => array('id'=>'2','parentid'=>1,'name'=>'分支A'),
    3 => array('id'=>'3','parentid'=>1,'name'=>'分支B'),
    4 => array('id'=>'4','parentid'=>2,'name'=>'叶子A1'),
    5 => array('id'=>'5','parentid'=>2,'name'=>'叶子A2')
);

echo "<!DOCTYPE html>";
echo "<html><head>";
echo "<title>Tree类递归可视化</title>";
echo "<style>";
echo "body { font-family: Arial, sans-serif; margin: 20px; }";
echo ".call-stack { border: 2px solid #333; margin: 10px; padding: 10px; }";
echo ".level-0 { background: #ffebee; border-color: #f44336; }";
echo ".level-1 { background: #e8f5e9; border-color: #4caf50; }";
echo ".level-2 { background: #e3f2fd; border-color: #2196f3; }";
echo ".level-3 { background: #fff3e0; border-color: #ff9800; }";
echo ".params { background: #f5f5f5; padding: 5px; margin: 5px 0; }";
echo ".result { background: #e8f5e9; padding: 5px; margin: 5px 0; }";
echo ".recursion-arrow { color: red; font-weight: bold; }";
echo "table { border-collapse: collapse; width: 100%; margin: 10px 0; }";
echo "th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }";
echo "th { background-color: #f2f2f2; }";
echo "</style>";
echo "</head><body>";

echo "<h1>Tree类递归调用可视化演示</h1>";

echo "<h2>原始数据展示</h2>";
echo "<div style='background: #f8f9fa; padding: 15px; border: 1px solid #dee2e6; margin: 10px 0;'>";
echo "<h3>测试数据结构</h3>";
echo "<pre style='background: #ffffff; padding: 10px; border: 1px solid #ddd;'>";
echo "可视化演示数据:\n";
print_r($data);
echo "</pre>";

echo "<h3>数据关系分析表</h3>";
echo "<table border='1' cellpadding='5' style='border-collapse: collapse; margin: 10px 0; width: 100%;'>";
echo "<tr style='background: #e9ecef;'>";
echo "<th>节点ID</th><th>父节点ID</th><th>节点名称</th><th>层级深度</th><th>节点角色</th>";
echo "</tr>";

$node_info = array(
    1 => array('level' => 1, 'role' => '根节点'),
    2 => array('level' => 2, 'role' => '分支节点'),
    3 => array('level' => 2, 'role' => '叶子节点'),
    4 => array('level' => 3, 'role' => '叶子节点'),
    5 => array('level' => 3, 'role' => '叶子节点')
);

foreach($data as $item) {
    $info = $node_info[$item['id']];
    echo "<tr>";
    echo "<td>{$item['id']}</td>";
    echo "<td>{$item['parentid']}</td>";
    echo "<td>{$item['name']}</td>";
    echo "<td>Level {$info['level']}</td>";
    echo "<td>{$info['role']}</td>";
    echo "</tr>";
}
echo "</table>";
echo "</div>";

echo "<h2>树形结构可视化</h2>";
echo "<div style='background: #e8f5e9; padding: 15px; border-left: 4px solid #4caf50;'>";
echo "<pre style='font-size: 14px; line-height: 1.4;'>";
echo "树形结构图:\n";
echo "根节点(1)\n";
echo "├─分支A(2)\n";
echo "│  ├─叶子A1(4)\n";
echo "│  └─叶子A2(5)\n";
echo "└─分支B(3)\n";
echo "</pre>";

echo "<h3>递归层级分析</h3>";
echo "<ul>";
echo "<li><strong>Level 1:</strong> 根节点(1) - parentid=0，有2个子节点</li>";
echo "<li><strong>Level 2:</strong> 分支A(2), 分支B(3) - 都是根节点的子节点</li>";
echo "<li><strong>Level 3:</strong> 叶子A1(4), 叶子A2(5) - 都是分支A的子节点</li>";
echo "</ul>";
echo "</div>";

// 创建可视化的递归追踪类
class VisualTree extends tree {
    private $call_level = 0;
    private $call_history = array();
    
    public function get_tree_visual($myid, $str, $sid = 0, $adds = '', $str_group = '') {
        $this->call_level++;
        $current_call = array(
            'level' => $this->call_level,
            'myid' => $myid,
            'adds' => $adds,
            'children_found' => array(),
            'results' => array()
        );
        
        echo "<div class='call-stack level-" . ($this->call_level % 4) . "'>";
        echo "<h3>📞 递归调用 Level {$this->call_level}</h3>";
        
        echo "<div class='params'>";
        echo "<strong>输入参数:</strong><br>";
        echo "• myid (父节点ID): <code>{$myid}</code><br>";
        echo "• adds (累积前缀): <code>'" . htmlspecialchars($adds) . "'</code><br>";
        echo "• 查找条件: parentid = {$myid}<br>";
        echo "</div>";
        
        $number = 1;
        $child = $this->get_child($myid);
        
        if(is_array($child) && count($child) > 0) {
            echo "<div style='background: #fff9c4; padding: 5px; margin: 5px 0;'>";
            echo "<strong>🔍 找到 " . count($child) . " 个子节点:</strong><br>";
            
            echo "<table>";
            echo "<tr><th>序号</th><th>ID</th><th>名称</th><th>处理顺序</th><th>图标选择</th><th>新的adds</th></tr>";
            
            $total = count($child);
            foreach($child as $id => $value) {
                $current_call['children_found'][] = $id;
                
                $j = $k = '';
                $icon_reason = '';
                if($number == $total) {
                    $j .= $this->icon[2]; // └
                    $icon_reason = "最后一个子节点，使用 └";
                } else {
                    $j .= $this->icon[1]; // ├
                    $k = $adds ? $this->icon[0] : ''; // │
                    $icon_reason = "非最后子节点，使用 ├";
                }
                
                $spacer = $adds ? $adds.$j : '';
                $new_adds = $adds.$k.$this->nbsp;
                
                echo "<tr>";
                echo "<td>{$number}/{$total}</td>";
                echo "<td>{$id}</td>";
                echo "<td>{$value['name']}</td>";
                echo "<td>{$icon_reason}</td>";
                echo "<td>{$j}</td>";
                echo "<td>'" . htmlspecialchars($new_adds) . "'</td>";
                echo "</tr>";
                
                $number++;
            }
            echo "</table>";
            echo "</div>";
            
            // 处理每个子节点
            $number = 1;
            foreach($child as $id => $value) {
                $j = $k = '';
                if($number == $total) {
                    $j .= $this->icon[2];
                } else {
                    $j .= $this->icon[1];
                    $k = $adds ? $this->icon[0] : '';
                }
                
                $spacer = $adds ? $adds.$j : '';
                
                $selected = '';
                if(is_array($sid)) {
                    $selected = in_array($id, $sid) ? 'selected' : '';
                } else {
                    $selected = $id == $sid ? 'selected' : '';
                }
                
                if(!is_array($value)) continue;
                extract($value);
                $parentid == 0 && $str_group ? eval("\$nstr = \"$str_group\";") : eval("\$nstr = \"$str\";");
                $this->ret .= $nstr;
                
                echo "<div class='result'>";
                echo "<strong>✅ 生成节点 #{$id} ({$value['name']}):</strong><br>";
                echo "HTML: " . htmlspecialchars($nstr) . "<br>";
                echo "</div>";
                
                $current_call['results'][] = $nstr;
                
                // 检查递归
                if($this->get_child($id)) {
                    $new_adds = $adds.$k.$this->nbsp;
                    echo "<div class='recursion-arrow'>";
                    echo "🔄 节点 #{$id} 有子节点，递归调用!<br>";
                    echo "传递参数: myid={$id}, adds='" . htmlspecialchars($new_adds) . "'<br>";
                    echo "</div>";
                    
                    $this->get_tree_visual($id, $str, $sid, $new_adds, $str_group);
                }
                
                $number++;
            }
        } else {
            echo "<div style='background: #f0f0f0; padding: 5px; margin: 5px 0;'>";
            echo "❌ 没有找到子节点 (parentid = {$myid})";
            echo "</div>";
        }
        
        echo "<div style='background: #e1f5fe; padding: 5px; margin: 5px 0;'>";
        echo "<strong>🏁 Level {$this->call_level} 调用结束</strong>";
        echo "</div>";
        echo "</div>";
        
        $this->call_history[] = $current_call;
        $this->call_level--;
        
        return $this->ret;
    }
}

// 运行可视化演示
$visualTree = new VisualTree();
$visualTree->init($data);
$visualTree->icon = array('│','├','└');
$visualTree->nbsp = "&nbsp;&nbsp;";

echo "<h2>递归调用过程追踪</h2>";
$result = $visualTree->get_tree_visual(0, "<div>\$spacer\$name</div>");

echo "<h2>最终生成结果</h2>";
echo "<div style='border: 2px solid #4caf50; padding: 10px; background: #f9f9f9;'>";
echo $result;
echo "</div>";

echo "<h2>核心递归原理总结</h2>";
echo "<div style='background: #fff3e0; padding: 15px; border-radius: 5px;'>";
echo "<h3>🔑 关键理解点:</h3>";
echo "<ol>";
echo "<li><strong>递归触发条件:</strong> 当前节点有子节点时</li>";
echo "<li><strong>参数传递规律:</strong>";
echo "   <ul>";
echo "   <li>myid: 传递当前节点的ID，作为下一级的父节点ID</li>";
echo "   <li>adds: 累积前缀，实现层级缩进效果</li>";
echo "   </ul>";
echo "</li>";
echo "<li><strong>adds累积公式:</strong> 新adds = 旧adds + k + nbsp";
echo "   <ul>";
echo "   <li>k = '│' (当前节点不是最后一个且adds不为空)</li>";
echo "   <li>k = '' (当前节点是最后一个或adds为空)</li>";
echo "   </ul>";
echo "</li>";
echo "<li><strong>递归终止条件:</strong> 节点没有子节点</li>";
echo "</ol>";
echo "</div>";

echo "</body></html>";
?>
