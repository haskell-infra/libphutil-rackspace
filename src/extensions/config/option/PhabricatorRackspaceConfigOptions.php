<?php

final class PhabricatorRackspaceConfigOptions
  extends PhabricatorApplicationConfigOptions {

  public function getName() {
    return pht('Rackspace Cloud');
  }

  public function getDescription() {
    return pht('Configure integration with Rackspace (servers, files, etc).');
  }

  public function getOptions() {
    return array(
      $this->newOption('rackspace-files.username', 'string', null)
        ->setLocked(true)
        ->setDescription(pht('Rackspace username.')),
      $this->newOption('rackspace-files.api-key', 'string', null)
        ->setMasked(true)
        ->setDescription(pht('API key for Rackspace.')),
      $this->newOption('rackspace-files.logging', 'bool', false)
        ->setDescription(pht(
          'Set this to true to enable access logs for all data that the file '.
          'objects acrue.')),
      $this->newOption('storage.rackspace.container', 'string', null)
         ->setSummary(pht('Rackspace Files container.'))
         ->setDescription(
           pht(
             "Set this to a valid Rackspace Files container to store files ".
             "there. You must also configure the Rackspace access keys in the ".
             "'Rackspace Cloud' group, and the region to store the files in.")),
      $this->newOption('storage.rackspace.region', 'enum', null)
         ->setEnumOptions(
           array(
             'IAD' => 'Northern Virginia (IAD)',
             'ORD' => 'Chicago (ORD)',
             'SYD' => 'Sydney (SYD)',
             'DFW' => 'Dallas (DFW)',
             'LON' => 'London (LON)',
             'HKG' => 'Hong Kong (HKG)',
           ))
         ->setSummary(pht('Rackspace Files region.'))
         ->setDescription(
           pht(
             "Set this to a valid Rackspace region, which specifies which ".
             "region your Cloud Files will be stored in. The default is ".
             "`null`.")),
    );
  }

}
