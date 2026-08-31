<?php
declare(strict_types=1);

if (!extension_loaded('openssl')) {
    echo "[Notice] openssl extension is not loaded\n";
}

if (!class_exists('security\src\EncryptionUtil')) {
    echo "[Error] Class security\\src\\EncryptionUtil not found!\n";
    exit(1);
}

$raw = 'TypePHP AES Test OK';
$encrypted = \security\src\EncryptionUtil::encrypt($raw);
$decrypted = \security\src\EncryptionUtil::decrypt($encrypted);

echo "[Success] Class loaded\n";
echo "Raw:       $raw\n";
echo "Encrypted: $encrypted\n";
echo "Decrypted: $decrypted\n";

if ($raw !== $decrypted) {
    echo "[Error] Decrypted text does not match raw text!\n";
    exit(1);
}

echo "Verification succeeded!\n";
