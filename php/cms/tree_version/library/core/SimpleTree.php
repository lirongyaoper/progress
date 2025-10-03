<?php
/**
 * SimpleTree.php - 简化版Tree类
 * 
 * 这是专门为初学者设计的简化版Tree类
 * 只包含最核心的功能，代码简洁易懂
 * 
 * 学习目标：
 * 1. 理解树形数据的基本操作
 * 2. 掌握获取子节点和父节点的方法
 * 3. 了解递归的基本应用
 * 
 * @author 学习项目
 * @version 1.0 (简化版)
 */

class SimpleTree {
    
    /**
     * 存储树形数据的二维数组
     * @var array
     */
    public $data = array();
    
    /**
     * 初始化方法 - 设置要处理的数据
     * 
     * @param array $data 二维数组，格式如：
     *   array(
     *       1 => array('id' => 1, 'parentid' => 0, 'name' => '根节点'),
     *       2 => array('id' => 2, 'parentid' => 1, 'name' => '子节点')
     *   )
     * @return boolean 成功返回true，失败返回false
     */
    public function init($data = array()) {
        // 检查输入是否为数组
        if (!is_array($data)) {
            return false;
        }
        
        // 保存数据到类属性中
        $this->data = $data;
        
        return true;
    }
    
    /**
     * 获取指定节点的所有子节点
     * 
     * 这是最基础也是最重要的方法
     * 
     * @param int $parent_id 父节点的ID
     * @return array|false 返回子节点数组，没有子节点则返回false
     */
    public function getChildren($parent_id) {
        $children = array();
        
        // 遍历所有数据，找出parentid等于指定ID的记录
        foreach ($this->data as $id => $item) {
            if ($item['parentid'] == $parent_id) {
                $children[$id] = $item;
            }
        }
        
        // 如果找到了子节点就返回，否则返回false
        return empty($children) ? false : $children;
    }
    
    /**
     * 获取指定节点的父节点
     * 
     * @param int $node_id 节点ID
     * @return array|false 返回父节点信息，如果是根节点或节点不存在则返回false
     */
    public function getParent($node_id) {
        // 首先检查节点是否存在
        if (!isset($this->data[$node_id])) {
            return false;
        }
        
        $parent_id = $this->data[$node_id]['parentid'];
        
        // 如果parentid是0，说明是根节点，没有父节点
        if ($parent_id == 0) {
            return false;
        }
        
        // 查找父节点
        if (isset($this->data[$parent_id])) {
            return $this->data[$parent_id];
        }
        
        return false;
    }
    
    /**
     * 递归获取指定节点下的所有子孙节点
     * 
     * 这个方法演示了递归的基本应用
     * 
     * @param int $parent_id 父节点ID
     * @return array 所有子孙节点的数组
     */
    public function getAllDescendants($parent_id) {
        $result = array();
        
        // 获取直接子节点
        $children = $this->getChildren($parent_id);
        
        if ($children) {
            foreach ($children as $child_id => $child) {
                // 添加当前子节点
                $result[$child_id] = $child;
                
                // 递归获取子节点的子节点
                $grandchildren = $this->getAllDescendants($child_id);
                if ($grandchildren) {
                    $result = array_merge($result, $grandchildren);
                }
            }
        }
        
        return $result;
    }
    
    /**
     * 生成简单的树形文本结构
     * 
     * 用于在控制台或网页上显示树形结构
     * 
     * @param int $parent_id 起始节点ID（通常是0，表示从根节点开始）
     * @param string $prefix 前缀字符串（用于递归时的缩进）
     * @return string 树形文本字符串
     */
    public function generateTreeText($parent_id = 0, $prefix = '') {
        $result = '';
        $children = $this->getChildren($parent_id);
        
        if ($children) {
            $count = count($children);
            $index = 0;
            
            foreach ($children as $child_id => $child) {
                $index++;
                $is_last = ($index == $count);
                
                // 根据是否是最后一个子节点来确定前缀字符
                if ($is_last) {
                    $current_prefix = $prefix . '└── ';
                    $next_prefix = $prefix . '    ';
                } else {
                    $current_prefix = $prefix . '├── ';
                    $next_prefix = $prefix . '│   ';
                }
                
                // 添加当前节点
                $result .= $current_prefix . $child['name'] . "\n";
                
                // 递归处理子节点
                $result .= $this->generateTreeText($child_id, $next_prefix);
            }
        }
        
        return $result;
    }
    
    /**
     * 检查一个节点是否是叶子节点（没有子节点）
     * 
     * @param int $node_id 节点ID
     * @return boolean 是叶子节点返回true，否则返回false
     */
    public function isLeaf($node_id) {
        $children = $this->getChildren($node_id);
        return ($children === false);
    }
    
    /**
     * 获取树的根节点（所有parentid为0的节点）
     * 
     * @return array 根节点数组
     */
    public function getRoots() {
        return $this->getChildren(0);
    }
    
    /**
     * 计算指定节点的深度（层级）
     * 
     * @param int $node_id 节点ID
     * @return int 返回节点深度，根节点深度为1
     */
    public function getDepth($node_id) {
        if (!isset($this->data[$node_id])) {
            return 0;
        }
        
        $depth = 1;
        $current_id = $node_id;
        
        // 向上遍历到根节点
        while ($this->data[$current_id]['parentid'] != 0) {
            $parent_id = $this->data[$current_id]['parentid'];
            if (!isset($this->data[$parent_id])) {
                break; // 防止无限循环
            }
            $depth++;
            $current_id = $parent_id;
        }
        
        return $depth;
    }
    
    /**
     * 打印数据结构（用于调试）
     * 
     * @return void
     */
    public function printData() {
        echo "=== 当前数据结构 ===\n";
        foreach ($this->data as $id => $item) {
            echo "ID: {$id}, ParentID: {$item['parentid']}, Name: {$item['name']}\n";
        }
        echo "===================\n\n";
    }
}

/**
 * 使用示例和说明
 * 
 * 如果直接运行这个文件，会显示使用示例
 */
if (basename(__FILE__) == basename($_SERVER["SCRIPT_NAME"])) {
    echo "=== SimpleTree 使用示例 ===\n\n";
    
    // 1. 准备测试数据
    $data = array(
        1 => array('id' => 1, 'parentid' => 0, 'name' => '电子产品'),
        2 => array('id' => 2, 'parentid' => 1, 'name' => '手机'),
        3 => array('id' => 3, 'parentid' => 1, 'name' => '电脑'),
        4 => array('id' => 4, 'parentid' => 2, 'name' => 'iPhone'),
        5 => array('id' => 5, 'parentid' => 2, 'name' => '华为手机'),
        6 => array('id' => 6, 'parentid' => 3, 'name' => '笔记本电脑'),
        7 => array('id' => 7, 'parentid' => 3, 'name' => '台式电脑')
    );
    
    // 2. 创建Tree实例并初始化
    $tree = new SimpleTree();
    $tree->init($data);
    
    // 3. 显示原始数据
    $tree->printData();
    
    // 4. 生成树形结构
    echo "=== 树形结构显示 ===\n";
    echo $tree->generateTreeText();
    echo "\n";
    
    // 5. 测试基本方法
    echo "=== 功能测试 ===\n";
    
    // 获取根节点
    $roots = $tree->getRoots();
    echo "根节点数量: " . count($roots) . "\n";
    
    // 获取手机分类的子节点
    $phone_children = $tree->getChildren(2);
    if ($phone_children) {
        echo "手机分类的子项目:\n";
        foreach ($phone_children as $child) {
            echo "  - " . $child['name'] . "\n";
        }
    }
    
    // 检查节点深度
    echo "iPhone的层级深度: " . $tree->getDepth(4) . "\n";
    
    // 检查是否为叶子节点
    echo "iPhone是叶子节点吗? " . ($tree->isLeaf(4) ? "是" : "否") . "\n";
    echo "电脑是叶子节点吗? " . ($tree->isLeaf(3) ? "是" : "否") . "\n";
    
    echo "\n=== 示例结束 ===\n";
}
?>
