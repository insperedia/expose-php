Expose mocking library
======================

Expose mocking library allows to create mocks, stubs and expose internal methods.
Unique feature is dymamic injection of test support code into target class. This eliminate needs to have any aditional code in a class under the test.


**Usage**
```
    $mock = new \Insperedia\Expose\Mock('\app\classes\TestClass');
    $mock->listenMethod('internalMethod', 'return false;');

    $mockedTestClass = $mock->createClass();
    $mockedTestClass->externalMethod("someValue");

    $this->assertEquals(1, $mockedTestClass->getCallCount('internalMethod'));
```