// 递归可视化演示的 JavaScript 代码

// 全局变量
let currentDemo = 'factorial';
let animationSpeed = 500;
let isAutoPlay = false;
let isPaused = false;

// 工具函数
function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

function getSpeed() {
    const speedValue = document.querySelector(`#${currentDemo}-speed`).value;
    return 1100 - (speedValue * 100); // 速度范围: 1000ms 到 100ms
}

// 演示切换
document.querySelectorAll('.demo-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        // 更新活动状态
        document.querySelectorAll('.demo-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.demo-container').forEach(d => d.classList.remove('active'));
        
        this.classList.add('active');
        const demoId = this.getAttribute('data-demo');
        document.getElementById(`${demoId}-demo`).classList.add('active');
        currentDemo = demoId;
    });
});

// ==================== 阶乘演示 ====================
class FactorialDemo {
    constructor() {
        this.stack = [];
        this.flow = [];
        this.isRunning = false;
        this.setupEventListeners();
    }

    setupEventListeners() {
        document.getElementById('factorial-start').addEventListener('click', () => this.start());
        document.getElementById('factorial-reset').addEventListener('click', () => this.reset());
        document.getElementById('factorial-auto').addEventListener('change', (e) => {
            isAutoPlay = e.target.checked;
        });
    }

    async start() {
        if (this.isRunning) return;
        
        this.isRunning = true;
        this.reset();
        
        const n = parseInt(document.getElementById('factorial-input').value);
        const result = await this.factorial(n);
        
        document.getElementById('factorial-result').textContent = `${n}! = ${result}`;
        this.isRunning = false;
    }

    reset() {
        this.stack = [];
        this.flow = [];
        document.getElementById('factorial-stack').innerHTML = '';
        document.getElementById('factorial-flow').innerHTML = '';
        document.getElementById('factorial-result').textContent = '';
        this.clearCodeHighlight();
    }

    async factorial(n, depth = 0) {
        // 添加到调用栈
        const frameId = `frame-${Date.now()}-${Math.random()}`;
        this.pushStack(frameId, `factorial(${n})`);
        
        // 添加到执行流程
        this.addFlow(`调用 factorial(${n})`, 'calling');
        
        // 高亮代码
        this.highlightCode(1);
        await sleep(getSpeed());
        
        if (n <= 1) {
            this.highlightCode(2);
            await sleep(getSpeed());
            
            this.addFlow(`基础情况: factorial(${n}) = 1`, 'result');
            
            // 从栈中弹出
            await sleep(getSpeed());
            this.popStack(frameId);
            this.addFlow(`返回 1`, 'returning');
            
            return 1;
        }
        
        this.highlightCode(4);
        await sleep(getSpeed());
        
        // 递归调用
        const result = n * await this.factorial(n - 1, depth + 1);
        
        this.addFlow(`计算: ${n} × ${result / n} = ${result}`, 'result');
        
        // 从栈中弹出
        await sleep(getSpeed());
        this.popStack(frameId);
        this.addFlow(`返回 ${result}`, 'returning');
        
        return result;
    }

    pushStack(id, content) {
        const stackContainer = document.getElementById('factorial-stack');
        const frame = document.createElement('div');
        frame.className = 'stack-frame active';
        frame.id = id;
        frame.textContent = content;
        stackContainer.insertBefore(frame, stackContainer.firstChild);
    }

    popStack(id) {
        const frame = document.getElementById(id);
        if (frame) {
            frame.classList.remove('active');
            frame.classList.add('returning');
            setTimeout(() => frame.remove(), 300);
        }
    }

    addFlow(content, type) {
        const flowContainer = document.getElementById('factorial-flow');
        const item = document.createElement('div');
        item.className = `flow-item ${type}`;
        item.textContent = content;
        flowContainer.appendChild(item);
        flowContainer.scrollTop = flowContainer.scrollHeight;
    }

    highlightCode(line) {
        this.clearCodeHighlight();
        const lines = document.querySelectorAll('#factorial-code .line');
        lines.forEach(l => {
            if (parseInt(l.getAttribute('data-line')) === line) {
                l.classList.add('active');
            }
        });
    }

    clearCodeHighlight() {
        document.querySelectorAll('#factorial-code .line').forEach(l => {
            l.classList.remove('active');
        });
    }
}

// ==================== 斐波那契演示 ====================
class FibonacciDemo {
    constructor() {
        this.callCount = 0;
        this.duplicates = {};
        this.nodes = [];
        this.edges = [];
        this.isRunning = false;
        this.setupEventListeners();
    }

    setupEventListeners() {
        document.getElementById('fibonacci-start').addEventListener('click', () => this.start());
        document.getElementById('fibonacci-reset').addEventListener('click', () => this.reset());
        document.getElementById('fibonacci-auto').addEventListener('change', (e) => {
            isAutoPlay = e.target.checked;
        });
    }

    async start() {
        if (this.isRunning) return;
        
        this.isRunning = true;
        this.reset();
        
        const n = parseInt(document.getElementById('fibonacci-input').value);
        
        // 初始化 SVG
        this.initSVG();
        
        // 创建根节点
        const rootNode = this.createNode(n, 200, 50);
        
        const result = await this.fibonacci(n, rootNode);
        
        document.getElementById('fibonacci-calls').textContent = this.callCount;
        document.getElementById('fibonacci-duplicates').textContent = 
            Object.values(this.duplicates).reduce((sum, count) => sum + Math.max(0, count - 1), 0);
        
        this.isRunning = false;
    }

    reset() {
        this.callCount = 0;
        this.duplicates = {};
        this.nodes = [];
        this.edges = [];
        document.getElementById('fibonacci-sequence').innerHTML = '';
        document.getElementById('fibonacci-calls').textContent = '0';
        document.getElementById('fibonacci-duplicates').textContent = '0';
        this.clearSVG();
    }

    initSVG() {
        const svg = document.getElementById('fibonacci-tree');
        svg.innerHTML = '';
        
        // 添加连线组
        const edgeGroup = document.createElementNS('http://www.w3.org/2000/svg', 'g');
        edgeGroup.id = 'edge-group';
        svg.appendChild(edgeGroup);
        
        // 添加节点组
        const nodeGroup = document.createElementNS('http://www.w3.org/2000/svg', 'g');
        nodeGroup.id = 'node-group';
        svg.appendChild(nodeGroup);
    }

    clearSVG() {
        const svg = document.getElementById('fibonacci-tree');
        svg.innerHTML = '';
    }

    createNode(value, x, y) {
        const svg = document.getElementById('fibonacci-tree');
        const nodeGroup = svg.getElementById('node-group');
        
        const g = document.createElementNS('http://www.w3.org/2000/svg', 'g');
        g.className = 'fib-node';
        g.setAttribute('transform', `translate(${x}, ${y})`);
        
        const circle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
        circle.setAttribute('r', '20');
        
        const text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
        text.textContent = `F(${value})`;
        
        g.appendChild(circle);
        g.appendChild(text);
        nodeGroup.appendChild(g);
        
        const node = { element: g, value, x, y };
        this.nodes.push(node);
        
        return node;
    }

    createEdge(parent, child) {
        const svg = document.getElementById('fibonacci-tree');
        const edgeGroup = svg.getElementById('edge-group');
        
        const line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
        line.className = 'fib-link';
        line.setAttribute('x1', parent.x);
        line.setAttribute('y1', parent.y + 20);
        line.setAttribute('x2', child.x);
        line.setAttribute('y2', child.y - 20);
        
        edgeGroup.appendChild(line);
        this.edges.push(line);
    }

    async fibonacci(n, node, x = 400, y = 50, level = 0) {
        this.callCount++;
        
        // 记录重复计算
        this.duplicates[n] = (this.duplicates[n] || 0) + 1;
        
        // 添加到序列
        this.addSequence(`F(${n})`);
        
        // 激活当前节点
        node.element.classList.add('active');
        
        // 高亮代码
        this.highlightCode(1);
        await sleep(getSpeed());
        
        if (n <= 1) {
            this.highlightCode(2);
            await sleep(getSpeed());
            
            // 标记完成
            node.element.classList.remove('active');
            node.element.classList.add('completed');
            
            // 更新节点文本
            const text = node.element.querySelector('text');
            text.textContent = `${n}`;
            
            return n;
        }
        
        this.highlightCode(4);
        await sleep(getSpeed());
        
        // 计算子节点位置
        const spacing = Math.max(30, 200 / Math.pow(2, level));
        const leftX = x - spacing;
        const rightX = x + spacing;
        const childY = y + 80;
        
        // 创建左子节点
        const leftNode = this.createNode(n - 1, leftX, childY);
        this.createEdge(node, leftNode);
        
        // 创建右子节点
        const rightNode = this.createNode(n - 2, rightX, childY);
        this.createEdge(node, rightNode);
        
        // 递归计算
        const left = await this.fibonacci(n - 1, leftNode, leftX, childY, level + 1);
        const right = await this.fibonacci(n - 2, rightNode, rightX, childY, level + 1);
        
        const result = left + right;
        
        // 更新节点文本
        const text = node.element.querySelector('text');
        text.textContent = `${result}`;
        
        // 标记完成
        node.element.classList.remove('active');
        node.element.classList.add('completed');
        
        return result;
    }

    addSequence(content) {
        const container = document.getElementById('fibonacci-sequence');
        const item = document.createElement('span');
        item.style.marginRight = '10px';
        item.textContent = content;
        container.appendChild(item);
    }

    highlightCode(line) {
        document.querySelectorAll('#fibonacci-code .line').forEach(l => {
            l.classList.remove('active');
            if (parseInt(l.getAttribute('data-line')) === line) {
                l.classList.add('active');
            }
        });
    }
}

// ==================== 汉诺塔演示 ====================
class TowerOfHanoiDemo {
    constructor() {
        this.disks = [];
        this.poles = { A: [], B: [], C: [] };
        this.moves = [];
        this.isRunning = false;
        this.currentStep = 0;
        this.setupEventListeners();
    }

    setupEventListeners() {
        document.getElementById('tower-start').addEventListener('click', () => this.start());
        document.getElementById('tower-reset').addEventListener('click', () => this.reset());
        document.getElementById('tower-step').addEventListener('click', () => this.step());
    }

    async start() {
        if (this.isRunning) return;
        
        this.isRunning = true;
        this.reset();
        
        const n = parseInt(document.getElementById('tower-input').value);
        this.initDisks(n);
        
        // 计算移动步骤
        this.moves = [];
        this.calculateMoves(n, 'A', 'C', 'B');
        
        // 执行动画
        for (let i = 0; i < this.moves.length; i++) {
            if (!this.isRunning) break;
            
            const move = this.moves[i];
            await this.animateMove(move.from, move.to, i);
            await sleep(getSpeed());
        }
        
        this.isRunning = false;
    }

    reset() {
        this.disks = [];
        this.poles = { A: [], B: [], C: [] };
        this.moves = [];
        this.currentStep = 0;
        this.isRunning = false;
        
        // 清空所有柱子
        ['A', 'B', 'C'].forEach(pole => {
            const poleElement = document.getElementById(`pole-${pole}`);
            const disks = poleElement.querySelectorAll('.disk');
            disks.forEach(disk => disk.remove());
        });
        
        // 清空移动记录
        document.getElementById('tower-moves').innerHTML = '';
    }

    step() {
        if (this.currentStep >= this.moves.length) return;
        
        const move = this.moves[this.currentStep];
        this.animateMove(move.from, move.to, this.currentStep);
        this.currentStep++;
    }

    initDisks(n) {
        const poleA = document.getElementById('pole-A');
        
        for (let i = n; i >= 1; i--) {
            const disk = document.createElement('div');
            disk.className = 'disk';
            disk.id = `disk-${i}`;
            disk.style.width = `${20 + i * 20}px`;
            disk.style.backgroundColor = this.getDiskColor(i);
            disk.style.bottom = `${20 + (n - i) * 30}px`;
            disk.textContent = i;
            
            poleA.appendChild(disk);
            this.poles.A.push(i);
        }
    }

    getDiskColor(n) {
        const colors = ['#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FECA57', '#FF9FF3'];
        return colors[(n - 1) % colors.length];
    }

    calculateMoves(n, from, to, aux) {
        if (n === 1) {
            this.moves.push({ from, to, disk: 1 });
            return;
        }
        
        this.calculateMoves(n - 1, from, aux, to);
        this.moves.push({ from, to, disk: n });
        this.calculateMoves(n - 1, aux, to, from);
    }

    async animateMove(from, to, moveIndex) {
        // 获取要移动的盘子
        const diskId = this.poles[from][this.poles[from].length - 1];
        const disk = document.getElementById(`disk-${diskId}`);
        
        if (!disk) return;
        
        // 从源柱子移除
        this.poles[from].pop();
        
        // 添加到目标柱子
        this.poles[to].push(diskId);
        
        // 动画效果
        disk.classList.add('moving');
        
        // 移动到目标位置
        const targetPole = document.getElementById(`pole-${to}`);
        targetPole.appendChild(disk);
        
        // 更新位置
        const targetHeight = this.poles[to].length - 1;
        disk.style.bottom = `${20 + targetHeight * 30}px`;
        
        // 添加移动记录
        this.addMoveRecord(`移动盘子 ${diskId}: ${from} → ${to}`, moveIndex);
        
        setTimeout(() => disk.classList.remove('moving'), 500);
    }

    addMoveRecord(text, index) {
        const container = document.getElementById('tower-moves');
        const record = document.createElement('div');
        record.className = 'move-item';
        record.textContent = `步骤 ${index + 1}: ${text}`;
        
        // 高亮当前步骤
        container.querySelectorAll('.move-item').forEach(item => {
            item.classList.remove('current');
        });
        record.classList.add('current');
        
        container.appendChild(record);
        container.scrollTop = container.scrollHeight;
    }
}

// ==================== 二叉树遍历演示 ====================
class BinaryTreeDemo {
    constructor() {
        this.tree = null;
        this.visitedNodes = [];
        this.isRunning = false;
        this.currentTraversal = 'preorder';
        this.setupEventListeners();
        this.initTree();
    }

    setupEventListeners() {
        document.getElementById('tree-start').addEventListener('click', () => this.start());
        document.getElementById('tree-reset').addEventListener('click', () => this.reset());
        document.getElementById('tree-step').addEventListener('click', () => this.step());
        document.getElementById('tree-traversal').addEventListener('change', (e) => {
            this.currentTraversal = e.target.value;
            this.updateCodeHighlight();
        });
    }

    initTree() {
        // 创建示例二叉树
        this.tree = {
            value: 1,
            left: {
                value: 2,
                left: { value: 4, left: null, right: null },
                right: { value: 5, left: null, right: null }
            },
            right: {
                value: 3,
                left: { value: 6, left: null, right: null },
                right: { value: 7, left: null, right: null }
            }
        };
        
        this.drawTree();
    }

    drawTree() {
        const svg = document.getElementById('binary-tree');
        svg.innerHTML = '';
        
        // 添加边
        const edgeGroup = document.createElementNS('http://www.w3.org/2000/svg', 'g');
        edgeGroup.id = 'tree-edges';
        svg.appendChild(edgeGroup);
        
        // 添加节点
        const nodeGroup = document.createElementNS('http://www.w3.org/2000/svg', 'g');
        nodeGroup.id = 'tree-nodes';
        svg.appendChild(nodeGroup);
        
        // 绘制树
        this.drawNode(this.tree, 300, 50, 150);
    }

    drawNode(node, x, y, spacing) {
        if (!node) return;
        
        const svg = document.getElementById('binary-tree');
        const nodeGroup = svg.getElementById('tree-nodes');
        const edgeGroup = svg.getElementById('tree-edges');
        
        // 绘制子节点的边
        if (node.left) {
            const line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
            line.className = 'tree-edge';
            line.setAttribute('x1', x);
            line.setAttribute('y1', y);
            line.setAttribute('x2', x - spacing);
            line.setAttribute('y2', y + 80);
            edgeGroup.appendChild(line);
        }
        
        if (node.right) {
            const line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
            line.className = 'tree-edge';
            line.setAttribute('x1', x);
            line.setAttribute('y1', y);
            line.setAttribute('x2', x + spacing);
            line.setAttribute('y2', y + 80);
            edgeGroup.appendChild(line);
        }
        
        // 绘制节点
        const g = document.createElementNS('http://www.w3.org/2000/svg', 'g');
        g.className = 'tree-node';
        g.setAttribute('transform', `translate(${x}, ${y})`);
        g.setAttribute('data-value', node.value);
        
        const circle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
        circle.setAttribute('r', '25');
        
        const text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
        text.textContent = node.value;
        
        g.appendChild(circle);
        g.appendChild(text);
        nodeGroup.appendChild(g);
        
        // 递归绘制子节点
        if (node.left) {
            this.drawNode(node.left, x - spacing, y + 80, spacing / 2);
        }
        if (node.right) {
            this.drawNode(node.right, x + spacing, y + 80, spacing / 2);
        }
    }

    async start() {
        if (this.isRunning) return;
        
        this.isRunning = true;
        this.reset();
        
        const result = [];
        
        switch (this.currentTraversal) {
            case 'preorder':
                await this.preorderTraversal(this.tree, result);
                break;
            case 'inorder':
                await this.inorderTraversal(this.tree, result);
                break;
            case 'postorder':
                await this.postorderTraversal(this.tree, result);
                break;
        }
        
        this.isRunning = false;
    }

    reset() {
        this.visitedNodes = [];
        this.isRunning = false;
        
        // 重置节点状态
        document.querySelectorAll('.tree-node').forEach(node => {
            node.classList.remove('visiting', 'visited');
        });
        
        // 清空结果
        document.getElementById('tree-result').innerHTML = '';
    }

    async preorderTraversal(node, result) {
        if (!node || !this.isRunning) return;
        
        // 访问当前节点
        await this.visitNode(node.value);
        result.push(node.value);
        
        // 递归左子树
        await this.preorderTraversal(node.left, result);
        
        // 递归右子树
        await this.preorderTraversal(node.right, result);
    }

    async inorderTraversal(node, result) {
        if (!node || !this.isRunning) return;
        
        // 递归左子树
        await this.inorderTraversal(node.left, result);
        
        // 访问当前节点
        await this.visitNode(node.value);
        result.push(node.value);
        
        // 递归右子树
        await this.inorderTraversal(node.right, result);
    }

    async postorderTraversal(node, result) {
        if (!node || !this.isRunning) return;
        
        // 递归左子树
        await this.postorderTraversal(node.left, result);
        
        // 递归右子树
        await this.postorderTraversal(node.right, result);
        
        // 访问当前节点
        await this.visitNode(node.value);
        result.push(node.value);
    }

    async visitNode(value) {
        // 高亮当前节点
        const node = document.querySelector(`.tree-node[data-value="${value}"]`);
        if (node) {
            node.classList.add('visiting');
            
            // 添加到结果
            this.addResult(value);
            
            await sleep(getSpeed());
            
            node.classList.remove('visiting');
            node.classList.add('visited');
        }
    }

    addResult(value) {
        const container = document.getElementById('tree-result');
        const node = document.createElement('div');
        node.className = 'result-node';
        node.textContent = value;
        container.appendChild(node);
    }

    updateCodeHighlight() {
        // 根据遍历方式高亮不同的代码行
        document.querySelectorAll('#tree-code .line').forEach(line => {
            line.style.opacity = '0.5';
        });
        
        const highlightLines = {
            'preorder': [2],
            'inorder': [4],
            'postorder': [6]
        };
        
        highlightLines[this.currentTraversal].forEach(lineNum => {
            const line = document.querySelector(`#tree-code .line.${this.currentTraversal}`);
            if (line) {
                line.style.opacity = '1';
                line.style.backgroundColor = 'rgba(97, 218, 251, 0.2)';
            }
        });
    }

    step() {
        // 单步执行功能（简化版）
        console.log('单步执行功能待实现');
    }
}

// 初始化所有演示
document.addEventListener('DOMContentLoaded', function() {
    new FactorialDemo();
    new FibonacciDemo();
    new TowerOfHanoiDemo();
    new BinaryTreeDemo();
});
