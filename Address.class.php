<?php
/**	op-unit-bitcoin-rpc:/Address.php
 *
 * @created    2026-01-26
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

/**	Use
 *
 */
use OP\OP_CORE;
use OP\OP_CI;
use OP\IF_BITCOIN_ADDRESS;

/**	Include
 *
 */
require_once(__DIR__.'/function/Curl.php');

/**	Bitcoin-RPC
 *
 */
class Address implements IF_BITCOIN_ADDRESS
{
	/**	trait.
	 *
	 */
	use OP_CORE;
	use OP_CI;

	/**	Get wallet connected address by label.
	 *
	 * @created    2026-01-19
	 * @param      string      $wallet
	 * @param      string      $label
	 * @return     string|false
	 */
	static function Get( string $wallet, string $label ) : string | false
	{
		//	...
		if( array_search($wallet, OP()->Unit()->Bitcoin()->Wallet()->List()) === false ){
			OP()->Error("This wallet name does not exists: {$wallet}");
			return false;
		}

		//	...
		$params = [
			$label,
		];

		//	Get addresses by label.
		$json = Curl('getaddressesbylabel', $params, $wallet);
		if( $json['result'] ){
			//	Return already exists address.
			foreach( $json['result'] as $address => $info ){
				if(($info['purpose'] ?? null) === 'receive'){
					return $address;
				}
			}
		}else{
			if( empty($json['error']) ){
				return false;
			}else if( $json['error']['code'] === -11 ){
				//	OK
			}else if( $json['error']['message'] === 'No addresses with label testcase' ){
				//	OK
			}else{
				OP()->Error($json['error']['message']);
			}
		}

		//	Create new address.
		return Curl('getnewaddress', $params, $wallet)['result'];
	}

	/**	Return the total amount sent to that address.
	 *  Not the current balance remaining at that address.
	 *
	 * @created    2026-02-21
	 * @param      string     $address
	 * @param      int        $minconf
	 * @return     float|false
	 */
	static function Recieve( string $wallet, string $address, int $minconf=1 ) : float | false
	{
		//	...
		$params = [
			$address,
			$minconf,
		];

		//	Return balance.
		return Curl('getreceivedbyaddress', $params, $wallet)['result'] ?? false;
	}
}
