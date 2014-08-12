<?php
// @todo license headers to be added

namespace CrateTest\PDO;

use Crate\PDO\PDO;
use PHPUnit_Framework_TestCase;

/**
 * Tests for {@see \Crate\PDO\PDO}
 *
 * @coverDefaultClass \Crate\PDO\PDO
 *
 * @group unit
 */
class PDOTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var PDO
     */
    protected $pdo;

    protected function setUp()
    {
        $this->pdo = new PDO('http://localhost:8080', null, null, []);
    }

    /**
     * @covers ::__construct
     */
    public function testInstantiation()
    {
        $pdo = new PDO('http://localhost:1234/', null, null, []);

        $this->assertInstanceOf('Crate\PDO\PDO', $pdo);
        $this->assertInstanceOf('PDO', $pdo);
    }

    /**
     * @covers ::__construct
     */
    public function testInstantiationWithInvalidOptions()
    {
        $this->setExpectedException('Crate\PDO\Exception\InvalidArgumentException');

        new PDO('http://localhost:1234/', null, null, 'a invalid value');
    }

    public function attributeProvider()
    {
        return [
            // Overriden attributes
            [PDO::ATTR_STATEMENT_CLASS, ['Crate\PDO\PDOStatement']],
            [PDO::ATTR_PERSISTENT, false],
            [PDO::ATTR_DRIVER_NAME, 'crate'],
            [PDO::ATTR_PREFETCH, false],
            [PDO::ATTR_AUTOCOMMIT, true],
            [PDO::ATTR_CLIENT_VERSION, PDO::VERSION],
            [PDO::ATTR_TIMEOUT, 5],

            // Inherited
            [PDO::ATTR_ERRMODE],
            [PDO::ATTR_DEFAULT_FETCH_MODE],
        ];
    }

    /**
     * @dataProvider attributeProvider
     *
     * @param string $attribute
     * @param mixed  $overrideValue
     */
    public function testDefaultAttributesMatchPDO($attribute, $overrideValue = null)
    {
        if ($overrideValue !== null) {
            $this->assertEquals($overrideValue, $this->pdo->getAttribute($attribute));
        } else {

            $PDO = new \PDO('sqlite::memory:');
            $this->assertEquals($PDO->getAttribute($attribute), $this->pdo->getAttribute($attribute));
        }
    }

    /**
     * @covers ::prepare
     */
    public function testPrepareReturnsAPDOStatement()
    {
        $statement = $this->pdo->prepare('SELECT * FROM tweets');
        $this->assertInstanceOf('Crate\PDO\PDOStatement', $statement);
    }

    /**
     * @covers ::getAvailableDrivers
     */
    public function testAvailableDriversContainsCrate()
    {
        $this->assertContains('crate', PDO::getAvailableDrivers());
    }

    /**
     * @covers ::beginTransaction
     */
    public function testBeginTransactionThrowsUnsupportedException()
    {
        $this->setExpectedException('Crate\PDO\Exception\UnsupportedException');
        $this->pdo->beginTransaction();
    }

    /**
     * @covers ::commit
     */
    public function testCommitThrowsUnsupportedException()
    {
        $this->setExpectedException('Crate\PDO\Exception\UnsupportedException');
        $this->pdo->commit();
    }

    /**
     * @covers ::rollback
     */
    public function testRollbackThrowsUnsupportedException()
    {
        $this->setExpectedException('Crate\PDO\Exception\UnsupportedException');
        $this->pdo->rollBack();
    }

    /**
     * @covers ::inTransaction
     */
    public function testInTransactionThrowsUnsupportedException()
    {
        $this->setExpectedException('Crate\PDO\Exception\UnsupportedException');
        $this->pdo->inTransaction();
    }

    /**
     * @covers ::lastInsertId
     */
    public function testLastInsertIdThrowsUnsupportedException()
    {
        $this->setExpectedException('Crate\PDO\Exception\UnsupportedException');
        $this->pdo->lastInsertId();
    }
}
