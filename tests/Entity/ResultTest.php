<?php
/**
 * PHP version 7.2
 * tests/Entity/ResultTest.php
 *
 * @category EntityTests
 * @package  MiW\Results\Tests
 * @author   Javier Gil <franciscojavier.gil@upm.es>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://www.etsisi.upm.es ETS de Ingeniería de Sistemas Informáticos
 */

namespace MiW\Results\Tests\Entity;

use MiW\Results\Entity\Result;
use MiW\Results\Entity\User;

/**
 * Class ResultTest
 *
 * @package MiW\Results\Tests\Entity
 */
class ResultTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var User $user
     */
    private $user;

    /**
     * @var Result $result
     */
    private $result;

    private const USERNAME = 'uSeR ñ¿?Ñ';
    private const POINTS = 2018;

    /**
     * @var \DateTime $time
     */
    private $time;

    /**
     * Sets up the fixture.
     * This method is called before a test is executed.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->user = new User();
        $this->user->setUsername(self::USERNAME);
        $this->time = new \DateTime('now');
        $this->result = new Result(
            self::POINTS,
            $this->user,
            $this->time
        );
    }

    /**
     * Implement testConstructor
     *
     * @covers \MiW\Results\Entity\Result::__construct()
     * @covers \MiW\Results\Entity\Result::getId()
     * @covers \MiW\Results\Entity\Result::getResult()
     * @covers \MiW\Results\Entity\Result::getUser()
     * @covers \MiW\Results\Entity\Result::getTime()
     *
     * @return void
     */
    public function testConstructor(): void
    {
        assertEquals(self::POINTS, $this->result->getResult());
        assertEquals(self::USERNAME, $this->result->getUser());
        assertEquals($this->time, $this->result->getTime());
    }

    /**
     * Implement testGet_Id().
     *
     * @covers \MiW\Results\Entity\Result::getId()
     * @return void
     */
    public function testGetId(): void
    {
        assertEquals(0, $this->result->getId());
    }

    /**
     * Implement testUsername().
     *
     * @covers \MiW\Results\Entity\Result::setResult
     * @covers \MiW\Results\Entity\Result::getResult
     * @return void
     */
    public function testResult(): void
    {
        $this->result->setResult(10);
        assertEquals(10, $this->result->getResult());
    }

    /**
     * Implement testUser().
     *
     * @covers \MiW\Results\Entity\Result::setUser()
     * @covers \MiW\Results\Entity\Result::getUser()
     * @return void
     */
    public function testUser(): void
    {
        $this->user = new User();
        $this->user->setUsername('aaa');
        assertEquals('aaa', $this->result->getUser());
    }

    /**
     * Implement testTime().
     *
     * @covers \MiW\Results\Entity\Result::setTime
     * @covers \MiW\Results\Entity\Result::getTime
     * @return void
     */
    public function testTime(): void
    {
        $timeNow = new \DateTime('now');
        $this->result->setTime($timeNow);
        assertEquals($timeNow, $this->result->getTime());
    }

    /**
     * Implement testTo_String().
     *
     * @covers \MiW\Results\Entity\Result::__toString
     * @return void
     */
    public function testToString(): void
    {
        assertEquals(sprintf(
            '%3d - %3d - %22s - %s',
            $this->result->getId(),
            $this->result->getResult(),
            $this->result->getUser(),
            $this->result->getTime()->format('Y-m-d H:i:s')
        ),
            $this->result->__toString());
    }

    /**
     * Implement testJson_Serialize().
     *
     * @covers \MiW\Results\Entity\Result::jsonSerialize
     * @return void
     */
    public function testJsonSerialize(): void
    {
        assertArrayHasKey('id', $this->result->jsonSerialize());
        $prueba = json_encode($this->result->jsonSerialize());
        assertJson($prueba);
    }
}
