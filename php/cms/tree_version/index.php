<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🌳 Tree类学习中心 - 优化版</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', 'Microsoft YaHei', Tahoma, Geneva, Verdana, sans-serif;
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
            padding: 40px 0;
        }
        
        .header h1 {
            font-size: 3.5em;
            margin-bottom: 15px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .header .subtitle {
            font-size: 1.4em;
            opacity: 0.95;
            margin-bottom: 20px;
        }
        
        .header .version-badge {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 1.1em;
            border: 2px solid rgba(255,255,255,0.3);
        }
        
        .learning-path {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        
        .step-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .step-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .step-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(135deg, #667eea, #764ba2);
        }
        
        .step-number {
            display: inline-block;
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-radius: 50%;
            text-align: center;
            line-height: 50px;
            font-size: 1.5em;
            font-weight: bold;
            margin-bottom: 20px;
        }
        
        .step-card h3 {
            color: #2d3748;
            margin-bottom: 15px;
            font-size: 1.4em;
        }
        
        .step-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            font-size: 0.9em;
            color: #718096;
        }
        
        .difficulty {
            display: flex;
            gap: 2px;
        }
        
        .star {
            color: #ffd700;
        }
        
        .step-card p {
            color: #4a5568;
            margin-bottom: 20px;
        }
        
        .step-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
            cursor: pointer;
            font-size: 0.9em;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-secondary {
            background: #f7fafc;
            color: #4a5568;
            border: 2px solid #e2e8f0;
        }
        
        .btn-secondary:hover {
            background: #edf2f7;
            border-color: #cbd5e0;
        }
        
        .quick-start {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 40px;
        }
        
        .quick-start h2 {
            text-align: center;
            color: #2d3748;
            margin-bottom: 30px;
            font-size: 2.2em;
        }
        
        .quick-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .quick-option {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 25px;
            text-align: center;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }
        
        .quick-option:hover {
            border-color: #667eea;
            background: #f0f4ff;
        }
        
        .quick-option .icon {
            font-size: 2.5em;
            margin-bottom: 15px;
        }
        
        .quick-option h4 {
            color: #2d3748;
            margin-bottom: 10px;
        }
        
        .project-stats {
            background: rgba(255,255,255,0.1);
            border-radius: 15px;
            padding: 30px;
            color: white;
            text-align: center;
            margin-bottom: 40px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-number {
            font-size: 2.5em;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 0.9em;
            opacity: 0.8;
        }
        
        .footer {
            text-align: center;
            color: white;
            opacity: 0.8;
            margin-top: 40px;
            padding: 20px;
        }
        
        @media (max-width: 768px) {
            .header h1 {
                font-size: 2.5em;
            }
            
            .learning-path {
                grid-template-columns: 1fr;
            }
            
            .step-actions {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🌳 Tree类学习中心</h1>
            <div class="subtitle">系统化掌握PHP树形数据处理 - 从零基础到项目实战</div>
            <div class="version-badge">✨ 优化版 v2.0</div>
        </div>
        
        <div class="project-stats">
            <h3>📊 项目统计</h3>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">4</div>
                    <div class="stat-label">学习步骤</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">15+</div>
                    <div class="stat-label">示例文件</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">3</div>
                    <div class="stat-label">Tree类版本</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">2000+</div>
                    <div class="stat-label">代码行数</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">20000+</div>
                    <div class="stat-label">文档字数</div>
                </div>
            </div>
        </div>
        
        <div class="quick-start">
            <h2>🚀 快速开始</h2>
            <div class="quick-options">
                <div class="quick-option">
                    <div class="icon">🎯</div>
                    <h4>新手入门</h4>
                    <p>从基础概念开始，循序渐进学习</p>
                    <a href="tutorial/step1-basics/" class="btn btn-primary">开始学习</a>
                </div>
                <div class="quick-option">
                    <div class="icon">⚡</div>
                    <h4>快速体验</h4>
                    <p>直接运行示例，快速了解功能</p>
                    <a href="playground/tools/run_examples.php" class="btn btn-primary">运行演示</a>
                </div>
                <div class="quick-option">
                    <div class="icon">🎮</div>
                    <h4>可视化演示</h4>
                    <p>交互式界面，直观理解树形结构</p>
                    <a href="playground/demos/assets/tree_demo.html" class="btn btn-primary">可视化演示</a>
                </div>
                <div class="quick-option">
                    <div class="icon">📚</div>
                    <h4>完整文档</h4>
                    <p>详细的学习指南和技术文档</p>
                    <a href="resources/documentation/" class="btn btn-primary">查看文档</a>
                </div>
            </div>
        </div>
        
        <div class="learning-path">
            <div class="step-card">
                <div class="step-number">1</div>
                <h3>📚 基础概念掌握</h3>
                <div class="step-meta">
                    <span>⏱️ 1-2小时</span>
                    <div class="difficulty">
                        <span class="star">⭐</span>
                    </div>
                </div>
                <p>理解树形结构的基本概念，学习PHP中的数据表示方法，为后续学习打下坚实基础。</p>
                <div class="step-actions">
                    <a href="tutorial/step1-basics/" class="btn btn-primary">开始学习</a>
                    <a href="tutorial/step1-basics/README.md" class="btn btn-secondary">学习指南</a>
                </div>
            </div>
            
            <div class="step-card">
                <div class="step-number">2</div>
                <h3>💻 动手实践</h3>
                <div class="step-meta">
                    <span>⏱️ 2-3小时</span>
                    <div class="difficulty">
                        <span class="star">⭐⭐</span>
                    </div>
                </div>
                <p>掌握SimpleTree类的核心方法，通过实际代码练习理解递归算法在树形结构中的应用。</p>
                <div class="step-actions">
                    <a href="tutorial/step2-practice/" class="btn btn-primary">开始实践</a>
                    <a href="tutorial/step2-practice/basic_usage.php" class="btn btn-secondary">运行示例</a>
                </div>
            </div>
            
            <div class="step-card">
                <div class="step-number">3</div>
                <h3>🎯 实际应用</h3>
                <div class="step-meta">
                    <span>⏱️ 3-4小时</span>
                    <div class="difficulty">
                        <span class="star">⭐⭐⭐</span>
                    </div>
                </div>
                <p>在真实项目场景中应用Tree类，学习网站菜单系统、分类管理等实用功能的开发。</p>
                <div class="step-actions">
                    <a href="tutorial/step3-application/" class="btn btn-primary">项目实战</a>
                    <a href="tutorial/step3-application/menu_demo.php" class="btn btn-secondary">菜单演示</a>
                </div>
            </div>
            
            <div class="step-card">
                <div class="step-number">4</div>
                <h3>🚀 高级进阶</h3>
                <div class="step-meta">
                    <span>⏱️ 2-3小时</span>
                    <div class="difficulty">
                        <span class="star">⭐⭐⭐⭐</span>
                    </div>
                </div>
                <p>掌握高级特性和性能优化技巧，完成综合练习，具备在生产环境中使用Tree类的能力。</p>
                <div class="step-actions">
                    <a href="tutorial/step4-advanced/" class="btn btn-primary">高级挑战</a>
                    <a href="tutorial/step4-advanced/exercises/" class="btn btn-secondary">练习题</a>
                </div>
            </div>
        </div>
        
        <div class="quick-start">
            <h2>🛠️ 开发工具</h2>
            <div class="quick-options">
                <div class="quick-option">
                    <div class="icon">🔧</div>
                    <h4>SimpleTree</h4>
                    <p>简化版Tree类，专为学习设计</p>
                    <a href="library/core/SimpleTree.php" class="btn btn-secondary">查看源码</a>
                </div>
                <div class="quick-option">
                    <div class="icon">⚡</div>
                    <h4>BasicTree</h4>
                    <p>基础版Tree类，功能更丰富</p>
                    <a href="library/extended/BasicTree.php" class="btn btn-secondary">查看源码</a>
                </div>
                <div class="quick-option">
                    <div class="icon">🎨</div>
                    <h4>代码沙盒</h4>
                    <p>实验环境，自由测试代码</p>
                    <a href="playground/sandbox/" class="btn btn-secondary">进入沙盒</a>
                </div>
                <div class="quick-option">
                    <div class="icon">🛠️</div>
                    <h4>调试工具</h4>
                    <p>代码格式化和调试辅助</p>
                    <a href="library/utils/" class="btn btn-secondary">工具集</a>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <p>🎉 <strong>Tree类学习项目 - 优化版</strong></p>
            <p>专为初学者设计的完整学习系统，助你成为Tree类专家！</p>
            <p style="margin-top: 15px; font-size: 0.9em;">
                💡 建议学习时间：1-2周 | 适合人群：PHP初学者、Web开发者 | 
                <a href="README.md" style="color: white; text-decoration: underline;">查看完整说明</a>
            </p>
        </div>
    </div>
    
    <script>
        // 简单的进度追踪
        document.addEventListener('DOMContentLoaded', function() {
            // 检查localStorage中的学习进度
            const progress = JSON.parse(localStorage.getItem('treeClassProgress') || '{}');
            
            // 为已完成的步骤添加标记
            Object.keys(progress).forEach(step => {
                if (progress[step]) {
                    const stepCard = document.querySelector(`[href*="${step}"]`);
                    if (stepCard) {
                        stepCard.innerHTML += ' ✅';
                        stepCard.style.background = 'linear-gradient(135deg, #48bb78, #38a169)';
                    }
                }
            });
            
            // 添加点击事件记录学习进度
            document.querySelectorAll('.btn-primary').forEach(btn => {
                btn.addEventListener('click', function() {
                    const href = this.getAttribute('href');
                    if (href && href.includes('tutorial/')) {
                        const step = href.split('/')[1];
                        progress[step] = true;
                        localStorage.setItem('treeClassProgress', JSON.stringify(progress));
                    }
                });
            });
        });
        
        // 添加一些交互效果
        document.querySelectorAll('.step-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    </script>
</body>
</html>