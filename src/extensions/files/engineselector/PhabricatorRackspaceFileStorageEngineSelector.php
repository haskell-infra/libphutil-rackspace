<?php

/**
 * Rackspace storage engine selector, which defaults to Rackspace
 * after the MySQL storage limit has been reached.
 */
final class PhabricatorRackspaceFileStorageEngineSelector
  extends PhabricatorFileStorageEngineSelector {

  /**
   * Select viable default storage engine.
   */
  public function selectStorageEngines($data, array $params) {
    $length = strlen($data);

    $mysql_key = 'storage.mysql-engine.max-size';
    $mysql_limit = PhabricatorEnv::getEnvConfig($mysql_key);

    $engines = array();
    /* First: MySQL if allowed */
    if ($mysql_limit && $length <= $mysql_limit) {
      $engines[] = new PhabricatorMySQLFileStorageEngine();
    }

    /* -- Check local disk -- */
    $local_key = 'storage.local-disk.path';
    $local_path = PhabricatorEnv::getEnvConfig($local_key);
    if ($local_path) {
      $engines[] = new PhabricatorLocalDiskFileStorageEngine();
    }

    /* -- Select Rackspace before S3 -- */
    $rackspace_key       = 'rackspace-files.container';
    $rackspace_container = PhabricatorEnv::getEnvConfig($rackspace_key);

    $rackspace_key    = 'rackspace-files.region';
    $rackspace_region = PhabricatorEnv::getEnvConfig($rackspace_key);

    if ($rackspace_container && $rackspace_region) {
      $engines[] = new PhabricatorRackspaceFileStorageEngine();
    }

    /* -- Check S3 -- */
    $s3_key = 'storage.s3.bucket';
    if (PhabricatorEnv::getEnvConfig($s3_key)) {
      $engines[] = new PhabricatorS3FileStorageEngine();
    }

    if ($mysql_limit && empty($engines)) {
      // If we return no engines, an exception will be thrown but it will be
      // a little vague ("No valid storage engines"). Since this is a default
      // case, throw a more specific exception.
      throw new Exception(
        'This file exceeds the configured MySQL storage engine filesize '.
        'limit, but no other storage engines are configured. Increase the '.
        'MySQL storage engine limit or configure a storage engine suitable '.
        'for larger files.');
    }

    return $engines;
  }
}
