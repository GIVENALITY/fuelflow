<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Encryption\DecryptException;

class EncryptionService
{
    /**
     * Encrypt sensitive data
     */
    public static function encrypt($data)
    {
        if (is_null($data)) {
            return null;
        }

        return Crypt::encrypt($data);
    }

    /**
     * Decrypt sensitive data
     */
    public static function decrypt($encryptedData)
    {
        if (is_null($encryptedData)) {
            return null;
        }

        try {
            return Crypt::decrypt($encryptedData);
        } catch (DecryptException $e) {
            return null;
        }
    }

    /**
     * Hash password with additional salt
     */
    public static function hashPassword($password, $salt = null)
    {
        if (is_null($salt)) {
            $salt = bin2hex(random_bytes(16));
        }

        $hashedPassword = Hash::make($password . $salt);

        return [
            'hash' => $hashedPassword,
            'salt' => $salt
        ];
    }

    /**
     * Verify password with salt
     */
    public static function verifyPassword($password, $hash, $salt)
    {
        return Hash::check($password . $salt, $hash);
    }

    /**
     * Encrypt file content
     */
    public static function encryptFile($filePath)
    {
        $content = file_get_contents($filePath);
        $encryptedContent = self::encrypt($content);

        $encryptedPath = $filePath . '.enc';
        file_put_contents($encryptedPath, $encryptedContent);

        return $encryptedPath;
    }

    /**
     * Decrypt file content
     */
    public static function decryptFile($encryptedFilePath)
    {
        $encryptedContent = file_get_contents($encryptedFilePath);
        $decryptedContent = self::decrypt($encryptedContent);

        $decryptedPath = str_replace('.enc', '', $encryptedFilePath);
        file_put_contents($decryptedPath, $decryptedContent);

        return $decryptedPath;
    }

    /**
     * Generate secure random token
     */
    public static function generateSecureToken($length = 32)
    {
        return bin2hex(random_bytes($length));
    }

    /**
     * Generate API key
     */
    public static function generateApiKey()
    {
        return 'fuf_' . bin2hex(random_bytes(32));
    }

    /**
     * Encrypt database field
     */
    public static function encryptField($value)
    {
        if (is_null($value) || $value === '') {
            return $value;
        }

        return self::encrypt($value);
    }

    /**
     * Decrypt database field
     */
    public static function decryptField($value)
    {
        if (is_null($value) || $value === '') {
            return $value;
        }

        return self::decrypt($value);
    }

    /**
     * Mask sensitive data for display
     */
    public static function maskSensitiveData($data, $type = 'default')
    {
        if (is_null($data) || $data === '') {
            return $data;
        }

        switch ($type) {
            case 'email':
                $parts = explode('@', $data);
                if (count($parts) === 2) {
                    $username = $parts[0];
                    $domain = $parts[1];
                    $maskedUsername = substr($username, 0, 2) . str_repeat('*', max(0, strlen($username) - 4)) . substr($username, -2);
                    return $maskedUsername . '@' . $domain;
                }
                break;

            case 'phone':
                return substr($data, 0, 3) . str_repeat('*', strlen($data) - 6) . substr($data, -3);

            case 'credit_card':
                return str_repeat('*', strlen($data) - 4) . substr($data, -4);

            case 'ssn':
                return '***-**-' . substr($data, -4);

            default:
                if (strlen($data) <= 4) {
                    return str_repeat('*', strlen($data));
                }
                return substr($data, 0, 2) . str_repeat('*', strlen($data) - 4) . substr($data, -2);
        }

        return $data;
    }

    /**
     * Generate secure filename
     */
    public static function generateSecureFilename($originalName)
    {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $filename = self::generateSecureToken(16);

        return $filename . '.' . $extension;
    }

    /**
     * Validate encryption integrity
     */
    public static function validateIntegrity($data, $hash)
    {
        return hash_equals(hash('sha256', $data), $hash);
    }

    /**
     * Generate integrity hash
     */
    public static function generateIntegrityHash($data)
    {
        return hash('sha256', $data);
    }
}
