<?php

/*
 * This file is part of SOLPARTS
 *
 * (c) SOLPARTS LLC (EDRPOU 46143031) <mail@sol.parts>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SolParts\PayumContracts\Checkbox;

/** Prepares one browser-to-POS AirCheck session. */
interface AircheckSessionProviderInterface
{
    public const STATUS_NEW = 'new';
    public const STATUS_PENDING = 'pending';
    public const STATUS_CAPTURED = 'captured';

    /**
     * @return array{
     *     websocket_url: string,
     *     websocket_token: string,
     *     terminal_id: string,
     *     terminal_label: string,
     *     cash_register_id: string,
     *     dev_pos?: array{scenario: string, delay_ms: int}
     * }
     */
    public function prepareSession(int $firmId = 0): array;
}
