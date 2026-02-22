<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/auth.php';

class LoginTest extends TestCase
{
    public function testValidLogin()
    {
        $email = "testuser@gmail.com";
        $password = "123456";

        $result = loginUser($email, $password);

        $this->assertTrue($result);
    }

    public function testInvalidLogin()
    {
        $email = "testuser@gmail.com";
        $password = "wrongpass";

        $result = loginUser($email, $password);

        $this->assertFalse($result);
    }
}
?>