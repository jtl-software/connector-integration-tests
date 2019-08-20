<?php

namespace Jtl\Connector\IntegrationTests;

use Doctrine\Common\Collections\ArrayCollection;
use jtl\Connector\Linker\IdentityLinker;
use jtl\Connector\Mapper\IPrimaryKeyMapper;
use jtl\Connector\Model\Ack;
use jtl\Connector\Model\DataModel;
use jtl\Connector\Serializer\JMS\SerializerBuilder;
use PHPUnit\Framework\TestCase;
use Jtl\Connector\Client\Client;

\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader('class_exists');

abstract class ConnectorTestCase extends TestCase
{
    /**
     * @var Client
     */
    protected static $client = null;
    
    /**
     * @var IPrimaryKeyMapper
     */
    protected $primaryKeyMapper = null;
    
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        
        if (!function_exists('getPrimaryKeyMapper')) {
            throw new \InvalidArgumentException('Required function getPrimaryKeyMapper is missing! Please define it in the bootstrap!');
        }
        
        $this->primaryKeyMapper = getPrimaryKeyMapper();
        
        parent::__construct($name, $data, $dataName);
    }
    
    /**
     * @param IPrimaryKeyMapper $primaryKeyMapper
     * @return ConnectorTestCase
     */
    public function setPrimaryKeyMapper($primaryKeyMapper)
    {
        if (!$primaryKeyMapper instanceof IPrimaryKeyMapper) {
            throw new \InvalidArgumentException();
        }
        
        $this->primaryKeyMapper = $primaryKeyMapper;
        
        return $this;
    }
    
    /**
     * @return IPrimaryKeyMapper
     */
    public function getPrimaryKeyMapper()
    {
        return $this->primaryKeyMapper;
    }
    
    public abstract function getIgnoreArray();
    
    /**
     * @return Client
     */
    protected function getConnectorClient()
    {
        if (self::$client === null) {
            self::$client = $this->generateClient();
            
            return self::$client;
        }
        
        return self::$client;
    }
    
    /**
     * @return Client
     */
    private function generateClient()
    {
        if (!defined('TEST_DIR')) {
            throw new \InvalidArgumentException('Const TEST_DIR is not defined! Please define it in the bootstrap file!');
        }
        
        $config = json_decode(file_get_contents(TEST_DIR . '/test-config.json'));
        
        return new Client($config->connector_token, $config->connector_url);
    }
    
    /**
     * @param string $json
     * @param string $controllerName
     * @return array|\JMS\Serializer\scalar|mixed|object
     */
    protected function jsonToCoreModels(string $json, string $controllerName)
    {
        $ns = 'ArrayCollection<jtl\\Connector\\Model\\' . $controllerName . '>';
        $serializer = SerializerBuilder::create();
        
        return $serializer->deserialize($json, $ns, 'json');
    }
    
    /**
     * @param string $controllerName
     * @param int $limit
     * @param string $endpointId
     * @return DataModel|DataModel[]|null
     */
    protected function pullCoreModels(string $controllerName, int $limit = 100, string $endpointId = "")
    {
        if ($endpointId === "") {
            return $this->getConnectorClient()->pull($controllerName, $limit);
        }
        
        $models = $this->getConnectorClient()->pull($controllerName, 999999);
        foreach ($models as $model) {
            if ($model->getId()->getEndpoint() === $endpointId) {
                return $model;
            }
        }
        
        return null;
    }
    
    /**
     * @param string $controllerName
     * @param string $endpointId
     * @param int $hostId
     */
    protected function deleteModel(string $controllerName, string $endpointId, int $hostId)
    {
        $ack = new Ack();
        $ack->setIdentities(new ArrayCollection([$controllerName => [$endpointId, $hostId]]));
        $this->getConnectorClient()->ack($ack);
        $className = 'jtl\Connector\Model\\'.$controllerName;
        $this->getConnectorClient()->delete($controllerName, [(new $className)->setId($endpointId, $hostId)]);
    }
    
    /**
     * @param array $models
     * @param bool $clearLinkings
     * @return array
     * @throws \ReflectionException
     * @throws \jtl\Connector\Exception\LinkerException
     */
    protected function pushCoreModels(array $models, bool $clearLinkings)
    {
        if (empty($models)) {
            return [];
        }
        
        $controllerName = (new \ReflectionClass($models[0]))->getShortName();
        $client = $this->getConnectorClient();
        
        $models = $client->push($controllerName, $models);
        
        if ($clearLinkings) {
            foreach ($models as $convertedModel) {
                $this->primaryKeyMapper->delete(
                    $convertedModel->getId()->getEndpoint(),
                    $convertedModel->getId()->getHost(),
                    IdentityLinker::getInstance()->getType($controllerName, 'id')
                );
            }
        }
        
        return $models;
    }
    
    /**
     * @param DataModel $actual
     * @param DataModel $expected
     * @param array|null $assertArray
     */
    protected function assertCoreModel(DataModel $expected, DataModel $actual)
    {
        $ignoreArray = $this->getIgnoreArray();
        
        $actualArray = json_decode($actual->toJson(), true);
        $expectedArray = json_decode($expected->toJson(), true);
        
        foreach ($ignoreArray as $value) {
            $path = explode('.', $value);
            if (count($path) > 1) {
                $tmpActual = &$actualArray;
                $tmpExpected = &$expectedArray;
                
                for ($i = 0; $i < count($path) - 1; $i++) {
                    $tmpActual = &$tmpActual[$path[$i]];
                    $tmpExpected = &$tmpExpected[$path[$i]];
                }
                unset($tmpActual[$path[$i]]);
                unset($tmpExpected[$path[$i]]);
                
            } else {
                unset($actualArray[$path[0]]);
                unset($expectedArray[$path[0]]);
            }
        }
        
        $this->assertEquals($expectedArray, $actualArray);
    }
}
