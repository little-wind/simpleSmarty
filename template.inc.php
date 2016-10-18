<?php
  //设置字符编码
  header('Content-Type:text/html; charset=utf-8');
  //设置网站根目录
  define('ROOT_PATH', dirname(__FILE__));
  //定义模板文件目录
  define('TPL_DIR', ROOT_PATH . '\\templates\\');
  //编译文件目录
  define('TPL_C_DIR', ROOT_PATH . '\\templates_c\\');
  //缓存目录
  define('CACHE', ROOT_PATH . '\\cache\\');
  //是否开启缓冲区，默认开启
  define('IS_CACHE', true);
  //判断是否开启缓冲区
  IS_CACHE ? ob_start() : NULL;
  //引入模板类
  require ROOT_PATH . '/inc/Templates.class.php';
?>