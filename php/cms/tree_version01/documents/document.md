要读懂 `tree.class.php` 文件，我们可以从「**类的设计目标**→**核心属性**→**方法逻辑**→**使用场景**」四个维度逐步拆解，结合代码细节理解其如何实现「层级数据处理与树形结构生成」的核心能力。


## 一、先明确：这个类的设计目标
`tree.class.php` 是一个**通用树形结构工具类**，核心目标是：  
接收「包含层级关系的二维数组」（如分类、菜单数据），通过封装好的方法实现「层级关系查询」（如找子节点、父节点、路径）和「树形UI生成」（如带缩进的下拉框、可折叠菜单），避免重复开发层级数据处理逻辑。

适用场景：后台分类管理、菜单生成、权限树展示等需要层级可视化的功能。


## 二、核心属性：理解类的“数据容器”
类的属性用于存储「原始数据」「样式符号」和「生成结果」，是方法间数据传递的基础：

| 属性名       | 类型       | 作用说明                                                                 |
|--------------|------------|--------------------------------------------------------------------------|
| `$arr`       | array      | 存储核心层级数据（二维数组），必须包含 `id`（节点唯一标识）、`parentid`（父节点ID），可额外带 `name`/`url` 等自定义字段。 |
| `$icon`      | array      | 树形结构的缩进修饰符号，默认 `['│','├','└']`，分别对应“竖线”“分支”“末尾分支”，用于可视化层级关系（可替换为图片路径）。 |
| `$nbsp`      | string     | 空格符号（默认 `&nbsp;`），辅助缩进，让层级结构更整齐。                 |
| `$ret`       | string     | 存储「基础树形结构」的生成结果（如 `get_tree`/`get_tree_multi` 方法的HTML字符串）。 |
| `$str`       | string     | 存储「复杂树形结构」的生成结果（如 `get_treeview` 方法的可折叠菜单HTML）。 |


## 三、方法逻辑：按“功能模块”拆解核心能力
类的方法可分为「**数据初始化**」「**层级关系查询**」「**树形UI生成**」「**辅助工具**」四大模块，每个模块的逻辑都围绕“层级数据”展开：


### 模块1：数据初始化（入口方法）
只有1个方法 `init`，是使用类的“第一步”，负责将外部数据注入类内部：
```php
public function init($arr=array()){
    $this->arr = $arr; // 将外部层级数组存入类属性
    $this->ret = '';   // 重置结果存储（避免多次调用方法时结果叠加）
    return is_array($arr); // 返回是否初始化成功（校验$arr是否为数组）
}
```
- 必须调用：所有其他方法（如 `get_child`/`get_tree`）都依赖 `$this->arr`，因此使用前必须先通过 `$tree->init($data)` 注入数据。
- 数据要求：传入的 `$arr` 需是二维数组，每个元素必须包含 `id` 和 `parentid`（否则后续方法会报错）。


### 模块2：层级关系查询（数据处理核心）
这组方法负责“解析 `$arr` 中的层级关系”，是树形生成的基础，共3个核心方法：

#### 1. `get_child($myid)`：获取指定节点的“直接子节点”
- 功能：找到 `parentid` 等于 `$myid` 的所有节点（仅下一级，不包含孙子节点）。
- 逻辑细节：
  ```php
  public function get_child($myid){
      $newarr = array();
      foreach($this->arr as $id => $a){ // 遍历所有节点
          if($a['parentid'] == $myid) { // 筛选父ID等于目标$myid的节点
              $newarr[$id] = $a;
          }
      }
      return $newarr ? $newarr : false; // 有结果返回数组，无结果返回false
  }
  ```
- 示例：若 `$myid=1`（顶级节点“前端开发”），会返回所有 `parentid=1` 的二级节点（如“HTML/CSS”“JavaScript”）。

#### 2. `get_parent($myid)`：获取指定节点的“父级同级节点”
- 功能：注意！不是“父节点本身”，而是“父节点的兄弟节点”（即祖父级的所有子节点）。
- 逻辑细节（需注意潜在设计缺陷，前文已提）：
  ```php
  public function get_parent($myid){
      $newarr = array();
      if(!isset($this->arr[$myid])) return false; // 先判断目标节点是否存在
      $pid = $this->arr[$myid]['parentid']; // 目标节点的父ID
      $pid = $this->arr[$pid]['parentid']; // 目标节点的祖父ID（关键：取两次parentid）
      foreach($this->arr as $id => $a){
          if($a['parentid'] == $pid) { // 筛选祖父ID下的所有子节点（父级的同级）
              $newarr[$id] = $a;
          }
      }
      return $newarr;
  }
  ```
- 示例：若 `$myid=7`（二级节点“HTML/CSS”，父ID=1），会先取祖父ID=0，再返回所有 `parentid=0` 的顶级节点（如“前端开发”“后端开发”）。

#### 3. `get_pos($myid, &$newarr)`：获取指定节点的“完整路径”
- 功能：递归找到从“顶级节点”到“当前节点”的所有祖先节点，生成路径（如“顶级→父级→当前节点”）。
- 逻辑细节（依赖引用传递 `&$newarr`）：
  ```php
  public function get_pos($myid,&$newarr){
      if(!isset($this->arr[$myid])) return false; // 目标节点不存在则返回
      $newarr[] = $this->arr[$myid]; // 先把当前节点加入路径
      $pid = $this->arr[$myid]['parentid']; // 获取父ID
      if(isset($this->arr[$pid])){ // 若父节点存在，递归调用（向上找祖先）
          $this->get_pos($pid,$newarr);
      }
      // 递归结束后，路径是“当前→父→顶级”，需反转并转为关联数组
      krsort($newarr); // 反转数组（变为“顶级→父→当前”）
      $a = array();
      foreach($newarr as $v){
          $a[$v['id']] = $v; // 用节点ID作为键，便于后续查询
      }
      return $a;
  }
  ```
- 示例：若 `$myid=25`（三级节点“Vue.js”，父ID=9，祖父ID=1），最终路径数组会包含“前端开发（1）→前端框架（9）→Vue.js（25）”。


### 模块3：树形UI生成（核心功能，面向前端）
这组方法是类的“最终产出”，将层级数据转为可直接渲染的HTML字符串，共4个核心方法，适配不同UI场景：

#### 1. `get_tree($myid, $str, $sid = 0, $adds = '', $str_group = '')`：基础树形（单选）
- 功能：生成带缩进符号的树形结构（如下拉框选项），支持单选选中状态。
- 核心参数：
  - `$myid`：起始节点ID（`0` 表示从顶级开始）；
  - `$str`：节点HTML模板（如 `<option value='$id' $selected>$spacer$name</option>`），其中 `$id`/`$name` 是 `$arr` 中的字段，`$spacer` 是自动生成的缩进符号；
  - `$sid`：选中节点ID（单选）。
- 逻辑核心：**递归遍历子节点**，拼接缩进符号和HTML模板：
  ```php
  public function get_tree($myid, $str, $sid = 0, $adds = '', $str_group = ''){
      $number=1;
      $child = $this->get_child($myid); // 先获取当前节点的直接子节点
      if(is_array($child)){
          $total = count($child); // 子节点总数（用于判断是否为最后一个节点）
          foreach($child as $id=>$value){
              // 1. 生成缩进符号（根据是否为最后一个节点，用├或└）
              $j=$k='';
              if($number==$total){ // 最后一个子节点：用└，后续无竖线
                  $j .= $this->icon[2];
              }else{ // 非最后一个：用├，后续加竖线│
                  $j .= $this->icon[1];
                  $k = $adds ? $this->icon[0] : '';
              }
              $spacer = $adds ? $adds.$j : ''; // 最终缩进符号（如“│  ├”）
  
              // 2. 判断是否选中（单选）
              $selected = is_array($sid) ? (in_array($id, $sid) ? 'selected' : '') : ($id==$sid ? 'selected' : '');
  
              // 3. 解析HTML模板（用eval将占位符替换为实际值）
              @extract($value); // 把节点数组转为变量（如$id=$value['id']，$name=$value['name']）
              // 顶级节点用$str_group模板，其他用$str模板
              $parentid == 0 && $str_group ? eval("\$nstr = \"$str_group\";") : eval("\$nstr = \"$str\";");
              $this->ret .= $nstr; // 拼接结果
  
              // 4. 递归处理子节点（传递当前缩进，让子节点缩进更深）
              $this->get_tree($id, $str, $sid, $adds.$k.$this->nbsp,$str_group);
              $number++;
          }
      }
      return $this->ret;
  }
  ```
- 输出示例：若生成下拉框，结果类似：
  ```html
  <option value='1'>前端开发</option>
  <option value='7'>│  ├HTML/CSS</option>
  <option value='19'>│  │  ├HTML5新特性</option>
  <option value='20'>│  │  └CSS3动画</option>
  <option value='8'>│  ├JavaScript</option>
  ```


#### 2. `get_tree_multi($myid, $str, $str2, $sid = 0, $adds = '')`：多选树形
- 功能：在 `get_tree` 基础上支持**多选**，并区分“可用节点”和“禁用节点”。
- 核心差异：
  - 多了 `$str2` 参数：禁用节点的HTML模板（如带 `disabled` 属性的选项）；
  - 选中判断：通过私有方法 `have($sid, $id)` 支持数组或逗号分隔字符串（如 `$sid=[7,8]` 或 `$sid="7,8"`）；
  - 禁用逻辑：判断节点是否有 `html_disabled` 字段，有则用 `$str2` 模板。


#### 3. `get_treeview($myid, $effected_id = 'example', ...)`：可折叠树形（适配jQuery插件）
- 功能：生成符合「jQuery TreeView」插件格式的可折叠树形结构（需配合插件使用），支持指定显示层级、自定义样式。
- 核心特性：
  - 生成 `<ul>/<li>` 嵌套结构，带 `filetree` 等样式类；
  - 支持“异步加载”：通过 `$showlevel` 控制直接显示的层级，超出层级显示占位符，后续异步加载；
  - 支持选中节点数组 `$selectedIds`（如 `$selectedIds=[25]` 选中“Vue.js”）。


#### 4. `creat_sub_json($myid, $str = '')`：异步子节点JSON
- 功能：生成指定节点的子节点JSON数据，用于“异步展开树形”（如点击父节点时动态加载子节点）。
- 输出：包含子节点ID、名称、层级状态（如是否有子节点 `hasChildren`）的JSON字符串，示例：
  ```json
  [
      {"id":"7","liclass":"hasChildren","children":[{"text":"&nbsp;","classes":"placeholder"}],"classes":"folder","text":"HTML/CSS"},
      {"id":"8","text":"JavaScript"}
  ]
  ```


### 模块4：辅助工具方法
只有1个私有方法 `have($list, $item)`，用于判断“项目是否在列表中”，支撑多选功能：
```php
private function have($list, $item){
    if(is_array($list)){
        return in_array($item, $list); // 列表是数组：用in_array判断
    }
    // 列表是字符串（如“7,8,9”）：用strpos判断是否包含目标项（前后加逗号避免部分匹配）
    return (strpos(',,'.$list.',', ','.$item.',') !== false);
}
```


## 四、关键注意点：避免使用踩坑
1. **数据格式依赖**：  
   `$arr` 必须是二维数组，且每个元素必须有 `id` 和 `parentid`；若 `$arr` 是索引数组（下标0、1、2...），原 `get_parent` 方法中 `isset($this->arr[$myid])` 会失效（需先转为关联数组，或修改方法逻辑为遍历匹配 `$value['id']`）。

2. **`eval` 函数风险**：  
   方法中用 `eval` 解析HTML模板（如 `eval("\$nstr = \"$str\";")`），若 `$str` 包含未过滤的用户输入，会有代码注入风险，使用时需确保模板是安全的（自己定义，而非用户传入）。

3. **结果变量重置**：  
   `$ret` 和 `$str` 是类属性，多次调用生成方法（如先 `get_tree` 再 `get_tree_multi`）时需手动重置（如 `$tree->ret = ''`），否则结果会叠加。


## 五、总结：这个类的“核心价值”
`tree.class.php` 本质是一个“**层级数据处理的工具封装**”：  
- 把“递归遍历子节点”“生成缩进符号”“解析选中状态”等重复逻辑封装成方法，避免开发时反复写递归和字符串拼接；  
- 支持多种UI输出（基础下拉框、多选框、可折叠菜单、异步JSON），通过传入不同模板适配不同场景，灵活性高；  
- 缺点是部分方法逻辑有缺陷（如 `get_parent` 语义不符）、依赖 `eval` 有安全风险，但整体仍是中小型项目处理树形结构的实用工具。

使用时只需：  
1. 准备符合格式的层级数组（含 `id`/`parentid`/`name`）；  
2. 调用 `init` 注入数据；  
3. 调用对应方法生成树形UI（如 `get_tree` 生成下拉框，`get_treeview` 生成可折叠菜单）。