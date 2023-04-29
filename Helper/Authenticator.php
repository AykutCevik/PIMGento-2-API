<?php

namespace Pimgento\Api\Helper;

use Akeneo\Pim\ApiClient\AkeneoPimClientInterface;
use Akeneo\PimEnterprise\ApiClient\AkeneoPimEnterpriseClientInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Akeneo\Pim\ApiClient\AkeneoPimClientBuilder;
use Pimgento\Api\Helper\Config as ConfigHelper;
use Http\Factory\Guzzle\StreamFactory;
use Http\Factory\Guzzle\RequestFactory;
use Symfony\Component\HttpClient\Psr18Client;

/**
 * Class Authenticator
 *
 * @category  Class
 * @package   Pimgento\Api\Helper
 * @author    Agence Dn'D <contact@dnd.fr>
 * @copyright 2018 Agence Dn'D
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      https://www.pimgento.com/
 */
class Authenticator extends AbstractHelper
{
    /**
     * This variable contains a ConfigHelper
     *
     * @var ConfigHelper $configHelper
     */
    protected $configHelper;

    /**
     * Authenticator constructor
     *
     * @param Context $context
     * @param ConfigHelper $configHelper
     */
    public function __construct(
        Context $context,
        ConfigHelper $configHelper
    ) {
        parent::__construct($context);

        $this->configHelper = $configHelper;
    }

    /**
     * Retrieve an authenticated akeneo php client
     *
     * @return AkeneoPimClientInterface|AkeneoPimEnterpriseClientInterface|false
     */
    public function getAkeneoApiClient()
    {
        /** @var string $baseUri */
        $baseUri = $this->configHelper->getAkeneoApiBaseUrl();
        /** @var string $clientId */
        $clientId = $this->configHelper->getAkeneoApiClientId();
        /** @var string $secret */
        $secret = $this->configHelper->getAkeneoApiClientSecret();
        /** @var string $username */
        $username = $this->configHelper->getAkeneoApiUsername();
        /** @var string $password */
        $password = $this->configHelper->getAkeneoApiPassword();

        if (!$baseUri || !$clientId || !$secret || !$username || !$password) {
            return false;
        }
        /** @var AkeneoPimClientBuilder $akeneoClientBuilder */
        $akeneoClientBuilder = new AkeneoPimClientBuilder($baseUri);
        $akeneoClientBuilder->setHttpClient(new Psr18Client());
        $akeneoClientBuilder->setStreamFactory(new StreamFactory());
        $akeneoClientBuilder->setRequestFactory(new RequestFactory());

        return $akeneoClientBuilder->buildAuthenticatedByPassword($clientId, $secret, $username, $password);
    }
}
