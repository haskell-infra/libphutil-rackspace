<?php

/**
 * Rackspace blueprint for Drydock - allows dynamic allocation of servers and
 * leasing them.
 *
 * @task internal Internals
 */
final class DrydockRackspaceBlueprintImplementation
  extends DrydockBlueprintImplementation {

/* -(  Implementation  )----------------------------------------------------- */

  public function isEnabled() {
    // This blueprint is only available if the Rackspace keys are configured.
    return
      PhabricatorEnv::getEnvConfig('rackspace-cloud.username') &&
      PhabricatorEnv::getEnvConfig('rackspace-cloud.api-key');
  }

  public function getBlueprintName() {
    return pht('Rackspace Cloud Remote Hosts');
  }

  public function getDescription() {
    return pht(
      'Allows Drydock to allocate and execute commands on '.
      'Rackspace Cloud servers.');
  }

  public function getType() {
    return 'host';
  }

  private function getRackspaceKeyPairName() {
    return 'phabricator-'.$this->getDetail('keypair');
  }

  /* -- Resource (server) allocation ---------------------------------------- */
  public function canAllocateMoreResources(array $pool) {
    $max_count = $this->getDetail('max-count');
    return count($pool) < $max_count;
  }

  protected function executeAllocateResource(DrydockLease $lease) {
    throw new Exception('NIH!');
  }

  /** Pending D10204
  protected function executeCloseResource(DrydockResource $resource) {

  }
  */

  /* -- Leasing information ------------------------------------------------- */

  /** Pending D10204
  protected function shouldCloseUnleasedResource(
    array $open_resources,
    DrydockResource $resource) {

    return count($open_resources) > $this->getDetail('min-count');
  }
  */

  protected function canAllocateLease(
    DrydockResource $resource,
    DrydockLease $lease) {

    return
      $lease->getAttribute('platform') === $resource->getAttribute('platform');
  }

  protected function shouldAllocateLease(
    // Pending D10204
    // array $all_resources,
    // array $all_leases_grouped,
    DrydockResource $resource,
    DrydockLease $lease,
    array $other_leases) {
    return false;
  }

  protected function executeAcquireLease(
    DrydockResource $resource,
    DrydockLease $lease) {

    throw new Exception('NIH!');
  }

  /** Pending D10204
  protected function executeReleaseLease(
    DrydockResource $resource,
    DrydockLease $lease) {

    // TODO: Remove leased directory
  }
  */

  /* -- Misc ---------------------------------------------------------------- */
  public function getInterface(
    DrydockResource $resource,
    DrydockLease $lease,
    $type) {

    switch ($type) {
      case 'command':
        return id(new DrydockSSHCommandInterface())
          ->setConfiguration(array(
            'host' => $resource->getAttribute('host'),
            'port' => $resource->getAttribute('port'),
            'credential' => $resource->getAttribute('credential'),
            'platform' => $resource->getAttribute('platform')));
      case 'filesystem':
        return id(new DrydockSFTPFilesystemInterface())
          ->setConfiguration(array(
            'host' => $resource->getAttribute('host'),
            'port' => $resource->getAttribute('port'),
            'credential' => $resource->getAttribute('credential')));
    }

    throw new Exception("No interface of type '{$type}'.");
  }

  public function getFieldSpecifications() {
    return array(
      'region' => array(
        'name' => pht('Region'),
        'type' => 'text',
        'required' => true,
        'caption' => pht('e.g. %s', 'ORD')
      ),
      'image' => array(
        'name' => pht('Rackspace image'),
        'type' => 'text',
        'required' => true,
        'caption' => pht('e.g. %s', 'ami-7fd3ae4f')
      ),
      'keypair' => array(
        'name' => pht('Key Pair'),
        'type' => 'credential',
        'required' => true,
        'credential.provides'
        => PassphraseCredentialTypeSSHPrivateKey::PROVIDES_TYPE,
        'caption' => pht(
          'Only the public key component is transmitted to Amazon.')
      ),
      'size' => array(
        'name' => pht('Instance Size'),
        'type' => 'text',
        'required' => true,
        'caption' => pht('e.g. %s', 't2.micro')
      ),
      'platform' => array(
        'name' => pht('Platform Name'),
        'type' => 'text',
        'required' => true,
        'caption' => pht('e.g. %s or %s', 'windows', 'linux')
      ),
      'storage-path' => array(
        'name' => pht('Storage Path'),
        'type' => 'text',
        'required' => true,
        'caption' => pht(
          'A writable location on the instance where new directories / files '.
          'can be created and data can be stored in.')
      ),
      'min-count' => array(
        'name' => pht('Minimum Instances'),
        'type' => 'int',
        'required' => true,
        'caption' => pht(
          'The minimum number of instances to keep running in '.
          'this pool at all times.')
      ),
      'max-count' => array(
        'name' => pht('Maximum Instances'),
        'type' => 'int',
        'caption' => pht(
          'The maximum number of instances to allow running at any time.  '.
          'If the number of instances currently running are equal to '.
          '`max-count` and another lease is requested, Drydock will place '.
          'leases on existing resources and thus exceeding '.
          '`leases-per-instance`.  If this parameter is left blank, then '.
          'this blueprint has no limit on the number of EC2 instances it '.
          'can allocate.')
      ),
      'leases-per-instance' => array(
        'name' => pht('Maximum Leases Per Instance'),
        'type' => 'int',
        'required' => true,
        'caption' => pht(
          'The soft limit on the number of leases to allocate to an '.
          'individual EC2 instance in the pool.  Drydock will choose the '.
          'instance with the lowest number of leases when selecting a '.
          'resource to lease on.  If all current EC2 instances have '.
          '`leases-per-instance` leases on them, then Drydock will allocate '.
          'another EC2 instance providing `max-count` would not be exceeded.'.
          '  If `max-count` would be exceeded, Drydock will instead '.
          'overallocate the lease to an existing EC2 instance and '.
          'exceed the limit specified here.')
      ),
    );
  }
}

// Local Variables:
// fill-column: 80
// indent-tabs-mode: nil
// c-basic-offset: 2
// buffer-file-coding-system: utf-8-unix
// End:
