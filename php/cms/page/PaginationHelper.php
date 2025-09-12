<?php
/**
 * 实用分页工具类
 * 提供多种分页方法和配置选项，适合实际项目使用
 * 
 * @author 实用工具类
 * @version 2.0
 */

class PaginationHelper {
    
    // 分页配置
    private $config = [
        'recordsPerPage' => 10,      // 每页显示记录数
        'maxLinks' => 7,             // 最多显示页码链接数
        'urlParam' => 'page',        // URL页码参数名
        'showInfo' => true,          // 是否显示分页信息
        'showFirstLast' => true,     // 是否显示首页末页
        'showPrevNext' => true,      // 是否显示上一页下一页
        'ellipsis' => '...',         // 省略号显示
        'cssClass' => 'pagination',  // CSS类名
        'activeClass' => 'active',   // 当前页CSS类名
        'disabledClass' => 'disabled' // 禁用状态CSS类名
    ];
    
    // 分页数据
    private $totalRecords = 0;       // 总记录数
    private $currentPage = 1;        // 当前页码
    private $totalPages = 0;         // 总页数
    private $offset = 0;             // 偏移量
    
    /**
     * 构造函数
     * @param array $config 配置数组
     */
    public function __construct($config = []) {
        $this->config = array_merge($this->config, $config);
        $this->initPagination();
    }
    
    /**
     * 初始化分页参数
     */
    private function initPagination() {
        // 获取当前页码
        $paramName = $this->config['urlParam'];
        $this->currentPage = isset($_GET[$paramName]) ? (int)$_GET[$paramName] : 1;
        
        // 验证页码
        if ($this->currentPage < 1) {
            $this->currentPage = 1;
        }
        
        // 计算偏移量
        $this->offset = ($this->currentPage - 1) * $this->config['recordsPerPage'];
    }
    
    /**
     * 设置总记录数
     * @param int $total 总记录数
     * @return $this 支持链式调用
     */
    public function setTotalRecords($total) {
        $this->totalRecords = (int)$total;
        $this->totalPages = ceil($this->totalRecords / $this->config['recordsPerPage']);
        
        // 调整当前页码
        if ($this->currentPage > $this->totalPages && $this->totalPages > 0) {
            $this->currentPage = $this->totalPages;
            $this->offset = ($this->currentPage - 1) * $this->config['recordsPerPage'];
        }
        
        return $this;
    }
    
    /**
     * 获取分页信息数组
     * @return array 分页信息
     */
    public function getInfo() {
        return [
            'totalRecords' => $this->totalRecords,
            'recordsPerPage' => $this->config['recordsPerPage'],
            'currentPage' => $this->currentPage,
            'totalPages' => $this->totalPages,
            'offset' => $this->offset,
            'hasPrevious' => $this->currentPage > 1,
            'hasNext' => $this->currentPage < $this->totalPages,
            'previousPage' => $this->currentPage > 1 ? $this->currentPage - 1 : null,
            'nextPage' => $this->currentPage < $this->totalPages ? $this->currentPage + 1 : null,
            'startRecord' => $this->totalRecords > 0 ? $this->offset + 1 : 0,
            'endRecord' => min($this->offset + $this->config['recordsPerPage'], $this->totalRecords)
        ];
    }
    
    /**
     * 获取SQL LIMIT子句
     * @return string LIMIT子句
     */
    public function getLimitClause() {
        return "LIMIT {$this->config['recordsPerPage']} OFFSET {$this->offset}";
    }
    
    /**
     * 获取偏移量
     * @return int 偏移量
     */
    public function getOffset() {
        return $this->offset;
    }
    
    /**
     * 获取每页记录数
     * @return int 每页记录数
     */
    public function getRecordsPerPage() {
        return $this->config['recordsPerPage'];
    }
    
    /**
     * 生成URL
     * @param int $page 页码
     * @param string $baseUrl 基础URL
     * @return string 完整URL
     */
    private function buildUrl($page, $baseUrl = '') {
        if (empty($baseUrl)) {
            $baseUrl = $_SERVER['REQUEST_URI'];
        }
        
        $paramName = $this->config['urlParam'];
        
        // 移除现有的页码参数
        $baseUrl = preg_replace("/[?&]{$paramName}=\d+/", '', $baseUrl);
        $baseUrl = rtrim($baseUrl, '?&');
        
        // 添加页码参数
        $separator = strpos($baseUrl, '?') !== false ? '&' : '?';
        return $baseUrl . $separator . "{$paramName}={$page}";
    }
    
    /**
     * 生成Bootstrap风格的分页导航
     * @param string $baseUrl 基础URL
     * @return string HTML代码
     */
    public function renderBootstrap($baseUrl = '') {
        if ($this->totalPages <= 1) {
            return '';
        }
        
        $info = $this->getInfo();
        $html = "<nav aria-label=\"分页导航\">";
        
        // 分页信息
        if ($this->config['showInfo']) {
            $html .= "<div class=\"text-center mb-2\">";
            $html .= "显示第 {$info['startRecord']} - {$info['endRecord']} 条，共 {$this->totalRecords} 条记录";
            $html .= "</div>";
        }
        
        $html .= "<ul class=\"{$this->config['cssClass']} justify-content-center\">";
        
        // 首页
        if ($this->config['showFirstLast'] && $info['hasPrevious']) {
            $html .= "<li class=\"page-item\">";
            $html .= "<a class=\"page-link\" href=\"" . $this->buildUrl(1, $baseUrl) . "\">首页</a>";
            $html .= "</li>";
        }
        
        // 上一页
        if ($this->config['showPrevNext']) {
            if ($info['hasPrevious']) {
                $html .= "<li class=\"page-item\">";
                $html .= "<a class=\"page-link\" href=\"" . $this->buildUrl($info['previousPage'], $baseUrl) . "\">上一页</a>";
                $html .= "</li>";
            } else {
                $html .= "<li class=\"page-item {$this->config['disabledClass']}\">";
                $html .= "<span class=\"page-link\">上一页</span>";
                $html .= "</li>";
            }
        }
        
        // 页码链接
        $this->renderPageNumbers($html, $baseUrl);
        
        // 下一页
        if ($this->config['showPrevNext']) {
            if ($info['hasNext']) {
                $html .= "<li class=\"page-item\">";
                $html .= "<a class=\"page-link\" href=\"" . $this->buildUrl($info['nextPage'], $baseUrl) . "\">下一页</a>";
                $html .= "</li>";
            } else {
                $html .= "<li class=\"page-item {$this->config['disabledClass']}\">";
                $html .= "<span class=\"page-link\">下一页</span>";
                $html .= "</li>";
            }
        }
        
        // 末页
        if ($this->config['showFirstLast'] && $info['hasNext']) {
            $html .= "<li class=\"page-item\">";
            $html .= "<a class=\"page-link\" href=\"" . $this->buildUrl($this->totalPages, $baseUrl) . "\">末页</a>";
            $html .= "</li>";
        }
        
        $html .= "</ul>";
        $html .= "</nav>";
        
        return $html;
    }
    
    /**
     * 生成简洁风格的分页导航
     * @param string $baseUrl 基础URL
     * @return string HTML代码
     */
    public function renderSimple($baseUrl = '') {
        if ($this->totalPages <= 1) {
            return '';
        }
        
        $info = $this->getInfo();
        $html = "<div class=\"{$this->config['cssClass']}\">";
        
        // 分页信息
        if ($this->config['showInfo']) {
            $html .= "<div class=\"pagination-info\">";
            $html .= "第 {$this->currentPage} 页，共 {$this->totalPages} 页";
            $html .= "</div>";
        }
        
        $html .= "<div class=\"pagination-links\">";
        
        // 上一页
        if ($info['hasPrevious']) {
            $html .= "<a href=\"" . $this->buildUrl($info['previousPage'], $baseUrl) . "\">上一页</a> ";
        }
        
        // 页码
        for ($i = 1; $i <= $this->totalPages; $i++) {
            if ($i == $this->currentPage) {
                $html .= "<strong>{$i}</strong> ";
            } else {
                $html .= "<a href=\"" . $this->buildUrl($i, $baseUrl) . "\">{$i}</a> ";
            }
        }
        
        // 下一页
        if ($info['hasNext']) {
            $html .= "<a href=\"" . $this->buildUrl($info['nextPage'], $baseUrl) . "\">下一页</a>";
        }
        
        $html .= "</div>";
        $html .= "</div>";
        
        return $html;
    }
    
    /**
     * 生成智能分页导航（带省略号）
     * @param string $baseUrl 基础URL
     * @return string HTML代码
     */
    public function renderSmart($baseUrl = '') {
        if ($this->totalPages <= 1) {
            return '';
        }
        
        $info = $this->getInfo();
        $maxLinks = $this->config['maxLinks'];
        
        $html = "<div class=\"{$this->config['cssClass']}\">";
        
        // 分页信息
        if ($this->config['showInfo']) {
            $html .= "<div class=\"pagination-info\">";
            $html .= "共 {$this->totalRecords} 条记录，第 {$this->currentPage} 页，共 {$this->totalPages} 页";
            $html .= "</div>";
        }
        
        $html .= "<ul class=\"pagination-list\">";
        
        // 上一页
        if ($this->config['showPrevNext']) {
            if ($info['hasPrevious']) {
                $html .= "<li><a href=\"" . $this->buildUrl($info['previousPage'], $baseUrl) . "\">&laquo; 上一页</a></li>";
            } else {
                $html .= "<li class=\"{$this->config['disabledClass']}\"><span>&laquo; 上一页</span></li>";
            }
        }
        
        // 计算显示的页码范围
        $start = max(1, $this->currentPage - floor($maxLinks / 2));
        $end = min($this->totalPages, $start + $maxLinks - 1);
        
        // 调整起始页
        if ($end - $start + 1 < $maxLinks) {
            $start = max(1, $end - $maxLinks + 1);
        }
        
        // 显示第一页和省略号
        if ($start > 1) {
            $html .= "<li><a href=\"" . $this->buildUrl(1, $baseUrl) . "\">1</a></li>";
            if ($start > 2) {
                $html .= "<li class=\"ellipsis\"><span>{$this->config['ellipsis']}</span></li>";
            }
        }
        
        // 显示页码
        for ($i = $start; $i <= $end; $i++) {
            if ($i == $this->currentPage) {
                $html .= "<li class=\"{$this->config['activeClass']}\"><span>{$i}</span></li>";
            } else {
                $html .= "<li><a href=\"" . $this->buildUrl($i, $baseUrl) . "\">{$i}</a></li>";
            }
        }
        
        // 显示最后一页和省略号
        if ($end < $this->totalPages) {
            if ($end < $this->totalPages - 1) {
                $html .= "<li class=\"ellipsis\"><span>{$this->config['ellipsis']}</span></li>";
            }
            $html .= "<li><a href=\"" . $this->buildUrl($this->totalPages, $baseUrl) . "\">{$this->totalPages}</a></li>";
        }
        
        // 下一页
        if ($this->config['showPrevNext']) {
            if ($info['hasNext']) {
                $html .= "<li><a href=\"" . $this->buildUrl($info['nextPage'], $baseUrl) . "\">下一页 &raquo;</a></li>";
            } else {
                $html .= "<li class=\"{$this->config['disabledClass']}\"><span>下一页 &raquo;</span></li>";
            }
        }
        
        $html .= "</ul>";
        $html .= "</div>";
        
        return $html;
    }
    
    /**
     * 渲染页码链接（私有方法）
     * @param string &$html HTML字符串引用
     * @param string $baseUrl 基础URL
     */
    private function renderPageNumbers(&$html, $baseUrl) {
        $maxLinks = $this->config['maxLinks'];
        
        // 计算显示的页码范围
        $start = max(1, $this->currentPage - floor($maxLinks / 2));
        $end = min($this->totalPages, $start + $maxLinks - 1);
        
        // 调整起始页
        if ($end - $start + 1 < $maxLinks) {
            $start = max(1, $end - $maxLinks + 1);
        }
        
        // 显示第一页和省略号
        if ($start > 1) {
            $html .= "<li class=\"page-item\">";
            $html .= "<a class=\"page-link\" href=\"" . $this->buildUrl(1, $baseUrl) . "\">1</a>";
            $html .= "</li>";
            
            if ($start > 2) {
                $html .= "<li class=\"page-item disabled\">";
                $html .= "<span class=\"page-link\">{$this->config['ellipsis']}</span>";
                $html .= "</li>";
            }
        }
        
        // 显示页码
        for ($i = $start; $i <= $end; $i++) {
            if ($i == $this->currentPage) {
                $html .= "<li class=\"page-item {$this->config['activeClass']}\">";
                $html .= "<span class=\"page-link\">{$i}</span>";
                $html .= "</li>";
            } else {
                $html .= "<li class=\"page-item\">";
                $html .= "<a class=\"page-link\" href=\"" . $this->buildUrl($i, $baseUrl) . "\">{$i}</a>";
                $html .= "</li>";
            }
        }
        
        // 显示最后一页和省略号
        if ($end < $this->totalPages) {
            if ($end < $this->totalPages - 1) {
                $html .= "<li class=\"page-item disabled\">";
                $html .= "<span class=\"page-link\">{$this->config['ellipsis']}</span>";
                $html .= "</li>";
            }
            
            $html .= "<li class=\"page-item\">";
            $html .= "<a class=\"page-link\" href=\"" . $this->buildUrl($this->totalPages, $baseUrl) . "\">{$this->totalPages}</a>";
            $html .= "</li>";
        }
    }
    
    /**
     * 生成JSON格式的分页数据
     * @return array JSON数据
     */
    public function toJson() {
        $info = $this->getInfo();
        
        return [
            'pagination' => $info,
            'config' => $this->config,
            'links' => [
                'first' => $this->buildUrl(1),
                'last' => $this->buildUrl($this->totalPages),
                'previous' => $info['hasPrevious'] ? $this->buildUrl($info['previousPage']) : null,
                'next' => $info['hasNext'] ? $this->buildUrl($info['nextPage']) : null,
                'current' => $this->buildUrl($this->currentPage)
            ]
        ];
    }
    
    /**
     * 静态方法：快速创建分页对象
     * @param int $totalRecords 总记录数
     * @param int $recordsPerPage 每页记录数
     * @param array $config 配置
     * @return PaginationHelper
     */
    public static function create($totalRecords, $recordsPerPage = 10, $config = []) {
        $config['recordsPerPage'] = $recordsPerPage;
        $pagination = new self($config);
        $pagination->setTotalRecords($totalRecords);
        return $pagination;
    }
}

// ==================== 使用示例 ====================

/**
 * 示例：使用PaginationHelper类
 */
function demonstratePaginationHelper() {
    echo "<h2>PaginationHelper 使用示例</h2>";
    
    // 模拟数据
    $totalRecords = 150;
    
    // 创建分页对象
    $pagination = PaginationHelper::create($totalRecords, 15, [
        'maxLinks' => 5,
        'showInfo' => true,
        'showFirstLast' => true
    ]);
    
    // 显示分页信息
    $info = $pagination->getInfo();
    echo "<div class='info-box'>";
    echo "<h3>分页信息：</h3>";
    echo "<p>总记录数：{$info['totalRecords']}</p>";
    echo "<p>每页显示：{$info['recordsPerPage']}</p>";
    echo "<p>当前页码：{$info['currentPage']}</p>";
    echo "<p>总页数：{$info['totalPages']}</p>";
    echo "<p>偏移量：{$info['offset']}</p>";
    echo "<p>显示记录：{$info['startRecord']} - {$info['endRecord']}</p>";
    echo "</div>";
    
    // 显示不同风格的分页导航
    echo "<h3>Bootstrap风格：</h3>";
    echo $pagination->renderBootstrap();
    
    echo "<h3>简洁风格：</h3>";
    echo $pagination->renderSimple();
    
    echo "<h3>智能风格：</h3>";
    echo $pagination->renderSmart();
    
    // 显示JSON数据
    echo "<h3>JSON数据：</h3>";
    echo "<pre>" . json_encode($pagination->toJson(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";
}

// 如果直接访问此文件，显示示例
if (basename(__FILE__) == basename($_SERVER['SCRIPT_NAME'])) {
    demonstratePaginationHelper();
}
?> 