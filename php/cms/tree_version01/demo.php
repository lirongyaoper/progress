<?php
/**
 * Tree类递归调用演示
 * 
 * 这个文件将详细展示tree类的递归调用过程，特别是传参机制
 */

require_once 'tree.class.php';

// 创建测试数据 - 一个更复杂的树形结构
$data = array(
    1 => array('id'=>'1','parentid'=>0,'name'=>'根目录'),
    2 => array('id'=>'2','parentid'=>0,'name'=>'系统管理'),
    3 => array('id'=>'3','parentid'=>1,'name'=>'用户管理'),
    4 => array('id'=>'4','parentid'=>1,'name'=>'权限管理'),
    5 => array('id'=>'5','parentid'=>2,'name'=>'系统设置'),
    6 => array('id'=>'6','parentid'=>3,'name'=>'添加用户'),
    7 => array('id'=>'7','parentid'=>3,'name'=>'编辑用户'),
    8 => array('id'=>'8','parentid'=>4,'name'=>'角色管理'),
    9 => array('id'=>'9','parentid'=>4,'name'=>'菜单管理'),
    10 => array('id'=>'10','parentid'=>6,'name'=>'基本信息'),
    11 => array('id'=>'11','parentid'=>6,'name'=>'扩展信息')
);

// 创建tree实例
$tree = new tree();
$tree->init($data);

// 自定义图标，更容易看清层级
$tree->icon = array('│','├','└');
$tree->nbsp = "&nbsp;&nbsp;";

echo "<h1>Tree类递归调用详解</h1>";

echo "<h2>1. 完整数据结构展示</h2>";
echo "<div style='background: #f8f9fa; padding: 15px; border: 1px solid #dee2e6; margin: 10px 0;'>";
echo "<h3>原始数组数据</h3>";
echo "<pre style='background: #ffffff; padding: 10px; border: 1px solid #ddd;'>";
echo "复杂测试数据:\n";
print_r($data);
echo "</pre>";

echo "<h3>数据关系详细表</h3>";
echo "<table border='1' cellpadding='5' style='border-collapse: collapse; margin: 10px 0; width: 100%;'>";
echo "<tr style='background: #e9ecef;'>";
echo "<th>ID</th><th>父节点ID</th><th>节点名称</th><th>层级</th><th>子节点</th><th>类型</th>";
echo "</tr>";

// 分析每个节点的子节点
$tree_temp = new tree();
$tree_temp->init($data);

foreach($data as $item) {
    $children = $tree_temp->get_child($item['id']);
    $child_names = array();
    if($children) {
        foreach($children as $child) {
            $child_names[] = $child['name'] . '(' . $child['id'] . ')';
        }
    }
    
    $level = '';
    $type = '';
    if($item['parentid'] == 0) {
        $level = 'Level 1';
        $type = '根节点';
    } else {
        // 计算层级
        $current = $item;
        $depth = 1;
        while($current['parentid'] != 0) {
            $depth++;
            foreach($data as $parent) {
                if($parent['id'] == $current['parentid']) {
                    $current = $parent;
                    break;
                }
            }
        }
        $level = 'Level ' . $depth;
        $type = empty($child_names) ? '叶子节点' : '分支节点';
    }
    
    echo "<tr>";
    echo "<td>{$item['id']}</td>";
    echo "<td>{$item['parentid']}</td>";
    echo "<td>{$item['name']}</td>";
    echo "<td>{$level}</td>";
    echo "<td>" . (empty($child_names) ? '无' : implode(', ', $child_names)) . "</td>";
    echo "<td>{$type}</td>";
    echo "</tr>";
}
echo "</table>";
echo "</div>";

echo "<h2>2. 树形结构图</h2>";
echo "<pre>";
echo "树形结构图:\n";
echo "根目录(1)\n";
echo "├─用户管理(3)\n";
echo "│  ├─添加用户(6)\n";
echo "│  │  ├─基本信息(10)\n";
echo "│  │  └─扩展信息(11)\n";
echo "│  └─编辑用户(7)\n";
echo "└─权限管理(4)\n";
echo "   ├─角色管理(8)\n";
echo "   └─菜单管理(9)\n";
echo "\n";
echo "系统管理(2)\n";
echo "└─系统设置(5)\n";
echo "</pre>";

// 创建一个修改版的tree类来追踪递归过程
class DebugTree extends tree {
    public $debug_level = 0;
    public $call_stack = array();
    
    public function get_tree_debug($myid, $str, $sid = 0, $adds = '', $str_group = '') {
        // 记录调用栈
        $this->call_stack[] = array(
            'level' => $this->debug_level,
            'myid' => $myid,
            'adds' => $adds,
            'str' => $str
        );
        
        echo "<div style='margin-left: " . ($this->debug_level * 20) . "px; border-left: 2px solid #ccc; padding: 5px;'>";
        echo "<strong>递归调用 Level {$this->debug_level}:</strong><br>";
        echo "参数 myid: {$myid}<br>";
        echo "参数 adds: '" . htmlspecialchars($adds) . "'<br>";
        echo "查找 parentid={$myid} 的子节点...<br>";
        
        $number = 1;
        $child = $this->get_child($myid);
        
        if(is_array($child)) {
            echo "找到子节点: ";
            foreach($child as $id => $value) {
                echo "#{$id}({$value['name']}) ";
            }
            echo "<br><br>";
            
            $total = count($child);
            foreach($child as $id => $value) {
                echo "<span style='color: blue;'>处理节点 #{$id} ({$value['name']})</span><br>";
                
                $j = $k = '';
                if($number == $total) {
                    $j .= $this->icon[2]; // └
                    echo "- 这是最后一个子节点，使用 '{$this->icon[2]}'<br>";
                } else {
                    $j .= $this->icon[1]; // ├
                    $k = $adds ? $this->icon[0] : ''; // │
                    echo "- 不是最后一个子节点，使用 '{$this->icon[1]}'<br>";
                    echo "- k = " . ($adds ? "'{$this->icon[0]}'" : "''") . "<br>";
                }
                
                $spacer = $adds ? $adds.$j : '';
                echo "- spacer = '$adds' + '$j' = '" . htmlspecialchars($spacer) . "'<br>";
                
                $selected = '';
                if(is_array($sid)) {
                    $selected = in_array($id, $sid) ? 'selected' : '';
                } else {
                    $selected = $id == $sid ? 'selected' : '';
                }
                
                if(!is_array($value)) return false;
                if(isset($value['str']) || isset($value['str_group'])) return false;
                
                extract($value);
                $parentid == 0 && $str_group ? eval("\$nstr = \"$str_group\";") : eval("\$nstr = \"$str\";");
                $this->ret .= $nstr;
                
                echo "- 生成HTML: " . htmlspecialchars($nstr) . "<br>";
                
                // 检查是否有子节点需要递归
                $has_children = $this->get_child($id);
                if($has_children) {
                    $new_adds = $adds.$k.$this->nbsp;
                    echo "<span style='color: red;'>→ 节点 #{$id} 有子节点，准备递归调用</span><br>";
                    echo "- 传递给下一级的 adds: '$adds' + '$k' + '{$this->nbsp}' = '" . htmlspecialchars($new_adds) . "'<br>";
                    
                    $this->debug_level++;
                    $this->get_tree_debug($id, $str, $sid, $new_adds, $str_group);
                    $this->debug_level--;
                } else {
                    echo "- 节点 #{$id} 没有子节点，不需要递归<br>";
                }
                
                echo "<br>";
                $number++;
            }
        } else {
            echo "没有找到子节点<br>";
        }
        
        echo "</div>";
        return $this->ret;
    }
}

echo "<h2>3. 递归调用过程详解</h2>";
echo "<p>我们来追踪 get_tree() 方法的递归调用过程：</p>";

// 创建调试版本的tree
$debugTree = new DebugTree();
$debugTree->init($data);
$debugTree->icon = array('│','├','└');
$debugTree->nbsp = "&nbsp;&nbsp;";

// 开始从根节点(parentid=0)生成树
$html_template = "<div style='padding-left: 10px;'>\$spacer\$name</div>";
echo "<h3>从根节点开始递归(parentid=0):</h3>";
$result = $debugTree->get_tree_debug(0, $html_template);

echo "<h2>4. 关键参数传递分析</h2>";
echo "<div style='background: #f5f5f5; padding: 10px; margin: 10px 0;'>";
echo "<h3>参数说明：</h3>";
echo "<ul>";
echo "<li><strong>\$myid</strong>: 当前要查找子节点的父ID</li>";
echo "<li><strong>\$str</strong>: HTML模板字符串</li>";
echo "<li><strong>\$sid</strong>: 选中的ID</li>";
echo "<li><strong>\$adds</strong>: 累积的前缀字符串（用于缩进和线条）</li>";
echo "<li><strong>\$str_group</strong>: 分组样式</li>";
echo "</ul>";

echo "<h3>递归传参的关键点：</h3>";
echo "<ol>";
echo "<li><strong>myid变化</strong>: 每次递归时，myid变为当前节点的id，用于查找该节点的子节点</li>";
echo "<li><strong>adds累积</strong>: adds参数会累积上级的缩进信息：<br>";
echo "   - 如果当前节点不是最后一个: adds = adds + '│' + '&nbsp;&nbsp;'<br>";
echo "   - 如果当前节点是最后一个: adds = adds + '' + '&nbsp;&nbsp;'</li>";
echo "<li><strong>层级深度</strong>: 通过adds的累积，实现了树形结构的可视化</li>";
echo "</ol>";
echo "</div>";

echo "<h2>5. 最终生成的HTML结果</h2>";
echo "<div style='border: 1px solid #ccc; padding: 10px; background: white;'>";
echo $result;
echo "</div>";

?>
