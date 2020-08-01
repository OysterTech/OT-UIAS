<?php

declare(strict_types=1);

namespace app;

use think\App;
use think\exception\ValidateException;
use think\Validate;
use app\model\Menu as MenuModel;
use think\facade\Session;

/**
 * 控制器基础类
 */
abstract class BaseController
{
	/**
	 * Request实例
	 * @var \think\Request
	 */
	protected $request;

	/**
	 * 应用实例
	 * @var \think\App
	 */
	protected $app;

	/**
	 * 是否批量验证
	 * @var bool
	 */
	protected $batchValidate = false;

	/**
	 * 控制器中间件
	 * @var array
	 */
	protected $middleware = [];

	/**
	 * 构造方法
	 * @access public
	 * @param  App  $app  应用对象
	 */
	public function __construct(App $app)
	{
		$this->app     = $app;
		$this->request = $this->app->request;

		// 控制器初始化
		$this->initialize();
	}

	// 初始化
	protected function initialize()
	{
		// 先判断是否已登录
		if (!Session::has('userInfo.id')) $this->noPermission(1);

		// 再判断是否有权限
		$uri = $this->request->baseUrl();
		$uri = (substr($uri, 0, 1) == '/') ? substr($uri, 1) : $uri;

		$query = MenuModel::alias('m')
			->field('rp.role_id')
			->join('role_permission rp', 'm.id=rp.menu_id')
			->where('m.uri', $uri)
			->select()
			->toArray();

		// 没有这个菜单
		if (count($query) <= 0) return;

		// 循环判断是否有当前角色
		$roleId = Session::get('userInfo.roleId');
		$havePermission = false;
		foreach ($query as $info) {
			if ($info['role_id'] === $roleId) $havePermission = true;
		}

		if ($havePermission === true) return;
		else $this->noPermission();
	}


	private function noPermission($logout = 0)
	{
		if ($this->request->isAjax()) return packApiData(4031, 'User have no permission', [], '用户无权限访问');
		elseif ($logout === 1) gotourl('/logout');
		else gotourl('/error/noPermission');
	}


	/**
	 * 验证数据
	 * @access protected
	 * @param  array        $data     数据
	 * @param  string|array $validate 验证器名或者验证规则数组
	 * @param  array        $message  提示信息
	 * @param  bool         $batch    是否批量验证
	 * @return array|string|true
	 * @throws ValidateException
	 */
	protected function validate(array $data, $validate, array $message = [], bool $batch = false)
	{
		if (is_array($validate)) {
			$v = new Validate();
			$v->rule($validate);
		} else {
			if (strpos($validate, '.')) {
				// 支持场景
				[$validate, $scene] = explode('.', $validate);
			}
			$class = false !== strpos($validate, '\\') ? $validate : $this->app->parseClass('validate', $validate);
			$v     = new $class();
			if (!empty($scene)) {
				$v->scene($scene);
			}
		}

		$v->message($message);

		// 是否批量验证
		if ($batch || $this->batchValidate) {
			$v->batch(true);
		}

		return $v->failException(true)->check($data);
	}
}
