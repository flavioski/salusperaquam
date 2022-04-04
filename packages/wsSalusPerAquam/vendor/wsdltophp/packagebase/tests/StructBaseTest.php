<?php

namespace WsdlToPhp\PackageBase\Tests;

class StructBaseTest extends TestCase
{
    /**
     *
     */
    public function testSetState()
    {
        $object = new StructObject();
        $object
            ->setBar('foo')
            ->setFoo('bar');
        $this->assertEquals($object, StructObject::__set_state(array(
            'bar' => 'foo',
            'foo' => 'bar',
        )));
    }
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetStateException()
    {
        StructObject::__set_state(array(
            'bar' => 'foo',
            'foo' => 'bar',
            'sample' => 'data',
        ));
    }
    /**
     *
     */
    public function testSetGet()
    {
        $object = new StructObject();
        $object
            ->setBar('foo')
            ->setFoo('bar');
        $this->assertSame('foo', $object->_get('bar'));
    }
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetGetWithException()
    {
        $object = new StructObject();
        $object
            ->setBar('foo')
            ->setFoo('bar');
        $object->_get('sample');
    }
    public function testJsonSerialize()
    {
        $object = new StructObject();
        $object
            ->setBar('foo')
            ->setFoo('bar');
        $this->assertSame([
            'foo' => 'bar',
            'bar' => 'foo',
        ], $object->jsonSerialize());
    }
    /**
     *
     */
    public function test__toStringMustReturnTheClassNameOfTheInstance()
    {
        $this->assertSame('WsdlToPhp\PackageBase\Tests\StructObject', (string) new StructObject());
    }
}
