<?php

namespace app;

use app\model\ApiRequestLog;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\HttpResponseException;
use think\exception\ValidateException;
use think\Response;
use Throwable;
use think\facade\Request;
use think\facade\Session;

/**
 * 应用异常处理类
 */
class ExceptionHandle extends Handle
{
	/**
	 * 不需要记录信息（日志）的异常类列表
	 * @var array
	 */
	protected $ignoreReport = [
		HttpException::class,
		HttpResponseException::class,
		ModelNotFoundException::class,
		DataNotFoundException::class,
		ValidateException::class,
	];

	/**
	 * 记录异常信息（包括日志或者其它方式记录）
	 *
	 * @access public
	 * @param  Throwable $exception
	 * @return void
	 */
	public function report(Throwable $exception): void
	{
		// 使用内置的方式记录异常日志
		// parent::report($exception);
	}

	/**
	 * Render an exception into an HTTP response.
	 *
	 * @access public
	 * @param \think\Request   $request
	 * @param Throwable $e
	 * @return Response
	 */
	public function render($request, Throwable $e): Response
	{
		// 添加自定义异常处理机制
		$statusCode = (($e instanceof HttpException)) ? $e->getStatusCode() : $e->getCode();

		if ($statusCode === 404) return parent::render($request, $e);

		$requestId = makeUUID();
		ApiRequestLog::create([
			'request_id' => $requestId,
			'path' => Request::baseUrl(),
			'referer' => $_SERVER['HTTP_REFERER'] ?? '.',
			'ip' => getIP(),
			'code' => $statusCode,
			'message' => $e->getMessage(),
			'req_data' => json_encode(Request::except(['token'])),
			'res_data' => json_encode([
				'file' => $e->getFile(),
				'line' => $e->getLine(),
				'session' => Session::all()
			]),
		]);

		return packApiData($statusCode, 'error', ['errorRequestId' => $requestId, 'errorInfo' => $e], '', false);

		// 其他错误交给系统处理
		// return parent::render($request, $e);
	}
}
