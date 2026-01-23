<?php
/**	op-unit-bitcoin-rpc:/index.php
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
namespace OP;

/**	Include
 *
 */
require_once(__DIR__.'/Bitcoin-RPC.class.php');
require_once(__DIR__.'/function/Curl.php');

//	...
return new \OP\UNIT\BITCOIN\RPC();
