# UK VAT ID Validator

> [!WARNING]
> This plugin has not yet publicly released. The installation instructions may not work.

Out of the box, Craft Commerce supports basic formatting validation for VAT IDs in the European Union. This plugin provides additional checks to ensure tax rules are correctly applied for customers with _UK_ VAT IDs:

- Tax rules are given a setting that allows merchants to disqualify taxes when a valid UK Tax ID (VAT number) is provided during checkout;
- UK Tax IDs are validated directly with *<abbr title="HM Revenue & Customs">HMRC</abbr>’s official API*;

### How it Works

Enabling the `validateBusinessTaxIdAsVatId` in Craft Commerce 4.8 (globally) or **Validate Business Tax ID as VAT ID** in Craft Commerce 5.x (in one or more stores) tells Commerce to perform cursory validation of the **Organization Tax ID** field on addresses used for tax calculation. This functionality is available to _all_ Commerce installations.

As tax rules are evaluated in the process of [calculating order totals](https://craftcms.com/docs/commerce/5.x/system/orders-carts.html#order-totals), _UK VAT ID Validator_ ensures the provided VAT ID is both correctly formatted and actually exists, using the HMRC API.

> [!NOTE]  
> Make sure your `useBillingAddressForTax` config setting (Commerce 4.8) or **Use Billing Address For Tax** store setting (Commerce 5.x) agrees with how you want to handle taxes in each market!

## Requirements

- Craft CMS 4.x or 5.x
- Craft Commerce 4.8 or 5.3 and later
- HMRC credentials for [Version 2 of the HMRC VAT API](https://developer.service.hmrc.gov.uk/api-documentation/docs/api/service/vat-registered-companies-api/2.0)

## Installation

You can install this plugin from the [Plugin Store](#from-the-plugin-store) or with [Composer](#with-composer). Once installed, follow the [configuration instructions](#configuration)!

#### From the Plugin Store

Go to the **Plugin Store** in your project’s [control panel](https://craftcms.com/docs/5.x/system/control-panel.html#plugin-store) and search for “UK VAT ID Validator.” On the plugin’s page, press **Install**.

#### With Composer

Open your terminal and run the following commands:

```bash
# Go to the project directory:
cd /path/to/my-project

# Require the plugin package with Composer:
ddev composer require craftcms/commerce-uk-vat-id-validator

# Tell Craft to install the plugin:
ddev craft plugin/install uk-vat-id-validator
```

## Configuration

### Step 1: Configure Plugin Settings

Navigate to **Settings → UK Tax ID Validator** in the control panel, and fill in the following fields:  

- **HMRC Client ID**: Obtain this from the HMRC Developer Hub.
- **HMRC Client Secret**: Provided alongside your HMRC Client ID.
- **Sandbox Mode**: Enable for testing (disables live API calls).

> [!WARNING]  
> Whenever possible, use an [environment variable](https://craftcms.com/docs/5.x/configure.html#control-panel-settings) so that these secrets don’t leak into your project config files!

### Optional Step 2: Enable Validation on Tax Rates

- Navigate to **Commerce → Tax Rates**.
- Edit a tax rate and check **UK VAT ID** under the **Disqualify with valid business tax ID?** option.
- Save the tax rate.

When a valid UK tax ID is provided in the address, the tax will not be applied.

## Support

Please report any issues on GitHub. Need help or have a question? Email us at **support@craftcms.com**.

:lemon:
