<?php

// 分页的核心思想：
// 	1. 计算总页数 = ceil($total /每页显示数)
// 	2. 计算偏移量 = （当前页 - 1 ） * 每页显示数
// 	3. 使用SQL的LIMIT 和 OFFSET 进行分页查询


class LryPagination{
	private $totalRecords = 0; //总记录数
	private $recordsPerPage = 10; //每页显示记录数目
	private $totalPages = 0;
	private $currentPage = 1 ;
	private $offset = 0;


	public function __construct($recordsPerPage = 10){
		$this -> recordsPerPage = $recordsPerPage;
		$this -> initPage();
	}

	//初始化分页参数，从URL获取当前页码，并进行验证
	private function initPage(){
		$this -> currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
		if($this->currentPage < 1){
			$this-> currentPage = 1;
		}
        // 计算偏移量：这是分页的核心公式
        // 偏移量 = (当前页码 - 1) × 每页显示数
		$this-> offset = ($this -> currentPage - 1) * $this ->recordsPerPage;
	}

    /**
     * 设置总记录数并计算总页数
     * @param int $total 总记录数
     */
     public function setTotalRecords($tatal){
     	$this->totalRecords = (int)$total;
     	// 计算总页数：向上取整
     	$this -> totalPages = ceil($this->totalRecords / $this-> recordsPerPage);
     	     // 确保当前页码不超过总页数
     	if($this->currentPage > $this->totalPages && $this->totalPages >0){
     		$this -> currentPage = $this -> totalPages;
     		//因为 $this-> currentPage 变化，所以需要重新设置  $this->offset 的值
     		$this->offset= ($this ->currentPage - 1) * $this -> recordsPerPage;
     	}
     }	
    /**
     * 获取分页查询的LIMIT子句
     * @return string 格式化的LIMIT子句
     */
    public function getLimitClause(){
    	return "LIMIT {$this->recordsPerPage} OFFSET {$this -> offset}";
    }









}