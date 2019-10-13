<?php
/**
 * MageHelper Create custom table Magento 2 with the use of
 * InstallSchema, InstallData, UpgradeSchema and UpgradeData
 *
 * @package      MageHelper_CreateCustomTable
 * @author       Kishan Savaliya <kishansavaliyakb@gmail.com>
 */

namespace MageHelper\CreateCustomTable\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        if (version_compare($context->getVersion(), '1.0.1', '<')) {
            $setup->getConnection()->addColumn(
                $setup->getTable('magehelper_employees'),
                'employee_salary',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 16,
                    'nullable' => false,
                    'default' => '',
                    'comment' => 'Employee Salary'
                ]
            );
        }
        $setup->endSetup();
    }
}