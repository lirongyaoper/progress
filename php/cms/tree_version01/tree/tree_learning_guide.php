<?php
/**
 * Tree类学习指南 - 为PHP初学者设计
 * 包含详细的代码注释和分步教学
 */

echo "===== Tree类学习指南 =====\n\n";

// ===============================
// 第一部分：理解树形数据结构
// ===============================
echo "第一部分：理解树形数据结构\n";
echo "--------------------------------\n";

echo "什么是树形结构？\n";
echo "树形结构是一种层次化的数据组织方式，每个节点可以有多个子节点。\n\n";

echo "基本概念：\n";
echo "- 节点(Node)：数据的基本单位\n";
echo "- 父节点(Parent)：上级节点\n";
echo "- 子节点(Child)：下级节点\n";
echo "- 根节点(Root)：最顶层节点，parentid=0\n";
echo "- 叶子节点(Leaf)：没有子节点的节点\n\n";

// 示例数据结构
$data = array(
    1 => array('id'=>1, 'parentid'=>0, 'name'=>'网站首页'),      // 根节点
    2 => array('id'=>2, 'parentid'=>0, 'name'=>'新闻中心'),      // 根节点
    3 => array('id'=>3, 'parentid'=>2, 'name'=>'国内新闻'),      // 新闻中心的子节点
    4 => array('id'=>4, 'parentid'=>2, 'name'=>'国际新闻'),      // 新闻中心的子节点
    5 => array('id'=>5, 'parentid'=>3, 'name'=>'政治新闻'),      // 国内新闻的子节点
);

echo "示例数据结构：\n";
foreach($data as $item) {
    $level = '';
    if($item['parentid'] == 0) $level = '[根节点]';
    elseif(isset($data[$item['parentid']]) && $data[$item['parentid']]['parentid'] == 0) $level = '[二级节点]';
    else $level = '[三级节点]';
    
    echo "ID:{$item['id']} | 父ID:{$item['parentid']} | 名称:{$item['name']} {$level}\n";
}

echo "\n树形关系图：\n";
echo "网站首页 (ID:1)\n";
echo "新闻中心 (ID:2)\n";
echo "├─ 国内新闻 (ID:3)\n";
echo "│  └─ 政治新闻 (ID:5)\n";
echo "└─ 国际新闻 (ID:4)\n\n";

// ===============================
// 第二部分：Tree类的核心属性
// ===============================
echo "第二部分：Tree类的核心属性\n";
echo "--------------------------------\n";

echo "class tree {\n";
echo "    public \$arr = array();           // 存储所有节点数据\n";
echo "    public \$icon = array('│','├','└'); // 树形显示符号\n";
echo "    public \$nbsp = '&nbsp;';         // 空格符号\n";
echo "    public \$ret = '';               // 存储生成的结果\n";
echo "    private \$_cache = array();      // 缓存查询结果\n";
echo "}\n\n";

echo "属性说明：\n";
echo "- \$arr：核心数据存储，二维数组格式\n";
echo "- \$icon：树形显示符号，用于美化输出\n";
echo "  - │ 垂直线，连接上下节点\n";
echo "  - ├ 分支线，表示还有后续节点\n";
echo "  - └ 末端线，表示最后一个节点\n";
echo "- \$ret：存储get_tree方法的输出结果\n";
echo "- \$_cache：缓存机制，提升查询性能\n\n";

// ===============================
// 第三部分：核心方法分析
// ===============================
echo "第三部分：核心方法分析\n";
echo "--------------------------------\n";

echo "1. init() - 初始化方法\n";
echo "   作用：设置数据并清空缓存\n";
echo "   用法：\$tree->init(\$data_array);\n\n";

echo "2. get_child() - 获取子节点\n";
echo "   作用：查找指定节点的所有直接子节点\n";
echo "   原理：遍历数组，找出parentid等于指定ID的节点\n";
echo "   用法：\$children = \$tree->get_child(2); // 获取ID为2的子节点\n\n";

// 模拟get_child的工作过程
echo "get_child(2)的工作过程演示：\n";
echo "查找parentid=2的节点：\n";
foreach($data as $id => $item) {
    if($item['parentid'] == 2) {
        echo "  找到：ID:{$item['id']}, 名称:{$item['name']}\n";
    }
}

echo "\n3. get_tree() - 生成树形结构\n";
echo "   作用：递归生成可视化的树形结构\n";
echo "   原理：\n";
echo "   - 获取子节点\n";
echo "   - 为每个子节点添加缩进和连接符\n";
echo "   - 递归处理每个子节点的子级\n";
echo "   - 使用模板生成最终输出\n\n";

// ===============================
// 第四部分：递归算法理解
// ===============================
echo "第四部分：递归算法理解\n";
echo "--------------------------------\n";

echo "递归是Tree类的核心，让我们理解它的工作原理：\n\n";

echo "递归三要素：\n";
echo "1. 递归条件：有子节点就继续递归\n";
echo "2. 递归调用：get_tree调用自己处理子节点\n";
echo "3. 终止条件：没有子节点时停止\n\n";

echo "递归执行流程（以ID=0开始）：\n";
echo "1. get_tree(0) - 处理根节点\n";
echo "   找到子节点：[1,2]\n";
echo "   \n";
echo "2. 处理节点1（网站首页）\n";
echo "   没有子节点，结束\n";
echo "   \n";
echo "3. 处理节点2（新闻中心）\n";
echo "   找到子节点：[3,4]\n";
echo "   调用 get_tree(2)\n";
echo "   \n";
echo "4. get_tree(2) - 处理新闻中心的子节点\n";
echo "   处理节点3（国内新闻）\n";
echo "   找到子节点：[5]\n";
echo "   调用 get_tree(3)\n";
echo "   \n";
echo "5. get_tree(3) - 处理国内新闻的子节点\n";
echo "   处理节点5（政治新闻）\n";
echo "   没有子节点，结束\n\n";

// ===============================
// 第五部分：模板系统理解
// ===============================
echo "第五部分：模板系统理解\n";
echo "--------------------------------\n";

echo "Tree类使用模板系统生成不同格式的输出：\n\n";

echo "模板变量：\n";
echo "- \$id：节点ID\n";
echo "- \$name：节点名称\n";
echo "- \$spacer：缩进和连接符\n";
echo "- \$selected：选中状态\n\n";

echo "常用模板示例：\n";
echo "1. HTML下拉选项：\n";
echo "   模板：\"<option value='\\\$id' \\\$selected>\\\$spacer\\\$name</option>\"\n";
echo "   结果：<option value='1' selected>├ 新闻中心</option>\n\n";

echo "2. 纯文本树形：\n";
echo "   模板：\"\\\$spacer\\\$name\\n\"\n";
echo "   结果：├ 新闻中心\n\n";

echo "3. JSON数据：\n";
echo "   模板：\"{'id':\\\$id,'name':'\\\$name'}\"\n";
echo "   结果：{'id':1,'name':'新闻中心'}\n\n";

// ===============================
// 第六部分：性能优化分析
// ===============================
echo "第六部分：性能优化分析\n";
echo "--------------------------------\n";

echo "Tree类的性能优化策略：\n\n";

echo "1. 缓存机制：\n";
echo "   - 首次查询：遍历整个数组\n";
echo "   - 后续查询：直接从缓存获取\n";
echo "   - 适用场景：重复查询同一节点的子级\n\n";

echo "2. 安全优化：\n";
echo "   - 替换eval()：避免代码注入攻击\n";
echo "   - 模板解析：使用str_replace安全替换\n";
echo "   - 输入验证：检查数组格式和数据类型\n\n";

echo "3. 内存优化：\n";
echo "   - 引用传递：减少数组复制\n";
echo "   - 及时清理：清空不需要的临时变量\n";
echo "   - 合理缓存：只缓存频繁访问的数据\n\n";

// ===============================
// 第七部分：实际应用场景
// ===============================
echo "第七部分：实际应用场景\n";
echo "--------------------------------\n";

echo "Tree类的常见应用：\n\n";

echo "1. 网站栏目管理：\n";
echo "   - 后台栏目列表\n";
echo "   - 前台导航菜单\n";
echo "   - 面包屑导航\n\n";

echo "2. 商品分类：\n";
echo "   - 商品分类树\n";
echo "   - 筛选条件组织\n";
echo "   - 价格区间分级\n\n";

echo "3. 组织架构：\n";
echo "   - 公司部门结构\n";
echo "   - 权限级别管理\n";
echo "   - 报告关系展示\n\n";

echo "4. 地区管理：\n";
echo "   - 省市区三级联动\n";
echo "   - 配送范围设置\n";
echo "   - 区域代理管理\n\n";

// ===============================
// 第八部分：学习建议
// ===============================
echo "第八部分：学习建议\n";
echo "--------------------------------\n";

echo "对于PHP初学者的建议：\n\n";

echo "1. 理解基础概念：\n";
echo "   - 先理解什么是树形结构\n";
echo "   - 掌握父子关系的概念\n";
echo "   - 理解递归的基本原理\n\n";

echo "2. 动手实践：\n";
echo "   - 创建简单的树形数据\n";
echo "   - 手工模拟get_child的查找过程\n";
echo "   - 画出树形结构图\n\n";

echo "3. 逐步深入：\n";
echo "   - 先学会使用基本方法\n";
echo "   - 理解模板系统的工作原理\n";
echo "   - 学习性能优化技巧\n\n";

echo "4. 实际应用：\n";
echo "   - 做一个简单的栏目管理\n";
echo "   - 实现三级分类选择\n";
echo "   - 制作树形导航菜单\n\n";

echo "5. 进阶学习：\n";
echo "   - 学习数据库设计中的树形结构\n";
echo "   - 了解左右值编码等高级技术\n";
echo "   - 研究大数据量下的性能优化\n\n";

echo "===== 学习指南结束 =====\n";
echo "\n记住：学习编程最重要的是动手实践！\n";
echo "建议你创建自己的数据，尝试不同的模板，\n";
echo "理解每一行代码的作用，这样才能真正掌握Tree类。\n";

?>
