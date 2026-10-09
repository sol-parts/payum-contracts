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

namespace SolParts\PayumContracts\Request;

/**
 * Generic-request для прямого отримання Api-інстанса з Payum-gateway без
 * рефлексії над `Payum\Core\Gateway::$apis`.
 *
 * Слідує патерну `Get*`-requests з Payum core ({@see \Payum\Core\Request\GetHttpRequest},
 * {@see \Payum\Core\Request\GetCurrency}, …) — порожній data-holder, який
 * заповнюється відповідним action-ом через мутатор.
 *
 * Спарений з {@see \SolParts\PayumContracts\Action\GetApiAction}: викликач
 * реєструє action у gateway-instance runtime через
 * {@see \Payum\Core\Gateway::addAction()}, задаючи очікуваний клас Api через
 * конструктор action-а, після чого `$gateway->execute(new GetApi())` віддає
 * потрібний інстанс. Такий «опціональний» action не лежить у factories
 * самих платіжних шлюзів — пакети залишаються чистими від адмін-tooling.
 *
 * Типовий викликач — адмін-команда хоста для прямих викликів API шлюзу
 * (refund/status повз Payum-workflow).
 *
 * @author Andrii Didenko <andrii@didenko.dev>
 */
final class GetApi
{
    private ?object $api = null;

    public function setApi(object $api): void
    {
        $this->api = $api;
    }

    public function getApi(): ?object
    {
        return $this->api;
    }
}
