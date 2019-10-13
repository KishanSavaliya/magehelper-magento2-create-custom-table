<?php
/**
 * MageHelper Create custom table Magento 2 with the use of
 * InstallSchema, InstallData, UpgradeSchema and UpgradeData
 *
 * @package      MageHelper_CreateCustomTable
 * @author       Kishan Savaliya <kishansavaliyakb@gmail.com>
 */

namespace MageHelper\CreateCustomTable\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
          /**
           * Install employee data
           */
          $data = [
              ['employee_name' => 'John Doe', 'position' => 'Accountant'],
              ['employee_name' => 'Cara Stevens', 'position' => 'Software Engineer ']
          ];
          foreach ($data as $bind) {
              $setup->getConnection()
                ->insertForce($setup->getTable('magehelper_employees'), $bind);
          }
    }
}