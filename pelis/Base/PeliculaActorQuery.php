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
use pelis\PeliculaActor as ChildPeliculaActor;
use pelis\PeliculaActorQuery as ChildPeliculaActorQuery;
use pelis\Map\PeliculaActorTableMap;

/**
 * Base class that represents a query for the 'pelicula_actor' table.
 *
 *
 *
 * @method     ChildPeliculaActorQuery orderByPeliculaId($order = Criteria::ASC) Order by the pelicula_id column
 * @method     ChildPeliculaActorQuery orderByActorId($order = Criteria::ASC) Order by the actor_id column
 *
 * @method     ChildPeliculaActorQuery groupByPeliculaId() Group by the pelicula_id column
 * @method     ChildPeliculaActorQuery groupByActorId() Group by the actor_id column
 *
 * @method     ChildPeliculaActorQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPeliculaActorQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPeliculaActorQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPeliculaActorQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildPeliculaActorQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildPeliculaActorQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildPeliculaActorQuery leftJoinPelicula($relationAlias = null) Adds a LEFT JOIN clause to the query using the Pelicula relation
 * @method     ChildPeliculaActorQuery rightJoinPelicula($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Pelicula relation
 * @method     ChildPeliculaActorQuery innerJoinPelicula($relationAlias = null) Adds a INNER JOIN clause to the query using the Pelicula relation
 *
 * @method     ChildPeliculaActorQuery joinWithPelicula($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Pelicula relation
 *
 * @method     ChildPeliculaActorQuery leftJoinWithPelicula() Adds a LEFT JOIN clause and with to the query using the Pelicula relation
 * @method     ChildPeliculaActorQuery rightJoinWithPelicula() Adds a RIGHT JOIN clause and with to the query using the Pelicula relation
 * @method     ChildPeliculaActorQuery innerJoinWithPelicula() Adds a INNER JOIN clause and with to the query using the Pelicula relation
 *
 * @method     ChildPeliculaActorQuery leftJoinActor($relationAlias = null) Adds a LEFT JOIN clause to the query using the Actor relation
 * @method     ChildPeliculaActorQuery rightJoinActor($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Actor relation
 * @method     ChildPeliculaActorQuery innerJoinActor($relationAlias = null) Adds a INNER JOIN clause to the query using the Actor relation
 *
 * @method     ChildPeliculaActorQuery joinWithActor($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Actor relation
 *
 * @method     ChildPeliculaActorQuery leftJoinWithActor() Adds a LEFT JOIN clause and with to the query using the Actor relation
 * @method     ChildPeliculaActorQuery rightJoinWithActor() Adds a RIGHT JOIN clause and with to the query using the Actor relation
 * @method     ChildPeliculaActorQuery innerJoinWithActor() Adds a INNER JOIN clause and with to the query using the Actor relation
 *
 * @method     \pelis\PeliculaQuery|\pelis\ActorQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPeliculaActor findOne(ConnectionInterface $con = null) Return the first ChildPeliculaActor matching the query
 * @method     ChildPeliculaActor findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPeliculaActor matching the query, or a new ChildPeliculaActor object populated from the query conditions when no match is found
 *
 * @method     ChildPeliculaActor findOneByPeliculaId(int $pelicula_id) Return the first ChildPeliculaActor filtered by the pelicula_id column
 * @method     ChildPeliculaActor findOneByActorId(int $actor_id) Return the first ChildPeliculaActor filtered by the actor_id column *

 * @method     ChildPeliculaActor requirePk($key, ConnectionInterface $con = null) Return the ChildPeliculaActor by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPeliculaActor requireOne(ConnectionInterface $con = null) Return the first ChildPeliculaActor matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPeliculaActor requireOneByPeliculaId(int $pelicula_id) Return the first ChildPeliculaActor filtered by the pelicula_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPeliculaActor requireOneByActorId(int $actor_id) Return the first ChildPeliculaActor filtered by the actor_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPeliculaActor[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPeliculaActor objects based on current ModelCriteria
 * @method     ChildPeliculaActor[]|ObjectCollection findByPeliculaId(int $pelicula_id) Return ChildPeliculaActor objects filtered by the pelicula_id column
 * @method     ChildPeliculaActor[]|ObjectCollection findByActorId(int $actor_id) Return ChildPeliculaActor objects filtered by the actor_id column
 * @method     ChildPeliculaActor[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PeliculaActorQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \pelis\Base\PeliculaActorQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\pelis\\PeliculaActor', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPeliculaActorQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPeliculaActorQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPeliculaActorQuery) {
            return $criteria;
        }
        $query = new ChildPeliculaActorQuery();
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
     * @param array[$pelicula_id, $actor_id] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildPeliculaActor|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PeliculaActorTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = PeliculaActorTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]))))) {
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
     * @return ChildPeliculaActor A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT pelicula_id, actor_id FROM pelicula_actor WHERE pelicula_id = :p0 AND actor_id = :p1';
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
            /** @var ChildPeliculaActor $obj */
            $obj = new ChildPeliculaActor();
            $obj->hydrate($row);
            PeliculaActorTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]));
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
     * @return ChildPeliculaActor|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildPeliculaActorQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(PeliculaActorTableMap::COL_PELICULA_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(PeliculaActorTableMap::COL_ACTOR_ID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPeliculaActorQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(PeliculaActorTableMap::COL_PELICULA_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(PeliculaActorTableMap::COL_ACTOR_ID, $key[1], Criteria::EQUAL);
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
     * @return $this|ChildPeliculaActorQuery The current query, for fluid interface
     */
    public function filterByPeliculaId($peliculaId = null, $comparison = null)
    {
        if (is_array($peliculaId)) {
            $useMinMax = false;
            if (isset($peliculaId['min'])) {
                $this->addUsingAlias(PeliculaActorTableMap::COL_PELICULA_ID, $peliculaId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($peliculaId['max'])) {
                $this->addUsingAlias(PeliculaActorTableMap::COL_PELICULA_ID, $peliculaId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PeliculaActorTableMap::COL_PELICULA_ID, $peliculaId, $comparison);
    }

    /**
     * Filter the query on the actor_id column
     *
     * Example usage:
     * <code>
     * $query->filterByActorId(1234); // WHERE actor_id = 1234
     * $query->filterByActorId(array(12, 34)); // WHERE actor_id IN (12, 34)
     * $query->filterByActorId(array('min' => 12)); // WHERE actor_id > 12
     * </code>
     *
     * @see       filterByActor()
     *
     * @param     mixed $actorId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPeliculaActorQuery The current query, for fluid interface
     */
    public function filterByActorId($actorId = null, $comparison = null)
    {
        if (is_array($actorId)) {
            $useMinMax = false;
            if (isset($actorId['min'])) {
                $this->addUsingAlias(PeliculaActorTableMap::COL_ACTOR_ID, $actorId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($actorId['max'])) {
                $this->addUsingAlias(PeliculaActorTableMap::COL_ACTOR_ID, $actorId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PeliculaActorTableMap::COL_ACTOR_ID, $actorId, $comparison);
    }

    /**
     * Filter the query by a related \pelis\Pelicula object
     *
     * @param \pelis\Pelicula|ObjectCollection $pelicula The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPeliculaActorQuery The current query, for fluid interface
     */
    public function filterByPelicula($pelicula, $comparison = null)
    {
        if ($pelicula instanceof \pelis\Pelicula) {
            return $this
                ->addUsingAlias(PeliculaActorTableMap::COL_PELICULA_ID, $pelicula->getId(), $comparison);
        } elseif ($pelicula instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PeliculaActorTableMap::COL_PELICULA_ID, $pelicula->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildPeliculaActorQuery The current query, for fluid interface
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
     * Filter the query by a related \pelis\Actor object
     *
     * @param \pelis\Actor|ObjectCollection $actor The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPeliculaActorQuery The current query, for fluid interface
     */
    public function filterByActor($actor, $comparison = null)
    {
        if ($actor instanceof \pelis\Actor) {
            return $this
                ->addUsingAlias(PeliculaActorTableMap::COL_ACTOR_ID, $actor->getId(), $comparison);
        } elseif ($actor instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PeliculaActorTableMap::COL_ACTOR_ID, $actor->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByActor() only accepts arguments of type \pelis\Actor or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Actor relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPeliculaActorQuery The current query, for fluid interface
     */
    public function joinActor($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Actor');

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
            $this->addJoinObject($join, 'Actor');
        }

        return $this;
    }

    /**
     * Use the Actor relation Actor object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \pelis\ActorQuery A secondary query class using the current class as primary query
     */
    public function useActorQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinActor($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Actor', '\pelis\ActorQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPeliculaActor $peliculaActor Object to remove from the list of results
     *
     * @return $this|ChildPeliculaActorQuery The current query, for fluid interface
     */
    public function prune($peliculaActor = null)
    {
        if ($peliculaActor) {
            $this->addCond('pruneCond0', $this->getAliasedColName(PeliculaActorTableMap::COL_PELICULA_ID), $peliculaActor->getPeliculaId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(PeliculaActorTableMap::COL_ACTOR_ID), $peliculaActor->getActorId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the pelicula_actor table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PeliculaActorTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PeliculaActorTableMap::clearInstancePool();
            PeliculaActorTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PeliculaActorTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PeliculaActorTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PeliculaActorTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PeliculaActorTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // PeliculaActorQuery
