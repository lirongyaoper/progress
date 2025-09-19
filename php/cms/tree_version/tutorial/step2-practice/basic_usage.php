<?php
/**
 * basic_usage.php - Tree类基础使用示例
 * 
 * 这个文件演示了SimpleTree类的基本使用方法
 * 通过具体的例子帮助初学者理解每个方法的用途
 * 
 * 学习建议：
 * 1. 先运行这个文件，观察输出结果
 * 2. 尝试修改数据，看看结果的变化
 * 3. 逐个注释掉某些代码，理解每部分的作用
 */

// 引入SimpleTree类
require_once __DIR__ . '/../../library/core/SimpleTree.php';

echo "===== Tree类基础使用示例 =====\n\n";

// 第一步：准备测试数据
// 这里我们模拟一个公司组织架构
$company_data = array(
    1 => array('id' => 1, 'parentid' => 0, 'name' => '总经理办公室'),
    2 => array('id' => 2, 'parentid' => 1, 'name' => '技术部'),
    3 => array('id' => 3, 'parentid' => 1, 'name' => '市场部'),
    4 => array('id' => 4, 'parentid' => 1, 'name' => '财务部'),
    5 => array('id' => 5, 'parentid' => 2, 'name' => '开发组'),
    6 => array('id' => 6, 'parentid' => 2, 'name' => '测试组'),
    7 => array('id' => 7, 'parentid' => 3, 'name' => '市场推广组'),
    8 => array('id' => 8, 'parentid' => 3, 'name' => '客服组'),
    9 => array('id' => 9, 'parentid' => 5, 'name' => '前端开发'),
    10 => array('id' => 10, 'parentid' => 5, 'name' => '后端开发'),
    11 => array('id' => 11, 'parentid' => 5, 'name' => 'UI设计')
);

echo "数据准备完成！我们有一个公司组织架构数据。\n\n";

// 第二步：创建Tree实例并初始化
$tree = new SimpleTree();
$init_result = $tree->init($company_data);

if ($init_result) {
    echo "✅ Tree类初始化成功！\n\n";
} else {
    echo "❌ Tree类初始化失败！\n\n";
    exit;
}

// 第三步：显示完整的组织架构
echo "===== 📊 公司组织架构图 =====\n";
echo $tree->generateTreeText();
echo "\n";

// 第四步：基础查询操作

echo "===== 🔍 基础查询操作 =====\n\n";

// 4.1 获取根部门
echo "1. 📋 获取公司的顶级部门：\n";
$top_departments = $tree->getRoots();
if ($top_departments) {
    foreach ($top_departments as $dept) {
        echo "   - {$dept['name']}\n";
    }
} else {
    echo "   没有找到顶级部门\n";
}
echo "\n";

// 4.2 获取技术部下的直接子部门
echo "2. 🏢 技术部下的直接子部门：\n";
$tech_children = $tree->getChildren(2); // 技术部的ID是2
if ($tech_children) {
    foreach ($tech_children as $child) {
        echo "   - {$child['name']}\n";
    }
} else {
    echo "   技术部下没有子部门\n";
}
echo "\n";

// 4.3 获取技术部下的所有子孙部门
echo "3. 🌳 技术部下的所有子孙部门：\n";
$all_tech_descendants = $tree->getAllDescendants(2);
if ($all_tech_descendants) {
    foreach ($all_tech_descendants as $descendant) {
        echo "   - {$descendant['name']}\n";
    }
} else {
    echo "   技术部下没有任何子部门\n";
}
echo "\n";

// 4.4 查找某个部门的父部门
echo "4. 🔗 查找'前端开发'的父部门：\n";
$frontend_parent = $tree->getParent(9); // 前端开发的ID是9
if ($frontend_parent) {
    echo "   前端开发的父部门是: {$frontend_parent['name']}\n";
} else {
    echo "   前端开发没有父部门或部门不存在\n";
}
echo "\n";

// 第五步：深度和层级分析

echo "===== 📏 深度和层级分析 =====\n\n";

// 分析不同部门的层级深度
$departments_to_check = array(
    1 => '总经理办公室',
    2 => '技术部',
    5 => '开发组',
    9 => '前端开发'
);

echo "各部门的层级深度：\n";
foreach ($departments_to_check as $id => $name) {
    $depth = $tree->getDepth($id);
    echo "   {$name}: 第 {$depth} 层\n";
}
echo "\n";

// 第六步：叶子节点检测

echo "===== 🍃 叶子节点检测 =====\n\n";

echo "检测哪些部门是叶子节点（没有下级部门）：\n";
foreach ($company_data as $id => $dept) {
    if ($tree->isLeaf($id)) {
        echo "   ✓ {$dept['name']} (叶子节点)\n";
    } else {
        echo "   ○ {$dept['name']} (有下级部门)\n";
    }
}
echo "\n";

// 第七步：实际应用场景演示

echo "===== 💼 实际应用场景演示 =====\n\n";

// 场景1：生成下拉选择框选项
echo "1. 生成部门选择下拉框的HTML选项：\n";
function generateSelectOptions($tree, $parent_id = 0, $prefix = '') {
    $html = '';
    $children = $tree->getChildren($parent_id);
    
    if ($children) {
        foreach ($children as $child) {
            $option_text = $prefix . $child['name'];
            $html .= "   <option value='{$child['id']}'>{$option_text}</option>\n";
            
            // 递归生成子选项
            $html .= generateSelectOptions($tree, $child['id'], $prefix . '├─ ');
        }
    }
    
    return $html;
}

echo "<select name='department'>\n";
echo generateSelectOptions($tree);
echo "</select>\n\n";

// 场景2：生成面包屑导航
echo "2. 为'前端开发'部门生成面包屑导航：\n";
function generateBreadcrumb($tree, $node_id) {
    $path = array();
    $current_id = $node_id;
    
    // 向上追溯到根节点
    while ($current_id != 0 && isset($tree->data[$current_id])) {
        array_unshift($path, $tree->data[$current_id]['name']);
        $current_id = $tree->data[$current_id]['parentid'];
    }
    
    return implode(' > ', $path);
}

$breadcrumb = generateBreadcrumb($tree, 9); // 前端开发的ID是9
echo "   面包屑: {$breadcrumb}\n\n";

// 场景3：统计各部门的下级数量
echo "3. 统计各部门的直接下级数量：\n";
foreach ($company_data as $id => $dept) {
    $children = $tree->getChildren($id);
    $child_count = $children ? count($children) : 0;
    $total_descendants = $tree->getAllDescendants($id);
    $total_count = $total_descendants ? count($total_descendants) : 0;
    
    echo "   {$dept['name']}: 直接下级 {$child_count} 个，总下级 {$total_count} 个\n";
}
echo "\n";

// 第八步：数据修改演示

echo "===== ✏️  数据修改演示 =====\n\n";

echo "原始的技术部结构：\n";
echo $tree->generateTreeText(2, '');
echo "\n";

// 模拟添加新部门
echo "假设我们要添加一个'运维组'到技术部下：\n";
$new_data = $company_data;
$new_data[12] = array('id' => 12, 'parentid' => 2, 'name' => '运维组');

$new_tree = new SimpleTree();
$new_tree->init($new_data);

echo "添加后的技术部结构：\n";
echo $new_tree->generateTreeText(2, '');
echo "\n";

// 第九步：性能提示

echo "===== ⚡ 性能提示 =====\n\n";

echo "💡 使用建议：\n";
echo "1. 数据量大时，考虑在数据库层面建立索引\n";
echo "2. 如果频繁查询同一节点的子节点，可以考虑缓存结果\n";
echo "3. 避免过深的树结构（建议不超过10层）\n";
echo "4. 定期检查数据完整性，避免出现孤儿节点\n\n";

echo "===== 🎉 示例演示完成 =====\n";
echo "接下来你可以：\n";
echo "1. 修改上面的数据，尝试不同的结构\n";
echo "2. 尝试运行 menu_demo.php 查看菜单应用\n";
echo "3. 完成 exercises/exercise_01.php 中的练习\n";
?>
