<?php
/**
 * exercise_01.php - Tree类基础练习题
 * 
 * 这个文件包含了一系列练习题，帮助你巩固Tree类的基础知识
 * 
 * 练习说明：
 * 1. 每个练习都有明确的要求和提示
 * 2. 你需要填写代码来完成练习
 * 3. 运行文件可以看到你的答案结果
 * 4. 参考答案在 solutions/ 目录下
 * 
 * 学习建议：
 * 1. 先自己尝试完成，再看参考答案
 * 2. 理解每个步骤的逻辑
 * 3. 尝试修改数据测试不同情况
 */

require_once __DIR__ . '/../src/SimpleTree.php';

echo "===== 🎯 Tree类基础练习 =====\n\n";

// 练习数据：模拟一个网站菜单结构
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

echo "数据已加载：网站菜单结构\n";
echo $tree->generateTreeText();
echo "\n";

// =============================================
// 练习1：基础查询 (难度: ⭐)
// =============================================

echo "===== 练习1：基础查询 ⭐ =====\n";
echo "要求：请完成以下查询任务\n\n";

echo "1.1 获取所有一级菜单（根菜单）\n";
echo "提示：使用getRoots()方法\n";

// TODO: 在这里写你的代码
// $first_level_menus = ???;

echo "你的答案：\n";
// TODO: 遍历并显示一级菜单的名称
// 期望输出：首页、产品中心、新闻中心、关于我们

echo "\n";

echo "1.2 获取'产品中心'下的直接子菜单\n";
echo "提示：产品中心的ID是2，使用getChildren()方法\n";

// TODO: 在这里写你的代码
// $product_children = ???;

echo "你的答案：\n";
// TODO: 显示产品中心下的子菜单
// 期望输出：手机产品、电脑产品

echo "\n";

echo "1.3 查找'iPhone系列'的父菜单\n";
echo "提示：iPhone系列的ID是9，使用getParent()方法\n";

// TODO: 在这里写你的代码
// $iphone_parent = ???;

echo "你的答案：\n";
// TODO: 显示父菜单的名称
// 期望输出：手机产品

echo "\n";

// =============================================
// 练习2：递归查询 (难度: ⭐⭐)
// =============================================

echo "===== 练习2：递归查询 ⭐⭐ =====\n";
echo "要求：使用递归方法获取更复杂的数据\n\n";

echo "2.1 获取'产品中心'下的所有子孙菜单\n";
echo "提示：使用getAllDescendants()方法\n";

// TODO: 在这里写你的代码
// $all_product_menus = ???;

echo "你的答案：\n";
// TODO: 显示所有子孙菜单
// 期望输出：手机产品、电脑产品、iPhone系列、安卓手机、笔记本电脑、台式电脑

echo "\n";

echo "2.2 计算每个菜单的层级深度\n";
echo "提示：使用getDepth()方法\n";

echo "你的答案：\n";
// TODO: 遍历所有菜单，显示其名称和深度
// 期望输出格式：首页: 第1层, 产品中心: 第1层, iPhone系列: 第3层 等

echo "\n";

// =============================================
// 练习3：条件判断 (难度: ⭐⭐)
// =============================================

echo "===== 练习3：条件判断 ⭐⭐ =====\n";
echo "要求：根据不同条件筛选和处理数据\n\n";

echo "3.1 找出所有叶子节点（末级菜单）\n";
echo "提示：使用isLeaf()方法\n";

echo "你的答案：\n";
// TODO: 找出所有叶子节点并显示
// 期望输出：首页、关于我们、公司新闻、行业动态、iPhone系列、安卓手机、笔记本电脑、台式电脑

echo "\n";

echo "3.2 统计每个一级菜单下的子菜单数量\n";
echo "提示：结合getRoots()和getAllDescendants()方法\n";

echo "你的答案：\n";
// TODO: 统计每个一级菜单的子菜单数量
// 期望输出格式：首页: 0个子菜单, 产品中心: 6个子菜单 等

echo "\n";

// =============================================
// 练习4：实际应用 (难度: ⭐⭐⭐)
// =============================================

echo "===== 练习4：实际应用 ⭐⭐⭐ =====\n";
echo "要求：完成一些实际的应用场景\n\n";

echo "4.1 生成HTML导航菜单\n";
echo "提示：使用递归方法生成嵌套的<ul><li>结构\n";

// TODO: 编写函数生成HTML菜单
function generateHTMLMenu($tree, $parent_id = 0) {
    // TODO: 实现这个函数
    // 要求生成如下格式的HTML：
    // <ul>
    //   <li><a href="/">首页</a></li>
    //   <li>
    //     <a href="/products">产品中心</a>
    //     <ul>
    //       <li><a href="/products/phone">手机产品</a></li>
    //       ...
    //     </ul>
    //   </li>
    // </ul>
    
    return ""; // TODO: 返回生成的HTML
}

echo "你的答案：\n";
// TODO: 完成generateHTMLMenu函数后取消注释下面这行
// echo generateHTMLMenu($tree);
echo "<!-- 请先完成generateHTMLMenu函数的实现 -->\n";

echo "4.2 生成面包屑导航\n";
echo "提示：从指定节点向上追溯到根节点\n";

// TODO: 编写函数生成面包屑导航
function generateBreadcrumbNav($tree, $node_id) {
    // TODO: 实现这个函数
    // 要求：返回从根节点到指定节点的路径
    // 格式：首页 > 产品中心 > 手机产品 > iPhone系列
    
    return ""; // TODO: 返回生成的面包屑
}

echo "你的答案（为iPhone系列生成面包屑）：\n";
// TODO: 完成generateBreadcrumbNav函数后取消注释下面这行
// echo generateBreadcrumbNav($tree, 9); // iPhone系列的ID是9
echo "<!-- 请先完成generateBreadcrumbNav函数的实现 -->\n";

echo "4.3 查找某个菜单的所有兄弟菜单\n";
echo "提示：先找到父节点，再获取父节点的所有子节点，排除自己\n";

// TODO: 编写函数查找兄弟菜单
function findSiblings($tree, $node_id) {
    // TODO: 实现这个函数
    // 要求：返回与指定节点同级的其他节点
    
    return array(); // TODO: 返回兄弟节点数组
}

echo "你的答案（查找'手机产品'的兄弟菜单）：\n";
// TODO: 完成findSiblings函数后取消注释下面的代码
// $siblings = findSiblings($tree, 5); // 手机产品的ID是5
// TODO: 显示兄弟菜单
echo "<!-- 请先完成findSiblings函数的实现 -->\n";

// =============================================
// 练习5：高级应用 (难度: ⭐⭐⭐⭐)
// =============================================

echo "===== 练习5：高级应用 ⭐⭐⭐⭐ =====\n";
echo "要求：完成更复杂的应用场景\n\n";

echo "5.1 移动菜单位置\n";
echo "提示：修改数据数组中节点的parentid\n";

// TODO: 编写函数移动菜单
function moveMenu($tree_data, $menu_id, $new_parent_id) {
    // TODO: 实现这个函数
    // 要求：将指定菜单移动到新的父菜单下
    // 注意：要检查不能移动到自己的子菜单下
    
    return $tree_data; // TODO: 返回修改后的数据
}

echo "你的答案（将'iPhone系列'移动到'电脑产品'下）：\n";
// TODO: 完成moveMenu函数后取消注释下面的代码
// $modified_data = moveMenu($menu_data, 9, 6);
// $new_tree = new SimpleTree();
// $new_tree->init($modified_data);
// echo "移动后的结构：\n";
// echo $new_tree->generateTreeText();
echo "<!-- 请先完成moveMenu函数的实现 -->\n";

echo "5.2 删除菜单及其所有子菜单\n";
echo "提示：先获取所有子孙节点，然后一起删除\n";

// TODO: 编写函数删除菜单
function deleteMenuAndChildren($tree_data, $menu_id) {
    // TODO: 实现这个函数
    // 要求：删除指定菜单及其所有子菜单
    
    return $tree_data; // TODO: 返回删除后的数据
}

echo "你的答案（删除'新闻中心'及其所有子菜单）：\n";
// TODO: 完成deleteMenuAndChildren函数后取消注释下面的代码
// $deleted_data = deleteMenuAndChildren($menu_data, 3);
// $deleted_tree = new SimpleTree();
// $deleted_tree->init($deleted_data);
// echo "删除后的结构：\n";
// echo $deleted_tree->generateTreeText();
echo "<!-- 请先完成deleteMenuAndChildren函数的实现 -->\n";

// =============================================
// 自我检测
// =============================================

echo "===== 🎓 自我检测 =====\n";
echo "完成练习后，请思考以下问题：\n\n";
echo "1. 你理解Tree类的基本工作原理了吗？\n";
echo "2. 你能区分getChildren()和getAllDescendants()的区别吗？\n";
echo "3. 你理解递归在树形结构中的应用了吗？\n";
echo "4. 你能独立实现一个简单的Tree类方法吗？\n";
echo "5. 你知道在什么场景下使用不同的Tree方法吗？\n\n";

echo "如果上述问题你都能肯定回答，恭喜你已经掌握了Tree类的基础知识！\n";
echo "接下来可以：\n";
echo "1. 查看 solutions/exercise_01_solution.php 对比你的答案\n";
echo "2. 尝试 exercise_02.php 中的进阶练习\n";
echo "3. 运行 examples/menu_demo.php 查看菜单应用实例\n\n";

echo "===== 练习结束 =====\n";
?>
