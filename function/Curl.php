<?php
/**	op-unit-bitcoin-rpc:/function/RPC.php
 *
 * @created    2026-01-25
 * @license    Apache-2.0
 * @package    op-unit-bitcoin-rpc
 * @copyright  Tomoaki Nagahara
 */

/**	Declare strict type
 *
 */
declare(strict_types=1);

/**	Namespace
 *
 */
namespace OP\UNIT\BITCOIN\RPC;

/**	Fetch
 *
 * @created    2026-01-25
 * @param      string     $method
 * @param      array      $params
 * @param      string     $wallet
 * @return     array
 */
function Curl( string $method, array $params = [], string $wallet = '' ) : array
{
	//	...
	if(!$config = OP()->Config('bitcoin') ){
		return [
			'result' => null,
			'error'  => null,
		];
	}
	$username = $config['username'];
	$password = $config['password'];
	$hostname = $config['hostname'];
	$port     = $config['port'];
	$path     = $wallet ? "wallet/{$wallet}" : '';
	$URL      = "http://{$hostname}:{$port}/{$path}";

	//	...
	$payload = json_encode([
		'jsonrpc' => '1.0',
		'id'      => 'php',
		'method'  => $method,
		'params'  => $params,
	]);

	//	...
	if( extension_loaded('curl') ){
		//	...
		$ch = curl_init();
		curl_setopt_array($ch, [
			CURLOPT_URL            => $URL,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPAUTH       => CURLAUTH_BASIC,
			CURLOPT_USERPWD        => "{$username}:{$password}",
			CURLOPT_POST           => true,
			CURLOPT_POSTFIELDS     => $payload,
			CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
		]);

		//	...
		$response = curl_exec($ch);
		curl_close($ch);
	}else{
	//	D('PHP curl extension does not loaded.');
	//	$json     = json_encode($payload);
		$response = shell_exec("curl -s --user {$username}:{$password} --data-binary '{$payload}' -H 'content-type: text/plain;' {$URL}");
	}

	//	...
	$json = json_decode($response, true);
	if(!OP()->isCI() ){
		D($method, $json);
	}

	//	...
	return $json;
}
