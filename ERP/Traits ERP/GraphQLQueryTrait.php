<?php

namespace App\Traits;

use Closure;
use Rebing\GraphQL\Support\Facades\GraphQL;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\SelectFields;
use Rebing\GraphQL\Support\Query;
use PHPUnit\Exception;

/**
 * Class GraphQLQueryTrait Trait, this trait is used in querys with same logic
 *
 * @package App\Traits
 * @author Ricardo Gonzalez <rgonzalez25202gmail.com>
 */
trait GraphQLQueryTrait
{
    public function args(): array
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::int()
            ],
            'activos' => [
                'name' => 'activos',
                'type' => Type::int()
            ],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $fields = $getSelectFields();
        $select = $fields->getSelect();
        $with = $fields->getRelations();

        $this->repository->isGraphql = true;
        $this->repository->fields = $select;
        $this->repository->with = $with;
        $paginate = [];

        if(isset($args['id'])) {
            return $this->repository->find($args['id']);
        }

        if(isset($args['activos'])) {
            $paginate['activos'] = $args['activos'];
        }

        return $this->repository->all($paginate);
    }
}