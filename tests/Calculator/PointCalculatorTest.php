<?php declare(strict_types=1);

use \PHPUnit\Framework\TestCase;

use \App\Factories\ApplicantFactory;
use \App\Calculator\PointCalculator;
use \App\Services\Applicant;
use \App\Domain\Major;

class PointCalculatorTest extends TestCase
{
    public function testCalculateWithNoApplicants()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('No applicants found.');

        $calculator = new PointCalculator([]);
        $calculator->calculate();
    }

    public function testCalculateWithUnknownMajor()
    {
        $Applicant = $this->createMock(Applicant::class);
        $Applicant->method('getMajor')->willReturn($this->createMock(Major::class));
        $Applicant->method('getMajor')->willReturn(new class {
            public function getKey() { return 'unknown'; }
        });

        $this->setUpApplicants([$Applicant]);

        $calculator = new PointCalculator([]);
        $result = $calculator->calculate();

        $this->assertEquals('There is no known major in the applicant\'s field of study.', $result[0]);
    }

    public function testCalculateWithMissingMandatorySubjects()
    {
        $Applicant = $this->createMock(Applicant::class);
        $Applicant->method('getMajor')->willReturn($this->createMock(Major::class));
        $Applicant->method('hasMissingSubjects')->willReturn(true);
        $Applicant->method('getMissingSubjects')->willReturn(['Math', 'Physics']);

        $this->setUpApplicants([$Applicant]);

        $calculator = new PointCalculator([]);
        $result = $calculator->calculate();

        $this->assertEquals('Missing mandatory subjects: Math, Physics', $result[0]);
    }

    public function testCalculateWithLowerSubjects()
    {
        $Applicant = $this->createMock(Applicant::class);
        $Applicant->method('getMajor')->willReturn($this->createMock(Major::class));
        $Applicant->method('hasLowerSubjects')->willReturn(true);
        $Applicant->method('getLowerSubjects')->willReturn(['Chemistry']);

        $this->setUpApplicants([$Applicant]);

        $calculator = new PointCalculator([]);
        $result = $calculator->calculate();

        $this->assertEquals('Too low a percentage in the final exam subject: Chemistry', $result[0]);
    }

    public function testCalculateWithMissingMandatorySubjectResult()
    {
        $Applicant = $this->createMock(Applicant::class);
        $Applicant->method('getMajor')->willReturn($this->createMock(Major::class));
        $Applicant->method('getMajor')->willReturn(new class {
            public function getMandatorySubject() { return 'Math'; }
            public function getOptionalSubjects() { return ['Biology']; }
        });
        $Applicant->method('get')->willReturn([]);

        $this->setUpApplicants([$Applicant]);

        $calculator = new PointCalculator([]);
        $result = $calculator->calculate();

        $this->assertEquals('Error: Missing mandatory subject result.', $result[0]);
    }

    public function testCalculateWithValidApplicant()
    {
        $Applicant = $this->createMock(Applicant::class);
        $Applicant->method('getMajor')->willReturn($this->createMock(Major::class));
        $Applicant->method('getMajor')->willReturn(new class {
            public function getMandatorySubject() { return 'Math'; }
            public function getOptionalSubjects() { return ['Biology']; }
        });
        $Applicant->method('get')->willReturn([
            ['nev' => 'Math', 'eredmeny' => '80%'],
            ['nev' => 'Biology', 'eredmeny' => '90%'],
        ]);
        $Applicant->method('hasMissingSubjects')->willReturn(false);
        $Applicant->method('hasLowerSubjects')->willReturn(false);

        $this->setUpApplicants([$Applicant]);

        $calculator = new PointCalculator([]);
        $result = $calculator->calculate();

        $this->assertArrayHasKey('base_points', $result[0]);
        $this->assertArrayHasKey('bonus_points', $result[0]);
        $this->assertArrayHasKey('total_points', $result[0]);
    }

    private function setUpApplicants(array $Applicants)
    {
        $factoryMock = $this->createMock(ApplicantFactory::class);
        $factoryMock->method('createFromGlobal')->willReturn($Applicants);
    }
}
