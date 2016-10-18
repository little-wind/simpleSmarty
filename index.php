<?php
  require dirname(__FILE__) . '\\template.inc.php';

  //实例化模板类
  $tpl = new Templates();

  //注入变量
  $name = '此景、哏美';
  // $content = 'http://www.windsmemory.com';

  $tpl->assign('name', $name);
  // $tpl->assign('content', $content);

  //载入tpl文件
  $tpl->display('index.tpl');
?>