<?php

final class PhabricatorRackspaceConfigOptions
  extends PhabricatorApplicationConfigOptions {

  public function getName() {
    return pht('Rackspace');
  }

  public function getDescription() {
    return pht('Configure integration with Rackspace (servers, files, etc).');
  }

  public function getOptions() {
    return array(
      /* -- Rackspace storage options. -- */
      $this->newOption('rackspace.storage.username', 'string', null)
        ->setLocked(true)
        ->setDescription(pht('Rackspace username for Cloud Files storage.')),
      $this->newOption('rackspace.storage.api-key', 'string', null)
        ->setMasked(true)
        ->setDescription(pht('API key for Cloud Files user.')),
      $this->newOption('rackspace.storage.logging', 'bool', false)
        ->setDescription(pht(
          'Set this to true to enable access logs for all data that the file '.
          'objects acrue.')),
      $this->newOption('rackspace.storage.container', 'string', null)
         ->setSummary(pht('Cloud Files container for file storage.'))
         ->setDescription(
           pht(
             'Set this to a valid Rackspace Files container to store files '.
             'in. You must also configure the Rackspace access keys, '.
             'and the region to store the files in.')),
      $this->newOption('rackspace.storage.region', 'enum', null)
         ->setEnumOptions(
           array(
             'IAD' => 'Northern Virginia (IAD)',
             'ORD' => 'Chicago (ORD)',
             'SYD' => 'Sydney (SYD)',
             'DFW' => 'Dallas (DFW)',
             'LON' => 'London (LON)',
             'HKG' => 'Hong Kong (HKG)',
           ))
         ->setSummary(pht('Cloud Files region.'))
         ->setDescription(
           pht(
             'Set this to a valid Rackspace region, which specifies which '.
             'region your Cloud Files will be stored in. The default is '.
             '`null`.')),
    );
  }
}

// Local Variables:
// fill-column: 80
// indent-tabs-mode: nil
// c-basic-offset: 2
// buffer-file-coding-system: utf-8-unix
// End:
