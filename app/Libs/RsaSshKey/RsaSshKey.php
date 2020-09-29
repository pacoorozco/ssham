<?php
/**
 * SSH Access Manager - SSH keys management solution.
 *
 * Copyright (c) 2017 - 2020 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2017 - 2020 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 * @link        https://github.com/pacoorozco/ssham
 */

namespace App\Libs\RsaSshKey;

use Illuminate\Support\Arr;
use phpseclib\Crypt\RSA;

class RsaSshKey
{
    /**
     * Private Key Format.
     */
    const PRIVATE_KEY_FORMAT = RSA::PRIVATE_FORMAT_PKCS1;

    /**
     * Private Key Bits Length.
     */
    const PRIVATE_KEY_LENGTH = 1024;

    /**
     * Public Key Format.
     */
    const PUBLIC_KEY_FORMAT = RSA::PUBLIC_FORMAT_OPENSSH;

    /**
     * Create public / private key pair to be used in SSH.
     *
     * Returns an array with the following elements:
     *  - 'privatekey': The private key.
     *  - 'publickey':  The public key.
     *
     * @return array - array of two elements 'privatekey' and 'publickey'.
     */
    public static function create(): array
    {
        $rsa = new RSA();
        $rsa->setPrivateKeyFormat(self::PRIVATE_KEY_FORMAT);
        $rsa->setPublicKeyFormat(self::PUBLIC_KEY_FORMAT);

        return Arr::only($rsa->createKey(self::PRIVATE_KEY_LENGTH), ['privatekey', 'publickey']);
    }

    /**
     * Return the public key.
     *
     * @param string $key
     *
     * @return string
     * @throws \App\Libs\RsaSshKey\InvalidInputException
     */
    public static function getPublicKey(string $key): string
    {
        $rsa = new RSA();
        if ($rsa->loadKey($key, self::PUBLIC_KEY_FORMAT) === false) {
            throw new InvalidInputException('The provided key is malformed.');
        }

        $key = $rsa->getPublicKey(self::PUBLIC_KEY_FORMAT);
        if ($key === false) {
            throw new InvalidInputException('The public key has not been found.');
        }

        return $key;
    }

    /**
     * Return the private key.
     *
     * @param string $key
     *
     * @return string
     * @throws \App\Libs\RsaSshKey\InvalidInputException
     */
    public static function getPrivateKey(string $key): string
    {
        $rsa = new RSA();
        if ($rsa->loadKey($key, self::PRIVATE_KEY_FORMAT) === false) {
            throw new InvalidInputException('The provided key is malformed.');
        }

        $key = $rsa->getPrivateKey(self::PRIVATE_KEY_FORMAT);
        if ($key === false) {
            throw new InvalidInputException('The private key has not been found.');
        }

        return $key;
    }

    /**
     * Get the fingerprint of a RSA public key.
     *
     * @param string $key           - RSA publec key content.
     * @param string $hashAlgorithm - Valid values are 'md5' or 'sha256'.
     *
     * @return string - The calculated fingerprint.
     *
     * @throws \App\Libs\RsaSshKey\InvalidInputException
     */
    public static function getPublicFingerprint(string $key, string $hashAlgorithm = 'md5'): string
    {
        $rsa = new RSA();
        if ($rsa->loadKey($key, self::PUBLIC_KEY_FORMAT) === false) {
            throw new InvalidInputException('The provided public key is malformed.');
        }

        $fingerprint = $rsa->getPublicKeyFingerprint($hashAlgorithm);
        if ($fingerprint === false) {
            throw new InvalidInputException('The provided public key is invalid.');
        }

        return $fingerprint;
    }

    /**
     * Remove carrier return and spaces characters from a RSA key.
     *
     * @param string $key
     *
     * @return string
     */
    private static function cleanRSAKey(string $key): string
    {
        if (empty($key)) {
            return $key;
        }

        return str_replace(["\n", "\t", "\r", ' '], '', $key);
    }

    /**
     * Returns true if two RSA keys are equal.
     *
     * @param string $key1
     * @param string $key2
     *
     * @return bool
     */
    public static function compareKeys(string $key1, string $key2): bool
    {
        return self::cleanRSAKey($key1) === self::cleanRSAKey($key2);
    }
}
