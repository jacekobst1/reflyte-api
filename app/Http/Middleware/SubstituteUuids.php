<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Exceptions\BadRequestException;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Reflector;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use ReflectionParameter;

final class SubstituteUuids
{
    /**
     * @throws BadRequestException
     */
    public function handle(Request $request, Closure $next)
    {
        // Getting a route object from current request
        $route = $request->route();

        // Filter only parameters type-hinted with UuidInterface
        /** @var ReflectionParameter[] $parameters */
        $parameters = array_filter($route->signatureParameters(), function ($p) {
            return Reflector::getParameterClassName($p) === UuidInterface::class;
        });

        foreach ($parameters as $parameter) {
            // Getting parameter value by parameter name (uuid string)
            $uuid = $route->parameter($parameter->getName());

            try {
                $uuidInterface = Uuid::fromString($uuid);
            } catch (InvalidUuidStringException) {
                throw new BadRequestException('Invalid uuid');
            }

            // Replace uuid string with Uuid object
            $route->setParameter($parameter->getName(), $uuidInterface);
        }

        return $next($request);
    }
}
