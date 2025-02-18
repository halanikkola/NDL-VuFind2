<?php
/**
 * CPU payment handler
 *
 * PHP version 7
 *
 * Copyright (C) The National Library of Finland 2016-2019.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 2,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category VuFind
 * @package  OnlinePayment
 * @author   Leszek Manicki <leszek.z.manicki@helsinki.fi>
 * @author   Samuli Sillanpää <samuli.sillanpaa@helsinki.fi>
 * @author   Ere Maijala <ere.maijala@helsinki.fi>
 * @author   Juha Luoma <juha.luoma@helsinki.fi>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org/wiki/vufind2:developer_manual Wiki
 */
namespace Finna\OnlinePayment;

require_once 'Cpu/Client.class.php';
require_once 'Cpu/Client/Payment.class.php';
require_once 'Cpu/Client/Product.class.php';

/**
 * CPU payment handler module.
 *
 * @category VuFind
 * @package  OnlinePayment
 * @author   Leszek Manicki <leszek.z.manicki@helsinki.fi>
 * @author   Samuli Sillanpää <samuli.sillanpaa@helsinki.fi>
 * @author   Ere Maijala <ere.maijala@helsinki.fi>
 * @author   Juha Luoma <juha.luoma@helsinki.fi>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org/wiki/vufind2:developer_manual Wiki
 */
class CPU extends BaseHandler
{
    public const STATUS_SUCCESS = 1;
    public const STATUS_CANCELLED = 0;
    public const STATUS_PENDING = 2;
    public const STATUS_ID_EXISTS = 97;
    public const STATUS_ERROR = 98;
    public const STATUS_INVALID_REQUEST = 99;

    /**
     * Start transaction.
     *
     * @param string             $finesUrl       Return URL to MyResearch/Fines
     * @param string             $ajaxUrl        Base URL for AJAX-actions
     * @param \Finna\Db\Row\User $user           User
     * @param array              $patron         Patron information
     * @param string             $driver         Patron MultiBackend ILS source
     * @param int                $amount         Amount (excluding transaction fee)
     * @param int                $transactionFee Transaction fee
     * @param array              $fines          Fines data
     * @param string             $currency       Currency
     * @param string             $statusParam    Payment status URL parameter
     *
     * @return string Error message on error, otherwise redirects to payment handler.
     */
    public function startPayment(
        $finesUrl,
        $ajaxUrl,
        $user,
        $patron,
        $driver,
        $amount,
        $transactionFee,
        $fines,
        $currency,
        $statusParam
    ) {
        $patronId = $patron['cat_username'];
        $orderNumber = $this->generateTransactionId($patronId);

        $returnUrl
            = "{$finesUrl}?driver={$driver}"
            . "&{$statusParam}=1";

        $notifyUrl
            = "{$ajaxUrl}/onlinePaymentNotify?driver={$driver}"
            . "&{$statusParam}=1";

        $payment = new \Cpu_Client_Payment($orderNumber);
        $email = trim($user->email);
        if ($email) {
            $payment->Email = $email;
        }
        $lastname = $user->lastname;
        if (!empty($user->firstname)) {
            $payment->FirstName = $user->firstname;
        } else {
            // We don't have both names separately, try to extract first name from
            // last name.
            if (strpos($lastname, ',') > 0) {
                // Lastname, Firstname
                [$lastname, $firstname] = explode(',', $lastname, 2);
            } else {
                // First Middle Last
                if (preg_match('/^(.*) (.*?)$/', $lastname, $matches)) {
                    $firstname = $matches[1];
                    $lastname = $matches[2];
                } else {
                    $firstname = '';
                }
            }
            $lastname = trim($lastname);
            $firstname = trim($firstname);
            $payment->FirstName = empty($firstname) ? 'ei tietoa' : $firstname;
        }
        $payment->LastName = empty($lastname) ? 'ei tietoa' : $lastname;

        $locale = $this->translator->getLocale();
        [$lang] = explode('-', $locale);
        if (!empty($this->config->supportedLanguages)) {
            $languageMappings = [];
            foreach (explode(':', $this->config->supportedLanguages) as $item) {
                $parts = explode('=', $item, 2);
                if (count($parts) != 2) {
                    continue;
                }
                $languageMappings[trim($parts[0])] = trim($parts[1]);
            }
            if (isset($languageMappings[$lang])) {
                $payment->Language = $languageMappings[$lang];
            }
        }

        $payment->Description
            = $this->config->paymentDescription ?? '';

        $payment->ReturnAddress = $returnUrl;
        $payment->NotificationAddress = $notifyUrl;

        if (!isset($this->config->productCode)) {
            $this->logPaymentError(
                'missing productCode configuration option',
                compact('user', 'patron', 'fines')
            );
            return '';
        }
        $productCode = $this->config->productCode;
        $productCodeMappings = $this->getProductCodeMappings();
        $organizationProductCodeMappings
            = $this->getOrganizationProductCodeMappings();

        foreach ($fines as $fine) {
            $fineType = $fine['fine'] ?? '';
            $fineOrg = $fine['organization'] ?? '';
            $fineDesc = '';
            if (!empty($fineType)) {
                $fineDesc = $this->translator->translate("fine_status_$fineType");
                if ("fine_status_$fineType" === $fineDesc) {
                    $fineDesc = $this->translator->translate("status_$fineType");
                    if ("status_$fineType" === $fineDesc) {
                        $fineDesc = $fineType;
                    }
                }
            }
            if (!empty($fine['title'])) {
                $fineDesc .= ' ('
                    . mb_substr(
                        $fine['title'],
                        0,
                        100 - 4 - strlen($fineDesc),
                        'UTF-8'
                    ) . ')';
            }
            if ($fineDesc) {
                // Get rid of characters that cannot be converted to ISO-8859-1 since
                // CPU apparently can't handle them properly.
                $fineDesc = iconv(
                    'ISO-8859-1',
                    'UTF-8',
                    iconv('UTF-8', 'ISO-8859-1//IGNORE', $fineDesc)
                );
                // Remove ' since it causes the string to be truncated
                $fineDesc = str_replace("'", ' ', $fineDesc);
                // Sanitize before limiting the length, otherwise the sanitization
                // process may blow the string through the limit
                $fineDesc = \Cpu_Client::sanitize($fineDesc);
                // Make sure that description length does not exceed CPU max limit of
                // 100 characters.
                $fineDesc = mb_substr($fineDesc, 0, 100, 'UTF-8');
            }

            $code = $productCodeMappings[$fineType] ?? $productCode;
            if (isset($organizationProductCodeMappings[$fineOrg])) {
                $code = $organizationProductCodeMappings[$fineOrg]
                    . ($productCodeMappings[$fineType] ?? '');
            }
            $code = mb_substr($code, 0, 25, 'UTF-8');
            $product = new \Cpu_Client_Product(
                $code,
                1,
                $fine['balance'],
                $fineDesc ?: null
            );
            $payment = $payment->addProduct($product);
        }
        if ($transactionFee) {
            $code = $this->config->transactionFeeProductCode ?? $productCode;
            $product = new \Cpu_Client_Product(
                $code,
                1,
                $transactionFee,
                'Palvelumaksu / Serviceavgift / Transaction fee'
            );
            $payment = $payment->addProduct($product);
        }

        if (!($module = $this->initCpu())) {
            $this->logPaymentError(
                'error initializing CPU online payment',
                compact('user', 'patron', 'fines')
            );
            return '';
        }

        try {
            $response = $module->sendPayment($payment);
        } catch (\Exception $e) {
            $this->logPaymentError(
                'exception sending payment: ' . $e->getMessage(),
                compact('user', 'patron', 'fines', 'payment')
            );
            return '';
        }
        if (isset($response['error']) || !$response) {
            $errorMessage = $response['error'] ?? 'sendPayment returned false';
            $this->logPaymentError(
                'error sending payment: ' . $errorMessage,
                compact('user', 'patron', 'fines', 'payment')
            );
            return '';
        }

        $response = json_decode($response);

        if (empty($response->Id) || empty($response->Status)) {
            $this->logPaymentError(
                'error starting payment, no response',
                compact('user', 'patron', 'fines', 'payment')
            );
            return '';
        }

        $status = intval($response->Status);
        if (in_array($status, [self::STATUS_ERROR, self::STATUS_INVALID_REQUEST])) {
            // System error or Request failed.
            $this->logPaymentError(
                'error starting transaction',
                compact('response', 'user', 'patron', 'fines', 'payment')
            );
            return '';
        }

        $params = [
            $orderNumber, $status,
            $response->Reference, $response->PaymentAddress
        ];
        if (!$this->verifyHash($params, $response->Hash)) {
            $this->logPaymentError(
                'error starting transaction, invalid checksum',
                compact('response', 'user', 'patron', 'fines', 'payment')
            );
            return '';
        }

        if ($status === self::STATUS_SUCCESS) {
            // Already processed
            $this->logPaymentError(
                'error starting transaction, transaction already processed',
                compact('response', 'user', 'patron', 'fines', 'payment')
            );
            return '';
        }

        if ($status === self::STATUS_ID_EXISTS) {
            // Order exists
            $this->logPaymentError(
                'error starting transaction, order exists',
                compact('response', 'user', 'patron', 'fines', 'payment')
            );
            return '';
        }

        if ($status === self::STATUS_CANCELLED) {
            // Cancelled
            $this->logPaymentError(
                'error starting transaction, order cancelled',
                compact('response', 'user', 'patron', 'fines', 'payment')
            );
            return '';
        }

        if ($status === self::STATUS_PENDING) {
            // Pending

            $success = $this->createTransaction(
                $orderNumber,
                $driver,
                $user->id,
                $patronId,
                $amount,
                $transactionFee,
                $currency,
                $fines
            );
            if (!$success) {
                return '';
            }
            $this->redirectToPayment($response->PaymentAddress);
        }
        return '';
    }

    /**
     * Return payment response parameters.
     *
     * @param Laminas\Http\Request $request Request
     *
     * @return array
     */
    public function getPaymentResponseParams($request)
    {
        $params = array_merge(
            $request->getQuery()->toArray(),
            $request->getPost()->toArray()
        );
        $payload = json_decode($request->getContent());

        $required = ['Id', 'Status', 'Reference', 'Hash'];
        $response = [];

        foreach ($required as $name) {
            if (isset($payload->$name)) {
                $response[$name] = $payload->$name;
                continue;
            }
            if (isset($params[$name])) {
                $response[$name] = $params[$name];
                continue;
            }

            $this->logPaymentError(
                "missing parameter $name in payment response",
                compact('request', 'params', 'payload')
            );

            return false;
        }

        $result = array_merge($response, $params);
        $result['transaction'] = $result['Id'];

        return $result;
    }

    /**
     * Process the response from payment service.
     *
     * @param Laminas\Http\Request $request Request
     *
     * @return string error message (not translated)
     *   or associative array with keys:
     *     'markFeesAsPaid' (boolean) true if payment was successful and fees
     *     should be registered as paid.
     *     'transactionId' (string) Transaction ID.
     *     'amount' (int) Amount to be registered (does not include transaction fee).
     */
    public function processResponse($request)
    {
        if (!$params = $this->getPaymentResponseParams($request)) {
            return 'online_payment_failed';
        }

        $id = $params['Id'];
        $status = intval($params['Status']);
        $reference = $params['Reference'];
        $orderNum = $params['transaction'];
        $hash = $params['Hash'];

        if (!$this->verifyHash([$id, $status, $reference], $hash)) {
            $this->setTransactionFailed($orderNum, 'invalid checksum');
            $this->logPaymentError(
                'error processing response: invalid checksum',
                compact('request', 'params')
            );
            return 'online_payment_failed';
        }

        [$success, $data] = $this->getStartedTransaction($orderNum);
        if (!$success) {
            return $data;
        }

        if ($status === self::STATUS_SUCCESS) {
            $this->setTransactionPaid($orderNum);
            return [
               'markFeesAsPaid' => true,
               'transactionId' => $orderNum,
               'amount' => $data->amount
            ];
        } elseif ($status === self::STATUS_CANCELLED) {
            $this->setTransactionCancelled($orderNum);
            return 'online_payment_canceled';
        } else {
            return 'online_payment_failed';
        }
    }

    /**
     * Init CPU module with configured merchantId, secret and URL.
     *
     * @return \Cpu_Client
     */
    protected function initCpu()
    {
        foreach (['merchantId', 'secret', 'url'] as $req) {
            if (!isset($this->config[$req])) {
                $this->logger->err("missing parameter $req");
                return false;
            }
        }

        $module = new \Cpu_Client(
            $this->config['url'],
            $this->config['merchantId'],
            $this->config['secret']
        );
        $module->setHttpService($this->http);
        $module->setLogger($this->logger);
        return $module;
    }

    /**
     * Verify transaction response hash.
     *
     * @param array  $params Parameters
     * @param string $hash   Hash
     *
     * @return boolean
     */
    protected function verifyHash($params, $hash)
    {
        $params[] = $this->config['secret'];
        return hash('sha256', implode('&', $params)) === $hash;
    }
}
