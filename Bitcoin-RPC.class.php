<?php
/**	op-unit-bitcoin-rpc:/Bitcoin-RPC.php
 *
 * @created    2026-01-23
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
namespace OP\UNIT\BITCOIN;

/**	Use
 *
 */
use OP\OP_CORE;
use OP\OP_CI;
use OP\IF_BITCOIN;
use OP\IF_BITCOIN_WALLET;
use function OP\UNIT\BITCOIN\RPC\Curl;
use OP\IF_BITCOIN_ADDRESS;

/**	Include
 *
 */
require_once(__DIR__.'/function/Curl.php');

/**	Bitcoin-RPC
 *
 */
class RPC implements IF_BITCOIN
{
	/**	trait.
	 *
	 */
	use OP_CORE;
	use OP_CI;

	/**	Send to Address.
	 *
	 * @created    2026-01-27
	 * @param      string     $wallet
	 * @param      string     $address
	 * @param      string     $amount
	 * @param      string     $passphrase
	 * @return     string|false
	 */
	public static function Send( string $wallet, string $address, string $amount, ?string $passphrase=null ) : string | false
	{
		//	...
		$params = [
			$address,
			$amount,
		];

		//	...
		if( $passphrase ){
			self::Wallet()->UnLock();
		}

		//	...
		$json = Curl('sendtoaddress', $params, $wallet);

		//	...
		if( $passphrase ){
			self::Wallet()->Lock();
		}

		//	...
		if( $json['error'] ){
			OP()->Error($json['error']['message']);
			return false;
		}

		//	...
		return $json['result'];
	}

	/**	Return wallet interface.
	 *
	 */
	public static function Wallet() : IF_BITCOIN_WALLET
	{
		//	...
		require_once(__DIR__.'/Wallet.class.php');

		//	...
		static $_wallet;

		//	...
		if(!$_wallet ){
			$_wallet = new RPC\Wallet();
		}

		//	...
		return $_wallet;
	}

	/**	Return wallet interface.
	 *
	 */
	public static function Address() : IF_BITCOIN_ADDRESS
	{
		//	...
		require_once(__DIR__.'/Address.class.php');

		//	...
		static $_address;

		//	...
		if(!$_address ){
			$_address = new RPC\Address();
		}

		//	...
		return $_address;
	}
}
