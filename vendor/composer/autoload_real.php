<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit5960512d55e7d355167b2b28c2b88105
{
    private static $loader;

    /**
     * 类的加载器,找 Composer\ClassLoader 如果不存在就是生成一个实例放在 ComposerAutoloaderInit5960512d55e7d355167b2b28c2b88105
     * @param $class
     */
    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) { //加载类的路径
            require __DIR__ . '/ClassLoader.php'; //加载ClassLoader文件
        }
    }

    /**
     * 将 composer cli 生成的各种 autoload_psr4, autoload_classmap, autoload_namespaces(psr-0) 全都注册到 Composer\ClassLoader 中
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader() //获取加载
    {
        if (null !== self::$loader) {
            return self::$loader;
        }
        //调用 ComposerAutoloaderInit5960512d55e7d355167b2b28c2b88105::loadClassLoader
        spl_autoload_register(array('ComposerAutoloaderInit5960512d55e7d355167b2b28c2b88105', 'loadClassLoader'), true, true); //类制动加载注册,自动调用array中的函数 throw抛出错误   prepend如果是 true，spl_autoload_register() 会添加函数到队列之首，而不是队列尾部。
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(); //加载类,php本身调用此时调用了之前注册的,调用时会将需要实例化的 完整命名空间 + 类名传入
        spl_autoload_unregister(array('ComposerAutoloaderInit5960512d55e7d355167b2b28c2b88105', 'loadClassLoader')); //删除之前注册的

        $useStaticLoader = PHP_VERSION_ID >= 50600 && !defined('HHVM_VERSION') && (!function_exists('zend_loader_file_encoded') || !zend_loader_file_encoded());
        if ($useStaticLoader) {
            require_once __DIR__ . '/autoload_static.php';

            call_user_func(\Composer\Autoload\ComposerStaticInit5960512d55e7d355167b2b28c2b88105::getInitializer($loader));
        } else {
            //将 composer cli 生成的各种 autoload_psr4, autoload_classmap, autoload_namespaces(psr-0) 全都注册到 Composer\ClassLoader 中
            $map = require __DIR__ . '/autoload_namespaces.php';
            foreach ($map as $namespace => $path) {
                $loader->set($namespace, $path);
            }

            $map = require __DIR__ . '/autoload_psr4.php';
            foreach ($map as $namespace => $path) {
                $loader->setPsr4($namespace, $path);
            }

            $classMap = require __DIR__ . '/autoload_classmap.php';
            if ($classMap) {
                $loader->addClassMap($classMap);
            }
        }

        $loader->register(true);

        if ($useStaticLoader) {
            $includeFiles = Composer\Autoload\ComposerStaticInit5960512d55e7d355167b2b28c2b88105::$files;
        } else {
            //直接 require 所有在 autoload_files 中的文件
            $includeFiles = require __DIR__ . '/autoload_files.php';
        }
        foreach ($includeFiles as $fileIdentifier => $file) {
            composerRequire5960512d55e7d355167b2b28c2b88105($fileIdentifier, $file);
        }

        return $loader;
    }
}

function composerRequire5960512d55e7d355167b2b28c2b88105($fileIdentifier, $file)
{
    //第一次执行
    // $fileIdentifier; 0e6d7bf4a5811bfa5cf40c5ccd6fae6a
    // $file;symfony/polyfill-mbstring/bootstrap.php
    // $GLOBALS['__composer_autoload_files'][$fileIdentifier] ; NULL

    if (empty($GLOBALS['__composer_autoload_files'][$fileIdentifier])) {
        require $file; //请求文件

        $GLOBALS['__composer_autoload_files'][$fileIdentifier] = true; //true
    }
}
