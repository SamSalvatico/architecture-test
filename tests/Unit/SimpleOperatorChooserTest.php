<?php

namespace Tests\Unit;

use App\Exceptions\TourImport\OperatorNotFoundException;
use App\Repositories\OperatorToursProcessor\OperatorChooser\SimpleOperatorChooser;
use App\Repositories\OperatorToursProcessor\Operators\FirstOperator\FirstOperatorProcessor;
use App\Repositories\OperatorToursProcessor\Operators\SecondOperator\SecondOperatorProcessor;
use App\Repositories\TourOperators\ToImportToursContent;
use Tests\TestCase;

class SimpleOperatorChooserTest extends TestCase
{
    /**
     * @dataProvider operatorTypesProvider
     */
    public function testGetProcessorReturnsCorrectValues(string $operatorId, string $expectedClass): void
    {
        $chooser = new SimpleOperatorChooser();

        $processor = $chooser->getProcessor(new ToImportToursContent($operatorId, [], "123"));

        $this->assertEquals(get_class($processor), $expectedClass);
    }

    public function testGetProcessorThrowsExceptionIfOperatorNotFound(): void
    {
        $chooser = new SimpleOperatorChooser();

        $this->expectException(OperatorNotFoundException::class);

        $chooser->getProcessor(new ToImportToursContent("I DO NOT EXIST, I HOPE", [], "123"));
    }

    /**
     * @return array<string,array>
     */
    public function operatorTypesProvider(): array
    {
        return [
            "first" => ["first", FirstOperatorProcessor::class],
            "second" => ["second", SecondOperatorProcessor::class],
        ];
    }
}
