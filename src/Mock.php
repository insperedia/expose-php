<?php

namespace Insperedia\Expose;

class Mock
{
    var $className;
    var $listenedMethods = [];
    var $instance;


    function __construct($className) {
        $this->className = $className;
    }

    public function createClass() {
        $methods = '';
        foreach ($this->listenedMethods as $methodName => $methodBody) {
            $methods .= $this->generateMethod($methodName, $methodBody, $this->className );
        }
        $randomClassName = $this->generateRandomString();

        $instance = null;
        $classCode = '
            class '.$randomClassName.' extends  '.$this->className.' {
                var $mockCalls = [];
            
                '.$methods.'
                
                function getCallCount($methodName) {
                    if (isset($this->mockCalls[$methodName])) {
                        return $this->mockCalls[$methodName];
                    } else {
                        return 0;
                    }
                }
            }
          
        ';

        eval($classCode);

        return $randomClassName;
    }

    public function instanciate() {
        $className = $this->createClass();
        $constructorParamString = $this->constructorParamsString(func_get_args());
        $instance = null;
        $constructor = '  $instance = new '.$className.'('.$constructorParamString.');';
        eval($constructor);
        return $instance;
    }

    private function constructorParamsString($params) {
        $params = array_map(function($value) {
                return '"'.$value.'"';
        }, $params);
        return implode(', ', $params);
    }

    private function generateMethod($methodName, $methodBody, $className) {
        $method = new \ReflectionMethod($className, $methodName);

        $parameters = $method->getParameters();
        $parameterNames = [];
        foreach ($parameters as $parameter) {
            $parameterNames[] = '$'.$parameter->getName();
        }
        $hasMethodBody = $methodBody ? 'true' : 'false';
        $methodName = $method->getName();
        $parameters = implode(',', $parameterNames);
        $methodString = 'public function '.$methodName.'('.$parameters.') {
            $methodName = "'.$methodName.'";
            if (isset($this->mockCalls[$methodName]))
                $this->mockCalls[$methodName]++;
            else 
                $this->mockCalls[$methodName] = 1;
           
            if ('.$hasMethodBody.') {
                '.$methodBody.'
            }
            else
                return parent::$methodName('.$parameters.');            
         }';

        return $methodString;

    }

    function listenMethod($methodName, $methodBody = false) {
        $this->listenedMethods[$methodName] = $methodBody;
    }

    public function getCallCount($methodName, $instance) {
        return $instance->getCallcount($methodName);
    }

    private function generateRandomString($length = 20) {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString.time();
    }

}