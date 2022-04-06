<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit3dcee1a35a89e682362b1b6d72066efb
{
    public static $prefixLengthsPsr4 = array (
        'w' => 
        array (
            'wsSalusPerAquam\\' => 16,
        ),
        'W' => 
        array (
            'WsdlToPhp\\PackageBase\\' => 22,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'wsSalusPerAquam\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'WsdlToPhp\\PackageBase\\' => 
        array (
            0 => __DIR__ . '/..' . '/wsdltophp/packagebase/src',
        ),
    );

    public static $classMap = array (
        'WsdlToPhp\\PackageBase\\AbstractSoapClientBase' => __DIR__ . '/..' . '/wsdltophp/packagebase/src/AbstractSoapClientBase.php',
        'WsdlToPhp\\PackageBase\\AbstractStructArrayBase' => __DIR__ . '/..' . '/wsdltophp/packagebase/src/AbstractStructArrayBase.php',
        'WsdlToPhp\\PackageBase\\AbstractStructBase' => __DIR__ . '/..' . '/wsdltophp/packagebase/src/AbstractStructBase.php',
        'WsdlToPhp\\PackageBase\\AbstractStructEnumBase' => __DIR__ . '/..' . '/wsdltophp/packagebase/src/AbstractStructEnumBase.php',
        'WsdlToPhp\\PackageBase\\SoapClientInterface' => __DIR__ . '/..' . '/wsdltophp/packagebase/src/SoapClientInterface.php',
        'WsdlToPhp\\PackageBase\\StructArrayInterface' => __DIR__ . '/..' . '/wsdltophp/packagebase/src/StructArrayInterface.php',
        'WsdlToPhp\\PackageBase\\StructEnumInterface' => __DIR__ . '/..' . '/wsdltophp/packagebase/src/StructEnumInterface.php',
        'WsdlToPhp\\PackageBase\\StructInterface' => __DIR__ . '/..' . '/wsdltophp/packagebase/src/StructInterface.php',
        'WsdlToPhp\\PackageBase\\Utils' => __DIR__ . '/..' . '/wsdltophp/packagebase/src/Utils.php',
        'wsSalusPerAquam\\ClassMap' => __DIR__ . '/../..' . '/src/ClassMap.php',
        'wsSalusPerAquam\\ServiceType\\Add' => __DIR__ . '/../..' . '/src/ServiceType/Add.php',
        'wsSalusPerAquam\\ServiceType\\Get' => __DIR__ . '/../..' . '/src/ServiceType/Get.php',
        'wsSalusPerAquam\\StructType\\AddSaleRequest' => __DIR__ . '/../..' . '/src/StructType/AddSaleRequest.php',
        'wsSalusPerAquam\\StructType\\AddSaleResponse' => __DIR__ . '/../..' . '/src/StructType/AddSaleResponse.php',
        'wsSalusPerAquam\\StructType\\Detail' => __DIR__ . '/../..' . '/src/StructType/Detail.php',
        'wsSalusPerAquam\\StructType\\GetTreatmentRequest' => __DIR__ . '/../..' . '/src/StructType/GetTreatmentRequest.php',
        'wsSalusPerAquam\\StructType\\GetTreatmentResponse' => __DIR__ . '/../..' . '/src/StructType/GetTreatmentResponse.php',
        'wsSalusPerAquam\\StructType\\Sale_detail' => __DIR__ . '/../..' . '/src/StructType/Sale_detail.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit3dcee1a35a89e682362b1b6d72066efb::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit3dcee1a35a89e682362b1b6d72066efb::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit3dcee1a35a89e682362b1b6d72066efb::$classMap;

        }, null, ClassLoader::class);
    }
}