<?php

namespace pelis\Base;

use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use pelis\PeliculaGenero as ChildPeliculaGenero;
use pelis\PeliculaGeneroQuery as ChildPeliculaGeneroQuery;
use pelis\Map\PeliculaGeneroTableMap;

/**
 * Base class that represents a query for the 'pelicula_genero' table.
 *
 *
 *
 * @method     ChildPeliculaGeneroQuery orderByPeliculaId($order = Criteria::ASC) Order by the pelicula_id column
 * @method     ChildPeliculaGeneroQuery orderByGeneroId($order = Criteria::ASC) Order by the genero_id column
 *
 * @method     ChildPeliculaGeneroQuery groupByPeliculaId() Group by the pelicula_id column
 * @method     ChildPeliculaGeneroQuery groupByGeneroId() Group by the genero_id column
 *
 * @method     ChildPeliculaGeneroQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPeliculaGeneroQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPeliculaGeneroQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPeliculaGeneroQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildPeliculaGeneroQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildPeliculaGeneroQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildPeliculaGeneroQuery leftJoinPelicula($relationAlias = null) Adds a LEFT JOIN clause to the query using the Pelicula relation
 * @method     ChildPeliculaGeneroQuery rightJoinPelicula($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Pelicula relation
 * @method     ChildPeliculaGeneroQuery innerJoinPelicula($relationAlias = null) Adds a INNER JOIN clause to the query using the Pelicula relation
 *
 * @method     ChildPeliculaGeneroQuery joinWithPelicula($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Pelicula relation
 *
 * @method     ChildPeliculaGeneroQuery leftJoinWithPelicula() Adds a LEFT JOIN clause and with to the query using the Pelicula relation
 * @method     ChildPeliculaGeneroQuery rightJoinWithPelicula() Adds a RIGHT JOIN clause and with to the query using the Pelicula relation
 * @method     ChildPeliculaGeneroQuery innerJoinWithPelicula() Adds a INNER JOIN clause and with to the query using the Pelicula relation
 *
 * @method     ChildPeliculaGeneroQuery leftJoinGenero($relationAlias = null) Adds a LEFT JOIN clause to the query using the Genero relation
 * @method     ChildPeliculaGeneroQuery rightJoinGenero($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Genero relation
 * @method     ChildPeliculaGeneroQuery innerJoinGenero($relationAlias = null) Adds a INNER JOIN clause to the query using the Genero relation
 *
 * @method     ChildPeliculaGeneroQuery joinWithGenero($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Genero relation
 *
 * @method     ChildPeliculaGeneroQuery leftJoinWithGenero() Adds a LEFT JOIN clause and with to the query using the Genero relation
 * @method     ChildPeliculaGeneroQuery rightJoinWithGenero() Adds a RIGHT JOIN clause and with to the query using the Genero relation
 * @method     ChildPeliculaGeneroQuery innerJoinWithGenero() Adds a INNER JOIN clause and with to the query using the Genero relation
 *
 * @method     \pelis\PeliculaQuery|\pelis\GeneroQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPeliculaGenero findOne(ConnectionInterface $con = null) Return the first ChildPeliculaGenero matching the query
 * @method     ChildPeliculaGenero findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPeliculaGenero matching the query, or a new ChildPeliculaGenero object populated from the query conditions when no match is found
 *
 * @method     ChildPeliculaGenero findOneByPeliculaId(int $pelicula_id) Return the first ChildPeliculaGenero filtered by the pelicula_id column
 * @method     ChildPeliculaGenero findOneByGeneroId(int $genero_id) Return the first ChildPeliculaGenero filtered by the genero_id column *

 * @method     ChildPeliculaGenero requirePk($key, ConnectionInterface $con = null) Return the ChildPeliculaGenero by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPeliculaGenero requireOne(ConnectionInterface $con = null) Return the first ChildPeliculaGenero matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPeliculaGenero requireOneByPeliculaId(int $pelicula_id) Return the first ChildPeliculaGenero filtered by the pelicula_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPeliculaGenero requireOneByGeneroId(int $genero_id) Return the first ChildPeliculaGenero filtered by the genero_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPeliculaGenero[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPeliculaGenero objects based on current ModelCriteria
 * @method     ChildPeliculaGenero[]|ObjectCollection findByPeliculaId(int $pelicula_id) Return ChildPeliculaGenero objects filtered by the pelicula_id column
 * @method     ChildPeliculaGenero[]|ObjectCollection findByGeneroId(int $genero_id) Return ChildPeliculaGenero objects filtered by the genero_id column
 * @method     ChildPeliculaGenero[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PeliculaGeneroQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \pelis\Base\PeliculaGeneroQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\pelis\\PeliculaGenero', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPeliculaGeneroQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPeliculaGeneroQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPeliculaGeneroQuery) {
            return $criteria;
        }
        $query = new ChildPeliculaGeneroQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj = $c->findPk(array(12, 34), $con);
     * </code>
     *
     * @param array[$pelicula_id, $genero_id] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildPeliculaGenero|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PeliculaGeneroTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = PeliculaGeneroTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]))))) {
            // the object is already in the instance pool
            return $obj;
        }

        return $this->findPkSimple($key, $con);
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPeliculaGenero A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT pelicula_id, genero_id FROM pelicula_genero WHERE pelicula_id = :p0 AND genero_id = :p1';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildPeliculaGenero $obj */
            $obj = new ChildPeliculaGenero();
            $obj->hydrate($row);
            PeliculaGeneroTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]));
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildPeliculaGenero|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildPeliculaGeneroQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(PeliculaGeneroTableMap::COL_PELICULA_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(PeliculaGeneroTableMap::COL_GENERO_ID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPeliculaGeneroQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(PeliculaGeneroTableMap::COL_PELICULA_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(PeliculaGeneroTableMap::COL_GENERO_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the pelicula_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPeliculaId(1234); // WHERE pelicula_id = 1234
     * $query->filterByPeliculaId(array(12, 34)); // WHERE pelicula_id IN (12, 34)
     * $query->filterByPeliculaId(array('min' => 12)); // WHERE pelicula_id > 12
     * </code>
     *
     * @see       filterByPelicula()
     *
     * @param     mixed $peliculaId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPeliculaGeneroQuery The current query, for fluid interface
     */
    public function filterByPeliculaId($peliculaId = null, $comparison = null)
    {
        if (is_array($peliculaId)) {
            $useMinMax = false;
            if (isset($peliculaId['min'])) {
                $this->addUsingAlias(PeliculaGeneroTableMap::COL_PELICULA_ID, $peliculaId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($peliculaId['max'])) {
                $this->addUsingAlias(PeliculaGeneroTableMap::COL_PELICULA_ID, $peliculaId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PeliculaGeneroTableMap::COL_PELICULA_ID, $peliculaId, $comparison);
    }

    /**
     * Filter the query on the genero_id column
     *
     * Example usage:
     * <code>
     * $query->filterByGeneroId(1234); // WHERE genero_id = 1234
     * $query->filterByGeneroId(array(12, 34)); // WHERE genero_id IN (12, 34)
     * $query->filterByGeneroId(array('min' => 12)); // WHERE genero_id > 12
     * </code>
     *
     * @see       filterByGenero()
     *
     * @param     mixed $generoId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPeliculaGeneroQuery The current query, for fluid interface
     */
    public function filterByGeneroId($generoId = null, $comparison = null)
    {
        if (is_array($generoId)) {
            $useMinMax = false;
            if (isset($generoId['min'])) {
                $this->addUsingAlias(PeliculaGeneroTableMap::COL_GENERO_ID, $generoId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($generoId['max'])) {
                $this->addUsingAlias(PeliculaGeneroTableMap::COL_GENERO_ID, $generoId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PeliculaGeneroTableMap::COL_GENERO_ID, $generoId, $comparison);
    }

    /**
     * Filter the query by a related \pelis\Pelicula object
     *
     * @param \pelis\Pelicula|ObjectCollection $pelicula The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPeliculaGeneroQuery The current query, for fluid interface
     */
    public function filterByPelicula($pelicula, $comparison = null)
    {
        if ($pelicula instanceof \pelis\Pelicula) {
            return $this
                ->addUsingAlias(PeliculaGeneroTableMap::COL_PELICULA_ID, $pelicula->getId(), $comparison);
        } elseif ($pelicula instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PeliculaGeneroTableMap::COL_PELICULA_ID, $pelicula->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPelicula() only accepts arguments of type \pelis\Pelicula or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Pelicula relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPeliculaGeneroQuery The current query, for fluid interface
     */
    public function joinPelicula($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Pelicula');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Pelicula');
        }

        return $this;
    }

    /**
     * Use the Pelicula relation Pelicula object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \pelis\PeliculaQuery A secondary query class using the current class as primary query
     */
    public function usePeliculaQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPelicula($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Pelicula', '\pelis\PeliculaQuery');
    }

    /**
     * Filter the query by a related \pelis\Genero object
     *
     * @param \pelis\Genero|ObjectCollection $genero The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPeliculaGeneroQuery The current query, for fluid interface
     */
    public function filterByGenero($genero, $comparison = null)
    {
        if ($genero instanceof \pelis\Genero) {
            return $this
                ->addUsingAlias(PeliculaGeneroTableMap::COL_GENERO_ID, $genero->getId(), $comparison);
        } elseif ($genero instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PeliculaGeneroTableMap::COL_GENERO_ID, $genero->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByGenero() only accepts arguments of type \pelis\Genero or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Genero relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPeliculaGeneroQuery The current query, for fluid interface
     */
    public function joinGenero($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Genero');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Genero');
        }

        return $this;
    }

    /**
     * Use the Genero relation Genero object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \pelis\GeneroQuery A secondary query class using the current class as primary query
     */
    public function useGeneroQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinGenero($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Genero', '\pelis\GeneroQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPeliculaGenero $peliculaGenero Object to remove from the list of results
     *
     * @return $this|ChildPeliculaGeneroQuery The current query, for fluid interface
     */
    public function prune($peliculaGenero = null)
    {
        if ($peliculaGenero) {
            $this->addCond('pruneCond0', $this->getAliasedColName(PeliculaGeneroTableMap::COL_PELICULA_ID), $peliculaGenero->getPeliculaId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(PeliculaGeneroTableMap::COL_GENERO_ID), $peliculaGenero->getGeneroId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the pelicula_genero table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PeliculaGeneroTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PeliculaGeneroTableMap::clearInstancePool();
            PeliculaGeneroTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PeliculaGeneroTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PeliculaGeneroTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PeliculaGeneroTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PeliculaGeneroTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // PeliculaGeneroQuery
