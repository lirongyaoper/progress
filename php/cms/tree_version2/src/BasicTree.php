<?php
/**
 * BasicTree.php - 基础版Tree类
 * 
 * 这是Tree类的第二阶段版本，在SimpleTree基础上增加了更多实用功能
 * 适合已经掌握基础操作的学习者进一步学习
 * 
 * 新增功能：
 * 1. 数组转树形结构
 * 2. 树形结构转数组
 * 3. 路径查找和路径字符串生成
 * 4. 节点移动和删除
 * 5. 数据验证和完整性检查
 * 
 * @author 学习项目
 * @version 2.0 (基础版)
 */

// 继承SimpleTree类，复用基础功能
require_once __DIR__ . '/SimpleTree.php';

class BasicTree extends SimpleTree {
    
    /**
     * 将扁平化数组转换为树形结构
     * 
     * 这是一个非常实用的方法，可以将数据库查询结果直接转换为树形结构
     * 
     * @param int $parent_id 起始节点ID（默认从根节点开始）
     * @return array 树形结构数组
     */
    public function toTree($parent_id = 0) {
        $tree = array();
        $children = $this->getChildren($parent_id);
        
        if ($children) {
            foreach ($children as $child_id => $child) {
                // 递归获取子节点的树形结构
                $child['children'] = $this->toTree($child_id);
                $tree[] = $child;
            }
        }
        
        return $tree;
    }
    
    /**
     * 将树形结构转换为扁平化数组
     * 
     * 与toTree()方法相反，将树形结构还原为扁平数组
     * 
     * @param array $tree 树形结构数组
     * @param int $parent_id 父节点ID
     * @return array 扁平化数组
     */
    public function fromTree($tree, $parent_id = 0) {
        $result = array();
        
        foreach ($tree as $item) {
            // 设置父节点ID
            $item['parentid'] = $parent_id;
            
            // 提取子节点
            $children = isset($item['children']) ? $item['children'] : array();
            unset($item['children']);
            
            // 添加当前节点
            $result[$item['id']] = $item;
            
            // 递归处理子节点
            if (!empty($children)) {
                $child_result = $this->fromTree($children, $item['id']);
                $result = array_merge($result, $child_result);
            }
        }
        
        return $result;
    }
    
    /**
     * 获取从根节点到指定节点的完整路径
     * 
     * @param int $node_id 目标节点ID
     * @return array|false 路径节点数组，失败返回false
     */
    public function getPath($node_id) {
        if (!isset($this->data[$node_id])) {
            return false;
        }
        
        $path = array();
        $current_id = $node_id;
        
        // 向上追溯到根节点
        while ($current_id != 0 && isset($this->data[$current_id])) {
            array_unshift($path, $this->data[$current_id]);
            $current_id = $this->data[$current_id]['parentid'];
        }
        
        return $path;
    }
    
    /**
     * 生成路径字符串
     * 
     * @param int $node_id 目标节点ID
     * @param string $separator 分隔符（默认为' > '）
     * @param string $field 用于显示的字段名（默认为'name'）
     * @return string|false 路径字符串，失败返回false
     */
    public function getPathString($node_id, $separator = ' > ', $field = 'name') {
        $path = $this->getPath($node_id);
        
        if ($path === false) {
            return false;
        }
        
        $names = array();
        foreach ($path as $node) {
            if (isset($node[$field])) {
                $names[] = $node[$field];
            }
        }
        
        return implode($separator, $names);
    }
    
    /**
     * 移动节点到新的父节点下
     * 
     * @param int $node_id 要移动的节点ID
     * @param int $new_parent_id 新的父节点ID
     * @return boolean 成功返回true，失败返回false
     */
    public function moveNode($node_id, $new_parent_id) {
        // 检查节点是否存在
        if (!isset($this->data[$node_id])) {
            return false;
        }
        
        // 检查新父节点是否存在（0表示根节点，允许）
        if ($new_parent_id != 0 && !isset($this->data[$new_parent_id])) {
            return false;
        }
        
        // 检查是否试图移动到自己的子孙节点下（防止循环引用）
        if ($this->isAncestor($node_id, $new_parent_id)) {
            return false;
        }
        
        // 执行移动
        $this->data[$node_id]['parentid'] = $new_parent_id;
        
        return true;
    }
    
    /**
     * 检查第一个节点是否是第二个节点的祖先节点
     * 
     * @param int $ancestor_id 祖先节点ID
     * @param int $descendant_id 后代节点ID
     * @return boolean 是祖先返回true，否则返回false
     */
    public function isAncestor($ancestor_id, $descendant_id) {
        if ($descendant_id == 0) {
            return false;
        }
        
        $current_id = $descendant_id;
        
        while ($current_id != 0 && isset($this->data[$current_id])) {
            if ($current_id == $ancestor_id) {
                return true;
            }
            $current_id = $this->data[$current_id]['parentid'];
        }
        
        return false;
    }
    
    /**
     * 删除节点及其所有子孙节点
     * 
     * @param int $node_id 要删除的节点ID
     * @return boolean 成功返回true，失败返回false
     */
    public function deleteNode($node_id) {
        if (!isset($this->data[$node_id])) {
            return false;
        }
        
        // 获取所有子孙节点
        $descendants = $this->getAllDescendants($node_id);
        
        // 删除所有子孙节点
        if ($descendants) {
            foreach ($descendants as $descendant_id => $descendant) {
                unset($this->data[$descendant_id]);
            }
        }
        
        // 删除节点本身
        unset($this->data[$node_id]);
        
        return true;
    }
    
    /**
     * 获取节点的兄弟节点
     * 
     * @param int $node_id 节点ID
     * @param boolean $include_self 是否包含自己（默认不包含）
     * @return array|false 兄弟节点数组，失败返回false
     */
    public function getSiblings($node_id, $include_self = false) {
        if (!isset($this->data[$node_id])) {
            return false;
        }
        
        $parent_id = $this->data[$node_id]['parentid'];
        $siblings = $this->getChildren($parent_id);
        
        if (!$siblings) {
            return false;
        }
        
        // 如果不包含自己，则移除自己
        if (!$include_self && isset($siblings[$node_id])) {
            unset($siblings[$node_id]);
        }
        
        return empty($siblings) ? false : $siblings;
    }
    
    /**
     * 获取树的最大深度
     * 
     * @param int $parent_id 起始节点ID（默认从根节点开始）
     * @return int 最大深度
     */
    public function getMaxDepth($parent_id = 0) {
        $max_depth = 0;
        $children = $this->getChildren($parent_id);
        
        if ($children) {
            foreach ($children as $child_id => $child) {
                $child_depth = $this->getMaxDepth($child_id);
                $max_depth = max($max_depth, $child_depth);
            }
        }
        
        return $max_depth + 1;
    }
    
    /**
     * 统计树的节点总数
     * 
     * @param int $parent_id 起始节点ID（默认统计整个树）
     * @return int 节点数量
     */
    public function getNodeCount($parent_id = null) {
        if ($parent_id === null) {
            return count($this->data);
        }
        
        $count = 0;
        $children = $this->getChildren($parent_id);
        
        if ($children) {
            $count = count($children);
            foreach ($children as $child_id => $child) {
                $count += $this->getNodeCount($child_id);
            }
        }
        
        return $count;
    }
    
    /**
     * 验证数据完整性
     * 
     * 检查是否存在孤儿节点、循环引用等问题
     * 
     * @return array 问题列表，如果没有问题返回空数组
     */
    public function validateData() {
        $issues = array();
        
        foreach ($this->data as $id => $node) {
            $parent_id = $node['parentid'];
            
            // 检查父节点是否存在（除了根节点）
            if ($parent_id != 0 && !isset($this->data[$parent_id])) {
                $issues[] = "节点 {$id} 的父节点 {$parent_id} 不存在（孤儿节点）";
            }
            
            // 检查是否存在循环引用
            if ($parent_id != 0 && $this->hasCircularReference($id)) {
                $issues[] = "节点 {$id} 存在循环引用";
            }
            
            // 检查必要字段
            if (!isset($node['id']) || !isset($node['parentid'])) {
                $issues[] = "节点 {$id} 缺少必要字段";
            }
        }
        
        return $issues;
    }
    
    /**
     * 检查是否存在循环引用
     * 
     * @param int $node_id 起始节点ID
     * @return boolean 存在循环引用返回true
     */
    private function hasCircularReference($node_id) {
        $visited = array();
        $current_id = $node_id;
        
        while ($current_id != 0 && isset($this->data[$current_id])) {
            if (in_array($current_id, $visited)) {
                return true; // 发现循环
            }
            
            $visited[] = $current_id;
            $current_id = $this->data[$current_id]['parentid'];
        }
        
        return false;
    }
    
    /**
     * 生成选择框选项HTML
     * 
     * @param int $parent_id 起始节点ID
     * @param string $prefix 前缀字符串
     * @param int $selected_id 选中的节点ID
     * @param array $disabled_ids 禁用的节点ID数组
     * @return string HTML字符串
     */
    public function generateSelectOptions($parent_id = 0, $prefix = '', $selected_id = null, $disabled_ids = array()) {
        $html = '';
        $children = $this->getChildren($parent_id);
        
        if ($children) {
            foreach ($children as $child_id => $child) {
                $option_text = $prefix . $child['name'];
                $selected = ($selected_id == $child_id) ? ' selected="selected"' : '';
                $disabled = in_array($child_id, $disabled_ids) ? ' disabled="disabled"' : '';
                
                $html .= "<option value='{$child_id}'{$selected}{$disabled}>{$option_text}</option>\n";
                
                // 递归生成子选项
                $html .= $this->generateSelectOptions($child_id, $prefix . '├─ ', $selected_id, $disabled_ids);
            }
        }
        
        return $html;
    }
    
    /**
     * 生成JSON格式的树形数据
     * 
     * @param int $parent_id 起始节点ID
     * @return string JSON字符串
     */
    public function toJSON($parent_id = 0) {
        $tree = $this->toTree($parent_id);
        return json_encode($tree, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
    
    /**
     * 从JSON数据初始化树
     * 
     * @param string $json_data JSON字符串
     * @return boolean 成功返回true，失败返回false
     */
    public function fromJSON($json_data) {
        $tree = json_decode($json_data, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return false;
        }
        
        $flat_data = $this->fromTree($tree);
        return $this->init($flat_data);
    }
}

/**
 * 使用示例和说明
 */
if (basename(__FILE__) == basename($_SERVER["SCRIPT_NAME"])) {
    echo "=== BasicTree 使用示例 ===\n\n";
    
    // 1. 准备测试数据
    $data = array(
        1 => array('id' => 1, 'parentid' => 0, 'name' => '总部', 'city' => '北京'),
        2 => array('id' => 2, 'parentid' => 1, 'name' => '华北区', 'city' => '北京'),
        3 => array('id' => 3, 'parentid' => 1, 'name' => '华南区', 'city' => '深圳'),
        4 => array('id' => 4, 'parentid' => 2, 'name' => '北京分公司', 'city' => '北京'),
        5 => array('id' => 5, 'parentid' => 2, 'name' => '天津分公司', 'city' => '天津'),
        6 => array('id' => 6, 'parentid' => 3, 'name' => '深圳分公司', 'city' => '深圳'),
        7 => array('id' => 7, 'parentid' => 3, 'name' => '广州分公司', 'city' => '广州'),
        8 => array('id' => 8, 'parentid' => 4, 'name' => '朝阳办事处', 'city' => '北京'),
        9 => array('id' => 9, 'parentid' => 4, 'name' => '海淀办事处', 'city' => '北京')
    );
    
    $tree = new BasicTree();
    $tree->init($data);
    
    echo "原始数据结构：\n";
    echo $tree->generateTreeText();
    echo "\n";
    
    // 2. 测试路径功能
    echo "=== 路径功能测试 ===\n";
    $path = $tree->getPath(8);
    echo "朝阳办事处的完整路径：\n";
    foreach ($path as $node) {
        echo "  - {$node['name']} ({$node['city']})\n";
    }
    
    $path_string = $tree->getPathString(8);
    echo "路径字符串: {$path_string}\n\n";
    
    // 3. 测试树形结构转换
    echo "=== 树形结构转换 ===\n";
    $tree_structure = $tree->toTree();
    echo "转换为树形结构（JSON格式）：\n";
    echo $tree->toJSON();
    echo "\n";
    
    // 4. 测试节点操作
    echo "=== 节点操作测试 ===\n";
    
    // 移动节点
    echo "将'朝阳办事处'移动到'华南区'下：\n";
    $tree->moveNode(8, 3);
    echo $tree->generateTreeText();
    echo "\n";
    
    // 获取兄弟节点
    $siblings = $tree->getSiblings(6);
    echo "深圳分公司的兄弟节点：\n";
    if ($siblings) {
        foreach ($siblings as $sibling) {
            echo "  - {$sibling['name']}\n";
        }
    }
    echo "\n";
    
    // 5. 测试统计功能
    echo "=== 统计功能 ===\n";
    echo "最大深度: " . $tree->getMaxDepth() . "\n";
    echo "总节点数: " . $tree->getNodeCount() . "\n";
    echo "华南区子节点数: " . $tree->getNodeCount(3) . "\n\n";
    
    // 6. 数据验证
    echo "=== 数据验证 ===\n";
    $issues = $tree->validateData();
    if (empty($issues)) {
        echo "✅ 数据完整性检查通过\n";
    } else {
        echo "❌ 发现以下问题：\n";
        foreach ($issues as $issue) {
            echo "  - {$issue}\n";
        }
    }
    echo "\n";
    
    // 7. 生成选择框
    echo "=== 选择框HTML ===\n";
    echo "<select name='company'>\n";
    echo $tree->generateSelectOptions(0, '', 6);
    echo "</select>\n\n";
    
    echo "=== BasicTree示例结束 ===\n";
}
?>
