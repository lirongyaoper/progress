<?php
/**
 * FullTree.php - 完整版Tree类
 * 
 * 这是Tree类的最终版本，包含所有高级功能和性能优化
 * 适合有一定基础的开发者学习和在生产环境中使用
 * 
 * 高级功能：
 * 1. 缓存机制 - 提高查询性能
 * 2. 批量操作 - 高效处理大量数据
 * 3. 排序功能 - 支持多种排序方式
 * 4. 过滤和搜索 - 条件查询功能
 * 5. 导入导出 - 支持多种格式
 * 6. 性能监控 - 查询性能统计
 * 7. 事件回调 - 支持自定义事件处理
 * 
 * @author 学习项目
 * @version 3.0 (完整版)
 */

// 继承BasicTree类，复用所有基础功能
require_once __DIR__ . '/BasicTree.php';

class FullTree extends BasicTree {
    
    /**
     * 缓存存储
     * @var array
     */
    private $cache = array();
    
    /**
     * 是否启用缓存
     * @var boolean
     */
    private $cache_enabled = true;
    
    /**
     * 性能统计
     * @var array
     */
    private $performance_stats = array(
        'query_count' => 0,
        'cache_hits' => 0,
        'total_time' => 0
    );
    
    /**
     * 事件回调
     * @var array
     */
    private $event_callbacks = array();
    
    /**
     * 排序配置
     * @var array
     */
    private $sort_config = array(
        'field' => 'name',
        'direction' => 'ASC'
    );
    
    /**
     * 设置缓存状态
     * 
     * @param boolean $enabled 是否启用缓存
     */
    public function setCacheEnabled($enabled = true) {
        $this->cache_enabled = $enabled;
        if (!$enabled) {
            $this->clearCache();
        }
    }
    
    /**
     * 清空缓存
     */
    public function clearCache() {
        $this->cache = array();
    }
    
    /**
     * 重写父类的getChildren方法，添加缓存支持
     * 
     * @param int $parent_id 父节点ID
     * @return array|false 子节点数组
     */
    public function getChildren($parent_id) {
        $start_time = microtime(true);
        $cache_key = "children_{$parent_id}";
        
        // 尝试从缓存获取
        if ($this->cache_enabled && isset($this->cache[$cache_key])) {
            $this->performance_stats['cache_hits']++;
            return $this->cache[$cache_key];
        }
        
        // 调用父类方法
        $result = parent::getChildren($parent_id);
        
        // 应用排序
        if ($result && !empty($this->sort_config['field'])) {
            $result = $this->sortNodes($result);
        }
        
        // 存入缓存
        if ($this->cache_enabled && $result !== false) {
            $this->cache[$cache_key] = $result;
        }
        
        // 记录性能统计
        $this->performance_stats['query_count']++;
        $this->performance_stats['total_time'] += (microtime(true) - $start_time);
        
        // 触发事件
        $this->triggerEvent('getChildren', array(
            'parent_id' => $parent_id,
            'result' => $result
        ));
        
        return $result;
    }
    
    /**
     * 设置排序配置
     * 
     * @param string $field 排序字段
     * @param string $direction 排序方向 (ASC/DESC)
     */
    public function setSortConfig($field = 'name', $direction = 'ASC') {
        $this->sort_config = array(
            'field' => $field,
            'direction' => strtoupper($direction)
        );
        
        // 清空缓存，因为排序配置改变了
        $this->clearCache();
    }
    
    /**
     * 对节点数组进行排序
     * 
     * @param array $nodes 节点数组
     * @return array 排序后的数组
     */
    private function sortNodes($nodes) {
        if (empty($nodes) || empty($this->sort_config['field'])) {
            return $nodes;
        }
        
        $field = $this->sort_config['field'];
        $direction = $this->sort_config['direction'];
        
        uasort($nodes, function($a, $b) use ($field, $direction) {
            if (!isset($a[$field]) || !isset($b[$field])) {
                return 0;
            }
            
            $result = strcmp($a[$field], $b[$field]);
            return ($direction === 'DESC') ? -$result : $result;
        });
        
        return $nodes;
    }
    
    /**
     * 批量添加节点
     * 
     * @param array $nodes 节点数组
     * @return boolean 成功返回true
     */
    public function batchAddNodes($nodes) {
        if (!is_array($nodes)) {
            return false;
        }
        
        foreach ($nodes as $node) {
            if (isset($node['id'])) {
                $this->data[$node['id']] = $node;
            }
        }
        
        // 清空缓存
        $this->clearCache();
        
        // 触发事件
        $this->triggerEvent('batchAdd', array('nodes' => $nodes));
        
        return true;
    }
    
    /**
     * 批量删除节点
     * 
     * @param array $node_ids 节点ID数组
     * @return boolean 成功返回true
     */
    public function batchDeleteNodes($node_ids) {
        if (!is_array($node_ids)) {
            return false;
        }
        
        foreach ($node_ids as $node_id) {
            $this->deleteNode($node_id);
        }
        
        return true;
    }
    
    /**
     * 搜索节点
     * 
     * @param string $keyword 搜索关键词
     * @param array $fields 搜索字段
     * @param boolean $fuzzy 是否模糊搜索
     * @return array 搜索结果
     */
    public function searchNodes($keyword, $fields = array('name'), $fuzzy = true) {
        $results = array();
        
        foreach ($this->data as $id => $node) {
            foreach ($fields as $field) {
                if (isset($node[$field])) {
                    $found = false;
                    
                    if ($fuzzy) {
                        $found = (stripos($node[$field], $keyword) !== false);
                    } else {
                        $found = (strcasecmp($node[$field], $keyword) === 0);
                    }
                    
                    if ($found) {
                        $results[$id] = $node;
                        break; // 找到一个匹配就够了
                    }
                }
            }
        }
        
        return $results;
    }
    
    /**
     * 按条件过滤节点
     * 
     * @param array $conditions 过滤条件
     * @return array 过滤结果
     */
    public function filterNodes($conditions) {
        $results = array();
        
        foreach ($this->data as $id => $node) {
            $match = true;
            
            foreach ($conditions as $field => $value) {
                if (!isset($node[$field]) || $node[$field] != $value) {
                    $match = false;
                    break;
                }
            }
            
            if ($match) {
                $results[$id] = $node;
            }
        }
        
        return $results;
    }
    
    /**
     * 获取节点的所有祖先节点
     * 
     * @param int $node_id 节点ID
     * @return array 祖先节点数组
     */
    public function getAncestors($node_id) {
        $ancestors = array();
        $current_id = $node_id;
        
        while ($current_id != 0 && isset($this->data[$current_id])) {
            $parent_id = $this->data[$current_id]['parentid'];
            if ($parent_id != 0 && isset($this->data[$parent_id])) {
                $ancestors[] = $this->data[$parent_id];
            }
            $current_id = $parent_id;
        }
        
        return array_reverse($ancestors); // 返回从根到直接父节点的顺序
    }
    
    /**
     * 获取两个节点的最近公共祖先
     * 
     * @param int $node_id1 节点1的ID
     * @param int $node_id2 节点2的ID
     * @return array|false 公共祖先节点，没有则返回false
     */
    public function getLowestCommonAncestor($node_id1, $node_id2) {
        $path1 = $this->getPath($node_id1);
        $path2 = $this->getPath($node_id2);
        
        if (!$path1 || !$path2) {
            return false;
        }
        
        $common_ancestor = null;
        $min_length = min(count($path1), count($path2));
        
        for ($i = 0; $i < $min_length; $i++) {
            if ($path1[$i]['id'] == $path2[$i]['id']) {
                $common_ancestor = $path1[$i];
            } else {
                break;
            }
        }
        
        return $common_ancestor;
    }
    
    /**
     * 复制节点及其子树到新位置
     * 
     * @param int $source_id 源节点ID
     * @param int $target_parent_id 目标父节点ID
     * @param boolean $deep_copy 是否深度复制（包含子节点）
     * @return int|false 新节点ID，失败返回false
     */
    public function copyNode($source_id, $target_parent_id, $deep_copy = true) {
        if (!isset($this->data[$source_id])) {
            return false;
        }
        
        // 生成新的ID（简单方式：使用当前最大ID+1）
        $new_id = max(array_keys($this->data)) + 1;
        
        // 复制源节点
        $new_node = $this->data[$source_id];
        $new_node['id'] = $new_id;
        $new_node['parentid'] = $target_parent_id;
        $this->data[$new_id] = $new_node;
        
        // 如果是深度复制，递归复制子节点
        if ($deep_copy) {
            $children = $this->getChildren($source_id);
            if ($children) {
                foreach ($children as $child_id => $child) {
                    $this->copyNode($child_id, $new_id, true);
                }
            }
        }
        
        // 清空缓存
        $this->clearCache();
        
        return $new_id;
    }
    
    /**
     * 导出为XML格式
     * 
     * @param int $parent_id 起始节点ID
     * @return string XML字符串
     */
    public function toXML($parent_id = 0) {
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $xml .= "<tree>\n";
        $xml .= $this->generateXMLNodes($parent_id, 1);
        $xml .= "</tree>";
        
        return $xml;
    }
    
    /**
     * 生成XML节点
     * 
     * @param int $parent_id 父节点ID
     * @param int $level 缩进级别
     * @return string XML节点字符串
     */
    private function generateXMLNodes($parent_id, $level) {
        $xml = '';
        $children = $this->getChildren($parent_id);
        $indent = str_repeat('  ', $level);
        
        if ($children) {
            foreach ($children as $child_id => $child) {
                $xml .= "{$indent}<node id=\"{$child['id']}\"";
                
                // 添加其他属性
                foreach ($child as $key => $value) {
                    if ($key != 'id' && $key != 'parentid') {
                        $xml .= " {$key}=\"" . htmlspecialchars($value) . "\"";
                    }
                }
                
                // 检查是否有子节点
                $grandchildren = $this->getChildren($child_id);
                if ($grandchildren) {
                    $xml .= ">\n";
                    $xml .= $this->generateXMLNodes($child_id, $level + 1);
                    $xml .= "{$indent}</node>\n";
                } else {
                    $xml .= " />\n";
                }
            }
        }
        
        return $xml;
    }
    
    /**
     * 导出为CSV格式
     * 
     * @param array $fields 要导出的字段
     * @return string CSV字符串
     */
    public function toCSV($fields = array('id', 'parentid', 'name')) {
        $csv = implode(',', $fields) . "\n";
        
        foreach ($this->data as $node) {
            $row = array();
            foreach ($fields as $field) {
                $value = isset($node[$field]) ? $node[$field] : '';
                $row[] = '"' . str_replace('"', '""', $value) . '"';
            }
            $csv .= implode(',', $row) . "\n";
        }
        
        return $csv;
    }
    
    /**
     * 从CSV导入数据
     * 
     * @param string $csv_data CSV数据
     * @param boolean $has_header 是否包含标题行
     * @return boolean 成功返回true
     */
    public function fromCSV($csv_data, $has_header = true) {
        $lines = explode("\n", trim($csv_data));
        
        if (empty($lines)) {
            return false;
        }
        
        // 处理标题行
        $headers = array();
        if ($has_header) {
            $header_line = array_shift($lines);
            $headers = str_getcsv($header_line);
        } else {
            $headers = array('id', 'parentid', 'name'); // 默认字段
        }
        
        $new_data = array();
        
        foreach ($lines as $line) {
            if (trim($line) === '') continue;
            
            $values = str_getcsv($line);
            $node = array();
            
            for ($i = 0; $i < count($headers); $i++) {
                $field = $headers[$i];
                $value = isset($values[$i]) ? $values[$i] : '';
                $node[$field] = $value;
            }
            
            if (isset($node['id']) && $node['id'] !== '') {
                $new_data[$node['id']] = $node;
            }
        }
        
        return $this->init($new_data);
    }
    
    /**
     * 注册事件回调
     * 
     * @param string $event 事件名称
     * @param callable $callback 回调函数
     */
    public function addEventListener($event, $callback) {
        if (!isset($this->event_callbacks[$event])) {
            $this->event_callbacks[$event] = array();
        }
        
        $this->event_callbacks[$event][] = $callback;
    }
    
    /**
     * 触发事件
     * 
     * @param string $event 事件名称
     * @param array $data 事件数据
     */
    private function triggerEvent($event, $data = array()) {
        if (isset($this->event_callbacks[$event])) {
            foreach ($this->event_callbacks[$event] as $callback) {
                if (is_callable($callback)) {
                    call_user_func($callback, $data);
                }
            }
        }
    }
    
    /**
     * 获取性能统计信息
     * 
     * @return array 性能统计数据
     */
    public function getPerformanceStats() {
        return $this->performance_stats;
    }
    
    /**
     * 重置性能统计
     */
    public function resetPerformanceStats() {
        $this->performance_stats = array(
            'query_count' => 0,
            'cache_hits' => 0,
            'total_time' => 0
        );
    }
    
    /**
     * 生成可视化的HTML树形结构
     * 
     * @param int $parent_id 起始节点ID
     * @param array $options 显示选项
     * @return string HTML字符串
     */
    public function generateHTMLTree($parent_id = 0, $options = array()) {
        $default_options = array(
            'show_icons' => true,
            'show_checkboxes' => false,
            'collapsible' => true,
            'css_class' => 'tree-view'
        );
        
        $options = array_merge($default_options, $options);
        
        $html = "<ul class=\"{$options['css_class']}\">\n";
        $html .= $this->generateHTMLNodes($parent_id, $options, 1);
        $html .= "</ul>\n";
        
        return $html;
    }
    
    /**
     * 生成HTML节点
     * 
     * @param int $parent_id 父节点ID
     * @param array $options 显示选项
     * @param int $level 层级
     * @return string HTML字符串
     */
    private function generateHTMLNodes($parent_id, $options, $level) {
        $html = '';
        $children = $this->getChildren($parent_id);
        
        if ($children) {
            foreach ($children as $child_id => $child) {
                $has_children = ($this->getChildren($child_id) !== false);
                $css_class = $has_children ? 'has-children' : 'leaf';
                
                $html .= "<li class=\"{$css_class}\">";
                
                // 添加复选框
                if ($options['show_checkboxes']) {
                    $html .= "<input type=\"checkbox\" name=\"nodes[]\" value=\"{$child_id}\" />";
                }
                
                // 添加图标
                if ($options['show_icons']) {
                    $icon = $has_children ? '📁' : '📄';
                    $html .= "<span class=\"icon\">{$icon}</span>";
                }
                
                // 添加节点名称
                $html .= "<span class=\"name\">" . htmlspecialchars($child['name']) . "</span>";
                
                // 递归处理子节点
                if ($has_children) {
                    $html .= "\n<ul>";
                    $html .= $this->generateHTMLNodes($child_id, $options, $level + 1);
                    $html .= "</ul>";
                }
                
                $html .= "</li>\n";
            }
        }
        
        return $html;
    }
    
    /**
     * 创建数据库表结构的SQL
     * 
     * @param string $table_name 表名
     * @param array $additional_fields 额外字段定义
     * @return string SQL语句
     */
    public function generateCreateTableSQL($table_name = 'tree_nodes', $additional_fields = array()) {
        $sql = "CREATE TABLE `{$table_name}` (\n";
        $sql .= "  `id` int(11) NOT NULL AUTO_INCREMENT,\n";
        $sql .= "  `parentid` int(11) NOT NULL DEFAULT '0',\n";
        $sql .= "  `name` varchar(255) NOT NULL,\n";
        
        // 添加额外字段
        foreach ($additional_fields as $field => $definition) {
            $sql .= "  `{$field}` {$definition},\n";
        }
        
        $sql .= "  `sort_order` int(11) DEFAULT '0',\n";
        $sql .= "  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,\n";
        $sql .= "  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,\n";
        $sql .= "  PRIMARY KEY (`id`),\n";
        $sql .= "  KEY `idx_parentid` (`parentid`),\n";
        $sql .= "  KEY `idx_sort_order` (`sort_order`)\n";
        $sql .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8;\n";
        
        return $sql;
    }
}

/**
 * 使用示例和说明
 */
if (basename(__FILE__) == basename($_SERVER["SCRIPT_NAME"])) {
    echo "=== FullTree 高级功能演示 ===\n\n";
    
    // 1. 准备测试数据
    $data = array(
        1 => array('id' => 1, 'parentid' => 0, 'name' => '电商平台', 'type' => 'system', 'status' => 'active'),
        2 => array('id' => 2, 'parentid' => 1, 'name' => '用户管理', 'type' => 'module', 'status' => 'active'),
        3 => array('id' => 3, 'parentid' => 1, 'name' => '商品管理', 'type' => 'module', 'status' => 'active'),
        4 => array('id' => 4, 'parentid' => 1, 'name' => '订单管理', 'type' => 'module', 'status' => 'active'),
        5 => array('id' => 5, 'parentid' => 2, 'name' => '用户注册', 'type' => 'feature', 'status' => 'active'),
        6 => array('id' => 6, 'parentid' => 2, 'name' => '用户登录', 'type' => 'feature', 'status' => 'active'),
        7 => array('id' => 7, 'parentid' => 3, 'name' => '商品分类', 'type' => 'feature', 'status' => 'active'),
        8 => array('id' => 8, 'parentid' => 3, 'name' => '商品发布', 'type' => 'feature', 'status' => 'maintenance'),
        9 => array('id' => 9, 'parentid' => 4, 'name' => '订单创建', 'type' => 'feature', 'status' => 'active'),
        10 => array('id' => 10, 'parentid' => 4, 'name' => '订单支付', 'type' => 'feature', 'status' => 'active')
    );
    
    $tree = new FullTree();
    $tree->init($data);
    
    // 2. 缓存和性能测试
    echo "=== 缓存和性能测试 ===\n";
    
    // 第一次查询（无缓存）
    $start = microtime(true);
    $children1 = $tree->getChildren(1);
    $time1 = microtime(true) - $start;
    
    // 第二次查询（有缓存）
    $start = microtime(true);
    $children2 = $tree->getChildren(1);
    $time2 = microtime(true) - $start;
    
    echo "第一次查询耗时: " . number_format($time1 * 1000, 3) . " ms\n";
    echo "第二次查询耗时: " . number_format($time2 * 1000, 3) . " ms\n";
    
    $stats = $tree->getPerformanceStats();
    echo "查询次数: {$stats['query_count']}, 缓存命中: {$stats['cache_hits']}\n\n";
    
    // 3. 排序功能
    echo "=== 排序功能 ===\n";
    $tree->setSortConfig('name', 'DESC');
    $sorted_children = $tree->getChildren(1);
    echo "按名称降序排列的子模块:\n";
    foreach ($sorted_children as $child) {
        echo "  - {$child['name']}\n";
    }
    echo "\n";
    
    // 4. 搜索和过滤
    echo "=== 搜索和过滤 ===\n";
    
    // 搜索包含"用户"的节点
    $search_results = $tree->searchNodes('用户');
    echo "搜索包含'用户'的节点:\n";
    foreach ($search_results as $result) {
        echo "  - {$result['name']} ({$result['type']})\n";
    }
    echo "\n";
    
    // 过滤状态为active的功能节点
    $filter_results = $tree->filterNodes(array('type' => 'feature', 'status' => 'active'));
    echo "状态为active的功能节点:\n";
    foreach ($filter_results as $result) {
        echo "  - {$result['name']}\n";
    }
    echo "\n";
    
    // 5. 高级查询
    echo "=== 高级查询 ===\n";
    
    // 获取祖先节点
    $ancestors = $tree->getAncestors(5);
    echo "用户注册的祖先节点:\n";
    foreach ($ancestors as $ancestor) {
        echo "  - {$ancestor['name']}\n";
    }
    echo "\n";
    
    // 获取最近公共祖先
    $lca = $tree->getLowestCommonAncestor(5, 7);
    if ($lca) {
        echo "用户注册和商品分类的最近公共祖先: {$lca['name']}\n\n";
    }
    
    // 6. 节点操作
    echo "=== 节点操作 ===\n";
    
    // 复制节点
    $new_id = $tree->copyNode(2, 1, false); // 浅复制用户管理模块
    if ($new_id) {
        echo "复制用户管理模块，新ID: {$new_id}\n";
    }
    
    // 批量添加节点
    $new_nodes = array(
        array('id' => 20, 'parentid' => 1, 'name' => '报表管理', 'type' => 'module', 'status' => 'active'),
        array('id' => 21, 'parentid' => 20, 'name' => '销售报表', 'type' => 'feature', 'status' => 'active')
    );
    $tree->batchAddNodes($new_nodes);
    echo "批量添加了报表管理模块\n\n";
    
    // 7. 事件回调
    echo "=== 事件回调 ===\n";
    $tree->addEventListener('getChildren', function($data) {
        echo "触发事件: 查询了节点 {$data['parent_id']} 的子节点\n";
    });
    
    $tree->getChildren(20); // 触发事件
    echo "\n";
    
    // 8. 导入导出
    echo "=== 导入导出 ===\n";
    
    // 导出为JSON
    $json_data = $tree->toJSON();
    echo "JSON格式导出（前100字符）:\n";
    echo substr($json_data, 0, 100) . "...\n\n";
    
    // 导出为CSV
    $csv_data = $tree->toCSV(array('id', 'parentid', 'name', 'type'));
    echo "CSV格式导出（前3行）:\n";
    $csv_lines = explode("\n", $csv_data);
    for ($i = 0; $i < 3 && $i < count($csv_lines); $i++) {
        echo $csv_lines[$i] . "\n";
    }
    echo "\n";
    
    // 9. HTML树形显示
    echo "=== HTML树形显示 ===\n";
    $html_tree = $tree->generateHTMLTree(1, array(
        'show_checkboxes' => true,
        'css_class' => 'demo-tree'
    ));
    echo "HTML树形结构（前200字符）:\n";
    echo substr($html_tree, 0, 200) . "...\n\n";
    
    // 10. 数据库表结构
    echo "=== 数据库表结构 ===\n";
    $create_sql = $tree->generateCreateTableSQL('system_modules', array(
        'type' => 'varchar(50) DEFAULT NULL',
        'status' => 'varchar(20) DEFAULT \'active\''
    ));
    echo "建表SQL（前3行）:\n";
    $sql_lines = explode("\n", $create_sql);
    for ($i = 0; $i < 3; $i++) {
        echo $sql_lines[$i] . "\n";
    }
    echo "\n";
    
    // 11. 最终性能统计
    echo "=== 最终性能统计 ===\n";
    $final_stats = $tree->getPerformanceStats();
    echo "总查询次数: {$final_stats['query_count']}\n";
    echo "缓存命中次数: {$final_stats['cache_hits']}\n";
    echo "缓存命中率: " . number_format(($final_stats['cache_hits'] / max($final_stats['query_count'], 1)) * 100, 1) . "%\n";
    echo "总耗时: " . number_format($final_stats['total_time'] * 1000, 3) . " ms\n\n";
    
    echo "=== FullTree演示完成 ===\n";
    echo "这个完整版Tree类提供了生产环境所需的所有高级功能！\n";
}
?>
