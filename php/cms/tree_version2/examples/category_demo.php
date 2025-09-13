<?php
/**
 * category_demo.php - 分类管理示例
 * 
 * 这个文件演示了如何在电商网站的商品分类管理中使用Tree类
 * 包含了常见的分类管理操作，如分类展示、移动、添加、删除等
 * 
 * 应用场景：
 * 1. 电商网站商品分类
 * 2. 内容管理系统文章分类
 * 3. 论坛版块分类
 * 4. 企业部门组织结构
 * 
 * 学习重点：
 * 1. 实际业务场景中的Tree类应用
 * 2. 数据的增删改查操作
 * 3. 用户界面的友好展示
 * 4. 数据完整性和错误处理
 */

require_once __DIR__ . '/../src/BasicTree.php';

echo "===== 🛍️ 电商分类管理系统演示 =====\n\n";

// =================================================
// 第一部分：初始化分类数据
// =================================================

echo "📋 第一步：初始化商品分类数据\n";
echo "========================================\n";

// 模拟电商网站的商品分类数据
$category_data = array(
    1 => array('id' => 1, 'parentid' => 0, 'name' => '电子产品', 'code' => 'electronics', 'sort' => 1, 'status' => 1),
    2 => array('id' => 2, 'parentid' => 0, 'name' => '服装鞋帽', 'code' => 'clothing', 'sort' => 2, 'status' => 1),
    3 => array('id' => 3, 'parentid' => 0, 'name' => '家居用品', 'code' => 'home', 'sort' => 3, 'status' => 1),
    4 => array('id' => 4, 'parentid' => 1, 'name' => '手机通讯', 'code' => 'mobile', 'sort' => 1, 'status' => 1),
    5 => array('id' => 5, 'parentid' => 1, 'name' => '电脑办公', 'code' => 'computer', 'sort' => 2, 'status' => 1),
    6 => array('id' => 6, 'parentid' => 1, 'name' => '数码相机', 'code' => 'camera', 'sort' => 3, 'status' => 1),
    7 => array('id' => 7, 'parentid' => 4, 'name' => '智能手机', 'code' => 'smartphone', 'sort' => 1, 'status' => 1),
    8 => array('id' => 8, 'parentid' => 4, 'name' => '老人手机', 'code' => 'senior_phone', 'sort' => 2, 'status' => 1),
    9 => array('id' => 9, 'parentid' => 5, 'name' => '笔记本电脑', 'code' => 'laptop', 'sort' => 1, 'status' => 1),
    10 => array('id' => 10, 'parentid' => 5, 'name' => '台式电脑', 'code' => 'desktop', 'sort' => 2, 'status' => 1),
    11 => array('id' => 11, 'parentid' => 2, 'name' => '男装', 'code' => 'men_clothing', 'sort' => 1, 'status' => 1),
    12 => array('id' => 12, 'parentid' => 2, 'name' => '女装', 'code' => 'women_clothing', 'sort' => 2, 'status' => 1),
    13 => array('id' => 13, 'parentid' => 11, 'name' => '男士衬衫', 'code' => 'men_shirt', 'sort' => 1, 'status' => 1),
    14 => array('id' => 14, 'parentid' => 11, 'name' => '男士裤装', 'code' => 'men_pants', 'sort' => 2, 'status' => 1),
    15 => array('id' => 15, 'parentid' => 3, 'name' => '家具', 'code' => 'furniture', 'sort' => 1, 'status' => 1),
    16 => array('id' => 16, 'parentid' => 3, 'name' => '厨具', 'code' => 'kitchen', 'sort' => 2, 'status' => 1)
);

$tree = new BasicTree();
$tree->init($category_data);

echo "✅ 分类数据初始化完成！共加载 " . count($category_data) . " 个分类\n\n";

// 显示完整的分类树
echo "🌳 完整的商品分类结构：\n";
echo $tree->generateTreeText();
echo "\n";

// =================================================
// 第二部分：分类查询功能演示
// =================================================

echo "🔍 第二步：分类查询功能演示\n";
echo "========================================\n";

// 2.1 获取一级分类（用于导航菜单）
echo "2.1 获取一级分类（导航菜单）：\n";
$main_categories = $tree->getRoots();
if ($main_categories) {
    foreach ($main_categories as $category) {
        echo "   📁 {$category['name']} ({$category['code']})\n";
    }
}
echo "\n";

// 2.2 获取指定分类的子分类
echo "2.2 获取'电子产品'下的子分类：\n";
$electronics_children = $tree->getChildren(1);
if ($electronics_children) {
    foreach ($electronics_children as $child) {
        echo "   📂 {$child['name']} ({$child['code']})\n";
    }
}
echo "\n";

// 2.3 获取分类的完整路径（面包屑导航）
echo "2.3 生成面包屑导航：\n";
$target_categories = array(7 => '智能手机', 13 => '男士衬衫', 9 => '笔记本电脑');

foreach ($target_categories as $id => $name) {
    $breadcrumb = $tree->getPathString($id, ' > ');
    echo "   {$name}: {$breadcrumb}\n";
}
echo "\n";

// 2.4 统计分类信息
echo "2.4 分类统计信息：\n";
foreach ($main_categories as $category) {
    $child_count = count($tree->getChildren($category['id']) ?: array());
    $total_count = count($tree->getAllDescendants($category['id']) ?: array());
    echo "   {$category['name']}: 直接子分类 {$child_count} 个，总下级分类 {$total_count} 个\n";
}
echo "\n";

// =================================================
// 第三部分：分类管理操作演示
// =================================================

echo "⚙️  第三步：分类管理操作演示\n";
echo "========================================\n";

// 3.1 添加新分类
echo "3.1 添加新分类：\n";
echo "   添加'游戏设备'到'电子产品'下...\n";

// 模拟添加新分类
$new_category = array('id' => 17, 'parentid' => 1, 'name' => '游戏设备', 'code' => 'gaming', 'sort' => 4, 'status' => 1);
$tree->data[17] = $new_category;

echo "   ✅ 新分类添加成功！\n";
echo "   更新后的'电子产品'分类：\n";
echo $tree->generateTreeText(1, '   ');
echo "\n";

// 3.2 移动分类
echo "3.2 移动分类位置：\n";
echo "   将'数码相机'移动到'游戏设备'下...\n";

if ($tree->moveNode(6, 17)) {
    echo "   ✅ 分类移动成功！\n";
    echo "   移动后的结构：\n";
    echo $tree->generateTreeText(17, '   ');
} else {
    echo "   ❌ 分类移动失败！\n";
}
echo "\n";

// 3.3 删除分类（标记为禁用）
echo "3.3 禁用分类：\n";
echo "   禁用'老人手机'分类...\n";

if (isset($tree->data[8])) {
    $tree->data[8]['status'] = 0; // 标记为禁用
    echo "   ✅ 分类已禁用！\n";
} else {
    echo "   ❌ 分类不存在！\n";
}
echo "\n";

// =================================================
// 第四部分：前端展示功能
// =================================================

echo "🎨 第四步：前端展示功能\n";
echo "========================================\n";

// 4.1 生成分类选择下拉框
echo "4.1 生成分类选择下拉框：\n";
echo "<select name=\"category_id\">\n";
echo "  <option value=\"0\">请选择分类</option>\n";
echo $tree->generateSelectOptions(0, '', null, array(8)); // 排除禁用的分类
echo "</select>\n\n";

// 4.2 生成侧边栏分类菜单
echo "4.2 生成侧边栏分类菜单：\n";
function generateCategoryMenu($tree, $parent_id = 0, $level = 0) {
    $html = '';
    $children = $tree->getChildren($parent_id);
    
    if ($children) {
        $indent = str_repeat('  ', $level);
        
        if ($level == 0) {
            $html .= "<ul class=\"category-menu\">\n";
        } else {
            $html .= "{$indent}<ul class=\"sub-menu\">\n";
        }
        
        foreach ($children as $child) {
            // 跳过禁用的分类
            if (isset($child['status']) && $child['status'] == 0) {
                continue;
            }
            
            $has_children = ($tree->getChildren($child['id']) !== false);
            $class = $has_children ? 'has-children' : '';
            
            $html .= "{$indent}  <li class=\"{$class}\">\n";
            $html .= "{$indent}    <a href=\"/category/{$child['code']}\">{$child['name']}</a>\n";
            
            if ($has_children) {
                $html .= generateCategoryMenu($tree, $child['id'], $level + 1);
            }
            
            $html .= "{$indent}  </li>\n";
        }
        
        $html .= "{$indent}</ul>\n";
    }
    
    return $html;
}

echo generateCategoryMenu($tree);
echo "\n";

// 4.3 生成API接口返回的JSON数据
echo "4.3 生成API接口JSON数据：\n";
function generateCategoryAPI($tree, $parent_id = 0) {
    $categories = array();
    $children = $tree->getChildren($parent_id);
    
    if ($children) {
        foreach ($children as $child) {
            // 跳过禁用的分类
            if (isset($child['status']) && $child['status'] == 0) {
                continue;
            }
            
            $category_info = array(
                'id' => $child['id'],
                'name' => $child['name'],
                'code' => $child['code'],
                'sort' => $child['sort'],
                'has_children' => ($tree->getChildren($child['id']) !== false),
                'path' => $tree->getPathString($child['id']),
                'level' => $tree->getDepth($child['id'])
            );
            
            $categories[] = $category_info;
        }
    }
    
    return $categories;
}

$api_data = generateCategoryAPI($tree);
echo json_encode(array_slice($api_data, 0, 3), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
echo "\n（只显示前3个分类）\n\n";

// =================================================
// 第五部分：数据完整性检查
// =================================================

echo "🔒 第五步：数据完整性检查\n";
echo "========================================\n";

// 5.1 验证分类数据完整性
echo "5.1 数据完整性检查：\n";
$issues = $tree->validateData();
if (empty($issues)) {
    echo "   ✅ 分类数据完整性检查通过\n";
} else {
    echo "   ❌ 发现以下问题：\n";
    foreach ($issues as $issue) {
        echo "      - {$issue}\n";
    }
}
echo "\n";

// 5.2 查找孤儿分类（测试用）
echo "5.2 检查孤儿分类：\n";
$orphan_categories = array();
foreach ($tree->data as $id => $category) {
    $parent_id = $category['parentid'];
    if ($parent_id != 0 && !isset($tree->data[$parent_id])) {
        $orphan_categories[] = $category;
    }
}

if (empty($orphan_categories)) {
    echo "   ✅ 没有发现孤儿分类\n";
} else {
    echo "   ❌ 发现孤儿分类：\n";
    foreach ($orphan_categories as $orphan) {
        echo "      - {$orphan['name']} (ID: {$orphan['id']}, 父ID: {$orphan['parentid']})\n";
    }
}
echo "\n";

// 5.3 检查循环引用
echo "5.3 检查循环引用：\n";
// 这里我们故意创建一个循环引用来测试
$original_parentid = $tree->data[1]['parentid'];
$tree->data[1]['parentid'] = 7; // 电子产品的父分类设为智能手机（创建循环）

$issues_with_cycle = $tree->validateData();
$has_cycle = false;
foreach ($issues_with_cycle as $issue) {
    if (strpos($issue, '循环引用') !== false) {
        echo "   ❌ {$issue}\n";
        $has_cycle = true;
    }
}

if (!$has_cycle) {
    echo "   ✅ 没有发现循环引用\n";
}

// 修复循环引用
$tree->data[1]['parentid'] = $original_parentid;
echo "   ✅ 循环引用已修复\n\n";

// =================================================
// 第六部分：实用工具函数
// =================================================

echo "🛠️  第六步：实用工具函数演示\n";
echo "========================================\n";

// 6.1 分类搜索功能
echo "6.1 分类搜索功能：\n";
function searchCategories($tree, $keyword) {
    $results = array();
    foreach ($tree->data as $id => $category) {
        if (stripos($category['name'], $keyword) !== false || 
            stripos($category['code'], $keyword) !== false) {
            $results[] = array(
                'id' => $category['id'],
                'name' => $category['name'],
                'path' => $tree->getPathString($category['id'])
            );
        }
    }
    return $results;
}

$search_results = searchCategories($tree, '手机');
echo "   搜索'手机'相关分类：\n";
foreach ($search_results as $result) {
    echo "      - {$result['name']} ({$result['path']})\n";
}
echo "\n";

// 6.2 获取热门分类（模拟根据销量排序）
echo "6.2 获取热门分类（模拟数据）：\n";
$hot_categories = array(
    array('id' => 7, 'name' => '智能手机', 'sales' => 15000),
    array('id' => 9, 'name' => '笔记本电脑', 'sales' => 8500),
    array('id' => 13, 'name' => '男士衬衫', 'sales' => 6200),
    array('id' => 10, 'name' => '台式电脑', 'sales' => 4300)
);

echo "   热门分类排行榜：\n";
foreach ($hot_categories as $index => $hot_cat) {
    $rank = $index + 1;
    $path = $tree->getPathString($hot_cat['id']);
    echo "      {$rank}. {$hot_cat['name']} - 销量: {$hot_cat['sales']} ({$path})\n";
}
echo "\n";

// 6.3 生成网站地图（sitemap）
echo "6.3 生成网站地图：\n";
function generateSitemap($tree, $base_url = 'https://example.com') {
    $sitemap = array();
    foreach ($tree->data as $category) {
        if (isset($category['status']) && $category['status'] == 1) { // 只包含启用的分类
            $sitemap[] = array(
                'url' => $base_url . '/category/' . $category['code'],
                'title' => $category['name'],
                'level' => $tree->getDepth($category['id'])
            );
        }
    }
    return $sitemap;
}

$sitemap = generateSitemap($tree);
echo "   网站地图（前5个链接）：\n";
for ($i = 0; $i < 5 && $i < count($sitemap); $i++) {
    $item = $sitemap[$i];
    $indent = str_repeat('  ', $item['level'] - 1);
    echo "      {$indent}- {$item['title']}: {$item['url']}\n";
}
echo "\n";

// =================================================
// 第七部分：性能优化建议
// =================================================

echo "⚡ 第七步：性能优化建议\n";
echo "========================================\n";

echo "📊 当前分类统计：\n";
echo "   - 总分类数量: " . count($tree->data) . "\n";
echo "   - 最大深度: " . $tree->getMaxDepth() . " 层\n";
echo "   - 一级分类数量: " . count($tree->getRoots()) . "\n\n";

echo "💡 性能优化建议：\n";
echo "   1. 数据库层面：\n";
echo "      - 为 parentid 字段建立索引\n";
echo "      - 为 sort 字段建立索引用于排序\n";
echo "      - 考虑使用 Redis 缓存热门分类\n\n";

echo "   2. 应用层面：\n";
echo "      - 使用 FullTree 类的缓存功能\n";
echo "      - 分页显示子分类（当子分类过多时）\n";
echo "      - 使用异步加载减少初始页面加载时间\n\n";

echo "   3. 前端优化：\n";
echo "      - 使用懒加载展开子分类\n";
echo "      - 压缩JSON数据传输\n";
echo "      - 使用CDN缓存分类图标\n\n";

// =================================================
// 总结
// =================================================

echo "🎉 演示总结\n";
echo "========================================\n";

echo "✅ 本演示展示了以下功能：\n";
echo "   1. 分类数据的初始化和管理\n";
echo "   2. 各种查询操作（子分类、路径、统计等）\n";
echo "   3. 分类的增删改操作\n";
echo "   4. 前端展示功能（下拉框、菜单、API等）\n";
echo "   5. 数据完整性检查和维护\n";
echo "   6. 实用工具函数（搜索、排行等）\n";
echo "   7. 性能优化建议\n\n";

echo "📚 进一步学习建议：\n";
echo "   1. 尝试集成到实际的Web应用中\n";
echo "   2. 连接数据库进行持久化存储\n";
echo "   3. 添加权限控制（谁可以管理哪些分类）\n";
echo "   4. 实现分类的批量导入导出功能\n";
echo "   5. 添加分类的多语言支持\n\n";

echo "🚀 下一步行动：\n";
echo "   - 完成 exercises/exercise_02.php 中的进阶练习\n";
echo "   - 尝试将这个演示改造为你自己项目的分类系统\n";
echo "   - 学习 FullTree 类的高级功能\n\n";

echo "===== 分类管理演示结束 =====\n";
?>
