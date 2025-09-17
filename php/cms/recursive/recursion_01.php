<?php
function visualizeRecursion($n, $depth = 0) {
  // $indent: 根据递归深度创建缩进字符串
  // 每层递归增加2个空格，用于可视化层次结构
  $indent = str_repeat("&nbsp;&nbsp;", $depth); // 使用HTML空格实体
  
  echo $indent . "→ 进入 visualizeRecursion($n)<br>";
  echo $indent . "&nbsp;&nbsp;当前递归深度: $depth<br>";
  
  if ($n <= 0) {
      echo $indent . "&nbsp;&nbsp;🎯 到达基础情况 (n=$n)<br>";
      echo $indent . "← 返回 visualizeRecursion($n)<br>";
      return;
  }
  
  echo $indent . "&nbsp;&nbsp;📞 准备递归调用 visualizeRecursion(" . ($n-1) . ")...<br>";
  visualizeRecursion($n - 1, $depth + 1);
  echo $indent . "← 返回 visualizeRecursion($n)<br>";
}


echo "<h3>递归可视化演示：</h3>";
echo "<div style='font-family: monospace; background: #f5f5f5; padding: 10px; border-radius: 5px;'>";
visualizeRecursion(3);
echo "</div>";