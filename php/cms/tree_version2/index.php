<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🌳 Tree类学习中心 - 从零开始掌握树形数据处理</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            color: white;
            margin-bottom: 40px;
        }
        
        .header h1 {
            font-size: 3em;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .header p {
            font-size: 1.2em;
            opacity: 0.9;
        }
        
        .main-content {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .intro {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .intro h2 {
            color: #4a5568;
            margin-bottom: 15px;
        }
        
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .feature {
            background: #f7fafc;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            border-left: 4px solid #4299e1;
        }
        
        .feature h3 {
            color: #2d3748;
            margin-bottom: 10px;
        }
        
        .learning-path {
            margin-bottom: 40px;
        }
        
        .learning-path h2 {
            text-align: center;
            color: #2d3748;
            margin-bottom: 30px;
            font-size: 2em;
        }
        
        .path-level {
            background: #f8f9fa;
            border-radius: 10px;
            margin-bottom: 20px;
            overflow: hidden;
            border: 2px solid #e2e8f0;
        }
        
        .level-header {
            background: linear-gradient(135deg, #4299e1, #3182ce);
            color: white;
            padding: 20px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        
        .level-header:hover {
            background: linear-gradient(135deg, #3182ce, #2c5282);
        }
        
        .level-header h3 {
            font-size: 1.3em;
            margin-bottom: 5px;
        }
        
        .level-meta {
            font-size: 0.9em;
            opacity: 0.9;
        }
        
        .level-content {
            padding: 20px;
            display: none;
        }
        
        .level-content.active {
            display: block;
        }
        
        .resources {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .resource {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .resource:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .resource h4 {
            color: #2d3748;
            margin-bottom: 8px;
        }
        
        .resource p {
            color: #718096;
            font-size: 0.9em;
            margin-bottom: 10px;
        }
        
        .resource-links {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.9em;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
        }
        
        .btn-primary {
            background: #4299e1;
            color: white;
        }
        
        .btn-primary:hover {
            background: #3182ce;
        }
        
        .btn-secondary {
            background: #e2e8f0;
            color: #4a5568;
        }
        
        .btn-secondary:hover {
            background: #cbd5e0;
        }
        
        .btn-success {
            background: #48bb78;
            color: white;
        }
        
        .btn-success:hover {
            background: #38a169;
        }
        
        .progress-section {
            background: #f7fafc;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }
        
        .progress-section h3 {
            color: #2d3748;
            margin-bottom: 15px;
        }
        
        .checklist {
            columns: 2;
            column-gap: 30px;
        }
        
        .checklist-item {
            margin-bottom: 8px;
            break-inside: avoid;
        }
        
        .checklist-item input {
            margin-right: 8px;
        }
        
        .quick-demo {
            background: linear-gradient(135deg, #38b2ac, #319795);
            color: white;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .quick-demo h3 {
            margin-bottom: 15px;
            font-size: 1.5em;
        }
        
        .demo-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .btn-demo {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 2px solid rgba(255,255,255,0.3);
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .btn-demo:hover {
            background: rgba(255,255,255,0.3);
            border-color: rgba(255,255,255,0.5);
        }
        
        .footer {
            text-align: center;
            color: white;
            opacity: 0.8;
            margin-top: 30px;
        }
        
        @media (max-width: 768px) {
            .header h1 {
                font-size: 2em;
            }
            
            .main-content {
                padding: 20px;
            }
            
            .checklist {
                columns: 1;
            }
            
            .demo-buttons {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🌳 Tree类学习中心</h1>
            <p>从零开始，系统掌握PHP树形数据处理</p>
        </div>
        
        <div class="main-content">
            <div class="intro">
                <h2>欢迎来到Tree类学习之旅！</h2>
                <p>这是一个专为初学者设计的完整学习系统，帮助你掌握树形数据结构的处理方法。</p>
            </div>
            
            <div class="features">
                <div class="feature">
                    <h3>📚 系统化教学</h3>
                    <p>从基础概念到实际应用，循序渐进的学习路径</p>
                </div>
                <div class="feature">
                    <h3>💻 动手实践</h3>
                    <p>丰富的示例代码和练习题，学以致用</p>
                </div>
                <div class="feature">
                    <h3>🎯 实战导向</h3>
                    <p>真实项目场景，掌握实用技能</p>
                </div>
                <div class="feature">
                    <h3>🔧 简化版本</h3>
                    <p>专为学习设计的SimpleTree类，易懂易用</p>
                </div>
            </div>
            
            <div class="quick-demo">
                <h3>🚀 快速体验</h3>
                <p>还没开始学习？先看看Tree类能做什么！</p>
                <div class="demo-buttons">
                    <a href="run_examples.php" class="btn-demo">运行所有演示</a>
                    <a href="examples/basic_usage.php" class="btn-demo">基础使用示例</a>
                    <a href="examples/menu_demo.php" class="btn-demo">菜单系统演示</a>
                </div>
            </div>
        </div>
        
        <div class="main-content">
            <div class="learning-path">
                <h2>📈 学习路径规划</h2>
                <p style="text-align: center; margin-bottom: 30px; color: #718096;">
                    按照以下路径学习，从易到难，循序渐进掌握Tree类
                </p>
                
                <!-- 第一阶段：理论基础 -->
                <div class="path-level">
                    <div class="level-header" onclick="toggleLevel(1)">
                        <h3>🎯 第一阶段：理论基础</h3>
                        <div class="level-meta">预计用时：2-3小时 | 难度：⭐⭐</div>
                    </div>
                    <div class="level-content" id="level1">
                        <div class="resources">
                            <div class="resource">
                                <h4>📖 基础概念学习</h4>
                                <p>了解什么是树形结构，掌握基本术语和概念</p>
                                <div class="resource-links">
                                    <a href="docs/01_基础概念.md" class="btn btn-primary">开始学习</a>
                                </div>
                            </div>
                            <div class="resource">
                                <h4>🏗️ 数据结构深入</h4>
                                <p>学习PHP中树形数据的表示方法和设计原则</p>
                                <div class="resource-links">
                                    <a href="docs/02_数据结构.md" class="btn btn-primary">查看详情</a>
                                </div>
                            </div>
                            <div class="resource">
                                <h4>⚙️ 核心方法掌握</h4>
                                <p>掌握Tree类的所有核心方法和使用技巧</p>
                                <div class="resource-links">
                                    <a href="docs/03_核心方法.md" class="btn btn-primary">方法详解</a>
                                </div>
                            </div>
                        </div>
                        <div style="background: #e6fffa; padding: 15px; border-radius: 8px; border-left: 4px solid #38b2ac;">
                            <strong>学习建议：</strong> 这个阶段重点是理解概念，不要急着写代码。仔细阅读文档，理解每个术语的含义。
                        </div>
                    </div>
                </div>
                
                <!-- 第二阶段：动手实践 -->
                <div class="path-level">
                    <div class="level-header" onclick="toggleLevel(2)">
                        <h3>💻 第二阶段：动手实践</h3>
                        <div class="level-meta">预计用时：3-4小时 | 难度：⭐⭐⭐</div>
                    </div>
                    <div class="level-content" id="level2">
                        <div class="resources">
                            <div class="resource">
                                <h4>🔍 源码阅读</h4>
                                <p>阅读SimpleTree类的实现，理解每个方法的工作原理</p>
                                <div class="resource-links">
                                    <a href="src/SimpleTree.php" class="btn btn-primary">查看源码</a>
                                    <a href="tool/print.php" class="btn btn-secondary">格式化查看</a>
                                </div>
                            </div>
                            <div class="resource">
                                <h4>🚀 基础示例</h4>
                                <p>运行基础使用示例，观察Tree类的实际效果</p>
                                <div class="resource-links">
                                    <a href="examples/basic_usage.php" class="btn btn-success">运行示例</a>
                                    <a href="data/treedata.php" class="btn btn-secondary">查看数据</a>
                                </div>
                            </div>
                            <div class="resource">
                                <h4>📝 练习题目</h4>
                                <p>完成基础练习，巩固所学知识</p>
                                <div class="resource-links">
                                    <a href="exercises/exercise_01.php" class="btn btn-primary">开始练习</a>
                                    <a href="exercises/solutions/" class="btn btn-secondary">参考答案</a>
                                </div>
                            </div>
                        </div>
                        <div style="background: #fff5f5; padding: 15px; border-radius: 8px; border-left: 4px solid #f56565;">
                            <strong>重要提示：</strong> 一定要亲自运行代码！只看不做永远学不会编程。遇到错误不要慌，这是学习的必经之路。
                        </div>
                    </div>
                </div>
                
                <!-- 第三阶段：实际应用 -->
                <div class="path-level">
                    <div class="level-header" onclick="toggleLevel(3)">
                        <h3>🎯 第三阶段：实际应用</h3>
                        <div class="level-meta">预计用时：4-5小时 | 难度：⭐⭐⭐⭐</div>
                    </div>
                    <div class="level-content" id="level3">
                        <div class="resources">
                            <div class="resource">
                                <h4>🎨 菜单系统</h4>
                                <p>学习如何生成网站导航菜单和面包屑导航</p>
                                <div class="resource-links">
                                    <a href="examples/menu_demo.php" class="btn btn-success">菜单演示</a>
                                    <a href="assets/tree_demo.html" class="btn btn-secondary">HTML效果</a>
                                </div>
                            </div>
                            <div class="resource">
                                <h4>🏪 分类管理</h4>
                                <p>电商网站的商品分类管理系统实现</p>
                                <div class="resource-links">
                                    <a href="examples/category_demo.php" class="btn btn-success">分类演示</a>
                                </div>
                            </div>
                            <div class="resource">
                                <h4>📋 实战案例</h4>
                                <p>多个真实项目场景的应用案例</p>
                                <div class="resource-links">
                                    <a href="docs/04_实战应用.md" class="btn btn-primary">案例集合</a>
                                </div>
                            </div>
                        </div>
                        <div style="background: #f0fff4; padding: 15px; border-radius: 8px; border-left: 4px solid #48bb78;">
                            <strong>学习重点：</strong> 这个阶段要理解Tree类在真实项目中的应用。尝试修改示例代码，实现你自己的需求。
                        </div>
                    </div>
                </div>
                
                <!-- 第四阶段：进阶提升 -->
                <div class="path-level">
                    <div class="level-header" onclick="toggleLevel(4)">
                        <h3>🚀 第四阶段：进阶提升</h3>
                        <div class="level-meta">预计用时：2-3小时 | 难度：⭐⭐⭐⭐⭐</div>
                    </div>
                    <div class="level-content" id="level4">
                        <div class="resources">
                            <div class="resource">
                                <h4>⚡ 性能优化</h4>
                                <p>学习如何优化Tree类的性能，处理大数据量</p>
                                <div class="resource-links">
                                    <a href="exercises/exercise_02.php" class="btn btn-primary">优化练习</a>
                                </div>
                            </div>
                            <div class="resource">
                                <h4>🔬 原版对比</h4>
                                <p>对比学习原版tree.class.php的高级功能</p>
                                <div class="resource-links">
                                    <a href="../lryblog.com/ryphp/core/class/tree.class.php" class="btn btn-secondary">原版代码</a>
                                </div>
                            </div>
                            <div class="resource">
                                <h4>🛠️ 功能扩展</h4>
                                <p>学习如何扩展Tree类，添加新功能</p>
                                <div class="resource-links">
                                    <a href="src/FullTree.php" class="btn btn-primary">扩展版本</a>
                                </div>
                            </div>
                        </div>
                        <div style="background: #faf5ff; padding: 15px; border-radius: 8px; border-left: 4px solid #9f7aea;">
                            <strong>进阶目标：</strong> 这个阶段要培养你的创新能力。尝试自己实现新功能，解决复杂问题。
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="main-content">
            <div class="progress-section">
                <h3>📊 学习进度自检</h3>
                <p style="margin-bottom: 20px; color: #718096;">勾选已完成的学习目标，追踪你的学习进度</p>
                <div class="checklist">
                    <div class="checklist-item">
                        <input type="checkbox" id="check1"> <label for="check1">理解树形结构基本概念</label>
                    </div>
                    <div class="checklist-item">
                        <input type="checkbox" id="check2"> <label for="check2">掌握Tree类数据格式</label>
                    </div>
                    <div class="checklist-item">
                        <input type="checkbox" id="check3"> <label for="check3">熟悉所有核心方法</label>
                    </div>
                    <div class="checklist-item">
                        <input type="checkbox" id="check4"> <label for="check4">能够阅读SimpleTree源码</label>
                    </div>
                    <div class="checklist-item">
                        <input type="checkbox" id="check5"> <label for="check5">运行所有基础示例</label>
                    </div>
                    <div class="checklist-item">
                        <input type="checkbox" id="check6"> <label for="check6">完成所有练习题</label>
                    </div>
                    <div class="checklist-item">
                        <input type="checkbox" id="check7"> <label for="check7">理解菜单系统实现</label>
                    </div>
                    <div class="checklist-item">
                        <input type="checkbox" id="check8"> <label for="check8">掌握分类管理应用</label>
                    </div>
                    <div class="checklist-item">
                        <input type="checkbox" id="check9"> <label for="check9">了解性能优化策略</label>
                    </div>
                    <div class="checklist-item">
                        <input type="checkbox" id="check10"> <label for="check10">能在项目中独立应用</label>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="main-content">
            <h2 style="text-align: center; margin-bottom: 20px; color: #2d3748;">📚 所有学习资源</h2>
            <div class="resources" style="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));">
                <div class="resource">
                    <h4>📖 学习文档</h4>
                    <div class="resource-links" style="flex-direction: column; gap: 5px;">
                        <a href="README.md" class="btn btn-secondary">项目介绍</a>
                        <a href="LEARNING_GUIDE.md" class="btn btn-secondary">学习指南</a>
                        <a href="PROJECT_SUMMARY.md" class="btn btn-secondary">项目总结</a>
                    </div>
                </div>
                <div class="resource">
                    <h4>💻 源代码</h4>
                    <div class="resource-links" style="flex-direction: column; gap: 5px;">
                        <a href="src/SimpleTree.php" class="btn btn-primary">SimpleTree类</a>
                        <a href="src/BasicTree.php" class="btn btn-secondary">BasicTree类</a>
                        <a href="src/FullTree.php" class="btn btn-secondary">FullTree类</a>
                    </div>
                </div>
                <div class="resource">
                    <h4>🎯 练习题</h4>
                    <div class="resource-links" style="flex-direction: column; gap: 5px;">
                        <a href="exercises/exercise_01.php" class="btn btn-primary">基础练习</a>
                        <a href="exercises/exercise_02.php" class="btn btn-primary">进阶练习</a>
                        <a href="exercises/solutions/" class="btn btn-secondary">参考答案</a>
                    </div>
                </div>
                <div class="resource">
                    <h4>🛠️ 工具助手</h4>
                    <div class="resource-links" style="flex-direction: column; gap: 5px;">
                        <a href="run_demo.php" class="btn btn-success">快速演示</a>
                        <a href="tool/print.php" class="btn btn-secondary">代码格式化</a>
                        <a href="DEBUG_REPORT.md" class="btn btn-secondary">调试报告</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <p>🎉 开始你的Tree类学习之旅，掌握树形数据处理的核心技能！</p>
            <p style="margin-top: 10px; font-size: 0.9em;">
                建议学习时间：1-2周 | 适合人群：PHP初学者、Web开发者
            </p>
        </div>
    </div>
    
    <script>
        function toggleLevel(levelNum) {
            const content = document.getElementById('level' + levelNum);
            const isActive = content.classList.contains('active');
            
            // 关闭所有其他级别
            document.querySelectorAll('.level-content').forEach(el => {
                el.classList.remove('active');
            });
            
            // 切换当前级别
            if (!isActive) {
                content.classList.add('active');
            }
        }
        
        // 默认显示第一阶段
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('level1').classList.add('active');
            
            // 保存学习进度到localStorage
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                const saved = localStorage.getItem(checkbox.id);
                if (saved === 'true') {
                    checkbox.checked = true;
                }
                
                checkbox.addEventListener('change', function() {
                    localStorage.setItem(this.id, this.checked);
                    updateProgress();
                });
            });
            
            updateProgress();
        });
        
        function updateProgress() {
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            const checked = document.querySelectorAll('input[type="checkbox"]:checked');
            const progress = Math.round((checked.length / checkboxes.length) * 100);
            
            // 可以在这里添加进度显示逻辑
            console.log('学习进度：' + progress + '%');
        }
    </script>
</body>
</html>
