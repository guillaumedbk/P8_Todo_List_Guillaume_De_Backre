<?php

namespace Tests\Unit\Voter;

use App\Entity\Task;
use App\Entity\User;
use App\Security\Voter\TaskVoter;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class TaskVoterTest extends WebTestCase
{
    /**
     * @dataProvider dataForTaskVoterTesting
     */
    public function testTaskVoter($user, $askedMethod, $task, $expectedResponse)
    {
        $token = new UsernamePasswordToken($user, 'main');
        $voter = new TaskVoter();
        self::assertEquals($expectedResponse, $voter->vote($token, $task, $askedMethod));
    }

    public function dataForTaskVoterTesting()
    {
        //Allowed
        $userAllowed = $this->createMock(User::class);
        $userAllowed->method('getId')->willReturn(1);
        $task = new Task();
        $task->setUser($userAllowed);

        //Not Allowed
        $userNotAllowed = $this->createMock(User::class);
        $userNotAllowed->method('getId')->willReturn(2);

        return [
            [$userAllowed, ['EDIT'], $task, VoterInterface::ACCESS_GRANTED],
            [$userAllowed, ['DELETE'], $task, VoterInterface::ACCESS_GRANTED],
            [$userAllowed, ['VIEW'], $task, VoterInterface::ACCESS_GRANTED],
            [$userNotAllowed, ['EDIT'], $task, VoterInterface::ACCESS_DENIED],
            [$userNotAllowed, ['DELETE'], $task, VoterInterface::ACCESS_DENIED],
            [$userNotAllowed, ['VIEW'], $task, VoterInterface::ACCESS_DENIED],
            ['user', ['VIEW'], $task, VoterInterface::ACCESS_DENIED],
            [$userAllowed, ['VIEW'], 'task', VoterInterface::ACCESS_ABSTAIN],
            [$userAllowed, ['NOTEXIST'], $task, VoterInterface::ACCESS_ABSTAIN],
            [$userAllowed, ['VIEW'], null, VoterInterface::ACCESS_ABSTAIN]
        ];
    }
}
