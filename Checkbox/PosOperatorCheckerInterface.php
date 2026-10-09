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

/**
 * Чи поточний актор — оператор POS (менеджер за касою), якому дозволено
 * запускати capture фіскального чека.
 *
 * POS-шлюзи фізично обслуговує персонал: покупець не має сам тригерити чек на
 * касі, тож CaptureAction гейтиться цим портом. Хто саме вважається оператором —
 * рішення хоста (адмін-сесія, роль, зміна) і в контракт не входить.
 */
interface PosOperatorCheckerInterface
{
    public function isPosOperator(): bool;
}
