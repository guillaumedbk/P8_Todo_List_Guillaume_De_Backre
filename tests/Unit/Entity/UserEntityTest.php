<?php

namespace Tests\Unit\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Test\ConstraintViolationAssertion;

class UserEntityTest extends TestCase
{
    /**
     * @dataProvider attributesForUser
     */
    public function testUserEntityIsValid($username, $expectedValue): void
    {
        $user = new User();
        $user->setUsername($username);

        //ASSERTS
        $this->assertEquals($expectedValue, $user->getUserName());
    }

    public function attributesForUser()
    {
        return [
            ['Toto', 'Toto'], //Simple value
        ];
    }
}
