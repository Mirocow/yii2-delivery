<?php
namespace mirocow\delivery\services\dpd\services;

use mirocow\delivery\components\ServiceAbstract;
use mirocow\delivery\services\dpd\dto\DPDLocationDto;
use mirocow\delivery\services\dpd\repositories\DPDLocationRepositoryInterface;
use DomainException;
use Yii;
use yii\base\Exception;
use yii\helpers\Console;
use yii\helpers\FileHelper;

/**
 * Class DPDSystemService
 * @package mirocow\delivery\components\dpd\services
 */
class DPDSystemService extends ServiceAbstract
{
    /**
     * @var bool выводить информацию о прогрессе в консоль
     */
    public $enableOutputConsole = false;

    /**
     * @var DPDLocationRepositoryInterface
     */
    private $repDpdLocation;

    /**
     * DPDSystemService constructor.
     * @param DPDLocationRepositoryInterface $repDpdLocation
     */
    public function __construct(DPDLocationRepositoryInterface $repDpdLocation)
    {
        ini_set('auto_detect_line_endings', true);
        $this->repDpdLocation = $repDpdLocation;
    }

    /**
     * @brief логирование процесса обновления
     * @param $message
     */
    private function log($message)
    {
        $this->enableOutputConsole === true ? Console::output($message) : Yii::info($message);
    }

    /**
     * @brief скачивание файла с FTP сервера DPD. Файл содержит список городов для которых доступна доставка DPD
     * @return string|null
     * @throws Exception
     */
    private function downloadLocations()
    {
        // коннект к серверу DPD
        $ftpConnect = ftp_connect(env('DPD_FTP_HOSTNAME'), env('DPD_FTP_PORT'));
        if ($ftpConnect === false) {
            throw new DomainException('Не удалось подключиться к FTP серверу DPD');
        }
        // авторизация на сервере
        if (!ftp_login($ftpConnect, env('DPD_FTP_USERNAME'), env('DPD_FTP_PASSWORD'))) {
            throw new DomainException('Не удалось пройти авторизация FTP на сервере DPD');
        }
        ftp_pasv($ftpConnect, true);

        // получения списка файлов, в документации написано что файлы находятся в папке integration
        $files = ftp_nlist($ftpConnect, '/integration');
        if ($files === false) {
            throw new DomainException('Не удалось получить список файлов с FTP сервера DPD');
        }
        // на сервере может находится несколько файлов
        // необходимо выбрать один файл со списком городов доставки DPD
        // файл со списком городов начинается со слова geography
        $files = array_filter($files, function ($value) {
            if (stripos(mb_strtolower($value), 'geography') > 1) {
                return true;
            }
            return false;
        });
        // убеждаемся что файл найден
        if (!count($files)) {
            return null;
        }
        $file = Yii::getAlias('@console/runtime/dpd') . '/cities.csv';
        if (!file_exists(Yii::getAlias('@console/runtime/dpd'))) {
            FileHelper::createDirectory(Yii::getAlias('@console/runtime/dpd'), 0777);
        }
        if (!ftp_get($ftpConnect, $file, $files[0], FTP_BINARY)) {
            throw new DomainException('Ошибка скачивания файла с FTP сервера DPD!');
        }
        // конвертация обязательна
        file_put_contents($file, mb_convert_encoding(file_get_contents($file), 'UTF-8', 'Windows-1251'));
        return $file;
    }

    /**
     * @brief обновление данных о доступных городах доставки DPD
     * @return bool
     * @throws Exception
     */
    public function refreshLocations()
    {
        $file = $this->downloadLocations();
        $file = fopen($file, 'r');
        if ($file === false) {
            return false;
        }
        $this->log('ИНФО: Получен файл с FTP сервера DPD...');

        $transaction = Yii::$app->db->beginTransaction();
        try {
            // перебираем весь файл
            while (($row = fgetcsv($file, null, ';')) !== false) {
                if (!$this->repDpdLocation->exist(['location_id' => $row[0]])) {
                    if (isset($row[5])) {
                        // полное наименование адреса
                        $origName = implode(', ', [trim($row[5]), trim($row[4]), trim($row[2] . ' ' . $row[3])]);
                        $repDto = new DPDLocationDto();
                        $repDto->location_id = $row[0];
                        $location_type = trim($row[2]);
                        switch (trim($row[2])) {
                            case 'г':
                            case 'Город':
                                $location_type = 'г';
                            break;
                        }
                        $repDto->location_type = $location_type;
                        $repDto->city_code = mb_substr($row[1], 2);
                        $repDto->orig_name = $origName;
                        $repDto->country_name = trim($row[5]);
                        $repDto->city_name = trim($row[3]);
                        $repDto->country_name_lower = mb_strtolower(trim($row[5]));
                        $repDto->city_name_lower = mb_strtolower(trim($row[3]));

                        // поиск кода страны
                        preg_match('/^([A-Z]+)/', $row[1], $matches);
                        if (isset($matches[0])) {
                            $repDto->country_code = $matches[0];
                        }

                        if ($this->repDpdLocation->create($repDto)) {
                            $this->log('ИНФО: Добавлен ' . $origName);
                        } else {
                            $this->log('ИНФО: Не удалось добавить город ' . $origName);
                        }

                    }
                }
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}
