<?php
/**
 * exercise_02.php - Tree类进阶练习题
 * 
 * 这个文件包含了更高级的练习题，适合已经掌握基础知识的学习者
 * 主要练习BasicTree和FullTree类的高级功能
 * 
 * 练习内容：
 * 1. 复杂的数据操作
 * 2. 性能优化相关练习
 * 3. 实际业务场景模拟
 * 4. 算法和数据结构应用
 * 5. 错误处理和数据验证
 * 
 * 难度等级：⭐⭐⭐⭐
 * 预计完成时间：2-3小时
 * 
 * 学习建议：
 * 1. 确保已经完成exercise_01.php
 * 2. 熟悉BasicTree和FullTree的方法
 * 3. 先阅读category_demo.php了解实际应用
 * 4. 独立完成后再查看答案
 */

require_once __DIR__ . '/../src/BasicTree.php';
require_once __DIR__ . '/../src/FullTree.php';

echo "===== 🎯 Tree类进阶练习 (第二阶段) =====\n\n";

// 练习数据：模拟一个在线教育平台的课程体系
$course_data = array(
    1 => array('id' => 1, 'parentid' => 0, 'name' => '编程开发', 'type' => 'category', 'level' => 1, 'price' => 0, 'students' => 0),
    2 => array('id' => 2, 'parentid' => 0, 'name' => '设计创意', 'type' => 'category', 'level' => 1, 'price' => 0, 'students' => 0),
    3 => array('id' => 3, 'parentid' => 0, 'name' => '职场技能', 'type' => 'category', 'level' => 1, 'price' => 0, 'students' => 0),
    4 => array('id' => 4, 'parentid' => 1, 'name' => '前端开发', 'type' => 'subcategory', 'level' => 2, 'price' => 0, 'students' => 0),
    5 => array('id' => 5, 'parentid' => 1, 'name' => '后端开发', 'type' => 'subcategory', 'level' => 2, 'price' => 0, 'students' => 0),
    6 => array('id' => 6, 'parentid' => 1, 'name' => '移动开发', 'type' => 'subcategory', 'level' => 2, 'price' => 0, 'students' => 0),
    7 => array('id' => 7, 'parentid' => 4, 'name' => 'HTML/CSS基础', 'type' => 'course', 'level' => 3, 'price' => 199, 'students' => 1250),
    8 => array('id' => 8, 'parentid' => 4, 'name' => 'JavaScript进阶', 'type' => 'course', 'level' => 3, 'price' => 299, 'students' => 980),
    9 => array('id' => 9, 'parentid' => 4, 'name' => 'React框架', 'type' => 'course', 'level' => 3, 'price' => 399, 'students' => 756),
    10 => array('id' => 10, 'parentid' => 4, 'name' => 'Vue.js实战', 'type' => 'course', 'level' => 3, 'price' => 399, 'students' => 823),
    11 => array('id' => 11, 'parentid' => 5, 'name' => 'Python基础', 'type' => 'course', 'level' => 3, 'price' => 249, 'students' => 1456),
    12 => array('id' => 12, 'parentid' => 5, 'name' => 'Node.js开发', 'type' => 'course', 'level' => 3, 'price' => 349, 'students' => 567),
    13 => array('id' => 13, 'parentid' => 6, 'name' => 'Android开发', 'type' => 'course', 'level' => 3, 'price' => 499, 'students' => 445),
    14 => array('id' => 14, 'parentid' => 6, 'name' => 'iOS开发', 'type' => 'course', 'level' => 3, 'price' => 599, 'students' => 334),
    15 => array('id' => 15, 'parentid' => 2, 'name' => 'UI设计', 'type' => 'subcategory', 'level' => 2, 'price' => 0, 'students' => 0),
    16 => array('id' => 16, 'parentid' => 15, 'name' => 'Photoshop精通', 'type' => 'course', 'level' => 3, 'price' => 299, 'students' => 890),
    17 => array('id' => 17, 'parentid' => 15, 'name' => 'Sketch设计', 'type' => 'course', 'level' => 3, 'price' => 199, 'students' => 456),
    18 => array('id' => 18, 'parentid' => 3, 'name' => '办公软件', 'type' => 'subcategory', 'level' => 2, 'price' => 0, 'students' => 0),
    19 => array('id' => 19, 'parentid' => 18, 'name' => 'Excel高级应用', 'type' => 'course', 'level' => 3, 'price' => 159, 'students' => 2340),
    20 => array('id' => 20, 'parentid' => 18, 'name' => 'PPT设计技巧', 'type' => 'course', 'level' => 3, 'price' => 129, 'students' => 1876)
);

echo "📚 练习数据：在线教育平台课程体系\n";
echo "包含 " . count($course_data) . " 个节点，涵盖分类、子分类、具体课程\n\n";

$tree = new BasicTree();
$tree->init($course_data);

// 显示数据结构
echo "🌳 课程体系结构：\n";
echo $tree->generateTreeText();
echo "\n";

// =============================================
// 练习组1：数据分析和统计 (难度: ⭐⭐⭐)
// =============================================

echo "===== 练习组1：数据分析和统计 ⭐⭐⭐ =====\n\n";

echo "练习1.1：计算各一级分类的总收入\n";
echo "要求：计算每个一级分类下所有课程的总收入（价格 × 学生数）\n";
echo "提示：需要遍历子孙节点，只统计type为'course'的节点\n\n";

// TODO: 在这里编写你的代码
function calculateCategoryRevenue($tree, $category_id) {
    // TODO: 实现这个函数
    // 1. 获取分类下的所有子孙节点
    // 2. 筛选出type为'course'的节点
    // 3. 计算总收入 = sum(price * students)
    
    return 0; // TODO: 返回实际计算的收入
}

echo "你的答案：\n";
// TODO: 完成calculateCategoryRevenue函数后取消注释下面的代码
// $main_categories = $tree->getRoots();
// foreach ($main_categories as $category) {
//     $revenue = calculateCategoryRevenue($tree, $category['id']);
//     echo "  {$category['name']}: ¥" . number_format($revenue) . "\n";
// }
echo "<!-- 请先完成calculateCategoryRevenue函数的实现 -->\n";

echo "练习1.2：找出最受欢迎的课程路径\n";
echo "要求：找出学生数最多的前5门课程，并显示其完整路径\n";
echo "提示：需要过滤出课程类型的节点，按学生数排序\n\n";

// TODO: 在这里编写你的代码
function getTopCourses($tree, $limit = 5) {
    $courses = array();
    
    // TODO: 实现这个函数
    // 1. 遍历所有节点，找出type为'course'的节点
    // 2. 按students字段排序
    // 3. 返回前$limit个课程
    
    return $courses;
}

echo "你的答案：\n";
// TODO: 完成getTopCourses函数后取消注释下面的代码
// $top_courses = getTopCourses($tree, 5);
// foreach ($top_courses as $index => $course) {
//     $rank = $index + 1;
//     $path = $tree->getPathString($course['id'], ' > ');
//     echo "  {$rank}. {$course['name']} - {$course['students']}人 ({$path})\n";
// }
echo "<!-- 请先完成getTopCourses函数的实现 -->\n";
echo "\n";

echo "练习1.3：计算平均课程价格分布\n";
echo "要求：统计不同价格区间的课程数量分布\n";
echo "价格区间：0-99, 100-199, 200-299, 300-399, 400-499, 500+\n\n";

// TODO: 在这里编写你的代码
function analyzePriceDistribution($tree) {
    $distribution = array(
        '0-99' => 0,
        '100-199' => 0,
        '200-299' => 0,
        '300-399' => 0,
        '400-499' => 0,
        '500+' => 0
    );
    
    // TODO: 实现这个函数
    // 1. 遍历所有课程
    // 2. 根据价格归类到不同区间
    // 3. 统计每个区间的数量
    
    return $distribution;
}

echo "你的答案：\n";
$price_distribution = analyzePriceDistribution($tree);
foreach ($price_distribution as $range => $count) {
    echo "  {$range}元: {$count}门课程\n";
}
echo "\n";

// =============================================
// 练习组2：复杂操作和算法 (难度: ⭐⭐⭐⭐)
// =============================================

echo "===== 练习组2：复杂操作和算法 ⭐⭐⭐⭐ =====\n\n";

echo "练习2.1：智能课程推荐\n";
echo "要求：基于已选课程，推荐相关课程\n";
echo "规则：如果用户选了某个分类的课程，推荐同分类下其他热门课程\n\n";

// TODO: 在这里编写你的代码
function recommendCourses($tree, $selected_course_ids, $limit = 3) {
    $recommendations = array();
    
    // TODO: 实现这个函数
    // 1. 找出已选课程的所有父分类
    // 2. 在这些分类下找其他课程
    // 3. 按学生数排序，排除已选的课程
    // 4. 返回推荐列表
    
    return $recommendations;
}

echo "假设用户已选课程：HTML/CSS基础(7), Python基础(11)\n";
echo "你的推荐：\n";
$selected_courses = array(7, 11);
$recommendations = recommendCourses($tree, $selected_courses, 3);
foreach ($recommendations as $course) {
    echo "  - {$course['name']} ({$course['students']}人学习)\n";
}
echo "\n";

echo "练习2.2：学习路径规划\n";
echo "要求：为新手规划一条完整的学习路径（从基础到高级）\n";
echo "规则：基础课程 -> 进阶课程 -> 实战课程（根据课程名称判断）\n\n";

// TODO: 在这里编写你的代码
function planLearningPath($tree, $domain) {
    $learning_path = array();
    
    // TODO: 实现这个函数
    // 1. 找到指定领域的分类节点
    // 2. 获取该分类下的所有课程
    // 3. 根据课程名称关键词分级（基础、进阶、实战等）
    // 4. 按学习顺序排列
    
    return $learning_path;
}

echo "你的答案（前端开发学习路径）：\n";
$frontend_path = planLearningPath($tree, '前端开发');
foreach ($frontend_path as $step => $course) {
    echo "  第" . ($step + 1) . "步: {$course['name']}\n";
}
echo "\n";

echo "练习2.3：构建课程依赖关系图\n";
echo "要求：分析课程间的逻辑依赖关系\n";
echo "提示：可以基于课程名称中的关键词判断依赖关系\n\n";

// TODO: 在这里编写你的代码
function buildDependencyGraph($tree) {
    $dependencies = array();
    
    // TODO: 实现这个函数
    // 1. 找出所有课程
    // 2. 根据课程名称分析依赖关系
    // 3. 例如："JavaScript进阶" 依赖于 "HTML/CSS基础"
    // 4. 构建依赖关系数组
    
    return $dependencies;
}

echo "你的答案：\n";
$dependencies = buildDependencyGraph($tree);
foreach ($dependencies as $course_id => $prereq_ids) {
    $course_name = $tree->data[$course_id]['name'];
    echo "  {$course_name} 的前置课程:\n";
    foreach ($prereq_ids as $prereq_id) {
        $prereq_name = $tree->data[$prereq_id]['name'];
        echo "    - {$prereq_name}\n";
    }
}
echo "\n";

// =============================================
// 练习组3：性能优化 (难度: ⭐⭐⭐⭐)
// =============================================

echo "===== 练习组3：性能优化 ⭐⭐⭐⭐ =====\n\n";

echo "练习3.1：实现缓存机制\n";
echo "要求：为频繁查询的操作添加缓存，提高性能\n";
echo "提示：可以缓存getChildren、统计数据等结果\n\n";

// TODO: 在这里编写你的代码
class CachedTree extends BasicTree {
    private $cache = array();
    
    public function getCachedChildren($parent_id) {
        // TODO: 实现带缓存的getChildren方法
        // 1. 检查缓存中是否存在
        // 2. 如果不存在，调用父类方法并缓存结果
        // 3. 返回结果
        
        return parent::getChildren($parent_id);
    }
    
    public function clearCache() {
        // TODO: 实现清空缓存的方法
        $this->cache = array();
    }
    
    public function getCacheStats() {
        // TODO: 返回缓存统计信息
        return array(
            'size' => count($this->cache),
            'keys' => array_keys($this->cache)
        );
    }
}

echo "你的答案：\n";
$cached_tree = new CachedTree();
$cached_tree->init($course_data);

// 测试缓存性能
$start_time = microtime(true);
for ($i = 0; $i < 1000; $i++) {
    $cached_tree->getCachedChildren(1);
}
$cached_time = microtime(true) - $start_time;

echo "缓存性能测试（1000次查询）: " . number_format($cached_time * 1000, 2) . " ms\n";
$cache_stats = $cached_tree->getCacheStats();
echo "缓存状态: " . $cache_stats['size'] . " 个缓存项\n\n";

echo "练习3.2：批量操作优化\n";
echo "要求：实现批量更新课程信息的功能\n";
echo "提示：减少单个操作，提高批量处理效率\n\n";

// TODO: 在这里编写你的代码
function batchUpdateCourses($tree, $updates) {
    $updated_count = 0;
    
    // TODO: 实现这个函数
    // 1. 接收批量更新数据 array('course_id' => array('field' => 'value'))
    // 2. 批量更新树数据
    // 3. 返回更新成功的数量
    
    return $updated_count;
}

echo "你的答案：\n";
$batch_updates = array(
    7 => array('students' => 1300, 'price' => 179),
    8 => array('students' => 1050),
    9 => array('price' => 359)
);

$updated = batchUpdateCourses($tree, $batch_updates);
echo "批量更新完成，共更新 {$updated} 门课程\n\n";

echo "练习3.3：内存使用优化\n";
echo "要求：实现数据的延迟加载，减少内存占用\n";
echo "提示：只在需要时加载子节点数据\n\n";

// TODO: 在这里编写你的代码
class LazyLoadTree {
    private $data_source; // 数据源（可以是数据库、API等）
    private $loaded_nodes = array();
    
    public function __construct($data_source) {
        $this->data_source = $data_source;
    }
    
    public function getNode($node_id) {
        // TODO: 实现延迟加载节点
        // 1. 检查节点是否已加载
        // 2. 如果未加载，从数据源加载
        // 3. 缓存到loaded_nodes中
        // 4. 返回节点数据
        
        return null;
    }
    
    public function getChildren($parent_id) {
        // TODO: 实现延迟加载子节点
        // 1. 只加载直接子节点
        // 2. 不预加载更深层的节点
        
        return array();
    }
    
    public function getMemoryUsage() {
        // TODO: 返回当前内存使用统计
        return array(
            'loaded_nodes' => count($this->loaded_nodes),
            'memory_usage' => memory_get_usage(true)
        );
    }
}

echo "你的答案：\n";
$lazy_tree = new LazyLoadTree($course_data);
$memory_stats = $lazy_tree->getMemoryUsage();
echo "延迟加载树初始化完成\n";
echo "已加载节点: {$memory_stats['loaded_nodes']}\n";
echo "内存使用: " . number_format($memory_stats['memory_usage'] / 1024, 2) . " KB\n\n";

// =============================================
// 练习组4：实际业务场景 (难度: ⭐⭐⭐⭐⭐)
// =============================================

echo "===== 练习组4：实际业务场景 ⭐⭐⭐⭐⭐ =====\n\n";

echo "练习4.1：构建课程搜索引擎\n";
echo "要求：实现智能搜索功能，支持多关键词、权重排序\n";
echo "评分规则：标题匹配+3分，分类匹配+2分，价格适中+1分，学生多+1分\n\n";

// TODO: 在这里编写你的代码
function searchCourses($tree, $keywords, $max_price = null) {
    $results = array();
    
    // TODO: 实现这个函数
    // 1. 解析搜索关键词
    // 2. 遍历所有课程，计算匹配得分
    // 3. 按得分排序返回结果
    // 4. 支持价格筛选
    
    return $results;
}

echo "你的答案：\n";
echo "搜索关键词：'JavaScript 前端'，最高价格：300元\n";
$search_results = searchCourses($tree, 'JavaScript 前端', 300);
foreach ($search_results as $result) {
    echo "  {$result['name']} - ¥{$result['price']} - 得分: {$result['score']}\n";
}
echo "\n";

echo "练习4.2：生成个性化学习计划\n";
echo "要求：根据用户水平、时间、预算生成定制学习计划\n";
echo "参数：用户水平(beginner/intermediate/advanced)，学习时间(小时/周)，预算\n\n";

// TODO: 在这里编写你的代码
function generatePersonalizedPlan($tree, $user_level, $hours_per_week, $budget) {
    $learning_plan = array();
    
    // TODO: 实现这个函数
    // 1. 根据用户水平筛选合适的课程
    // 2. 根据时间安排学习进度
    // 3. 在预算范围内选择最优课程组合
    // 4. 生成周计划
    
    return $learning_plan;
}

echo "你的答案：\n";
echo "用户档案：初学者，每周10小时，预算1000元\n";
$personal_plan = generatePersonalizedPlan($tree, 'beginner', 10, 1000);
foreach ($personal_plan as $week => $courses) {
    echo "  第{$week}周计划:\n";
    foreach ($courses as $course) {
        echo "    - {$course['name']} (预计{$course['estimated_hours']}小时)\n";
    }
}
echo "\n";

echo "练习4.3：实现课程推荐系统\n";
echo "要求：基于协同过滤算法推荐课程\n";
echo "思路：找到相似用户，推荐他们喜欢但当前用户未学的课程\n\n";

// TODO: 在这里编写你的代码
function collaborativeFiltering($tree, $user_courses, $all_user_data) {
    $recommendations = array();
    
    // TODO: 实现这个函数
    // 1. 计算用户相似度
    // 2. 找到最相似的用户群体
    // 3. 统计他们学习的课程
    // 4. 推荐当前用户未学过的热门课程
    
    return $recommendations;
}

// 模拟用户数据
$user_data = array(
    'user1' => array(7, 8, 11),  // 学了HTML、JS、Python
    'user2' => array(7, 9, 10),  // 学了HTML、React、Vue
    'user3' => array(8, 9, 12),  // 学了JS、React、Node
    'current_user' => array(7, 8) // 当前用户学了HTML、JS
);

echo "你的答案：\n";
echo "当前用户已学：HTML/CSS基础, JavaScript进阶\n";
$cf_recommendations = collaborativeFiltering($tree, array(7, 8), $user_data);
foreach ($cf_recommendations as $rec) {
    echo "  推荐: {$rec['name']} (相似度: {$rec['similarity']})\n";
}
echo "\n";

// =============================================
// 练习组5：高级数据结构 (难度: ⭐⭐⭐⭐⭐)
// =============================================

echo "===== 练习组5：高级数据结构 ⭐⭐⭐⭐⭐ =====\n\n";

echo "练习5.1：实现课程图谱可视化数据\n";
echo "要求：生成用于前端图谱展示的节点和边数据\n";
echo "格式：nodes数组(id,name,group) + edges数组(source,target,weight)\n\n";

// TODO: 在这里编写你的代码
function generateGraphData($tree) {
    $graph_data = array(
        'nodes' => array(),
        'edges' => array()
    );
    
    // TODO: 实现这个函数
    // 1. 遍历所有节点，生成nodes数组
    // 2. 根据父子关系生成edges数组
    // 3. 添加权重（可基于学生数、价格等）
    // 4. 分组（按分类、类型等）
    
    return $graph_data;
}

echo "你的答案：\n";
$graph_data = generateGraphData($tree);
echo "图谱数据统计：\n";
echo "  节点数: " . count($graph_data['nodes']) . "\n";
echo "  边数: " . count($graph_data['edges']) . "\n";
echo "  前3个节点: ";
for ($i = 0; $i < 3 && $i < count($graph_data['nodes']); $i++) {
    echo $graph_data['nodes'][$i]['name'] . " ";
}
echo "\n\n";

echo "练习5.2：实现B+树索引结构\n";
echo "要求：为课程数据建立B+树索引，支持范围查询\n";
echo "索引字段：price（价格），支持价格区间查询\n\n";

// TODO: 在这里编写你的代码
class BPlusTreeIndex {
    private $root;
    private $order; // B+树的阶数
    
    public function __construct($order = 4) {
        $this->order = $order;
        $this->root = null;
    }
    
    public function insert($key, $value) {
        // TODO: 实现B+树插入操作
        // 1. 如果是空树，创建根节点
        // 2. 找到叶子节点位置
        // 3. 插入数据，如果节点溢出则分裂
    }
    
    public function rangeQuery($min_price, $max_price) {
        // TODO: 实现范围查询
        // 1. 找到起始叶子节点
        // 2. 遍历叶子节点链表
        // 3. 收集范围内的所有数据
        return array();
    }
    
    public function getIndexStats() {
        // TODO: 返回索引统计信息
        return array(
            'height' => 0,
            'node_count' => 0,
            'data_count' => 0
        );
    }
}

echo "你的答案：\n";
$price_index = new BPlusTreeIndex(4);

// 建立索引
foreach ($course_data as $course) {
    if ($course['type'] == 'course') {
        $price_index->insert($course['price'], $course);
    }
}

$index_stats = $price_index->getIndexStats();
echo "B+树索引统计：\n";
echo "  树高度: {$index_stats['height']}\n";
echo "  节点数: {$index_stats['node_count']}\n";
echo "  数据量: {$index_stats['data_count']}\n";

echo "价格区间查询 (200-400元)：\n";
$range_results = $price_index->rangeQuery(200, 400);
foreach ($range_results as $course) {
    echo "  {$course['name']}: ¥{$course['price']}\n";
}
echo "\n";

echo "练习5.3：实现分布式树结构\n";
echo "要求：设计支持分片的树结构，模拟分布式存储\n";
echo "分片策略：按分类ID取模分片\n\n";

// TODO: 在这里编写你的代码
class DistributedTree {
    private $shards = array();
    private $shard_count;
    
    public function __construct($shard_count = 3) {
        $this->shard_count = $shard_count;
        for ($i = 0; $i < $shard_count; $i++) {
            $this->shards[$i] = new BasicTree();
        }
    }
    
    public function addNode($node) {
        // TODO: 实现分片添加节点
        // 1. 根据节点ID计算分片
        // 2. 添加到对应分片
    }
    
    public function getNode($node_id) {
        // TODO: 实现跨分片查询节点
        // 1. 计算节点所在分片
        // 2. 从对应分片查询
        return null;
    }
    
    public function getChildren($parent_id) {
        // TODO: 实现跨分片获取子节点
        // 1. 可能需要查询多个分片
        // 2. 合并结果
        return array();
    }
    
    public function getShardStats() {
        // TODO: 返回分片统计信息
        $stats = array();
        for ($i = 0; $i < $this->shard_count; $i++) {
            $stats[$i] = array(
                'node_count' => 0,
                'memory_usage' => 0
            );
        }
        return $stats;
    }
}

echo "你的答案：\n";
$distributed_tree = new DistributedTree(3);

// 分片存储数据
foreach ($course_data as $course) {
    $distributed_tree->addNode($course);
}

$shard_stats = $distributed_tree->getShardStats();
echo "分布式树统计：\n";
foreach ($shard_stats as $shard_id => $stats) {
    echo "  分片{$shard_id}: {$stats['node_count']}个节点\n";
}
echo "\n";

// =============================================
// 自我评估和总结
// =============================================

echo "===== 🎓 自我评估 =====\n";
echo "完成所有练习后，请评估自己的掌握程度：\n\n";

$skill_checklist = array(
    "数据分析和统计" => "能否独立完成复杂的数据统计计算？",
    "算法设计" => "能否设计高效的树遍历和查询算法？",
    "性能优化" => "是否理解缓存、批处理等优化技术？",
    "业务建模" => "能否将实际业务需求转换为树结构操作？",
    "高级数据结构" => "是否掌握索引、分布式等高级概念？"
);

echo "技能自检清单：\n";
foreach ($skill_checklist as $skill => $question) {
    echo "  □ {$skill}: {$question}\n";
}
echo "\n";

echo "===== 🚀 进阶建议 =====\n";
echo "如果你完成了大部分练习，建议你：\n\n";

echo "📈 下一步学习方向：\n";
echo "  1. 学习实际数据库的树形查询（如MySQL的递归CTE）\n";
echo "  2. 研究前端树形组件的实现（如Element UI的Tree）\n";
echo "  3. 了解大数据场景下的树形结构优化\n";
echo "  4. 学习图数据库（如Neo4j）的树形数据处理\n\n";

echo "💼 实际项目应用：\n";
echo "  1. 实现一个完整的分类管理后台\n";
echo "  2. 开发权限管理系统的角色树\n";
echo "  3. 构建知识图谱或技能树系统\n";
echo "  4. 设计多级评论系统\n\n";

echo "📚 扩展学习资源：\n";
echo "  1. 数据结构与算法教程\n";
echo "  2. 数据库设计与优化\n";
echo "  3. 分布式系统设计\n";
echo "  4. 前端数据可视化\n\n";

echo "===== 练习结束 =====\n";
echo "恭喜你完成了Tree类的进阶练习！\n";
echo "现在你已经具备了在实际项目中使用Tree类的能力。\n";
echo "记住：理论结合实践，才能真正掌握技术的精髓。\n\n";

echo "🎯 最终挑战：\n";
echo "尝试用你学到的知识，为你正在参与的项目设计一个树形结构解决方案！\n";
?>
