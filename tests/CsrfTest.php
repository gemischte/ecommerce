<?php

use PHPUnit\Framework\TestCase;
use App\Security\Csrf;

final class CsrfTest extends TestCase
{
    protected function setUp(): void
    {
        $_SESSION = [];
    }
    
    public function testCreateTokenReturnsString(): void
    {
        $token = Csrf::csrf_token();
        $this->assertIsString($token);
        $this->assertSame(64, strlen($token));
        $this->assertMatchesRegularExpression('/^[a-f0-9]{64}$/', $token);
    }

    public function testTokenIsReused(): void
    {
        $token = Csrf::csrf_token();
        $token2 = Csrf::csrf_token();
        $this->assertSame($token, $token2);
    }
    
    public function testCsrfTokenRegeneratesWhenSessionTokenInvalid(): void
    {
        $_SESSION['csrf_token'] = 'invalid token';
        $token = Csrf::csrf_token();
        $this->assertSame(64, strlen($token));
        $this->assertMatchesRegularExpression('/^[a-f0-9]{64}$/', $token);
        $this->assertNotSame('invalid token', $token);
    }

    public function testCsrfTokenRegeneratesWhenWrongLengthHex(): void
    {
        $_SESSION['csrf_token'] = str_repeat('a', 63);
        $token = Csrf::csrf_token();
        $this->assertSame(64, strlen($token));
    }

    public function testCsrfFieldHtml(): void
    {
        $field = Csrf::csrf_field();
        $this->assertStringContainsString('name="csrf_token"', $field);
        $this->assertStringContainsString('type="hidden"', $field);
        $this->assertMatchesRegularExpression('/value="[a-f0-9]{64}"/', $field);
    }

    public function testCsrfFieldContainsCurrentSessionToken(): void
    {
        $token = Csrf::csrf_token();
        $field = Csrf::csrf_field();
        $this->assertStringContainsString($token, $field);
    }

    public function testVerifyTokenValid(): void
    {
        $token = Csrf::csrf_token();
        $this->assertTrue(Csrf::verify_token($token));
    }

    public function testVerifyTokenInvalid(): void
    {
        Csrf::csrf_token();
        $this->assertFalse(Csrf::verify_token("Invalid Token"));
    }

    public function testVerifyTokenNullReturnsFalse(): void
    {
        Csrf::csrf_token();
        $this->assertFalse(Csrf::verify_token(null));
    }

    public function testVerifyTokenEmptyStringReturnsFalse(): void
    {
        Csrf::csrf_token();
        $this->assertFalse(Csrf::verify_token(''));
    }

    public function testVerifyTokenFailsWithoutSessionToken(): void
    {
        $this->assertFalse(Csrf::verify_token(str_repeat('0', 64)));
    }

    public function testVerifyTokenWrongHexSameLengthReturnsFalse(): void
    {
        Csrf::csrf_token();
        $other = str_repeat('b', 64);
        $this->assertFalse(Csrf::verify_token($other));
    }

    public function testVerifyTokenNonHexSixtyFourCharsReturnsFalse(): void
    {
        Csrf::csrf_token();
        $bogus = str_repeat('g', 64);
        $this->assertFalse(Csrf::verify_token($bogus));
    }
}
