<?php

namespace craftcms\ukvatidvalidator\models;

use craft\base\Model;
use craft\helpers\App;

/**
 * UK Vat ID Validator settings
 *
 * @property-write string $hmrcClientId
 * @property-write string $hmrcClientSecret
 * @property-write bool|string $isSandbox
 */
class Settings extends Model
{

    /**
     * @var string|null
     */
    private ?string $_hmrcClientId = null;

    /**
     * @var string|null
     */
    private ?string $_hmrcClientSecret = null;

    /**
     * Whether to use the test-api.service.hmrc.gov.uk or api.service.hmrc.gov.uk url to communicate with API.
     *
     * @var bool
     */
    private bool $_isSandbox = true;

    /**
     * @param bool $parse
     * @return string
     */
    public function getHmrcClientId(bool $parse = true): string
    {
        return ($parse ? App::parseEnv($this->_hmrcClientId) : $this->_hmrcClientId) ?? '';
    }

    /**
     * @param bool $parse
     * @return string
     */
    public function getHmrcClientSecret(bool $parse = true): string
    {
        return ($parse ? App::parseEnv($this->_hmrcClientSecret) : $this->_hmrcClientSecret) ?? '';
    }

    /**
     * @param bool $parse
     * @return bool
     */
    public function getIsSandbox(bool $parse = true): bool
    {
        return $this->_isSandbox;
    }

    /**
     * @param string $value
     * @return void
     */
    public function setHmrcClientId(string $value): void
    {
        $this->_hmrcClientId = $value;
    }

    /**
     * @param string $value
     * @return void
     */
    public function setHmrcClientSecret(string $value): void
    {
        $this->_hmrcClientSecret = $value;
    }

    /**
     * @param bool|string $value
     * @return void
     */
    public function setIsSandbox(bool|string $value): void
    {
        $this->_isSandbox = $value;
    }
}
