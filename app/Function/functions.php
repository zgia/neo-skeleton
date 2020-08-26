<?php

use App\Helper\BaseHelper;
use App\Service\BaseService;
use App\Service\System\SystemService;
use Neo\Base\Model;
use Neo\Config;
use Neo\Html\Page;

/**
 * 加载工具类
 *
 * @param string $helper    工具类
 * @param string $namespace 命名空间前缀
 *
 * @return BaseHelper 加载的类
 */
function loadHelper(string $helper, $namespace = 'App\\Helper')
{
    return loadClass($helper, $namespace);
}

/**
 * 加载业务
 *
 * @param string $service   业务类
 * @param string $namespace 命名空间前缀
 *
 * @return BaseService 加载的类
 */
function loadService(string $service, $namespace = 'App\\Service')
{
    return loadClass($service, $namespace);
}

/**
 * 加载模型
 *
 * @param string $model     模型类
 * @param string $namespace 命名空间前缀
 *
 * @return Model 加载的类
 */
function loadModel(string $model, $namespace = 'App\\Model')
{
    return loadClass($model, $namespace);
}

/**
 * 当前是否开发环境
 *
 * @return bool
 */
function isDevelop()
{
    return ! defined('NEO_ENVIRONMENT') || NEO_ENVIRONMENT != 'product';
}

/**
 * 将输出内容数组转为JSON格式输出显示
 *
 * @param string $msg        消息
 * @param int    $code       错误码
 * @param int    $statusCode Http状态码
 */
function printErrorJSON(string $msg, int $code = 1, int $statusCode = 200)
{
    $jsonarray = [
        'code' => $code,
        'msg' => $msg,
    ];

    printOutJSON($jsonarray, $statusCode);
}

/**
 * 将输出内容数组转为JSON格式输出显示
 *
 * @param string $msg        消息
 * @param int    $code       错误码
 * @param int    $statusCode Http状态码
 */
function printSuccessJSON(string $msg, int $code = 0, int $statusCode = 200)
{
    $jsonarray = [
        'code' => $code,
        'msg' => $msg,
    ];

    printOutJSON($jsonarray, $statusCode);
}

/**
 * 跳转时显示错误信息。如果指定URL，则显示错误信息后，自动跳转到这个URL。
 * 否则，停留在错误信息页面，等待用户后退。
 *
 * @param string $message 跳转时，显示的信息
 * @param string $url     页面跳转地址
 * @param bool   $back    是否显示后退链接
 */
function displayError(string $message, string $url = '', bool $back = true)
{
    if (neo()->getRequest()->isAjax()) {
        printErrorJSON($message);
    } else {
        Page::redirect($url, $message, __('Error'), $url ? 2 : 0, true, $back);
    }
}

/**
 * 显示提示信息
 *
 * @param string $message 显示的信息
 * @param string $url     页面跳转地址
 * @param bool   $back    是否显示后退链接
 */
function displayMessage(string $message, string $url = '', $back = false)
{
    if (neo()->getRequest()->isAjax()) {
        printSuccessJSON($message);
    } else {
        Page::redirect($url, $message, __('Information'), 0, false, $back);
    }
}

/**
 * Send email
 *
 * @param string       $subject     邮件主题
 * @param string       $body        邮件内容
 * @param array|string $emails      收件人地址，可以多个
 * @param string       $contentType text/plain 或者 text/html
 * @param string       $attachment  附件绝对路径
 *
 * @return int The number of successful recipients. Can be 0 which indicates failure
 */
function sendMail(string $subject, string $body, $emails, ?string $contentType = null, ?string $attachment = null)
{
    // Create the Transport
    $transport = (new Swift_SmtpTransport(
        getOption('smtp_host'),
        getOption('smtp_port'),
        getOption('smtp_encryption') ?: null
    ))
        ->setUsername(getOption('smtp_username'))
        ->setPassword(getOption('smtp_password'));

    // Create the Mailer using your created Transport
    $mailer = new Swift_Mailer($transport);

    // Create a message
    $message = (new Swift_Message($subject))
        ->setFrom(getOption('smtp_frommail'), getOption('smtp_fromname'))
        ->setTo($emails)
        ->setBody($body, $contentType);

    if ($attachment && is_file($attachment)) {
        $message->attach(Swift_Attachment::fromPath($attachment));
    }

    // Send the message
    return $mailer->send($message);
}

/**
 * 返回某个系统设置项
 *
 * @param string     $key     系统设置的某个项
 * @param null|mixed $default 没有获取到值时，可以返回一个默认值
 *
 * @return mixed 如果这个项目不存在，则返回NULL
 */
function getOption(string $key, $default = null)
{
    return SystemService::getOption($key, $default);
}

/**
 * 拼接URI
 *
 * @param mixed $uri
 *
 * @return string
 */
function baseURL(...$uri)
{
    return ABSURL . '/' . ltrim(implode('', $uri), '/');
}

/**
 * 跳转
 */
function redirect()
{
    $args = func_get_args();

    $args[0] = $args[0] ?? '';

    if (! $args[0] || $args[0][0] === '/') {
        $args[0] = baseURL($args[0]);
    }

    Page::redirect(...$args);
}
