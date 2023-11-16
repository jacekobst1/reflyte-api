<?php

declare(strict_types=1);

uses(Illuminate\Foundation\Testing\TestCase::class);
uses(Tests\CreatesApplication::class);

//test('Every model has uuid primary key', function () {
//    foreach (getClasses() as $class) {
//        $reflectionClass = new ReflectionClass($class);
//
//        if ($reflectionClass->isSubclassOf(Model::class) && !$reflectionClass->isAbstract()) {
//            expect($class)->toUse(HasUuids::class);
//        }
//    }
//});
//
//function getClasses(): array
//{
//    // Load every class in App\Modules namespace
//    $allClasses = File::allFiles(app_path('Modules'));
//    foreach ($allClasses as $file) {
//        require_once $file->getPathname();
//    }
//
//    /**
//     * Method get_declared_classes() returns hundreds of loaded classes (cause Laravel app is booted),
//     * so we filter them to get only classes in App\Modules namespace
//     */
//    $declaredClasses = get_declared_classes();
//    return array_filter($declaredClasses, function (string $class) {
//        return str_contains($class, 'App\\Modules\\');
//    });
//}
