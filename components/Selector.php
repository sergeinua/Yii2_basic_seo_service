<?php
namespace app\components;

class Selector {

    const WSDL_NAMESPACE = "https://adwords.google.com/api/adwords/cm/v201603";
    const XSI_TYPE = "Selector";

    /**
     * @var string[]
     */
    public $fields;

    /**
     * @var Predicate[]
     */
    public $predicates;

    /**
     * @var DateRange
     */
    public $dateRange;

    /**
     * @var OrderBy[]
     */
    public $ordering;

    /**
     * @var Paging
     */
    public $paging;

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

    public function __construct($fields = null,
                                $predicates = null,
                                $dateRange = null,
                                $ordering = null,
                                $paging = null
    ) {
        $this->fields = $fields;
        $this->predicates = $predicates;
        $this->dateRange = $dateRange;
        $this->ordering = $ordering;
        $this->paging = $paging;
    }
}