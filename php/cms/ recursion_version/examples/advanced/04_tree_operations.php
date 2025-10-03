<?php
/**
 * 进阶示例 4：树的递归操作
 * 展示递归在树形结构中的应用
 * 
 * 包含内容：
 * 1. 二叉树的遍历（前序、中序、后序）
 * 2. 树的高度和深度计算
 * 3. 查找和路径
 * 4. 树的构建和修改
 */

// 二叉树节点类
class TreeNode {
    public $value;
    public $left;
    public $right;
    
    public function __construct($value) {
        $this->value = $value;
        $this->left = null;
        $this->right = null;
    }
}

// 二叉树类
class BinaryTree {
    private $root;
    
    public function __construct() {
        $this->root = null;
    }
    
    // 插入节点（二叉搜索树方式）
    public function insert($value) {
        $this->root = $this->insertRecursive($this->root, $value);
    }
    
    private function insertRecursive($node, $value) {
        // 基础情况：找到插入位置
        if ($node === null) {
            return new TreeNode($value);
        }
        
        // 递归插入到左子树或右子树
        if ($value < $node->value) {
            $node->left = $this->insertRecursive($node->left, $value);
        } else {
            $node->right = $this->insertRecursive($node->right, $value);
        }
        
        return $node;
    }
    
    // 前序遍历：根 -> 左 -> 右
    public function preorder($node = null) {
        if ($node === null) {
            $node = $this->root;
        }
        
        $result = [];
        $this->preorderRecursive($node, $result);
        return $result;
    }
    
    private function preorderRecursive($node, &$result) {
        if ($node === null) {
            return;
        }
        
        $result[] = $node->value;                    // 访问根
        $this->preorderRecursive($node->left, $result);  // 遍历左子树
        $this->preorderRecursive($node->right, $result); // 遍历右子树
    }
    
    // 中序遍历：左 -> 根 -> 右
    public function inorder($node = null) {
        if ($node === null) {
            $node = $this->root;
        }
        
        $result = [];
        $this->inorderRecursive($node, $result);
        return $result;
    }
    
    private function inorderRecursive($node, &$result) {
        if ($node === null) {
            return;
        }
        
        $this->inorderRecursive($node->left, $result);   // 遍历左子树
        $result[] = $node->value;                     // 访问根
        $this->inorderRecursive($node->right, $result);  // 遍历右子树
    }
    
    // 后序遍历：左 -> 右 -> 根
    public function postorder($node = null) {
        if ($node === null) {
            $node = $this->root;
        }
        
        $result = [];
        $this->postorderRecursive($node, $result);
        return $result;
    }
    
    private function postorderRecursive($node, &$result) {
        if ($node === null) {
            return;
        }
        
        $this->postorderRecursive($node->left, $result);  // 遍历左子树
        $this->postorderRecursive($node->right, $result); // 遍历右子树
        $result[] = $node->value;                      // 访问根
    }
    
    // 计算树的高度
    public function height($node = null) {
        if ($node === null) {
            $node = $this->root;
        }
        
        return $this->heightRecursive($node);
    }
    
    private function heightRecursive($node) {
        // 基础情况：空节点高度为 0
        if ($node === null) {
            return 0;
        }
        
        // 递归计算左右子树高度，取较大值加 1
        $leftHeight = $this->heightRecursive($node->left);
        $rightHeight = $this->heightRecursive($node->right);
        
        return max($leftHeight, $rightHeight) + 1;
    }
    
    // 查找节点
    public function find($value) {
        return $this->findRecursive($this->root, $value);
    }
    
    private function findRecursive($node, $value) {
        // 基础情况
        if ($node === null || $node->value === $value) {
            return $node;
        }
        
        // 在二叉搜索树中查找
        if ($value < $node->value) {
            return $this->findRecursive($node->left, $value);
        } else {
            return $this->findRecursive($node->right, $value);
        }
    }
    
    // 查找从根到目标节点的路径
    public function findPath($value) {
        $path = [];
        $this->findPathRecursive($this->root, $value, $path);
        return $path;
    }
    
    private function findPathRecursive($node, $value, &$path) {
        if ($node === null) {
            return false;
        }
        
        // 将当前节点加入路径
        $path[] = $node->value;
        
        // 找到目标节点
        if ($node->value === $value) {
            return true;
        }
        
        // 在左子树或右子树中查找
        if ($this->findPathRecursive($node->left, $value, $path) ||
            $this->findPathRecursive($node->right, $value, $path)) {
            return true;
        }
        
        // 回溯：从路径中移除当前节点
        array_pop($path);
        return false;
    }
    
    // 计算节点总数
    public function countNodes($node = null) {
        if ($node === null) {
            $node = $this->root;
        }
        
        // 基础情况
        if ($node === null) {
            return 0;
        }
        
        // 递归计算：1（当前节点）+ 左子树节点数 + 右子树节点数
        return 1 + $this->countNodes($node->left) + $this->countNodes($node->right);
    }
    
    // 计算叶子节点数
    public function countLeaves($node = null) {
        if ($node === null) {
            $node = $this->root;
        }
        
        // 基础情况
        if ($node === null) {
            return 0;
        }
        
        // 叶子节点：没有子节点
        if ($node->left === null && $node->right === null) {
            return 1;
        }
        
        // 递归计算左右子树的叶子节点数
        return $this->countLeaves($node->left) + $this->countLeaves($node->right);
    }
    
    // 镜像翻转树
    public function mirror($node = null) {
        if ($node === null) {
            $node = $this->root;
        }
        
        // 基础情况
        if ($node === null) {
            return null;
        }
        
        // 交换左右子树
        $temp = $node->left;
        $node->left = $node->right;
        $node->right = $temp;
        
        // 递归翻转左右子树
        $this->mirror($node->left);
        $this->mirror($node->right);
        
        return $node;
    }
    
    // 判断是否为平衡二叉树
    public function isBalanced($node = null) {
        if ($node === null) {
            $node = $this->root;
        }
        
        return $this->checkBalance($node) !== -1;
    }
    
    private function checkBalance($node) {
        // 基础情况
        if ($node === null) {
            return 0;
        }
        
        // 检查左子树
        $leftHeight = $this->checkBalance($node->left);
        if ($leftHeight === -1) {
            return -1;
        }
        
        // 检查右子树
        $rightHeight = $this->checkBalance($node->right);
        if ($rightHeight === -1) {
            return -1;
        }
        
        // 检查当前节点是否平衡
        if (abs($leftHeight - $rightHeight) > 1) {
            return -1;
        }
        
        return max($leftHeight, $rightHeight) + 1;
    }
    
    // 可视化打印树
    public function printTree($node = null, $prefix = "", $isLeft = true) {
        if ($node === null) {
            $node = $this->root;
        }
        
        if ($node !== null) {
            echo $prefix;
            echo $isLeft ? "├── " : "└── ";
            echo $node->value . "\n";
            
            if ($node->left !== null || $node->right !== null) {
                if ($node->left !== null) {
                    $this->printTree($node->left, $prefix . ($isLeft ? "│   " : "    "), true);
                } else {
                    echo $prefix . ($isLeft ? "│   " : "    ") . "├── null\n";
                }
                
                if ($node->right !== null) {
                    $this->printTree($node->right, $prefix . ($isLeft ? "│   " : "    "), false);
                } else {
                    echo $prefix . ($isLeft ? "│   " : "    ") . "└── null\n";
                }
            }
        }
    }
    
    public function getRoot() {
        return $this->root;
    }
}

// 通用树节点（可以有多个子节点）
class GeneralTreeNode {
    public $value;
    public $children;
    
    public function __construct($value) {
        $this->value = $value;
        $this->children = [];
    }
    
    public function addChild($child) {
        $this->children[] = $child;
    }
}

// 文件系统树示例
function buildFileSystemTree() {
    $root = new GeneralTreeNode("/");
    
    $home = new GeneralTreeNode("home");
    $usr = new GeneralTreeNode("usr");
    $var = new GeneralTreeNode("var");
    
    $root->addChild($home);
    $root->addChild($usr);
    $root->addChild($var);
    
    $user1 = new GeneralTreeNode("user1");
    $user2 = new GeneralTreeNode("user2");
    $home->addChild($user1);
    $home->addChild($user2);
    
    $documents = new GeneralTreeNode("documents");
    $downloads = new GeneralTreeNode("downloads");
    $user1->addChild($documents);
    $user1->addChild($downloads);
    
    return $root;
}

// 计算目录大小（假设叶子节点有大小）
function calculateSize($node, $sizes = []) {
    // 如果是叶子节点（文件），返回其大小
    if (empty($node->children)) {
        return $sizes[$node->value] ?? 1; // 默认大小为 1
    }
    
    // 递归计算所有子节点的大小总和
    $totalSize = 0;
    foreach ($node->children as $child) {
        $totalSize += calculateSize($child, $sizes);
    }
    
    return $totalSize;
}

// 查找文件路径
function findFile($node, $filename, $currentPath = "") {
    $currentPath .= "/" . $node->value;
    
    // 找到目标文件
    if ($node->value === $filename) {
        return $currentPath;
    }
    
    // 在子节点中查找
    foreach ($node->children as $child) {
        $result = findFile($child, $filename, $currentPath);
        if ($result !== null) {
            return $result;
        }
    }
    
    return null;
}

// 测试和演示
echo "=== 二叉树操作演示 ===\n\n";

// 创建二叉搜索树
$tree = new BinaryTree();
$values = [50, 30, 70, 20, 40, 60, 80, 10, 25, 35, 45];

echo "插入值: " . implode(", ", $values) . "\n\n";
foreach ($values as $value) {
    $tree->insert($value);
}

echo "树结构：\n";
$tree->printTree();

echo "\n=== 遍历演示 ===\n";
echo "前序遍历: " . implode(", ", $tree->preorder()) . "\n";
echo "中序遍历: " . implode(", ", $tree->inorder()) . "\n";
echo "后序遍历: " . implode(", ", $tree->postorder()) . "\n";

echo "\n=== 树的属性 ===\n";
echo "树的高度: " . $tree->height() . "\n";
echo "节点总数: " . $tree->countNodes() . "\n";
echo "叶子节点数: " . $tree->countLeaves() . "\n";
echo "是否平衡: " . ($tree->isBalanced() ? "是" : "否") . "\n";

echo "\n=== 查找操作 ===\n";
$searchValue = 35;
$node = $tree->find($searchValue);
echo "查找 $searchValue: " . ($node ? "找到" : "未找到") . "\n";

$path = $tree->findPath($searchValue);
echo "从根到 $searchValue 的路径: " . implode(" -> ", $path) . "\n";

echo "\n=== 文件系统树演示 ===\n";
$fileSystem = buildFileSystemTree();

// 模拟文件大小
$fileSizes = [
    'documents' => 100,
    'downloads' => 200,
    'user1' => 50,
    'user2' => 75
];

echo "文件系统结构示例：\n";
echo "/\n";
echo "├── home/\n";
echo "│   ├── user1/\n";
echo "│   │   ├── documents/\n";
echo "│   │   └── downloads/\n";
echo "│   └── user2/\n";
echo "├── usr/\n";
echo "└── var/\n";

$totalSize = calculateSize($fileSystem, $fileSizes);
echo "\n总大小: $totalSize\n";

$searchFile = "documents";
$filePath = findFile($fileSystem, $searchFile);
echo "查找 '$searchFile': $filePath\n";

echo "\n=== 递归的优势 ===\n";
echo "1. 代码简洁：树的递归操作比迭代更直观\n";
echo "2. 自然匹配：树的定义本身就是递归的\n";
echo "3. 易于理解：每个节点的处理方式相同\n";
echo "4. 功能强大：轻松实现复杂的树操作\n";
