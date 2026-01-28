<?php
/**	op-unit-bitcoin-rpc:/Wallet.class.php
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

/**	Use
 *
 */
use OP\OP_CORE;
use OP\OP_CI;
use OP\IF_BITCOIN_WALLET;

/**	Include
 *
 */
require_once(__DIR__.'/function/Curl.php');

/**	Bitcoin-RPC
 *
 */
class Wallet implements IF_BITCOIN_WALLET
{
	/**	trait.
	 *
	 */
	use OP_CORE;
	use OP_CI;

	/**	Get balance.
	 *
	 */
	public static function Balance( string $wallet ) : float | false
	{
		return Curl('getbalance', [], $wallet)['result'] ?? false;
	}

	/**	Create wallet.
	 *
	 * @created    2026-01-25
	 */
	static public function Create( string $name, ?string $passphrase=null ) : bool
	{
		//	Whole match - positive pattern.
		if(!preg_match('/^[-_0-9a-zA-Z]+$/', $name) ){
			//	Negative pattern.
			/* @var $match array */
			preg_match('/([^-_0-9a-zA-Z])/u', $name, $match);
			OP()->Error("This character is not supported: {$match[1]}");
			return false;
		}

		//	...
		$list = self::List();

		//	...
		if( array_search($name, $list) !== false ){
			OP()->Error("This wallet name is already exists: {$name}");
			return false;
		}

		//	...
		$params = [
			$name,       // wallet_name
			false,       // disable_private_keys
			false,       // blank
			$passphrase, // passphrase
			false        // avoid_reuse
		];

		//	...
		$result = Curl('createwallet', $params)['result'];
		return isset($result['name']) ? true : false ;
	}

	/**	Load wallet.
	 *
	 * @created    2026-01-25
	 * @param      string     $name
	 * @return     bool
	 */
	static public function Load( string $wallet ) : bool
	{
		return true;
	}

	/**	Get already loaded wallets list.
	 *
	 * @created    2026-01-25
	 * @return     array
	 */
	static public function Loaded() : array
	{
		return Curl('listwallets')['result'] ?? [];
	}

	/**	Get all exists wallets list.
	 *
	 * @created    2026-01-26
	 * @return     array
	 */
	static public function List() : array
	{
		//	...
		$result = Curl('listwalletdir')['result'] ?? [];

		//	...
		$list = [];
		foreach( $result['wallets'] as $item ){
			$list[] = $item['name'];
		}

		//	...
		return $list;
	}

	static public function Lock()
	{
		//	walletlock
	}

	static public function UnLock()
	{
		//	walletpassphrase
	}

	static public function Backup()
	{
		//	backupwallet
	}

	static public function ChangePassphrase()
	{
		//	walletpassphrasechange
	}
}
