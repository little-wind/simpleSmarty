<?php
  //模板类
  class Templates {
    //通过创建数组，接收需要解析的内容
    private $vars = array();
    //保存系统变量
    private $config = array();

    
    public function __construct() {
      //验证目录是否存在
      if(!is_dir(TPL_DIR) OR !is_dir(TPL_C_DIR) OR !is_dir(CACHE)) {
        exit('ERROR: 模板/编译目录/缓存目录不存在！');
      }

      //提取系统变量
      $sxe = simplexml_load_file('config/profile.xml');
      $tagLib = $sxe->xpath('/root/taglib');
      foreach($tagLib as $tag) {
        $this->config["$tag->name"] = $tag->value;
      }
    }

    //注入变量，进行内容的替换
    //$var：同步模板里的变量名；$value：变量的值
    public function assign($var, $value) {
      if(isset($var) AND !empty($var)) {
        //赋值
        $this->vars[$var] = $value;
      } else {
        exit('ERROR: 模板变量不存在！');
      }
    }

    //display()方法
    public function display($file) {
      //生成模板路径
      $tplFile = TPL_DIR . $file;

      //验证模板文件是否存在
      if(!file_exists($tplFile)) {
        exit('ERROR: 模板文件不存在！');
      }

      //编译文件
      $parFile = TPL_C_DIR . sha1($file) . $file . '.php';
      //缓存文件
      $cacheFile = CACHE . sha1($file) . $file . '.html';

      //如果文件非第一次执行，跳过编译，直接调用缓存文件
      if(IS_CACHE) {
        //验证缓存/编译文件是否存在
        if(file_exists($cacheFile) AND file_exists($parFile)) {
          //判断模板文件是否曾修改
          if(filemtime($parFile) >= filemtime($tplFile) AND  //判断编译文件修改时间是否大于等于模板修改时间
              filemtime($cacheFile) >= filemtime($parFile)) { //判断缓存修改时间是否大于等于编译文件修改时间
            include $cacheFile;
            return; //跳过本方法，不再执行
          }
        }
      }

      //判断编译文件是否存在，或者模板文件是否被修改了
      if(!file_exists($parFile) OR filemtime($parFile) < filemtime($tplFile)) {
        //引用模板解析类
        require ROOT_PATH . '\\inc\\Parser.class.php';
        $Parser = new Parser($tplFile);
        $Parser->compile($parFile);
      }

      //载入编译文件
      include $parFile;
      if(IS_CACHE) {
        //获取缓冲区内的数据，并创建缓存文件
        file_put_contents($cacheFile, ob_get_contents());
        //清楚缓冲区，即清除编译文件所加载的内容
        ob_end_clean();
        //载入缓存文件
        include $cacheFile;
      }
    }
  }
?>