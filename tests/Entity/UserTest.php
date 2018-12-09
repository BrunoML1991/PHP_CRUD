<?php
/**
 * PHP version 7.2
 * tests/Entity/UserTest.php
 *
 * @category EntityTests
 * @package  MiW\Results\Tests
 * @author   Javier Gil <franciscojavier.gil@upm.es>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://www.etsisi.upm.es ETS de Ingeniería de Sistemas Informáticos
 */

namespace MiW\Results\Tests\Entity;

use MiW\Results\Entity\User;
use PHPUnit\Framework\TestCase;

/**
 * Class UserTest
 *
 * @package MiW\Results\Tests\Entity
 * @group   users
 */
class UserTest extends TestCase
{
    /**
     * @var User $user
     */
    private $user;

    /**
     * Sets up the fixture.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->user = new User();
    }

    /**
     * @covers \MiW\Results\Entity\User::__construct()
     */
    public function testConstructor(): void
    {
        $this->user = new User('Bruno','pr@pr.com','aaa',false,true);
        assertEquals('Bruno',$this->user->getUsername());
        assertEquals('pr@pr.com',$this->user->getEmail());
        assertEquals(true,$this->user->validatePassword('aaa'));
        assertEquals(false,$this->user->isEnabled());
        assertEquals(true,$this->user->isAdmin());
    }

    /**
     * @covers \MiW\Results\Entity\User::getId()
     */
    public function testGetId(): void
    {
        assertEquals(0,$this->user->getId());
        $this->user->setId(4);
        assertEquals(4,$this->user->getId());
    }

    /**
     * @covers \MiW\Results\Entity\User::setUsername()
     * @covers \MiW\Results\Entity\User::getUsername()
     */
    public function testGetSetUsername(): void
    {
        assertEquals('',$this->user->getUsername());
        $this->user->setUsername('prueba');
        assertEquals('prueba',$this->user->getUsername());
    }

    /**
     * @covers \MiW\Results\Entity\User::getEmail()
     * @covers \MiW\Results\Entity\User::setEmail()
     */
    public function testGetSetEmail(): void
    {
        assertEquals('',$this->user->getEmail());
        $this->user->setEmail('pr@pr.com');
        assertEquals('pr@pr.com',$this->user->getEmail());
    }

    /**
     * @covers \MiW\Results\Entity\User::setEnabled()
     * @covers \MiW\Results\Entity\User::isEnabled()
     */
    public function testIsSetEnabled(): void
    {
        assertEquals(true,$this->user->isEnabled());
        $this->user->setEnabled(false);
        assertEquals(false,$this->user->isEnabled());
    }

    /**
     * @covers \MiW\Results\Entity\User::setIsAdmin()
     * @covers \MiW\Results\Entity\User::isAdmin
     */
    public function testIsSetAdmin(): void
    {
        assertEquals(false,$this->user->isAdmin());
        $this->user->setIsAdmin(true);
        assertEquals(true,$this->user->isAdmin());
    }

    /**
     * @covers \MiW\Results\Entity\User::setPassword()
     * @covers \MiW\Results\Entity\User::validatePassword()
     */
    public function testSetValidatePassword(): void
    {
        $this->user->setPassword('aaa');
        assertEquals(true,$this->user->validatePassword('aaa'));
        assertEquals(false,$this->user->validatePassword('ccc'));
    }

    /**
     * @covers \MiW\Results\Entity\User::__toString()
     */
    public function testToString(): void
    {
        $this->user->setUsername('pr');
        assertEquals('pr',$this->user->__toString());
    }

    /**
     * @covers \MiW\Results\Entity\User::jsonSerialize()
     */
    public function testJsonSerialize(): void
    {
        assertArrayHasKey('id',$this->user->jsonSerialize());
        $prueba = json_encode($this->user->jsonSerialize());
        assertJson($prueba);
    }
}
