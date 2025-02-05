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

    /* public function testCreateFromArrayWithValidData(): void
    {
        $data = ['name' => 'John Doe', 'age' => 30];
        $applicant = ApplicantFactory::createFromArray($data);

        $this->assertInstanceOf(Applicant::class, $applicant);
        $this->assertSame('John Doe', $applicant->getName());
        $this->assertSame(30, $applicant->getAge());
    }

    public function testCreateFromArrayWithEmptyArray(): void
    {
        $data = [];
        $applicant = ApplicantFactory::createFromArray($data);

        $this->assertInstanceOf(Applicant::class, $applicant);
        $this->assertSame('', $applicant->getName());
        $this->assertNull($applicant->getAge());
    }

    public function testCreateFromArrayWithMissingFields(): void
    {
        $data = ['name' => 'Jane Doe'];
        $applicant = ApplicantFactory::createFromArray($data);

        $this->assertInstanceOf(Applicant::class, $applicant);
        $this->assertSame('Jane Doe', $applicant->getName());
        $this->assertNull($applicant->getAge());
    }

    public function testCreateFromArrayWithInvalidDataType(): void
    {
        $data = 'invalid data type';
        $this->expectException(TypeError::class);
        ApplicantFactory::createFromArray($data);
    } */
}
