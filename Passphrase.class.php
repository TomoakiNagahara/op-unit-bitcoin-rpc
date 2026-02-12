<?php
/**	op-unit-bitcoin-rpc:/Passphrase.class.php
 *
 * @created    2026-02-13
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

/**	Include
 *
 */
require_once(__DIR__.'/function/Curl.php');

/**	Bitcoin-RPC
 *
 */
class Passphrase
{
	/**	trait.
	 *
	 */
	use OP_CORE;
	use OP_CI;

	public static function Encrypt( string $wallet, string $passphrase ) : bool
	{
		$params = [
			$passphrase,
		];
		$json = Curl('encryptwallet', $params, $wallet);
		D($json);
		return true;
	}

	/**	Change passphrase.
	 *
	 * @param string $wallet
	 * @param string $old
	 * @param string $new
	 */
	public static function Change( string $wallet, string $old, string $new ) : bool
	{
		$params = [
			$old,
			$new,
		];
		$json = Curl('walletpassphrasechange', $params, $wallet);
		D($json);
		return true;
	}
}
