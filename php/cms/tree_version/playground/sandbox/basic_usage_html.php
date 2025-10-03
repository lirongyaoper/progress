<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tree类基础使用示例 - HTML版本</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            line-height: 1.6;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            text-align: center;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        .section {
            margin: 30px 0;
            padding: 20px;
            background: #f8f9fa;
            border-left: 4px solid #3498db;
            border-radius: 5px;
        }
        .section h2 {
            color: #2c3e50;
            margin-top: 0;
        }
        .code-output {
            background: #2c3e50;
            color: #ecf0f1;
            padding: 15px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            white-space: pre-wrap;
            overflow-x: auto;
        }
        .tree-structure {
            background: #e8f5e8;
            border: 1px solid #4caf50;
        }
        .success {
            color: #27ae60;
            font-weight: bold;
        }
        .highlight {
            background: #fff3cd;
            border: 1px solid #ffc107;
            color: #856404;
        }
        ul {
            list-style-type: none;
            padding-left: 20px;
        }
        li {
            margin: 5px 0;
            padding: 5px;
            background: #ecf0f1;
            border-radius: 3px;
        }
        .breadcrumb {
            background: #3498db;
            color: white;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🌳 Tree类基础使用示例</h1>
        
        <?php
        // 引入SimpleTree类
        require_once __DIR__ . '/../src/SimpleTree.php';

        // 准备测试数据
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

        // 创建Tree实例并初始化
        $tree = new SimpleTree();
        $init_result = $tree->init($company_data);
        ?>

        <div class="section">
            <h2>📊 初始化结果</h2>
            <?php if ($init_result): ?>
                <p class="success">✅ Tree类初始化成功！</p>
            <?php else: ?>
                <p style="color: red;">❌ Tree类初始化失败！</p>
            <?php endif; ?>
        </div>

        <div class="section">
            <h2>🏢 公司组织架构图</h2>
            <div class="code-output tree-structure">
<?php echo htmlspecialchars($tree->generateTreeText()); ?>
            </div>
        </div>

        <div class="section">
            <h2>🔍 基础查询操作</h2>
            
            <h3>1. 获取顶级部门</h3>
            <ul>
                <?php
                $top_departments = $tree->getRoots();
                if ($top_departments) {
                    foreach ($top_departments as $dept) {
                        echo "<li>📋 {$dept['name']}</li>";
                    }
                } else {
                    echo "<li>没有找到顶级部门</li>";
                }
                ?>
            </ul>

            <h3>2. 技术部下的直接子部门</h3>
            <ul>
                <?php
                $tech_children = $tree->getChildren(2);
                if ($tech_children) {
                    foreach ($tech_children as $child) {
                        echo "<li>🏢 {$child['name']}</li>";
                    }
                } else {
                    echo "<li>技术部下没有子部门</li>";
                }
                ?>
            </ul>

            <h3>3. 技术部下的所有子孙部门</h3>
            <ul>
                <?php
                $all_tech_descendants = $tree->getAllDescendants(2);
                if ($all_tech_descendants) {
                    foreach ($all_tech_descendants as $descendant) {
                        echo "<li>🌳 {$descendant['name']}</li>";
                    }
                } else {
                    echo "<li>技术部下没有任何子部门</li>";
                }
                ?>
            </ul>

            <h3>4. 查找父部门</h3>
            <?php
            $frontend_parent = $tree->getParent(9);
            if ($frontend_parent) {
                echo "<div class='breadcrumb'>前端开发的父部门是: {$frontend_parent['name']}</div>";
            } else {
                echo "<p>前端开发没有父部门或部门不存在</p>";
            }
            ?>
        </div>

        <div class="section">
            <h2>📏 深度和层级分析</h2>
            <ul>
                <?php
                $departments_to_check = array(
                    1 => '总经理办公室',
                    2 => '技术部',
                    5 => '开发组',
                    9 => '前端开发'
                );

                foreach ($departments_to_check as $id => $name) {
                    $depth = $tree->getDepth($id);
                    echo "<li>📐 {$name}: 第 {$depth} 层</li>";
                }
                ?>
            </ul>
        </div>

        <div class="section">
            <h2>🍃 叶子节点检测</h2>
            <ul>
                <?php
                foreach ($company_data as $id => $dept) {
                    if ($tree->isLeaf($id)) {
                        echo "<li style='background:#d4edda;'>✓ {$dept['name']} (叶子节点)</li>";
                    } else {
                        echo "<li style='background:#cce5ff;'>○ {$dept['name']} (有下级部门)</li>";
                    }
                }
                ?>
            </ul>
        </div>

        <div class="section">
            <h2>💼 实际应用场景演示</h2>
            
            <h3>1. 部门选择下拉框</h3>
            <div class="code-output">
                <select name="department" style="width: 100%; padding: 5px;">
                    <?php
                    function generateSelectOptions($tree, $parent_id = 0, $prefix = '') {
                        $html = '';
                        $children = $tree->getChildren($parent_id);
                        
                        if ($children) {
                            foreach ($children as $child) {
                                $option_text = $prefix . $child['name'];
                                echo "<option value='{$child['id']}'>{$option_text}</option>\n";
                                
                                // 递归生成子选项
                                generateSelectOptions($tree, $child['id'], $prefix . '├─ ');
                            }
                        }
                    }
                    generateSelectOptions($tree);
                    ?>
                </select>
            </div>

            <h3>2. 面包屑导航示例</h3>
            <?php
            function generateBreadcrumb($tree, $node_id) {
                $path = array();
                $current_id = $node_id;
                
                while ($current_id != 0 && isset($tree->data[$current_id])) {
                    array_unshift($path, $tree->data[$current_id]['name']);
                    $current_id = $tree->data[$current_id]['parentid'];
                }
                
                return implode(' > ', $path);
            }

            $breadcrumb = generateBreadcrumb($tree, 9);
            echo "<div class='breadcrumb'>🍞 {$breadcrumb}</div>";
            ?>

            <h3>3. 部门统计信息</h3>
            <ul>
                <?php
                foreach ($company_data as $id => $dept) {
                    $children = $tree->getChildren($id);
                    $child_count = $children ? count($children) : 0;
                    $total_descendants = $tree->getAllDescendants($id);
                    $total_count = $total_descendants ? count($total_descendants) : 0;
                    
                    echo "<li>📊 {$dept['name']}: 直接下级 <strong>{$child_count}</strong> 个，总下级 <strong>{$total_count}</strong> 个</li>";
                }
                ?>
            </ul>
        </div>

        <div class="section highlight">
            <h2>🎯 学习建议</h2>
            <ul>
                <li>🔍 对比这个HTML版本和纯文本版本的区别</li>
                <li>📝 尝试修改数据结构，观察结果变化</li>
                <li>🛠️ 查看 <a href="menu_demo.php">menu_demo.php</a> 了解菜单应用</li>
                <li>📚 完成 <a href="../exercises/exercise_01.php">练习题目</a></li>
                <li>📖 阅读 <a href="../docs/03_核心方法.md">核心方法文档</a></li>
            </ul>
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <a href="../index.php" style="background: #3498db; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">🏠 返回学习中心</a>
        </div>
    </div>
</body>
</html>
