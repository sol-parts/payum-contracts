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

namespace SolParts\PayumContracts\Exception;

use Payum\Core\Exception\LogicException;

/**
 * Шлюз не зміг зупинити платіж: рахунок уже поза станом, з якого його можна інвалідувати,
 * бо триває платіжна сесія або платіж завершено.
 *
 * Контракт між пакетами шлюзів і хостом. Кожен шлюз розпізнає це за власним протокольним
 * кодом (monobank — `INVOICE_ALREADY_USED` у CancelAction), а хост ухвалює рішення за КЛАСОМ,
 * не знаючи кодів: перехід замовлення в «Скасовано» блокується, бо рахунок лишається
 * оплатним — інакше клієнт бачить скасоване замовлення, за яке за хвилину спишуться гроші.
 *
 * Не помилка оплати: спільний Payum-extension хоста цей клас пропускає, щоб не писати
 * «оплата не пройшла» тому, хто щойно натиснув «скасувати».
 */
class PaymentSessionInProgressException extends LogicException
{
}
