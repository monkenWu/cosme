<?php  namespace App\Libraries\Microservice;

use CodeIgniter\Exceptions\ExceptionInterface;
use CodeIgniter\Exceptions\FrameworkException;

class GatewayException extends FrameworkException implements ExceptionInterface
{

	/**
	 * 找不到微服務
	 *
	 * @param string $serviceName 微服務名稱
	 * @return GatewayException
	 */
	public static function forServiceNotFound(string $serviceName):GatewayException
	{
		return new static("找不到 {$serviceName} 這個微服務，請確認 /Config/Gateway.php 是否正確紀錄");
	}

	public static function forServiceActionError(string $serviceName,string $errorString):GatewayException
	{
		return new static("服務實體 {$serviceName} 動作異常︰{$errorString}");
	}
}
?>