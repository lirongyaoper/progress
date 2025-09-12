<?php
/**
 * PHP分页实现完整示例
 * 包含详细的分页逻辑和注释，适合教学使用
 * 
 * @author 教学示例
 * @version 1.0
 */

// 数据库连接配置
$host = 'localhost';
$dbname = 'test_db';
$username = 'root';
$password = '';

/**
 * 分页类 - 封装分页相关功能
 */
class Pagination {
    
    private $totalRecords;    // 总记录数
    private $recordsPerPage;  // 每页显示记录数
    private $currentPage;     // 当前页码
    private $totalPages;      // 总页数
    private $offset;          // 偏移量
    private $pdo;            // 数据库连接对象
    
    /**
     * 构造函数
     * @param PDO $pdo 数据库连接对象
     * @param int $recordsPerPage 每页显示记录数，默认10条
     */
    public function __construct($pdo, $recordsPerPage = 10) {
        $this->pdo = $pdo;
        $this->recordsPerPage = $recordsPerPage;
        $this->initPagination();
    }
    
    /**
     * 初始化分页参数
     */
    private function initPagination() {
        // 获取当前页码，默认为第1页
        $this->currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        
        // 验证页码的有效性
        if ($this->currentPage < 1) {
            $this->currentPage = 1;
        }
        
        // 计算偏移量
        $this->offset = ($this->currentPage - 1) * $this->recordsPerPage;
    }
    
    /**
     * 获取总记录数
     * @param string $table 表名
     * @param string $where 查询条件（可选）
     * @param array $params 查询参数（可选）
     * @return int 总记录数
     */
    public function getTotalRecords($table, $where = '', $params = []) {
        try {
            $sql = "SELECT COUNT(*) as total FROM {$table}";
            if (!empty($where)) {
                $sql .= " WHERE {$where}";
            }
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $this->totalRecords = (int)$result['total'];
            
            // 计算总页数
            $this->totalPages = ceil($this->totalRecords / $this->recordsPerPage);
            
            // 确保当前页码不超过总页数
            if ($this->currentPage > $this->totalPages && $this->totalPages > 0) {
                $this->currentPage = $this->totalPages;
                $this->offset = ($this->currentPage - 1) * $this->recordsPerPage;
            }
            
            return $this->totalRecords;
            
        } catch (PDOException $e) {
            die("获取总记录数失败: " . $e->getMessage());
        }
    }
    
    /**
     * 执行分页查询
     * @param string $sql 基础SQL查询语句
     * @param array $params 查询参数（可选）
     * @return array 当前页的数据
     */
    public function getPageData($sql, $params = []) {
        try {
            // 添加LIMIT和OFFSET到SQL语句
            $sql .= " LIMIT {$this->recordsPerPage} OFFSET {$this->offset}";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            die("分页查询失败: " . $e->getMessage());
        }
    }
    
    /**
     * 生成分页导航HTML
     * @param string $url 基础URL，不包含页码参数
     * @param int $maxLinks 最多显示多少个页码链接，默认5个
     * @return string 分页导航HTML
     */
    public function renderPagination($url = '', $maxLinks = 5) {
        // 如果没有数据，返回空字符串
        if ($this->totalPages <= 1) {
            return '';
        }
        
        // 处理URL，确保包含正确的分隔符
        if (empty($url)) {
            $url = $_SERVER['REQUEST_URI'];
            // 移除现有的page参数
            $url = preg_replace('/[?&]page=\d+/', '', $url);
            $url = rtrim($url, '?&');
            $separator = strpos($url, '?') !== false ? '&' : '?';
        } else {
            $separator = strpos($url, '?') !== false ? '&' : '?';
        }
        
        $html = '<div class="pagination">';
        
        // 显示总记录数和当前页信息
        $html .= '<div class="pagination-info">';
        $html .= "共 {$this->totalRecords} 条记录，第 {$this->currentPage} 页，共 {$this->totalPages} 页";
        $html .= '</div>';
        
        $html .= '<ul class="pagination-list">';
        
        // 上一页链接
        if ($this->currentPage > 1) {
            $prevPage = $this->currentPage - 1;
            $html .= "<li><a href=\"{$url}{$separator}page={$prevPage}\">&laquo; 上一页</a></li>";
        } else {
            $html .= '<li class="disabled"><span>&laquo; 上一页</span></li>';
        }
        
        // 计算显示的页码范围
        $startPage = max(1, $this->currentPage - floor($maxLinks / 2));
        $endPage = min($this->totalPages, $startPage + $maxLinks - 1);
        
        // 调整起始页，确保显示足够的页码
        if ($endPage - $startPage + 1 < $maxLinks) {
            $startPage = max(1, $endPage - $maxLinks + 1);
        }
        
        // 显示第一页和省略号
        if ($startPage > 1) {
            $html .= "<li><a href=\"{$url}{$separator}page=1\">1</a></li>";
            if ($startPage > 2) {
                $html .= '<li class="disabled"><span>...</span></li>';
            }
        }
        
        // 显示页码链接
        for ($i = $startPage; $i <= $endPage; $i++) {
            if ($i == $this->currentPage) {
                $html .= "<li class=\"active\"><span>{$i}</span></li>";
            } else {
                $html .= "<li><a href=\"{$url}{$separator}page={$i}\">{$i}</a></li>";
            }
        }
        
        // 显示最后一页和省略号
        if ($endPage < $this->totalPages) {
            if ($endPage < $this->totalPages - 1) {
                $html .= '<li class="disabled"><span>...</span></li>';
            }
            $html .= "<li><a href=\"{$url}{$separator}page={$this->totalPages}\">{$this->totalPages}</a></li>";
        }
        
        // 下一页链接
        if ($this->currentPage < $this->totalPages) {
            $nextPage = $this->currentPage + 1;
            $html .= "<li><a href=\"{$url}{$separator}page={$nextPage}\">下一页 &raquo;</a></li>";
        } else {
            $html .= '<li class="disabled"><span>下一页 &raquo;</span></li>';
        }
        
        $html .= '</ul>';
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * 获取分页信息数组
     * @return array 包含分页信息的数组
     */
    public function getPaginationInfo() {
        return [
            'totalRecords' => $this->totalRecords,
            'recordsPerPage' => $this->recordsPerPage,
            'currentPage' => $this->currentPage,
            'totalPages' => $this->totalPages,
            'offset' => $this->offset,
            'hasPrevious' => $this->currentPage > 1,
            'hasNext' => $this->currentPage < $this->totalPages,
            'previousPage' => $this->currentPage > 1 ? $this->currentPage - 1 : null,
            'nextPage' => $this->currentPage < $this->totalPages ? $this->currentPage + 1 : null
        ];
    }
}

/**
 * 数据库连接函数
 * @return PDO 数据库连接对象
 */
function getDatabaseConnection() {
    global $host, $dbname, $username, $password;
    
    try {
        $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
        $pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]);
        return $pdo;
    } catch (PDOException $e) {
        die("数据库连接失败: " . $e->getMessage());
    }
}

/**
 * 创建示例数据表（如果不存在）
 * @param PDO $pdo 数据库连接对象
 */
function createSampleTable($pdo) {
    try {
        // 创建用户表
        $sql = "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL,
            email VARCHAR(100) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $pdo->exec($sql);
        
        // 检查是否需要插入示例数据
        $stmt = $pdo->query("SELECT COUNT(*) FROM users");
        $count = $stmt->fetchColumn();
        
        if ($count == 0) {
            // 插入示例数据
            $sql = "INSERT INTO users (username, email) VALUES ";
            $values = [];
            for ($i = 1; $i <= 100; $i++) {
                $values[] = "('user{$i}', 'user{$i}@example.com')";
            }
            $sql .= implode(', ', $values);
            $pdo->exec($sql);
            echo "<p style='color: green;'>已创建示例数据（100条记录）</p>";
        }
        
    } catch (PDOException $e) {
        die("创建示例表失败: " . $e->getMessage());
    }
}

// 主程序开始
try {
    // 获取数据库连接
    $pdo = getDatabaseConnection();
    
    // 创建示例数据
    createSampleTable($pdo);
    
    // 创建分页对象，每页显示10条记录
    $pagination = new Pagination($pdo, 10);
    
    // 获取总记录数
    $totalRecords = $pagination->getTotalRecords('users');
    
    // 执行分页查询
    $sql = "SELECT id, username, email, created_at FROM users ORDER BY id DESC";
    $users = $pagination->getPageData($sql);
    
    // 获取分页信息
    $paginationInfo = $pagination->getPaginationInfo();
    
} catch (Exception $e) {
    die("程序执行失败: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP分页实现示例</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        
        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .pagination-info {
            text-align: center;
            margin-bottom: 20px;
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
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        
        tr:hover {
            background-color: #f5f5f5;
        }
        
        .code-section {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 4px;
            padding: 15px;
            margin: 20px 0;
        }
        
        .code-section h3 {
            margin-top: 0;
            color: #495057;
        }
        
        .highlight {
            background-color: #fff3cd;
            padding: 10px;
            border-radius: 4px;
            border-left: 4px solid #ffc107;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>PHP分页实现完整示例</h1>
        
        <div class="highlight">
            <strong>分页信息：</strong><br>
            总记录数：<?php echo $paginationInfo['totalRecords']; ?> | 
            每页显示：<?php echo $paginationInfo['recordsPerPage']; ?> | 
            当前页：<?php echo $paginationInfo['currentPage']; ?> | 
            总页数：<?php echo $paginationInfo['totalPages']; ?>
        </div>
        
        <!-- 用户数据表格 -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>用户名</th>
                    <th>邮箱</th>
                    <th>创建时间</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="4" style="text-align: center; color: #999;">暂无数据</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['id']); ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        
        <!-- 分页导航 -->
        <?php echo $pagination->renderPagination(); ?>
        
        <!-- 代码说明 -->
        <div class="code-section">
            <h3>分页实现要点说明：</h3>
            <ol>
                <li><strong>参数获取</strong>：从URL的page参数获取当前页码，默认为第1页</li>
                <li><strong>数据验证</strong>：确保页码为正整数，不超过总页数</li>
                <li><strong>偏移量计算</strong>：offset = (当前页码 - 1) × 每页数量</li>
                <li><strong>SQL查询</strong>：使用COUNT(*)获取总数，LIMIT OFFSET进行分页</li>
                <li><strong>导航生成</strong>：智能显示页码链接，处理边界情况</li>
                <li><strong>安全性</strong>：使用PDO预处理语句防止SQL注入</li>
            </ol>
        </div>
        
        <div class="code-section">
            <h3>使用方法：</h3>
            <pre><code>// 1. 创建分页对象
$pagination = new Pagination($pdo, 10);

// 2. 获取总记录数
$total = $pagination->getTotalRecords('users');

// 3. 执行分页查询
$data = $pagination->getPageData("SELECT * FROM users ORDER BY id");

// 4. 显示分页导航
echo $pagination->renderPagination();</code></pre>
        </div>
    </div>
</body>
</html> 