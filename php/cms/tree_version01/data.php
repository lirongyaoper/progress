<?php
/**
 * Tree类演示数据文件
 * 
 * 包含多种复杂度的测试数据，用于不同的演示场景
 */

// 基础演示数据
$data = array(
    1 => array('id'=>'1','parentid'=>0,'name'=>'根目录'),
    2 => array('id'=>'2','parentid'=>0,'name'=>'系统管理'),
    3 => array('id'=>'3','parentid'=>1,'name'=>'用户管理'),
    4 => array('id'=>'4','parentid'=>1,'name'=>'权限管理'),
    5 => array('id'=>'5','parentid'=>2,'name'=>'系统设置'),
    6 => array('id'=>'6','parentid'=>3,'name'=>'添加用户'),
    7 => array('id'=>'7','parentid'=>3,'name'=>'编辑用户'),
    8 => array('id'=>'8','parentid'=>4,'name'=>'角色管理'),
    9 => array('id'=>'9','parentid'=>4,'name'=>'菜单管理')
);

// 简化演示数据（用于core_recursion.php）
$simple_data = array(
    1 => array('id'=>'1','parentid'=>0,'name'=>'A'),
    2 => array('id'=>'2','parentid'=>1,'name'=>'B'),
    3 => array('id'=>'3','parentid'=>2,'name'=>'C')
);

// 复杂演示数据（用于高级演示）
$complex_data = array(
    1 => array('id'=>'1','parentid'=>0,'name'=>'公司架构'),
    2 => array('id'=>'2','parentid'=>1,'name'=>'技术部'),
    3 => array('id'=>'3','parentid'=>1,'name'=>'销售部'),
    4 => array('id'=>'4','parentid'=>1,'name'=>'人事部'),
    5 => array('id'=>'5','parentid'=>2,'name'=>'前端组'),
    6 => array('id'=>'6','parentid'=>2,'name'=>'后端组'),
    7 => array('id'=>'7','parentid'=>2,'name'=>'测试组'),
    8 => array('id'=>'8','parentid'=>3,'name'=>'直销组'),
    9 => array('id'=>'9','parentid'=>3,'name'=>'渠道组'),
    10 => array('id'=>'10','parentid'=>5,'name'=>'React团队'),
    11 => array('id'=>'11','parentid'=>5,'name'=>'Vue团队'),
    12 => array('id'=>'12','parentid'=>6,'name'=>'Java团队'),
    13 => array('id'=>'13','parentid'=>6,'name'=>'PHP团队'),
    14 => array('id'=>'14','parentid'=>6,'name'=>'Python团队')
);

// 数据结构说明
echo "<!-- \n";
echo "数据结构说明:\n";
echo "1. \$data - 基础演示数据，包含根目录、系统管理等节点\n";
echo "2. \$simple_data - 简化数据，只有A->B->C三级结构\n";
echo "3. \$complex_data - 复杂数据，模拟公司组织架构\n";
echo "\n";
echo "每个数组元素包含:\n";
echo "- id: 节点唯一标识\n";
echo "- parentid: 父节点ID，0表示根节点\n";
echo "- name: 节点显示名称\n";
echo "-->\n";

