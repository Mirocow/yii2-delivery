<?php
namespace mirocow\delivery\services\dpd\client;

use mirocow\delivery\services\dpd\client\interfaces\AuthInterface;
use mirocow\delivery\exceptions\ExceptionServiceClient;
use Yii;
use yii\helpers\Json;

/**
 * Class SoapClient
 * @package mirocow\delivery\services\dpd\client
 */
class SoapClient extends \SoapClient
{
    /**
     * Параметры авторизации
     * @var array
     */
    protected $auth = [];

    /**
     * @brief параметры для SoapClient
     * @var array
     */
    protected $soapOptions = [
        'connection_timeout' => 20,
    ];

    /**
     * SoapClient constructor.
     * @param string $wsdl
     * @param AuthInterface $auth
     * @param array $options
     */
    public function __construct(string $wsdl, AuthInterface $auth, array $options = [])
    {
        try {
            $this->auth = ['clientNumber' => $auth->getClientNumber(), 'clientKey' => $auth->getSecretKey()];
            if (empty($this->auth['clientNumber']) || empty($this->auth['clientKey'])) {
                throw new \Exception('DPD: Authentication data is not provided');
            }
            parent::__construct($auth->resolveWsdl($wsdl), array_merge($this->soapOptions, $options));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @brief выполняет запрос к внешнему API
     * @param string $method
     * @param array $params
     * @param string $wrap
     * @param bool $keys
     * @return array|bool|mixed|string
     * @throws \Exception
     */
    public function invoke($method, array $params = [], $wrap = 'request', $keys = false)
    {
        $params = array_merge($params, ['auth' => $this->auth]);
        $request = $wrap ? [$wrap => $params] : $params;
        $cacheKey = 'api.' . $method . '.' . md5(serialize($request) . ($keys ? serialize($keys) : ''));
        $ret = Yii::$app->cache->get($cacheKey) ?: false;

        if (!$ret) {
            try {
                $ret = $this->$method($request);
                if ($ret && isset($ret->return->file)) {
                    return ['FILE' => $ret->return->file];
                }
            } catch (\SoapFault $e) {
                $paramsLog = $request;
                unset($paramsLog['auth']);
                Yii::error([
                    'msg' => 'SoapFault. Ошибка запроса DPD для метода ' . $method,
                    'extra' => [
                        'message' => $e->getMessage(),
                        'params' => $paramsLog,
                        'paramsBase' => base64_encode(Json::encode($paramsLog)),
                        'trace' => $e->getTraceAsString(),
                    ],
                ]);
                throw new ExceptionServiceClient($e);
            } catch (\Exception $e) {
                $paramsLog = $request;
                unset($paramsLog['auth']);
                Yii::error([
                    'msg' => 'Exception. Ошибка запроса DPD для метода ' . $method,
                    'extra' => [
                        'message' => $e->getMessage(),
                        'params' => $paramsLog,
                        'paramsBase' => base64_encode(Json::encode($paramsLog)),
                        'trace' => $e->getTraceAsString(),
                    ],
                ]);
                throw new ExceptionServiceClient($e);
            }

            $ret = Json::encode($ret);
            $ret = Json::decode($ret, true);

            if (array_key_exists('return', $ret)) {
                $ret = $ret['return'];
                if ($keys && array_intersect((array)$keys, array_keys($ret))) {
                    $ret = [$ret];
                }
            } else {
                $ret = [];
            }
            Yii::$app->cache->set($cacheKey, $ret);
        }
        return $ret;
    }
}
