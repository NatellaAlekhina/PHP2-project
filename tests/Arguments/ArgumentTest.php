<?php

namespace Test\Arguments;

use App\Argument\Argument;
use App\Exceptions\ArgumentException;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/..'.'/../public/autoloader_runtime.php';

class ArgumentTest extends TestCase
{
    public function testItReturnArgumentValueByName(): void
    {
        //Podgotovka
        $argument = new Argument(['some_key' => 'some_value']);

        //Deistvie
        $value = $argument->get('some_key');

        //Proverka
        $this->assertEquals('some_value', $value);
    }

    public function testItReturnArgumentStringValueByName(): void
    {
        //Podgotovka
        $argument = new Argument(['some_key' => 1]);

        //Deistvie
        $value = $argument->get('some_key');

        //Proverka
        $this->assertSame('1', $value);
    }

    public function testItThrowExcepptionWhenArgumentIsAbsent(): void
    {
        //Podgotovka
        $argument = new Argument([]);

        //Type ogidaemogo iskluycheniya
        $this->expectException(ArgumentException::class);

        //Sobitie
        $this->expectExceptionMessage("No such argument: some_key".PHP_EOL);
        //deistvie k polucheniu oshibki
        $argument->get('some_key');
    }

    public function argumentsProvider(): iterable
    {
        return [
            ['some_string', 'some_string'],
            [' some_string', 'some_string'],
            [' some_string ', 'some_string'],
            [123, '123'],
            [12.3, '12.3'],
        ];
    }


    /**
     * @dataProvider  argumentsProvider
     */
    public function testItConvertsArgumentsToStrings(
        $inputValue,
        $expectedValue
    ): void {
        //podstavlyaem pervoe znachenie iz testovogo nabora
        $argument = new Argument(['some_key' => $inputValue]);
        $value = $argument->get('some_key');

        //sveryaem so vtorim znacheniem iz testovogo nabora
        $this->assertEquals($expectedValue, $value);
    }
}