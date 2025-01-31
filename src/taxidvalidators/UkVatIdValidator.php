<?php
/**
 * @link https://craftcms.com/
 * @copyright Copyright (c) Pixel & Tonic, Inc.
 * @license https://craftcms.github.io/license/
 */

namespace craft\commerce\ukvatidvalidator\taxidvalidators;

use Craft;
use craft\commerce\base\TaxIdValidatorInterface;
use craft\commerce\ukvatidvalidator\Plugin;
use craft\helpers\StringHelper;

/**
 * UkVatIdValidator checks if a given VAT ID is valid in the UK.
 * Test numbers: https://github.com/hmrc/vat-registered-companies-api/blob/main/public/api/conf/1.0/test-data/vrn.csv
 * @author Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @since 1.0.0
 */
class UkVatIdValidator implements TaxIdValidatorInterface
{
    private \GuzzleHttp\Client $_guzzleClient;

    /**
     * @var string
     */
    private string $sandboxApiUrl = 'https://test-api.service.hmrc.gov.uk';

    /**
     * @var string
     */
    private string $productionApiUrl = 'https://api.service.hmrc.gov.uk';


    public function __construct(\GuzzleHttp\Client $guzzleClient = null)
    {
        $this->_guzzleClient = $guzzleClient ?: \Craft::createGuzzleClient();
    }

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('commerce', 'UK VAT ID');
    }

    /**
     * @inheritdoc
     */
    public function validateFormat(string $idNumber): bool
    {
        $idNumber = ltrim($idNumber, 'GB');
        $correctLength = StringHelper::length($idNumber) == 9 || StringHelper::length($idNumber) == 12;
        // should only be numbers
        $validChars = (bool)preg_match('/^\d+$/', $idNumber);
        return $correctLength && $validChars;
    }

    private function getOauthAccessToken()
    {
        if ($cachedToken = Craft::$app->getCache()->get('commerce:ukVat:accessToken')) {
            return $cachedToken;
        }

        $clientId = Plugin::getInstance()->getSettings()->getHmrcClientId();
        $clientSecret = Plugin::getInstance()->getSettings()->getHmrcClientSecret();
        $accessToken = false;

        try {
            $response = $this->_guzzleClient->post($this->sandboxApiUrl . '/oauth/token', [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'form_params' => [
                    'client_id' => $clientId,
                    'client_secret' => $clientSecret,
                    'grant_type' => 'client_credentials',
                    'scope' => 'read:vat',
                ],
            ]);

            $accessToken = json_decode($response->getBody()->getContents(), true)['access_token'];
        } catch (\Exception $e) {
            Craft::error('Error getting UK VAT ID access token: ' . $e->getMessage());
        }

        if ($accessToken) {
            Craft::$app->getCache()->set('commerce:ukVat:accessToken', $accessToken, 7200);
        }

        return $accessToken;
    }

    /**
     * @inheritdoc
     */
    public function validateExistence(string $idNumber): bool
    {
        $accessToken = $this->getOauthAccessToken();
        $testMode = Plugin::getInstance()->getSettings()->getIsSandbox();
        $idNumber = ltrim($idNumber, 'GB');

        $url = $testMode ? $this->sandboxApiUrl : $this->productionApiUrl;
        $url = $url . "/organisations/vat/check-vat-number/lookup/{$idNumber}";

        try {
            $result = $this->_guzzleClient->get($url, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $accessToken,
                        'Accept' => 'application/vnd.hmrc.2.0+json',
                    ],
                ])->getStatusCode() == 200;
        } catch (\Exception $e) {
            \Craft::error('Error validating UK VAT ID: ' . $e->getMessage());
            $result = false;
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function validate(string $idNumber): bool
    {
        return $this->validateFormat($idNumber) && $this->validateExistence($idNumber);
    }

    /**
     * @inheritdoc
     */
    public static function isEnabled(): bool
    {
        return Plugin::getInstance()->getSettings()->getHmrcClientId() && Plugin::getInstance()->getSettings()->getHmrcClientSecret();
    }
}
