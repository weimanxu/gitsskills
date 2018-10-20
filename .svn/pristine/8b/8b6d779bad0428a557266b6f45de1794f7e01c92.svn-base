<?php
class View {
    //视图文件名，不包含后缀名，例如，加载test/index时，viewFile为index
    private $viewFile = '';
    //视图数据
    private $data = [];
    
	//默认layout
    public $layout = 'main';
	
	public $defaultExtension = 'php';
	public $params = [];		//页面、layout之间传递数据
	
	/**
	 * 单标签HTML元素
	 */
	public static $voidElements = [
			'area' => 1,
			'base' => 1,
			'br' => 1,
			'col' => 1,
			'command' => 1,
			'embed' => 1,
			'hr' => 1,
			'img' => 1,
			'input' => 1,
			'keygen' => 1,
			'link' => 1,
			'meta' => 1,
			'param' => 1,
			'source' => 1,
			'track' => 1,
			'wbr' => 1,
	];
	
	/**
	 * 通过renderTagAttributes()生成的标签，属性按照以下顺序排序
	 */
	public static $attributeOrder = [
			'type',
			'id',
			'class',
			'name',
			'value',
	
			'href',
			'src',
			'action',
			'method',
	
			'selected',
			'checked',
			'readonly',
			'disabled',
			'multiple',
	
			'size',
			'maxlength',
			'width',
			'height',
			'rows',
			'cols',
	
			'alt',
			'title',
			'rel',
			'media',
	];
	public static $dataAttributes = ['data', 'data-ng', 'ng'];
	
    private static $Instance = null;
    
    public $title;
    
    static private $ver_number;
    static private $cdn_server;
    
    /**
     * @param array 通过registerJs()注册的JS文件
     */
    public $jsFiles;
    
    /**
     * @param array //通过registerCss()注册的CS文件
     */
    public $cssFiles;
    
    public $metaTags;	//meta标签的数组
    
    public static function _getInstance () {
    	if (self::$Instance === null){
    		self::$Instance = new self();
    	}
    	return self::$Instance;
    }
    
    private function __construct () {
        $this->title = App::loadConf('webName');
        self::$ver_number = App::loadConf('deployVersion');
        self::$cdn_server = empty(App::loadConf('cdnServer')) || App::isDev() ? '' : (App::$request->getProtocol() .'://'. App::loadConf('cdnServer'));
    }
    
    
    /**
     * 加载所有meta标签
     * @param
     * @return
     */
    public function head(){
    	if (empty($this->metaTags)) return '';
    	return implode("\n", $this->metaTags) . "\n";
    }
    
    
    /**
     * 输出css格式的html，放到页面顶部
     */
    public function loadStyles(){
    	if (empty($this->cssFiles)) return '';
    	return implode("\n", $this->cssFiles) . "\n";
    }
    
    /**
     * 输出js格式的html，放到页面底部
     */
    public function loadScripts(){
    	if (empty($this->jsFiles)) return '';
    	return implode("\n", $this->jsFiles) . "\n";
    }
    
    /**
     * 设置视图数据
     * 
     * @return 
     * @author Ymj Created at 2016年12月20日
     */
    public function setData($key, $value = ''){
        if(is_array($key)){
            $this->data = array_merge($this->data, $key);
        }else{
            $this->data[$key] = $value;
        }
    }
    
    /**
     * 加载视图页面
     * @param $view		//view文件夹下的路径
     * @param $data		//页面数据
     */
    public function loadView($viewName, $data = []){
        $data = array_merge($this->data, $data);
        
        //设置视图文件名
        $lastIndex = strrpos($viewName, '/');
        if ($lastIndex > 0){
            $this->viewFile = substr($viewName, $lastIndex + 1);
        }else{
            $this->viewFile = $viewName;
        }
        
    	//view文件路径
    	$viewFile = $this->_findViewFile($viewName);
    	//view文件内容
    	$viewContent = $this->_renderFile($viewFile, $data);
    	//layout文件路径
    	$layoutFile = $this->_getLayoutFile();
    	
    	$params = array_merge(['content' => $viewContent], $data);
    	//使用布局
    	if ($layoutFile) {
    		$viewContent = $this->_renderFile($layoutFile, $params);
    	}

    	$viewContent = $this->loadDebug($viewContent);
    	echo $viewContent;
    	exit;
    }
    
    /**
     * 加载debug信息
     * @param string $viewContent		//页面内容
     * @return string
     */
    public function loadDebug ($viewContent) {
    	
    	//debug下插入profile
    	if (App::isDebug()){
    		//打开输出控制缓冲
    		ob_start();
    		ob_implicit_flush(false);
    		 
    		App::requireFile('/view/debug/profile.php');
    		$debug = ob_get_clean();
    		 
    		//查找</head>标签替换，加载debug.js
    		$viewContent = str_replace("</head>",  $this->jsHtml('/public/js/lib/debug.js') . "</head>", $viewContent);
    		//查找</body>标签替换
    		$viewContent = str_replace("</body>", $debug . "</body>", $viewContent);
    	}
    	return $viewContent;
    }
    
    
    /**
     * layout嵌套开始，开启输出控制
     */
    public function beginContent() {
    	//打开输出控制缓冲
    	ob_start();
    	ob_implicit_flush(false);
    }
    
    /**
     * layout嵌套结束（在子layout里面引入父layout）
     * @param string $layout		//父layout文件名字
     * @param array $params			//数据（content为内部保留键）
     */
    public function endContent($parentLayout, $params = []) {
    	$sonLayout = ob_get_clean();
    	
    	$file = PATH .'/view/layouts/'. $parentLayout;
    	
    	if (pathinfo($file, PATHINFO_EXTENSION) === '') {
    		$file .= '.' . $this->defaultExtension;
    	}
    	
    	if (!is_file($file) || pathinfo($file, PATHINFO_EXTENSION) !== 'php') {
    		die('布局文件有误！');
    	}
    	 
    	//子layout内容
    	$params['content'] = $sonLayout;
    	 
    	$viewContent = $this->_renderFile($file, $params);
    	 
    	echo $this->loadDebug($viewContent);
    	exit;
    }
    
    
    /**
     * 返回视图文件名
     *
     * @param  
     * @return string
     */
    public function getViewFile () {
        return $this->viewFile;
    }
    
    /**
     * 查找view文件
     * @param $view		//views路径下的相对路径
     * @return string view文件绝对路径
     */
    private function _findViewFile($view) {
    	$file = PATH .'/view/'. $view;
    	if (pathinfo($file, PATHINFO_EXTENSION) === '') {
    		$file .= '.' . $this->defaultExtension;
    	}
    
    	if (!is_file($file)) {
    		die('视图不存在！');
    	}
    
    	return $file;
    }
    
    
    /**
     * 输出文件到输出流并返回保存
     * @param
     * @return stream
     */
    private function _renderFile($file, $params = []) {
    	//打开输出控制缓冲
    	ob_start();
    	ob_implicit_flush(false);
    		
    	//layout里面的content
    	extract($params, EXTR_OVERWRITE);
    	require_once $file;
    	
    	$html = ob_get_clean();
    	
    	return $html;
    }
    
    /**
     * 获取layout文件路径
     * 
     * @return false | string
     */
    private function _getLayoutFile($viewFile = '') {
    	$layout = $viewFile ? $viewFile : $this->layout;
    	
    	if (empty($layout)) return false;
    		
    	$file = PATH .'/view/layouts/'. $layout;
    
    	if (pathinfo($file, PATHINFO_EXTENSION) === '') {
    		$file .= '.' . $this->defaultExtension;
    	}
    		
    	if (!is_file($file) || pathinfo($file, PATHINFO_EXTENSION) !== 'php') {
    		die('布局文件有误！');
    	}
    	return $file;
    }
    
    
    /**
     * 绑定meta元素
     * 格式：
     * $options = [
     * 		'name' => 'keywords'
     * 		'content' => '关键词1, 关键词2, 关键词3'
     * ]
     *
     *
     * @param array $options	//meta数据
     * @param string $key		//标记，相同则后面的覆盖前面的元素
     */
    public function registerMetaTag($options, $key = null){
    	if ($key === null) {
    		$this->metaTags[] = static::tag('meta', '', $options);
    	} else {
    		$this->metaTags[$key] = static::tag('meta', '', $options);
    	}
    }
    
    
    /**
     * 绑定css文件
     * @param string | array $css 	//css文件路径( /public/....)
     * @param boolean $version		//版本号
     * @param array $options		//css元素属性
     */
    public function registerCss($css, $version = null, $options = []){
    	$ver = self::_makeVersion($version);
    
    	if (is_string($css)) {
    		$css = self::$cdn_server . $css . $ver;
    		$this->cssFiles[] = static::cssFile($css, $options);
    	}elseif (is_array($css)) {
    		foreach ($css as $one) {
    			$one = self::$cdn_server . $one . $ver;
    			$this->cssFiles[] = static::cssFile($one, $options);
    		}
    	}
    }
    
    
    /**
     * 绑定js文件
     * @param string | array 	//js文件路径( /public/....)
     * @param boolean $version	//版本号
     * @param array $options	//js元素属性
     */
    public function registerJs($js, $version = null, $options = []){
    	$ver = self::_makeVersion($version);
    
    	if (is_string($js)) {
    	    if (preg_match('/^https?|\/\//', $js)){
    	        $js = $js . $ver;
    	    }else{
    	        $js = self::$cdn_server . $js . $ver;
    	    }
    		$this->jsFiles[] = static::jsFile($js, $options);
    	}elseif (is_array($js)) {
    		foreach ($js as $one) {
    		    if (preg_match('/^https?|\/\//', $js)){
    		        $one = self::$cdn_server . $one . $ver;
    		    }else{
    		        $one = $one . $ver;
    		    }
    			$this->jsFiles[] = static::jsFile($one, $options);
    		}
    	}
    }
    
    /**
     * 即时引用js文件
     * @param string | array $url	//以斜杠开头的js文件路径
     * @param boolean $version		//是否添加版本号
     * @return string
     */
    public function jsHtml($url, $version = null){
    	$ver = self::_makeVersion($version);
    	
    	$html = '';
    	if (is_string($url)) {
	    	$src = self::$cdn_server . $url . $ver;
	    	$html = '<script src="'. $src ."\"></script>\n";
    	}elseif (is_array($url)) {
    		foreach ($url as $one) {
    			$one = self::$cdn_server . $one . $ver;
    			$html .= '<script src="'. $one ."\"></script>\n";
    		}
    	}
    	
    	return $html;
    }
    
    /**
     * 即时引用css文件
     * @param string $url			//以斜杠开头的js文件路径
     * @param boolean $version		//是否添加版本号
     * @return 
     */
    public function cssHtml($url, $version = null){
    	$ver = self::_makeVersion($version);
    	
    	$html = '';
    	if (is_string($url)) {
    		$href = self::$cdn_server . $url . $ver;
    		$html = '<link href="'. $href ."\" rel=\"stylesheet\">\n";
    	}elseif (is_array($url)) {
    		foreach ($url as $one) {
    			$one = self::$cdn_server . $one . $ver;
    			$html .= '<link href="'. $one ."\" rel=\"stylesheet\">\n";
    		}
    	}
    	 
    	return $html;
    }
    
    /**
     * 返回配置版本号
     * 
     * @return number
     */
    public function getVersion() {
    	return self::$ver_number;
    }
    
    
    
    /**
     * 组装版本号字符串
     * @param boolean $ver	//true: 设置版本号， false: 没有版本号
     * 
     * 默认 null则根据环境设置，开发环境：版本号为当前时间戳，发布环境：版本号在配置文件中指定
     * 
     * @return 
     */
    private static function _makeVersion($ver = null) {
    	if ($ver !== null && is_bool($ver)){
    	    $str = $ver && self::$ver_number ? ('?v='. self::$ver_number) : '';
    	}
    	else{
    	    if(App::isDev()){
    	        $str = '?v=' . time();
    	    }else{
    	        if(!empty(self::$ver_number)){
    	            $str = '?v=' . self::$ver_number;
    	        }else{
    	            $str = '';
    	        }
    	    }
    	}
    	return $str;
    }
    
    
    /**
     * 生成css的html头
     * @param $url	//css文件路径 | 完整url
     * @return
     */
    public static function cssFile($url, $options = []) {
    	if (!isset($options['rel'])) $options['rel'] = 'stylesheet';
    	$options['href'] = $url;
    	
    	/**
    	 * <link href="/static/common/base.css" rel="stylesheet">
    	 * <!-- [if lt IE 8 ]><link href="/static/common/base.css" rel="stylesheet"><![endif]-->
    	 * <noscript>Your browser does not support html5!</noscript>
    	 */
    	if (isset($options['condition'])) {
    		$condition = $options['condition'];
    		unset($options['condition']);
    		return "<!--[if $condition]>" . static::tag('link', '', $options) . "<![endif]-->";
    	} elseif (isset($options['noscript']) && $options['noscript'] === true) {
    		unset($options['noscript']);
    		return "<noscript>" . static::tag('link', '', $options) . "</noscript>";
		} else {
    		return static::tag('link', '', $options);
    	}
    }
    
    
    /**
     * 生成js的html头
     * @param $url	//js文件路径 | 完整url
     * @return
     */
    public static function jsFile($url, $options = []) {
    	$options['src'] = $url;
    	if (isset($options['condition'])) {
    		$condition = $options['condition'];
    		unset($options['condition']);
    		return "<!--[if $condition]>\n" . static::tag('script', '', $options) . "\n<![endif]-->";
    	} else {
    		return static::tag('script', '', $options);
    	}
    }
    
    /**
     * 生成HTML内容
     * @param 
     * @return 
     */
    public static function tag($name, $content = '', $options = []){
    	$html = '<'. $name . static::renderTagAttributes($options) .'>';
    	return isset(static::$voidElements[strtolower($name)]) ? $html : ($html . $content .'</'. $name .'>');
    }
    
    
    
	/**
     * 生成html标签的属性
     *
     * boolean类型  直接填其键
     * eg:
     *   selected => true   转换为： selected
     *
     * null值过滤掉
     *
     * 属性值会使用encode()处理
     *
     * 'data' => ['id' => 1, 'name' => 'asc'] 会转换为：data-id="1" data-name="asc"
     *
     * 'data' => ['params' => ['id' => 1, 'name' => 'asc'], 'status' => 'ok']转换为：
     * data-params='{"id":1,"name":"asc"}' data-status="ok"
     *
     * @param array $attributes //html标签要渲染的属性
     * @return string | empty
     */
    public static function renderTagAttributes($attributes) {
    	//属性排序
		if (count($attributes) > 1) {
			$sorted = [];
			foreach (static::$attributeOrder as $name) {
	    	  	if (isset($attributes[$name])) {
	    	  		$sorted[$name] = $attributes[$name];
	    	  	}
    	  	}
    	  	
    	  	$attributes = array_merge($sorted, $attributes);
		}
    
		
		/**
		 * 实例
		 * [
		 *  	'id' => 'order',
		 *  
		 *  	'class' => 'nav',
		 *  
		 *  	'data' => [
		 *  		'id' => 1, 'name' => 'asc',
		 * 		],
		 * ]
		 */
		
		$html = '';
		foreach ($attributes as $name => $value) {
			if (is_bool($value) && $value) {
					$html .= " $name";
			} elseif (is_array($value)) {
				
				//name = data
			    if (in_array($name, static::$dataAttributes)) {
				    foreach ($value as $k => $v) {
					    if (is_array($v)) {
					    	$html .= " $name-$k='" . json_encode($v, JSON_HEX_APOS) . "'";
					    } else {
					   		$html .= " $name-$k=\"" . static::html_encode($v) . '"';
					    }
				    }
			    } else {
			    	$html .= " $name='" . json_encode($value, JSON_HEX_APOS) . "'";
			    }
			} elseif ($value !== null) {
		    	$html .= " $name=\"" . static::html_encode($value) . '"';
		    }
    	}
    
    	return $html;
    }
    
	/**
	 * 处理$content的特殊元素
	 * @param string $content
	 * @param boolean $doubleEncode	//是否处理$content里面的HTML元素
	 * @return string
	 */
	public static function html_encode($content, $doubleEncode = true) {
    	return htmlspecialchars($content, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', $doubleEncode);
    }
    
    /**
     * 还原html标签元素
     * @param string $content
     * @return string
     */
    public static function html_decode($content) {
		return htmlspecialchars_decode($content, ENT_QUOTES);
	}
}