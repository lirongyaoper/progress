<?php
/**
 * Tree类学习示例 - 基本属性解析
 */

class tree {
    // 核心属性解析：
    
    /**
     * 存储树形数据的二维数组
     * 格式：array(
     *     1 => array('id'=>1, 'parentid'=>0, 'name'=>'根节点'),
     *     2 => array('id'=>2, 'parentid'=>1, 'name'=>'子节点1'),
     *     3 => array('id'=>3, 'parentid'=>1, 'name'=>'子节点2')
     * )
     */
    public $arr = array();

    /**
     * 树形显示的装饰符号
     * icon[0] = '│' - 垂直线，用于中间层级
     * icon[1] = '├' - 带分支的垂直线，用于有后续节点
     * icon[2] = '└' - 最后一个节点的符号
     */
    public $icon = array('│','├','└');
    
    /**
     * 空格符号，用于缩进
     */
    public $nbsp = "&nbsp;";
    
    /**
     * 存储生成的树形结构字符串
     */
    public $ret = '';
    public $str = '';

    /**
     * 缓存数组，优化查询性能
     * 避免重复计算同一节点的子级
     */
    private $_cache = array();
}

// 示例数据结构
$example_data = array(
    1 => array('id'=>1, 'parentid'=>0, 'name'=>'公司总部'),
    2 => array('id'=>2, 'parentid'=>0, 'name'=>'分公司A'),
    3 => array('id'=>3, 'parentid'=>1, 'name'=>'技术部'),
    4 => array('id'=>4, 'parentid'=>1, 'name'=>'市场部'),
    5 => array('id'=>5, 'parentid'=>3, 'name'=>'PHP开发组'),
    6 => array('id'=>6, 'parentid'=>3, 'name'=>'前端开发组')
);

echo "示例数据结构：\n";
print_r($example_data);
?>
