<?php

use PHPUnit\Framework\TestCase;
use App\Utils\Helper;

final class HelperTest extends TestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();
        $_GET = [];
        unset($_SERVER['PHP_SELF']);
    }

    public function testCreateUidReturnsValidFormat(): void
    {
        $uid = Helper::create_uid();
        $this->assertMatchesRegularExpression('/^user_[a-f0-9]{32}$/', $uid);
    }

    public function testSelectLangBuildsCorrectUrl(): void
    {
        $_GET = ['page' => 'home'];
        $_SERVER['PHP_SELF'] = 'index.php';

        $url = Helper::select_lang('en');

        $this->assertIsString($url);
        $this->assertStringStartsWith('index.php?', $url);
        $this->assertStringContainsString('page=home', $url);
        $this->assertStringContainsString('lang=en', $url);
    }

    public function testAllCountriesReturnsExpectedData(): void
    {
        $expected = [
            ["name" => "United States", "code" => "US"]
        ];

        $result = $this->createMock(\mysqli_result::class);
        $result->method("fetch_all")->willReturn($expected);
        $stmt = $this->createMock(\mysqli_stmt::class);
        $stmt->method("execute")->willReturn(true);
        $stmt->method("get_result")->willReturn($result);
        $conn = $this->createMock(\mysqli::class);
        $conn->method("prepare")->willReturn($stmt);
        $countries = Helper::all_countries($conn);
        $this->assertSame($expected, $countries);
    }
    
}
