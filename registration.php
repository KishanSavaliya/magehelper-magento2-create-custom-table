<?php
/**
 * MageHelper Create custom table Magento 2 with the use of
 * InstallSchema, InstallData, UpgradeSchema and UpgradeData
 *
 * @package      MageHelper_CreateCustomTable
 * @author       Kishan Savaliya <kishansavaliyakb@gmail.com>
 */
\Magento\Framework\Component\ComponentRegistrar::register(
    \Magento\Framework\Component\ComponentRegistrar::MODULE,
    'MageHelper_CreateCustomTable',
    __DIR__
);