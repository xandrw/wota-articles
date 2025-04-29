<?php

declare(strict_types=1);

namespace App\Presentation\Api\ValueResolvers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

// WIP - Add route parameters to request constructor automatically
/** @SuppressUnused */
readonly class RequestHasRouteParametersValueResolver implements ValueResolverInterface
{
    public function __construct(private ValidatorInterface $validator) {}

    /**
     * @throws ValidationFailedException
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        /** @var class-string<RequestHasRouteParametersInterface>|null $requestClass */
        $requestClass = $argument->getType();

        if ($requestClass === null || class_exists($requestClass) === false) {
            return [];
        }

        if (is_subclass_of($requestClass, RequestHasRouteParametersInterface::class) === false) {
            return [];
        }

        $data = json_decode($request->getContent(), true) ?? [];
        foreach ($requestClass::getRouteParameterKeys() as $routeParameter) {
            $data[$routeParameter] = $request->attributes->get($routeParameter);
        }

        /** @var RequestHasRouteParametersInterface $requestInstance */
        $requestInstance = new $requestClass(...$data);
        $errors = $this->validator->validate($requestInstance);

        if (count($errors) > 0) {
            throw new ValidationFailedException($requestInstance, $errors);
        }

        return [$requestInstance];
    }
}
