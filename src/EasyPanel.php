<?php

namespace EasyPanel;

class  EasyPanel
{
    private $routePreFix;
    private $routeName;

    public function __construct()
    {
        $this->routePreFix = config('easy_panel.route_prefix');
        $this->routeName = str_replace('/', '.', trim($this->routePreFix, '/'));
    }
    public function getRouteName()
    {
        return $this->routeName;
    }
    public function getRouteOf($name)
    {
        return $this->routeName. '.'.$name;
    }
    public function getCrudConfig($name)
    {
        $className = ucwords($name);
        $classNamespace = "\\App\\CRUD\\{$className}Component";
        $classPath = app_path("/CRUD/{$className}Component.php");
        $isNamespaceExist = class_exists($classNamespace);
        /***
         * realpath convert unix to windows path
         * also if path not exist return false
         */
        $isFileExist = realpath($classPath);
        abrot_unless(
            $isFileExist
            or
            $isNamespaceExist,
            403,
            "We can not find any class with ${name}. It must be in {$className} path or {$classNamespace} namesapce "
        );

        $instance = app()->make($classNamespace);

        abrot_unless(
            instance instanceof CRUDComponent,
            403,
            "{$classNamespace} should implement CRUDComponent interface"
        );
        return $instance;
    }
    public  function getCrudWithName($name)
    {
        // TODO: how about fail on finding?
        return \EasyPanel\Models\CRUD::query()->where('name', $name)->first();
    }
    public function adminHasPermission($routeName, $withAcl, $withPolicy = false, $entity = [])
    {
        $showButton = true;

        if ($withAcl) {
            if (!auth()->user()->hasPermission($routeName)) {
                $showButton = false;
            } else if ($withPolicy && !auth()->user()->hasPermission($routeName, $entity)) {
                $showButton = false;
            }
        }

        return $showButton;


    }

}