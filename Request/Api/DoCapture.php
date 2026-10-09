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

namespace SolParts\PayumContracts\Request\Api;

use Payum\Core\Request\Generic;

/**
 * Серверна фіналізація hold-платежу — списання заблокованої суми БЕЗ участі браузера.
 *
 * Окремий request, а не штатний {@see \Payum\Core\Request\Capture}, бо Capture у наших
 * шлюзах обслуговує клієнтський флоу і відповідає редиректами (HttpRedirect на сторінку
 * банку) — з CLI/крону фіналізації це було б аварією. DoCapture диспатчить хост, а
 * підтримує `Action/Api/DoCaptureAction` відповідного шлюзу.
 *
 * Пакет PayumContracts — контракт хост↔шлюз (за зразком symfony/contracts): спільні
 * request-примітиви живуть тут, щоб шлюзові пакети не залежали від коду хоста.
 *
 * @author Andrii Didenko <andrii@didenko.dev>
 */
class DoCapture extends Generic
{
}
