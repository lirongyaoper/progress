<?php
/**
 * exercise_02_solution.php - Tree类进阶练习参考答案
 * 
 * 这个文件包含了exercise_02.php中所有高级练习题的参考答案
 * 这些答案展示了Tree类在复杂业务场景中的应用
 * 
 * 答案特点：
 * 1. 完整的功能实现
 * 2. 详细的注释说明
 * 3. 错误处理和边界检查
 * 4. 性能优化考虑
 * 5. 实际业务场景模拟
 * 
 * 学习建议：
 * 1. 先独立完成练习，再查看答案
 * 2. 理解每个解决方案的设计思路
 * 3. 分析算法复杂度和性能特点
 * 4. 尝试改进和扩展功能
 */

require_once __DIR__ . '/../../src/BasicTree.php';
require_once __DIR__ . '/../../src/FullTree.php';

echo "===== 📖 Tree类进阶练习参考答案 =====\n\n";

// 重新初始化课程数据
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

$tree = new BasicTree();
$tree->init($course_data);

echo "课程数据已重新加载，开始展示进阶练习参考答案...\n\n";

// =============================================
// 练习组1 答案：数据分析和统计 (难度: ⭐⭐⭐)
// =============================================

echo "===== 练习组1 参考答案：数据分析和统计 ⭐⭐⭐ =====\n\n";

echo "练习1.1：计算各一级分类的总收入\n";
echo "答案解析：递归遍历子孙节点，累计课程收入\n";

function calculateCategoryRevenue($tree, $category_id) {
    $total_revenue = 0;
    
    // 获取分类下的所有子孙节点
    $descendants = $tree->getAllDescendants($category_id);
    
    if ($descendants) {
        foreach ($descendants as $node) {
            // 只统计课程类型的节点
            if (isset($node['type']) && $node['type'] == 'course') {
                $price = isset($node['price']) ? $node['price'] : 0;
                $students = isset($node['students']) ? $node['students'] : 0;
                $total_revenue += $price * $students;
            }
        }
    }
    
    return $total_revenue;
}

echo "参考答案：\n";
$main_categories = $tree->getRoots();
foreach ($main_categories as $category) {
    $revenue = calculateCategoryRevenue($tree, $category['id']);
    echo "  {$category['name']}: ¥" . number_format($revenue) . "\n";
}
echo "\n";

echo "练习1.2：找出最受欢迎的课程路径\n";
echo "答案解析：过滤课程节点，按学生数排序\n";

function getTopCourses($tree, $limit = 5) {
    $courses = array();
    
    // 遍历所有节点，找出课程类型的节点
    foreach ($tree->data as $node) {
        if (isset($node['type']) && $node['type'] == 'course') {
            $courses[] = $node;
        }
    }
    
    // 按学生数降序排序
    usort($courses, function($a, $b) {
        $students_a = isset($a['students']) ? $a['students'] : 0;
        $students_b = isset($b['students']) ? $b['students'] : 0;
        return $students_b - $students_a;
    });
    
    // 返回前$limit个课程
    return array_slice($courses, 0, $limit);
}

echo "参考答案：\n";
$top_courses = getTopCourses($tree, 5);
foreach ($top_courses as $index => $course) {
    $rank = $index + 1;
    $path = $tree->getPathString($course['id'], ' > ');
    echo "  {$rank}. {$course['name']} - {$course['students']}人 ({$path})\n";
}
echo "\n";

echo "练习1.3：计算平均课程价格分布\n";
echo "答案解析：遍历课程，按价格区间归类统计\n";

function analyzePriceDistribution($tree) {
    $distribution = array(
        '0-99' => 0,
        '100-199' => 0,
        '200-299' => 0,
        '300-399' => 0,
        '400-499' => 0,
        '500+' => 0
    );
    
    foreach ($tree->data as $node) {
        if (isset($node['type']) && $node['type'] == 'course') {
            $price = isset($node['price']) ? $node['price'] : 0;
            
            if ($price < 100) {
                $distribution['0-99']++;
            } elseif ($price < 200) {
                $distribution['100-199']++;
            } elseif ($price < 300) {
                $distribution['200-299']++;
            } elseif ($price < 400) {
                $distribution['300-399']++;
            } elseif ($price < 500) {
                $distribution['400-499']++;
            } else {
                $distribution['500+']++;
            }
        }
    }
    
    return $distribution;
}

echo "参考答案：\n";
$price_distribution = analyzePriceDistribution($tree);
foreach ($price_distribution as $range => $count) {
    echo "  {$range}元: {$count}门课程\n";
}
echo "\n";

// =============================================
// 练习组2 答案：复杂操作和算法 (难度: ⭐⭐⭐⭐)
// =============================================

echo "===== 练习组2 参考答案：复杂操作和算法 ⭐⭐⭐⭐ =====\n\n";

echo "练习2.1：智能课程推荐\n";
echo "答案解析：基于已选课程的分类，推荐同分类热门课程\n";

function recommendCourses($tree, $selected_course_ids, $limit = 3) {
    $recommendations = array();
    $parent_categories = array();
    
    // 1. 找出已选课程的所有父分类
    foreach ($selected_course_ids as $course_id) {
        if (isset($tree->data[$course_id])) {
            $path = $tree->getPath($course_id);
            foreach ($path as $node) {
                if ($node['type'] == 'subcategory') {
                    $parent_categories[] = $node['id'];
                }
            }
        }
    }
    
    $parent_categories = array_unique($parent_categories);
    
    // 2. 在这些分类下找其他课程
    $candidate_courses = array();
    foreach ($parent_categories as $category_id) {
        $descendants = $tree->getAllDescendants($category_id);
        if ($descendants) {
            foreach ($descendants as $node) {
                if ($node['type'] == 'course' && !in_array($node['id'], $selected_course_ids)) {
                    $candidate_courses[] = $node;
                }
            }
        }
    }
    
    // 3. 按学生数排序
    usort($candidate_courses, function($a, $b) {
        return $b['students'] - $a['students'];
    });
    
    // 4. 去重并返回推荐列表
    $seen_ids = array();
    foreach ($candidate_courses as $course) {
        if (!in_array($course['id'], $seen_ids)) {
            $recommendations[] = $course;
            $seen_ids[] = $course['id'];
            if (count($recommendations) >= $limit) {
                break;
            }
        }
    }
    
    return $recommendations;
}

echo "参考答案：\n";
echo "假设用户已选课程：HTML/CSS基础(7), Python基础(11)\n";
$selected_courses = array(7, 11);
$recommendations = recommendCourses($tree, $selected_courses, 3);
foreach ($recommendations as $course) {
    echo "  - {$course['name']} ({$course['students']}人学习)\n";
}
echo "\n";

echo "练习2.2：学习路径规划\n";
echo "答案解析：根据课程名称关键词智能排序学习路径\n";

function planLearningPath($tree, $domain) {
    $learning_path = array();
    
    // 1. 找到指定领域的分类节点
    $domain_category = null;
    foreach ($tree->data as $node) {
        if ($node['type'] == 'subcategory' && strpos($node['name'], $domain) !== false) {
            $domain_category = $node;
            break;
        }
    }
    
    if (!$domain_category) {
        return $learning_path;
    }
    
    // 2. 获取该分类下的所有课程
    $courses = array();
    $descendants = $tree->getAllDescendants($domain_category['id']);
    if ($descendants) {
        foreach ($descendants as $node) {
            if ($node['type'] == 'course') {
                $courses[] = $node;
            }
        }
    }
    
    // 3. 根据课程名称关键词分级
    $basic_keywords = array('基础', '入门', 'HTML', 'CSS');
    $intermediate_keywords = array('进阶', 'JavaScript', 'JS');
    $advanced_keywords = array('框架', 'React', 'Vue', '实战');
    
    $basic_courses = array();
    $intermediate_courses = array();
    $advanced_courses = array();
    
    foreach ($courses as $course) {
        $name = $course['name'];
        $is_basic = false;
        $is_intermediate = false;
        $is_advanced = false;
        
        foreach ($basic_keywords as $keyword) {
            if (strpos($name, $keyword) !== false) {
                $is_basic = true;
                break;
            }
        }
        
        foreach ($intermediate_keywords as $keyword) {
            if (strpos($name, $keyword) !== false) {
                $is_intermediate = true;
                break;
            }
        }
        
        foreach ($advanced_keywords as $keyword) {
            if (strpos($name, $keyword) !== false) {
                $is_advanced = true;
                break;
            }
        }
        
        if ($is_basic) {
            $basic_courses[] = $course;
        } elseif ($is_intermediate) {
            $intermediate_courses[] = $course;
        } elseif ($is_advanced) {
            $advanced_courses[] = $course;
        } else {
            $intermediate_courses[] = $course; // 默认归为中级
        }
    }
    
    // 4. 按学习顺序排列
    $learning_path = array_merge($basic_courses, $intermediate_courses, $advanced_courses);
    
    return $learning_path;
}

echo "参考答案（前端开发学习路径）：\n";
$frontend_path = planLearningPath($tree, '前端开发');
foreach ($frontend_path as $step => $course) {
    echo "  第" . ($step + 1) . "步: {$course['name']}\n";
}
echo "\n";

echo "练习2.3：构建课程依赖关系图\n";
echo "答案解析：根据课程名称关键词分析技术依赖关系\n";

function buildDependencyGraph($tree) {
    $dependencies = array();
    $courses = array();
    
    // 获取所有课程
    foreach ($tree->data as $node) {
        if ($node['type'] == 'course') {
            $courses[$node['id']] = $node;
        }
    }
    
    // 定义依赖关系规则
    $dependency_rules = array(
        'JavaScript' => array('HTML', 'CSS'),
        'React' => array('JavaScript', 'HTML', 'CSS'),
        'Vue' => array('JavaScript', 'HTML', 'CSS'),
        'Node.js' => array('JavaScript'),
        'Android' => array('Java'), // 假设有Java课程
        'iOS' => array('Swift') // 假设有Swift课程
    );
    
    foreach ($courses as $course_id => $course) {
        $dependencies[$course_id] = array();
        
        foreach ($dependency_rules as $tech => $prereqs) {
            if (strpos($course['name'], $tech) !== false) {
                // 查找前置课程
                foreach ($prereqs as $prereq) {
                    foreach ($courses as $prereq_id => $prereq_course) {
                        if (strpos($prereq_course['name'], $prereq) !== false && $prereq_id != $course_id) {
                            $dependencies[$course_id][] = $prereq_id;
                        }
                    }
                }
            }
        }
        
        // 去重
        $dependencies[$course_id] = array_unique($dependencies[$course_id]);
    }
    
    return $dependencies;
}

echo "参考答案：\n";
$dependencies = buildDependencyGraph($tree);
foreach ($dependencies as $course_id => $prereq_ids) {
    if (!empty($prereq_ids)) {
        $course_name = $tree->data[$course_id]['name'];
        echo "  {$course_name} 的前置课程:\n";
        foreach ($prereq_ids as $prereq_id) {
            $prereq_name = $tree->data[$prereq_id]['name'];
            echo "    - {$prereq_name}\n";
        }
    }
}
echo "\n";

// =============================================
// 练习组3 答案：性能优化 (难度: ⭐⭐⭐⭐)
// =============================================

echo "===== 练习组3 参考答案：性能优化 ⭐⭐⭐⭐ =====\n\n";

echo "练习3.1：实现缓存机制\n";
echo "答案解析：使用内存缓存提高查询性能\n";

class CachedTree extends BasicTree {
    private $cache = array();
    private $cache_hits = 0;
    private $cache_misses = 0;
    
    public function getCachedChildren($parent_id) {
        $cache_key = "children_{$parent_id}";
        
        // 检查缓存中是否存在
        if (isset($this->cache[$cache_key])) {
            $this->cache_hits++;
            return $this->cache[$cache_key];
        }
        
        // 如果不存在，调用父类方法并缓存结果
        $result = parent::getChildren($parent_id);
        $this->cache[$cache_key] = $result;
        $this->cache_misses++;
        
        return $result;
    }
    
    public function clearCache() {
        $this->cache = array();
        $this->cache_hits = 0;
        $this->cache_misses = 0;
    }
    
    public function getCacheStats() {
        return array(
            'size' => count($this->cache),
            'keys' => array_keys($this->cache),
            'hits' => $this->cache_hits,
            'misses' => $this->cache_misses,
            'hit_rate' => $this->cache_hits + $this->cache_misses > 0 ? 
                          $this->cache_hits / ($this->cache_hits + $this->cache_misses) * 100 : 0
        );
    }
}

echo "参考答案：\n";
$cached_tree = new CachedTree();
$cached_tree->init($course_data);

// 测试缓存性能
$start_time = microtime(true);
for ($i = 0; $i < 1000; $i++) {
    $cached_tree->getCachedChildren(1);
    $cached_tree->getCachedChildren(4);
    $cached_tree->getCachedChildren(5);
}
$cached_time = microtime(true) - $start_time;

echo "缓存性能测试（3000次查询）: " . number_format($cached_time * 1000, 2) . " ms\n";
$cache_stats = $cached_tree->getCacheStats();
echo "缓存统计: {$cache_stats['size']} 个缓存项，命中率: " . number_format($cache_stats['hit_rate'], 1) . "%\n\n";

echo "练习3.2：批量操作优化\n";
echo "答案解析：减少单个操作，提高批量处理效率\n";

function batchUpdateCourses($tree, $updates) {
    $updated_count = 0;
    
    foreach ($updates as $course_id => $fields) {
        if (isset($tree->data[$course_id])) {
            foreach ($fields as $field => $value) {
                $tree->data[$course_id][$field] = $value;
            }
            $updated_count++;
        }
    }
    
    return $updated_count;
}

echo "参考答案：\n";
$batch_updates = array(
    7 => array('students' => 1300, 'price' => 179),
    8 => array('students' => 1050),
    9 => array('price' => 359)
);

$updated = batchUpdateCourses($tree, $batch_updates);
echo "批量更新完成，共更新 {$updated} 门课程\n\n";

echo "练习3.3：内存使用优化\n";
echo "答案解析：实现延迟加载，减少内存占用\n";

class LazyLoadTree {
    private $data_source;
    private $loaded_nodes = array();
    private $load_count = 0;
    
    public function __construct($data_source) {
        $this->data_source = $data_source;
    }
    
    public function getNode($node_id) {
        // 检查节点是否已加载
        if (isset($this->loaded_nodes[$node_id])) {
            return $this->loaded_nodes[$node_id];
        }
        
        // 如果未加载，从数据源加载
        if (isset($this->data_source[$node_id])) {
            $this->loaded_nodes[$node_id] = $this->data_source[$node_id];
            $this->load_count++;
            return $this->loaded_nodes[$node_id];
        }
        
        return null;
    }
    
    public function getChildren($parent_id) {
        $children = array();
        
        // 只加载直接子节点，不预加载更深层的节点
        foreach ($this->data_source as $node_id => $node) {
            if ($node['parentid'] == $parent_id) {
                $children[$node_id] = $this->getNode($node_id);
            }
        }
        
        return empty($children) ? false : $children;
    }
    
    public function getMemoryUsage() {
        return array(
            'loaded_nodes' => count($this->loaded_nodes),
            'total_nodes' => count($this->data_source),
            'load_count' => $this->load_count,
            'memory_usage' => memory_get_usage(true)
        );
    }
}

echo "参考答案：\n";
$lazy_tree = new LazyLoadTree($course_data);

// 测试延迟加载
$lazy_tree->getChildren(1);
$lazy_tree->getChildren(4);

$memory_stats = $lazy_tree->getMemoryUsage();
echo "延迟加载统计:\n";
echo "  已加载节点: {$memory_stats['loaded_nodes']}/{$memory_stats['total_nodes']}\n";
echo "  加载次数: {$memory_stats['load_count']}\n";
echo "  内存使用: " . number_format($memory_stats['memory_usage'] / 1024, 2) . " KB\n\n";

// =============================================
// 练习组4 答案：实际业务场景 (难度: ⭐⭐⭐⭐⭐)
// =============================================

echo "===== 练习组4 参考答案：实际业务场景 ⭐⭐⭐⭐⭐ =====\n\n";

echo "练习4.1：构建课程搜索引擎\n";
echo "答案解析：多关键词匹配，权重评分排序\n";

function searchCourses($tree, $keywords, $max_price = null) {
    $results = array();
    $keyword_array = explode(' ', $keywords);
    
    foreach ($tree->data as $node) {
        if ($node['type'] == 'course') {
            // 价格筛选
            if ($max_price !== null && $node['price'] > $max_price) {
                continue;
            }
            
            $score = 0;
            
            // 计算匹配得分
            foreach ($keyword_array as $keyword) {
                $keyword = trim($keyword);
                if (empty($keyword)) continue;
                
                // 标题匹配 +3分
                if (stripos($node['name'], $keyword) !== false) {
                    $score += 3;
                }
                
                // 分类匹配 +2分
                $path = $tree->getPathString($node['id']);
                if (stripos($path, $keyword) !== false) {
                    $score += 2;
                }
            }
            
            // 价格适中 +1分 (100-400元区间)
            if ($node['price'] >= 100 && $node['price'] <= 400) {
                $score += 1;
            }
            
            // 学生多 +1分 (>800人)
            if ($node['students'] > 800) {
                $score += 1;
            }
            
            if ($score > 0) {
                $node['score'] = $score;
                $results[] = $node;
            }
        }
    }
    
    // 按得分排序
    usort($results, function($a, $b) {
        return $b['score'] - $a['score'];
    });
    
    return $results;
}

echo "参考答案：\n";
echo "搜索关键词：'JavaScript 前端'，最高价格：300元\n";
$search_results = searchCourses($tree, 'JavaScript 前端', 300);
foreach ($search_results as $result) {
    echo "  {$result['name']} - ¥{$result['price']} - 得分: {$result['score']}\n";
}
echo "\n";

echo "练习4.2：生成个性化学习计划\n";
echo "答案解析：根据用户条件智能安排学习进度\n";

function generatePersonalizedPlan($tree, $user_level, $hours_per_week, $budget) {
    $learning_plan = array();
    $total_cost = 0;
    $weekly_hours = 0;
    $current_week = 1;
    
    // 根据用户水平筛选合适的课程
    $suitable_courses = array();
    foreach ($tree->data as $node) {
        if ($node['type'] == 'course') {
            $is_suitable = false;
            
            switch ($user_level) {
                case 'beginner':
                    $is_suitable = (strpos($node['name'], '基础') !== false || 
                                   $node['price'] < 300);
                    break;
                case 'intermediate':
                    $is_suitable = (strpos($node['name'], '进阶') !== false || 
                                   ($node['price'] >= 200 && $node['price'] <= 500));
                    break;
                case 'advanced':
                    $is_suitable = (strpos($node['name'], '实战') !== false || 
                                   strpos($node['name'], '框架') !== false);
                    break;
            }
            
            if ($is_suitable && $total_cost + $node['price'] <= $budget) {
                $suitable_courses[] = $node;
            }
        }
    }
    
    // 按价格和学生数排序
    usort($suitable_courses, function($a, $b) {
        $score_a = $a['students'] / max($a['price'], 1);
        $score_b = $b['students'] / max($b['price'], 1);
        return ($score_b > $score_a) ? 1 : (($score_b < $score_a) ? -1 : 0);
    });
    
    // 生成周计划
    foreach ($suitable_courses as $course) {
        if ($total_cost + $course['price'] > $budget) {
            continue;
        }
        
        $estimated_hours = min(8, $hours_per_week); // 每门课最多8小时
        
        if ($weekly_hours + $estimated_hours > $hours_per_week) {
            $current_week++;
            $weekly_hours = 0;
        }
        
        if (!isset($learning_plan[$current_week])) {
            $learning_plan[$current_week] = array();
        }
        
        $course['estimated_hours'] = $estimated_hours;
        $learning_plan[$current_week][] = $course;
        
        $weekly_hours += $estimated_hours;
        $total_cost += $course['price'];
        
        if ($current_week > 12) { // 最多12周计划
            break;
        }
    }
    
    return $learning_plan;
}

echo "参考答案：\n";
echo "用户档案：初学者，每周10小时，预算1000元\n";
$personal_plan = generatePersonalizedPlan($tree, 'beginner', 10, 1000);
foreach ($personal_plan as $week => $courses) {
    echo "  第{$week}周计划:\n";
    foreach ($courses as $course) {
        echo "    - {$course['name']} (预计{$course['estimated_hours']}小时, ¥{$course['price']})\n";
    }
}
echo "\n";

echo "练习4.3：实现课程推荐系统\n";
echo "答案解析：基于用户相似度的协同过滤推荐\n";

function collaborativeFiltering($tree, $user_courses, $all_user_data) {
    $recommendations = array();
    $current_user_courses = $user_courses;
    
    // 计算与其他用户的相似度
    $similarities = array();
    foreach ($all_user_data as $user_id => $courses) {
        if ($user_id == 'current_user') continue;
        
        // 计算Jaccard相似度
        $intersection = array_intersect($current_user_courses, $courses);
        $union = array_unique(array_merge($current_user_courses, $courses));
        
        $similarity = count($union) > 0 ? count($intersection) / count($union) : 0;
        
        if ($similarity > 0) {
            $similarities[$user_id] = array(
                'similarity' => $similarity,
                'courses' => $courses
            );
        }
    }
    
    // 按相似度排序
    uasort($similarities, function($a, $b) {
        return ($b['similarity'] > $a['similarity']) ? 1 : (($b['similarity'] < $a['similarity']) ? -1 : 0);
    });
    
    // 统计推荐课程
    $candidate_courses = array();
    foreach ($similarities as $user_id => $data) {
        foreach ($data['courses'] as $course_id) {
            if (!in_array($course_id, $current_user_courses)) {
                if (!isset($candidate_courses[$course_id])) {
                    $candidate_courses[$course_id] = array(
                        'score' => 0,
                        'similarity_sum' => 0
                    );
                }
                $candidate_courses[$course_id]['score']++;
                $candidate_courses[$course_id]['similarity_sum'] += $data['similarity'];
            }
        }
    }
    
    // 计算最终推荐分数
    foreach ($candidate_courses as $course_id => $data) {
        if (isset($tree->data[$course_id])) {
            $course = $tree->data[$course_id];
            $course['similarity'] = number_format($data['similarity_sum'] / $data['score'], 3);
            $recommendations[] = $course;
        }
    }
    
    // 按相似度排序
    usort($recommendations, function($a, $b) {
        return ($b['similarity'] > $a['similarity']) ? 1 : (($b['similarity'] < $a['similarity']) ? -1 : 0);
    });
    
    return array_slice($recommendations, 0, 3);
}

// 模拟用户数据
$user_data = array(
    'user1' => array(7, 8, 11),  // 学了HTML、JS、Python
    'user2' => array(7, 9, 10),  // 学了HTML、React、Vue
    'user3' => array(8, 9, 12),  // 学了JS、React、Node
    'current_user' => array(7, 8) // 当前用户学了HTML、JS
);

echo "参考答案：\n";
echo "当前用户已学：HTML/CSS基础, JavaScript进阶\n";
$cf_recommendations = collaborativeFiltering($tree, array(7, 8), $user_data);
foreach ($cf_recommendations as $rec) {
    echo "  推荐: {$rec['name']} (相似度: {$rec['similarity']})\n";
}
echo "\n";

// =============================================
// 练习组5 答案：高级数据结构 (难度: ⭐⭐⭐⭐⭐)
// =============================================

echo "===== 练习组5 参考答案：高级数据结构 ⭐⭐⭐⭐⭐ =====\n\n";

echo "练习5.1：实现课程图谱可视化数据\n";
echo "答案解析：生成图数据库格式的节点和边数据\n";

function generateGraphData($tree) {
    $graph_data = array(
        'nodes' => array(),
        'edges' => array()
    );
    
    // 生成节点数据
    foreach ($tree->data as $node) {
        $group = 0;
        switch ($node['type']) {
            case 'category': $group = 1; break;
            case 'subcategory': $group = 2; break;
            case 'course': $group = 3; break;
        }
        
        $graph_data['nodes'][] = array(
            'id' => $node['id'],
            'name' => $node['name'],
            'group' => $group,
            'size' => isset($node['students']) ? min(50, max(10, $node['students'] / 20)) : 15,
            'type' => $node['type']
        );
    }
    
    // 生成边数据
    foreach ($tree->data as $node) {
        if ($node['parentid'] != 0) {
            $weight = 1;
            if ($node['type'] == 'course') {
                $weight = isset($node['students']) ? min(10, max(1, $node['students'] / 100)) : 1;
            }
            
            $graph_data['edges'][] = array(
                'source' => $node['parentid'],
                'target' => $node['id'],
                'weight' => $weight
            );
        }
    }
    
    return $graph_data;
}

echo "参考答案：\n";
$graph_data = generateGraphData($tree);
echo "图谱数据统计：\n";
echo "  节点数: " . count($graph_data['nodes']) . "\n";
echo "  边数: " . count($graph_data['edges']) . "\n";
echo "  前3个节点: ";
for ($i = 0; $i < 3 && $i < count($graph_data['nodes']); $i++) {
    echo $graph_data['nodes'][$i]['name'] . " ";
}
echo "\n\n";

echo "练习5.2：实现B+树索引结构（简化版）\n";
echo "答案解析：基于数组的简化B+树实现\n";

class BPlusTreeIndex {
    private $index = array();
    private $data_count = 0;
    
    public function __construct($order = 4) {
        // 简化实现，使用数组模拟B+树
    }
    
    public function insert($key, $value) {
        if (!isset($this->index[$key])) {
            $this->index[$key] = array();
        }
        $this->index[$key][] = $value;
        $this->data_count++;
    }
    
    public function rangeQuery($min_price, $max_price) {
        $results = array();
        
        foreach ($this->index as $price => $courses) {
            if ($price >= $min_price && $price <= $max_price) {
                $results = array_merge($results, $courses);
            }
        }
        
        return $results;
    }
    
    public function getIndexStats() {
        return array(
            'height' => 2, // 简化为固定高度
            'node_count' => count($this->index),
            'data_count' => $this->data_count
        );
    }
}

echo "参考答案：\n";
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
echo "答案解析：按哈希分片的分布式存储\n";

class DistributedTree {
    private $shards = array();
    private $shard_count;
    
    public function __construct($shard_count = 3) {
        $this->shard_count = $shard_count;
        for ($i = 0; $i < $shard_count; $i++) {
            $this->shards[$i] = new BasicTree();
            $this->shards[$i]->init(array());
        }
    }
    
    private function getShardIndex($node_id) {
        return $node_id % $this->shard_count;
    }
    
    public function addNode($node) {
        $shard_index = $this->getShardIndex($node['id']);
        $this->shards[$shard_index]->data[$node['id']] = $node;
    }
    
    public function getNode($node_id) {
        $shard_index = $this->getShardIndex($node_id);
        return isset($this->shards[$shard_index]->data[$node_id]) ? 
               $this->shards[$shard_index]->data[$node_id] : null;
    }
    
    public function getChildren($parent_id) {
        $children = array();
        
        // 需要查询所有分片
        for ($i = 0; $i < $this->shard_count; $i++) {
            foreach ($this->shards[$i]->data as $node) {
                if ($node['parentid'] == $parent_id) {
                    $children[$node['id']] = $node;
                }
            }
        }
        
        return empty($children) ? false : $children;
    }
    
    public function getShardStats() {
        $stats = array();
        for ($i = 0; $i < $this->shard_count; $i++) {
            $stats[$i] = array(
                'node_count' => count($this->shards[$i]->data),
                'memory_usage' => strlen(serialize($this->shards[$i]->data))
            );
        }
        return $stats;
    }
}

echo "参考答案：\n";
$distributed_tree = new DistributedTree(3);

// 分片存储数据
foreach ($course_data as $course) {
    $distributed_tree->addNode($course);
}

$shard_stats = $distributed_tree->getShardStats();
echo "分布式树统计：\n";
foreach ($shard_stats as $shard_id => $stats) {
    echo "  分片{$shard_id}: {$stats['node_count']}个节点, " . 
         number_format($stats['memory_usage']/1024, 2) . "KB\n";
}
echo "\n";

// =============================================
// 答案总结和学习指导
// =============================================

echo "===== 📚 进阶答案总结和学习指导 =====\n\n";

echo "🎯 核心技术点掌握程度检查：\n\n";

$advanced_skills = array(
    "数据统计分析" => "能否熟练进行复杂的数据聚合和分析？",
    "算法设计能力" => "能否设计高效的推荐和搜索算法？",
    "缓存优化技术" => "是否理解缓存机制的实现原理？",
    "批量处理优化" => "能否优化大数据量的处理性能？",
    "业务建模能力" => "能否将复杂业务转换为技术实现？",
    "数据结构设计" => "是否掌握高级数据结构的应用？"
);

foreach ($advanced_skills as $skill => $question) {
    echo "  ✓ {$skill}: {$question}\n";
}
echo "\n";

echo "💡 重要编程思想和模式：\n\n";

echo "1. 分而治之 (Divide and Conquer)：\n";
echo "   - 将复杂问题分解为小问题\n";
echo "   - 递归处理各个子问题\n";
echo "   - 合并子问题的解决方案\n\n";

echo "2. 缓存模式 (Caching Pattern)：\n";
echo "   - 识别热点数据和频繁操作\n";
echo "   - 合理设计缓存键和过期策略\n";
echo "   - 处理缓存一致性问题\n\n";

echo "3. 策略模式 (Strategy Pattern)：\n";
echo "   - 推荐算法的可插拔设计\n";
echo "   - 不同业务场景的策略切换\n";
echo "   - 算法优化的渐进式改进\n\n";

echo "4. 观察者模式 (Observer Pattern)：\n";
echo "   - 数据变更的事件通知\n";
echo "   - 缓存失效的自动处理\n";
echo "   - 系统状态的实时监控\n\n";

echo "⚡ 性能优化经验总结：\n\n";

echo "1. 查询优化：\n";
echo "   - 使用缓存减少重复计算\n";
echo "   - 批量查询替代循环查询\n";
echo "   - 索引结构提高查找效率\n\n";

echo "2. 内存优化：\n";
echo "   - 延迟加载减少内存占用\n";
echo "   - 对象池复用减少GC压力\n";
echo "   - 数据压缩降低存储开销\n\n";

echo "3. 算法优化：\n";
echo "   - 选择合适的时间复杂度\n";
echo "   - 避免嵌套循环的性能陷阱\n";
echo "   - 使用空间换时间的权衡\n\n";

echo "🚀 实际项目应用建议：\n\n";

echo "1. 系统架构设计：\n";
echo "   - 微服务化的树形数据服务\n";
echo "   - Redis集群的分布式缓存\n";
echo "   - ElasticSearch的搜索引擎\n\n";

echo "2. 数据库设计：\n";
echo "   - 合理的索引策略\n";
echo "   - 读写分离的主从架构\n";
echo "   - 分库分表的水平扩展\n\n";

echo "3. 前端集成：\n";
echo "   - 虚拟滚动的性能优化\n";
echo "   - 懒加载的用户体验\n";
echo "   - 实时搜索的交互设计\n\n";

echo "📈 技术成长路径：\n\n";

$growth_path = array(
    "初级开发者" => array(
        "掌握基础Tree操作",
        "理解递归和遍历算法",
        "完成简单的CRUD功能"
    ),
    "中级开发者" => array(
        "设计缓存和优化策略",
        "实现复杂的业务逻辑",
        "处理并发和性能问题"
    ),
    "高级开发者" => array(
        "架构分布式树形系统",
        "设计可扩展的算法框架",
        "指导团队技术决策"
    )
);

foreach ($growth_path as $level => $skills) {
    echo "{$level}：\n";
    foreach ($skills as $skill) {
        echo "  - {$skill}\n";
    }
    echo "\n";
}

echo "🎉 恭喜完成所有进阶练习！\n";
echo "你现在已经具备了在企业级项目中应用Tree类的能力。\n";
echo "继续保持学习和实践，成为真正的技术专家！\n\n";

echo "📝 最后的挑战：\n";
echo "尝试结合你学到的所有知识，设计并实现一个完整的：\n";
echo "- 多级分类管理系统\n";
echo "- 智能推荐引擎\n";
echo "- 权限管理系统\n";
echo "- 知识图谱平台\n\n";

echo "===== 进阶练习参考答案展示结束 =====\n";
?>
