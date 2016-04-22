<?php
namespace app\components;

class ReportDefinition {

    const WSDL_NAMESPACE = "https://adwords.google.com/api/adwords/cm/v201603";
    const XSI_TYPE = "ReportDefinition";

    /**
     * @var integer
     */
    public $id;

    /**
     * @var Selector
     */
    public $selector;

    /**
     * @var string
     */
    public $reportName;

    /**
     * @var tnsReportDefinitionReportType
     */
    public $reportType;

    /**
     * @var boolean
     */
    public $hasAttachment;

    /**
     * @var tnsReportDefinitionDateRangeType
     */
    public $dateRangeType;

    /**
     * @var tnsDownloadFormat
     */
    public $downloadFormat;

    /**
     * @var string
     */
    public $creationTime;

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

    public function __construct(
        $id = null,
        $selector = null,
        $reportName = null,
        $reportType = null,
        $hasAttachment = null,
        $dateRangeType = null,
        $downloadFormat = null,
        $creationTime = null
    ) {
        $this->id = $id;
        $this->selector = $selector;
        $this->reportName = $reportName;
        $this->reportType = $reportType;
        $this->hasAttachment = $hasAttachment;
        $this->dateRangeType = $dateRangeType;
        $this->downloadFormat = $downloadFormat;
        $this->creationTime = $creationTime;
    }
}