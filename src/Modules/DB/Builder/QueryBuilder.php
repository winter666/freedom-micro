<?php


namespace Freedom\Modules\DB\Builder;



use Freedom\Modules\DB\Builder\Clause\CreateClause;
use Freedom\Modules\DB\Builder\Clause\DeleteClause;
use Freedom\Modules\DB\Builder\Clause\SelectClause;
use Freedom\Modules\DB\Builder\Clause\UpdateClause;
use Freedom\Modules\DB\Builder\Clause\WhereClause;
use Freedom\Modules\DB\Connection;
use Freedom\Modules\DB\Model;

class QueryBuilder
{
    private int|null $limit = null;

    private SelectClause $selectClause;
    private WhereClause $whereClause;
    private UpdateClause $updateClause;
    private DeleteClause $deleteClause;
    private CreateClause $createClause;

    public function __construct(protected Model $model, protected Connection $connection)
    {
        $this->selectClause = new SelectClause($model->getTable());
        $this->updateClause = new UpdateClause($model->getTable());
        $this->deleteClause = new DeleteClause($model->getTable());
        $this->createClause = new CreateClause($model->getTable());
        $this->whereClause = new WhereClause();
    }

    public function select(array $fields): QueryBuilder
    {
        $this->selectClause->push($fields);
        return $this;
    }

    public function where(string $field, $value, string $operator = '=', string $expression = 'AND'): QueryBuilder
    {
        $this->whereClause->push($field, $value, $operator, $expression);
        return $this;
    }

    public function orWhere(string $field, $value, string $operator = '='): QueryBuilder {
        $this->where($field, $value, $operator, 'OR');
       return $this;
    }

    // execute
    public function first(): Model|null
    {
        $this->limit = 1;
        $exec = $this->makeSelectQueryString();
        $state = $this->connection->getConnection()
            ->prepare($exec['query']);
        $state->execute($exec['values']);
        $result = $state->fetch();
        if ($result) {
            return $this->extract($result);
        }

        return null;
    }

    // execute
    public function get(): array
    {
        $exec = $this->makeSelectQueryString();
        $state = $this->connection->getConnection()
            ->prepare($exec['query']);
        $state->execute($exec['values']);
        $result = $state->fetchAll();
        if ($result) {
            return $this->extractMany($result);
        }

        return [];
    }

    // execute
    public function update(array $values): bool
    {
        $this->updateClause->push($values);
        $exec = $this->makeUpdateQueryString();
        $state = $this->connection->getConnection()
            ->prepare($exec['query']);
        return $state->execute($exec['values']);
    }

    // execute
    public function delete(): bool
    {
        $exec = $this->makeDeleteQueryString();
        $state = $this->connection->getConnection()
            ->prepare($exec['query']);
        return $state->execute($exec['values']);
    }

    // execute
    public function create(array $data)
    {
        $this->createClause->push($data);
        $exec = $this->makeCreateQueryString();
        $state = $this->connection->getConnection()
            ->prepare($exec['query']);
        return $state->execute($exec['values']);
    }

    private function extractMany(array $collection): array {
        return array_map(fn ($item) => ($this->extract($item)), $collection);
    }

    private function extract(array $result): Model
    {
        $item = new $this->model;
        foreach ($result as $key => $value) {
            if (preg_match('/^([0-9]+)$/', $key)) {
                continue;
            }

            $item->$key = $value;
        }

        return $item;
    }

    public function toSQL(): string
    {
        $exec = $this->makeSelectQueryString();
        return $exec['query'];
    }

    private function makeSelectQueryString(): array
    {
        $query = $this->selectClause->run();
        $this->whereClause->run();
        $query .= ' ' . $this->whereClause->getPrepare();
        $values = $this->whereClause->getValue();

        if (!is_null($this->limit)) {
            $query .= ' LIMIT '. $this->limit;
        }

        return compact('query', 'values');
    }

    private function makeUpdateQueryString(): array
    {
        $this->updateClause->run();
        $this->whereClause->run();
        $query = $this->updateClause->getPrepare();
        $values = $this->updateClause->getValue();
        $query .= ' ' . $this->whereClause->getPrepare();
        foreach($this->whereClause->getValue() as $value) {
            $values[] = $value;
        }

        return compact('query', 'values');
    }

    private function makeDeleteQueryString(): array
    {
        $query = $this->deleteClause->run();
        $this->whereClause->run();
        $query .= $this->whereClause->getPrepare();
        $values = $this->whereClause->getValue();
        return compact('query', 'values');
    }

    private function makeCreateQueryString(): array
    {
        $this->createClause->run();
        $query = $this->createClause->getPrepare();
        $values = $this->createClause->getValue();
        return compact('query', 'values');
    }


}
