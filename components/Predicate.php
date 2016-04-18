<?php

namespace app\components;

class Predicate {
    const WSDL_NAMESPACE = "https://adwords.google.com/api/adwords/cm/v201603";
    const XSI_TYPE = "Predicate";
    /**
     * @var string
     */
    public $field;
    /**
     * @var tnsPredicateOperator
     */
    public $operator;
    /**
     * @var string[]
     */
    public $values;
    /**
     * Gets the namesapce of this class
     * @return the namespace of this class
     */
    public function getNamespace() {
        return self::WSDL_NAMESPACE;
    }
    /**
     * Gets the xsi:type name of this class
     * @return the xsi:type name of this class
     */
    public function getXsiTypeName() {
        return self::XSI_TYPE;
    }
    public function __construct($field = null, $operator = null,
                                $values = null) {
        $this->field = $field;
        $this->operator = $operator;
        $this->values = $values;
    }
}