<?php
/**
 * exercise_01_solution.php - Tree类基础练习答案
 * 
 * 这个文件包含了exercise_01.php中所有练习题的参考答案
 * 请先独立完成练习，再查看答案进行对比学习
 * 
 * 学习建议：
 * 1. 对比自己的答案和参考答案
 * 2. 理解每个解决方案的思路
 * 3. 尝试改进和优化代码
 * 4. 举一反三，应用到其他场景
 */

require_once __DIR__ . '/../../src/SimpleTree.php';

echo "===== 📖 Tree类基础练习参考答案 =====\n\n";

// 重新初始化数据
$menu_data = array(
    1 => array('id' => 1, 'parentid' => 0, 'name' => '首页', 'url' => '/'),
    2 => array('id' => 2, 'parentid' => 0, 'name' => '产品中心', 'url' => '/products'),
    3 => array('id' => 3, 'parentid' => 0, 'name' => '新闻中心', 'url' => '/news'),
    4 => array('id' => 4, 'parentid' => 0, 'name' => '关于我们', 'url' => '/about'),
    5 => array('id' => 5, 'parentid' => 2, 'name' => '手机产品', 'url' => '/products/phone'),
    6 => array('id' => 6, 'parentid' => 2, 'name' => '电脑产品', 'url' => '/products/computer'),
    7 => array('id' => 7, 'parentid' => 3, 'name' => '公司新闻', 'url' => '/news/company'),
    8 => array('id' => 8, 'parentid' => 3, 'name' => '行业动态', 'url' => '/news/industry'),
    9 => array('id' => 9, 'parentid' => 5, 'name' => 'iPhone系列', 'url' => '/products/phone/iphone'),
    10 => array('id' => 10, 'parentid' => 5, 'name' => '安卓手机', 'url' => '/products/phone/android'),
    11 => array('id' => 11, 'parentid' => 6, 'name' => '笔记本电脑', 'url' => '/products/computer/laptop'),
    12 => array('id' => 12, 'parentid' => 6, 'name' => '台式电脑', 'url' => '/products/computer/desktop')
);

$tree = new SimpleTree();
$tree->init($menu_data);

echo "数据已重新加载，开始展示参考答案...\n\n";

// =============================================
// 练习1 答案：基础查询 (难度: ⭐)
// =============================================

echo "===== 练习1 参考答案：基础查询 ⭐ =====\n\n";

echo "1.1 获取所有一级菜单（根菜单）\n";
echo "答案解析：使用getRoots()方法获取所有父ID为0的节点\n";

$first_level_menus = $tree->getRoots();
echo "参考答案：\n";
if ($first_level_menus) {
    foreach ($first_level_menus as $menu) {
        echo "  - {$menu['name']}\n";
    }
} else {
    echo "  没有找到一级菜单\n";
}
echo "\n";

echo "1.2 获取'产品中心'下的直接子菜单\n";
echo "答案解析：产品中心的ID是2，使用getChildren(2)获取其直接子菜单\n";

$product_children = $tree->getChildren(2);
echo "参考答案：\n";
if ($product_children) {
    foreach ($product_children as $child) {
        echo "  - {$child['name']}\n";
    }
} else {
    echo "  产品中心下没有子菜单\n";
}
echo "\n";

echo "1.3 查找'iPhone系列'的父菜单\n";
echo "答案解析：iPhone系列的ID是9，使用getParent(9)查找其父菜单\n";

$iphone_parent = $tree->getParent(9);
echo "参考答案：\n";
if ($iphone_parent) {
    echo "  iPhone系列的父菜单是: {$iphone_parent['name']}\n";
} else {
    echo "  iPhone系列没有父菜单或菜单不存在\n";
}
echo "\n";

// =============================================
// 练习2 答案：递归查询 (难度: ⭐⭐)
// =============================================

echo "===== 练习2 参考答案：递归查询 ⭐⭐ =====\n\n";

echo "2.1 获取'产品中心'下的所有子孙菜单\n";
echo "答案解析：使用getAllDescendants(2)递归获取所有子孙节点\n";

$all_product_menus = $tree->getAllDescendants(2);
echo "参考答案：\n";
if ($all_product_menus) {
    foreach ($all_product_menus as $menu) {
        echo "  - {$menu['name']}\n";
    }
} else {
    echo "  产品中心下没有子孙菜单\n";
}
echo "\n";

echo "2.2 计算每个菜单的层级深度\n";
echo "答案解析：遍历所有菜单，使用getDepth()方法计算深度\n";

echo "参考答案：\n";
foreach ($menu_data as $id => $menu) {
    $depth = $tree->getDepth($id);
    echo "  {$menu['name']}: 第{$depth}层\n";
}
echo "\n";

// =============================================
// 练习3 答案：条件判断 (难度: ⭐⭐)
// =============================================

echo "===== 练习3 参考答案：条件判断 ⭐⭐ =====\n\n";

echo "3.1 找出所有叶子节点（末级菜单）\n";
echo "答案解析：遍历所有菜单，使用isLeaf()方法判断是否为叶子节点\n";

echo "参考答案：\n";
foreach ($menu_data as $id => $menu) {
    if ($tree->isLeaf($id)) {
        echo "  ✓ {$menu['name']} (叶子节点)\n";
    }
}
echo "\n";

echo "3.2 统计每个一级菜单下的子菜单数量\n";
echo "答案解析：获取一级菜单，然后分别统计其子菜单数量\n";

echo "参考答案：\n";
$root_menus = $tree->getRoots();
foreach ($root_menus as $root_menu) {
    $descendants = $tree->getAllDescendants($root_menu['id']);
    $count = $descendants ? count($descendants) : 0;
    echo "  {$root_menu['name']}: {$count}个子菜单\n";
}
echo "\n";

// =============================================
// 练习4 答案：实际应用 (难度: ⭐⭐⭐)
// =============================================

echo "===== 练习4 参考答案：实际应用 ⭐⭐⭐ =====\n\n";

echo "4.1 生成HTML导航菜单\n";
echo "答案解析：使用递归方法生成嵌套的HTML结构\n";

function generateHTMLMenu($tree, $parent_id = 0) {
    $html = '';
    $children = $tree->getChildren($parent_id);
    
    if ($children) {
        $html .= "<ul>\n";
        foreach ($children as $child) {
            $html .= "  <li>\n";
            $html .= "    <a href=\"{$child['url']}\">{$child['name']}</a>\n";
            
            // 递归处理子菜单
            $sub_html = generateHTMLMenu($tree, $child['id']);
            if ($sub_html) {
                $html .= "    " . $sub_html;
            }
            
            $html .= "  </li>\n";
        }
        $html .= "</ul>\n";
    }
    
    return $html;
}

echo "参考答案：\n";
echo generateHTMLMenu($tree);
echo "\n";

echo "4.2 生成面包屑导航\n";
echo "答案解析：从目标节点向上追溯到根节点，构建路径\n";

function generateBreadcrumbNav($tree, $node_id) {
    $path = array();
    $current_id = $node_id;
    
    // 向上追溯到根节点
    while ($current_id != 0 && isset($tree->data[$current_id])) {
        array_unshift($path, $tree->data[$current_id]['name']);
        $current_id = $tree->data[$current_id]['parentid'];
    }
    
    return implode(' > ', $path);
}

echo "参考答案（为iPhone系列生成面包屑）：\n";
$breadcrumb = generateBreadcrumbNav($tree, 9);
echo "  {$breadcrumb}\n\n";

echo "4.3 查找某个菜单的所有兄弟菜单\n";
echo "答案解析：先找到父节点，再获取父节点的所有子节点，排除自己\n";

function findSiblings($tree, $node_id) {
    if (!isset($tree->data[$node_id])) {
        return array();
    }
    
    $parent_id = $tree->data[$node_id]['parentid'];
    $siblings = $tree->getChildren($parent_id);
    
    if ($siblings && isset($siblings[$node_id])) {
        unset($siblings[$node_id]); // 排除自己
    }
    
    return $siblings ? $siblings : array();
}

echo "参考答案（查找'手机产品'的兄弟菜单）：\n";
$siblings = findSiblings($tree, 5);
if ($siblings) {
    foreach ($siblings as $sibling) {
        echo "  - {$sibling['name']}\n";
    }
} else {
    echo "  没有找到兄弟菜单\n";
}
echo "\n";

// =============================================
// 练习5 答案：高级应用 (难度: ⭐⭐⭐⭐)
// =============================================

echo "===== 练习5 参考答案：高级应用 ⭐⭐⭐⭐ =====\n\n";

echo "5.1 移动菜单位置\n";
echo "答案解析：修改节点的parentid，但要检查是否会造成循环引用\n";

function moveMenu($tree_data, $menu_id, $new_parent_id) {
    // 检查节点是否存在
    if (!isset($tree_data[$menu_id])) {
        return $tree_data;
    }
    
    // 检查新父节点是否存在（0表示根节点，允许）
    if ($new_parent_id != 0 && !isset($tree_data[$new_parent_id])) {
        return $tree_data;
    }
    
    // 检查是否会造成循环引用（简单检查：不能移动到自己的子节点下）
    $temp_tree = new SimpleTree();
    $temp_tree->init($tree_data);
    
    $descendants = $temp_tree->getAllDescendants($menu_id);
    if ($descendants && isset($descendants[$new_parent_id])) {
        echo "  ❌ 错误：不能移动到自己的子菜单下\n";
        return $tree_data;
    }
    
    // 执行移动
    $tree_data[$menu_id]['parentid'] = $new_parent_id;
    
    return $tree_data;
}

echo "参考答案（将'iPhone系列'移动到'电脑产品'下）：\n";
$modified_data = moveMenu($menu_data, 9, 6);
$new_tree = new SimpleTree();
$new_tree->init($modified_data);
echo "移动后的结构：\n";
echo $new_tree->generateTreeText();
echo "\n";

echo "5.2 删除菜单及其所有子菜单\n";
echo "答案解析：先获取所有子孙节点，然后一起删除\n";

function deleteMenuAndChildren($tree_data, $menu_id) {
    $temp_tree = new SimpleTree();
    $temp_tree->init($tree_data);
    
    // 获取所有子孙节点
    $descendants = $temp_tree->getAllDescendants($menu_id);
    
    // 删除所有子孙节点
    if ($descendants) {
        foreach ($descendants as $descendant_id => $descendant) {
            unset($tree_data[$descendant_id]);
        }
    }
    
    // 删除节点本身
    if (isset($tree_data[$menu_id])) {
        unset($tree_data[$menu_id]);
    }
    
    return $tree_data;
}

echo "参考答案（删除'新闻中心'及其所有子菜单）：\n";
$deleted_data = deleteMenuAndChildren($menu_data, 3);
$deleted_tree = new SimpleTree();
$deleted_tree->init($deleted_data);
echo "删除后的结构：\n";
echo $deleted_tree->generateTreeText();
echo "\n";

// =============================================
// 答案解析和学习要点
// =============================================

echo "===== 📚 答案解析和学习要点 =====\n\n";

echo "🎯 核心学习要点：\n\n";

echo "1. 基础查询方法：\n";
echo "   - getRoots(): 获取根节点\n";
echo "   - getChildren(): 获取直接子节点\n";
echo "   - getParent(): 获取父节点\n";
echo "   - getAllDescendants(): 递归获取所有子孙节点\n\n";

echo "2. 判断方法：\n";
echo "   - isLeaf(): 判断是否为叶子节点\n";
echo "   - getDepth(): 计算节点深度\n\n";

echo "3. 实用技巧：\n";
echo "   - 面包屑导航：向上追溯路径\n";
echo "   - 兄弟节点：通过父节点间接获取\n";
echo "   - HTML生成：递归构建嵌套结构\n\n";

echo "4. 高级操作：\n";
echo "   - 移动节点：修改parentid，注意循环引用\n";
echo "   - 删除节点：先删子孙，再删自己\n";
echo "   - 数据验证：检查完整性和一致性\n\n";

echo "💡 编程思维训练：\n\n";

echo "1. 递归思维：\n";
echo "   - 树形结构天然适合递归处理\n";
echo "   - 明确递归终止条件\n";
echo "   - 考虑递归深度限制\n\n";

echo "2. 数据完整性：\n";
echo "   - 操作前检查数据有效性\n";
echo "   - 避免产生孤儿节点\n";
echo "   - 防止循环引用\n\n";

echo "3. 性能考虑：\n";
echo "   - 大数据量时考虑缓存\n";
echo "   - 避免重复查询\n";
echo "   - 合理使用索引\n\n";

echo "🚀 进阶方向：\n\n";

echo "1. 数据库应用：\n";
echo "   - 学习SQL的递归查询（WITH RECURSIVE）\n";
echo "   - 了解邻接列表和路径枚举等存储模式\n";
echo "   - 掌握数据库索引优化\n\n";

echo "2. 前端集成：\n";
echo "   - 学习树形组件的使用\n";
echo "   - 理解懒加载和虚拟滚动\n";
echo "   - 掌握用户交互设计\n\n";

echo "3. 算法深化：\n";
echo "   - 学习平衡二叉树\n";
echo "   - 了解B树和B+树\n";
echo "   - 研究图论算法\n\n";

echo "📖 常见错误和解决方案：\n\n";

$common_mistakes = array(
    "忘记检查节点是否存在" => "使用isset()检查数组键是否存在",
    "递归时没有终止条件" => "明确设置递归终止条件，避免无限循环",
    "修改数据时产生循环引用" => "操作前进行循环检测",
    "字符串拼接性能问题" => "大量拼接时使用数组join或buffer",
    "没有考虑数据边界情况" => "处理空数组、空节点等特殊情况"
);

foreach ($common_mistakes as $mistake => $solution) {
    echo "❌ {$mistake}\n";
    echo "✅ 解决方案: {$solution}\n\n";
}

echo "🎉 恭喜完成基础练习！\n";
echo "现在你已经掌握了Tree类的基本使用方法。\n";
echo "建议继续挑战exercise_02.php中的进阶练习！\n\n";

echo "===== 参考答案展示结束 =====\n";
?>
