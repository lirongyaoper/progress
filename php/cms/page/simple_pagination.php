<?php
/**
 * 简化版PHP分页类 - 教学演示版本
 * 这个版本专门用于教学，代码简洁易懂，注释详细
 */

/**
 * 分页类 - 教学版本
 * 
 * 分页的核心思想：
 * 1. 计算总页数 = ceil(总记录数 / 每页显示数)
 * 2. 计算偏移量 = (当前页 - 1) × 每页显示数
 * 3. 使用SQL的LIMIT和OFFSET进行分页查询
 */
class SimplePagination {
    
    // 分页相关属性
    private $totalRecords = 0;    // 总记录数
    private $recordsPerPage = 10; // 每页显示记录数
    private $currentPage = 1;     // 当前页码
    private $totalPages = 0;      // 总页数
    private $offset = 0;          // 偏移量（用于SQL查询）
    
    /**
     * 构造函数
     * @param int $recordsPerPage 每页显示的记录数
     */
    public function __construct($recordsPerPage = 10) {
        $this->recordsPerPage = $recordsPerPage;
        $this->initPage();
    }
    
    /**
     * 初始化分页参数
     * 从URL获取当前页码，并进行验证
     */
    private function initPage() {
        // 从URL参数获取当前页码，默认为第1页
        $this->currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        
        // 验证页码：必须大于等于1
        if ($this->currentPage < 1) {
            $this->currentPage = 1;
        }
        
        // 计算偏移量：这是分页的核心公式
        // 偏移量 = (当前页码 - 1) × 每页显示数
        $this->offset = ($this->currentPage - 1) * $this->recordsPerPage;
    }
    
    /**
     * 设置总记录数并计算总页数
     * @param int $total 总记录数
     */
    public function setTotalRecords($total) {
        $this->totalRecords = (int)$total;
        
        // 计算总页数：向上取整
        $this->totalPages = ceil($this->totalRecords / $this->recordsPerPage);
        
        // 确保当前页码不超过总页数
        if ($this->currentPage > $this->totalPages && $this->totalPages > 0) {
            $this->currentPage = $this->totalPages;
            $this->offset = ($this->currentPage - 1) * $this->recordsPerPage;
        }
    }
    
    /**
     * 获取分页查询的LIMIT子句
     * @return string 格式化的LIMIT子句
     */
    public function getLimitClause() {
        return "LIMIT {$this->recordsPerPage} OFFSET {$this->offset}";
    }
    
    /**
     * 获取偏移量
     * @return int 偏移量
     */
    public function getOffset() {
        return $this->offset;
    }
    
    /**
     * 获取每页显示数
     * @return int 每页显示数
     */
    public function getRecordsPerPage() {
        return $this->recordsPerPage;
    }
    
    /**
     * 获取当前页码
     * @return int 当前页码
     */
    public function getCurrentPage() {
        return $this->currentPage;
    }
    
    /**
     * 获取总页数
     * @return int 总页数
     */
    public function getTotalPages() {
        return $this->totalPages;
    }
    
    /**
     * 获取总记录数
     * @return int 总记录数
     */
    public function getTotalRecords() {
        return $this->totalRecords;
    }
    
    /**
     * 是否有上一页
     * @return bool
     */
    public function hasPrevious() {
        return $this->currentPage > 1;
    }
    
    /**
     * 是否有下一页
     * @return bool
     */
    public function hasNext() {
        return $this->currentPage < $this->totalPages;
    }
    
    /**
     * 获取上一页页码
     * @return int|null
     */
    public function getPreviousPage() {
        return $this->hasPrevious() ? $this->currentPage - 1 : null;
    }
    
    /**
     * 获取下一页页码
     * @return int|null
     */
    public function getNextPage() {
        return $this->hasNext() ? $this->currentPage + 1 : null;
    }
    
    /**
     * 生成简单的分页导航HTML
     * @return string 分页导航HTML
     */
    public function renderSimple() {
        // 如果没有数据或只有一页，不显示分页
        if ($this->totalPages <= 1) {
            return '<p>暂无分页</p>';
        }
        
        $html = '<div class="pagination">';
        $html .= "<p>第 {$this->currentPage} 页，共 {$this->totalPages} 页</p>";
        $html .= '<div class="pagination-links">';
        
        // 上一页链接
        if ($this->hasPrevious()) {
            $html .= "<a href=\"?page={$this->getPreviousPage()}\">上一页</a> ";
        }
        
        // 页码链接
        for ($i = 1; $i <= $this->totalPages; $i++) {
            if ($i == $this->currentPage) {
                $html .= "<strong>{$i}</strong> ";
            } else {
                $html .= "<a href=\"?page={$i}\">{$i}</a> ";
            }
        }
        
        // 下一页链接
        if ($this->hasNext()) {
            $html .= "<a href=\"?page={$this->getNextPage()}\">下一页</a>";
        }
        
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * 生成美观的分页导航HTML
     * @return string 分页导航HTML
     */
    public function renderBeautiful() {
        if ($this->totalPages <= 1) {
            return '';
        }
        
        $html = '<div class="pagination-beautiful">';
        $html .= '<div class="pagination-info">';
        $html .= "共 {$this->totalRecords} 条记录，第 {$this->currentPage} 页，共 {$this->totalPages} 页";
        $html .= '</div>';
        
        $html .= '<ul class="pagination-list">';
        
        // 上一页
        if ($this->hasPrevious()) {
            $html .= "<li><a href=\"?page={$this->getPreviousPage()}\">&laquo; 上一页</a></li>";
        } else {
            $html .= '<li class="disabled"><span>&laquo; 上一页</span></li>';
        }
        
        // 智能显示页码（最多显示7个）
        $maxVisible = 7;
        $start = max(1, $this->currentPage - floor($maxVisible / 2));
        $end = min($this->totalPages, $start + $maxVisible - 1);
        
        // 调整起始页
        if ($end - $start + 1 < $maxVisible) {
            $start = max(1, $end - $maxVisible + 1);
        }
        
        // 显示第一页和省略号
        if ($start > 1) {
            $html .= '<li><a href="?page=1">1</a></li>';
            if ($start > 2) {
                $html .= '<li class="ellipsis"><span>...</span></li>';
            }
        }
        
        // 显示页码
        for ($i = $start; $i <= $end; $i++) {
            if ($i == $this->currentPage) {
                $html .= "<li class=\"active\"><span>{$i}</span></li>";
            } else {
                $html .= "<li><a href=\"?page={$i}\">{$i}</a></li>";
            }
        }
        
        // 显示最后一页和省略号
        if ($end < $this->totalPages) {
            if ($end < $this->totalPages - 1) {
                $html .= '<li class="ellipsis"><span>...</span></li>';
            }
            $html .= "<li><a href=\"?page={$this->totalPages}\">{$this->totalPages}</a></li>";
        }
        
        // 下一页
        if ($this->hasNext()) {
            $html .= "<li><a href=\"?page={$this->getNextPage()}\">下一页 &raquo;</a></li>";
        } else {
            $html .= '<li class="disabled"><span>下一页 &raquo;</span></li>';
        }
        
        $html .= '</ul>';
        $html .= '</div>';
        
        return $html;
    }
}

// ==================== 使用示例 ====================

/**
 * 示例1：基本使用
 */
function example1() {
    echo "<h2>示例1：基本使用</h2>";
    
    // 模拟数据
    $totalRecords = 100;  // 总记录数
    $recordsPerPage = 10; // 每页显示10条
    
    // 创建分页对象
    $pagination = new SimplePagination($recordsPerPage);
    
    // 设置总记录数
    $pagination->setTotalRecords($totalRecords);
    
    // 显示分页信息
    echo "<p>总记录数：{$pagination->getTotalRecords()}</p>";
    echo "<p>每页显示：{$pagination->getRecordsPerPage()}</p>";
    echo "<p>当前页码：{$pagination->getCurrentPage()}</p>";
    echo "<p>总页数：{$pagination->getTotalPages()}</p>";
    echo "<p>偏移量：{$pagination->getOffset()}</p>";
    echo "<p>LIMIT子句：{$pagination->getLimitClause()}</p>";
    
    // 显示简单分页导航
    echo $pagination->renderSimple();
}

/**
 * 示例2：模拟数据库查询
 */
function example2() {
    echo "<h2>示例2：模拟数据库查询</h2>";
    
    // 模拟数据库数据
    $allData = [];
    for ($i = 1; $i <= 50; $i++) {
        $allData[] = [
            'id' => $i,
            'name' => "用户{$i}",
            'email' => "user{$i}@example.com"
        ];
    }
    
    // 创建分页对象
    $pagination = new SimplePagination(8); // 每页显示8条
    $pagination->setTotalRecords(count($allData));
    
    // 模拟分页查询
    $offset = $pagination->getOffset();
    $limit = $pagination->getRecordsPerPage();
    $currentPageData = array_slice($allData, $offset, $limit);
    
    // 显示当前页数据
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>ID</th><th>姓名</th><th>邮箱</th></tr>";
    
    foreach ($currentPageData as $item) {
        echo "<tr>";
        echo "<td>{$item['id']}</td>";
        echo "<td>{$item['name']}</td>";
        echo "<td>{$item['email']}</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
    // 显示美观的分页导航
    echo $pagination->renderBeautiful();
}

/**
 * 示例3：实际数据库查询（需要数据库连接）
 */
function example3() {
    echo "<h2>示例3：实际数据库查询</h2>";
    echo "<p style='color: red;'>注意：这个示例需要配置数据库连接</p>";
    
    /*
    // 数据库连接配置
    $host = 'localhost';
    $dbname = 'test_db';
    $username = 'root';
    $password = '';
    
    try {
        // 连接数据库
        $pdo = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // 创建分页对象
        $pagination = new SimplePagination(5);
        
        // 获取总记录数
        $stmt = $pdo->query("SELECT COUNT(*) FROM users");
        $totalRecords = $stmt->fetchColumn();
        $pagination->setTotalRecords($totalRecords);
        
        // 执行分页查询
        $sql = "SELECT * FROM users ORDER BY id " . $pagination->getLimitClause();
        $stmt = $pdo->query($sql);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // 显示数据
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>用户名</th><th>邮箱</th></tr>";
        
        foreach ($users as $user) {
            echo "<tr>";
            echo "<td>{$user['id']}</td>";
            echo "<td>{$user['username']}</td>";
            echo "<td>{$user['email']}</td>";
            echo "</tr>";
        }
        
        echo "</table>";
        
        // 显示分页导航
        echo $pagination->renderBeautiful();
        
    } catch (PDOException $e) {
        echo "数据库错误：" . $e->getMessage();
    }
    */
}

// ==================== 页面显示 ====================

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP分页教学演示</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        
        .container {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        h1 {
            color: #333;
            text-align: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        
        h2 {
            color: #007bff;
            border-left: 4px solid #007bff;
            padding-left: 15px;
        }
        
        .pagination {
            margin: 20px 0;
            text-align: center;
        }
        
        .pagination-links a {
            display: inline-block;
            padding: 8px 12px;
            margin: 0 2px;
            text-decoration: none;
            border: 1px solid #ddd;
            border-radius: 4px;
            color: #333;
        }
        
        .pagination-links a:hover {
            background-color: #f0f0f0;
        }
        
        .pagination-links strong {
            display: inline-block;
            padding: 8px 12px;
            margin: 0 2px;
            background-color: #007bff;
            color: white;
            border-radius: 4px;
        }
        
        .pagination-beautiful {
            margin: 20px 0;
        }
        
        .pagination-info {
            text-align: center;
            margin-bottom: 15px;
            color: #666;
            font-size: 14px;
        }
        
        .pagination-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 5px;
        }
        
        .pagination-list li {
            margin: 0;
        }
        
        .pagination-list a,
        .pagination-list span {
            display: block;
            padding: 8px 12px;
            text-decoration: none;
            border: 1px solid #ddd;
            border-radius: 4px;
            color: #333;
            transition: all 0.3s ease;
        }
        
        .pagination-list a:hover {
            background-color: #f0f0f0;
            border-color: #999;
        }
        
        .pagination-list .active span {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }
        
        .pagination-list .disabled span {
            color: #999;
            background-color: #f9f9f9;
            cursor: not-allowed;
        }
        
        .pagination-list .ellipsis span {
            border: none;
            background: none;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        
        .code-block {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 4px;
            padding: 15px;
            margin: 15px 0;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            overflow-x: auto;
        }
        
        .highlight {
            background-color: #fff3cd;
            padding: 15px;
            border-radius: 4px;
            border-left: 4px solid #ffc107;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>PHP分页实现教学演示</h1>
        
        <div class="highlight">
            <h3>分页核心公式：</h3>
            <ul>
                <li><strong>总页数</strong> = ceil(总记录数 ÷ 每页显示数)</li>
                <li><strong>偏移量</strong> = (当前页码 - 1) × 每页显示数</li>
                <li><strong>SQL查询</strong> = SELECT ... LIMIT 每页显示数 OFFSET 偏移量</li>
            </ul>
        </div>
        
        <?php
        // 运行示例
        example1();
        example2();
        example3();
        ?>
        
        <div class="code-block">
            <h3>完整使用流程：</h3>
            <pre><code>// 1. 创建分页对象
$pagination = new SimplePagination(10);

// 2. 设置总记录数
$pagination->setTotalRecords($totalRecords);

// 3. 执行分页查询
$sql = "SELECT * FROM table " . $pagination->getLimitClause();
$data = $pdo->query($sql)->fetchAll();

// 4. 显示分页导航
echo $pagination->renderBeautiful();</code></pre>
        </div>
    </div>
</body>
</html> 