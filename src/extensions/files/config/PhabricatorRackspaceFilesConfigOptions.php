<?php

final class PhabricatorRackspaceFilesConfigOptions
  extends PhabricatorApplicationConfigOptions {

  public function getName() {
    return pht('Files');
  }

  public function getOptions() {

    return array(
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
