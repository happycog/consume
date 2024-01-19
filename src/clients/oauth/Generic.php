<?php
namespace verbb\consume\clients\oauth;

use craft\helpers\App;
use verbb\consume\base\OAuthClient;

use verbb\auth\providers\Generic as GenericProvider;

class Generic extends OAuthClient
{
    // Static Methods
    // =========================================================================

    public static function getOAuthProviderClass(): string
    {
        return GenericProvider::class;
    }


    // Properties
    // =========================================================================

    public static string $providerHandle = 'generic';
    public ?string $url = null;
    public ?string $authorizationUrl = null;
    public ?string $tokenUrl = null;
    public ?string $grant = 'authorization_code';


    // Public Methods
    // =========================================================================

    public function defineRules(): array
    {
        $rules = parent::defineRules();

        $rules[] = [
            ['url'], 'required', 'when' => function($model) {
                return $model->enabled;
            },
        ];

        return $rules;
    }

    public function getGrant(): string
    {
        return $this->grant;
    }

    public function getOAuthProviderConfig(): array
    {
        $config = parent::getOAuthProviderConfig();
        $config['urlAuthorize'] = App::parseEnv($this->authorizationUrl);
        $config['urlAccessToken'] = App::parseEnv($this->tokenUrl);
        $config['urlResourceOwnerDetails'] = App::parseEnv($this->url);
        $config['scopes'] = $this->scopes;
        $config['scopeSeparator'] = $this->scopeSeparator;
        $config['baseApiUrl'] = App::parseEnv($this->url);

        // Merge in any additional config options set at the template level
        $config = array_merge($config, $this->getProviderOptions());

        return $config;
    }

}
