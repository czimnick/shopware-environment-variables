<?php declare(strict_types=1);

namespace ShopwareEnvironmentVariables\Source\Config;

use Shopware\Components\Plugin\ConfigReader;
use Shopware\Models\Shop\Shop;

class Reader implements ConfigReader
{
    /**
     * @var ConfigReader
     */
    private $configReader;

    /**
     * @var array
     */
    private $customEnvironmentVariables;

    /**
     * @param ConfigReader $configReader
     * @param array $customEnvironmentVariables
     */
    public function __construct(ConfigReader $configReader, array $customEnvironmentVariables)
    {
        $this->configReader = $configReader;
        $this->customEnvironmentVariables = [];
        if (isset($customEnvironmentVariables['plugins'])) {
            $this->customEnvironmentVariables = $customEnvironmentVariables['plugins'];
        }
    }

    /**
     * @param string $pluginName
     * @param Shop|null $shop
     * @return array
     */
    public function getByPluginName($pluginName, Shop $shop = null): array
    {
        $result = $this->configReader->getByPluginName($pluginName, $shop);
        $shopId = $shop !== null ? $shop->getId() : 1;

        if (!isset($this->customEnvironmentVariables[$shopId][$pluginName])) {
            return $result;
        }

        return array_merge($result, $this->customEnvironmentVariables[$shopId][$pluginName]);
    }
}
