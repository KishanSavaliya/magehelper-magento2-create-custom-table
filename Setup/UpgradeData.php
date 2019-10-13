<?php
/**
 * MageHelper Create custom table Magento 2 with the use of
 * InstallSchema, InstallData, UpgradeSchema and UpgradeData
 *
 * @package      MageHelper_CreateCustomTable
 * @author       Kishan Savaliya <kishansavaliyakb@gmail.com>
 */

namespace MageHelper\CreateCustomTable\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class UpgradeData implements UpgradeDataInterface
{
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        if ($context->getVersion()
            && version_compare($context->getVersion(), '1.0.2') < 0
        ) {
            $table = $setup->getTable('magehelper_employees');

            $setup->getConnection()
                ->update($table, ['employee_salary' => '10000', 'position' => 'Designer'], 'employee_id = 1');

            $setup->getConnection()
                ->update($table, ['employee_salary' => '20000'], 'employee_id = 2');
        }
        $setup->endSetup();
    }
}