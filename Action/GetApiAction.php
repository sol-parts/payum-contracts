<?php

/*
 * This file is part of Sol.parts
 *
 * (c) SOLPARTS LLC (EDRPOU 46143031) <mail@sol.parts>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SolParts\PayumContracts\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\ApiAwareTrait;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Exception\UnsupportedApiException;
use Payum\Core\Gateway;
use SolParts\PayumContracts\Request\GetApi;

/**
 * Generic-action, що заповнює {@see GetApi} інстансом потрібного Api-класу
 * з вже сконфігурованого Payum-gateway.
 *
 * Не реєструється у factories платіжних шлюзів постійно. Натомість викликач
 * (зазвичай адмін-команда) додає його у `$gateway` runtime:
 *
 * ```php
 * $gateway = $this->payum->getGateway($name);
 * if ($gateway instanceof \Payum\Core\Gateway) {
 *     $gateway->addAction(new GetApiAction(ConcreteApi::class));
 * }
 *
 * $gateway->execute($req = new GetApi());
 * $api = $req->getApi(); // instanceof ConcreteApi
 * ```
 *
 * `addAction()` живе на конкретному `Payum\Core\Gateway`, а не на
 * `GatewayInterface`, тому instanceof-перевірка обов'язкова.
 *
 * Підбір Api у `Gateway::execute()` стандартний: Payum по черзі дзвонить
 * `$action->setApi($api)` для кожного зареєстрованого у gateway Api;
 * `ApiAwareTrait::setApi()` кидає {@see UnsupportedApiException}, якщо
 * тип не збігається з `apiClass` з конструктора — тоді Payum переходить
 * до наступного Api. Якщо жоден не підходить — кине LogicException.
 *
 * @author Andrii Didenko <andrii@didenko.dev>
 */
final class GetApiAction implements ActionInterface, ApiAwareInterface
{
    use ApiAwareTrait;

    /** @var object|null */
    protected $api;

    /**
     * @param class-string $apiClass FQN очікуваного Api-класу (наприклад,
     *                               `SolParts\PayumLiqPay\Api::class`).
     *                               Payum підбере у gateway саме той Api,
     *                               що `instanceof` цього класу.
     */
    public function __construct(string $apiClass)
    {
        $this->apiClass = $apiClass;
    }

    public function execute($request): void
    {
        if (!$request instanceof GetApi) {
            throw RequestNotSupportedException::createActionNotSupported($this, $request);
        }

        $request->setApi($this->api ?? throw new \LogicException('Payum API was not injected into GetApiAction.'));
    }

    public function supports($request): bool
    {
        return $request instanceof GetApi;
    }
}
