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
    $engines = array();

    $rackspace_key       = 'rackspace-files.container';
    $rackspace_container = PhabricatorEnv::getEnvConfig($rackspace_key);

    $rackspace_key    = 'rackspace-files.region';
    $rackspace_region = PhabricatorEnv::getEnvConfig($rackspace_key);

    if ($rackspace_container && $rackspace_region) {
      $engines[] = new PhabricatorRackspaceFileStorageEngine();
      return $engines;
    }

    throw new Exception(
      'You selected the Rackspace file storage engine, but have not '.
      'configured a container or region to use yet. Please do so '.
      'immediately under `Config > Rackspace`.');
  }
}

// Local Variables:
// fill-column: 80
// indent-tabs-mode: nil
// c-basic-offset: 2
// buffer-file-coding-system: utf-8-unix
// End:
