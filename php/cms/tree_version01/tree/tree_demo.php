<?php
/**
 * Tree类完整使用示例
 * 为PHP初学者准备的详细演示
 */

// 引入tree类（假设已经包含）
// require_once 'tree.class.php';

// 模拟tree类的简化版本用于演示
class tree {
    public $arr = array();
    public $icon = array('│','├','└');
    public $nbsp = "&nbsp;";
    public $ret = '';
    private $_cache = array();

    public function clearCache() {
        $this->_cache = array();
    }

    public function init($arr=array()){
        $this->arr = $arr;
        $this->ret = '';
        $this->_cache = array();
        return is_array($arr);
    }

    public function get_child($myid){
        if(isset($this->_cache['child_' . $myid])) {
            return $this->_cache['child_' . $myid];
        }
        
        $newarr = array();
        if(is_array($this->arr)){
            foreach($this->arr as $id => $a){
                if($a['parentid'] == $myid) $newarr[$id] = $a;
            }
        }
        
        $result = $newarr ? $newarr : false;
        $this->_cache['child_' . $myid] = $result;
        return $result;
    }

    // 简化版的get_tree方法
    public function get_tree($myid, $str, $sid = 0, $adds = '', $str_group = ''){
        $number=1;
        $child = $this->get_child($myid);
        if(is_array($child)){
            $total = count($child);
            foreach($child as $id=>$value){
                $j=$k='';
                if($number==$total){
                    $j .= $this->icon[2];
                }else{
                    $j .= $this->icon[1];
                    $k = $adds ? $this->icon[0] : '';
                }
                $spacer = $adds ? $adds.$j : '';
                
                $selected = '';
                if(is_array($sid)){
                    $selected = in_array($id, $sid) ? 'selected' : '';
                }else{
                    $selected = $id==$sid ? 'selected' : '';
                }
                
                // 简化的模板解析
                $nstr = str_replace(array('\$id', '\$name', '\$spacer', '\$selected'), 
                                  array($value['id'], $value['name'], $spacer, $selected), $str);
                $this->ret .= $nstr;
                
                $this->get_tree($value['id'], $str, $sid, $adds.$k.$this->nbsp,$str_group);
                $number++;
            }
        }
        return $this->ret;
    }
}

echo "<h1>Tree类学习示例</h1>\n";

// ===============================
// 示例1：基本数据结构
// ===============================
echo "<h2>示例1：网站栏目结构</h2>\n";

$data = array(
    1 => array('id'=>1, 'parentid'=>0, 'name'=>'首页'),
    2 => array('id'=>2, 'parentid'=>0, 'name'=>'新闻中心'), 
    3 => array('id'=>3, 'parentid'=>0, 'name'=>'产品中心'),
    4 => array('id'=>4, 'parentid'=>2, 'name'=>'国内新闻'),
    5 => array('id'=>5, 'parentid'=>2, 'name'=>'国际新闻'),
    6 => array('id'=>6, 'parentid'=>3, 'name'=>'软件产品'),
    7 => array('id'=>7, 'parentid'=>3, 'name'=>'硬件产品'),
    8 => array('id'=>8, 'parentid'=>4, 'name'=>'政治新闻'),
    9 => array('id'=>9, 'parentid'=>4, 'name'=>'经济新闻'),
);

echo "<h3>原始数据结构：</h3>\n";
echo "<pre>\n";
print_r($data);
echo "</pre>\n";

// ===============================
// 示例2：初始化tree对象
// ===============================
echo "<h2>示例2：初始化Tree对象</h2>\n";

$tree = new tree();
$tree->init($data);

echo "数据初始化完成，数组包含 " . count($tree->arr) . " 个节点\n\n";

// ===============================
// 示例3：获取子节点
// ===============================
echo "<h2>示例3：获取子节点演示</h2>\n";

echo "<h3>获取根节点(ID=0)的子节点：</h3>\n";
$children = $tree->get_child(0);
if($children) {
    foreach($children as $id => $item) {
        echo "ID: {$item['id']}, 名称: {$item['name']}\n";
    }
} else {
    echo "没有找到子节点\n";
}

echo "\n<h3>获取新闻中心(ID=2)的子节点：</h3>\n";
$children = $tree->get_child(2);
if($children) {
    foreach($children as $id => $item) {
        echo "ID: {$item['id']}, 名称: {$item['name']}\n";
    }
} else {
    echo "没有找到子节点\n";
}

// ===============================
// 示例4：生成HTML下拉菜单
// ===============================
echo "\n<h2>示例4：生成HTML下拉菜单</h2>\n";

$tree->ret = ''; // 清空结果
$html_template = "<option value='\$id' \$selected>\$spacer\$name</option>\n";
$tree_html = $tree->get_tree(0, $html_template, 4); // 选中ID为4的项

echo "<h3>生成的HTML代码：</h3>\n";
echo "<pre>\n";
echo htmlspecialchars($tree_html);
echo "</pre>\n";

echo "<h3>实际显示效果：</h3>\n";
echo "<select>\n";
echo $tree_html;
echo "</select>\n";

// ===============================
// 示例5：生成纯文本树形结构
// ===============================
echo "\n<h2>示例5：生成纯文本树形结构</h2>\n";

$tree->ret = ''; // 清空结果
$text_template = "\$spacer\$name\n";
$tree_text = $tree->get_tree(0, $text_template);

echo "<h3>树形文本结构：</h3>\n";
echo "<pre>\n";
echo $tree_text;
echo "</pre>\n";

// ===============================
// 示例6：缓存性能演示
// ===============================
echo "\n<h2>示例6：缓存性能演示</h2>\n";

// 清空缓存（需要添加公共方法）
$tree->clearCache();
echo "缓存已清空\n";

// 第一次查询
$start_time = microtime(true);
$children1 = $tree->get_child(2);
$time1 = microtime(true) - $start_time;

// 第二次查询（使用缓存）
$start_time = microtime(true);
$children2 = $tree->get_child(2);
$time2 = microtime(true) - $start_time;

echo "第一次查询耗时: " . number_format($time1 * 1000000, 2) . " 微秒\n";
echo "第二次查询耗时: " . number_format($time2 * 1000000, 2) . " 微秒（使用缓存）\n";
echo "性能提升: " . number_format(($time1 - $time2) / $time1 * 100, 2) . "%\n";

// ===============================
// 示例7：错误处理演示
// ===============================
echo "\n<h2>示例7：错误处理演示</h2>\n";

// 查询不存在的节点
$non_exist = $tree->get_child(999);
if($non_exist === false) {
    echo "正确处理：节点999不存在，返回false\n";
}

// 空数据初始化
$empty_tree = new tree();
$empty_tree->init(array());
$empty_result = $empty_tree->get_child(0);
if($empty_result === false) {
    echo "正确处理：空数据集，返回false\n";
}

echo "\n<h2>学习总结</h2>\n";
echo "<ul>\n";
echo "<li>Tree类用于处理层次化数据结构</li>\n";
echo "<li>核心是parentid关系建立父子层级</li>\n";
echo "<li>get_child()方法是基础，用于获取子节点</li>\n";
echo "<li>get_tree()方法用于生成可视化树形结构</li>\n";
echo "<li>支持模板自定义，可生成HTML、文本等格式</li>\n";
echo "<li>内置缓存机制提升查询性能</li>\n";
echo "<li>安全的模板解析，避免代码注入风险</li>\n";
echo "</ul>\n";

echo "\n<p><strong>建议练习：</strong></p>\n";
echo "<ol>\n";
echo "<li>尝试创建自己的分类数据</li>\n";
echo "<li>修改模板生成不同格式的输出</li>\n";
echo "<li>测试多级深度的树形结构</li>\n";
echo "<li>理解递归算法的工作原理</li>\n";
echo "</ol>\n";

?>
