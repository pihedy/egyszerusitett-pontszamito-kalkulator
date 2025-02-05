<?php declare(strict_types=1);

use \PHPUnit\Framework\TestCase;

use \App\Factories\ApplicantFactory;

use \App\Services\Applicant;

class ApplicantFactoryTest extends TestCase
{
    public function testCreateFromGlobalWithNoGlobalData(): void
    {
        $this->assertSame([], ApplicantFactory::createFromGlobal());
    }

    public function testCreateFromGlobalWithNonExampleData(): void
    {
        $GLOBALS['testData'] = ['name' => 'Valami'];
        $GLOBALS['exampleData1'] = ['name' => 'Kecske'];

        $result = ApplicantFactory::createFromGlobal();

        $this->assertArrayHasKey('exampleData1', $result);
        $this->assertArrayNotHasKey('testData', $result);

        $this->assertInstanceOf(Applicant::class, $result['exampleData1']);
    }

    public function testCreateFromGlobalWithMultipleExampleData(): void
    {
        $GLOBALS['exampleData1'] = ['name' => 'Valami'];
        $GLOBALS['exampleData2'] = ['name' => 'Kecske'];

        $result = ApplicantFactory::createFromGlobal();

        $this->assertCount(2, $result);

        $this->assertArrayHasKey('exampleData1', $result);
        $this->assertArrayHasKey('exampleData2', $result);

        $this->assertInstanceOf(Applicant::class, $result['exampleData1']);
        $this->assertInstanceOf(Applicant::class, $result['exampleData2']);
    }
}
