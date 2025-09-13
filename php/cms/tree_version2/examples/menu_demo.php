<?php
/**
 * menu_demo.php - 网站菜单生成演示
 * 
 * 这个示例展示了如何使用Tree类来处理网站菜单系统
 * 包括多级菜单的生成、当前位置识别、面包屑导航等实用功能
 * 
 * 应用场景：
 * - 网站导航菜单
 * - 后台管理菜单
 * - 分类筛选菜单
 * - 权限控制菜单
 */

require_once __DIR__ . '/../src/SimpleTree.php';

// 模拟网站菜单数据（更真实的场景）
$website_menu = array(
    1 => array('id' => 1, 'parentid' => 0, 'name' => '首页', 'url' => '/', 'icon' => 'home', 'target' => '_self'),
    2 => array('id' => 2, 'parentid' => 0, 'name' => '产品展示', 'url' => '/products', 'icon' => 'grid', 'target' => '_self'),
    3 => array('id' => 3, 'parentid' => 0, 'name' => '解决方案', 'url' => '/solutions', 'icon' => 'lightbulb', 'target' => '_self'),
    4 => array('id' => 4, 'parentid' => 0, 'name' => '技术支持', 'url' => '/support', 'icon' => 'headphones', 'target' => '_self'),
    5 => array('id' => 5, 'parentid' => 0, 'name' => '关于我们', 'url' => '/about', 'icon' => 'users', 'target' => '_self'),
    
    // 产品展示子菜单
    6 => array('id' => 6, 'parentid' => 2, 'name' => '智能手机', 'url' => '/products/smartphones', 'icon' => 'smartphone', 'target' => '_self'),
    7 => array('id' => 7, 'parentid' => 2, 'name' => '平板电脑', 'url' => '/products/tablets', 'icon' => 'tablet', 'target' => '_self'),
    8 => array('id' => 8, 'parentid' => 2, 'name' => '笔记本电脑', 'url' => '/products/laptops', 'icon' => 'laptop', 'target' => '_self'),
    9 => array('id' => 9, 'parentid' => 2, 'name' => '智能配件', 'url' => '/products/accessories', 'icon' => 'watch', 'target' => '_self'),
    
    // 智能手机子分类
    10 => array('id' => 10, 'parentid' => 6, 'name' => '旗舰系列', 'url' => '/products/smartphones/flagship', 'icon' => 'star', 'target' => '_self'),
    11 => array('id' => 11, 'parentid' => 6, 'name' => '中端系列', 'url' => '/products/smartphones/mid-range', 'icon' => 'circle', 'target' => '_self'),
    12 => array('id' => 12, 'parentid' => 6, 'name' => '入门系列', 'url' => '/products/smartphones/entry', 'icon' => 'square', 'target' => '_self'),
    
    // 解决方案子菜单
    13 => array('id' => 13, 'parentid' => 3, 'name' => '企业解决方案', 'url' => '/solutions/enterprise', 'icon' => 'building', 'target' => '_self'),
    14 => array('id' => 14, 'parentid' => 3, 'name' => '教育解决方案', 'url' => '/solutions/education', 'icon' => 'book', 'target' => '_self'),
    15 => array('id' => 15, 'parentid' => 3, 'name' => '医疗解决方案', 'url' => '/solutions/healthcare', 'icon' => 'heart', 'target' => '_self'),
    
    // 技术支持子菜单
    16 => array('id' => 16, 'parentid' => 4, 'name' => '下载中心', 'url' => '/support/downloads', 'icon' => 'download', 'target' => '_self'),
    17 => array('id' => 17, 'parentid' => 4, 'name' => '技术文档', 'url' => '/support/docs', 'icon' => 'file-text', 'target' => '_blank'),
    18 => array('id' => 18, 'parentid' => 4, 'name' => '常见问题', 'url' => '/support/faq', 'icon' => 'help-circle', 'target' => '_self'),
    19 => array('id' => 19, 'parentid' => 4, 'name' => '在线客服', 'url' => '/support/chat', 'icon' => 'message-circle', 'target' => '_blank'),
    
    // 关于我们子菜单
    20 => array('id' => 20, 'parentid' => 5, 'name' => '公司简介', 'url' => '/about/company', 'icon' => 'info', 'target' => '_self'),
    21 => array('id' => 21, 'parentid' => 5, 'name' => '发展历程', 'url' => '/about/history', 'icon' => 'clock', 'target' => '_self'),
    22 => array('id' => 22, 'parentid' => 5, 'name' => '联系我们', 'url' => '/about/contact', 'icon' => 'mail', 'target' => '_self'),
    23 => array('id' => 23, 'parentid' => 5, 'name' => '招聘信息', 'url' => '/about/careers', 'icon' => 'briefcase', 'target' => '_self')
);

echo "===== 🌐 网站菜单系统演示 =====\n\n";

// 初始化Tree
$tree = new SimpleTree();
$tree->init($website_menu);

// 显示完整菜单结构
echo "📋 完整网站菜单结构：\n";
echo $tree->generateTreeText();
echo "\n";

// =============================================
// 1. 生成水平导航菜单（一级菜单）
// =============================================

echo "===== 1. 水平导航菜单生成 =====\n\n";

function generateTopNavigation($tree) {
    $top_menus = $tree->getRoots();
    $html = "<nav class='top-navigation'>\n";
    $html .= "  <ul class='nav-list'>\n";
    
    if ($top_menus) {
        foreach ($top_menus as $menu) {
            $has_children = !$tree->isLeaf($menu['id']);
            $dropdown_class = $has_children ? " has-dropdown" : "";
            
            $html .= "    <li class='nav-item{$dropdown_class}'>\n";
            $html .= "      <a href='{$menu['url']}' target='{$menu['target']}'>\n";
            $html .= "        <i class='icon-{$menu['icon']}'></i>\n";
            $html .= "        {$menu['name']}\n";
            if ($has_children) {
                $html .= "        <i class='dropdown-arrow'></i>\n";
            }
            $html .= "      </a>\n";
            $html .= "    </li>\n";
        }
    }
    
    $html .= "  </ul>\n";
    $html .= "</nav>\n";
    
    return $html;
}

echo "生成的水平导航菜单HTML：\n";
echo generateTopNavigation($tree);
echo "\n";

// =============================================
// 2. 生成下拉菜单（多级菜单）
// =============================================

echo "===== 2. 下拉菜单生成 =====\n\n";

function generateDropdownMenu($tree, $parent_id = 0, $level = 0) {
    $children = $tree->getChildren($parent_id);
    if (!$children) return '';
    
    $indent = str_repeat("  ", $level * 2);
    $class = $level == 0 ? "dropdown-menu" : "sub-menu";
    
    $html = "{$indent}<ul class='{$class} level-{$level}'>\n";
    
    foreach ($children as $child) {
        $has_children = !$tree->isLeaf($child['id']);
        $item_class = $has_children ? "has-submenu" : "";
        
        $html .= "{$indent}  <li class='menu-item {$item_class}'>\n";
        $html .= "{$indent}    <a href='{$child['url']}' target='{$child['target']}'>\n";
        $html .= "{$indent}      <i class='icon-{$child['icon']}'></i>\n";
        $html .= "{$indent}      {$child['name']}\n";
        $html .= "{$indent}    </a>\n";
        
        // 递归生成子菜单
        if ($has_children) {
            $html .= generateDropdownMenu($tree, $child['id'], $level + 1);
        }
        
        $html .= "{$indent}  </li>\n";
    }
    
    $html .= "{$indent}</ul>\n";
    
    return $html;
}

echo "生成的完整下拉菜单HTML（产品展示部分）：\n";
echo generateDropdownMenu($tree, 2); // 产品展示的ID是2
echo "\n";

// =============================================
// 3. 面包屑导航生成
// =============================================

echo "===== 3. 面包屑导航生成 =====\n\n";

function generateBreadcrumb($tree, $current_menu_id) {
    if (!isset($tree->data[$current_menu_id])) {
        return '';
    }
    
    $path = array();
    $current_id = $current_menu_id;
    
    // 向上追溯构建路径
    while ($current_id != 0 && isset($tree->data[$current_id])) {
        array_unshift($path, $tree->data[$current_id]);
        $current_id = $tree->data[$current_id]['parentid'];
    }
    
    $html = "<nav class='breadcrumb'>\n";
    $html .= "  <ol class='breadcrumb-list'>\n";
    
    $total = count($path);
    foreach ($path as $index => $item) {
        $is_last = ($index == $total - 1);
        $class = $is_last ? "breadcrumb-item current" : "breadcrumb-item";
        
        $html .= "    <li class='{$class}'>\n";
        
        if ($is_last) {
            $html .= "      <span>{$item['name']}</span>\n";
        } else {
            $html .= "      <a href='{$item['url']}'>{$item['name']}</a>\n";
            $html .= "      <span class='separator'>/</span>\n";
        }
        
        $html .= "    </li>\n";
    }
    
    $html .= "  </ol>\n";
    $html .= "</nav>\n";
    
    return $html;
}

echo "面包屑导航示例（当前页面：旗舰系列）：\n";
echo generateBreadcrumb($tree, 10); // 旗舰系列的ID是10
echo "\n";

// =============================================
// 4. 侧边栏菜单生成
// =============================================

echo "===== 4. 侧边栏菜单生成 =====\n\n";

function generateSidebarMenu($tree, $parent_id = 0, $current_id = null, $level = 0) {
    $children = $tree->getChildren($parent_id);
    if (!$children) return '';
    
    $indent = str_repeat("  ", $level * 2);
    $class = $level == 0 ? "sidebar-menu" : "sub-menu";
    
    $html = "{$indent}<ul class='{$class}'>\n";
    
    foreach ($children as $child) {
        $has_children = !$tree->isLeaf($child['id']);
        $is_current = ($child['id'] == $current_id);
        $is_active = isInPath($tree, $child['id'], $current_id);
        
        $item_classes = array();
        if ($has_children) $item_classes[] = "has-children";
        if ($is_current) $item_classes[] = "current";
        if ($is_active) $item_classes[] = "active";
        
        $class_str = empty($item_classes) ? "" : " class='" . implode(" ", $item_classes) . "'";
        
        $html .= "{$indent}  <li{$class_str}>\n";
        $html .= "{$indent}    <a href='{$child['url']}'>\n";
        $html .= "{$indent}      <i class='icon-{$child['icon']}'></i>\n";
        $html .= "{$indent}      <span>{$child['name']}</span>\n";
        
        if ($has_children) {
            $html .= "{$indent}      <i class='toggle-icon'></i>\n";
        }
        
        $html .= "{$indent}    </a>\n";
        
        // 如果有子菜单且当前路径经过此节点，则展开子菜单
        if ($has_children && $is_active) {
            $html .= generateSidebarMenu($tree, $child['id'], $current_id, $level + 1);
        }
        
        $html .= "{$indent}  </li>\n";
    }
    
    $html .= "{$indent}</ul>\n";
    
    return $html;
}

// 辅助函数：检查指定节点是否在当前路径上
function isInPath($tree, $node_id, $current_id) {
    if (!$current_id || !isset($tree->data[$current_id])) {
        return false;
    }
    
    $temp_id = $current_id;
    while ($temp_id != 0 && isset($tree->data[$temp_id])) {
        if ($temp_id == $node_id) {
            return true;
        }
        $temp_id = $tree->data[$temp_id]['parentid'];
    }
    
    return false;
}

echo "侧边栏菜单示例（当前页面：中端系列）：\n";
echo generateSidebarMenu($tree, 0, 11); // 中端系列的ID是11
echo "\n";

// =============================================
// 5. 手机端菜单生成
// =============================================

echo "===== 5. 手机端菜单生成 =====\n\n";

function generateMobileMenu($tree, $parent_id = 0, $level = 0) {
    $children = $tree->getChildren($parent_id);
    if (!$children) return '';
    
    $html = '';
    
    foreach ($children as $child) {
        $has_children = !$tree->isLeaf($child['id']);
        $indent = str_repeat("  ", $level);
        
        $html .= "{$indent}<div class='mobile-menu-item level-{$level}'>\n";
        $html .= "{$indent}  <div class='menu-header'>\n";
        $html .= "{$indent}    <a href='{$child['url']}' class='menu-link'>\n";
        $html .= "{$indent}      <i class='icon-{$child['icon']}'></i>\n";
        $html .= "{$indent}      <span>{$child['name']}</span>\n";
        $html .= "{$indent}    </a>\n";
        
        if ($has_children) {
            $html .= "{$indent}    <button class='toggle-btn' data-target='submenu-{$child['id']}'>\n";
            $html .= "{$indent}      <i class='icon-chevron-down'></i>\n";
            $html .= "{$indent}    </button>\n";
        }
        
        $html .= "{$indent}  </div>\n";
        
        // 递归生成子菜单
        if ($has_children) {
            $html .= "{$indent}  <div class='submenu' id='submenu-{$child['id']}'>\n";
            $html .= generateMobileMenu($tree, $child['id'], $level + 1);
            $html .= "{$indent}  </div>\n";
        }
        
        $html .= "{$indent}</div>\n";
    }
    
    return $html;
}

echo "手机端菜单HTML：\n";
echo "<div class='mobile-menu'>\n";
echo generateMobileMenu($tree);
echo "</div>\n\n";

// =============================================
// 6. 菜单统计信息
// =============================================

echo "===== 6. 菜单统计信息 =====\n\n";

function getMenuStats($tree) {
    $total_menus = count($tree->data);
    $root_menus = count($tree->getRoots());
    $leaf_menus = 0;
    $max_depth = 0;
    $menu_by_level = array();
    
    foreach ($tree->data as $id => $menu) {
        if ($tree->isLeaf($id)) {
            $leaf_menus++;
        }
        
        $depth = $tree->getDepth($id);
        $max_depth = max($max_depth, $depth);
        
        if (!isset($menu_by_level[$depth])) {
            $menu_by_level[$depth] = 0;
        }
        $menu_by_level[$depth]++;
    }
    
    return array(
        'total' => $total_menus,
        'roots' => $root_menus,
        'leaves' => $leaf_menus,
        'max_depth' => $max_depth,
        'by_level' => $menu_by_level
    );
}

$stats = getMenuStats($tree);

echo "📊 菜单系统统计：\n";
echo "- 总菜单数：{$stats['total']} 个\n";
echo "- 一级菜单：{$stats['roots']} 个\n";
echo "- 叶子菜单：{$stats['leaves']} 个\n";
echo "- 最大层级：{$stats['max_depth']} 层\n\n";

echo "各层级菜单分布：\n";
foreach ($stats['by_level'] as $level => $count) {
    echo "- 第 {$level} 层：{$count} 个菜单\n";
}
echo "\n";

// =============================================
// 7. 菜单权限控制示例
// =============================================

echo "===== 7. 菜单权限控制示例 =====\n\n";

// 模拟用户权限（用户可访问的菜单ID列表）
$user_permissions = array(1, 2, 6, 7, 10, 11, 4, 16, 17, 5, 20, 22);

function generatePermissionFilteredMenu($tree, $permissions, $parent_id = 0) {
    $children = $tree->getChildren($parent_id);
    if (!$children) return '';
    
    $html = "<ul class='filtered-menu'>\n";
    
    foreach ($children as $child) {
        // 检查用户是否有权限访问此菜单
        if (!in_array($child['id'], $permissions)) {
            continue;
        }
        
        $has_children = !$tree->isLeaf($child['id']);
        
        $html .= "  <li class='menu-item'>\n";
        $html .= "    <a href='{$child['url']}'>\n";
        $html .= "      <i class='icon-{$child['icon']}'></i>\n";
        $html .= "      {$child['name']}\n";
        $html .= "    </a>\n";
        
        // 递归处理子菜单
        if ($has_children) {
            $submenu = generatePermissionFilteredMenu($tree, $permissions, $child['id']);
            if (!empty(trim($submenu))) {
                $html .= $submenu;
            }
        }
        
        $html .= "  </li>\n";
    }
    
    $html .= "</ul>\n";
    
    return $html;
}

echo "根据用户权限过滤后的菜单：\n";
echo generatePermissionFilteredMenu($tree, $user_permissions);
echo "\n";

// =============================================
// 总结
// =============================================

echo "===== 🎉 菜单系统演示总结 =====\n\n";

echo "本演示展示了Tree类在网站菜单系统中的应用：\n\n";

echo "✅ 核心功能：\n";
echo "1. 多级菜单结构管理\n";
echo "2. 各种格式的菜单HTML生成\n";
echo "3. 面包屑导航生成\n";
echo "4. 当前位置识别和高亮\n";
echo "5. 权限控制和菜单过滤\n\n";

echo "🔧 技术要点：\n";
echo "1. 递归遍历生成嵌套结构\n";
echo "2. 路径追溯实现面包屑\n";
echo "3. 条件判断控制菜单展开\n";
echo "4. 权限数组过滤可访问菜单\n\n";

echo "📱 适用场景：\n";
echo "1. 企业网站导航系统\n";
echo "2. 电商网站分类菜单\n";
echo "3. 后台管理系统菜单\n";
echo "4. 移动端折叠菜单\n\n";

echo "接下来你可以：\n";
echo "1. 修改菜单数据测试不同结构\n";
echo "2. 尝试添加更多菜单属性（如排序、描述等）\n";
echo "3. 实现菜单的增删改功能\n";
echo "4. 继续学习 category_demo.php 中的分类管理应用\n";
?>
