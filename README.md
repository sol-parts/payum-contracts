# Payum Contracts

Shared contracts between a [Payum](https://github.com/Payum/Payum) host
application and the `sol-parts/payum-*` gateway packages — request primitives
and narrow ports that keep gateway packages free of host code, in the spirit
of `symfony/contracts`.

## Installation

```bash
composer require sol-parts/payum-contracts
```

## What is inside

### `Request\Api\DoCapture`

Server-side finalization of a held payment: charging the blocked amount
without a browser involved. It is a separate request rather than the core
`Capture` because `Capture` serves the customer flow and may reply with an
`HttpRedirect` to the bank page — fatal when dispatched from CLI or cron.

The host dispatches `DoCapture`; the gateway package that supports two-stage
payments handles it with its `Action/Api/DoCaptureAction`.

```php
use SolParts\PayumContracts\Request\Api\DoCapture;

$gateway->execute(new DoCapture($paymentDetails));
```

### `Request\GetApi` + `Action\GetApiAction`

Extract the configured `Api` instance from an already built Payum gateway
without reflection over the private `Gateway::$apis`. The action is not
registered in gateway factories permanently — the caller (typically a console
command doing direct API calls, e.g. refund or status outside the Payum
workflow) attaches it at runtime:

```php
use SolParts\PayumContracts\Action\GetApiAction;
use SolParts\PayumContracts\Request\GetApi;

$gateway = $payum->getGateway($gatewayName);
if ($gateway instanceof \Payum\Core\Gateway) {
    $gateway->addAction(new GetApiAction(ConcreteApi::class));
}

$gateway->execute($request = new GetApi());
$api = $request->getApi(); // instanceof ConcreteApi
```

### `Checkbox` ports

Narrow ports for POS / fiscal-receipt gateways built on
[Checkbox](https://checkbox.ua/) AirCheck:

- `AircheckSessionProviderInterface` — prepares one browser-to-POS AirCheck
  session (websocket endpoint, terminal, cash register).
- `PosOperatorCheckerInterface` — answers whether the current actor is a POS
  operator allowed to trigger a fiscal receipt capture. POS gateways are
  operated by staff, so capture actions gate on this port; who qualifies as
  an operator is entirely the host's decision.

The host implements the ports and exposes them in its DI container. With
Symfony, declare the interface-to-service alias explicitly — autowiring does
not create it on its own:

```yaml
# config/services.yaml
services:
    SolParts\PayumContracts\Checkbox\PosOperatorCheckerInterface: '@app.pos_operator_checker'
```

## License

Released under the [MIT License](LICENSE).
