<?php
/**
 * @desc openssl扩展实现AES加解密，支持AES-128-ECB算法，支持PKCS7Padding填充方式
 * @author Tinywan(ShaoBo Wan)
 */
declare(strict_types=1);

namespace security\src;


class EncryptionUtil
{
    // 加密算法
    private const ALGORITHM = 'AES-128-ECB';

    // 16位秘钥key
    private const KEY = 'RestyExNti0srT13N';

    /**
     * @desc 加密
     * @param string $encryptText
     * @return string
     * @author Tinywan(ShaoBo Wan)
     */
    public static function encrypt(string $encryptText): string
    {
        $encryptedBytes = openssl_encrypt($encryptText, self::ALGORITHM, self::KEY, OPENSSL_RAW_DATA);
        return base64_encode($encryptedBytes);
    }

    /**
     * @desc 解密
     * @param string $encryptedText
     * @return false|string
     * @author Tinywan(ShaoBo Wan)
     */
    public static function decrypt(string $encryptedText): false|string
    {
        return openssl_decrypt(base64_decode($encryptedText), self::ALGORITHM, self::KEY, OPENSSL_RAW_DATA);
    }
}
