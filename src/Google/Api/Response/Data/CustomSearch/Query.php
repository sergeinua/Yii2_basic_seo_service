<?php

/*
 * This file is part of the Google APIs PHP Clients package.
 *
 * (c) Stephen Melrose <me@stephenmelrose.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Google\Api\Response\Data\CustomSearch;

use Google\Api\Response\Data\AbstractData;

/**
 * Query is a single query from the Google Custom Search API.
 *
 * @author Stephen Melrose <me@stephenmelrose.co.uk>
 */
class Query extends AbstractData
{
    /**
     * @var string
     */
    protected $title;
    
    /**
     * @var integer
     */
    protected $totalResults;
    
    /**
     * @var string
     */
    protected $searchTerms;
    
    /**
     * @var integer
     */
    protected $count;
    
    /**
     * @var integer
     */
    protected $startIndex;
    
    /**
     * @var integer
     */
    protected $startPage;
    
    /**
     * @var string
     */
    protected $language;
    
    /**
     * @var string
     */
    protected $inputEncoding;
    
    /**
     * @var string
     */
    protected $outputEncoding;
    
    /**
     * @var string
     */
    protected $safe;
    
    /**
     * @var string
     */
    protected $cx;
    
    /**
     * @var string
     */
    protected $cref;
    
    /**
     * @var string
     */
    protected $sort;
    
     /** @var string
     */
    protected $searchType;
    
     /** @var string
     */
    protected $imgSize;
    
    /**
     * @var string
     */
    protected $imgColorType;
    
    /**
     * @var string
     */
    protected $imgDominantColor;
    
    /**
     * @var string
     */
    protected $imgType;
    
    /**
     * Gets the description.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * Gets the total number of search results.
     *
     * @return integer
     */
    public function getTotalNumberOfResults()
    {
        return (int) $this->totalResults;
    }
    
    /**
     * Gets the search expression.
     *
     * @return string
     */
    public function getQuery()
    {
        return $this->searchTerms;
    }
    
    /**
     * Gets the number of search results in this set.
     *
     * @return string
     */
    public function getNumberOfResults()
    {
        return $this->count;
    }
    
    /**
     * Gets the start index of this set of search results.
     *
     * @return integer
     */
    public function getStartIndex()
    {
        return $this->startIndex;
    }
    
    /**
     * Gets the page number of this set of search results.
     *
     * @return integer
     */
    public function getPage()
    {
        return $this->startPage;
    }
    
    /**
     * Gets the language of the search results.
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }
    
    /**
     * Gets the character encoding of the request.
     *
     * @return string
     */
    public function getInputEncoding()
    {
        return $this->inputEncoding;
    }
    
    /**
     * Gets the character encoding of the search results.
     *
     * @return string
     */
    public function getOutputEncoding()
    {
        return $this->outputEncoding;
    }
    
    /**
     * Gets the safety level of the search results.
     *
     * @return string
     */
    public function getSafetyLevel()
    {
        return $this->safe;
    }
    
    /**
     * Gets the custom search engine ID used in the request.
     *
     * @return string
     */
    public function getCustomSearchEngineId()
    {
        return $this->cx;
    }
    
    /**
     * Gets the custom search engine spec URL used in the request.
     *
     * @return string
     */
    public function getCustomSearchEngineSpecUrl()
    {
        return $this->cref;
    }
    
    /**
     * Gets the what the search results were sorted by.
     *
     * @return string
     */
    public function getSortedBy()
    {
        return $this->sort;
    }
    
    
    /**
     * Gets the search type ('image' or 'normal' for dafault search).
     *
     * @return string
     */
    public function getSearchType()
    {
        return $this->searchType;
    }
    
     /**
     * Gets the img size (icon, small, medium, large, xlarge, xxlarge, huge)
     *
     * @return string
     */
    public function getImgSize()
    {
        return $this->imgSize;
    }
    
    /**
     * Gets the image color type.
     *
     * @return string
     */
    public function getImgColorType()
    {
        return $this->imgColorType;
    }   
  
    /**
     * Gets the image dominant color.
     *
     * @return string
     */
    public function getImgDominantColor()
    {
        return $this->imgDominantColor;
    }
        
    /**
     * Gets the image type.
     *
     * @return string
     */
    public function getImgType()
    {
        return $this->imgType;
    }
}
