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

namespace SolParts\PayumContracts\Tests\Action;

use Payum\Core\Gateway;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SolParts\PayumContracts\Action\GetApiAction;
use SolParts\PayumContracts\Request\GetApi;

/**
 * Виконуваний спец патерну «дістати Api-інстанс із уже сконфігурованого gateway»
 * (див. PHPDoc GetApiAction): викликач реєструє action runtime через
 * Gateway::addAction() і забирає Api запитом GetApi — без рефлексії над
 * приватним Gateway::$apis.
 */
#[CoversClass(GetApiAction::class)]
#[CoversClass(GetApi::class)]
final class GetApiActionTest extends TestCase
{
    /**
     * Наскрізний прогін через справжній Payum\Core\Gateway, а не мок: саме
     * Gateway::execute() виконує підбір Api (по черзі викликає setApi() і
     * пропускає інстанси, чий тип не збігається з apiClass конструктора).
     * З двох зареєстрованих Api запит має отримати рівно той, чий клас
     * передали в конструктор action-а. Падіння означає, що викликач
     * (типово — консольна команда прямих викликів API шлюзу: refund/status
     * повз Payum-workflow) отримає чужий Api або null і піде з ним у банк.
     */
    public function testReturnsExactlyTheApiMatchingRequestedClassFromConfiguredGateway(): void
    {
        $expectedApi = new class {
            public string $marker = 'expected';
        };
        $foreignApi = new \stdClass();

        $gateway = new Gateway();
        $gateway->addApi($foreignApi);
        $gateway->addApi($expectedApi);
        $gateway->addAction(new GetApiAction($expectedApi::class));

        $gateway->execute($request = new GetApi());

        self::assertSame($expectedApi, $request->getApi());
    }

    /**
     * Виклик execute() повз gateway (Api ніхто не інжектнув) має бути гучним
     * LogicException, а не мовчазним null у запиті: null викликач розіменує
     * вже далеко від причини — на реальному виклику API шлюзу.
     */
    public function testExecuteWithoutInjectedApiFailsLoudlyInsteadOfReturningNull(): void
    {
        $action = new GetApiAction(\stdClass::class);

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Payum API was not injected');

        $action->execute(new GetApi());
    }
}
