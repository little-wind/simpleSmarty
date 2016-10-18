<?php
  //模板解析类
  class Parser {
    private $tplContent;
    

    //构造函数，获取模板文件内容
    public function __construct($tplFile) {
      if(!$this->tplContent = file_get_contents($tplFile)) {
        exit('ERROR: 模板文件读取错误！');
      }
    }

    //解析普通变量
    private function parserVar() {
      //查找到tpl中的所有变量
      $patten = '/\{\$([\w]+)\}/';
      //+：一个或多个
      if(preg_match($patten, $this->tplContent)) {
        $this->tplContent = preg_replace($patten, "<?php echo \$this->vars['$1']; ?>", $this->tplContent);
      }
    }

    //解析系统变量
    private function parserConfig() {
      $patten = '/<!--\{([\w]+)\}-->/';
      //查找系统变量的标识
      if(preg_match($patten, $this->tplContent)) {
        $this->tplContent = preg_replace($patten, "<?php echo \$this->config['$1']; ?>", $this->tplContent);
      }
    }

    public function compile($parFile) {
      $this->parserVar();
      $this->parserConfig();

      //写入编译文件
      if(!file_put_contents($parFile, $this->tplContent)) {
        exit('ERROR: 编译文件生成出错！');
      }
    }
  }
?>