<?php

namespace Drupal\social_api\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Site\Settings;

/**
 * Defines a base class for Social API content entities.
 */
class SocialApi extends ContentEntityBase implements ContentEntityInterface {

  /**
   * Encryption key based on the unique hash salt of the Drupal installation.
   *
   * @var string
   */
  protected $key;

  /**
   * Sets the encrypted, serialized token.
   *
   * @param string $token
   *   The serialized access token.
   *
   * @return \Drupal\social_auth\Entity\SocialAuth
   *   Drupal Social Auth Entity.
   */
  public function setToken($token) {
    $this->set('token', $this->encryptToken($token));

    return $this;
  }

  /**
   * Returns the unencrypted, serialized access token.
   *
   * @return string
   *   The serialized access token.
   */
  public function getToken() {
    $token = $this->get('token')->value;

    return $this->decryptToken($token);
  }

  /**
   * Returns the encrypted token.
   *
   * @param string $token
   *   The plain-text token provided by the provider.
   *
   * @return string
   *   The encrypted token.
   */
  protected function encryptToken($token) {
    $key = $this->getEncryptionKey();

    // Remove the base64 encoding from our key.
    $encryption_key = base64_decode($key);

    // Generates an initialization vector.
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));

    // Encrypts the data using AES 256 encryption in CBC mode using our
    // encryption key and initialization vector.
    $encrypted = openssl_encrypt($token, 'aes-256-cbc', $encryption_key, 0, $iv);

    // The $iv is just as important as the key for decrypting, so save it with
    // our encrypted data using a unique separator (::).
    return base64_encode($encrypted . '::' . $iv);
  }

  /**
   * Decrypts the stored token.
   *
   * @param string $token
   *   Encrypted token stored in database.
   *
   * @return string
   *   The plain-text token provided by the provider.
   */
  protected function decryptToken($token) {
    $key = $this->getEncryptionKey();

    // Removes the base64 encoding from our key.
    $encryption_key = base64_decode($key);

    // Split the encrypted data from our IV - our unique separator used was
    // "::".
    list($encrypted_data, $iv) = explode('::', base64_decode($token), 2);

    return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);
  }

  /**
   * Gets the hash salt for this drupal installation.
   *
   * @return string
   *   The encryption key.
   */
  public function getEncryptionKey() {
    if (!$this->key) {
      $this->key = Settings::getHashSalt();
    }

    return $this->key;
  }

}
