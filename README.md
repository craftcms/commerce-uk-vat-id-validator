# UK VAT ID Validator

⚠️ Not yet publicly released.

Adds a UK VAT ID validator to Craft Commerce.

Automatic Tax Rate Validation adds a checkbox to Craft Commerce tax rates, allowing merchants to disqualify taxes when a valid UK Tax ID (VAT number) is provided during checkout. The plugin integrates directly with *HMRC’s official API* to verify VAT numbers. You will need HMRC API keys to use this plugin.

## Requirements

- Craft CMS 4.x or 5.x  
- Craft Commerce 4.8 or 5.3 and later  
- HMRC credentials for Version 2 of the HMRC VAT API  

## Installation

You can install this plugin from the Plugin Store or with Composer.

#### From the Plugin Store

Go to the Plugin Store in your project’s Control Panel and search for “UK VAT ID Validator.” Then press “Install.”

#### With Composer

Open your terminal and run the following commands:

```bash
# Go to the project directory
cd /path/to/my-project.test

# Tell Composer to load the plugin
composer require craftcms/commerce-uk-vat-id-validator

# Tell Craft to install the plugin
./craft plugin/install uk-vat-id-validator
```

## Configuration

### Step 1: Configure Plugin Settings

Navigate to **Settings → UK Tax ID Validator** in the control panel.  
Fill in the following fields:  

- **HMRC Client ID**: Obtain this from the HMRC Developer Hub.  
- **HMRC Client Secret**: Provided alongside your HMRC Client ID.  
- **Sandbox Mode**: Enable for testing (disables live API calls).  

> **Note**  
> If you set `validateBusinessTaxIdAsVatId` in Craft Commerce 4.8 or `validateOrganizationTaxIdAsVatId` in Craft Commerce 5.8 to `true` in store settings, the organization tax ID field will be validated with the validator via the API when an address is saved. This is not required for tax rate validation to work as described below.

### Optional Step 2: Enable Validation on Tax Rates

- Navigate to **Commerce → Tax Rates**.  
- Edit a tax rate and check **UK VAT ID** under the "Disqualify with valid business tax ID?" option.  
- Save the tax rate.  

When a valid UK tax ID is provided in the address, the tax will not be applied.

## Support

Report issues on GitHub.  
Questions? Email **support@craftcms.com**.
